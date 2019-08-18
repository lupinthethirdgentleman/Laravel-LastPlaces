<?php
namespace App\Http\Controllers;
use Auth,Blade,Config,Cache,Cookie,DB,File,Hash,Input,Mail,mongoDate,Redirect,Request,Response,Session,URL,View,Validator;
use App\Model\EmailAction;
use App\Model\EmailTemplate;
use App\Model\NewsLettersubscriber;  
use App\Model\User;
use App\Model\Userlogin;
use App\Model\Connection;
use App\Model\UserInvite;




/**
 * Registration Controller
 *
 * Add your methods in the class below
 *
 * This file will render views from views/registration
 */
 
class RegistrationController extends BaseController {
	/**
	 * Function for  registration page
	 *
	 * @param null
	 *
	 * @return url. 
	 */
	public function getIndex(){
		$title ='Sign up';
		$userEmail	=	Input::get('email');
		
		if(Auth::check() && Auth::user()->user_role_id == FRONT_USER){
			return Redirect::route('after-login');
		}

		$disabilityList = DB::table('dropdown_managers')->where('dropdown_type', 'disability')->lists('name','id');
	
		return View::make('signup')->with('userEmail',$userEmail)->with('disabilityList', $disabilityList)->with('title',$title);
	}//end getIndex()
	
	/**
	 * Function for save user registration details
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
					/*'first_name' 		=> 'required',
					'last_name' 		=> 'required',*/
					'email' 			=> 'required|email|unique:users,email,NULL,_id,deleted_at,NULL',
					'password'			=> 'required|min:6',
					'confirm_password'  => 'required|same:password',
					'g-recaptcha-response' => 'required|captcha'	
					/*'disability'  		=> 'required',*/
				);
				$validator = Validator::make(
					Input::all(),
					$validationRules
				);

				$password 	=  Input::get('password');
				
				if($password !=''){
					if (preg_match('#[0-9]#', $password) && preg_match('#[a-zA-Z]#', $password)) {
					//if (preg_match('#[0-9]#', $password) && preg_match('#[a-zA-Z]#', $password) && preg_match('#[\W]#', $password)) {
						//$correctPassword	=	 Hash::make($password);
					}else{
						$errors = $validator->messages();
						$errors->add('password', trans("messages.user_management.password_help_message"));
						//$errors->add('password', 'Password must have be a combination of number, atleast one upper case letter, atleast one lower case letter');
						$response	=	array(
						'success' 		=> 0,
						'errors' 	=> $errors,
						'title' 		=> '',
						'url'			=>	''
						);
					return Response::json($response); die;
						//return Redirect::back()->withErrors($errors)->withInput();
					}
				}

				if (!$validator->fails()){
					
					$userEmail	=	Input::get('userEmail');
					
					######### array for save user #########
					$userData										=  array();
					######### image upload #########
					
					$userData['user_role_id']						=  FRONT_USER;
					$fullName										=  ucwords(str_replace(' ', '',Input::get('first_name')).' '.str_replace(' ', '',Input::get('last_name')));
					$userData['slug'] 								=  $fullName!='' ? $this->getSlug($fullName,'full_name','User') : '';
					$userData['email'] 								=  Input::get('email');
					$userData['password']	 						=  Hash::make(Input::get('password'));
					$userData['full_name'] 							=  ucwords($fullName);
					$userData['first_name'] 						=  Input::get('first_name');
					$userData['last_name'] 							=  Input::get('last_name');
					/*$userData['disability'] 							=  Input::get('disability');*/
					$validateString									=	md5(time() . Input::get('email'));
					$userData['validate_string']					=  (!$userEmail) ? $validateString : '';
					$userData['forgot_password_validate_string']	= '';
					$userData['active']								= 1;
					$userData['is_verified']						= (!$userEmail) ? 0 : 1;
					$userData['created_at']							= date('Y-m-d H:i:s');
					$userData['updated_at']							= date('Y-m-d H:i:s');
					######### save user and get insert id #########
					
					$insertId								=	User::insertGetId($userData);
					
					######### save user for newsletter subscriber #########
				
					$allErrors		=	'';
					$title			=	'';
					$url 			=	'';
					
					$emailExists	=	1;
					if(($userEmail) && $emailExists > 0 ){
						
						$userdata = array(
							'email' 					=> Input::get('email'),
							'password' 					=> Input::get('password'),
							'user_role_id' 				=> FRONT_USER,
							'deleted_at'				=> null	
						);
					
						if(Auth::attempt($userdata)) {
							
							if(Auth::user()->user_role_id != ADMIN_USER){								
								Userlogin::insert(array('user_id'=>Auth::user()->_id,'created_at'=> new mongoDate()));
							}
								$url	= route('after-login');
							if(!Auth::user()->is_profile_complete){
								$url  				=  route('after-login-channel','create-profile');
							}
						
						}
					}else{
						
						$this->sendVerificationLink($validateString);
						$allErrors	=	trans("Thank you. We have sent you email.Please check the link in that message to active your account.");
						$title		=	trans("Registration Success");
					}
					
					$response	=	array(
						'success' 		=> 1,
						'success_msg' 	=> $allErrors,
						'title' 		=> $title,
						'url'			=>	$url
					);
					return Response::json($response); die;	
				}else{
					
					/* $allErrors='<ul>';
					foreach ($validator->errors()->all('<li>:message</li>') as $message){
							$allErrors .=  $message; 
					}
					$allErrors .= '</ul>';  */
					$response	=	array(
						'success' => false,
						'errors' => $validator->errors()
					);
					return  Response::json($response); die;
				}	
			}
		}
	}// end postIndex()
	
	/** 
	 * Function for send verification link 
	 *
	 * @param $validate_string as validate string
	 *
	 * @return void
	 */
	public function sendVerificationLink($validate_string=null){
		$userDetail	=	User::where('active',1)->where('validate_string',$validate_string)->first();
		
		if($validate_string == '' ){
			return Redirect::to('/')
				->with('error', 'Invalid Link.');
		}
		if($userDetail){
			
			$userDetail			=	User::where('active',1)->where('validate_string',$validate_string)->first();
			$emailActions		=	EmailAction::where('action','=','account_verification')->get()->toArray();

			$emailTemplates		=	EmailTemplate::where('action','=','account_verification')->get(array('name','subject','action','body'))->toArray();
			$cons 				=   explode(',',$emailActions[0]['options']);
			$constants 			=   array();
			foreach($cons as $key => $val){
				$constants[] = '{'.$val.'}';
			}
			
			$username			=   Input::get('firstname');
			$loginLink   	 	= 	URL::to('account-verification/'.$validate_string);  
			$verificationUrl 	=   $loginLink;
			$loginLink 			=   '<a style="font-weight:bold;text-decoration:none;color:#000;" target="_blank" href="'.$loginLink.'">click here</a>';
		
			$subject 			=  $emailTemplates[0]['subject'];
			$rep_Array 			=  array($userDetail->full_name,$loginLink,$verificationUrl); 
			$messageBody		=  str_replace($constants, $rep_Array, $emailTemplates[0]['body']);
			$this->sendMail($userDetail->email,$userDetail->full_name,$subject,$messageBody);
			Session::flash('flash_notice', trans('A verification email has been sent to you please follow the steps mentioned in the email.')); 
			return Redirect::to('/');
		}else{
			return Redirect::to('/')
				->with('error', trans('Sorry, you are using wrong link.'));
		}
	}// end sendVerificationLink()

	/** 
	 * Function for user account verification
	 *
	 * @param $validate_string as validate string
	 *
	 * @return void
	 */
	 
	function accountVerification($validate_string = ''){
		$userInfo	=	User::where('validate_string' ,'=', $validate_string)->get(array('validate_string','is_verified','id','email','full_name'))->toArray();
		if(!empty($userInfo)){
			if($userInfo['0']['is_verified']==1){
				Session::flash('flash_notice', trans('Your account has been verified already.')); 
				return Redirect::route('front-sign-up');
			}else{
				User::where('id', $userInfo['0']['id'])->update(array(
					'validate_string'   =>  '',
					'is_verified' 		=>  1,
					'active' 			=>  1
				)); 
				
				
				
				$this->thanksForRegistration($userInfo);
				Session::flash('flash_notice', 'Your account has been verified. Please Login Now.'); 
				return Redirect::route('login-view');
			}
		}else{
			Session::flash('error', trans('Sorry, you are using wrong link.')); 
			return Redirect::to('/');
		}
	}// end accountVerification()
	
	/**
	 * Function for thanks for registration mail
	 * 
	 * @param $userDetails as user detail array of user
	 * 
	 * @return void. 
	 */
	function thanksForRegistration($userDetails){
		$emailActions		=	EmailAction::where('action','=','thanks_for_registration')->get()->toArray();
		$emailTemplates		=	EmailTemplate::where('action','=','thanks_for_registration')->get(array('name','subject','action','body'))->toArray();
		$cons = explode(',',$emailActions[0]['options']);
		$constants = array();
		foreach($cons as $key=>$val){
			$constants[] = '{'.$val.'}';
		}
		$username			=   $userDetails[0]['full_name'];
		$subject 			=  $emailTemplates[0]['subject']; 
		$rep_Array 			=  array($username); 
		$messageBody		=  str_replace($constants, $rep_Array, $emailTemplates[0]['body']);
		$this->sendMail($userDetails[0]['email'],$username,$subject,$messageBody);
	}// end thanksForRegistration()
}// end RegistrationController class
