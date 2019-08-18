<?php

namespace App\Http\Controllers\admin;
use App\Http\Controllers\BaseController;
use App\Model\AdminUser;
use Auth,Blade,Config,Cache,Cookie,DB,File,Hash,Input,Mail,mongoDate,Redirect,Request,Response,Session,URL,View,Validator;
/**
 * Ajaxdata Controller
 *
 * Add your methods in the class below
 *
 * These methods are used in ajax call
 */
 
class AdminAjaxdataController extends BaseController {
	
/**
 * Function for get list of states
 *
 * @param null
 *
 * @return list of states(select box). 
 */
 
	public function getStates(){
		 
		if(Request::ajax() && Input::get()){
			
			$countryId		=	Input::get('country_code');
			
			$country		  	=	Input::get('state');
			
			if($countryId!=''){
				$regionList		=	DB::table('states')
									->where('status',1)
									->where('country_id','=',$countryId)
									->orderBy('name','ASC')
									->lists('name','id');
							
				$list	=	'<select id="region" name="region" onchange = "get_city(this.id);"><option value="">'.trans('Please Select Region').'</option>';
				if(count($regionList)>0){
					foreach($regionList as $k=>$v){
						$list.= '<option value='.$k.'>'.$v.'</option>';
					}
				}
				$list	.=	'</select>';
				echo $list;
				die;
			}else{
				echo '<select id="region"  class="small"  name="region" > <option value="">'.trans('messages.Please Select Region').'</option>';
				die;
			}
		}	
	}// end getStates()

/**
 * Function for get list of cities
 *
 * @param null
 *
 * @return list of cities(select box). 
 */
	public function getCities(){
	
		if(Request::ajax() && Input::get()){
			$countryId		=	Input::get('country_code'); 
			$regionId		=	Input::get('region_code'); 
			if($countryId!=''){
				
				$cityList		=	DB::table('cities')
									->where('country_id','=',$countryId)
									->where('state_id','=',$regionId)
									->where('status',1)
									->orderBy('name','ASC')
									->lists('name','id');
				
				$list	=	'<select id="city"  name="city"><option value="">'.trans('Please Select City').'</option>';
				if(count($cityList)>0){
					foreach($cityList as $k=>$v){
						$list.= '<option value='.$k.'>'.$v.'</option>';
					}
				}
				$list	.=	'</select>';
				echo $list;
				die;
			}else{
				echo '<select  id="city" class="small" name="city"><option value="">'.trans('messages.Please Select City').'</option>';
				die;
			}
		}	
	}// end getCities()
	
}// end AjaxdataController class
