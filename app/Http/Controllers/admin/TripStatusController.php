<?php
namespace App\Http\Controllers\admin;

use App\Http\Controllers\BaseController;
use App\Model\Hcp;
use App\Model\Company;
use App\Model\Language;
use App\Model\TripStatus;
use mjanssen\BreadcrumbsBundle\Breadcrumbs as Breadcrumb;
use Auth,Blade,Config,Cache,Cookie,DB,File,Hash,Input,Mail,mongoDate,Redirect,Request,Response,Session,URL,View,Validator;

/**
 * Hcp Controller
 *
 * Add your methods in the class below
 *
 * This file will render views from views/admin/HCP
 */
 
class TripStatusController extends BaseController {

	public $model	=	'TripStatus';
	
	public function __construct() {
		View::share('modelName',$this->model);
	}

	/**
	* Function for display all TripStatus
	*
	* @param null
	*
	* @return view page. 
	*/
 
	public function listTripStatus(){	
	
		### breadcrumbs Start ###
		// Breadcrums   is  added   here dynamically
		Breadcrumb::addBreadcrumb(trans("messages.system_management.dashboard"),URL::to('admin/dashboard'));
		Breadcrumb::addBreadcrumb(trans("Trip Status"),'');
		$breadcrumbs 	= 	Breadcrumb::generate();
		### breadcrumbs End ###
		
		$DB	=	TripStatus::query();
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
		
		$sortBy 	= 	(Input::get('sortBy')) ? Input::get('sortBy') : 'created_at';
	    $order  	= 	(Input::get('order')) ? Input::get('order')   : 'DESC';

		$result = $DB->orderBy($sortBy, $order)
					 ->paginate(Config::get("Reading.records_per_page"));

		//$location_list = DB::table('locations')->lists('Name','id');



		return  View::make('admin.TripStatus.index',compact('breadcrumbs','result','searchVariable','sortBy','order'));
	}// end listHcp()



	/**
	* Function for display page  for add new Hcp
	*
	* @param null
	*
	* @return view page. 
	*/
	public function addTripStatus(){
		
		### breadcrumbs Start ###
		// Breadcrums   is  added   here dynamically
		Breadcrumb::addBreadcrumb(trans("messages.system_management.dashboard"),URL::to('admin/dashboard'));
		Breadcrumb::addBreadcrumb(trans("Trip Status"),URL::to('admin/list-trip-status'));
		Breadcrumb::addBreadcrumb(trans("Add New Trip Status"),'');
		$breadcrumbs 	= 	Breadcrumb::generate();
		### breadcrumbs End ###

		return  View::make('admin.TripStatus.add',compact('breadcrumbs'));
	} //end addHcp()


	
	/**
	* Function for save added Destination Country
	*
	* @param null
	*
	* @return redirect page. 
	*/
	function saveTripStatus(){
		
		$thisData				=	Input::all();

		$validator = Validator::make(
			array(
				'status_name' 		=> Input::get('status_name'),
			),
			array(
				'status_name' 		=> 'required',
			)
		);
		
		if ($validator->fails())
		{  return Redirect::back()->withErrors($validator)->withInput();

		}else{

			$obj 	 					= new TripStatus;
			$obj->status_name 			=  Input::get('status_name');

			$obj->save();
			
			Session::flash('flash_notice', trans("Trip Status added successfully!")); 
			return Redirect::to('admin/list-trip-status');
		}
	}//end saveHcp()



	/**
	* Function for display page  for edit Hcp
	*
	* @param $Id ad id of Hcp
	*
	* @return view page. 
	*/	
	public function editTripStatus($Id){

		$TripStatus		  =	TripStatus::find($Id);
	
		### breadcrumbs Start ###
		// Breadcrums   is  added   here dynamically
		Breadcrumb::addBreadcrumb(trans("messages.system_management.dashboard"),URL::to('admin/dashboard'));
		Breadcrumb::addBreadcrumb(trans("Trip Status"),URL::to('admin/list-trip-status'));
		Breadcrumb::addBreadcrumb(trans("Edit Trip Status"),'');
		$breadcrumbs 	= 	Breadcrumb::generate();
		
		### breadcrumbs End ###

		return  View::make('admin.TripStatus.edit',compact('TripStatus','breadcrumbs'));
	}// end editHcp()



	/**
	* Function for update cms page
	*
	* @param $Id ad id of cms page
	*
	* @return redirect page. 
	*/
	function updateTripStatus($Id){
			$thisData				=	Input::all();
		
		$validator = Validator::make(
			array(
				'status_name' 		=> Input::get('status_name'),
			),
			array(
				'status_name' 		=> 'required',
			)
		);
		
		if ($validator->fails())
		{  
			return Redirect::back()->withErrors($validator)->withInput();

		}else{
			$obj 					= 	TripStatus:: findorFail($Id);
			$obj->status_name	 	=  Input::get('status_name');

			$obj->save();
			
			Session::flash('flash_notice', trans("Trip Status updated successfully!")); 
			return Redirect::to('admin/list-trip-status');
		}
	}// end updateCms()



	/**
	* Function for delete Hcp 
	*
	* @param $Id as id of Hcp 
	* @param $Status as status of Hcp page
	*
	* @return redirect page. 
	*/	
	public function deleteDestinationCountry($Id = 0){
		
		$deleteDestinationCountry = DestinationCountry::where('id', '=', $Id)->delete();

		Session::flash('flash_notice',  trans("Destination Country deleted successfully!")); 
		return Redirect::to('admin/list-destination-country');
	}// end updateCmstatus()




	/**
	* Function for display Hcp detail
	*
	* @param $userId 	as id of Hcp
	*
	* @return view page. 
	*/
	public function viewDestinationCountry($Id = 0){
	
		Breadcrumb::addBreadcrumb(trans("messages.user_management.dashboard"),URL::to('admin/dashboard'));
		Breadcrumb::addBreadcrumb(trans("Destination Country"),URL::to('admin/list-destination-country'));
		Breadcrumb::addBreadcrumb(trans("messages.user_management.view"),Request::url());
		$breadcrumbs 	= 	Breadcrumb::generate();
		
		if($Id){
			$DestinationCountryDetails		= DestinationCountry::find($Id); 			
			$companyDetails = Company::where('id',$hcpDetails->company_id)->first();
			return View::make('admin.Hcp.view', compact('DestinationCountryDetails','breadcrumbs','companyDetails'));
		}
	} // end viewHcp()

	
	function updateStatus($id){
		$currentStatus = TripStatus::where('id',$id)->select('active')->first();
		$newStatus = ($currentStatus->active ==1)?0:1;
		$data = array();
		$data['active'] = $newStatus;
		TripStatus::where('id',$id)->update($data);
		return Redirect::to('admin/list-trip-status');
	}
	
}// end HcpController()
