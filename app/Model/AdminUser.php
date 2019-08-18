<?php
namespace App\Model;

use Eloquent;

/**
 * AdminUser Model
 */
class AdminUser extends Eloquent {
	


	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';
	
	protected $dates = ['deleted_at'];
	
	/**
 * Function for  bind UserProfile model   
 *
 * @param null 
 *
 * return query
 */	
	public function userProfile(){
		return $this->hasMany('App\Model\UserProfile','user_id');
	}//end userProfile()
	
	
	/**
	 * Function for  bind Userlogin model   
	 *
	 * @param null 
	 *
	 * return query
	 */			
	public function userLastLogin(){
        return $this->hasOne('App\Model\Userlogin','user_id','_id')->orderBy('created_at','desc')->limit(1);
	}//end userLastLogin()
	
	
	
	
	
}// end AdminUser class
