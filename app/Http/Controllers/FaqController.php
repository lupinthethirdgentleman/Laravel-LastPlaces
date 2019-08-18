<?php 
namespace App\Http\Controllers;
use Auth,Blade,Config,Cache,Cookie,DB,File,Hash,Input,Mail,Redirect,Request,Response,Session,URL,View,Validator;
use Excel;
use PDF;
use App\Model\Faq;


class FaqController extends BaseController {

	public function index(){
	$faqObj = FAQ::all();
	//print_r($faqObj);
	return View::make('cms.faq')->with('faq',$faqObj);

	}

}

?>