<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\{
    Company
};

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('company.index');
    }

     /**
     * Fetch all company
     *
     * @return \Illuminate\Http\Response
     */

    public function indexData(){
        return Company::with('locations')->orderBy('id','desc')->get();
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
            'name' => 'required|unique:companies,name',
            'location' => 'required'
        ]);

        DB::beginTransaction();
        try {
            if($company = Company::create($request->all())){
                // Assigning of locations
                $company->locations()->sync( (array) $request->location);

                DB::commit();

                return Company::with('locations')->where('id', $company->id)->first();
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
    public function update(Request $request, Company $company)
    {
        $request->validate([
            'name' => 'required',
            'location' => 'required'
        ]);

        DB::beginTransaction();
        try {
            if($company->update($request->all())){
                // Assigning of locations
                $company->locations()->sync([]);
                $company->locations()->sync( (array) $request->location);

                DB::commit();

                return Company::with('locations')->where('id', $company->id)->first();
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
    public function destroy(Company $company)
    {
        if($company->delete()){
            return $company;
        }
    }
}
