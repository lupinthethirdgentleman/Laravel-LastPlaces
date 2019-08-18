<?php 
namespace App\Model; 
use Eloquent;
use Session;


/**
 * Blog Model
 */
 
class Blog extends Eloquent   {
	
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
 
	protected $table = 'blogs';

	
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
	 * hasMany bind function for  AdminBlockDescription model 
	 *
	 * @param null
	 * 
	 * @return query
	 */	
	public function blogDescription() {
		$currentLanguageId 	=	Session::get('currentLanguageId');
        
        return $this->hasOne('App\Model\BlogDescription','parent_id')->where('language_id',$currentLanguageId);

    }// end description()

    public function blogComments() {
		//$currentLanguageId 	=	Session::get('currentLanguageId');
        
        return $this->hasMany('App\Model\BlogComments','blog_id');
    }

    public function blogsAuthor(){
    	        return $this->hasOne('App\Model\User','id','author_id');
    }

	
} // end Blog class