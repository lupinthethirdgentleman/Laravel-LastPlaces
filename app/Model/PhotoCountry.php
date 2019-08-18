<?php 
namespace App\Model; 
use Eloquent;
/**
 * Testimonial Model
 */
 
class PhotoCountry extends Eloquent  {

	
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
 
	protected $table = 'photos_country';
	
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

    public function country()
    {
        return $this->belongsTo('App\Model\DestinationCountry','country_id','id');
    }
	
		
}// end Testimonial class
