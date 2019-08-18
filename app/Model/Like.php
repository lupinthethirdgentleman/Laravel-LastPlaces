<?php 
namespace App\Model; 
use Eloquent;

	/**
	 * Company Model
	 */
 
class Like extends Eloquent   {
	
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	 
	protected $table = 'hcp_likes';
	public $timestamps = false;

	protected $fillable = ['user_id','hcp_id','like'];
} // end Company class
