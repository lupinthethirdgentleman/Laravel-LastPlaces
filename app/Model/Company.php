<?php 
namespace App\Model; 
use Eloquent;

	/**
	 * Company Model
	 */
 
class Company extends Eloquent   {
	
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	 
	protected $table = 'companies';

	public $timestamps = false;
	
} // end Company class
