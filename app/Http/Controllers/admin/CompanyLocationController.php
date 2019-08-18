<?php
namespace App\Http\Controllers\admin;

use App\Http\Controllers\BaseController;
use App\Model\CompanyLocation;
use App\Model\Company;
use App\Model\CmsDescription;
use App\Model\Language;
use mjanssen\BreadcrumbsBundle\Breadcrumbs as Breadcrumb;
use Auth,Blade,Config,Cache,Cookie,DB,File,Hash,Input,Mail,mongoDate,Redirect,Request,Response,Session,URL,View,Validator;

/**
 * Location Controller
 *
 * Add your methods in the class below
 *
 * This file will render views from views/admin/Company
 */
 
class CompanyLocationController extends BaseController {
	/**
	* Function for display all Location
	*
	* @param null
	*
	* @return view page. 
	*/
 
	public function listLocation(){	
	
		### breadcrumbs Start ###
		// Breadcrums   is  added   here dynamically
		Breadcrumb::addBreadcrumb(trans("messages.system_management.dashboard"),URL::to('admin/dashboard'));
		Breadcrumb::addBreadcrumb(trans("Locations"),'');
		$breadcrumbs 	= 	Breadcrumb::generate();
		### breadcrumbs End ###
		
		$DB	=	CompanyLocation::query();
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
		
		//$sortBy = (Input::get('sortBy')) ? Input::get('sortBy') : 'updated_at';
	    //$order  = (Input::get('order')) ? Input::get('order')   : 'DESC';
		
		$result = $DB->orderBy('id','desc')
					 ->paginate(Config::get("Reading.records_per_page"));

		$company_list = DB::select('select * from companies');
		
		return  View::make('admin.companyLocation.index',compact('breadcrumbs','result','searchVariable','company_list'));
	}// end listCompany()



	/**
	* Function for display page for add new location
	*
	* @param null
	*
	* @return view page. 
	*/
	public function addLocation(){
		
		### breadcrumbs Start ###
		// Breadcrums   is  added   here dynamically
		Breadcrumb::addBreadcrumb(trans("messages.system_management.dashboard"),URL::to('admin/dashboard'));
		Breadcrumb::addBreadcrumb(trans("Locations"),URL::to('admin/list-location'));
		Breadcrumb::addBreadcrumb(trans("Add New Location"),'');
		$breadcrumbs 	= 	Breadcrumb::generate();
		### breadcrumbs End ###

		## Get Company List ##
		$company_list = DB::select('select * from companies');
		//print_r($company_list);die;
	
		return  View::make('admin.companyLocation.add',compact('breadcrumbs','company_list'));
	} //end addCompany()
	


	/**
	* Function for save added location
	*
	* @param null
	* 
	* @return redirect page. 
	*/
	function saveLocation(){
		
		$thisData				=	Input::all();
		$validator = Validator::make(
			array(
				'lcompany_name' 				=> Input::get('lcompany_name'),
				'location_name' 			=> Input::get('location_name'),

			),
			array(
				'lcompany_name' 				=> 'required',
				'location_name' 			=> 'required',
			)
		);
		
		if ($validator->fails())
		{  return Redirect::back()->withErrors($validator)->withInput();

		}else{
				$obj 	 		= new CompanyLocation;
				$obj->Name  	= Input::get('location_name');
				$obj->company  = Input::get('lcompany_name');
				$obj->status    = 1;


				$obj->save();	
				

				Session::flash('flash_notice', trans("Location added successfully!")); 
				return Redirect::to('admin/list-location');
		}
	}//end saveCompany()



	/**
	* Function for display page  for edit location
	*
	* @param $Id ad id of location page
	*
	* @return view page. 
	*/	
	public function editLocation($Id){
	
		### breadcrumbs Start ###
		// Breadcrums   is  added   here dynamically
		Breadcrumb::addBreadcrumb(trans("messages.system_management.dashboard"),URL::to('admin/dashboard'));
		Breadcrumb::addBreadcrumb(trans("Locations"),URL::to('admin/list-location'));
		Breadcrumb::addBreadcrumb(trans("Edit Location"),'');
		$breadcrumbs 	= 	Breadcrumb::generate();
		
		### breadcrumbs End ###
		
		$Location		=	CompanyLocation::find($Id);
		$company_list = DB::select('select * from companies');

		return  View::make('admin.companyLocation.edit',compact('Location','breadcrumbs','company_list'));
	}// end editCompany()



	/**
	* Function for update location
	*
	* @param $Id ad id of location
	*
	* @return redirect page. 
	*/
	function updateLocation($Id){
		$thisData				=	Input::all();
		
		$validator = Validator::make(
			array(
				'lcompany_name' 				=> Input::get('lcompany_name'),
				'location_name' 			=> Input::get('location_name'),
				
			),
			array(
				'lcompany_name' 				=> 'required',
				'location_name' 			=> 'required',
			)
		);
		
		if ($validator->fails())
		{  return Redirect::back()->withErrors($validator)->withInput();

		}else{
			
				$obj 	 		= CompanyLocation::find($Id);
				$obj->company  = Input::get('lcompany_name');
				$obj->Name  	= Input::get('location_name');


				$obj->save();	

				Session::flash('flash_notice', trans("Location updated successfully!")); 
				return Redirect::to('admin/list-location');
		}
	}// end updateCompany()



	/**
	* Function for delete location
	*
	* @param $Id as id of location
	* @param $Status as status of location
	*
	* @return redirect page. 
	*/	
	public function deleteLocation($Id = 0){
		
		$deleteLocation = CompanyLocation::where('id', '=', $Id)->delete();
		
		Session::flash('flash_notice',  trans("Location deleted successfully!")); 
		
		return Redirect::to('admin/list-location');
	}// end deleteCompany()




	/**
	* Function for display location detail
	*
	* @param $userId 	as id of location
	*
	* @return view page. 
	*/
	public function viewLocation($Id = 0){
	
		Breadcrumb::addBreadcrumb(trans("messages.user_management.dashboard"),URL::to('admin/dashboard'));
		Breadcrumb::addBreadcrumb(trans("Locations"),URL::to('admin/list-location'));
		Breadcrumb::addBreadcrumb(trans("messages.user_management.view"),Request::url());
		$breadcrumbs 	= 	Breadcrumb::generate();
		
		if($Id){
			
			$locationDetails	=	CompanyLocation::find($Id); 
			
			
			return View::make('admin.companyLocation.view', compact('locationDetails','breadcrumbs'));
		}
		
	} // end viewCompany()



	/**
	* Function for update location status
	*
	* @param $Id as id of location page
	* @param $Status as status of location 
	*
	* @return redirect page. 
	*/	
	public function updateLocationStatus($Id =0, $Status=0){

		CompanyLocation::where('id', '=', $Id)->update(array('status' => $Status));

		
		Session::flash('flash_notice',  trans("Location status updated successfully!")); 
		
		return Redirect::to('admin/list-location');
	}// end updateCompanyStatus()

	function updateStatus($id){
		$currentStatus = CompanyLocation::where('id',$id)->select('status')->first();
		$newStatus = ($currentStatus->status ==1)?0:1;
		$data = array();
		$data['status'] = $newStatus;
		CompanyLocation::where('id',$id)->update($data);
		return Redirect::to('admin/list-location');
	}
	
}// end LocationController()
