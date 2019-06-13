<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Rules\CompanyAreaRule;
use DB;
use App\{
    CompanyCategory,
    Report
};

class CompanyCategoryController extends Controller
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
        return CompanyCategory::with('company', 'location', 'operationLine', 'category', 'areas')->orderBy('id','desc')->get();
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
            'operation_line' => 'required_if:category,==,1',
            'company' => ['required', new CompanyAreaRule($request->company,$request->location,$request->operation_line,$request->category)],
            'location' => 'required',
            
        ]);

        DB::beginTransaction();
        try {

            $area_details = [
                'company_id' => $request->company,
                'location_id' => $request->location,
                'operation_line_id' => $request->operation_line,
                'category_id' => $request->category,
            ];

            if($companyArea = CompanyCategory::create($area_details)){
                
                $companyArea->areas()->sync( (array) $request->areas);

                DB::commit();
                return CompanyCategory::all();
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
    public function update(Request $request, CompanyCategory $companyCategory)
    {    
        $request->validate([
            'areas' => 'required',
            'category_id' => 'required',
            'company' => ['required', new CompanyAreaRule($request->company,$request->location_id,$request->operation_line,$request->category_id,$companyCategory->id)],
            'location_id' => 'required',
            'operation_line' => 'required_if:category,==,1',
        ]);

        DB::beginTransaction();
        try {
            $area_details = [
                'company_id' => $request->company,
                'location_id' => $request->location_id,
                'operation_line_id' => $request->operation_line,
                'category_id' => $request->category_id,
            ];
            
            if($companyCategory->update($area_details)){

                $companyCategory->areas()->sync( (array) $request->areas);

                DB::commit();

                return CompanyCategory::with('company', 'location', 'operationLine', 'category', 'areas')->where('id',$companyCategory->id)->first();
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
    public function destroy(CompanyCategory $companyCategory)
    {
        try {
            if($companyCategory->delete()){
                
                DB::commit();

                return $companyCategory;
                }

        } catch (Exception $e) {
            DB::rollBack();
        }
    }

    /**
     * Get all areas per company
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function companyAreaPerCompany($companyId, $locationId, $categoryId, $operationLineId = null){
        
        return CompanyCategory::with('areas')
        ->where('company_id', $companyId)
        ->where('location_id', $locationId)
        ->where('category_id', $categoryId)
        ->when($operationLineId !== 'undefined', function ($q) use ($operationLineId){
            $q->where('operation_line_id', $operationLineId);
        })->get();

    }
}
