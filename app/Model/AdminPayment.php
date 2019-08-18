<?php
namespace App\Model;

use Eloquent;
/*
 AdminPayment Model
 */
class AdminPayment extends Eloquent  {

/**
 * The database table used by the model.
 *
 * @var string
 */
 
	protected $table ='booking_details';
 
 	
 /* Function for  bind AdminUser model   
 *
 * @param null 
 *
 * return query
 */
	 	
/**
 * function for find result from database 
 *
 * @param null
 * 
 * @return array
 */	
	public static function showResult(){
		$results	=  AdminPayment::query();
		return $results;
	}//end showResult()
	 
}// end AdminPayment