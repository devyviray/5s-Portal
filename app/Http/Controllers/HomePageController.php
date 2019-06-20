<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Storage;
use Auth;
use App\{
    HomePage
};

class HomePageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home-page.index');
    }

    /**
     * Fetch all data
     *
     * @return \Illuminate\Http\Response
     */
    public function indexData()
    {
        return HomePage::orderBy('id', 'desc')->get();
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
            'image' => 'required'
        ]);

        DB::beginTransaction();
        try {
            if($request->has('image')){
                $attachments = $request->file('image');
                foreach($attachments as $key => $attachment){
                    $filename = $attachment->getClientOriginalName();
                    $path = Storage::disk('public')->put('homepage', $attachment);
    
                    $uploadedFile = $this->uploadFiles($path,  $filename, Auth::user()->id);
                }
            }
            DB::commit();

            return HomePage::latest()->first();
        } catch (Exception $e) {
            DB::rollBack();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(HomePage $homePage)
    {
        DB::beginTransaction();
        try {
            $homePage->delete();
            DB::commit();
            return $homePage;

        } catch (Exception $e) {
            DB::rollBack();
        }
    }

    /**
     * Upload files for homepage
     *
     * @return \Illuminate\Http\Response
     */
    public function uploadFiles($path,$filename, $uploaded)
    {
        $homePage = new HomePage;
        $homePage->file_path = $path;
        $homePage->file_name = $filename;
        $homePage->uploaded_by = $uploaded;
        $homePage->save();
    }

}
