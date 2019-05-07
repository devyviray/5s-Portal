<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\{
    Role
};

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('role.index');
    }

    /**
     * Fetch all role
     *
     * @return \Illuminate\Http\Response
     */

    public function indexData(){
        return Role::orderBy('id','desc')->get();
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
           'level' => 'required|integer' 
        ]);
        
        return Role::create(['slug' => strtolower($request->name), 'description' => $request->name.' Role']+ $request->all());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required',
            'level' => 'required|integer' 
         ]);

        if($role->update(['slug' => strtolower($request->name), 'description' => $request->name.' Role']+ $request->all())){

            return Role::where('id', $role->id)->first();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        if($role->delete()){
            return $role;
        }
    }
}
