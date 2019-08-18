<?php
namespace App\Model; 
use Eloquent;
/**
 * Enquiry Model
 */
 
class Enquiry extends Eloquent   {
	
	/**
	 * The database collection used by the model.
	 *
	 * @var string
	 */
 
	protected $table = 'trip_enquiry';

	public function trip() {
		return $this->belongsTo('App\Model\Trip','trip_id','id');
	}


} // end Enquiry class
