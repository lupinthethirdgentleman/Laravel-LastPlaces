<?php 
namespace App\Model; 
use Eloquent;

/**
 * Country Model
 */
 
class TripPackage extends Eloquent  {
	
/**
 * The database table used by the model.
 *
 * @var string
 */
 
	protected $table = 'trip_package';

	public function getTrip(){
        return $this->belongsTo('App\Model\Trip','trip_id','id');
    }//end userLastLogin()
	
	

}// end Media class
