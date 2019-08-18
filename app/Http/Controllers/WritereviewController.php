<?php

namespace App\Http\Controllers;
use Auth,Blade,Config,Cache,Cookie,DB,File,Hash,Input,Mail,Redirect,Request,Response,Session,URL,View,Validator;
use App\Model\User;
use App\Model\Userlogin;
use App\Model\EmailAction;
use App\Model\EmailTemplate;
use App\Model\UserCart;


/**
 * Login Controller
 *
 * Add your methods in the class below
 *
 * This file will render views from views
 */ 
 
class WritereviewController extends BaseController {

	public function WritereviewView(){
		return View::make('write_review');
	}
