<?php 
namespace App\Model; 
use Eloquent;
/**
 * Testimonial Model
 */
 
class PhotoCountryNature extends Eloquent  {

	
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
 
	protected $table = 'photos_country_nature';
	
	/**
	 * Function for  bind AdminTestimonialDescription model   
	 *
	 * @param null 
	 *
	 * return query
	 */	
 	public function trip()
    {
        return $this->belongsTo('App\Model\Trip','country_id');
    }

    public function Country()
    {
        return $this->belongsTo('App\Model\DestinationCountry','country_id','id');
    }
	
		
}// end Testimonial class
