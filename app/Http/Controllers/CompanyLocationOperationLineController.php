<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Rules\CompanyOperationLineRule;
use App\{
    Company,
    CompanyLocation,
    CompanyLocationOperationLine
};
class CompanyLocationOperationLineController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('company-operation-line.index');
    }

    /**
     * Fetch all company operation line
     *
     * @return \Illuminate\Http\Response
     */
    public function indexData()
    {
        return CompanyLocationOperationLine::with('companyLocation', 'companyLocation.company', 'companyLocation.location', 'operationLine')->orderBy('id', 'desc')->get()->groupBy('company_location_id');
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
            'company' => ['required', new CompanyOperationLineRule($request->location)],
            'location' => 'required',
            'operation_line' => 'required'
        ]);
        
        $company = CompanyLocation::where('company_id', $request->company)->where('location_id', $request->location)->first();
      
        DB::beginTransaction();
        try {
            foreach($request->operation_line as $id){
                $data = [
                    'company_location_id' => $company->id,
                    'operation_line_id' => $id
                ];

                $companyOperationLine = CompanyLocationOperationLine::create($data);
            }

            DB::commit();
            return $companyOperationLine;

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
        $request->validate([
            'company' => 'required',
            'location' => 'required',
            'operation_line' => 'required'
        ]);
      
        DB::beginTransaction();
        try {
            $companyLocationOperationLine = CompanyLocationOperationLine::where('company_location_id', $id)->get();
            // $arrayDiff = array_diff($request->operation_line,$companyLocationOperationLine->pluck('operation_line_id')->toArray());
            if($request->operation_line !== $companyLocationOperationLine->pluck('operation_line_id')->toArray()){
                if(CompanyLocationOperationLine::where('company_location_id', $id)->delete()){
                    foreach($request->operation_line as $operationLine){
                        $array = [
                            'company_location_id' => $id,
                            'operation_line_id' => $operationLine
                        ];
                        CompanyLocationOperationLine::create($array);
                    }
                }
            }
            DB::commit();
            return $companyLocationOperationLine;

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
    public function destroy($id)
    {
        return CompanyLocationOperationLine::where('company_location_id', $id)->delete();
    }
}
