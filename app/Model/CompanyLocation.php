<?php 
namespace App\Model; 
use Eloquent;

	/**
	 * Company Model
	 */
 
class CompanyLocation extends Eloquent   {
	
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	 
	protected $table = 'locations';

	public $timestamps = false;
	
} // end Company class
