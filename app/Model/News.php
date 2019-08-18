<?php 
namespace App\Model; 
use Eloquent;
use Session;


/**
 * News Model
 */
 
class News extends Eloquent   {
	
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
 
	protected $table = 'news';

	
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
	public function newsDescription() {
		$currentLanguageId 	=	Session::get('currentLanguageId');
        
        return $this->hasOne('App\Model\NewsDescription','parent_id')->where('language_id',$currentLanguageId);

    }// end description()

    public function newsComments() {
		//$currentLanguageId 	=	Session::get('currentLanguageId');
        
        return $this->hasMany('App\Model\NewsComments','news_id');
    }

    public function newsAuthor(){
    	        return $this->hasOne('App\Model\User','id','author_id');
    }

	
} // end News class