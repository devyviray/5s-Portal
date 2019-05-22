<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\{
    User
};

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('user.index');
    }

     /**
     * Fetch all user
     *
     * @return \Illuminate\Http\Response
     */

    public function indexData(){
        return User::with('companies', 'location', 'roles')->orderBy('id','desc')->get();
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
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'company' => 'required',
            'company_location' => 'required'
        ]);

        DB::beginTransaction();
        try {
            if($user = User::create(['password' => 'password'] + $request->all())){
                // Assigning of company
                $user->companies()->sync( (array) $request->company);
                // Assigning of role
                $user->syncRoles($request->role);
                DB::commit();

                return User::with('companies','location', 'roles')->where('id', $user->id)->first();
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
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email,'.  $user->id,
            'company' => 'required',
            'company_location' => 'required',
            'role' => 'required'
        ]);

        DB::beginTransaction();
        try {
            if($user->update($request->all())){
                // Assigning of company
                $user->companies()->sync( (array) $request->company);
                // Assigning of role
                $user->syncRoles($request->role);

                DB::commit();

                return User::with('companies','roles', 'location')->where('id', $user->id)->first();
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
    public function destroy(User $user)
    {
        if($user->delete()){
            return $user;
        }
    }


     /**
     * Get all process owner per company
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getProcessOwnerPerCompany($companyId, $locationId){

        return User::whereHas('companies', function ($q) use ($companyId){
                $q->where('company_id',$companyId);
            })->whereHas('roles', function ($q){
                $q->where('role_id', 4); //Process owner
            })->where('company_location', $locationId)->get();
    }
}
