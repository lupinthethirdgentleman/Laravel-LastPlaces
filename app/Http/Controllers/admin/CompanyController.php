<?php
namespace App\Http\Controllers\admin;

use App\Http\Controllers\BaseController;
use App\Model\Company;
use App\Model\CmsDescription;
use App\Model\Language;
use mjanssen\BreadcrumbsBundle\Breadcrumbs as Breadcrumb;
use Auth,Blade,Config,Cache,Cookie,DB,File,Hash,Input,Mail,mongoDate,Redirect,Request,Response,Session,URL,View,Validator;

/**
 * Company Controller
 *
 * Add your methods in the class below
 *
 * This file will render views from views/admin/Company
 */
 
class CompanyController extends BaseController {
	/**
	* Function for display all Company
	*
	* @param null
	*
	* @return view page. 
	*/
 
	public function listCompany(){	
	
		### breadcrumbs Start ###
		// Breadcrums   is  added   here dynamically
		Breadcrumb::addBreadcrumb(trans("messages.system_management.dashboard"),URL::to('admin/dashboard'));
		Breadcrumb::addBreadcrumb(trans("Companies"),'');
		$breadcrumbs 	= 	Breadcrumb::generate();
		### breadcrumbs End ###
		
		$DB	=	Company::query();
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
		
		return  View::make('admin.Company.index',compact('breadcrumbs','result','searchVariable'));
	}// end listCompany()



	/**
	* Function for display page for add new company
	*
	* @param null
	*
	* @return view page. 
	*/
	public function addCompany(){
		
		### breadcrumbs Start ###
		// Breadcrums   is  added   here dynamically
		Breadcrumb::addBreadcrumb(trans("messages.system_management.dashboard"),URL::to('admin/dashboard'));
		Breadcrumb::addBreadcrumb(trans("Companies"),URL::to('admin/list-company'));
		Breadcrumb::addBreadcrumb(trans("Add New Company"),'');
		$breadcrumbs 	= 	Breadcrumb::generate();
		### breadcrumbs End ###
		
	
		return  View::make('admin.Company.add',compact('breadcrumbs'));
	} //end addCompany()
	


	/**
	* Function for save added company
	*
	* @param null
	* 
	* @return redirect page. 
	*/
	function saveCompany(){
		
		$thisData				=	Input::all();
		$validator = Validator::make(
			array(
				'company_name' 				=> Input::get('company_name'),
				/*'company_location' 			=> Input::get('company_location'),*/

			),
			array(
				'company_name' 				=> 'required',
				/*'company_location' 			=> 'required',*/
			)
		);
		
		if ($validator->fails())
		{  return Redirect::back()->withErrors($validator)->withInput();

		}else{
				$obj 	 		= new Company;
				$obj->Name  	= Input::get('company_name');
				/*$obj->location  = Input::get('company_location');*/
				$obj->status    = 1;


				if(input::hasFile('image')){
					$extension 	=	 Input::file('image')->getClientOriginalExtension();
					$fileName	=	time().'-company-image.'.$extension;
					
					if(Input::file('image')->move(COMPANY_IMAGE_ROOT_PATH, $fileName)){
						$obj->image			=	$fileName;
					}
				}


				$obj->save();	
				

				Session::flash('flash_notice', trans("Company added successfully!")); 
				return Redirect::to('admin/list-company');
		}
	}//end saveCompany()



	/**
	* Function for display page  for edit company
	*
	* @param $Id ad id of company page
	*
	* @return view page. 
	*/	
	public function editCompany($Id){
	
		### breadcrumbs Start ###
		// Breadcrums   is  added   here dynamically
		Breadcrumb::addBreadcrumb(trans("messages.system_management.dashboard"),URL::to('admin/dashboard'));
		Breadcrumb::addBreadcrumb(trans("Companies"),URL::to('admin/list-company'));
		Breadcrumb::addBreadcrumb(trans("Edit Company"),'');
		$breadcrumbs 	= 	Breadcrumb::generate();
		
		### breadcrumbs End ###
		
		$Company		=	Company::find($Id);

		return  View::make('admin.Company.edit',compact('Company','breadcrumbs'));
	}// end editCompany()



	/**
	* Function for update company
	*
	* @param $Id ad id of company
	*
	* @return redirect page. 
	*/
	function updateCompany($Id){
		$thisData				=	Input::all();
		
		$validator = Validator::make(
			array(
				'company_name' 				=> Input::get('company_name'),
				/*'company_location' 			=> Input::get('company_location'),*/
				
			),
			array(
				'company_name' 				=> 'required',
				/*'company_location' 			=> 'required',*/
			)
		);
		
		if ($validator->fails())
		{  return Redirect::back()->withErrors($validator)->withInput();

		}else{
			
				$obj 	 		= Company::find($Id);
				$obj->Name  	= Input::get('company_name');
				/*$obj->location  = Input::get('company_location');*/


				if(input::hasFile('image')){
				$extension 	=	 Input::file('image')->getClientOriginalExtension();
				$fileName	=	time().'-company-image.'.$extension;
			
				if(Input::file('image')->move(COMPANY_IMAGE_ROOT_PATH, $fileName)){
					$obj->image			=	$fileName;
				}
				$image 			=	Company::where('id',$Id)->pluck('image');
				@unlink(COMPANY_IMAGE_ROOT_PATH.$image);
			}


				$obj->save();	

				Session::flash('flash_notice', trans("Company updated successfully!")); 
				return Redirect::to('admin/list-company');
		}
	}// end updateCompany()



	/**
	* Function for delete company
	*
	* @param $Id as id of company
	* @param $Status as status of company
	*
	* @return redirect page. 
	*/	
	public function deleteCompany($Id = 0){
		
		$deleteCompany = Company::where('id', '=', $Id)->delete();
		
		Session::flash('flash_notice',  trans("Company deleted successfully!")); 
		
		return Redirect::to('admin/list-company');
	}// end deleteCompany()




	/**
	* Function for display company detail
	*
	* @param $userId 	as id of company
	*
	* @return view page. 
	*/
	public function viewCompany($Id = 0){
	
		Breadcrumb::addBreadcrumb(trans("messages.user_management.dashboard"),URL::to('admin/dashboard'));
		Breadcrumb::addBreadcrumb(trans("Companies"),URL::to('admin/list-company'));
		Breadcrumb::addBreadcrumb(trans("messages.user_management.view"),Request::url());
		$breadcrumbs 	= 	Breadcrumb::generate();
		
		if($Id){
			
			$compnayDetails	=	Company::find($Id); 
			
			
			return View::make('admin.Company.view', compact('compnayDetails','breadcrumbs'));
		}
		
	} // end viewCompany()



	/**
	* Function for update company status
	*
	* @param $Id as id of company page
	* @param $Status as status of company 
	*
	* @return redirect page. 
	*/	
	public function updateCompanyStatus($Id =0, $Status=0){

		Company::where('id', '=', $Id)->update(array('status' => $Status));

		
		Session::flash('flash_notice',  trans("Company status updated successfully!")); 
		
		return Redirect::to('admin/list-company');
	}// end updateCompanyStatus()

	
}// end CompanyController()
