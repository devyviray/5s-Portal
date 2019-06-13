<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use Mail; 
use Storage;
use Carbon\Carbon;
use App\Rules\ReportingMonthRule;
use App\{
    User,
    Report,
    ReportDetail,
    UploadedFile
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
    public function index()
    {
        return view('report.index');
    }

    /**
     * Get all Reports
     *
     * @return \Illuminate\Http\Response
     */
    public function indexData()
    {
       return Report::with('company', 'location', 'operationLine', 'category', 'area', 'inspector', 'processOwner', 'reportDetail')
        ->when(Auth::user()->level() < 3, function($q){
            $q->where('process_owner_id', Auth::user()->id);
        })->orderBy('id', 'desc')->get();
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
            // 'operation_line' => 'required',
            'category' => 'required',
            'area' => 'required',
            'process_owner' => ['required', new ReportingMonthRule($request->company,$request->location,$request->operation_line,$request->category,$request->area,$request->date_of_inspection)],
            'date_of_inspection' => 'required',
            'time_of_inspection' => 'required',
            'checklist' => 'required',
            'points' => 'required',
            'points.*' => 'required',
            // 'attachments' => 'required',
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
                'time_of_inspection' => $request->time_of_inspection,
                'status' => 1,
                'reporting_month' => $date->isoFormat('M')
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
            Mail::to(User::findOrFail($request->process_owner))->send(new ReportCreated(Auth::user()->id, $r->id));

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
            Report::whereIn('id', $request->ids)->update(['status' => 4, 'ratings' => $request->final_rating]);

            // Send email to inspector
            $report = Report::findOrFail($request->ids[0]);
            Mail::to(User::findOrFail($report->inspector_id))->send(new ProcessOwnerApprovedReport($report->process_owner_id, $report->id));

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
            Mail::to(User::findOrFail($report->inspector_id))->send(new ProcessOwnerForCheckingReport($report->process_owner_id, $report->id));

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
        $uploadedFile->save();
    }
}
