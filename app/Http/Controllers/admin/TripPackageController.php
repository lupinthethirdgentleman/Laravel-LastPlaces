<?php
namespace App\Http\Controllers\admin;

use App\Http\Controllers\BaseController;
use App\Model\Hcp;
use App\Model\Company;
use App\Model\Language;
use App\Model\TripPackage;
use mjanssen\BreadcrumbsBundle\Breadcrumbs as Breadcrumb;
use Auth,Blade,Config,Cache,Cookie,DB,File,Hash,Input,Mail,mongoDate,Redirect,Request,Response,Session,URL,View,Validator;

/**
 * Hcp Controller
 *
 * Add your methods in the class below
 *
 * This file will render views from views/admin/HCP
 */
 
class TripPackageController extends BaseController {

	public $model	=	'TripPackage';
	
	public function __construct() {
		View::share('modelName',$this->model);
	}

	/**
	* Function for display all TripPackage
	*
	* @param null
	*
	* @return view page. 
	*/
 
	public function listTripPackage(){	
	
		### breadcrumbs Start ###
		// Breadcrums   is  added   here dynamically
		Breadcrumb::addBreadcrumb(trans("messages.system_management.dashboard"),URL::to('admin/dashboard'));
		Breadcrumb::addBreadcrumb(trans("Trip Package"),'');
		$breadcrumbs 	= 	Breadcrumb::generate();
		### breadcrumbs End ###
		
		$DB	=	TripPackage::query();
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

		$result = $DB->with('getTrip')
					 ->orderBy($sortBy, $order)
					 ->paginate(Config::get("Reading.records_per_page"));

		//$location_list = DB::table('locations')->lists('Name','id');



		return  View::make('admin.TripPackage.index',compact('breadcrumbs','result','searchVariable','sortBy','order'));
	}// end listHcp()



	/**
	* Function for display page  for add new Hcp
	*
	* @param null
	*
	* @return view page. 
	*/
	public function addTripPackage(){
		
		### breadcrumbs Start ###
		// Breadcrums   is  added   here dynamically
		Breadcrumb::addBreadcrumb(trans("messages.system_management.dashboard"),URL::to('admin/dashboard'));
		Breadcrumb::addBreadcrumb(trans("Trip Package"),URL::to('admin/list-trip-package'));
		Breadcrumb::addBreadcrumb(trans("Add New Trip Package"),'');
		$breadcrumbs 	= 	Breadcrumb::generate();
		### breadcrumbs End ###

		$languages	=	Language::where('is_active', '=', ACTIVE)->get(array('title','id'));
		$language_code		=	Config::get('default_language.language_code');		
		
		$tripList = DB::table('trips')->lists('tripname','id');
		$statusList = DB::table('trip_status')->where('active',1)->lists('status_name','id');

		return  View::make('admin.TripPackage.add',compact('breadcrumbs','tripList','languages' ,'language_code','statusList'));
	} //end addHcp()


	
	/**
	* Function for save added Destination Country
	*
	* @param null
	*
	* @return redirect page. 
	*/
	function saveTripPackage(){
		
		$thisData				=	Input::all();

		$validator = Validator::make(
			array(
				'trip_name' 		=> Input::get('trip_name'),
				'trip_date' 		=> Input::get('trip_date'),
				'trip_price' 		=> Input::get('trip_price'),
				'supplement' 		=> Input::get('supplement'),
				'status_name' 		=> Input::get('status_name'),
			),
			array(
				'trip_name' 		=> 'required',
				'trip_date' 		=> 'required',
				'trip_price' 		=> 'required',
				'supplement' 		=> 'required',
				'status_name' 		=> 'required',
			)
		);
		
		if ($validator->fails())
		{  return Redirect::back()->withErrors($validator)->withInput();

		}else{

			$obj 	 			= new TripPackage;
			$trip_date = date("Y-m-d", strtotime(Input::get('trip_date')));
			$obj->trip_date = $trip_date;
			$obj->trip_id  	= Input::get('trip_name');
			$obj->price  	= Input::get('trip_price');
			$obj->supplement  	= Input::get('supplement');
			$obj->status_id	 			=  Input::get('status_name');

			$obj->save();
			
			Session::flash('flash_notice', trans("Trip Package added successfully!")); 
			return Redirect::to('admin/list-trip-package');
		}
	}//end saveHcp()



	/**
	* Function for display page  for edit Hcp
	*
	* @param $Id ad id of Hcp
	*
	* @return view page. 
	*/	
	public function editTripPackage($Id){

		$TripPackage		  =	TripPackage::find($Id);
	
		### breadcrumbs Start ###
		// Breadcrums   is  added   here dynamically
		Breadcrumb::addBreadcrumb(trans("messages.system_management.dashboard"),URL::to('admin/dashboard'));
		Breadcrumb::addBreadcrumb(trans("Trip Package"),URL::to('admin/list-trip-package'));
		Breadcrumb::addBreadcrumb(trans("Edit Trip Package"),'');
		$breadcrumbs 	= 	Breadcrumb::generate();
		
		### breadcrumbs End ###
		
		$tripList  = DB::table('trips')->lists('tripname','id');
		$statusList = DB::table('trip_status')->where('active',1)->lists('status_name','id');

		return  View::make('admin.TripPackage.edit',compact('TripPackage','breadcrumbs','tripList','statusList'));
	}// end editHcp()



	/**
	* Function for update cms page
	*
	* @param $Id ad id of cms page
	*
	* @return redirect page. 
	*/
	function updateTripPackage($Id){
			$thisData				=	Input::all();
		
		$validator = Validator::make(
			array(
				'trip_name' 		=> Input::get('trip_name'),
				'trip_date' 		=> Input::get('trip_date'),
				'trip_price' 		=> Input::get('trip_price'),
				'supplement' 		=> Input::get('supplement'),
				'status_name' 		=> Input::get('status_name'),
			),
			array(
				'trip_name' 		=> 'required',
				'trip_date' 		=> 'required',
				'trip_price' 		=> 'required',
				'supplement' 		=> 'required',
				'status_name' 		=> 'required',
			)
		);
		
		if ($validator->fails())
		{  
			return Redirect::back()->withErrors($validator)->withInput();

		}else{
			$obj 					= 	TripPackage:: findorFail($Id);
			$obj->trip_id  	= Input::get('trip_name');
			$trip_date = date("Y-m-d", strtotime(Input::get('trip_date')));
			$obj->trip_date = $trip_date;
			$obj->price  	= Input::get('trip_price');
			$obj->supplement  	= Input::get('supplement');
			$obj->status_id	 			=  Input::get('status_name');

			$obj->save();
			
			Session::flash('flash_notice', trans("Trip Package updated successfully!")); 
			return Redirect::to('admin/list-trip-package');
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
		$currentStatus = TripPackage::where('id',$id)->select('active')->first();
		$newStatus = ($currentStatus->active ==1)?0:1;
		$data = array();
		$data['active'] = $newStatus;
		TripPackage::where('id',$id)->update($data);
		return Redirect::to('admin/list-trip-package');
	}
	
}// end HcpController()
