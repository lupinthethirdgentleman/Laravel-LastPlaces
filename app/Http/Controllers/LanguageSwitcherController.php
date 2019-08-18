<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Redirect;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\Language;
use Config;

class LanguageSwitcherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if(!Session::has('currentLanguageId')){
            Session::put('currentLanguageId', Config::get('default_language.language_code'));
        }

        $currentLanguageCode    =   \Input::get('lang'); 
        
        if($currentLanguageCode!=''){
            \App::setLocale($currentLanguageCode);
            $currentLanguageDetail  =   Language::where('lang_code',$currentLanguageCode)
                ->first();
            $currentLanguageId      =   $currentLanguageDetail->id; 
            \Session::put('currentLanguageId',$currentLanguageId);
            \Cache::put('currentLanguageId',$currentLanguageId,1000);
        }else{
            $a= \Cache::get('currentLanguageId');
            \Session::put('currentLanguageId',$a);
        }
        return Redirect::back();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
    public function destroy($id)
    {
        //
    }
}
