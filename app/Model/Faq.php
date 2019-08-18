<?php 
namespace App\Model;  
use Eloquent;

/**
 * Faq Model
 */
class Faq extends Eloquent {

	/**
	 * The database table used by the model
	 *
	 * @var string
	 */
	protected $table = 'faqs';

	/**
	* belongsTo function for bind AdminDropDown model  
	*
	* @param null
	* 
	* @return query
	*/		
	public  function category(){
		return $this->belongsTo('App\Model\DropDown')->select('name','id');
	} //end category()
	
}// end Faq class
