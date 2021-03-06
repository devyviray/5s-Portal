<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use Mail; 
use Storage;
use PDF;
use Carbon\Carbon;
use App\Rules\{
    ReportingMonthRule,
    UpdateReportRule
};
use App\{
    User,
    Report,
    ReportDetail,
    UploadedFile,
    CompanyCategory
};
use App\Mail\{
    ReportCreated,
    ProcessOwnerApprovedReport,
    ProcessOwnerForCheckingReport,
    InspectorValidateReport
};

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($company, $location)
    {
        return view('report.index', compact('company', 'location'));
    }

    /**
     * Get all Reports
     *
     * @return \Illuminate\Http\Response
     */
    public function indexData($company, $location)
    {
        $user = Auth::user();
        return Report::with('company', 'location', 'operationLine', 'category', 'area', 'inspector', 'processOwner', 'reportDetail')
            ->when(Auth::user()->level() > 2, function($q) use ($company, $location){
                $q->where('company_id', $company)
                ->where('location_id', $location);
            })
            ->when(Auth::user()->level() == 2, function($q) use ($user){
                $q->where('process_owner_id', $user->id);
            })->when(Auth::user()->level() == 1, function($query) use ($user){
                $query->whereHas('processOwner', function($q) use ($user){
                    $q->where('department_id',$user->department_id);
                })
                ->where('company_id', $user->companies[0]->id)
                ->where('location_id', $user->location->id);
                
            })
            ->orderBy('id', 'desc')->get();
    }

    /**
     * Display adding report  page
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){

        return view('report.form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'company' => 'required',
            'location' => 'required',
            'category' => 'required',
            'operation_line' => 'required_if:category,==,1',
            'area' => ['required', new ReportingMonthRule($request->company,$request->location,$request->operation_line,$request->category,$request->area,$request->date_of_inspection)],
            'process_owner' => 'required',
            'date_of_inspection' => 'required',
            'start_time_of_inspection' => 'required',
            'end_time_of_inspection' => 'required',
            'checklist' => 'required',
            'points' => 'required',
            'points.*' => 'required',
            'topManagement' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $date = Carbon::parse($request->date_of_inspection);

            $data = [
                'company_id' => $request->company,
                'location_id' => $request->location,
                'operation_line_id' => $request->operation_line,
                'category_id' => $request->category,
                'area_id' => $request->area,
                'process_owner_id' => $request->process_owner,
                'inspector_id' => Auth::user()->id,
                'date_of_inspection' => $request->date_of_inspection,
                'start_time_of_inspection' => $request->start_time_of_inspection,
                'end_time_of_inspection' => $request->end_time_of_inspection,
                'status' => 1,
                'reporting_month' => $date->isoFormat('M'),
                'reporting_year' => $date->isoFormat('Y')
            ];
            if($r = Report::create($data)){
                $ids = [];
                foreach(json_decode($request->checklist) as $key => $checklist){
                    $report = ReportDetail::create(
                        [
                            'checklist_id' => $checklist->id,
                            'checklist_batch' => $checklist->batch,
                            'report_id' => $r->id,
                            'name' => $checklist->requirement. ' - '.$checklist->description,
                            'points' => explode(',',$request->points)[$key],
                        ]
                    );
                    $ids[] = $report->id;
                }
                $topManagementId = strpos($request->topManagement, ',') !== true ? explode(',',$request->topManagement) : $request->topManagement;
                $r->topManagements()->sync($topManagementId);
            }

            if($request->has('attachments')){
                $attachments = $request->file('attachments');
                foreach($attachments as $key => $attachment){
                    $filename = $attachment->getClientOriginalName();
                    $path = Storage::disk('public')->put('report', $attachment);
    
                    $uploadedFile = $this->uploadFiles($request->process_owner,$ids[explode(',',$request->attachment_index)[$key]],explode(',',$request->attachment_ids)[$key], $filename,$path);
                }
            }
            // Send email to process owner
            Mail::to(User::findOrFail($request->process_owner))->send(new ReportCreated(Auth::user()->id, $request->area, $r->id));

            DB::commit();

            return Report::all();

        } catch (Exception $e) {
            DB::rollBack();
        }
    }

     /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($reportId)
    {
        $userId = Auth::user()->id;
        return view('report.view', compact('reportId', 'userId'));
    }

    /**
     *Fetch All Filtered Reports  
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    
    public function getFilteredReports(Request $request){
        $request->validate([
            'company' => 'required',
            'location' => 'required',
        ]);

        return Report::with('company', 'location', 'operationLine', 'category', 'area', 'inspector', 'processOwner', 'reportDetail')
        ->where('company_id', $request->company)
        ->where('location_id', $request->location)
        ->when($request->category, function($q) use ($request){
            $q->where('category_id', $request->category);
        })->when($request->operation_line, function($q) use ($request){
            $q->where('operation_line_id', $request->operation_line);
        })->when($request->area, function($q) use ($request){
            $q->where('area_id', $request->area);
        })->when(Auth::user()->level() < 3, function($q){
            $q->where('process_owner_id', Auth::user()->id);
        })->orderBy('id', 'desc')->get();
    }

    /**
     *Fetch All reports per user  
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function getReportsPerUser($reportId){
        return Report::with('company','category','operationLine', 'area', 'inspector', 'reportDetail.uploadedFiles')
        ->where('id', $reportId)->get();
    }

     /**
     *Approve reports per user  
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function approveReportPerUser(Request $request){
        $request->validate([
            'ids' => 'required',
            'final_rating' => 'required'
        ]);

        DB::beginTransaction();
        try {
            Report::whereIn('id', $request->ids)->update(['status' => 4, 'ratings' => number_format((float)$request->final_rating, 2, '.', '')]);

            // Send email to inspector and top management
            $report = Report::with('inspector','topManagements')->where('id',$request->ids[0])->first();
            Mail::to($report->topManagements->push($report->inspector))->send(new ProcessOwnerApprovedReport($report->process_owner_id, $report->id));

            DB::commit();

            return  $report;

        } catch (Exception $e) {
            DB::rollBack();
        }
    }

    /**
     *For checking reports per user  
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function checkingReportPerUser(Request $request){
        $request->validate([
            'ids' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $ids = [];
            foreach($request->comments as $comment){
                $uploadedFile = UploadedFile::findOrFail($comment['id']);
                $uploadedFile->update(['comment' => $comment['text']]);
                $ids[] = $comment['id'];
            }
            Report::whereIn('id', $request->ids)->update(['status' => 2]); //Status for checking 
            // Send email to inspector
            $report = Report::findOrFail($request->ids[0]);
            Mail::to(User::findOrFail($report->inspector_id))->send(new ProcessOwnerForCheckingReport($report->process_owner_id, $report->area_id , $report->id));

            DB::commit();
            return  $report;

        } catch (Exception $e) {
            DB::rollBack();
        }
    
    }

    /**
     * Display validate page
     *
     * @return \Illuminate\Http\Response
     */
    public function validateIndex($reportId){

        return view('report.verified', compact('reportId'));

    }

    /**
     * Validate Report
     *
     * @return \Illuminate\Http\Response
     */

    public function validateReportPerUser(Request $request){
        $request->validate([
            'ids' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $r = Report::with('reportDetail')->where('id', $request->ids[0])->first();
            foreach($r->reportDetail as $key => $value){
                $value->update(['points' => $request->points[$key]]);
            }

            Report::where('id', $request->ids[0])->update(['status' => 3]); //Report validated 

            if($request->accepted_ids){ //Accept comment
                UploadedFile::whereIn('id', $request->accepted_ids)->update(['status' => '1']);
            }
            if($request->rejected_ids){ //Reject comment
                UploadedFile::whereIn('id', $request->rejected_ids)->update(['status' => '2']);
            }

            // Send email to inspector
            $report = Report::findOrFail($request->ids[0]);
            Mail::to(User::findOrFail($report->process_owner_id))->send(new InspectorValidateReport($report->process_owner_id, $report->id));

            DB::commit();

            return Report::with('company','category','operationLine', 'area', 'inspector', 'reportDetail.uploadedFiles')->where('id', $request->ids[0])->get();

        } catch (Exception $e) {
            DB::rollBack();
        }
    }

    /**
     * Display validate page
     *
     * @return \Illuminate\Http\Response
     */
    public function editIndex($reportId){

        return view('report.edit', compact('reportId'));

    }

    /**
     * Update Report
     *
     * @return \Illuminate\Http\Response
     */

    public function updateReport(Request $request){
        $request->validate([
            'id' => ['required', new UpdateReportRule],
        ]);

        DB::beginTransaction();
        try {
            if($request->deleted_uploaded_id){
                $deletedUploadedId =  strpos($request->deleted_uploaded_id, ',') !== true ? explode(',',$request->deleted_uploaded_id) : $request->deleted_uploaded_id;
                UploadedFile::whereIn('id', $deletedUploadedId)->get()
                ->map(function($photo) {
                    $photo->delete();
                });
            }

            $report = Report::with('reportDetail')->where('id', $request->id)->first();
            foreach($report->reportDetail as $key => $value){
                $value->update(['points' => explode(',',$request->points)[$key]]);
            }
            if($request->has('attachments')){
                $attachments = $request->file('attachments');
                $attachmentIds = strpos($request->attachment_ids, ',') !== true ? explode(',',$request->attachment_ids) : $request->attachment_ids;
                $checklistIds = strpos($request->checklist_ids, ',') !== true ? explode(',',$request->checklist_ids) : $request->checklist_ids;
                foreach($attachments as $key => $attachment){
                    $filename = $attachment->getClientOriginalName();
                    $path = Storage::disk('public')->put('report', $attachment);

                    $uploadedFile = $this->uploadFiles($request->process_owner_id,  $attachmentIds[$key],$checklistIds[$key], $filename,$path);
                }
            }
            
            DB::commit();
            return $report;

        } catch (Exception $e) {
            DB::rollBack();
        }
        
    }


     /**
     * Display trend and analysis page
     *
     * @return \Illuminate\Http\Response
     */
    public function trendIndex(){

        return view('report.trend');

    }

    /**
     * Uploading files for Reports
     *
     * @return \Illuminate\Http\Response
     */
    public function uploadFiles($user_id,$report_detail_id,$checklist_id,$filename,$path)
    {
        $uploadedFile = new UploadedFile;
        $uploadedFile->user_id = $user_id;
        $uploadedFile->report_detail_id = $report_detail_id;
        $uploadedFile->checklist_id = $checklist_id;
        $uploadedFile->uploader_id = Auth::user()->id;
        $uploadedFile->file_name = $filename;
        $uploadedFile->file_path = $path;
        $uploadedFile->model = 'App\Report';
        $uploadedFile->status = '0';
        $uploadedFile->save();
    }


     /**
     *  Get Trend and analysis data
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function trendAndAnalysisData(){
        
        $query = CompanyCategory::with('company', 'location', 'operationLine')
        ->whereNotNull('operation_line_id')->get();

        foreach($query as $q){
            $q['reports'] = Report::where('company_id',$q->company_id)
            ->where('location_id', $q->location_id)
            ->where('operation_line_id',$q->operation_line_id)
            ->where('reporting_year', Carbon::now()->year)
            ->orderBy('reporting_month', 'asc')->get();
        }

        return $query;

    }

     /**
     *  Get Performace Evaluation Rating data
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function performaceEvaluationRating($company, $location){
        return Report::where('company_id',$company)
            ->where('location_id', $location)
            ->where('reporting_year', Carbon::now()->year)
            ->where('status', 4) //Final rating
            ->whereNotNull('operation_line_id')
            ->orderBy('reporting_month', 'asc')->get();
    }

    /**
     * Generate trend and analysis to PDF
     *
     * @return \Illuminate\Http\Response
     */
    
    public function generatePDF(){ 
        $pdf = PDF::loadView('report.pdf', ['data' => $this->trendAndAnalysisData()])->setPaper('a4', 'landscape');

        return $pdf->stream('report.pdf');
    }

    /**
     * Get notification or report for process owner
     *
     * @return \Illuminate\Http\Response
     */

    public function getReportsNotification(){
        if(Auth::user()->level() !== 1 && Auth::user()->level() !== 4 && Auth::user()->level() !== 5 && Auth::user()->level() !== 6){
            $reports = Report::when(Auth::user()->level() == 2, function ($q){
                $q->where('status' ,1)->where('process_owner_id', Auth::user()->id);
            })->when(Auth::user()->level() == 3, function ($q){
                $q->where('status',2)->where('inspector_id', Auth::user()->id);
            })->get();
    
            return count($reports);
        }
    }
}
