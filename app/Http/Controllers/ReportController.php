<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use Carbon\Carbon;
use Storage;
use App\{
    Report,
    UploadedFile
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
        return Report::with('company', 'location', 'operationLine', 'category', 'area', 'inspector')
        ->where('process_owner', Auth::user()->id)
        ->where('status', 1)->get()
        ->groupBy(function($val){
            return Carbon::parse($val->date_of_inspection)->format('m');
        });
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
            'operation_line' => 'required',
            'category' => 'required',
            'area' => 'required',
            'process_owner' => 'required',
            'date_of_inspection' => 'required',
            'time_of_inspection' => 'required',
            'checklist' => 'required',
            'points' => 'required',
            'points.*' => 'required',
            // 'attachments' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $ids = [];
            foreach(json_decode($request->checklist) as $key => $checklist){
                $report = Report::create(
                    [
                        'company_id' => $request->company,
                        'location_id' => $request->location,
                        'operation_line_id' => $request->operation_line,
                        'category_id' => $request->category,
                        'area_id' => $request->area,
                        'process_owner' => $request->process_owner,
                        'inspector' => Auth::user()->id,
                        'date_of_inspection' => $request->date_of_inspection,
                        'time_of_inspection' => $request->time_of_inspection,
                        'checklist_id' => $checklist->id,
                        'checklist_batch' => $checklist->batch,
                        'name' => $checklist->requirement. ' - '.$checklist->description,
                        'points' => explode(',',$request->points)[$key],
                        'status' => 1
                    ]
                );
                $ids[] = $report->id;
            }
            
            if($request->has('attachments')){
                $attachments = $request->file('attachments');
                foreach($attachments as $key => $attachment){
                    $filename = $attachment->getClientOriginalName();
                    $path = Storage::disk('public')->put('report', $attachment);
    
                    $uploadedFile = $this->uploadFiles($request->process_owner,$ids[explode(',',$request->attachment_index)[$key]],explode(',',$request->attachment_ids)[$key], $filename,$path);
                }

            }
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
    public function show($companyId,$locationId,$operationLineId,$categoryId,$areaId)
    {
        return view('report.view', compact('companyId', 'locationId', 'operationLineId','categoryId','areaId'));
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     *Fetch All reports per user  
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function getReportsPerUser($companyId, $locationId, $operationLineId,$categoryId, $areaId){
        return Report::with('uploadedFiles', 'company','category','operationLine', 'area', 'inspector')
        ->where('company_id', $companyId)
        ->where('location_id', $locationId)
        ->where('operation_line_id', $operationLineId)
        ->where('category_id', $categoryId)
        ->where('area_id', $areaId)
        ->where('process_owner', Auth::user()->id)
        // ->where('status', 1)->get();
        ->get();
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
            $report = Report::whereIn('id', $request->ids)->update(['status' => 3, 'ratings' => $request->final_rating]);
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
    public function checkingReporPerUser(Request $request){
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
            $report = Report::whereIn('id', $request->ids)->update(['status' => 2]); //Status for checking 
            DB::commit();
            return  $report;

        } catch (Exception $e) {
            DB::rollBack();
        }
    
    }

    /**
     * Uploading files for Reports
     *
     * @return \Illuminate\Http\Response
     */
    public function uploadFiles($user_id,$report_id,$checklist_id,$filename,$path)
    {
        $uploadedFile = new UploadedFile;
        $uploadedFile->user_id = $user_id;
        $uploadedFile->report_id = $report_id;
        $uploadedFile->checklist_id = $checklist_id;
        $uploadedFile->uploader_id = Auth::user()->id;
        $uploadedFile->file_name = $filename;
        $uploadedFile->file_path = $path;
        $uploadedFile->model = 'App\Report';
        $uploadedFile->save();
    }
}
