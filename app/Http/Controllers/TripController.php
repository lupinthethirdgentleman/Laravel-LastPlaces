<?php

namespace App\Http\Controllers;
use Auth,Blade,Config,Cache,Cookie,DB,File,Hash,Input,Mail,Redirect,Request,Response,Session,URL,View,Validator;
use App\Model\User;
use App\Model\Userlogin;
use App\Model\EmailAction;
use App\Model\EmailTemplate;
use App\Model\UserCart;
use App\Model\DestinationCountry;
use App\Model\Photo;
use App\Model\TripMapDetail;



/**
 *Trip Controller
 *
 * Add your methods in the class below
 *
 * This file will render views from views
 */ 
 
class TripController extends BaseController {

	public function view($slug){
		$trip_slug = \Request::segment(3);
		$country_slug = \Request::segment(2);

		$title = ucfirst(str_replace('-', ' ', $slug));
		$lang			=	\App::getLocale();
		//$tripDetail	=	DB::select( DB::raw("SELECT * FROM trips_description WHERE foreign_key = (select id from trips WHERE trips.slug = '$trip_slug' and is_active=1) AND language_id = (select id from languages WHERE languages.lang_code = '$lang')") );

		$tripDetail=DB::table('trips_description')
					->select("*")
					->join('trips','trips.id','=','trips_description.foreign_key')
					->where('trips.slug',$trip_slug)
					->where('trips.is_active',1)
					->join('languages','languages.id','=','trips_description.language_id')
					->where('languages.lang_code',$lang)
					->get();
		$tripinfo = DB::select( DB::raw("select * from trips WHERE trips.slug = '$trip_slug' and is_active=1") );
		/*if(empty($tripDetail)){
			return Redirect::to('/');
		} */
		$result	=	array();
		
		foreach($tripDetail as $trip){
			$key	=	$trip->source_col_name;
			$value	=	$trip->source_col_description;
			$result[$trip->source_col_name]	=	$trip->source_col_description;
		}
		
		/*print_r($result);
		die();*/
		foreach($tripinfo as $trip_info){
			$result['tripdays'] = $trip_info->tripdays;
			$result['image'] = $trip_info->image;
			$result['header_image'] = $trip_info->header_image;
			$result['trip_id'] = $trip_info->id;
			$result['baseprice'] = $trip_info->baseprice;

		}

		//Client Review Detail
		$clientReviewDetail = DB::select( DB::raw("SELECT * FROM trip_review_description WHERE foreign_key in (select id from trip_review WHERE trip_id = $trip_info->id and is_active=1) AND language_id = (select id from languages WHERE languages.lang_code = '$lang')") );
		$clientCount = DB::select( DB::raw("SELECT * FROM trip_review where trip_id = $trip_info->id and is_active = 1 ") );
		//echo "<pre>"; print_r($clientReviewDetail);die;
		$client_result = array();
		$i = 0;
		if(count($clientCount)>0) {
			foreach($clientCount as $cc) {
				$cc_id = $cc->id;
				foreach($clientReviewDetail as $clientReview) {
					if($cc_id==$clientReview->foreign_key) {
						$key = $clientReview->source_col_name;
						$value = $clientReview->source_col_description;
						$client_result[$i][$clientReview->source_col_name] = $clientReview->source_col_description;
					}
				}
				$i++;
			}
		}
		//Client Review Detail

		//Trip Package
		$packageList = DB::table('trip_package')
									->join('trip_status','trip_package.status_id','=','trip_status.id')
									->select('trip_package.id','trip_package.trip_id','trip_package.trip_date','trip_package.price','trip_package.supplement','trip_status.status_name')
									->where('trip_id',$trip_info->id)
									->where('trip_package.active','=',1)
									->where('trip_date','>=',date("Y-m-d"))
									->get();
		//Trip Package

		//Country Info
		$CountryInfo	=	DB::select( DB::raw("SELECT * FROM destination_country_description WHERE parent_id = (select id from destination_country WHERE destination_country.slug = '$country_slug' and active=1) AND language_id = (select id from languages WHERE languages.lang_code = '$lang')") );
		$country_info_result	=	array();
		
		foreach($CountryInfo as $country_info){
			$key	=	$country_info->source_col_name;
			$value	=	$country_info->source_col_description;
			$country_info_result[$country_info->source_col_name]	=	$country_info->source_col_description;
		}

		$countryRegionId = DestinationCountry::select('region_id')
									->where('slug','=',$country_slug)
									->first();

		$regionIdToFetch =  $countryRegionId->region_id;

		$countryListToFetch = DestinationCountry::with('destinationCountryDescription')->where('region_id',$regionIdToFetch)->where('active','1')->limit(5)->get();
		

		//echo "<pre>"; print_r($country_info_result);die;
		//Country Info
		//print_r($result);
		$photoGalleryFetchTripId =  $result['trip_id'];

		$photoGalleryObj = Photo::where('trip_id','=',$photoGalleryFetchTripId)->get();

		//print_r($photoGalleryObj);

		$locations = TripMapDetail::select('*')
							->where('trip_id',$trip_info->id)
							->get();
		//echo "<pre>";print_r($locations);die;
		$rows = array();
		$i=0;
		foreach($locations as $location) {
			$rows[$i][0] = $location->id;
			$rows[$i][1] = $location->lat;
			$rows[$i][2] = $location->lng;
			$rows[$i][3] = $location->location;
			$rows[$i][4] = $location->active;
			$i++;
		}

		$locations_info = json_encode($rows);

		$countrydetail = DestinationCountry::select('*')
									->where('slug','=',$country_slug)
									->first();


		
		return View::make('trip_view' , compact('result','slug','title','country_slug','client_result','packageList','country_info_result','countryListToFetch','photoGalleryObj','locations','locations_info','countrydetail'));
	}
}
