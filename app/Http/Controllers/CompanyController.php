<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use App\{
    Company,
    Location
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
        return Company::with('locations')->orderBy('name','asc')->get();
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

     /**
     * Get company location
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function companyLocation($id){

        return Company::with('locations')->where('id', $id)->first();
    }

    /**
     * Get company of the Authenticated user
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function companyOfUser(){

        $company = Auth::user()->companies;
        $location = Location::where('id', Auth::user()->company_location)->get();
        
        foreach($company as $c){
            $c['locations'] = $location;
        }

        return $company;
    }
}
