<?php

namespace App\Http\Controllers;
use Auth,Blade,Config,Cache,Cookie,DB,File,Hash,Input,Mail,mongoDate,Redirect,Request,Response,Session,URL,View,Validator;
use App\Model\User;
use App\Model\Ceremony;
use App\Model\Booking;
use App\Model\Promocodes;
use App\Model\Userlogin;
use App\Model\EmailAction;
use App\Model\EmailTemplate;
use App\Model\Notification;


/**
 * Login Controller
 *
 * Add your methods in the class below
 *
 * This file will render views from views
 */ 
 
class ApiController extends BaseController {
	
	 
	/**
	 * Function for Listing User Request
	 *
	 * @param null
	 *
	 * @return json reponse. 
	 */
	 
	public function register(Request $request){
		$formData	= Input::all();
		$detail = (object) null; 
		
		 $response	= array();
		 $messages = array(
					'full_name.required' 	=> "Please Enter Name",
					'gender.required' 		=> "Please Enter Gender",
					'civil_id.required' 	=> "Please Enter Civil ID",
					'phone.required' 		=> "Please Enter Mobile No.",
					'email.required' 		=> "Please Enter Email Address",
					'faulty.required' 		=> "Please Enter Faulty",
					'device_id.required' 	=> "Please Enter Device ID",
					'device_type.required' 	=> "Please Enter Device Type",
					'device_token.required' => "Please Enter Device Token",
				);
			
			$validator = Validator::make(
					Input::all(),
					array(
						'full_name' 		=> 'required',
						'gender' 			=> 'required',
						'civil_id' 			=> 'required',
						'phone' 			=> 'required',
						'email' 			=> 'required|email|unique:users',
						'faulty'			=> 'required',
						'password'			=> 'required|min:6',
						'device_id'			=> 'required',	
						'device_type'		=> 'required',
						'device_token'		=> 'required',				
					), $messages
				);
		     if ($validator->fails()) 
		     {    
		     	$allErrors =  '';
		     	foreach ($validator->errors()->all() as $message){
							$allErrors =  $message; 
							break;
					}

                $response	=	array(
					'status' 	=> 0,
					'message'	=> $allErrors,
					'detail'    => $detail
				);
			
			}else{  

				$userRoleId				=  FRONT_USER ;
				$fullName				=  ucwords(Input::get('full_name'));
				$otp = mt_rand(1000, 9999);
			
				$obj 					=  new User;
				$validateString			=  md5(time() . Input::get('email'));
				$obj->validate_string	=  $validateString;					
				$obj->full_name 		=  $fullName;
				$obj->email 			=  Input::get('email');
				$obj->slug	 			=  $this->getSlug($fullName,'slug','User');
				$obj->password	 		=  Hash::make(Input::get('password'));
				$obj->user_role_id		=  $userRoleId;
				$obj->gender			=  Input::get('gender');
				$obj->phone				=  Input::get('phone');
				$obj->civil_id				=  Input::get('civil_id');
				$obj->faulty				=  Input::get('faulty');
				$obj->otp = $otp;
				
				
				$obj->save();
				$userId	=	$obj->id;			
				$encId			=	md5(time() . Input::get('email'));
				
				
				//mail email and password to new registered user
				
				$settingsEmail 	= Config::get('Site.email');
				$full_name		= $obj->full_name; 
				$email			= $obj->email;
				$password		= Input::get('password');
				$route_url      =  URL::to('verifyAccount/' . $validateString);
				$click_link   	=   $route_url;
				
				$emailActions	= EmailAction::where('action','=','user_registration_app')->get()->toArray();
				$emailTemplates	= EmailTemplate::where('action','=','user_registration_app')->get(array('name','subject','action','body'))->toArray();
			
				$cons 			= explode(',',$emailActions[0]['options']);
				$constants 		= array();
				
				foreach($cons as $key => $val){
					$constants[] = '{'.$val.'}';
				} 
				
				$subject 		= $emailTemplates[0]['subject'];
				$rep_Array 		= array($full_name,$email,$password,$click_link,$route_url); 
				$messageBody	= str_replace($constants, $rep_Array, $emailTemplates[0]['body']);
						
				$mail			= $this->sendMail($email,$full_name,$subject,$messageBody,$settingsEmail);	


				if(!empty($userId))
				{

					$device_info =array(
									'user_id'    	 => $userId,
									'device_id'      => Input::get('device_id'),
									'device_type'    => Input::get('device_type'),
									'device_token'   => Input::get('device_token'),
								);

					DB::table('device_info')->insert($device_info);


					$response	=	array(
					'status' 	=> 1,
					'message'	=> 'User Registered Successfully.',
					'detail'    => $detail
				    );
				}
			}
		
		    return Response::json($response); die;
	}


	public function verifyOtp(Request $request){
		$formData	= Input::all();
		$detail 	= new \stdClass();
		
		 $response	= array();
		 $messages = array(
					'email.required' 	=> "Please Enter Email Address",
					'otp.required' 		=> "Please Enter OTP",
				);
			
			$validator = Validator::make(
					Input::all(),
					array(
						'email' 		=> 'required|email',
						'otp'			=> 'required|min:4|max:5',
					), $messages
				);
		     if ($validator->fails()) 
		     {    

		     	$allErrors =  '';
		     	foreach ($validator->errors()->all() as $message){
							$allErrors =  $message; 
							break;
					}

                $response	=	array(
					'status' 	=> 0,
					'message'	=> $allErrors,
					'detail'    => $detail
				);
			
			}else{  

				$userInfo = DB::table('users')->where('email',Input::get('email'))->first();
				if(!empty($userInfo)){
					if($userInfo->is_verified == 1){
						$success = 0;
						$errors	= 'This account is already verified.';
						
					}else{
						if($userInfo->otp == Input::get('otp')){

							DB::table('users')
					            ->where('id', $userInfo->id)
					            ->update(['is_verified' => 1]);


					        $detail = User::where('id',$userInfo->id)->select('id','email','full_name','civil_id','faulty')
												->first();
												
								$sessions_data = DB::table('sessions')->where('user_id',$detail->id)->first();
								if(empty($sessions_data))
								{
									 DB::table('sessions')->insert(['user_id' => $detail->id, 'session_token' => bcrypt(mt_rand(100000, 999999))]);
								}
								else
								{
									DB::table('sessions')->where('user_id',$detail->id)->update(['session_token' => bcrypt(mt_rand(100000, 999999))]);
									
								}
								$sessions_data = DB::table('sessions')->where('user_id',$detail->id)->first();
								
								$detail->session_token = $sessions_data->session_token;

							$success = 1;
							$errors	= '';
						}else{
							$success = 0;
							$errors	= 'Incorrect OTP.';
						}
					}
				}else{
					$success = 0;
					$errors	= 'No Account is found with email address.';
				}


					$response	=	array(
					'status' 	=>$success,
					'message'	=> $errors,
					'detail'    => $detail ?  $detail : '',
				);
				
			}
		
		     return Response::json($response); die;
	}



	public function verifyAccount($validate_string=null){
		if($validate_string!="" && $validate_string!=null){
			$userDetail	=	User::where('active',1)->where('validate_string',$validate_string)->first();
			if(!empty($userDetail)){
				if($userDetail->is_verified == 1){
						$success = 0;
						$message	= 'The link has been used already.';
				}else{
					DB::table('users')
					    ->where('id', $userDetail->id)
					    ->update(['is_verified' => 1]);

					$success = 1;
					$message	= 'Your account has been successfully verified. You can login in app now.';
				}
			}else{
				$success = 0;
				$message = 'Invalid Link';
			}
		}else{
			$success = 0;
			$message = 'Invalid Access';
		}

		return View::make('verify_account' ,compact('success', 'message'));
	}

	
	/**
	 * Function for Get Sliders
	 *
	 * @param null
	 *
	 * @return json reponse. 
	 */
	 
	public function login(){$formData	= Input::all();
			$detail = (object) null; 
				
			if(!empty($formData)){
				
				####### Validation messages #######
				$messages = array(
					'email.required' 	=> 'Please Enter Email Address',
					//'email.email' 			=> trans('messages.login.front_email_valid_email_msg'),
					'password.required' 	=> 'Please Enter Password',
					'device_id' 			=> 'Please Enter Device Id',
					'device_type' 			=> 'Please Enter Device Type',
					'device_token' 			=> 'Please Enter Device Token',

				);
				####### validation rule ####### 
				$validator = Validator::make(
					Input::all(),
					array(
						'email' 			=> 'required',
						'password'			=> 'required',	
						'device_id'			=> 'required',
						'device_type'		=> 'required',
						'device_token'		=> 'required',
						
				
					),
					$messages
				);
				$redirectUrl	= '';
				if ($validator->fails()){
					//$allErrors='<ul>';
					foreach ($validator->errors()->all() as $message){
							$allErrors =  $message; 
							break;
					}
					//$allErrors .=  $validator->errors(); 
					//$allErrors .= '</ul>'; 
					
					$success = 0;
					$errorMessage	=	$allErrors;
					
				}else{
					
					
					$userdata = array(
						'email' 					=> Input::get('email'),
						'password' 					=> Input::get('password'),
						'user_role_id' 				=> 2,
					);

					
					$remember = (Input::has('remember_me')) ? true : 0; 
					if (Auth::attempt($userdata)) {
					#######  check that user is direct login or redirect to other side  ####### 


						if(Auth::user()->active == 1 && Auth::user()->is_verified == 1){
								$device_info =array(
									'user_id'    	 => Auth::user()->id,
									'device_id'      => Input::get('device_id'),
									'device_type'    => Input::get('device_type'),
									'device_token'   => Input::get('device_token'),
								);
							
								$success =	1;
								$errorMessage	=	'You are logged in successfully.';
								$detail = User::where('id',Auth::user()->id)->select('id','email','full_name','civil_id','faulty')
												->first();
												
								$sessions_data = DB::table('sessions')->where('user_id',$detail->id)->first();
								if(empty($sessions_data))
								{
									 DB::table('sessions')->insert(['user_id' => $detail->id, 'session_token' => bcrypt(mt_rand(100000, 999999))]);
								}
								else
								{
									DB::table('sessions')->where('user_id',$detail->id)->update(['session_token' => bcrypt(mt_rand(100000, 999999))]);
									
								}
								$sessions_data = DB::table('sessions')->where('user_id',$detail->id)->first();
								
								$detail->session_token = $sessions_data->session_token;
										

								$deviceId = Input::get('device_id');

								$DeviceAlreadyExist = DB::table('device_info')->where('user_id',Auth::user()->id)->count();
								if($DeviceAlreadyExist){

									// update code here
									DB::table('device_info')->where('user_id',Auth::user()->id)->update($device_info);

								}else{
									// insert code here
									DB::table('device_info')->insert($device_info);
								}


						}else if(Auth::user()->is_verified != 1){
							$success =	0;
							$errorArray	=	 "Your account has not been verified yet. Please verify your email and try again later.";
							$errorMessage	=	$errorArray;
							Auth::logout();
						}else{
							$success =	0;
							$errorArray	=	 trans("Your account has been disable please contact to admin");
							$errorMessage	=	$errorArray;
							Auth::logout();
						}
					}else{
						$success =	0;
						$errorArray		=	 trans("Please enter correct Email or Password");
						$errorMessage	=	$errorArray	;		
					}			
				}

				
				$response	=	array(
					'status' 	=>$success,
					'message'	=> $errorMessage,
					'detail'    => $detail ? $detail : '',
				);

				return Response::json($response); die;	
			}			
					
	}
	
	
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

					)
				);
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
					$userInfo 	= User::where('forgot_password_validate_string',$validate_string)->first();
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

	
	public function userLogout()
	{
		$formData	= Input::all();
		$detail = (object) null; 
		
		 $response	= array();
		 $messages = array(
					'user_id.required' 		    => "Please Enter User ID",
					'session_token'                 => "Please Enter Session Token" 
				);
			
			$validator = Validator::make(
					Input::all(),
					array(
						'user_id' 			=> 'required',
						'session_token'         => 'required' 
					 ), $messages
				);
		     if ($validator->fails()) 
		     {    
                $response	=	array(
					'status' 	=> 0,
					'message'	=> $validator->messages(),
					'detail'    => $detail
				);
			}
			else
            {  
				$user_id = $formData['user_id'];
				
				$session_token =  $formData['session_token'];
				$sessions_data = DB::table('sessions')->where('user_id',$user_id)->first();
				
				
				if(!empty($sessions_data)){
				if($sessions_data->session_token != $session_token)
				{
					$response	=	array(
								'status' 	=> -1,
								'detail'    => $detail,
								'message'   => 'Session Token Did Not Match'
								);
					return  Response::json($response);			
				}
				
											
				$results = User::find($user_id)->update(['is_login' => 0, 'is_temp_login' => 0]);
									
				if($results)
				{
							
							$response	=	array(
												'status' 	=> 1,
												'detail'	=> $detail,
												'message'   => "Logout successfully"
											);
				}
				
				else
				{
					$response	=	array(
										'status' 	=> 0,
										'detail'	=> $detail,
										'message'	=> 'Unsuccessfull'
									);
				}
				 
			}else{
				$response	=	array(
										'status' 	=> 0,
										'detail'	=> $detail,
										'message'	=> 'Invalid Request'
									);
			}
			}
			
			return Response::json($response); die; 	
	}
	
	
	public function userProfile()
	{
		$formData	= Input::all();
		$detail = (object) null; 
		
		 $response	= array();
		 $messages = array(
					'user_id.required' 		    => "Please Enter user ID",
					'session_token.required' 		    => "Please Enter Session Token"
				);
			
			$validator = Validator::make(
					Input::all(),
					array(
						'user_id' 			=> 'required',
						'session_token'         => 'required'
					 ), $messages
				);
		     if ($validator->fails()) 
		     {    
                $response	=	array(
					'status' 	=> 0,
					'message'	=> $validator->messages(),
					'detail'    => $detail
				);
			}
			else
            {  
				$user_id = $formData['user_id'];
				
				$session_token =  $formData['session_token'];
				$sessions_data = DB::table('sessions')->where('user_id',$user_id)->first();
				
				if(!empty($sessions_data)){
					if($sessions_data->session_token != $session_token)
					{
						$response	=	array(
									'status' 	=> -1,
									'detail'    => $detail,
									'message'   => 'Session Token Did Not Match'
									);
						return  Response::json($response);			
					}
					
					
					$results = User::where('id',$user_id)
								->select('id','slug','full_name','user_role_id','civil_id','faulty','last_name','address','city','phone')
								->first();
			
					if($results){
						$response	=	array(
											'status' 	=> 1,
											'message'	=> "",
											'detail'   => $results,
										);
					}else{
						$response	=	array(
											'status' 	=> 0,
											'message'	=> 'Unsuccessfull',
											'detail'	=> $detail,
										);
					}
			}else{
				$response	=	array(
										'status' 	=> 0,
										'message'	=> 'Invalid Request',
										'detail'	=> $detail,
									);
			}
		}
			
			return Response::json($response); die; 	
	}
	
	
	
	
	public function userUpdateProfile()
	{
		$formData	= Input::all();
		$detail = (object) null; 
		
		 $response	= array();
		 $messages = array(
					'user_id' 		    => "Please Enter User ID",
					'session_token' 		=> "Please Enter Session Token",
					'full_name' 		    => "Please Enter Name",
					'phone' 				=> "Please Enter Phone Number",
					'civil_id'              => "Please Enter Civil ID",
					'faulty'              => "Please Enter Faulty",
				);
			
			$validator = Validator::make(
					Input::all(),
					array(
						'user_id' 			=> 'required',
						'session_token'         => 'required',
						'full_name' 			=> 'required',
						'phone'         		=> 'required',
						'civil_id' 				=> 'required',
						'faulty' 				=> 'required',
					 ), $messages
				);
		     if ($validator->fails()) 
		     {    
                $allErrors =  '';
		     	foreach ($validator->errors()->all() as $message){
							$allErrors =  $message; 
							break;
					}

                $response	=	array(
					'status' 	=> 0,
					'message'	=> $allErrors,
					'detail'    => $detail
				);
			}
			else
            {  
				$user_id = $formData['user_id'];
				$full_name = $formData['full_name'];
				$phone = $formData['phone'];
				$civil_id = $formData['civil_id'];
				$faulty = $formData['faulty'];
				
				$session_token =  $formData['session_token'];
				$checkUserSession = $this->verifyUserSession($user_id, $session_token);
				if(is_array($checkUserSession)){
					return  Response::json($checkUserSession);
				}else{
					$results = User::where('id',$user_id)->update(['full_name' => $full_name, 'phone' => $phone,'civil_id' => $civil_id, 'faulty' => $faulty]);
				
				    $data = User::where('id',$user_id)->select('id','username','slug','full_name','user_role_id','civil_id','faulty','phone')
								->first();				
				
					if($results){
								$response	=	array(
													'status' 	=> 1,
													'message'	=> "Profile Updated Successfully",
													'detail'   => $data,

												);
					}else{
						$response	=	array(
											'status' 	=> 0,
											'message'	=> 'Unsuccessfull',
											'detail'	=> $detail
										);
					}
				}
				 
			}
			
			return Response::json($response); die; 	
	}


	public function ceremonyList(){
		$search     	= Input::get('name');
		$sort_by     	= Input::get('sort_by') ? Input::get('sort_by') : 'created_at';
		$sort_type     	= Input::get('sort_type') ? Input::get('sort_type') : 'desc';

		
		$formData	= Input::all();
		$detail = (object) null; 
		$response	= array();
		$messages = array(
					'user_id' 		    => "Please Enter User ID",
					'session_token' 	=> "Please Enter Session Token",
		);

		$validator = Validator::make(
				Input::all(),
				array(
						'user_id' 			=> 'required',
						'session_token'         => 'required',
					 ), $messages
				);
		     if ($validator->fails()){    
                $allErrors =  '';
		     	foreach ($validator->errors()->all() as $message){
							$allErrors =  $message; 
							break;
				}

                $response	=	array(
					'status' 	=> 0,
					'message'	=> $allErrors,
					'detail'    => $detail
				);
			}else{
				$user_id = $formData['user_id'];
				$session_token =  $formData['session_token'];

				$checkUserSession = $this->verifyUserSession($user_id, $session_token);

				if(is_array($checkUserSession)){
					return  Response::json($checkUserSession);
				}else{
					$sessions_data  =  DB::table('sessions')->where('user_id',$user_id)->first();	
					$userId 		=  $sessions_data->user_id;
					$userdetail  	=  DB::table('users')->select('gender')->where('id',$userId)->first();	 
					if(!empty($search)){
						$ceremonies  = Ceremony::where(function ($query) use ($userdetail) {
										    $query->where('ceremony_for', '=', 2)
										          ->orWhere('ceremony_for', '=', $userdetail->gender);
										})->where('name','like', '%' . $search . '%')
											->select('id','name','description','total_seats','remaining_seats','price','date','status','address','latitude','longitude','image')->orderBy($sort_by, $sort_type)->paginate(10);

						$response = array(
							'status' 	=> 1,
							'message'	=> "Success",
							'ceremonies' => $ceremonies->all(),
						);
					}else{
						$ceremonies = Ceremony::where(function ($query) use ($userdetail) {
										    $query->where('ceremony_for', '=', 2)
										          ->orWhere('ceremony_for', '=', $userdetail->gender);
										})->select('id','name','description','total_seats','remaining_seats','price','date','status','address','latitude','longitude','image')->orderBy($sort_by, $sort_type)->paginate(10);
						
						$response = array(
							'status' 	=> 1,
							'message'	=> "Success",
							'ceremonies' => $ceremonies->all(),
						);

					}
				}	
			}
	    return Response::json($response);
	}


	public function applyPromoCode(){
		$formData	= Input::all();
		$detail = (object) null; 
		
		$response	= array();
		$messages = array(
					'user_id' 		    => "Please Enter User ID",
					'session_token' 	=> "Please Enter Session Token",
					'promo_code' 		=> "Please Enter Promo Code",
		);
			
		$validator = Validator::make(
				Input::all(),
				array(
						'user_id' 			=> 'required',
						'session_token'         => 'required',
						'promo_code' 			=> 'required',
					 ), $messages
				);
		     if ($validator->fails()){    
                $allErrors =  '';
		     	foreach ($validator->errors()->all() as $message){
							$allErrors =  $message; 
							break;
				}

                $response	=	array(
					'status' 	=> 0,
					'message'	=> $allErrors,
					'detail'    => $detail
				);
			}else{  

				$user_id = $formData['user_id'];
				$promo_code = $formData['promo_code'];
				$session_token =  $formData['session_token'];

				$checkUserSession = $this->verifyUserSession($user_id, $session_token);
				if(is_array($checkUserSession)){
					return  Response::json($checkUserSession);
				}else{
					$promodate = Promocodes::where('code',$promo_code)->select('id','code','discount','status','date_from','date_to')->first();		
					$todayDate = date('Y-m-d');		
				
					if($promodate){
						if($todayDate < $promodate->date_from){
							$status = 0;
							$message = 'Promo Code is not available yet.';
							$detail = $detail;
						}elseif($todayDate > $promodate->date_to){
							$status = 0;
							$message = 'Promo Code is expired';
							$detail = $detail;
						}else{
							$status = 1;
							$message = 'Promo Code detail.';
							$detail = $promodate;
						}
					}else{
						$status = 0;
						$message = 'Invalid Promo Code.';
						$detail = $detail;
					}
				}
			}

			$response =	array(
					'status' 	=> $status,
					'message'	=> $message,
					'detail'   => $detail,
			);

			return Response::json($response); die; 	
	}


	public function bookCeremonySeats(){
		$formData	= Input::all();
		$detail = (object) null; 
		
		$response	= array();
		$messages = array(
					'user_id' 		=> "Please Enter User ID",
					'session_token' => "Please Enter Session Token",
					'ceremony_id' 	=> "Please Enter Ceremony Id",
					'price' 		=> "Please Enter Price",
					'discount' 		=> "Please Enter Discount",
					'final_price' 	=> "Please Enter Final Price",
					'seats' 		=> "Please Enter No. Of Seats",
		);
			
		$validator = Validator::make(
				Input::all(),
				array(
						'user_id' 			=> 'required',
						'session_token'     => 'required',
						'ceremony_id' 		=> 'required',
						'price' 			=> 'required',
						'discount' 			=> 'required',
						'final_price' 		=> 'required',
						'seats' 			=> 'required',
					 ), $messages
				);
		     if ($validator->fails()){    
                $allErrors =  '';
		     	foreach ($validator->errors()->all() as $message){
							$allErrors =  $message; 
							break;
				}

                $response	=	array(
					'status' 	=> 0,
					'message'	=> $allErrors,
					'detail'    => $detail
				);
			}else{  

				$user_id = $formData['user_id'];
				$session_token =  $formData['session_token'];

				$checkUserSession = $this->verifyUserSession($user_id, $session_token);
				if(is_array($checkUserSession)){
					return  Response::json($checkUserSession);
				}else{
					$ceremony_id = $formData['ceremony_id'];
					$price = $formData['price'];
					$discount = $formData['discount'];
					$final_price = $formData['final_price'];
					$seats = $formData['seats'];	
					$promocode_id = $formData['promocode_id'] ? $formData['promocode_id'] : 0;					

					$ceremony_detail = Ceremony::where('id',$ceremony_id)->select('id','name','total_seats','remaining_seats','price','date')->first();		
					$todayDate = date('Y-m-d');

					$userInfo = User::where('id',$user_id)->first();			

					if($userInfo){
						if($ceremony_detail){
							if($todayDate > $ceremony_detail->date){
								$status = 0;
								$message = 'Ceremony Event has been passed';
								$detail = $detail;
							}elseif($seats > $ceremony_detail->remaining_seats){
								$status = 0;
								$message = 'Ceremony Event Seats has been filled.';
								$detail = $detail;
							}else{
								$booking_no = 'booking' . $user_id . mt_rand(99999, 999999);
								$obj 					=  new Booking;
								$obj->user_id			=  $user_id;					
								$obj->ceremony_id 		=  $ceremony_id;
								$obj->booking_no 		=  $booking_no;
								$obj->slug	 			=  $this->getSlug($booking_no,'slug','Booking');
								$obj->price				=  $price;
								$obj->discount			=  $discount;
								$obj->final_price		=  $final_price;
								$obj->promocode_id		=  $promocode_id;
								$obj->seats				=  $seats;
								$obj->status			=  1;
								
								$obj->save();
								$bookingId	=	$obj->id;	


								$settingsEmail 	= Config::get('Site.email');
								$full_name		= $userInfo->full_name; 
								$email			= $userInfo->email;
								
								$emailActions	= EmailAction::where('action','=','booked_event_seats')->get()->toArray();
								$emailTemplates	= EmailTemplate::where('action','=','booked_event_seats')->get(array('name','subject','action','body'))->toArray();
							
								$cons 			= explode(',',$emailActions[0]['options']);
								$constants 		= array();
								
								foreach($cons as $key => $val){
									$constants[] = '{'.$val.'}';
								} 
								
								$subject 		= $emailTemplates[0]['subject'];
								$rep_Array 		= array($full_name,$email,$ceremony_detail->name, $booking_no,$final_price,$seats,); 
								$messageBody	= str_replace($constants, $rep_Array, $emailTemplates[0]['body']);
										
								$mail			= $this->sendMail($email,$full_name,$subject,$messageBody,$settingsEmail);	



								$status = 1;
								$message = 'Booking has been saved successfully.';
								$detail = $detail;
							}
						}else{
							$status = 0;
							$message = 'Invalid Ceremony Event.';
							$detail = $detail;
						}
					}else{
						$status = 0;
						$message = 'Invalid User Detail.';
						$detail = $detail;
					}	
				}
			}

			$response =	array(
					'status' 	=> $status,
					'message'	=> $message,
					'detail'   => $detail,
			);

			return Response::json($response); die; 	
	}
	
	
	
	/**
	 * Function is used to Send Notification
	 *
	 * @param $device_id as device id
	 *
	 * @param null
	 *
	 * @return url. 
	 */	 
	public function notification(){ 

	 	ECHO FCM_API_KEY;
	
	}



	private function verifyUserSession($user_id, $session_token){
				$detail = (object) null; 
				$user_data = DB::table('users')->where('id',$user_id)->count();
				$sessions_data = DB::table('sessions')->where('user_id',$user_id)->first();

				if(empty($user_data)){
					$status = 0;
					$message = "Invalid User Access.";
				}elseif(empty($sessions_data)){
					$status = -1;
					$message = "Invalid Session Token.";
				}elseif($sessions_data->session_token != $session_token){
					$status = -1;
					$message = "Session Token Did Not Match.";
				}else{
					$status = 1;
					$message = '';
				}


				if($status == 0 || $status == -1){
					return $response = array(
							'status' => $status,
							'message' => $message,
							'detail' => $detail
						);
				}else{
					return 1;
				}

	}


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
	
	public function sortArray( $data, $field ) {
		$field = (array) $field;
		uasort( $data, function($a, $b) use($field) {
			$retval = 0;
			foreach( $field as $fieldname ) {
				if( $retval == 0 ) $retval = strnatcmp( $a[$fieldname], $b[$fieldname] );
			}
			return $retval;
		} );
		return $data;
	}
	
	function array_sort_by_column(&$arr, $col, $dir = SORT_ASC) 
	{
			$sort_col = array();
			foreach ($arr as $key=> $row) {
				$sort_col[$key] = $row[$col];
			}

			array_multisort($sort_col, $dir, $arr);
	}
	
	
	function array_desc_sort_by_column(&$arr, $col, $dir = SORT_DESC) 
	{
			$sort_col = array();
			foreach ($arr as $key=> $row) {
				$sort_col[$key] = $row[$col];
			}

			array_multisort($sort_col, $dir, $arr);
	}

	
		
}// end LoginController class
