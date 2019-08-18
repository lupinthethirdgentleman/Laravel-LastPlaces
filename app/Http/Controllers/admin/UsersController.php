<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\BaseController;
use App\Model\AdminUser;
use App\Model\User;
use App\Model\NewsLettersubscriber;
use App\Model\EmailTemplate;
use App\Model\EmailAction;
use App\Model\Blog;
use mjanssen\BreadcrumbsBundle\Breadcrumbs as Breadcrumb;
use Auth,Blade,Config,Cache,Cookie,DB,File,Hash,Input,Mail,mongoDate,Redirect,Request,Response,Session,URL,View,Validator;

/**
 * Users Controller
 *
 * Add your methods in the class below
 *
 * This file will render views from views/admin/usermgmt
 */

/*
MAIL_DRIVER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=465
MAIL_USERNAME=test@gmail.com
MAIL_PASSWORD=test
MAIL_ENCRYPTION=ssl*/
 
class UsersController extends BaseController {

 /**
 * Function for display list of all users
 *
 * @param null
 *
 * @return view page. 
 */

	public function listUsers(){
		
		### breadcrumbs Start ###
		Breadcrumb::addBreadcrumb(trans("messages.user_management.dashboard"),URL::to('admin/dashboard'));
		Breadcrumb::addBreadcrumb(trans("messages.user_management.users"),URL::to('admin/users'));
		$breadcrumbs 	= 	Breadcrumb::generate();
		### breadcrumbs End ###

		$DB = User::query();
		
		$searchVariable	=	array(); 
		$inputGet		=	Input::get();
		
		/* seacrching on the basis of username and email */ 
		
		if ((Input::get() && isset($inputGet['display'])) || isset($inputGet['page']) ) {
			$searchData	=	Input::get();
			unset($searchData['display']);
			unset($searchData['_token']);
			
			if(isset($searchData['page'])){
				unset($searchData['page']);
			}
			
			foreach($searchData as $fieldName => $fieldValue){
				if(!empty($fieldValue) || $fieldValue==0){
					$DB->where("$fieldName",'like','%'.$fieldValue.'%');
				}
				$searchVariable	=	array_merge($searchVariable,array($fieldName => $fieldValue));
			}
		}
		
		$sortBy 	= 	(Input::get('sortBy')) ? Input::get('sortBy') : 'created_at';
	    $order  	= 	(Input::get('order')) ? Input::get('order')   : 'DESC';
		
		$result 	= 	$DB->with('users')
		 						->where('user_role_id','=',2)
		 						->where('is_deleted',0)
		 						->orderBy($sortBy, $order)
		 						->paginate(Config::get("Reading.records_per_page"));	
		//dd($result);
		
		return  View::make('admin.usermgmt.index', compact('breadcrumbs','result' ,'searchVariable','sortBy','order','userType','type'));
	}// end listUsers()

/**
 * Function for add users
 *
 * @param null
 *
 * @return view page. 
 */	
	public function addUser(){
		### breadcrumbs Start ###
		Breadcrumb::addBreadcrumb(trans("messages.user_management.dashboard"),URL::to('admin/dashboard'));
		Breadcrumb::addBreadcrumb(trans("messages.user_management.users"),URL::to('admin/users'));
		Breadcrumb::addBreadcrumb(trans("messages.user_management.add"),Request::url());
		$breadcrumbs 	= 	Breadcrumb::generate();
		### breadcrumbs End ###
	
		/*$regionList		=	array();
		$old_country 	= 	Input::old('country');
		$old_region 	= 	Input::old('region');
		
		if(empty($old_country)){
			$countryCode	=	Input::old('country');
		}
		
		$countryList	=	DB::table('countries')->where('status',1)->orderBy('name','ASC')->lists('name','id');*/
			
		return  View::make('admin.usermgmt.add',compact('breadcrumbs'));
		
	}//end addCompany()
	
	
/**
 * Function for save added users
 *
 * @param null
 *
 * @return view page. 
 */	
	public function saveUser(){
		
		$formData	=	Input::all();
		//echo "<pre>";
		//print_r($formData);die;
		if(!empty($formData)){
			$validator = Validator::make(
				Input::all(),
				array(
					/*'full_name'			=> 'required',*/
				//	'gender'			=> 'required',
					//'civil_id'			=> 'required',
				//	'faulty'			=> 'required',
					'email' 			=> 'required|email|unique:users',
					'first_name' 		=> 'required',
					'last_name' 		=> 'required',
					'phone_number' 		=> 'required',
					//'password'			=> 'required|min:8',
					//'confirm_password'  => 'required|min:8|same:password', 
					//'phone_number' 		=> 'required|numeric',
					
				)
			);
		
			$password 	=  Input::get('password');
			
			/*if (preg_match('#[0-9]#', $password) && preg_match('#[a-zA-Z]#', $password) && preg_match('#[\W]#', $password)) {
				$correctPassword	=	 Hash::make($password);
			}else{
				$errors = $validator->messages();
				$errors->add('password', trans("messages.user_management.password_help_message"));
				return Redirect::back()->withErrors($errors)->withInput();
			}*/
				
			if ($validator->fails()){
				 return Redirect::back()->withErrors($validator)->withInput();
			}else{ 
				
				
				$userRoleId				=  FRONT_USER ;
				/*$fullName				=  ucwords(Input::get('full_name'));*/
			
				$obj 					=  new User;
				$validateString			=  md5(time() . Input::get('email'));
				$obj->validate_string	=  $validateString;					
				$obj->first_name 		=  Input::get('first_name');
				$obj->last_name 		=  Input::get('last_name');
				
				//$obj->gender 			=  Input::get('gender');
				$obj->email 			=  Input::get('email');
				/*$obj->slug	 			=  $this->getSlug($fullName,'slug','User');*/
				$obj->password	 		=  Hash::make(Input::get('password'));
				$obj->user_role_id		=  $userRoleId;
				$obj->phone				=  Input::get('phone_number');
				//$obj->civil_id				=  Input::get('civil_id');
				//$obj->faulty				=  Input::get('faulty');
				$obj->is_verified		=  1; 
				$obj->active			=  1; 
				
				if(input::hasFile('image')){
					$extension 	=	 Input::file('image')->getClientOriginalExtension();
					$fileName	=	time().'-user-image.'.$extension;
					
					if(Input::file('image')->move(USER_PROFILE_IMAGE_ROOT_PATH, $fileName)){
						$obj->image			=	$fileName;
					}
				}
				
				$obj->save();
				$userId	=	$obj->id;			
				$encId			=	md5(time() . Input::get('email'));
				
				/*NewsLettersubscriber::insert(
											array(
												'user_id' 		=>  $userId,
												'email'	  		=>  Input::get('email'),
												'is_verified' 	=>  0,
												'status' 		=>  1,
												'enc_id' 		=>  $encId,
												'created_at' 	=>   DB::raw('NOW()'),
												'updated_at' 	=>   DB::raw('NOW()')
											));
											
				$obj->save(); 
				*/
				//mail email and password to new registered user
				
				/*$settingsEmail 	= Config::get('Site.email');
				$full_name		= $obj->full_name; 
				$email			= $obj->email;
				$password		= Input::get('password');
				$route_url      =  URL::to('login');
				$click_link   	=   $route_url;
				
				$emailActions	= EmailAction::where('action','=','user_registration')->get()->toArray();
				$emailTemplates	= EmailTemplate::where('action','=','user_registration')->get(array('name','subject','action','body'))->toArray();
			
				$cons 			= explode(',',$emailActions[0]['options']);
				$constants 		= array();
				
				foreach($cons as $key => $val){
					$constants[] = '{'.$val.'}';
				} 
				
				$subject 		= $emailTemplates[0]['subject'];
				$rep_Array 		= array($full_name,$email,$password,$click_link,$route_url); 
				$messageBody	= str_replace($constants, $rep_Array, $emailTemplates[0]['body']);*/
						
				//$mail			= $this->sendMail($email,$full_name,$subject,$messageBody,$settingsEmail);	
					
				Session::flash('success',trans("messages.user_management.user_added_successfully"));
				return Redirect::to('admin/users');
			}
		}
	}// saveUser()
	
 /**
 * Function for display user detail
 *
 * @param $userId 	as id of user
 *
 * @return view page. 
 */
	public function viewUser($userId = 0){
	
		Breadcrumb::addBreadcrumb(trans("messages.user_management.dashboard"),URL::to('admin/dashboard'));
		Breadcrumb::addBreadcrumb(trans("messages.user_management.users"),URL::to('admin/users'));
		Breadcrumb::addBreadcrumb(trans("messages.user_management.view"),Request::url());
		$breadcrumbs 	= 	Breadcrumb::generate();
		
		if($userId){
			
			$userDetails	=	AdminUser::find($userId); 
			
			/*#### Getting country name ###
			$countryName	=	DB::table('countries')
								->where('id','=',$userDetails->country)
								->where('status',1)
								->pluck('name');
			
			#### Getting region name ###
			$regionName		=	DB::table('states')
								->where('country_id',$userDetails->country)
								->where('status',1)
								->pluck('name');
		
			#### Getting city name ###
			$cityName		=	DB::table('cities')
								->where('country_id',$userDetails->country)
								->where('state_id',$userDetails->region)
								->where('status',1)
								->pluck('name');*/
								
			return View::make('admin.usermgmt.view', compact('userDetails','breadcrumbs'));
		}
		
	} // end viewUser()

 /**
 * Function for display page for edit user
 *
 * @param $userId as id of user
 *
 * @return view page. 
 */
 
	public function editUser($userId = 0){
		
		### breadcrumbs Start ###
		Breadcrumb::addBreadcrumb(trans("messages.user_management.dashboard"),URL::to('admin/dashboard'));
		Breadcrumb::addBreadcrumb(trans("messages.user_management.users"),URL::to('admin/users'));
		Breadcrumb::addBreadcrumb(trans("messages.user_management.edit"),Request::url());
		$breadcrumbs 	= 	Breadcrumb::generate();
		### breadcrumbs End ###
		
		if($userId){
			
			$userDetails	=	AdminUser::find($userId);
		
			/*$regionList		=	array();
			$cityList		=	array();
			$old_country	=	Input::old('country'); 
			$old_region		=	Input::old('region');
			
			if(!empty($old_country)){
				$countryCode	=  Input::old('country');
			}else{
				$countryCode	=  $userDetails->country;
			}
			
			$regionList		= 	DB::table('states')
									->where('status',1)
									->where('country_id','=',$countryCode)
									->orderBy('name','ASC')
									->lists('name','id');
								
			if(!empty($old_country) && !empty($old_region)){
				$countryId	=	Input::old('country');
				$regionId		=	Input::old('region');
			}else{
				$countryId	=	$userDetails->country;
				$regionId		=	$userDetails->region;
			}
			
			$cityList		=	DB::table('cities')
									->where('status',1)
									->where('country_id',$countryId)
									->where('state_id',$regionId)
									->lists('name','id');
					
			$countryList	=	DB::table('countries')->where('status',1)->orderBy('name','ASC')->lists('name','id');*/
			
			return View::make('admin.usermgmt.edit', compact('userDetails','breadcrumbs'));
		}
	} // end editUser()

 /**
 * Function for update user detail
 *
 * @param $userId as id of user
 *
 * @return redirect page. 
 */
	public function updateUser($userId = 0){	
		
		$thisData	=	Input::all(); 
		$validator = Validator::make(
			Input::all(),
			array(
					'email' 				=> 'required|email',
					'first_name'			=> 'required',
					'last_name'				=> 'required',
					'phone'					=> 'required'
				)
			);
			
		if ($validator->fails()){
			
			return Redirect::to('/admin/users/edit-user/'.$userId)
				->withErrors($validator)->withInput();
		}else{
			## Update user's information in users table ##
			$obj	 				=  User::find($userId);		
			$obj->first_name		=  Input::get('first_name');
			$obj->last_name			=  Input::get('last_name');
			$obj->phone				=  Input::get('phone');
			$obj->email				=  Input::get('email');
			$obj->save();
			return Redirect::to('/admin/users')->with('success',trans("messages.user_management.user_edited_successfully"));
		}
	}// end updateUser()
	
 /**
 * Function for mark a user as deleted 
 *
 * @param $userId as id of user
 *
 * @return redirect page. 
 */
	public function deleteUser($userId = 0){

		$user = Blog::where('author_id', $userId)->count();
		if($user>=1) {
			Session::flash('error',trans("User can't be deleted,because he is author"));
			return Redirect::to('admin/users/');
		}

		if($userId){		
			$userModel	=	User::where('id',$userId)->update(array('is_deleted'=>1));
			Session::flash('flash_notice',trans("messages.user_management.user_successfully_deleted")); 
		}
		
		return Redirect::to('admin/users/');
	} // end deleteUser()

 /**
 * Function for update user status
 *
 * @param $userId as id of user
 * @param $userStatus as status of user
 *
 * @return redirect page. 
 */
 
	public function updateUserStatus($userId = 0, $userStatus = 0){
		
		if($userStatus == 0	){
			$statusMessage	=	trans("messages.user_management.user_successfully_deactivated");
		}else{
			$statusMessage	=	trans("messages.user_management.user_successfully_activated");
		}
		
		AdminUser::where('id', '=', $userId)->update(array('active' => $userStatus));
		Session::flash('flash_notice', $statusMessage); 
		return Redirect::to('admin/users');
	} // end updateUserStatus()
	
 /**
 * Function for verify user
 *
 * @param $userId as id of user
 *
 * @return redirect page. 
 */
	public function verifiedUser($userId = 0){
		AdminUser::where('id', '=', $userId)->update(array('is_verified' => 1));
		Session::flash('flash_notice', 'User status updated successfully.'); 
		return Redirect::to('admin/users');
	} // end verifiedUser()
	

/**
 * Function for delete,active,deactive user
 *
 * @param $userId as id of users
 *
 * @return redirect page. 
 */
 		
	public function performMultipleAction($userId = 0){
		if(Request::ajax()){
			$actionType = ((Input::get('type'))) ? Input::get('type') : '';
			if(!empty($actionType) && !empty(Input::get('ids'))){
				if($actionType	==	'active'){
					AdminUser::whereIn('id', Input::get('ids'))->update(array('active' => 1));
				}
				elseif($actionType	==	'inactive'){
					AdminUser::whereIn('id', Input::get('ids'))->update(array('active' => 0));
				}
				elseif($actionType	==	'verified'){
					AdminUser::whereIn('id', Input::get('ids'))->update(array('is_verified' => 1));
				}
				elseif($actionType	==	'notverified'){
					AdminUser::whereIn('id', Input::get('ids'))->update(array('is_verified' => 0));
				}
				elseif($actionType	==	'delete'){
					AdminUser::whereIn('id', Input::get('ids'))->update(array('is_deleted' => 1));
				}
				Session::flash('flash_notice', trans("messages.user_management.action_performed_message")); 
			}
		}
	}//end performMultipleAction()

/**
 * Function for send credential to user
 *
 * @param $id as id of users
 *
 * @return redirect page. 
 */
	public function sendCredential($id){
				
		$obj	=	AdminUser::find($id);
		
		$settingsEmail 	= Config::get('Site.email');
		$full_name		= $obj->full_name; 
		$email			= $obj->email;
		
		$password		=  substr(uniqid(rand(10,1000),false),rand(0,10),8);
		$obj->password	=	Hash::make($password);
		$obj->save();
		
		$route_url      =  URL::to('login');
		$click_link   	=   $route_url;
		
		$emailActions	= EmailAction::where('action','=','send_login_credentials')->get()->toArray();
		$emailTemplates	= EmailTemplate::where('action','=','send_login_credentials')->get(array('name','subject','action','body'))->toArray();
	
		$cons 			= explode(',',$emailActions[0]['options']);
		$constants 		= array();
		
		foreach($cons as $key => $val){
			$constants[] = '{'.$val.'}';
		} 
		
		$subject 		= $emailTemplates[0]['subject'];
		$rep_Array 		= array($full_name,$email,$password,$click_link,$route_url); 
		$messageBody	= str_replace($constants, $rep_Array, $emailTemplates[0]['body']);
				
		$mail			= $this->sendMail($email,$full_name,$subject,$messageBody,$settingsEmail);
		Session::flash('flash_notice', trans("messages.access_manager.sent_login_credentials_success"));
		return Redirect::back();
	}	
}//end UsersController
