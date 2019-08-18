<?php
namespace App\Http\Controllers\admin;

use App\Http\Controllers\BaseController;
use App\Model\Hcp;
use App\Model\Company;
use App\Model\Language;
use mjanssen\BreadcrumbsBundle\Breadcrumbs as Breadcrumb;
use Auth,Blade,Config,Cache,Cookie,DB,File,Hash,Input,Mail,mongoDate,Redirect,Request,Response,Session,URL,View,Validator;

/**
 * Hcp Controller
 *
 * Add your methods in the class below
 *
 * This file will render views from views/admin/HCP
 */
 
class HcpController extends BaseController {
	/**
	* Function for display all Hcp
	*
	* @param null
	*
	* @return view page. 
	*/
 
	public function listHcp(){	
	
		### breadcrumbs Start ###
		// Breadcrums   is  added   here dynamically
		Breadcrumb::addBreadcrumb(trans("messages.system_management.dashboard"),URL::to('admin/dashboard'));
		Breadcrumb::addBreadcrumb(trans("DA/HCP"),'');
		$breadcrumbs 	= 	Breadcrumb::generate();
		### breadcrumbs End ###
		
		$DB	=	Hcp::query();
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

		$result = $DB->with('getCompanyDeatil','companylocation')
					 ->orderBy($sortBy, $order)
					 ->paginate(Config::get("Reading.records_per_page"));

		//$location_list = DB::table('locations')->lists('Name','id');



		return  View::make('admin.Hcp.index',compact('breadcrumbs','result','searchVariable','sortBy','order'));
	}// end listHcp()



	/**
	* Function for display page  for add new Hcp
	*
	* @param null
	*
	* @return view page. 
	*/
	public function addHcp(){
		
		### breadcrumbs Start ###
		// Breadcrums   is  added   here dynamically
		Breadcrumb::addBreadcrumb(trans("messages.system_management.dashboard"),URL::to('admin/dashboard'));
		Breadcrumb::addBreadcrumb(trans("DA/HCP"),URL::to('admin/list-hcp'));
		Breadcrumb::addBreadcrumb(trans("Add New DA/HCP"),'');
		$breadcrumbs 	= 	Breadcrumb::generate();
		### breadcrumbs End ###
		
		
		$companyList = DB::table('companies')->lists('name','id');

		return  View::make('admin.Hcp.add',compact('breadcrumbs','companyList'));
	} //end addHcp()


	
	/**
	* Function for save added Hcp
	*
	* @param null
	*
	* @return redirect page. 
	*/
	function saveHcp(){
		
		$thisData				=	Input::all();
		$validator = Validator::make(
			array(
				'company_name' 		=> Input::get('company_name'),
				'company_location' 	=> Input::get('company_location'),
				'first_name' 		=> Input::get('first_name'),
				//'middle_name' 	=> Input::get('middle_name'),
				'last_name' 		=> Input::get('last_name'),
				'profession' 		=> Input::get('profession'),

				
			),
			array(
				'company_name' 		=> 'required',
				'company_location' 	=> 'required',
				'first_name' 		=> 'required',
				//'middle_name' 	=> 'required',
				'last_name' 		=> 'required',
				'profession' 		=> 'required',

			)
		);
		
		if ($validator->fails())
		{  return Redirect::back()->withErrors($validator)->withInput();

		}else{

			$obj 	 			= new Hcp;
			$obj->company_id  	= Input::get('company_name');
			$obj->location  	= Input::get('company_location');
			$obj->first_name  	= Input::get('first_name');
			$obj->middle_name  	= Input::get('middle_name');
			$obj->last_name  	= Input::get('last_name');
			$fullName			= ucwords(str_replace(' ', '',Input::get('first_name')).' '.str_replace(' ', '',Input::get('middle_name')).' '.str_replace(' ', '',Input::get('last_name')));
			$obj->full_name		= $fullName;
			$obj->profession  	= Input::get('profession');
			//$obj->created_at  	= date('Y-m-d H:i:s');


			if(input::hasFile('image')){
					$extension 	=	 Input::file('image')->getClientOriginalExtension();
					$fileName	=	time().'-hcp-image.'.$extension;
					
					if(Input::file('image')->move(HCP_IMAGE_ROOT_PATH, $fileName)){
						$obj->image			=	$fileName;
					}
				}

			$obj->save();	
			
			Session::flash('flash_notice', trans("Health Professional added successfully!")); 
			return Redirect::to('admin/list-hcp');
		}
	}//end saveHcp()



	/**
	* Function for display page  for edit Hcp
	*
	* @param $Id ad id of Hcp
	*
	* @return view page. 
	*/	
	public function editHcp($Id){
	
		### breadcrumbs Start ###
		// Breadcrums   is  added   here dynamically
		Breadcrumb::addBreadcrumb(trans("messages.system_management.dashboard"),URL::to('admin/dashboard'));
		Breadcrumb::addBreadcrumb(trans("DA/HCP"),URL::to('admin/list-hcp'));
		Breadcrumb::addBreadcrumb(trans("Edit DA/HCP"),'');
		$breadcrumbs 	= 	Breadcrumb::generate();
		
		### breadcrumbs End ###
		
		$Hcp		  =	Hcp::find($Id);
		$companyList  = DB::table('companies')->lists('name','id');
		$locationlist = DB::table('companies')->where('id',$Hcp->location)->lists('location','id');

		return  View::make('admin.Hcp.edit',compact('Hcp','breadcrumbs','companyList','locationlist'));
	}// end editHcp()



	/**
	* Function for update cms page
	*
	* @param $Id ad id of cms page
	*
	* @return redirect page. 
	*/
	function updateHcp($Id){
			$thisData				=	Input::all();
		
		$validator = Validator::make(
			array(
				'company_name' 			=> Input::get('company_name'),
				'company_location' 		=> Input::get('company_location'),
				'first_name' 			=> Input::get('first_name'),
				//'middle_name' 			=> Input::get('middle_name'),
				'last_name' 			=> Input::get('last_name'),
				'profession' 			=> Input::get('profession'),

				
			),
			array(
				'company_name' 			=> 'required',
				'company_location' 		=> 'required',
				'first_name' 			=> 'required',
				//'middle_name' 			=> 'required',
				'last_name' 			=> 'required',
				'profession' 			=> 'required',

			)
		);
		
		if ($validator->fails())
		{  return Redirect::back()->withErrors($validator)->withInput();

		}else{
			$obj 	 			= Hcp::find($Id);
			$obj->company_id  	= Input::get('company_name');
			$obj->location  	= Input::get('company_location');
			$obj->first_name  	= Input::get('first_name');
			$obj->middle_name  	= Input::get('middle_name');
			$obj->last_name  	= Input::get('last_name');
			$fullName			= ucwords(str_replace(' ', '',Input::get('first_name')).' '.str_replace(' ', '',Input::get('middle_name')).' '.str_replace(' ', '',Input::get('last_name')));
			$obj->full_name		= $fullName;
			$obj->profession  	= Input::get('profession');


			if(input::hasFile('image')){
				$extension 	=	 Input::file('image')->getClientOriginalExtension();
				$fileName	=	time().'-hcp-image.'.$extension;
			
				if(Input::file('image')->move(HCP_IMAGE_ROOT_PATH, $fileName)){
					$obj->image			=	$fileName;
				}
				$image 			=	Hcp::where('id',$Id)->pluck('image');
				@unlink(HCP_IMAGE_ROOT_PATH.$image);
			}



			$obj->save();	
			
			Session::flash('flash_notice', trans("Health Professional update successfully!")); 
			return Redirect::to('admin/list-hcp');
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
	public function deleteHcp($Id = 0){
		
		$deleteHcp = Hcp::where('id', '=', $Id)->delete();

		Session::flash('flash_notice',  trans("Health Professional deleted successfully!")); 
		return Redirect::to('admin/list-hcp');
	}// end updateCmstatus()




	/**
	* Function for display Hcp detail
	*
	* @param $userId 	as id of Hcp
	*
	* @return view page. 
	*/
	public function viewHcp($Id = 0){
	
		Breadcrumb::addBreadcrumb(trans("messages.user_management.dashboard"),URL::to('admin/dashboard'));
		Breadcrumb::addBreadcrumb(trans("DA/HCP"),URL::to('admin/list-hcp'));
		Breadcrumb::addBreadcrumb(trans("messages.user_management.view"),Request::url());
		$breadcrumbs 	= 	Breadcrumb::generate();
		
		if($Id){
			$hcpDetails		= Hcp::with('getCompanyDeatil')->find($Id); 			
			$companyDetails = Company::where('id',$hcpDetails->company_id)->first();
			return View::make('admin.Hcp.view', compact('hcpDetails','breadcrumbs','companyDetails'));
		}
	} // end viewHcp()



	/**
	* Function for GetCountryLocation
	*
	* @return redirect . 
	*/	
	public function GetCountryLocation($Id){
		
		$company_id		  	 = $Id;
		
		//$companyLocation     = DB::table('companies')->where('id',$company_id)->lists('location','id');
		$companyLocation     = DB::table('locations')->where('company',$company_id)->where('status',1)->lists('Name','id');

        return json_encode($companyLocation);
	}// end GetCountryLocation()
	
	function updateStatus($id){
		$currentStatus = Hcp::where('id',$id)->select('status')->first();
		$newStatus = ($currentStatus->status ==1)?0:1;
		$data = array();
		$data['status'] = $newStatus;
		Hcp::where('id',$id)->update($data);
		return Redirect::to('admin/list-hcp');
	}
	
}// end HcpController()
