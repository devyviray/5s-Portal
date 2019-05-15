<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use App\{
    Faq
};

class FaqController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('faq.index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function indexPage(){
        return view('faq.index-page');
    }

     /**
     * Fetch all FAQs
     *
     * @return \Illuminate\Http\Response
     */

    public function indexData(){
        return Faq::with('user')->orderBy('id','desc')->get();
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
            'question' => 'required',
            'answer' => 'required'
        ]);

        DB::beginTransaction();
        try {
            if($faq = Faq::create(['user_id' => Auth::user()->id] + $request->all())){

                DB::commit();

                return Faq::with('user')->where('id', $faq->id)->first();
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
    public function update(Request $request, Faq $faq)
    {
        $request->validate([
            'question' => 'required',
            'answer' => 'required'
        ]);

        DB::beginTransaction();
        try {
            if($faq->update(['user_id' => Auth::user()->id] + $request->all())){

                DB::commit();

                return Faq::with('user')->where('id', $faq->id)->first();
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
    public function destroy(Faq $faq)
    {
        if($faq->delete()){
            return $faq;
        }
    }
}
