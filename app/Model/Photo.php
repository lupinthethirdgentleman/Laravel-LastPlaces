<?php 
namespace App\Model; 
use Eloquent;
/**
 * Testimonial Model
 */
 
class Photo extends Eloquent  {

	
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
 
	protected $table = 'photos';
	
	/**
	 * Function for  bind AdminTestimonialDescription model   
	 *
	 * @param null 
	 *
	 * return query
	 */	
 	public function trip()
    {
        return $this->belongsTo('App\Model\Trip','trip_id');
    }
	
		
}// end Testimonial class
