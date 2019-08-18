<?php 
namespace App\Http\Controllers\admin;
use App\Http\Controllers\BaseController;
use mjanssen\BreadcrumbsBundle\Breadcrumbs as Breadcrumb;
use App\Model\AdminPayment;
use Auth,Blade,Config,Cache,Cookie,DB,File,Hash,Input,Mail,mongoDate,Redirect,Request,Response,Session,URL,View,Validator;
/**
 * UsersPaymentController Controller
 *
 * Add your methods in the class below
 *
 * This file will render views from views/admin/payment
 */
 
 class UsersPaymentController extends BaseController {

/*
 * Function for display Payment detail from database   
 *
 * @param null
 *
 * @return view page. 
 */
	public function listPayment(){ 
		
		### breadcrumbs Start ###
  // 		Breadcrumb::addBreadcrumb('Dashboard',URL::to('admin/dashboard'));
		// Breadcrumb::addBreadcrumb('Payment Manager');
		// $breadcrumbs 	= 	Breadcrumb::generate();
		### breadcrumbs End ###
		$DB				=	AdminPayment::showResult();

		$searchVariable	=	array(); 
		$inputGet		=	Input::get();
		$from		    =	Input::get('from');
		$to		        =	Input::get('to');
		
		if ((Input::get() && isset($inputGet['display'])) || isset($inputGet['page']) ) {
			
			$searchData	=	Input::get();
			unset($searchData['display']);
			unset($searchData['_token']);
			if(isset($searchData['page'])){
				unset($searchData['page']);
			}
			
			## Validation on searching 'to' and 'from' field ##
			
			if($from !='' || $to !='' ){
				$validator = Validator::make(
					array(
					'from' 	 =>  $from,
					'to'	 =>  $to,
					), 
					array(
					'from'   => 'required',
					'to'     => 'required',
					)
				);
			
				if ($validator->fails()){
				return Redirect::to('admin/payment-manager')
				->withErrors($validator)->withInput();	
				}
			}
			if($from !='' and $to !='' ){
				
				unset($searchData['from']);
				unset($searchData['to']);
					
				$from	=	date('Y-m-d ',strtotime($from));
				$to		=	date('Y-m-d ',strtotime("+1 day",strtotime($to)));
				
				$DB->whereBetween('created_at',array($from,$to)); 
				$searchVariable	=	array_merge($searchVariable,array('from' => $from,'to' => $to));
			}
	
			foreach($searchData as $fieldName => $fieldValue){
				if(!empty($fieldValue)){
					$DB->where("$fieldName",'=',$fieldValue); 
					$searchVariable	=	array_merge($searchVariable,array($fieldName => $fieldValue));
				}
			}
		} 
		

		$sortBy = (Input::get('sortBy')) ? Input::get('sortBy') : 'created_at';
	    $order  = (Input::get('order')) ? Input::get('order')   : 'DESC';
	    
		$results	= $DB->orderBy($sortBy, $order)->paginate(Config::get("Reading.records_per_page"));
		return View::make('admin.payment.index',compact('results','searchVariable','breadcrumbs','sortBy','order'));
	}//end listPayment()
	

 /* Function for dispaly  payment detail on popup   
 *
 * @param $id as payment id
 *   
 * @return view page. 
 */
	public function pricingDetail($id){		
		if(Request::ajax()){   
			
			$DB 	=  AdminPayment::showResult();
			$result	=  $DB->where('id',$id)->get();
			return View::make('admin.payment.popup',compact('result'));
		}  
	} //end pricingDetail()
	
 }// end UsersPaymentController class
