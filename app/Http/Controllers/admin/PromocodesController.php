<?php
namespace App\Http\Controllers\admin;
use App\Http\Controllers\BaseController;
use App\Model\Promocodes;
use mjanssen\BreadcrumbsBundle\Breadcrumbs as Breadcrumb;
use Auth, Blade, Config, Cache, Cookie, DB, File, Hash, Input, Mail, mongoDate, Redirect, Request, Response, Session, URL, View, Validator;

/**
 * Promocodes Controller 
 *
 * Add your methods in the class below
 *
 * This file will render views from views/admin/Promocodes
 */
 
class PromocodesController extends BaseController {
 
 /**
 * Function for display list of all Promocodes 
 *
 * @param null
 *
 * @return view page. 
 */	
	public function listPromocodes(){
		// Breadcrums   is  added   here dynamically
		Breadcrumb::addBreadcrumb(trans("messages.global.breadcrumbs_dashboard"),URL::to('admin/dashboard'));
		Breadcrumb::addBreadcrumb(trans("messages.promocodes.breadcrumbs_Promocodes_module"),URL::to('admin/promocodes-manager'));
		$breadcrumbs 	= 	Breadcrumb::generate();
		
		$DB = Promocodes::query();
		
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
		
		$result 	= 	$DB->orderBy($sortBy, $order)->paginate(Config::get("Reading.records_per_page"));
		return  View::make('admin.Promocodes.index', compact('breadcrumbs','result' ,'searchVariable','sortBy','order'));
	}// end listPromocodes()

 /**
 * Function for display Promocodes add page 
 *
 * @param null
 *
 * @return view page. 
 */	
	public function addPromocodes(){
		//  Breadcrumbs 
		Breadcrumb::addBreadcrumb(trans("messages.global.breadcrumbs_dashboard"),URL::to('admin/dashboard'));
		Breadcrumb::addBreadcrumb(trans("messages.promocodes.breadcrumbs_Promocodes_module"),URL::to('admin/promocodes-manager'));
		Breadcrumb::addBreadcrumb(trans("messages.promocodes.breadcrumbs_Promocodes_add"),'');
		$breadcrumbs 	= 	Breadcrumb::generate();
		
		return  View::make('admin.Promocodes.add',compact('breadcrumbs')); 
	}// end addPromocodes()
	
 /**
 * Function for save Promocodes
 *
 * @param null
 *
 * @return redirect page. 
 */	

	public function savePromocodes(){
		$validationRules	= array(
			'code' => 'required|unique:promocodes',
			'discount' => 'required|numeric|min:1|max:100',
			'from' => 'required',
			'to' => 'required',
		);
		
		// this is use  for validation
		$validator = Validator::make(
			Input::all(),
			$validationRules
		);
	
		//  if  validation error then redirect with error otherwise result will be save
		if ($validator->fails()){
			return Redirect::back()
				->withErrors($validator)->withInput();	
				
		}else{
			Promocodes::insert(
					array(
						'code' 			=> Input::get('code'),
						'discount' 		=> Input::get('discount'),
						'date_from' 	=> Input::get('from'),
						'date_to' 		=> Input::get('to'),
						'status'		=> 1,
						'created_at' 	=> DB::raw('NOW()'),
						'updated_at' 	=> DB::raw('NOW()'),
				)
			);
				//   flash message will be  display message	
			Session::flash('flash_notice', trans("messages.promocodess.Promocodes_add"));
			return Redirect::to('admin/promocodes-manager');
			
		}
		return  View::make('admin.promocodesmanager.add',array('data' => $data)); 
	}// end savePromocodes()
	
 /**
 * Function for display edit Promocodes page 
 *
 * @param $PromocodesId as id of ad
 *
 * @return view page. 
 */	
	
	public function editPromocodes($PromocodesId = 0){
		//  Breadcrumbs 
		Breadcrumb::addBreadcrumb(trans("messages.global.breadcrumbs_dashboard"),URL::to('admin/dashboard'));
		Breadcrumb::addBreadcrumb(trans("messages.promocodes.breadcrumbs_Promocodes_module"),URL::to('admin/promocodes-manager'));
		Breadcrumb::addBreadcrumb(trans("messages.promocodes.breadcrumbs_Promocodes_edit"),'');
		$breadcrumbs 	= 	Breadcrumb::generate();
		
		
		$result			=	Promocodes::where('id', '=', $PromocodesId)->get();		
		return  View::make('admin.Promocodes.edit',compact('breadcrumbs', 'result')); 
	}//end editPromocodess()
	
 /**
 * Function for  update Promocodes
 *
 * @param null
 *
 * @return redirect page. 
 */	
	public function updatePromocodes(){
		$validationRules	=  array(
				'discount' => 'required|numeric|min:1|max:100',
				'from' => 'required',
				'to' => 'required',
		);
		$validator = Validator::make(
			Input::all(),
			$validationRules
		);
		if ($validator->fails()){
			return Redirect::to('admin/promocodes-manager/edit-promocodes/'.Input::get('id'))
				->withErrors($validator)->withInput();	
				
		}else{
			Promocodes::where('id', Input::get('id'))
				->update(
					array(
					'discount' => Input::get('discount'),
					'date_from' => Input::get('from'),
					'date_to' => Input::get('to'),
					'updated_at' 	=> DB::raw('NOW()')
				));	
			
			Session::flash('flash_notice', trans('messages.promocodes.Promocodes_update')); 
			return Redirect::to('admin/promocodes-manager');
			
		}
	}// end updatePromocodes()
	
 /**
 * Function for delete Promocodes
 *
 * @param $PromocodesId as id of ad
 *
 * @return redirect page. 
 */	
 
	public function deletePromocodes($PromocodesId = 0){
		if($PromocodesId){
			$result	=	DB::table('promocodes')->where('id', '=', $PromocodesId)->first();
			$promocodes	=	Promocodes::find($PromocodesId);
			$promocodes->delete();
			Session::flash('flash_notice',trans('messages.promocodes.Promocodes_delete')); 
		}
		return Redirect::to('admin/promocodes-manager');
	}// end deletePromocodes()
	
 /**
 * Function for update Promocodes status
 *
 * @param $PromocodesId 	as id of ad
 * @param $Promocodesstatus as status of ad
 *
 * @return redirect page. 
 */	
 
	public function updatePromocodestatus($PromocodesId = 0, $Promocodestatus = 0){
		Promocodes::where('id', '=', $PromocodesId)->update(array('status' => $Promocodestatus));
		Session::flash('flash_notice', trans('messages.promocodes.Promocodes_status')); 
		return Redirect::to('admin/promocodes-manager');
	} //end  updatePromocodesstatus()
	
	
 
}
