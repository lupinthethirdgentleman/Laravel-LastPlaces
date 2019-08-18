<?php 
namespace App\Model; 
use Eloquent;

	/**
	 * Company Model
	 */
 
class Review extends Eloquent   {
	
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	 
	protected $table = 'reviews';


    public function hcp(){
        return $this->belongsTo('App\Model\Hcp','hcp_id','id');
    }

    public function dropdown(){
        return $this->belongsTo('App\Model\DropDown','health_benefit','id');
    }

    public function Company(){
        return $this->belongsTo('App\Model\Company','company_id','id');
    }

	public function like(){
        return $this->hasMany('App\Model\Like','hcp_id')->where('like',1);
    }

	public function dislike(){
        return $this->hasMany('App\Model\Like','hcp_id')->where('like',0);
    }

} // end Company class
