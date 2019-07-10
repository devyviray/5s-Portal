<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Mail; 
use Carbon\Carbon;
use App\Mail\{
    ContactUs
};

class ContactUsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('contact-us.index');
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
            'subject' => 'required',
            'message' => 'required'
        ]);

        Mail::to('tqmmnl-ilogroup@lafiladmin.com')->send(new ContactUs(Auth::user()->id, Carbon::now()->toDayDateTimeString() ,$request->subject,$request->message));
        
    }

}
