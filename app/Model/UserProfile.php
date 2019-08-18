<?php
namespace App\Model;
use Eloquent;
/**
 * UserProfile Model
 */
class UserProfile extends Eloquent{

/**
 * The database table used by the model.
 *
 * @var string
 */
	protected $table = 'user_profiles';
	
/* only these field will create or update by model */	

	  protected $fillable	 =  array('user_id','field_value','field_name');

	   public $timestamps	 =  false; 
	   
/* Function for  bind AdminUser model   
 *
 * @param null 
 *
 * return query
 */
 
 public function userDetail(){
	 return $this->hasOne('AdminUser','id','user_id')
					->select('id','full_name','username','email')
					->where('is_verified',1)
					->where('active',1)
					->where('is_deleted',0);
	}//end user_detail()

}// end UserProfile class
