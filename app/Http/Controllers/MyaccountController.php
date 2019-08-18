<?php
namespace App\Http\Controllers;
use Auth,Blade,Config,Cache,Cookie,DB,File,Hash,Input,Mail,mongoDate,Redirect,Request,Response,Session,URL,View,Validator,Uploader;
use App\Model\User,App\Model\DropDown,App\Model\Connection;
use CustomHelper;
//use Illuminate\Http\Request;


/**
 * Login Controller
 *
 * Add your methods in the class below
 *
 * This file will render views from views
 */ 
 
class MyaccountController extends BaseController {
	
	
	/**
	 * Function for user dashboard page
	 * 
	 * @param null
	 * 
	 * return view page 
	 * 
	 */
	public function index(){
		$title = 'Dashboard';
		//$cobj = new User;
		//$response = $cobj->getRatingandReview(Auth::user()->id)->paginate(3);
		$response 		= DB::table('reviews as a')
				        ->join('hcp as b', 'a.hcp_id' ,'=' ,'b.id')
				        ->where('a.user_id', '=', Auth::user()->id)
				        ->where('a.status', '=', 1)
				       	->paginate(3);

		$searchKeyword	=	Input::get('searched_keyword');
		
		return View::make('myaccount.index',compact('title','searchKeyword','response'));
		
	}//end index()
	
	/**
	 * Function for load chart elements
	 * 
	 * @param null
	 * 
	 * return view page 
	 * 
	 */
	public function loadChartElement(){
		if(Request::ajax()){
			
			$element	=	Input::get('element');
			$sortingYear=	Input::get('sorting_year');
			$month	=	date('m');
			$year	=	date('Y');
			
			for ($i = 0; $i < 12; $i++) {
				if(Input::get('is_sort') != '' && $sortingYear !=''){
					$months[] = date("$sortingYear-m", strtotime( date( 'Y-01-01' )." +$i months"));
				}else{
					$months[] = date("Y-m", strtotime( date( 'Y-01-01' )." +$i months"));
					//$months[] = date("Y-m", strtotime( date( 'Y-01-01' )." +$i months"));
				}
			}
		
			//$months		=	array_reverse($months);
			$months		=	$months;
			$num		=	0;
			$num1		=	0;
			$num2		=	0;
			$num3		=	0;
			$num4		=	0;
			$totalMarketPlace	=	array();
			$contestData		=	array();
			$allOrders  		=  array();
			$totalConnections   =  array();
			$totalFunds   		=  array();
			
			switch($element){
				
			case 'contests' :
			
				/* data  For contest */
				foreach($months as $month){
				
					$monthStartDate					=	 new MongoDate(strtotime(date('Y-m-01 00:00:00', strtotime($month))));
					$monthEndDate					=	 new MongoDate(strtotime(date('Y-m-t 23:59:59', strtotime($month))));
					
					$contestData[$num]['month']		=	 $month;
					
					$contestData[$num]['contest'] 	=	MarketPlaceContest::where('user_id',Auth::user()->_id)
														->where('created_at', '>' , $monthStartDate)
														->where('created_at','<' , $monthEndDate)
														->count();
																	
					
					$num ++;
				}
				
				return View::make('myaccount.contest_chart',compact('contestData','sortingYear'));
			break;
			
			case 'marketplace' :
				
				foreach($months as $month){
					
					$monthStartDate					=	 new MongoDate(strtotime(date('Y-m-01 00:00:00', strtotime($month))));
					$monthEndDate					=	 new MongoDate(strtotime(date('Y-m-t 23:59:59', strtotime($month))));
				
					$totalMarketPlace[$num1]['month']	=	 $month;
					$marketplaceCondition				=		Marketplace::where('user_id',Auth::user()->_id)->where('is_deleted','!=',1);
					
					
					$marketplaceCondition 	=		$marketplaceCondition
															->where('created_at', '>' , $monthStartDate)
															->where('created_at','<' , $monthEndDate);
					
																	
					
					$totalMarketPlace[$num1]['marketplace']  = $marketplaceCondition->count();
						
																
					$num1 ++;
				}
				return View::make('myaccount.marketplace_chart',compact('totalMarketPlace','sortingYear'));
			break;
			
			case 'orders' :
				
				foreach($months as $month){
					$monthStartDate				=	 new MongoDate(strtotime(date('Y-m-01 00:00:00', strtotime($month))));
					$monthEndDate				=	 new MongoDate(strtotime(date('Y-m-t 23:59:59', strtotime($month))));
					
					$allOrders[$num2]['month']	=	 $month;
					
					$allOrders[$num2]['orders']    =    OrderItem::where('seller_id',Auth::user()->_id)
														->where('created_at', '>' , $monthStartDate)
														->where('created_at','<' , $monthEndDate)
														->count();
					$num2 ++;
				}
				
				return View::make('myaccount.orders_chart',compact('allOrders','sortingYear'));
			break;
			
			case 'connections' :
				
				foreach($months as $month){
					$monthStartDate				=	 new MongoDate(strtotime(date('Y-m-01 00:00:00', strtotime($month))));
					$monthEndDate				=	 new MongoDate(strtotime(date('Y-m-t 23:59:59', strtotime($month))));
					
					$totalConnections[$num3]['month']	  =	 $month;
					
					$totalConnections[$num3]['connection']    =    Connection::where('user_id',Auth::user()->_id)
																	->where('created_at', '>' , $monthStartDate)
																	->where('created_at','<' , $monthEndDate)
																	->count();
					$num3 ++;
				}
				
				return View::make('myaccount.connections_chart',compact('totalConnections','sortingYear'));
			break;
			
			case 'funds' :
				
				foreach($months as $month){
					$monthStartDate				=	 new MongoDate(strtotime(date('Y-m-01 00:00:00', strtotime($month))));
					$monthEndDate				=	 new MongoDate(strtotime(date('Y-m-t 23:59:59', strtotime($month))));
					
					$totalFunds[$num4]['month']	=	 $month;
					$totalFunds[$num4]['funds'] =    OrderItem::where('created_at', '>' , $monthStartDate)
														->where('created_at','<' , $monthEndDate)
														->where(function($query){
															$query->where(function($query){
																		$query->where('seller_id',Auth::user()->id)
																		->where('refund_detail','exists',false);
																	})
																	->orWhere('refund_detail.paid_to',Auth::user()->_id);
																})
											
														->sum('seller_amount');

					$num4 ++;
				}
				
				return View::make('myaccount.funds_chart',compact('totalFunds','sortingYear'));
			break;
			
			}
		}
	}//end loadChartElement()
	
	/**
	 * Function for user edit profile
	 * 
	 * @param null
	 * 
	 * return view page 
	 * 
	 */
	 
	public function editProfile(){
		if(Request::ajax()){
			$formData	=	Input::all();
			
			if(!empty($formData)){
				
				//~ if(Auth::user()->password == '' && Input::get('password') == '' ){
					//~ $response	=	array(
						//~ 'success' => false,
						//~ 'errors'  => 'The password field is required.'
					//~ );
					//~ return  Response::json($response); die;
				//~ }
				######### validation rule #########
				$validationRules	=	array(
					'first_name' 		=> 'required',
					'last_name' 		=> 'required',
					'email' 			=> 'required|email|unique:users,email,'.Auth::user()->_id.',_id,deleted_at,NULL',
					'birthday_day'  	=> 'required|integer|Between:1,31', 
					'birthday_month'  	=> 'required|Integer|Between:1,12', 
					'birthday_year'  	=> 'Required|Integer|Between:'.BIRTHDAY_START_YEAR.','.date("Y"),
					'gender'  			=> 'Required',
					//'category'  		=> 'Required',
				);
				
				$get_password         = Input::get('password');
                $get_confirm_password = Input::get('confirm_password');
                
                if($get_password != '' || $get_confirm_password != '') {
                    $rules          = array(
                        'password' 			=> 'required|min:6',
                        'confirm_password'  => 'required|min:6|same:password'
                    );
                    $validationRules = array_merge($validationRules, $rules);
                }
                
                
				$validator = Validator::make(
					Input::all(),
					$validationRules
				);
				if (!$validator->fails()){
					$mainCategoryArray	= array();
					$subCategoryArray 	= array();
					$uniqueMainCategory	= array();
					$uniqueSubCategory 	= array();
					
					if(!empty(Input::get('category'))){
						foreach(Input::get('category') as $value){
							$category 			=	explode(',',$value);
							$service_category	=	(isset($category[0])) ? $category[0]:'';
							$sub_category		=	(isset($category[1])) ? $category[1]:'';
							
							array_push($mainCategoryArray,$service_category);
							array_push($subCategoryArray,$sub_category);
						}
						
						$uniqueMainCategory	 =	array_unique($mainCategoryArray);
						$uniqueSubCategory	 =	array_unique($subCategoryArray);
					}
					
					######### array for save user #########
					$userData										=  array();
					######### image upload #########
					$userData['email'] 								=  Input::get('email');
					$userData['first_name'] 						=  Input::get('first_name');
					$userData['last_name'] 							=  Input::get('last_name');
					$userData['full_name'] 							=  ucwords(Input::get('first_name').' '.Input::get('last_name'));
					$userData['birthday_day'] 						=  (int)Input::get('birthday_day');
					$userData['birthday_month'] 					=  (int)Input::get('birthday_month');	
					$userData['birthday_year'] 						=  (int)Input::get('birthday_year');		
					$userData['gender'] 							=  Input::get('gender');
					$userData['location'] 							=  Input::get('location');
					$userData['city'] 								=  Input::get('city');
					$userData['country'] 							=  Input::get('country');
					$userData['country_code'] 						=  Input::get('country_code');
					$userData['service_category'] 					=  array_values($uniqueMainCategory);
					$userData['service_sub_category'] 				=  array_values($uniqueSubCategory);
					$userData['updated_at']							=  new mongoDate();
					
					if(!empty(Input::get('password')) && !empty(Input::get('confirm_password')) && Input::get('password') == Input::get('confirm_password')){
						$userData['password'] 						=  Hash::make(Input::get('password'));
					} 
					######### update user #########
					
					User::where('_id',Auth::user()->id)->update($userData);
					
					if(Auth::user()->is_profile_complete == 1){
						$url	=	route('after-login'); 
					}else{
						$url	=	route('after-login-channel','create-profile'); 
					}
					
					$response	=	array(
						'success' 	=>1,
						'url'		=> $url
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
	
		$categoryDropDown	=	DropDown::where('dropdown_type','market-category')->orderBy('name','asc')->select('name','_id','sub_category')->get()->toArray();
		
		return View::make('myaccount.edit_profile',compact('categoryDropDown'));
		
	}//end editProfile()
	
	/**
	 * Function for display after login page 
	 *
	 * @param null
	 *
	 * @return response array. 
	 */

	public function afterLoginPage($slug=''){
		$isDisplayPopup	 	=	0;
		$element			= 	'';	
		if(in_array($slug,array('create-profile'))){
			$isDisplayPopup		=	1;
		}
		$currentDate			=	strtotime(date(Config::get("Reading.date")));
		
		$trendingMarketplace		=	MarketPlace::with('userDetail','marketPlaceReviews','myCollection')
										->Conditions()
										//->where('user_id','!=',Auth::user()->id)
										->where('is_featured',1)
										->where('quantity','!=',0)
										->where('expire_date','>=',$currentDate)
										->get();
		
		$currentDate			=	strtotime(date(Config::get("Reading.date")));
		$trendingCampaigns		=	Campaign::Conditions()->with('campaignImages')
									//->where('user_id','!=',Auth::user()->id)
									->where('duration','>=',$currentDate)
									->get();
									
		$popularCauses			=	Causes::Conditions()->where('is_active',ACTIVE)
											->where('user_id','!=',Auth::user()->id)
											->get();
		
		
		return View::make("myaccount.after_login",compact('isDisplayPopup','slug','trendingMarketplace','trendingCampaigns','popularCauses'));
	} //end  afterLoginPage()
	
	/**
	 * Function for add users's profile information
	 *
	 * @param null
	 *
	 * @return response array. 
	 */
	public function completeProfile(){
		if(Request::ajax()){
			$isSkipped	=	Input::get('is_skipped');
			if(isset($isSkipped) && $isSkipped == 1){
				$user 						=	   User::find(Auth::user()->id);
				$user->is_profile_complete	=		1;
				$user->save();	
				$redirectUrl  			=   route('after-login');
				$response	=	array(
					'success' 		=>	1,
					'redirect_url'  =>  $redirectUrl
				);
				return  Response::json($response); die;		
					
			}else{
			
				$formData				=	Input::all();
				$redirectUrl			=	'';	
				if(!empty($formData)){
					unset($formData['_token']);
					
					$validator = Validator::make(
						$formData,
						array(
							'tag_line' 			=> 'required|max:'.ABOUT_ME_WORD_LIMIT,
						 ),
						 array(
							'tag_line.required' 	 	=> 'The about me field is required.',
							'tag_line.max' 	 			=> 'The about me may not be greater than '.ABOUT_ME_WORD_LIMIT.' characters.'
						 )
					);
					
					if ($validator->fails()){		
						$allErrors='<ul>';
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
						$user 					=	   User::find(Auth::user()->id);
						$user->tag_line			=	   Input::get('tag_line');
						
						$user->is_profile_complete	=		1;
						$user->save();	
						$redirectUrl  			=   route('after-login');
						$response	=	array(
							'success' 		=>	1,
							'redirect_url'  =>  $redirectUrl
						);
					}
					return  Response::json($response); die;						
				}	
			}
		}	
	} //end completeProfile() 	
	
	/**
	 * Function for update notification seen status
	 *
	 * @param null
	 *
	 * @return response array. 
	 */
	public function updateNotificationSeenStatus(){
		if(Request::ajax()){ 
			$notificationId	= Input::get('notification_id');
			if($notificationId != ''){
				$obj	=	Notification::find($notificationId);
				$obj->is_seen	=	1; 
				$obj->save();
			}else{
				Notification::where('user_id',Auth::user()->_id)->update(array('is_seen' => 1));
			}
			$response	=	array(
								'success' 		=>	true,
							);
			return  Response::json($response); die;			
		}
	}
	
	/**
	 * Function for update notification read status
	 *
	 * @param null
	 *
	 * @return response array. 
	 */
	public function updateNotificationReadStatus(){
		if(Request::ajax()){ 
			$notificationId	= Input::get('notification_id');
			if($notificationId != ''){
				$obj				=	Notification::find($notificationId);
				$obj->is_read		=	1; 
				$obj->is_seen		=	1;  
				$obj->save();
			}
			$response	=	array(
								'success' 				=>	true,
							);
			return  Response::json($response); die;			
		}
	}
	
	/**
	 * Function for deleting notification
	 *
	 * @param null
	 *
	 * @return response array. 
	 */
	public function deleteNotification(){
		if(Request::ajax()){ 
			$notificationId	= Input::get('notification_id');
			if($notificationId != ''){
				Notification::where('_id',$notificationId)->delete();
			}
			$successMessage	=	trans("messages.MyAccount.delete_notification_notice_msg");
			
			$response	=	array(
								'success' 				=>	true,
								'message' 				=>	$successMessage,
							);
			return  Response::json($response); die;			
		}
	}// end deleteNotification()
	
	/**
	 * Function for show notification(when cilck on notification icons) and also for get count of notification  
	 *
	 * @param null
	 *
	 * @return response array. 
	 */
	public function listUserNotification(){
		if(Request::ajax()){ 
			if(Input::get('is_popup_btn_clicked') != ''){
				$notificationCount		=	Notification::where('user_id',Auth::user()->_id)->update(array('is_seen' => 1));
				$notifications			=	Notification::where('user_id',Auth::user()->_id)->where('is_read',0)->select('title','message','url','is_seen','is_read')->orderBy('created_at','desc')->take(5)->get();
				$response	=	array(
						'success' 			 =>	true,
						'html'				 => (String) View::make('elements.user_notification_popup',compact('notifications')),
				);
			}else{
				$notificationCount		=	Notification::where('user_id',Auth::user()->_id)->where('is_seen',0)->count();
				$response	=	array(
							'success' 			 =>	true,
							 'countNotification' => $notificationCount
							);
			}
			return  Response::json($response); die;						
		}
	}//end listUserNotification()
	
	/**
	 * Function for show all user notification 
	 *
	 * @param null
	 *
	 * @return notification view page. 
	 */
	public function viewAllNotification(){
		$notifications			=	Notification::where('user_id',Auth::user()->_id)->select('_id','title','message','url','created_at','is_read');
		
		// order management
		if(Input::get('notification_type') == 'read'){
			$notifications = $notifications->where('is_read',1);
		}elseif(Input::get('notification_type') == 'unread') {
			$notifications = $notifications->where('is_read',0);
		}
		
		$notifications 				=  $notifications->orderBy('created_at','desc')->get();
		if(Request::ajax()){
			return View::make('myaccount.view_all_notification_element',compact('notifications'));
		}else{
			return View::make('myaccount.view_all_notifications',compact('notifications'));
		}
	}// end viewAllNotification()
	

	/**
	 * Function for updating user profile image
	 *
	 * @param $userId as User Id
	 *
	 * @return response array. 
	 */	
	public function updateProfileImage($userId){
		$user_id		=	$userId;
		$formData		=	Input::all();
		$validator = Validator::make(
			$formData,
			array(
				'uploadfile' 				=> 'required|mimes:'.IMAGE_EXTENSION,
			 ),
			 array(
				'uploadfile.required' 		=> 'Please upload your image.',
			 )
		);
		if($validator->fails()){
			$response = array('message'=>'error','file_name' => '');
		}else{
			if(Input::hasFile('uploadfile')){
				$time			=	 	$userId.time();
				$folderName		=		USER_IMAGES_ROOT_PATH.Auth::user()->slug.DS.USER_PROFILE_IMG_FOLDER;
				$extension 		=	 	Input::file('uploadfile')->getClientOriginalExtension();
				$userImageName	=		$time.rand().'user-profile-image.'.$extension;		
				if(Auth::user()->image!=''){
					@unlink(USER_IMAGES_ROOT_PATH.Auth::user()->slug.'/'.USER_PROFILE_IMG_FOLDER.'/'.Auth::user()->image);
				}
				if(!File::exists($folderName)){
					File::makeDirectory($folderName, $mode = 0777,true);
				}
				if(!File::exists(USER_IMAGES_ROOT_PATH.Auth::user()->slug.DS.USER_PROFILE_IMG_FOLDER)){
					File::makeDirectory( USER_IMAGES_ROOT_PATH.Auth::user()->slug.DS.USER_PROFILE_IMG_FOLDER, $mode = 0777,true);
				}
				if(Input::file('uploadfile')->move($folderName, $userImageName)){
					ini_set('max_execution_time', 0);
					$user 			=	   User::find($userId);
					$user->image    =  	   $userImageName;
					$user->save();	
					$response = array('message'=>'success','file_name' => $userImageName);
				}
			}
		}
		echo json_encode($response);
		exit;		
	}//end updateProfileImage()	
	
	
	/**
	 * Function for updating user cover image
	 *
	 * @param NULL
	 *
	 * @return response array. 
	 */	
	public function updateCoverImage(){
		
		$user_id		=	Auth::user()->_id;
		$formData		=	Input::all();
		$validator = Validator::make(
			$formData,
			array(
				'cover_image1' 				=> 'mimes:'.IMAGE_EXTENSION,
			 )
		);
		if($validator->fails()){
			$response = array('message'=>'error','file_name' => '');
		}else{
			
			$imageSize		=	getimagesize(Input::file('cover_image1'));
			$width			=	$imageSize[0];
			$height			=	$imageSize[1];
			$imageCrop		= false;
			
			//~ if($width > 1486 && $height > 500){
				//~ $imageCrop	= true;
			//~ }
			
			if($height > 500){
				$imageCrop	= true;
			}
			
			if(Input::hasFile('cover_image1')){
				
				$time			=	 	Auth::user()->_id.time();
				$folderName		=		USER_IMAGES_ROOT_PATH.Auth::user()->slug.DS.USER_COVER_IMG_FOLDER;
				$extension 		=	 	Input::file('cover_image1')->getClientOriginalExtension();
				$userImageName	=		$time.rand().'-user-cover-image.'.$extension;		
				if(Auth::user()->cover_image!=''){
					@unlink(USER_IMAGES_ROOT_PATH.Auth::user()->slug.'/'.USER_COVER_IMG_FOLDER.'/'.Auth::user()->cover_image);
				}
				if(!File::exists($folderName)){
					File::makeDirectory($folderName, $mode = 0777,true);
				}
				if(!File::exists(USER_IMAGES_ROOT_PATH.Auth::user()->slug.DS.USER_COVER_IMG_FOLDER)){
					File::makeDirectory( USER_IMAGES_ROOT_PATH.Auth::user()->slug.DS.USER_COVER_IMG_FOLDER, $mode = 0777,true);
				}
				if(Input::file('cover_image1')->move($folderName, $userImageName)){
					ini_set('max_execution_time', 0);
					$user 				  =	   User::find(Auth::user()->_id);
					$user->cover_image    =    $userImageName;
					$user->cover_type     =    USER_COVER_IMAGE;
					$user->cover_thumb    =    '';
					$user->cover_image_position	=	'';
					
					$user->save();	
					$response = array('message'=>'success','file_name1' => $userImageName,'imageCrop'=>isset($imageCrop) ? $imageCrop : '');
				}
			}
			
		}
		echo json_encode($response);
		exit;			
	}//end updateProfileImage()	
	
	
	
	/**
	 * Function for save position of cropped image 
	 * 
	 * @param null
	 * 
	 * return response 
	 * 
	 */
	public function cropCoverImage(){
		if(isset($_POST['pos'])){
			$userObj	=	User::find(Auth::user()->_id);
			$userObj->cover_image_position	=	$_POST['pos'];
			$userObj->save();
			$response	=	array('success' => true,);
			return  Response::json($response); die;
		}
	}//end cropCoverImage()
	
	/**
	 * Function for updating user cover image
	 *
	 * @param NULL
	 *
	 * @return response array. 
	 */	
	 
	public function updateCoverVideo(){
		
		$userId		=	Auth::user()->_id;
		$formData	=	Input::all();
		
		$userCover 			=  User::where('user_id',$userId)->where('cover_type',USER_COVER_VIDEO)->select('cover_image','cover_thumb')->first();
		
		if(!empty($userCover)){
			if($userCover->cover_image != ''){
				 $extension 	=	pathinfo($userCover->cover_image	,PATHINFO_EXTENSION);
				 $fileName 		=	pathinfo($userCover->cover_image	,PATHINFO_FILENAME);
				 
				 @unlink(USER_IMAGES_ROOT_PATH.Auth::user()->slug.'/'.USER_COVER_VIDEO_MP4_URL.'/'.$fileName.'.mp4');
				 @unlink(USER_IMAGES_ROOT_PATH.Auth::user()->slug.'/'.USER_COVER_VIDEO_ORG_URL.'/'.$userCover->cover_image);
				 @unlink(USER_IMAGES_ROOT_PATH.Auth::user()->slug.'/'.USER_COVER_VIDEO_WEBM_URL.'/'.$fileName.'.webm');
				 @unlink(USER_IMAGES_ROOT_PATH.Auth::user()->slug.'/'.USER_COVER_VIDEO_THUMB_URL.'/'.$userCover->cover_thumb);
				 
				 User::where('user_id',Auth::user()->_id)->where('cover_type',USER_COVER_VIDEO)->delete();
			}
		}
		
		$extension 		=	 Input::file('cover-video')->getClientOriginalExtension();
		$rules 			=    explode(',',VIDEO_EXTENSION);
		
		if(!in_array($extension,$rules)){
			$response = array('message'=>'error','file_name' => '');
		}else{
			
			if(Input::hasFile('cover-video')){
			
				$time			=	 	Auth::user()->id.time();
				$folderName		=		USER_IMAGES_ROOT_PATH.Auth::user()->slug.DS.USER_COVER_VIDEO_ORG_URL;
				
				
				$userVideoName		=		$time.'_user_video.'.$extension;
				$fileName			=		$time.'_user_video';
				$userCoverThumb		=		$time.'_user_video.jpg';
				
				
				if(!File::exists($folderName)){
					File::makeDirectory($folderName, $mode = 0777,true);
				}
			
				if(!File::exists(USER_IMAGES_ROOT_PATH.Auth::user()->slug.DS.USER_COVER_VIDEO_MP4_URL)){
					File::makeDirectory( USER_IMAGES_ROOT_PATH.Auth::user()->slug.DS.USER_COVER_VIDEO_MP4_URL, $mode = 0777,true);
				}
				if(!File::exists(USER_IMAGES_ROOT_PATH.Auth::user()->slug.DS.USER_COVER_VIDEO_WEBM_URL)){
					File::makeDirectory( USER_IMAGES_ROOT_PATH.Auth::user()->slug.DS.USER_COVER_VIDEO_WEBM_URL, $mode = 0777,true);
				}
				if(!File::exists(USER_IMAGES_ROOT_PATH.Auth::user()->slug.DS.USER_COVER_VIDEO_THUMB_URL)){
					File::makeDirectory( USER_IMAGES_ROOT_PATH.Auth::user()->slug.DS.USER_COVER_VIDEO_THUMB_URL, $mode = 0777,true);
				}		
				
				
		
				if(Input::file('cover-video')->move($folderName, $userVideoName)){
					ini_set('max_execution_time', 0);
					
					$width	=	'1450';
					$height	=	'500';	
						
					$source			=	USER_IMAGES_ROOT_PATH.Auth::user()->slug.DS.USER_COVER_VIDEO_ORG_URL.$userVideoName;			
					$target			=	USER_IMAGES_ROOT_PATH.Auth::user()->slug.DS.USER_COVER_VIDEO_MP4_URL.$fileName.'.mp4';
					$this->convertToMp4($source, $target, $width, $height);			
					$target			=	USER_IMAGES_ROOT_PATH.Auth::user()->slug.DS.USER_COVER_VIDEO_THUMB_URL.$userCoverThumb;
					$this->generateThumbnail($source, $target, $width, $height);
					$target			=	USER_IMAGES_ROOT_PATH.Auth::user()->slug.DS.USER_COVER_VIDEO_WEBM_URL.$fileName.'.webm';
					$this->convertToWebm($source, $target, $width, $height);
		
		
					$user 				  =	   User::find($userId);
					$user->cover_image    =    $userVideoName;
					$user->cover_thumb    =    $userCoverThumb;
					$user->cover_type     =    USER_COVER_VIDEO;
					
					$user->save();	
					
					$response = array('message'=>'success','thumb_name' => $userCoverThumb,'file_name' =>$fileName );
				}
			}
		}
		
		echo json_encode($response);
		exit;			
	}//end updateCoverVideo()	
	
	/**
	 * Function for uploading user twist off video
	 *
	 * @param null
	 *
	 * @return response array. 
	 */	
	function uploadTwistOffVideo(){
		
		$userTwistOff 			=  UserTwistOff::where('user_id',Auth::user()->_id)->first();
		
		if(!empty($userTwistOff)){
			if($userTwistOff->video != '' && $userTwistOff->video != ''){
				 $extension 	=	pathinfo($userTwistOff->video	,PATHINFO_EXTENSION);
				 $fileName 		=	pathinfo($userTwistOff->video	,PATHINFO_FILENAME);
				 @unlink(USER_IMAGES_ROOT_PATH.Auth::user()->slug.'/'.USER_TWIST_VIDEO_MP4_URL.'/'.$fileName.'.mp4');
				 @unlink(USER_IMAGES_ROOT_PATH.Auth::user()->slug.'/'.USER_TWIST_VIDEO_ORG_URL.'/'.$userTwistOff->video);
				 @unlink(USER_IMAGES_ROOT_PATH.Auth::user()->slug.'/'.USER_TWIST_VIDEO_WEBM_URL.'/'.$fileName.'.webm');
				 @unlink(USER_IMAGES_ROOT_PATH.Auth::user()->slug.'/'.USER_TWIST_VIDEO_THUMB_URL.'/'.$userTwistOff->image);
				 $userTwistOff 			=  UserTwistOff::where('user_id',Auth::user()->_id)->delete();
			}
		}
		
		//include uploader class for multiple image upload on wall post
		include('class.uploader.php');
		
		if(Request::ajax()){ 
			
		$folderName		=		USER_IMAGES_ROOT_PATH.Auth::user()->slug.DS.USER_TWIST_VIDEO_ORG_URL;
		
		//if folder not exist
		if(!File::exists($folderName)){
			File::makeDirectory($folderName, $mode = 0777,true);
		}
		if(!File::exists(USER_IMAGES_ROOT_PATH.Auth::user()->slug.DS.USER_TWIST_VIDEO_MP4_URL)){
			File::makeDirectory( USER_IMAGES_ROOT_PATH.Auth::user()->slug.DS.USER_TWIST_VIDEO_MP4_URL, $mode = 0777,true);
		}
		if(!File::exists(USER_IMAGES_ROOT_PATH.Auth::user()->slug.DS.USER_TWIST_VIDEO_THUMB_URL)){
			File::makeDirectory( USER_IMAGES_ROOT_PATH.Auth::user()->slug.DS.USER_TWIST_VIDEO_THUMB_URL, $mode = 0777,true);
		}
		if(!File::exists(USER_IMAGES_ROOT_PATH.Auth::user()->slug.DS.USER_TWIST_VIDEO_WEBM_URL)){
			File::makeDirectory( USER_IMAGES_ROOT_PATH.Auth::user()->slug.DS.USER_TWIST_VIDEO_WEBM_URL, $mode = 0777,true);
		}
		
		//get the actual file name from input 
		$file		=	isset($_FILES['twist_image']['name'][0]) ? $_FILES['twist_image']['name'][0] : '';
		//find the file extension
		
		if($file){
			$extension 	=	pathinfo($file	,PATHINFO_EXTENSION);
			$newFileName	=	Auth::user()->_id.rand().time().'twist_off';
		}
	
		$uploader = new Uploader();
		
		$data = $uploader->upload($_FILES['twist_image'], array(
			'uploadDir' => $folderName	, //Upload directory
			'title' 	=> ($newFileName) ? $newFileName : array('name'), //New file name 
		));
		
		$videoName		=	$data['data']['metas'][0]['name']; // full name
		$fileName		=	$newFileName; // without extension
		$imageName		=	$newFileName.'.jpg'; // image name
		
		//covert the video 
		$this->covertVideo($videoName,$fileName,$imageName);	
		// Save into database
		
		$userTwistOff			=	new UserTwistOff();
		$userTwistOff->user_id  =  Auth::user()->_id;
		$userTwistOff->image  	=  $imageName;
		$userTwistOff->video  	=  $videoName;
		$userTwistOff->save();	
		
		$response	=	array(
			'success' 					=>	1,
			'video_thumbnail'   		=>	$imageName
		);
							
		return  Response::json($response); die;
		}
	}//end uploadTwistOffVideo()
	
	
	/**
	 * Function for convert the uploaded videos
	 *
	 * @param $videoName,$fileName and $imageName
	 *
	 * @return response array. 
	 */
	public function covertVideo($videoName,$fileName,$imageName){
		$width	=	1098;
		$height	=	500;		
		$source			=	USER_IMAGES_ROOT_PATH.Auth::user()->slug.DS.USER_TWIST_VIDEO_ORG_URL.$videoName;			
		$target			=	USER_IMAGES_ROOT_PATH.Auth::user()->slug.DS.USER_TWIST_VIDEO_MP4_URL.$fileName.'.mp4';
		$this->convertToMp4($source, $target, $width, $height);			
		$target			=	USER_IMAGES_ROOT_PATH.Auth::user()->slug.DS.USER_TWIST_VIDEO_THUMB_URL.$imageName;
		$this->generateThumbnail($source, $target, $width, $height);
		$target			=	USER_IMAGES_ROOT_PATH.Auth::user()->slug.DS.USER_TWIST_VIDEO_WEBM_URL.$fileName.'.webm';
		$this->convertToWebm($source, $target, $width, $height);
		
	}//end covertVideo()
	
	/**
	 * Function for display all market place 
	 *
	 * @param null
	 *
	 * @return response array. 
	 */
	
	public function trendingMarketPlace(){
		$currentDate			=	strtotime(date(Config::get("Reading.date")));
		$search					=	Input::get('search');
		$offset					=	(int)Input::get('offset',0);
		$limit					=	(int)Input::get('limit',Config::get('Reading.record_front_per_page'));
		
		$dropDownSelect			=	DropDown::query()->where('dropdown_type','market-category')->orderBy('name','asc')->lists('name','_id')->toArray();
		
		$trendingCondition		=	MarketPlace::with('userDetail','marketPlaceReviews','myCollection')
										->Conditions()
										->where('user_id','!=',Auth::user()->id)
										->where('is_deleted','!=',1)
										->orderBy('_id','desc')
										->where('expire_date','>=',$currentDate);
										
		
		$trendingCampaignsCount	=	$trendingCondition->count();
		
		
		/* For Market Place Category Or SubCategory Searching Start*/		
		if(Input::get('category_id') != '' && Input::get('sub_category_id') != ''){
			Session::put('marketplace_by_collection_category_id',Input::get('category_id'));
			Session::put('marketplace_by_collection_sub_category_id',Input::get('sub_category_id'));
			$val1	=	Input::get('category_id');
			$val2	=	Input::get('sub_category_id');
			$trendingCondition->where('category_id',$val1)->where('sub_category_id',$val2);
			$trendingCampaignsCount	  		=	$trendingCondition->count();
		}elseif(Input::get('category_id') != ''){
			Session::put('marketplace_by_collection_category_id',Input::get('category_id'));
			if(Session::has('marketplace_by_collection_sub_category_id')){
				Session::forget('marketplace_collection_by_sub_category_id');
			}
			$val1	=	Input::get('category_id');
			$trendingCondition->where('category_id',$val1);
			$trendingCampaignsCount  		=	$trendingCondition->count();
		}
		
		$trendingCampaigns		=	$trendingCondition->skip($offset)->take($limit)->get();
		
		if(Input::get('load_more')==1){
			return View::make('trending.more_trending_marketplace',compact('trendingCampaignsCount','trendingCampaigns','offset','limit','dropDownSelect'));
		}else{
			if(Session::has('marketplace_by_collection_category_id')){
				Session::forget('marketplace_by_collection_category_id');
			}
			if(Session::has('marketplace_by_collection_sub_category_id')){
				Session::forget('marketplace_by_collection_sub_category_id');
			}
			return View::make('trending.trending_marketplace',compact('trendingCampaignsCount','trendingCampaigns','offset','limit','dropDownSelect'));
		}
	}//end trendingMarketPlace()
	
	/**
	 * Function for display all causes 
	 *
	 * @param null
	 *
	 * @return response array. 
	 */
	 
	public function popularCauses(){
		$offset					=	(int)Input::get('offset',0);
		$limit					=	(int)Input::get('limit',Config::get('Reading.record_front_per_page'));
		$search					=	Input::get('search');
		$causesCondition		=	Causes::Conditions()->where('is_active',ACTIVE);
		$causesCount			=	$causesCondition->count();
		//searching
		if($search!=''){
			Session::put('search_buying',$search);
			$causesCondition->where('title','like',"%$search%");
			$causesCount 		=	$causesCondition->count();
		}else{
			if(Input::get('load_more')==1){
				if(Session::has('search_buying') && $search!=''){
					$causesCondition->where('title','like',"%$search%");
					$causesCount	=	$causesCondition->count();
				}
			}
		}
		$popularCauses			=	$causesCondition->skip($offset)->take($limit)->get();
		if(Input::get('load_more')==1){
			return View::make('trending.more_popular_causes',compact('causesCount','popularCauses','offset','limit'));
		}else{
			if(Session::has('search_buying')){
				Session::forget('search_buying');
			}
			return View::make('trending.popular_causes',compact('causesCount','popularCauses','offset','limit'));
		}
	}//end popularCauses()
	
	
	/**
	 * Function for view all campaigns
	 *
	 * @param null
	 *
	 * @return response array. 
	 */
		public function viewAllCampaign(){
		
		$currentDate			=	strtotime(date(Config::get("Reading.date")));
		$search					=	Input::get('search');
		$offset					=	(int)Input::get('offset',0);
		$limit					=	(int)Input::get('limit',Config::get('Reading.record_front_per_page'));
		
		$dropDownSelect			=	DropDown::query()->where('dropdown_type','campaign-category')->orderBy('name','asc')->lists('name','_id')->toArray();
		
		$trendingCondition		=	Campaign::with('userDetail','campaignImages')
										// ->where('user_id','!=',Auth::user()->id)
										->Conditions()
										->orderBy('created_at','desc')
										->where('duration','>=',$currentDate);
										
		$trendingCampaignsCount	=	$trendingCondition->count();
		
		
		/* For Market Place Category Or SubCategory Searching Start*/		
		if(Input::get('category_id') != '' && Input::get('sub_category_id') != ''){
			Session::put('campaign_by_collection_category_id',Input::get('category_id'));
			Session::put('campaign_by_collection_sub_category_id',Input::get('sub_category_id'));
			$val1	=	Input::get('category_id');
			$val2	=	Input::get('sub_category_id');
			$trendingCondition->where('category_id',$val1)->where('sub_category_id',$val2);
			$trendingCampaignsCount	  		=	$trendingCondition->count();
		}elseif(Input::get('category_id') != ''){
			Session::put('campaign_by_collection_category_id',Input::get('category_id'));
			if(Session::has('campaign_by_collection_sub_category_id')){
				Session::forget('campaign_collection_by_sub_category_id');
			}
			$val1	=	Input::get('category_id');
			$trendingCondition->where('category_id',$val1);
			$trendingCampaignsCount  		=	$trendingCondition->count();
		}
		
		$trendingCampaigns		=	$trendingCondition->skip($offset)->take($limit)->get();
		
		if(Input::get('load_more')==1){
			return View::make('trending.more_trending_campaign',compact('trendingCampaignsCount','trendingCampaigns','offset','limit','dropDownSelect'));
			
		}else{
			if(Session::has('campaign_by_collection_category_id')){
				Session::forget('campaign_by_collection_category_id');
			}
			if(Session::has('campaign_by_collection_sub_category_id')){
				Session::forget('campaign_by_collection_sub_category_id');
			}
			return View::make('trending.trending_campaign',compact('trendingCampaignsCount','trendingCampaigns','offset','limit','dropDownSelect'));
		}
	
	}//end viewAllCampaign()
	
	
	public function searchDashboardData($searchKeyword =''){
		
		return View::make('myaccount.index',compact('searchKeyword'));
	
	}

	public function manageProfile(){
		//$cobj = new User;
		$title = 'Manage password';
		//$response = $cobj->getUserProfileData(Auth::user()->id);
		$response = '';
		return View::make('myaccount.edit_profile',compact('response','title'));
	}
	public function saveProfile(){
			$formData	=	Input::all();
			$validationRules = array(
					    'password' => 'required|min:6|same:cpassword',
					    'cpassword' => 'required|min:6'
					);

			$validator = Validator::make(
					Input::all(),
					$validationRules
			);

			$password 	=  Input::get('password');
				
			if($password !=''){
				if (preg_match('#[0-9]#', $password) && preg_match('#[a-zA-Z]#', $password) && preg_match('#[\W]#', $password)) {
					//$correctPassword	=	 Hash::make($password);
				}else{
					$errors = $validator->messages();
					$errors->add('password', trans("messages.user_management.password_help_message"));
					$response	=	array('success' => 0,'errors'=>$errors);
					return  Response::json($response);
				}
			}
			
			if($validator->fails()){
				$allErrors='<ul>';
				foreach ($validator->errors()->all('<li>:message</li>') as $message){
						$allErrors .=  $message; 
				}
				$allErrors .= '</ul>'; 
				$response	=	array('success' => 0,'errors'=>$allErrors);
				return  Response::json($response);	 
			}else{
				$cobj = new User;
				$response 	 = $cobj->updateUserProfileData($formData,Auth::user()->id);
				$response	 =	array('success' => 1);
				return  Response::json($response);
			}
	}
}//end MyAccountController class
