<?php

namespace App\Http\Controllers;
use Auth,Blade,Config,Cache,Cookie,DB,File,Hash,Input,Mail,Redirect,Request,Response,Session,URL,View,Validator;
use App\Model\User;
use App\Model\Userlogin;
use App\Model\EmailAction;
use App\Model\EmailTemplate;
use App\Model\UserCart;
use App\Model\DestinationCountry;
use App\Model\PhotoCountry;
use App\Model\PhotoCountryNature;




/**
 * Login Controller
 *
 * Add your methods in the class below
 *
 * This file will render views from views
 */ 
 
class PhotoGalleryController extends BaseController {

	public function view(){
		$slug = \Request::segment(2);
		//$photoObj = Photo::paginate(10);
		if($slug=='tribes'){
			$query = PhotoCountry::query();
		}else{
			$query = PhotoCountryNature::query()->orderByRaw('RAND()');
		}
		
		if(request('country')){
			$query->where('country_id',request('country'))->orderByRaw('RAND()');
		}

		$photoObj = $query->paginate(15);
		// print_r($query);exit();
		
		$destinationCountry = DestinationCountry::select('id')->with('destinationCountryDescription')->where('active',1)->orderBy('name','asc')->get();
	  /* $currentLanguageId 	=	Session::get('currentLanguageId');
	   $destinationCountry = DB::table('destination_country_description')
	   							->select('destination_country_description.*','trips.id as tripid',DB::raw('count(trips.id) as totaltrips'),DB::raw('count(photos.id) as totalphoto'))
	   							->where('destination_country_description.language_id',$currentLanguageId)
	   							->join('trips','trips.country_id','=','destination_country_description.parent_id')
	   						    ->join('photos','photos.trip_id','=','trips.id')
	   						    ->groupBy('photos.trip_id')
	   						    ->groupBy('trips.country_id')
	   							->get();*/


		//print_r($destinationCountry);die;
		$country_count =  count($destinationCountry);

		for($i=0;$i<$country_count;$i++){
			$country_id_temp = $destinationCountry[$i]->destinationCountryDescription[0]->parent_id;
				if($slug=='tribes'){
					$photoObjCount = DB::table('photos_country')
				        ->where('country_id',$country_id_temp)
				        ->count();
				}else{
					$photoObjCount = DB::table('photos_country_nature')
				        ->where('country_id',$country_id_temp)
						->count();

				}
				// echo($photoObjCount);
				// exit("0");
			 $destinationCountry[$i]->destinationCountryDescription[0]->total_images = $photoObjCount;
		}
		


		return View::make('photo_gallery_view',compact('photoObj','destinationCountry','slug'));
	}

	
}
