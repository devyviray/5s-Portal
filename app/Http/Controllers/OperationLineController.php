<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\{
    OperationLine,
    CompanyLocation,
    CompanyLocationOperationLine
};

class OperationLineController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('operation.index');
    }

   /**
     * Get all operation line
     *
     * @return \Illuminate\Http\Response
     */
    public function indexData()
    {
        return OperationLine::orderBy('id','desc')->get();
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
            "name" => 'required',
        ]);
            
        DB::beginTransaction();
        try {
            
            $operationLine = OperationLine::create($request->all());
            DB::commit();
            return $operationLine;

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
    public function update(Request $request, OperationLine $operationLine)
    {
        $request->validate([
            "name" => 'required',
        ]);
            
        DB::beginTransaction();
        try {
            
            $operationLine->update($request->all());
            DB::commit();
            return $operationLine;

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
    public function destroy(OperationLine $operationLine)
    {
        DB::beginTransaction();
        try {
            
            $operationLine->delete();
            DB::commit();
            return $operationLine;

        } catch (Exception $e) {
            DB::rollBack();
        }
    }

    /**
     * Fetch operation line per company
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function getOperationLinePerCompany($companyId, $locationId){
        $company = CompanyLocation::where('company_id', $companyId)->where('location_id', $locationId)->first();
        $companyLocationOperationLine = CompanyLocationOperationLine::with('operationLine')->where('company_location_id', $company->id)->get();
        
        return $companyLocationOperationLine;
    }
}
