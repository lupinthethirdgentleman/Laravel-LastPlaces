<?php 
namespace App\Model; 
use Eloquent;
use Session;
/**
 * Testimonial Model
 */
 
class Region extends Eloquent  {

	
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
 
	protected $table = 'region';

	public static function scopeOfPage($query)
    {
        return $query->where('is_active',1);
    }

    public function country(){
        return $this->hasMany('App\Model\DestinationCountry','region_id')->orderBy('name','asc');
    }
    public function regionDescription() {
		$currentLanguageId 	=	Session::get('currentLanguageId');
        
        return $this->hasOne('App\Model\RegionDescription','foreign_key')
        		->where('language_id',$currentLanguageId);

    }
	
	/**
	 * Function for  bind AdminTestimonialDescription model   
	 *
	 * @param null 
	 *
	 * return query
	 */	
 
	
		
}// end Testimonial class
