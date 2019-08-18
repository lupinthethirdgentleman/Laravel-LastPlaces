<?php

namespace App\Http\Controllers;
use Auth,Blade,Config,Cache,Cookie,DB,File,Hash,Input,Mail,Redirect,Request,Response,Session,URL,View,Validator;
use App\Model\User;
use App\Model\Userlogin;
use App\Model\EmailAction;
use App\Model\EmailTemplate;
use App\Model\UserCart;
use App\Model\News;



/**
 * News Controller
 *
 * Add your methods in the class below
 *
 * This file will render views from views
 */ 
 
class NewsPagesController extends BaseController {
	public function view($slug){
		$newsObj = News::with('newsDescription','newsComments','newsAuthor')
				   ->where('slug','=',$slug)
				   ->first()->toArray();
		//echo "<pre>";
		//print_r($blogObj);		   die;
		$recentNewsObj = News::with('newsDescription')
				   ->where('is_active','=',1)
				   ->orderBy('id','desc')
				   ->limit(8)
				   ->get();
		return View::make('news_view',['newsData'=>$newsObj,'recentNewsObj'=>$recentNewsObj]);
	}
}
