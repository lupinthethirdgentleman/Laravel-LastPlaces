<?php

namespace App\Http\Controllers;
use Auth,Blade,Config,Cache,Cookie,DB,File,Hash,Input,Mail,Redirect,Request,Response,Session,URL,View,Validator;
use App\Model\User;
use App\Model\Userlogin;
use App\Model\EmailAction;
use App\Model\EmailTemplate;
use App\Model\UserCart;
use App\Model\Blog;



/**
 * Login Controller
 *
 * Add your methods in the class below
 *
 * This file will render views from views
 */ 
 
class BlogController extends BaseController {
	public function view($slug){
		$blogObj = Blog::with('blogDescription','blogComments','blogsAuthor')
				   ->where('slug','=',$slug)
				   ->first()->toArray();
		//echo "<pre>";
		//print_r($blogObj);		   die;
		$recentBlogObj = Blog::with('blogDescription')
				   ->where('is_active','=',1)
				   ->orderBy('id','desc')
				   ->limit(8)
				   ->get();
		return View::make('blog_view',['blogData'=>$blogObj,'recentBlogObj'=>$recentBlogObj]);
	}
}
