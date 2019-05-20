<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\{
    CompanyArea
};

class CompanyAreaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('company-area.index');
    }

    /**
     * Get all Company Area
     *
     * @return \Illuminate\Http\Response
     */
    public function indexData()
    {
        return CompanyArea::with('company', 'location', 'operationLine', 'category', 'area')->orderBy('id','desc')->get();
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
            'areas' => 'required',
            'category' => 'required',
            'company' => 'required',
            'location' => 'required',
            'operation_line' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $area_details = [];
            foreach($request->areas as $area){
                $area_details[] = [
                    'company_id' => $request->company,
                    'location_id' => $request->location,
                    'operation_line_id' => $request->operation_line,
                    'category_id' => $request->category,
                    'area_id' => $area
                ];
            }
            if($companyArea = CompanyArea::insert($area_details)){
                
                DB::commit();
                return CompanyArea::all();
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
    public function update(Request $request, CompanyArea $companyArea)
    {
        $request->validate([
            'area_id' => 'required',
            'category_id' => 'required',
            'company_id' => 'required',
            'location_id' => 'required',
            'operation_line_id' => 'required',
        ]);

        DB::beginTransaction();
        try {
            // $area_details = [];
            // foreach($request->areas as $area){
            //     $area_details[] = [
            //         'company_id' => $request->company,
            //         'location_id' => $request->location,
            //         'operation_line_id' => $request->operation_line,
            //         'category_id' => $request->category,
            //         'area_id' => $area
            //     ];
            // }
            if($companyArea->update($request->all())){
                
                DB::commit();

                return CompanyArea::with('company', 'location', 'operationLine', 'category', 'area')->where('id',$companyArea->id)->first();
            }

        } catch (Exception $e) {
            DB::rollBack();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(CompanyArea $companyArea)
    {
        try {
            if($companyArea->delete()){
                
                DB::commit();

                return $companyArea;
            }

        } catch (Exception $e) {
            DB::rollBack();
        }
    }
}
