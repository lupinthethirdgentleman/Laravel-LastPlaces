<?php

namespace App\Http\Controllers;
use Auth,Blade,Config,Cache,Cookie,DB,File,Hash,Input,Mail,Redirect,Request,Response,Session,URL,View,Validator;
use App\Model\User;
use App\Model\Userlogin;
use App\Model\EmailAction;
use App\Model\EmailTemplate;
use App\Model\UserCart;
use App\Model\Region;

/**
 * Login Controller
 *
 * Add your methods in the class below
 *
 * This file will render views from views
 */ 
 
class RegionController extends BaseController {

	public function view($slug){
		$title = ucfirst(str_replace('-', ' ', $slug));
		$lang			=	\App::getLocale();
		$regionDetail	=	DB::select( DB::raw("SELECT * FROM region_description WHERE foreign_key = (select id from region WHERE region.slug = '$slug') AND language_id = (select id from languages WHERE languages.lang_code = '$lang')") );
		
		 if(empty($regionDetail)){
			return Redirect::to('/');
		} 
		$result	=	array();
		
		foreach($regionDetail as $region){
			$key	=	$region->source_col_name;
			$value	=	$region->source_col_description;
			$result[$region->source_col_name]	=	$region->source_col_description;
			$region_id = $region->foreign_key;
		}


		$countryDetail	=	DB::select( DB::raw("SELECT * FROM destination_country_description WHERE parent_id in (select id from destination_country where region_id = '$region_id' and active =1) AND language_id = (select id from languages WHERE languages.lang_code = '$lang')") );
		$countryCount = DB::select( DB::raw("SELECT * FROM destination_country where region_id = '$region_id' and active=1 ") );
		$i=0;
		$country_result	=	array();
		if(count($countryCount)>0) {
			foreach($countryCount as $cc) {
				$cc_id = $cc->id;
				foreach($countryDetail as $country){
					if($cc_id==$country->parent_id) {
						$key	=	$country->source_col_name;
						$value	=	$country->source_col_description;
						$country_result[$i][$country->source_col_name]	=	$country->source_col_description;
					}
				}
				$country_result[$i]['img']	=	$cc->image;
				$country_result[$i]['slug']	=	$cc->slug;
				$i++;
			}
		}
		//echo '<pre>'; print_r($country_result); die;

		/*$countryDetail	=	DB::select( DB::raw("SELECT * FROM destination_country_description WHERE parent_id = (select id from destination_country where region_id = '$region_id') AND language_id = (select id from languages WHERE languages.lang_code = '$lang')") );
		$country_result	=	array();

		echo '<pre>'; print_r($countryDetail); die;
		
			foreach($countryDetail as $country){
				$i = 0;
				$key	=	$country->source_col_name;
				$value	=	$country->source_col_description;
				$country_result[$i][$country->source_col_name]	=	$country->source_col_description;
			}

		echo '<pre>'; print_r($country_result); die;*/

		return View::make('region_view' , compact('result','slug','title','country_result'));
	}
}
