<?php 
namespace App\Model; 
use Eloquent;
use Session;


/**
 * Blog Model
 */
 
class Setdepartures extends Eloquent   {
	
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
 
	protected $table = 'trips';

	
	/**
	* scope function
	*
	* @param $query 	as query object
	* 
	* @return query
	*/	
	public static function scopeOfPage($query)
    {
        return $query->where('is_active',1);
    } //end scopeOfPage()
	
	
	

	
} // end Blog class