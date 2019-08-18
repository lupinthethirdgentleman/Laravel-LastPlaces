<?php
namespace App\Model; 
use Eloquent;
/**
 * Ads Model
 */
class Payment extends Eloquent {

/**
 * The database table used by the model.
 *
 * @var string
 */
	protected $table = 'payments';


	public function user() {
		return $this->belongsTo('App\Model\User');
	}

	public function ceremony() {
		return $this->belongsTo('App\Model\Ceremony');
	}

	public function booking() {
		return $this->belongsTo('App\Model\Ceremony');
	}
	
}// end Ads class
