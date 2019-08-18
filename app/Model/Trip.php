<?php 
namespace App\Model; 
use Eloquent;
use Session;
/**
 * Testimonial Model
 */
 
class Trip extends Eloquent  {

	
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
 
	protected $table = 'trips';
	
	/**
	 * Function for  bind AdminTestimonialDescription model   
	 *
	 * @param null 
	 *
	 * return query
	 */	
 	public function country()
    {
        return $this->belongsTo('App\Model\DestinationCountry','country_id');
    }

    public function tripDescription() {
		$currentLanguageId 	=	Session::get('currentLanguageId');
        
        return $this->hasMany('App\Model\TripDescription','foreign_key')
        		->where('language_id',$currentLanguageId)->where('source_col_name','tripname');

    }
	
		
}// end Testimonial class
