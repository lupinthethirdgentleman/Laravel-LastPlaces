<?php 
namespace App\Model; 
use Eloquent;

	/**
	 * Cms Model
	 */
 
class Cms extends Eloquent   {
	
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	 
	protected $table = 'cms_pages';

	/**
	* hasMany  function for bind CmsDescription and get result acoording language
	*
	* @param null
	* 
	* @return query
	*/
	
	public function accordingLanguage()
    {
		$currentLanguageId	=	Session::get('currentLanguageId');
        return $this->hasOne('CmsDescription','foreign_key')->select('source_col_description','foreign_key')->where('language_id' , $currentLanguageId)->where('source_col_name','body');
		
    } //end accordingLanguage()
	
	/**
	 * function for find result from database 
	 *
	 * @param $slug
	 * @param $fields as fields which need to select
	 * 
	 * @return array
	 */	
 
	public static function getResult($slug='',$fields = array()){		
		$result		=	 Cms::with('accordingLanguage')
							->select($fields)
							->where('slug',$slug)
							->get()->first()
							->toArray();
		
		return $result;
	} //end getResult()		
	
} // end Cms class
