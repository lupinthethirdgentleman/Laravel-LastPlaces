<?php 
namespace App\Model; 
use Eloquent,Session;

/**
 * DropDown Model
*/
 
class DropDown extends Eloquent  {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	*/
 
	protected $table = 'dropdown_managers';
 
	/** 
	 * Function to get all faq that are belongs to faq category()
	 *
	 * @param null
	 * 
	 * @return query 
	*/
 	public  function faq()
    { 
        return $this->hasMany('App\Model\Faq','category_id')
			->select('id','category_id','is_active')
			->where('is_active',1)
			->with('description');
    } //end faq()

	/** 
	 * Function to get all description accoding to language
	 *
	 * @param null
	 * 
	 * @return query 
	*/
	public function description(){
		$currentLanguageId	=	Session::get('currentLanguageId');
		return $this->hasOne('App\Model\DropDownDescription','parent_id')->select('parent_id','name')->where('language_id' , $currentLanguageId);
	}//end description()
			
}// end DropDown class
