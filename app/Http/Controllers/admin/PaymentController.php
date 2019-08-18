<?php
namespace App\Http\Controllers\admin;
use App\Http\Controllers\BaseController;
use App\Model\Payment;
use mjanssen\BreadcrumbsBundle\Breadcrumbs as Breadcrumb;
use Auth, Blade, Config, Cache, Cookie, DB, File, Hash, Input, Mail, mongoDate, Redirect, Request, Response, Session, URL, View, Validator;

/**
 * Payment Controller 
 *
 * Add your methods in the class below
 *
 * This file will render views from views/admin/Payment
 */
 
class PaymentController extends BaseController {
 
 /**
 * Function for display list of all Payment 
 *
 * @param null
 *
 * @return view page. 
 */	
	public function listPayment(){ 
		// Breadcrums   is  added   here dynamically
		Breadcrumb::addBreadcrumb(trans("messages.global.breadcrumbs_dashboard"),URL::to('admin/dashboard'));
		Breadcrumb::addBreadcrumb(trans("messages.payment.breadcrumbs_Payment_module"),URL::to('admin/payment-manager'));
		$breadcrumbs 	= 	Breadcrumb::generate();
		
		$DB = Payment::query();
		
		//  search 
		$searchVariable	=	array(); 
		$inputGet		=	Input::get();
		if ((Input::get() && isset($inputGet['display'])) || isset($inputGet['page']) ) {
			$searchData	=	Input::get();
			unset($searchData['display']);
			unset($searchData['_token']);
			
			if(isset($searchData['page'])){
				unset($searchData['page']);
			}
			
			foreach($searchData as $fieldName => $fieldValue){
				if(!empty($fieldValue)){
					$DB->where("$fieldName",'like','%'.$fieldValue.'%');
					$searchVariable	=	array_merge($searchVariable,array($fieldName => $fieldValue));
				}
			}
		}
		//  this  is use for  sorting  result click  on   field name sortby and  order will  be pass  
		$sortBy = (Input::get('sortBy')) ? Input::get('sortBy') : 'updated_at';
	    $order  = (Input::get('order')) ? Input::get('order')   : 'DESC';
		
		$result 	= 	$DB->with('user','ceremony')->orderBy($sortBy, $order)->paginate(Config::get("Reading.records_per_page"));

		//dd($result); 
		return  View::make('admin.Payment.index', compact('breadcrumbs','result' ,'searchVariable','sortBy','order'));
	}// end listPayment()
	
 /**
 * Function for delete Payment
 *
 * @param $PaymentId as id of ad
 *
 * @return redirect page. 
 */	
}
