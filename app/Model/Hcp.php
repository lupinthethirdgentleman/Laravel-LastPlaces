<?php 
namespace App\Model; 
use Eloquent;

	/**
	 * Company Model
	 */
 
class Hcp extends Eloquent   {
	
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	 
	protected $table = 'hcp';

	public $timestamps = false;



	/**
	 * Function for  bind Userlogin model   
	 *
	 * @param null 
	 *
	 * return query
	 */			
	public function getCompanyDeatil(){
        return $this->belongsTo('App\Model\Company','company_id','id');
    }//end userLastLogin()

	public function company(){
        return $this->belongsTo('App\Model\Company','company_id','id');
    }

	public function like(){
        return $this->hasMany('App\Model\Like','hcp_id')->where('like',1);
    }

	public function dislike(){
        return $this->hasMany('App\Model\Like','hcp_id')->where('like',2);
    }

	public function review(){
        return $this->hasMany('App\Model\Review','hcp_id');
    }

    public function companylocation(){
        return $this->belongsTo('App\Model\CompanyLocation','location','id');
    }
	
	
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_at = $model->freshTimestamp();
        });
    }
	
} // end Company class
