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
 
class LoginController extends BaseController {

	public function loginView(){
		$title = 'Login';
		if(Auth::check()){
			return Redirect::to('dashboard');
		}
		return View::make('login',compact('title'));
	}
	
	/**
	 * Function for login users
	 *
	 * @param null
	 *
	 * @return json reponse. 
	 */
	public function login(){
		if(Request::ajax()){
			$formData	=	Input::all();

			//print_r($formData); exit;
			if(!empty($formData)){
				####### Validation messages #######
				$messages = array(
					'email.required' 		=> trans('messages.login.front_email_required_msg'),
					'email.email' 			=> trans('messages.login.front_email_valid_email_msg'),
					'password.required' 	=> trans('messages.login.front_password_required_msg'),
					'password.min' 			=> trans('messages.login.front_password_minimum_msg'),
				);
				
				####### validation rule ####### 

				$validator = Validator::make(
					Input::all(),
					array(
						'email' 			=> 'required|email',
						'password'			=> 'required|min:6',
						'g-recaptcha-response' => 'required|captcha'					
					),
					$messages
				);
				$redirectUrl	= '';
				if ($validator->fails()){
					$allErrors='<ul>';
					foreach ($validator->errors()->all('<li>:message</li>') as $message){
							$allErrors .=  $message; 
					}
					$allErrors .= '</ul>'; 
					
					$success = false;
					$errorMessage	=	$allErrors;
					
				}else{
					$userdata = array(
						'email' 					=> Input::get('email'),
						'password' 					=> Input::get('password'),
						'user_role_id' 				=> FRONT_USER,
						'is_deleted'				=> 0	
					);
					$remember = (Input::has('remember_me')) ? true : false; 
					$datee = date('Y-m-d H:i:s');
					if (Auth::attempt($userdata,$remember)) {
					#######  check that user is direct login or redirect to other side  ####### 

						if(Auth::user()->active == 1){					
							if(Auth::user()->is_verified == 1){
								####### login table entry #######
								if(Auth::user()->user_role_id != ADMIN_USER){								
									$userLoginCount	=	Userlogin::where('user_id',Auth::user()->id)->count();
									if($userLoginCount > 0){
										Userlogin::where('user_id',Auth::user()->id)->update(array('created_at'=> $datee));
									}else{
										Userlogin::insert(array('user_id' =>Auth::user()->id ,'created_at'=> $datee));
									}
								}
								if(Session::has('backUrl')){

									$url  	=  Session::get('backUrl');
									Session::forget('backUrl');

								}else{
									
									$lastLogin = DB::table('users_login')
												->where('user_id',Auth::user()->id)
												->whereNotNull('updated_at')
												->count();

									if($lastLogin >0 ){

										$url  	=  route('user-dashboard');	
										
									}else{

										$url  = URL::to('/pages/help');
									}
									
								}
								
								
								$success =	1;
								$errorMessage	=	'';
								$redirectUrl	=  $url	; 	
								
							}else{ ####### if user is not varified
								$link				=	URL::to('/send-verification-link');
								$validateString		=	Auth::user()->validate_string;
								$success 			=	false;
								$errorVerification	=	'Email verification is required. Please check your inbox for verification details or  to resend verification code';
								$clickhere			=	'Click here';
								$errorArray			=	$errorVerification.' <a style="border:none;" href="'.$link.'/'.$validateString.'">'.$clickhere.'</a>';
								$errorMessage		= 	$errorArray;	
								Auth::logout();
							}
						}else{
							$success =	false;
							$errorArray	=	 'Your account has been disabled. Please contact to admin.';
							$errorMessage	=	$errorArray;
							Auth::logout();
						}
					}else{
						$success =	false;
						$errorArray	=	 'Invalid Credentials.';
						$errorMessage	=	$errorArray	;	
					}			
				}
				
				$response	=	array(
					'success' 	=>$success,
					'errors'	=> $errorMessage,
					'redirect'	=> $redirectUrl
				);
				return Response::json($response); die;	
			}			
		}			
	}// end login()

	/**
	 * Function is used to send email for forgot password process
	 *
	 * @param null
	 *
	 * @return json response. 
	 */		
	public function sendPassword(){
		$title = 'Forget password';
		if(Request::ajax()){
			$validator = Validator::make(
				Input::all(),
				array(
					'email' 	=> 'required|email',
					'g-recaptcha-response' => 'required|captcha'	
				)
			);
			if ($validator->fails()){
				$allErrors='<ul>';
				foreach ($validator->errors()->all('<li>:message</li>') as $message){
						$allErrors .=  $message; 
				}
				$allErrors .= '</ul>'; 
				$response	=	array(
					'success' => false,
					'errors' => $allErrors
				);
			}else{
				$email		=	Input::get('email');   
				$userDetail	=	User::where('email',$email)->first();
				
				if(!empty($userDetail)){
					if($userDetail->deleted_at == '' || isset($userDetail->deleted_at)){
						if($userDetail->active == 1 ){
							if($userDetail->is_verified == 1 ){
								$forgot_password_validate_string	= 	md5($userDetail->email);
								User::where('email',$email)->update(array('forgot_password_validate_string'=>$forgot_password_validate_string));
								
								$settingsEmail 		=  Config::get('Site.email');
								$email 				=  $userDetail->email;
							
								$full_name			=  $userDetail->full_name;  
								$route_url      	=  URL::to('reset_password/'.$forgot_password_validate_string);
								$varify_link   		=   $route_url;
								
								$emailActions		=	EmailAction::where('action','=','forgot_password')->get()->toArray();
								$emailTemplates		=	EmailTemplate::where('action','=','forgot_password')->get(array('name','subject','action','body'))->toArray();
								$cons = explode(',',$emailActions[0]['options']);
								$constants = array();
								foreach($cons as $key=>$val){
									$constants[] = '{'.$val.'}';
								}
								
								$subject 			=  $emailTemplates[0]['subject'];
								$rep_Array 			= array($full_name,$varify_link,$route_url); 
								$messageBody		=  str_replace($constants, $rep_Array, $emailTemplates[0]['body']);
								$this->sendMail($email,$full_name,$subject,$messageBody,$settingsEmail);
								
								$response	=	array(
									'success' 	=>1
								);
							}else{
								
								$link	=	URL::to('/send-verification-link');
								$validateString	=	$userDetail->validate_string;
								$errorVerification	=	trans("Email verification is required. Please check your inbox for verification details or  to resend verification code ");
								$clickhere			=	trans("click here");
								
								$errorMessage	= $errorVerification.' <a style="color:#00FF7F" href="'
								.$link.
								'/'.$validateString.'">'.$clickhere.'</a>';		
									
								$response	=	array(
									'success' => false,
									'errors' => $errorMessage
								);
							}					
						}else{
							$response	=	array(
								'success' => false,
								'errors' => trans("Your account has been disabled.")
							);
						}	
					}else{
						$response	=	array(
							'success' => false,
							'errors' => trans("Your email not resgister with us.")
						);
					}
				}else{
					$response	=	array(
						'success' => false,
						'errors' => trans("Your email is not registered with us.")
					);
				}
			}
			return  Response::json($response); die;
		}else{
			if(Auth::check() && Auth::user()->user_role_id == FRONT_USER){
				return Redirect::to('dashboard');
			}
			return View::make('login.forgot_password',compact('title'));
		}
	}// sendPassword()	
	
	/**
	 * Function is used to show reset password page
	 *
	 * @param $validate_string as validate strind which stored in database
	 *
	 * @return view page. 
	 */	
	public function resetPassword($validate_string=null){
		$title = 'Reset password';
		if($validate_string!="" && $validate_string!=null){
			$userDetail	=	User::where('active',1)->where('forgot_password_validate_string',$validate_string)->first();
			if(!empty($userDetail)){
				return View::make('login.reset_password' ,compact('validate_string'));
			}else{
				return Redirect::to('/')
						->with('error', trans('Sorry, you are using wrong link.'));
			}
		}else{
			
			return View::make('login.reset_password' ,compact('validate_string','title'));
			//return Redirect::to('/')->with('error', trans('messages.wrong.link'));
		}
	}// end resetPassword()
	
	/**
	 * Function is used to reset user current password
	 *
	 * @param $validate_string as validate string which stored in database
	 *
	 * @return url. 
	 */	
	public function resetPasswordSave($validate_string=null){
		if(Request::ajax()){ 
			$formData	=	Input::all();
			if(!empty($formData)){
				$newPassword		=	Input::get('new_password');
				$validator = Validator::make(
					Input::all(),
					array(
						'new_password' 				=> 'required|min:6|confirmed',
						'new_password_confirmation' => 'required|min:6',
						'g-recaptcha-response' => 'required|captcha'

					)
				);

				if($newPassword !=''){
					if (preg_match('#[0-9]#', $newPassword) && preg_match('#[a-zA-Z]#', $newPassword)) {
					//if (preg_match('#[0-9]#', $password) && preg_match('#[a-zA-Z]#', $password) && preg_match('#[\W]#', $password)) {
						$correctPassword	=	 Hash::make($newPassword);
					}else{
						$errors = $validator->messages();
						$errors->add('password', trans("messages.user_management.password_help_message"));
						//$errors->add('password', 'Password must have be a combination of number, atleast one upper case letter, atleast one lower case letter');
						$response	=	array(
						'success' 		=> 0,
						'errors' 	=> $errors
						);
					return Response::json($response); die;
						//return Redirect::back()->withErrors($errors)->withInput();
					}
				}

				if ($validator->fails()){
					 $allErrors	= '<ul>';
					foreach ($validator->errors()->all('<li>:message</li>') as $message)
					{
							$allErrors .=  $message; 
					}
					$allErrors .= '</ul>'; 
					$response	=	array(
						'success' => false,
						'errors' => $allErrors
					);
					return  Response::json($response);
				}else{
					$userInfo = User::where('forgot_password_validate_string',$validate_string)->first();
					User::where('forgot_password_validate_string',$validate_string)
							->update(array(
								'password'							=>	Hash::make($newPassword),
								'forgot_password_validate_string'	=>	'',
						));
					
					####### mail to user that password has been change successfully  #######
					$settingsEmail 		= Config::get('Site.email');			
					$action				= "reset_password";
					$emailActions		=	EmailAction::where('action','=','reset_password')->get()->toArray();
					$emailTemplates		=	EmailTemplate::where('action','=','reset_password')->get(array('name','subject','action','body'))->toArray();
					$cons 				= 	explode(',',$emailActions[0]['options']);
					$constants 			= 	array();
					foreach($cons as $key=>$val){
						$constants[] = '{'.$val.'}';
					}
					$subject 			=  $emailTemplates[0]['subject'];
					$rep_Array 			= 	array($userInfo->full_name); 
					$messageBody		=  str_replace($constants, $rep_Array, $emailTemplates[0]['body']);		 
					$this->sendMail($userInfo->email,$userInfo->full_name,$subject,$messageBody,$settingsEmail);
					$response	=	array('success' => 1);
					return  Response::json($response);	 
				}
			}
		}
	} // end resetPasswordSave()	 

	/**
	 * Function is used to logout from account
	 *
	 * @param null
	 *
	 * @return url. 
	 */	 
	public function logout(){  
		if(Auth::check()){
			Auth::logout();
		}
		return Redirect::to('/');
	}// end logout()
		
}// end LoginController class
