<?php

namespace App\Http\Controllers;
use Auth,Blade,Config,Cache,Cookie,DB,File,Hash,Input,Mail,Redirect,Request,Response,Session,URL,View,Validator;
/*use App\Model\User;
use App\Model\Userlogin;*/
use App\Model\EmailAction;
use App\Model\EmailTemplate;
use App\Model\Connection;
use App\Model\NewsLettersubscriber; 
/*use App\Model\UserCart;*/
use App\Model\Company;
use App\Model\Hcp;


/**
 * Login Controller
 *
 * Add your methods in the class below
 *
 * This file will render views from views
 */ 
 
class AdddahcpController extends BaseController {

	public function adddahcpView(){
		$userEmail	=	Input::get('email');

		$company_list = DB::table('companies')->lists('Name','id');
		$location_list = DB::table('locations')->lists('Name','id');

		return View::make('add_dahcp')->with('userEmail',$userEmail)->with('company_list', $company_list)->with('location_list', $location_list)->with('title','Add hcp');
	}


	/**
	 * Function for add dahcp details
	 *
	 * @param null
	 *
	 * @return url. 
	 */
	public function postIndex(){
		if(Request::ajax()){ 
			$formData	=	Input::all();

			if(!empty($formData)){
				######### validation rule #########
				$validationRules	=	array(
					'first_name' 		=> 'required',
					'last_name' 		=> 'required',
					'company_id' 		=> 'required',
					'location' 			=> 'required',
					'profession' 		=> 'required',
					'dahcp_image'		=> 'image',
				);
				$validator = Validator::make(
					Input::all(),
					$validationRules
				);
				if (!$validator->fails()){
					
					$userEmail	=	Input::get('userEmail');
					
					######### array for save user #########
					$userData										=  array();
					######### image upload #########
					$userData['first_name'] 						=  Input::get('first_name');
					$userData['middle_name'] 						=  Input::get('middle_name');
					$userData['last_name'] 							=  Input::get('last_name');
					$fullName										=  ucwords(str_replace(' ', '',Input::get('first_name')).' '.str_replace(' ', '',Input::get('middle_name')).' '.str_replace(' ', '',Input::get('last_name')));
					$userData['full_name'] 							=  ucwords($fullName);
					$userData['profession'] 						=  Input::get('profession');
					$userData['company_id'] 						=  Input::get('company_id');
					$userData['location'] 							=  Input::get('location');
					/*$validateString									=	md5(time() . Input::get('email'));
					$userData['validate_string']					=  (!$userEmail) ? $validateString : '';
					$userData['active']								= 1;
					$userData['is_verified']						= (!$userEmail) ? 0 : 1;*/
					$userData['created_at']							= date('Y-m-d H:i:s');
					/*$userData['updated_at']							= date('Y-m-d H:i:s');*/

					if(input::hasFile('dahcp_image')){
					$extension 	=	 Input::file('dahcp_image')->getClientOriginalExtension();
					$fileName	=	time().'-hcp-image.'.$extension;
					
						if(Input::file('dahcp_image')->move(HCP_IMAGE_ROOT_PATH, $fileName)){
							$userData['image']			=	$fileName;
						}
					}


					######### save user and get insert id #########
					
					$insertId								=	Hcp::insertGetId($userData);
					
					######### save user for newsletter subscriber #########
				
					$allErrors		=	'';
					$title			=	'';
					$url 			=	'';
					
					$allErrors	=	trans("Health Professional added successfully");
					$title		=	trans("DAHCP");
					
					$response	=	array(
						'success' 		=> 1,
						'success_msg' 	=> $allErrors,
						'title' 		=> $title,
						'url'			=>	$url
					);
					return Response::json($response); die;	
				}else{
					
					$allErrors='<ul>';
					foreach ($validator->errors()->all('<li>:message</li>') as $message){
							$allErrors .=  $message; 
					}
					$allErrors .= '</ul>'; 
					$response	=	array(
						'success' => false,
						'errors' => $allErrors
					);
					return  Response::json($response); die;
				}	
			}
		}
	}// end postIndex()

	public function GetCountryLocation($Id){
		
		$company_id		  	 = $Id;
		
		$companyLocation     = DB::table('locations')->where('company',$company_id)->where('status',1)->lists('Name','id');

        return json_encode($companyLocation);
	}// end GetCountryLocation()

}
