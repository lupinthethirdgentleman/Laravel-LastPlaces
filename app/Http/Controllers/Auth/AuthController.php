<?php
namespace App\Http\Controllers\Auth;
use Auth,Blade,Config,Cache,Cookie,DB,File,Hash,Input,Mail,mongoDate,Redirect,Request,Response,Session,URL,View,Validator;
use App\Http\Controllers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use App\Model\User,Illuminate\Routing\Controller;



class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'getLogout']);
    }
	
	public function getSlug($title, $modelName,$limit = 30){
		$slug 		= 	 substr(\Str::slug($title),0 ,$limit);
		$Model		=	"\App\Model\\$modelName";
		$slugCount 	=  count($Model::where('slug', 'regexp', "/^{$slug}(-[0-9]*)?$/i")->get());
		return ($slugCount > 0) ? $slug."-".$slugCount : $slug;
	}//end getSlug()

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }
	 /**
	 * Redirect the user to the facebook authentication page.
	 *
	 * @return Response
	 */
	public function redirectToProvider($provider)
    {
		
		if(Session::has('SocialUserRole')){
			Session::forget('SocialUserRole');
		}
		
		$userType				=	Input::get('type');
		$userRoleId				=	$userType ;
		
		// check user type exist 
		if(in_array($userType,Config::get('user_roles'))){
			$userRoleId			=	strtoupper($userRoleId);
		}
		
		Session::put('SocialUserRole',$userRoleId); 
		
		switch ($provider) {
			case 'facebook':
				  return Socialite::driver($provider)->fields(['first_name', 'last_name', 'email', 'gender', 'verified','birthday','address'])->redirect();
				break;
			case 'twitter':
				 return Socialite::driver($provider)->redirect();
				break;
			case 'google':
				 return Socialite::driver($provider)->redirect();
				break;
		}
      
    }

    /**
     * Obtain the user information from facebook.
     *
     * @return Response
     */
    public function handleProviderCallback($provider)
    {	
		$errorFacebook	=	Input::get('error');
		$errorTwitter	=	Input::get('denied');
		if($errorFacebook!='' || $errorTwitter!=''){
			return Redirect::to('/');
		}

		$SocialUserRole	=	Session::get('SocialUserRole');	
        $user 			=	Socialite::driver($provider)->user();
		$socialField	=	$provider.'_id'; 
		$first_name 	= 	'';
		$last_name 		= 	'';
		$email 			= 	'';
		switch ($provider) {
			case 'facebook':
				$first_name 				= 	$user->firstname;
				$last_name 					= 	$user->lastname;
				$email 						= 	$user->email;
				$socialId 					= 	$user->id; 
				$profilePic 				= 	$user->avatar; 
				break;
			case 'twitter':
				$name 							= 	explode(" ",$user->name,2);
				$first_name						=	isset($name[0]) ? $name[0] :'';
				$last_name						=	isset($name[1]) ? $name[1] :'';
				$socialId 						= 	$user->id;
				$profilePic 					= 	$user->avatar; 
				break;
			case 'google':
				$first_name 				= 	$user->given_name;
				$last_name 					= 	$user->family_name;
				$socialId 					= 	$user->id; 
				$profilePic 				= 	$user->picture; 
				break;
		}
		
		if($email!=''){		
			$emailCount	=	User::where('email',$email)->count();
			if($emailCount>0){
				User::where('email',$email)->update(array("$socialField"=>$socialId));
			}
		}
		
		$userAlreadyRegister		=	User::where($socialField,$socialId)->count();
		if($userAlreadyRegister==0){
			$userData										=	array();
			$userData['email']								=	$email;
			$userData['username']							= 	'';
			$userData['password']							= 	'';
			$userData['first_name']							=	$first_name;
			$userData['last_name']							=	$last_name;
			$userData['full_name']							= 	$first_name.' '.$last_name;
			$userData['date_of_birth']						=	'';
			$userData['user_role_id']						= constant ($SocialUserRole); 
			$userData['phone_number']						=	'';
			$userData[$socialField]	 						=	$socialId;
			$userData['device_id']	 						=	'';
			$userData['device_token']	 					=	'';
			$userData['validate_string']					=	'';
			$userData['forgot_password_validate_string']	=	'';
			$userData['active']								=	1;
			$userData['is_verified']						=	1;
			$userData['is_deleted']							=	0;
			$userData['slug'] 								=  $this->getSlug($userData['full_name'],'User');
			$userData['file_path'] 							=  strtoupper(date('M'). date('Y')).'/'.$userData['slug'];
			$userData['created_at']							=	new mongoDate();
			$userData['updated_at']							=	new mongoDate();
			$userImage    									= 	file_get_contents($profilePic);
			$userImageName     								= 	$socialId ."_$provider.jpg";
			$newFolder     									= 	strtoupper(date('M'). date('Y')).DS.$userData['slug'].DS;	  
			$folderPath										=	USER_IMAGE_ROOT_PATH.$newFolder.USER_PROFILE_IMG_FOLDER;
		  
			// get profile image  from social url  
			if(!File::exists($folderPath)) {
				File::makeDirectory($folderPath, $mode = 0777,true);
			}
			file_put_contents($folderPath.'/'.$userImageName,$userImage);
			$userData['image'] = $userImageName;
			User::insert($userData);
		}
						
		$userId		=	User::where($socialField,$socialId)->pluck('_id'); 
		Auth::loginUsingId($userId);
		$yourURL	=	URL::to('/dashboard');
		echo ("<script>location.href='$yourURL'</script>");
		
    }
 
}
