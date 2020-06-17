<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use Mail; 
use Storage;
use PDF;
use Carbon\Carbon;
use Config;
Use Exception;
use App\Rules\{
    ReportingMonthRule,
    UpdateReportRule
};
use App\{
    User,
    Report,
    ReportDetail,
    UploadedFile,
    CompanyCategory,
    ErrorLog
};
use App\Mail\{
    InspectionReportForReview,
    AreaOwnerApprovedReport,
    ReturnedInspection,
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
            'department' => 'required',
            'category' => 'required',
            'operation_line' => 'required_if:category,==,1',
            'date_of_inspection' => 'required',
            'area' => ['required', new ReportingMonthRule($request->company,$request->location,$request->operation_line,$request->category,$request->area,$request->date_of_inspection)],
            'process_owner' => 'required',
            'start_time_of_inspection' => 'required',
            'end_time_of_inspection' => 'required',
            'checklist' => 'required',
            'points' => 'required',
            'points.*' => 'required',
            'accompanied_by' => 'required',
            'department_head' => 'required',
            'bu_head' => 'required',
            'group_president' => 'required',
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
                'reporting_year' => $date->isoFormat('Y'),
                'department_id' => $request->department,
                'accompanied_by' => $request->accompanied_by,
                'department_head_id' => $request->department_head,
                'cluster_head_id' => $request->bu_head,
                'group_president_id' => $request->group_president,
                'is_draft' => 1
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
                            'previous_rating' => 0,
                            'recurrence_number' => $checklist->recurrence_number,
                            'criticality' => $checklist->criticality
                        ]
                    );
                    $ids[] = $report->id;
                }
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
            // Mail::to(User::findOrFail($request->process_owner))->send(new InspectionReportForReview(Auth::user()->id, $request->area, $r->id));

            DB::commit();
            return $r;

        } catch (Exception $e) {
            DB::rollBack();
            $error = ErrorLog::create(['message' => $e->getMessage(),'model' => 'App\Report','user_id' => Auth::user()->id]);
            DB::commit();
            return response()->json(['errors' => [ 'server_error' => 'Please seek an assistance from your system administrator.']], 500);
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
            'year' => 'required',
            'company' => 'required',
            'location' => 'required',
            'category' => 'required',
            'operation_line' => 'required_if:category,==,1',
            'area' => 'required'
        ]);

        return Report::with('company', 'location', 'operationLine', 'category', 'area', 'inspector', 'processOwner', 'reportDetail')
        ->where('reporting_year',$request->year)
        ->where('company_id', $request->company)
        ->where('location_id', $request->location)
        ->where('category_id', $request->category)
        ->when($request->operation_line, function($q) use ($request){
            $q->where('operation_line_id', $request->operation_line);
        })
        ->when($request->area !== 'ALL', function ($q) use($request){
            $q->where('area_id', $request->area);
        })
        ->where('status',Config::get('constants.status.final'))
        ->get()
        ->groupBy('area_id');
    }


    /**
     * Generate filtered reports to PDF
     *
     * @return \Illuminate\Http\Response
     */
    
    public function filteredReportToPDF($year,$company,$location,$category,$operation_line,$area){

        $data = Report::with('company', 'location', 'operationLine', 'category', 'area', 'inspector', 'processOwner', 'reportDetail')
        ->where('reporting_year',$year)
        ->where('company_id', $company)
        ->where('location_id', $location)
        ->where('category_id', $category)
        ->when($operation_line !== '0', function($q) use ($operation_line){
            $q->where('operation_line_id', $operation_line);
        })
        ->when($area !== 'ALL', function ($q) use($area){
            $q->where('area_id', $area);
        })
        ->where('status',Config::get('constants.status.final'))
        ->get()
        ->groupBy('area_id')
        ->toArray();

        $pdf = PDF::loadView('report.per-bu-pdf', compact('data'))->setPaper('a4', 'landscape');

        return $pdf->stream('report.per-bu-pdf');
    }


    /**
     *Fetch All reports per user  
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function getReportsPerUser($reportId){
        return Report::with('company','location','department','category','operationLine', 'area', 'inspector',
         'processOwner','departmentHead','reportDetail.uploadedFiles')
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
            foreach($request->comments as $comment){
                $uploadedFile = UploadedFile::findOrFail($comment['id']);
                $uploadedFile->update(['comment' => $comment['text']]);
            }
            Report::whereIn('id', $request->ids)->update([
                    'status' => 4, 
                    'ratings' => number_format((float)$request->final_rating, 2, '.', '')
                ]);
            // Send email to Process owner and Department Head
            $report = Report::with('inspector','processOwner','departmentHead')->where('id',$request->ids[0])->first();
            Mail::to([$report->processOwner,$report->departmentHead,$report->inspector])->send(new AreaOwnerApprovedReport($report->process_owner_id, $report->id));

            DB::commit();
            return  $report;
        } catch (Exception $e) {
            DB::rollBack();
            $error = ErrorLog::create(['message' => $e->getMessage(),'model' => 'App\Report','user_id' => Auth::user()->id]);
            DB::commit();
            return response()->json(['errors' => [ 'server_error' => 'Please seek an assistance from your system administrator.']], 500);
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
            'non_acceptance_reason' => 'required'
        ]);

        DB::beginTransaction();
        try {
            $ids = [];
            foreach($request->comments as $comment){
                $uploadedFile = UploadedFile::findOrFail($comment['id']);
                $uploadedFile->update(['comment' => $comment['text']]);
                $ids[] = $comment['id'];
            }
            Report::whereIn('id', $request->ids)->update([
                'status' => 2, //Status for checking
                'non_acceptance_reason' => $request->non_acceptance_reason,
                'non_acceptance_date' => Carbon::now()
                ]); 
            // Send email to inspector
            $report = Report::findOrFail($request->ids[0]);
            Mail::to(User::findOrFail($report->inspector_id))->send(new ReturnedInspection($report->process_owner_id, $report->area_id , $report->id));

            DB::commit();
            return  $report;

        } catch (Exception $e) {
            DB::rollBack();
            $error = ErrorLog::create(['message' => $e->getMessage(),'model' => 'App\Report','user_id' => Auth::user()->id]);
            DB::commit();
            return response()->json(['errors' => [ 'server_error' => 'Please seek an assistance from your system administrator.']], 500);
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
            $error = ErrorLog::create(['message' => $e->getMessage(),'model' => 'App\Report','user_id' => Auth::user()->id]);
            DB::commit();
            return response()->json(['errors' => [ 'server_error' => 'Please seek an assistance from your system administrator.']], 500);
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
            $report_details = json_decode($request->report_details);
            foreach($report->reportDetail as $key => $value){
                $value->update([
                    'points' => explode(',',$request->points)[$key],
                    'previous_rating' => 0,
                    'recurrence_number' =>  $report_details[$key]->recurrence_number,
                    'criticality' => $report_details[$key]->criticality,
                    ]);
            }
            if($request->descriptions){
                foreach(json_decode($request->descriptions) as $description){
                    $uploadedFile = UploadedFile::findOrFail($description->id);
                    $uploadedFile->update(['description' => $description->text]);
                }
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
            $error = ErrorLog::create(['message' => $e->getMessage(),'model' => 'App\Report','user_id' => Auth::user()->id]);
            DB::commit();
            return response()->json(['errors' => [ 'server_error' => 'Please seek an assistance from your system administrator.']], 500);
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
     * Generate trend and analysis to PDF
     *
     * @return \Illuminate\Http\Response
     */
    
    public function generatePDF(){ 
        $pdf = PDF::loadView('report.pdf', ['data' => $this->trendAndAnalysisData()])->setPaper('a4', 'landscape');

        return $pdf->stream('report.pdf');
    }


    /**
     * Display notification index
     *
     * @return \Illuminate\Http\Response
     */
    public function reportsNotificationIndex(){
        return view('report.notifications');
    }

    /**
     * Get notification or report for process owner and inspector
     *
     * @return \Illuminate\Http\Response
     */

    public function getReportsNotification(){
        if(Auth::user()->level() == 2 && Auth::user()->level() == 3){
            $reports = Report::with('company', 'location', 'department', 'operationLine', 'category', 'area', 'inspector', 'processOwner', 'reportDetail')
            ->when(Auth::user()->level() == 2, function ($q){
                $q->where('status' ,1)
                ->whereNull('is_draft')
                ->where('process_owner_id', Auth::user()->id);
            })->when(Auth::user()->level() == 3, function ($q){
                $q->where('status',2)
                ->where('inspector_id', Auth::user()->id);
            })->get();
    
            return $reports;
        }
    }

    /**
     * Display drafts page
     *
     * @return \Illuminate\Http\Response
     */
    public function drafts(){
        return view('report.drafts');
    }

    /**
     * Fetch all draft report per user  
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function draftsData(){
        return Report::with('company', 'location', 'department', 'operationLine', 'category', 'area', 'inspector', 'processOwner', 'reportDetail')
                ->where('inspector_id', Auth::user()->id)
                ->whereNotNull('is_draft')
                ->orderBy('id', 'desc')->get();
    }


    /**
     * Submit report, Remove tagged as drafts
     *
     * @return \Illuminate\Http\Response
     */
    public function submit(Request $request){
        $report = Report::with('processOwner')->where('id',$request->id)->first();

        DB::beginTransaction();
        try {
            $report->update([
                'date_submitted' => Carbon::now(),
                'is_draft' => null
            ]);
            // Send email to process owner
            Mail::to($report->processOwner)->send(new InspectionReportForReview(Auth::user()->id, $report->area_id, $report->id));

            DB::commit();;
            return $this->getReportsPerUser($report->id);
        } catch (Exception $e) {
            DB::rollBack();
            $error = ErrorLog::create(['message' => $e->getMessage(),'model' => 'App\Report','user_id' => Auth::user()->id]);
            DB::commit();
            return response()->json(['errors' => [ 'server_error' => 'Please seek an assistance from your system administrator.']], 500);
        }
    }

    /**
     * Revise report, Tagged reports as final
     *
     * @return \Illuminate\Http\Response
     */
    public function revised(Request $request){
        $request->validate([
            'id' => 'required',
            'final_rating' => 'required'
        ]);

        DB::beginTransaction();
        try {
            Report::where('id', $request->id)->update([
                    'status' => 4, 
                    'ratings' => number_format((float)$request->final_rating, 2, '.', ''),
                    'date_resubmit' => Carbon::now()
                ]);
            // Send email to Process owner and Department Head
            $report = Report::with('inspector','processOwner','departmentHead')->where('id',$request->id)->first();
            Mail::to([$report->processOwner,$report->departmentHead,$report->inspector])->send(new AreaOwnerApprovedReport($report->process_owner_id, $report->id));

            DB::commit();
            return $this->getReportsPerUser($report->id);
        } catch (Exception $e) {
            DB::rollBack();
            $error = ErrorLog::create(['message' => $e->getMessage(),'model' => 'App\Report','user_id' => Auth::user()->id]);
            DB::commit();
            return response()->json(['errors' => [ 'server_error' => 'Please seek an assistance from your system administrator.']], 500);

        }
    }

    /**
     * Display summary page
     *
     * @return \Illuminate\Http\Response
     */
    public function summary(){
        return view('report.summary');
    }

    /**
     * Query to get summary per BU
     *
     * @return \Illuminate\Http\Response
     */

    public function summaryPerBUQuery($year){
        $final_array = [];
        $reports = Report::with('company', 'location', 'operationLine', 'category', 'area', 'inspector', 'processOwner', 'reportDetail')
        ->where('reporting_year',$year)
        ->where('status',Config::get('constants.status.final'))
        ->get()
        ->groupBy(['company_id','location_id'])
        ->toArray();

        if($reports){
            foreach($reports as $report){
                foreach($report as $r){
                    array_push($final_array,$r);
                }
            }
        }
        return $final_array;
    }
    /**
     * Summary report per BU
     *
     * @return \Illuminate\Http\Response
     */
    public function summaryPerBU(Request $request){
        return $this->summaryPerBUQuery($request->year);
    }

    /**
     * Generate summary for BU to PDF
     *
     * @return \Illuminate\Http\Response
     */
    public function summaryPerBUToPDF($year){
        $data = $this->summaryPerBUQuery($year);
        $pdf = PDF::loadView('report.summary-per-bu-pdf', compact('data'))->setPaper('a4', 'landscape');
        
        return $pdf->stream('report.summary-per-bu-pdf');
    }
    
    /**
     * Generate summary for all BU to PDF
     *
     * @return \Illuminate\Http\Response
     */
    public function rankingPerBUToPDF($year){
        $data = $this->summaryPerBUQuery($year);
        $pdf = PDF::loadView('report.ranking-per-bu-pdf', compact('data'))->setPaper('a4', 'landscape');
        
        return $pdf->stream('report.ranking-per-bu-pdf');
    }

     /**
     * Displaying inspection history page
     *
     * @return \Illuminate\Http\Response
     */
    public function inspectionHistory($reportId){
        return view('report.inspection-history', compact('reportId'));
    }

    /**
     * Get last 3 inspections
     *
     * @return \Illuminate\Http\Response
     */
    public function inspectionHistoryData(Report $report){

        $report = Report::with('company','location','department','category','operationLine', 'area', 'inspector',
        'processOwner','departmentHead','reportDetail.uploadedFiles')
        ->where('company_id', $report->company_id)
        ->where('location_id', $report->location_id)
        ->when($report->operation_line_id, function ($q) { 
            $q->where('operation_line_id', $report->operation_line_id);
        })
        ->where('category_id', $report->category_id)
        ->where('area_id', $report->area_id)
        ->where('status',Config::get('constants.status.final'))
        ->where('id', '!=',$report->id)
        ->orderBy('id','desc')
        ->take('3')
        ->get();

        return $report;
    }

    /**
     * Get Previous month report
     *
     * @return \Illuminate\Http\Response
     */
    public function previousMonthRanking(){

        $now = Carbon::now();
        $month = $now->month == 1 ? 12 : $now->month -1;
        $year = $now->month == 1 ? $now->year -1 : $now->year;

        $final_array = [];
        $reports = Report::with('company', 'location', 'operationLine', 'category', 'area', 'inspector', 'processOwner', 'reportDetail')
        ->where('reporting_month',$month)
        ->where('reporting_year',$year)
        ->where('status',Config::get('constants.status.final'))
        ->get()
        ->groupBy(['company_id','location_id'])
        ->toArray();

        if($reports){
            foreach($reports as $report){
                foreach($report as $r){
                    array_push($final_array,$r);
                }
            }
        }
        return $final_array;
    }
}
