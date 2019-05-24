<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
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
            $report_id = [];

            // Script to assign report id per attachment
            foreach(explode(',',$request->attachment_ids) as $a => $i){
                $startKey = 0;
                if($a == 0){
                    $report_id[] = $ids[0];
                }else{
                    if($i[$startKey] == $i){
                        $report_id[] = $ids[$startKey];
                    }else{
                        $startKey = $startKey + 1;
                        $report_id[] = $ids[$startKey];
                    }
                }
            }

            if($request->has('attachments')){
                $attachments = $request->file('attachments');
                foreach($attachments as $key => $attachment){
                    $filename = $attachment->getClientOriginalName();
                    $path = Storage::disk('public')->put('report', $attachment);
    
                    $uploadedFile = $this->uploadFiles($request->process_owner,$report_id[$key],explode(',',$request->attachment_ids)[$key], $filename,$path);
                }

            }
            DB::commit();
            return Report::all();

        } catch (Exception $e) {
            DB::rollBack();
        }
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
        return Report::with('uploadedFiles')
        ->where('company_id', $companyId)
        ->where('location_id', $locationId)
        ->where('operation_line_id', $operationLineId)
        ->where('category_id', $categoryId)
        ->where('area_id', $areaId)
        ->where('process_owner', Auth::user()->id)
        ->where('status', 1)->get();
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

        return Report::whereIn('id', $request->ids)->update(['status' => 3, 'ratings' => $request->final_rating]);
    }

    /**
     *For checking reports per user  
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function checkingReporPerUser(Request $request){
        $request->validate([
            'comment.*' => 'required',
        ]);
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
