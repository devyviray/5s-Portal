<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\{
    Area
};

class AreaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('area.index');
    }
    
     /**
     * Get all Area
     *
     * @return \Illuminate\Http\Response
     */
    public function indexData()
    {
        return Area::orderBy('id','desc')->get();
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
            'name' => 'required|unique:areas,name',
        ]);
            
        DB::beginTransaction();
        try {
            
            $area = Area::create($request->all());
            DB::commit();
            return $area;

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
    public function update(Request $request, Area $area)
    {
        $request->validate([
            'name' => 'required|unique:areas,name,' .$area->id,
        ]);
            
        DB::beginTransaction();
        try {
            
            $area->update($request->all());
            DB::commit();
            return $area;

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
    public function destroy(Area $area)
    {
        DB::beginTransaction();
        try {

            $area->delete();
            DB::commit();
            return $area;

        } catch (Exception $e) {
            DB::rollBack();
        }
    }
}
