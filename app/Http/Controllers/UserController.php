<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;
use DB;
use Config;
use App\{
    User,
    Role
};
use App\Mail\{
    UserCreation
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
        return User::with('companies', 'location', 'roles', 'department')->orderBy('id','desc')->get();
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
            'company_location' => 'required',
            'department_id' => 'required',
            'role' => 'required'
        ]);

        DB::beginTransaction();
        try {
            $default_password = substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(5/strlen($x)) )),1,5);
            if($user = User::create(['password' => bcrypt($default_password)] + $request->all())){
                // Assigning of company
                $user->companies()->sync( (array) $request->company);
                // Assigning of role
                $user->syncRoles($request->role);
                // Send email to user
                Mail::to($user)->send(new UserCreation(Role::whereIn('id',$request->role)->pluck('name'), $default_password));
            
                DB::commit();

                return User::with('companies','location', 'roles','department')->where('id', $user->id)->first();
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
            'department_id' => 'required',
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

                return User::with('companies','roles', 'location', 'department')->where('id', $user->id)->first();
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
            $q->where('role_id', Config::get('constants.roles.process_owner.id'));
        })->where('company_location', $locationId)
        ->get();
    }

    /**
     *  Fetch Department Head user
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function departmentHedUser($companyId, $locationId){
        return User::whereHas('companies', function ($q) use ($companyId){
            $q->where('company_id',$companyId);
        })->whereHas('roles', function ($q){
            $q->where('role_id', Config::get('constants.roles.department_head.id'));
        })->where('company_location', $locationId)
        ->get();
    }

    /**
     *  Fetch Department Head user
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function buHedUser($companyId, $locationId){
        return User::whereHas('companies', function ($q) use ($companyId){
            $q->where('company_id',$companyId);
        })->whereHas('roles', function ($q){
            $q->where('role_id', Config::get('constants.roles.bu_head.id'));
        })->where('company_location', $locationId)
        ->first();
    }
    /**
     *  Fetch Group president user
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function groupPresidentUser(){
        return User::whereHas('roles', function ($q){
            $q->where('role_id', Config::get('constants.roles.group_president.id'));
        })->first();
    }

     /*
     * Change password 
     * 
     * @return \Illuminate\Http\Response
     */

    public function changePassword(Request $request){

        $request->validate([
            'user_id' => 'required',
            'new_password' => 'required|confirmed',
            'new_password_confirmation' => 'required'
        ]);
        
        DB::beginTransaction();
        try {
            $user = User::findOrFail($request->user_id);
            $user->update(['password' => bcrypt($request->input('new_password'))]);
            DB::commit();

            return $user;
        } catch (Exception $e) {
            DB::rollBack();
        }
    }
}
