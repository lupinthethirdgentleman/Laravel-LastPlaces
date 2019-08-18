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
 
class CountryController extends BaseController {

	public function TestimonialListView(){
		return View::make('testimonial_list');
	}

	public function view($slug){

		$title = ucfirst(str_replace('-', ' ', $slug));
		$lang			=	\App::getLocale();
		$countryDetail	=	DB::select( DB::raw("SELECT * FROM destination_country_description WHERE parent_id = (select id from destination_country WHERE destination_country.slug = '$slug' and active =1) AND language_id = (select id from languages WHERE languages.lang_code = '$lang')") );
		$countryInfo = DB::select( DB::raw("SELECT * FROM destination_country WHERE destination_country.slug = '$slug' and active =1"));
		 if(empty($countryDetail)){
			return Redirect::to('/');
		} 
		$result	=	array();
		
		foreach($countryDetail as $country){
			$key	=	$country->source_col_name;
			$value	=	$country->source_col_description;
			$result[$country->source_col_name]	=	$country->source_col_description;
			$country_id = $country->parent_id;
		}

		
		foreach($countryInfo as $country_info){
			$result['image'] = $country_info->image;
			$result['header_image'] = $country_info->header_image;
			$result['slug'] = $country_info->slug;
		}

		$tripDetail	=	DB::select( DB::raw("SELECT * FROM trips_description WHERE foreign_key in (select id from trips where country_id = '$country_id' and is_active =1) AND language_id = (select id from languages WHERE languages.lang_code = '$lang')") );
		$tripCount = DB::select( DB::raw("SELECT * FROM trips where country_id = '$country_id' and is_active = 1 ") );
		$i=0;
		$trip_result	=	array();
		if(count($tripCount)>0) {
			foreach($tripCount as $tc) {
				$tc_id = $tc->id;
				foreach($tripDetail as $trip){
					if($tc_id==$trip->foreign_key) {
						$key	=	$trip->source_col_name;
						$value	=	$trip->source_col_description;
						$trip_result[$i][$trip->source_col_name]	=	$trip->source_col_description;
					}
				}
				$trip_result[$i]['img']	=	$tc->image;
				$trip_result[$i]['slug']	=	$tc->slug;
				$i++;
			}
		}

		return View::make('country_view' , compact('result','slug','title','trip_result'));

	}
}
