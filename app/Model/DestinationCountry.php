<?php 
namespace App\Model;
use Eloquent;
use Session;

/**
 * Country Model
 */
 
class DestinationCountry extends Eloquent  {
	
/**
 * The database table used by the model.
 *
 * @var string
 */
 
	protected $table = 'destination_country';

	public function destinationCountryDescription() {
		$currentLanguageId 	=	Session::get('currentLanguageId');
        
        return $this->hasMany('App\Model\DestinationCountryDescription','parent_id')
        		->where('language_id',$currentLanguageId);

    }
	
	
	public function destinationDescription() {
		$currentLanguageId 	=	Session::get('currentLanguageId');
        
        return $this->hasMany('App\Model\DestinationCountryDescription','parent_id')
        		->where('language_id',$currentLanguageId)->where('source_col_name','name');

    }

}// end Media class
