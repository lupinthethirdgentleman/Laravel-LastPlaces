<?php
namespace App\Model; 

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Eloquent;
use DB;
use Hash;

class User extends Eloquent implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;
	
	
	protected $dates = ['deleted_at'];

    /**
     * The database table used by the model.
     *
     * @var string
     */


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];
    
    
	  /* Scope Function 
	 *
	 * @param null 
	 *
	 * return query
	 */
 
	public function scopeActiveConditions($query){
		return $query->where('active',1)->where('is_verified',1);
		
	}//end ScopeActiveCondition
  
	 /**
	 * hasMany function for bind userLastLogin model 
	 *
	 * @param null 
	 *
	 * return query
	 */
	public function userLastLogin($query){
		return $this->hasMany('App\Model\userLastLogin','user_id');
	}//end userLastLogin
 	
 	//to get the user data while editing profile
 	public function getUserProfileData($id){
 		$users = DB::select('select * from users where id = ?',array($id));
 		return $users;
 	}
 	public function updateUserProfileData($post,$id){
 		$updateDetails=array(
						    'password' => Hash::make($post['password'])
						);	
 		DB::table('users')->where('id', $id)->update($updateDetails);
 		
 	}

	public function users(){
        return $this->hasMany('App\Model\User','id');
    }//end users()
	 /*function getRatingandReview($id){
 		$Review = DB::select('select a.*,b.full_name from reviews as a inner join hcp as b on a.hcp_id = b.id where a.user_id = ?',array($id));
 		return $Review;
 	}*/
}
