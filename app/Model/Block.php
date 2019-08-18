<?php 
namespace App\Model; 
use Eloquent;
use Session;

/**
 * AdminBlock Model
 */
 
class Block extends Eloquent  {
	
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
 
	protected $table = 'blocks';
	
	/**
	 * hasMany bind function for  AdminBlockDescription model 
	 *
	 * @param null
	 * 
	 * @return query
	 */	
	public function description() {
		$currentLanguageId 	=	Session::get('currentLanguageId');
        return $this->hasOne('App\Model\BlockDescription','parent_id')
        			->where('language_id',$currentLanguageId);
    }// end description()
	
}// end AdminBlock class
