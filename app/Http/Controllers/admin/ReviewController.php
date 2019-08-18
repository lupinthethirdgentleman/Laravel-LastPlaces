<?php
namespace App\Http\Controllers\admin;

use App\Http\Controllers\BaseController;
use App\Model\Company;
use App\Model\Review;
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
 
class ReviewController extends BaseController {
	/**
	* Function for display all Company
	*
	* @param null
	*
	* @return view page. 
	*/
 
	public function listReview(){	
	
		### breadcrumbs Start ###
		// Breadcrums   is  added   here dynamically
		Breadcrumb::addBreadcrumb(trans("messages.system_management.dashboard"),URL::to('admin/dashboard'));
		Breadcrumb::addBreadcrumb(trans("Reviews"),'');
		$breadcrumbs 	= 	Breadcrumb::generate();
		### breadcrumbs End ###
		
		$DB	=	Review::query();
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

	    $company_list = DB::select('select * from companies');
	    $hcp_list = DB::select('select * from hcp');
	    $user_list = DB::select('select * from users');		
		$benefit_list = DB::table('dropdown_managers')->where('dropdown_type', 'health_benefit')->lists('name','id');
		$locationName = DB::table('hcp as h')->join('locations as l','h.location','=','l.id')->join('reviews as r','r.hcp_id','=','h.id')->get();
		
		$result = $DB->orderBy('id','desc')
					 ->paginate(Config::get("Reading.records_per_page"));
		
		return  View::make('admin.Review.index',compact('breadcrumbs','result','searchVariable','company_list','benefit_list','hcp_list','user_list','locationName'));
	}// end listCompany()

	/**
	* Function for delete company
	*
	* @param $Id as id of company
	* @param $Status as status of company
	*
	* @return redirect page. 
	*/	
	public function deleteReview($Id = 0){
		
		$deleteReview = Review::where('id', '=', $Id)->delete();
		
		Session::flash('flash_notice',  trans("Review deleted successfully!")); 
		
		return Redirect::to('admin/list-review');
	}// end deleteCompany()




	/**
	* Function for display company detail
	*
	* @param $userId 	as id of company
	*
	* @return view page. 
	*/
	public function viewReview($Id = 0){
	
		Breadcrumb::addBreadcrumb(trans("messages.user_management.dashboard"),URL::to('admin/dashboard'));
		Breadcrumb::addBreadcrumb(trans("Reviews"),URL::to('admin/list-review'));
		Breadcrumb::addBreadcrumb(trans("messages.user_management.view"),Request::url());
		$breadcrumbs 	= 	Breadcrumb::generate();
		
		if($Id){
			
			$reviewDetails	=	Review::find($Id); 
			
			
			return View::make('admin.Review.view', compact('reviewsDetails','breadcrumbs'));
		}
		
	} // end viewCompany()

	function updateStatus($id){
		$currentStatus = Review::where('id',$id)->select('status')->first();
		$newStatus = ($currentStatus->status ==1)?0:1;
		$data = array();
		$data['status'] = $newStatus;
		Review::where('id',$id)->update($data);
		return Redirect::to('admin/list-review');
	}
}// end CompanyController()
