<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\{
    Category
};

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('category.index');
    }

     /**
     * Get all category
     *
     * @return \Illuminate\Http\Response
     */
    public function indexData()
    {
        return Category::orderBy('id','desc')->get();
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
            
            $category = Category::create($request->all());
            DB::commit();
            return $category;

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
    public function update(Request $request, Category $category)
    {
        $request->validate([
            "name" => 'required',
        ]);
            
        DB::beginTransaction();
        try {
            
            $category->update($request->all());
            DB::commit();
            return $category;

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
    public function destroy(Category $category)
    {
        DB::beginTransaction();
        try {
            
            $category->delete();
            DB::commit();
            return $category;

        } catch (Exception $e) {
            DB::rollBack();
        }
    }
}
