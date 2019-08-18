<?php 
namespace App\Model; 
use Eloquent;


/**
 * Slider Model
 */
 
class Slider extends Eloquent   {
	
/**
 * The database table used by the model.
 *
 * @var string
 */
 
	protected $table = 'sliders';

	
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

/**
 * function for find result form database function
 *
 * @param $fields 	as fields which need to select
 * 
 * @return array
 */	
	public static function getResult($fields = array()){
		$result	=	Slider::OfPage()->select($fields)->orderBy('order','asc')->get()->toArray();
		return $result;
	
	} //end getResult()
	
} // end Slider class