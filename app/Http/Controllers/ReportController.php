<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use App\{
    Report
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
            // 'attachments' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $area_details = [];
            foreach($request->selecwted_checklist as $checklist){
                $area_details[] = [
                    'company_id' => $request->company,
                    'location_id' => $request->location,
                    'operation_line_id' => $request->operation_line,
                    'category' => $request->category,
                    'area' => $request->area,
                    'process_owner' => $request->process_owner,
                    'inspector' => Auth::user()->id(),
                    'date_of_inspection' => $request->date_of_inspection,
                    'time_of_inspection' => $request->time_of_inspection,
                    'checklist_id' => $checklist['id'],
                    'checklist_batch' => $checklist['batch'],
                    'name' => $checklist['requirement']. ' - '.$checklist['description'],
                    'points' => 2,
                    'status' => 1 
                ];
            }
            if(Report::insert($area_details)){
                
                DB::commit();
                return Report::all();
            }
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
        return Report::where('company_id', $companyId)
        ->where('location_id', $locationId)
        ->where('operation_line_id', $operationLineId)
        ->where('category_id', $categoryId)
        ->where('area_id', $areaId)
        ->where('process_owner', Auth::user()->id)
        ->where('status', 1)->get();
    }
}
