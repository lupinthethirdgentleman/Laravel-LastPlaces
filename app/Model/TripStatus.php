<?php 
namespace App\Model; 
use Eloquent;

/**
 * Country Model
 */
 
class TripStatus extends Eloquent  {
	
/**
 * The database table used by the model.
 *
 * @var string
 */
 
	protected $table = 'trip_status';

	/*public function getTrip(){
        return $this->belongsTo('App\Model\Trip','trip_id','id');
    }*/
	
	

}// end Media class
