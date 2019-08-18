<?php
namespace App\Http\Controllers\admin;

use App\Http\Controllers\BaseController;
use App\Model\DropDown;
use App\Model\Language;
use App\Model\DropDownDescription;
use mjanssen\BreadcrumbsBundle\Breadcrumbs as Breadcrumb;
use Auth,Blade,Config,Cache,Cookie,DB,File,Hash,Input,Mail,mongoDate,Redirect,Request,Response,Session,URL,View,Validator;
/**
 * DropDownController Controller
 *
 * Add your methods in the class below
 *
 * This file will render views from views/admin/dropdown
 */
 
class DropDownController extends BaseController {

	 /**
	 * Function for display all DropDown    
	 *
	 * @param $type as category of dropdown 
	 *
	 * @return view page. 
	 */
	public function listDropDown($type=''){

		### breadcrumbs Start ###
		// Breadcrums   is  added   here dynamically
		Breadcrumb::addBreadcrumb(trans("messages.master.dashboard"),URL::to('admin/dashboard'));
		Breadcrumb::addBreadcrumb(studly_case($type),URL::to('admin/dropdown-manager/'.$type));
		$breadcrumbs 	= 	Breadcrumb::generate();
		### breadcrumbs End ###
		
		$DB				=	DropDown::query()->where('dropdown_type',$type);
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
		
		$sortBy = (Input::get('sortBy')) ? Input::get('sortBy') : 'updated_at';
	    $order  = (Input::get('order')) ? Input::get('order')   : 'DESC';
		
		$result = $DB->orderBy($sortBy, $order)->paginate(Config::get("Reading.records_per_page")); 
		
		return  View::make('admin.dropdown.index',compact('breadcrumbs','result','searchVariable','sortBy','order','type'));
	}// end listDropDown()

	 /**
	 * Function for display page  for add new DropDown  
	 *
	 * @param $type as category of dropdown 
	 *
	 * @return view page. 
	 */
	public function addDropDown($type=''){
	
		### breadcrumbs Start ###
		// Breadcrums   is  added   here dynamically
		Breadcrumb::addBreadcrumb(trans("messages.master.dashboard"),URL::to('admin/dashboard'));
		Breadcrumb::addBreadcrumb(studly_case($type),URL::to('admin/dropdown-manager/'.$type));
		Breadcrumb::addBreadcrumb(trans("messages.master.add"),'');
		$breadcrumbs 	= 	Breadcrumb::generate();
		### breadcrumbs End ###
		
		$languages	=	Language::where('active', '=', '1')->get(array('title','id'));
	
		$default_language	=	Config::get('default_language');
		$language_code 		=   $default_language['language_code'];
		
		return  View::make('admin.dropdown.add',compact('languages' ,'language_code','breadcrumbs','type'));
	} //end addDropDown()
	
	 /**
	 * Function for save added DropDown page
	 *
	 * @param null
	 *
	 * @return redirect page. 
	 */
	function saveDropDown($type=''){
		$thisData				=	Input::all();
		$default_language		=	Config::get('default_language');
		$language_code 			=   $default_language['language_code'];
		$dafaultLanguageArray	=	$thisData['data'][$language_code];
		
		$validator = Validator::make(
			array(
				'name' 			=>  $dafaultLanguageArray['name'],
				'dropdown_type'	=>	$type,
				
			),	
			array(
				'name' 			=> 'required',
				
			)
		);
		
		if ($validator->fails()){	
			return Redirect::to('admin/dropdown-manager/add-dropdown/'.$type)
				->withErrors($validator)->withInput();
		}else{
			
			$dropdown = new DropDown;
			$dropdown->slug    				= $this->getSlugWithoutModel($type ,'slug', 'dropdown_managers');
			$dropdown->name    				= $dafaultLanguageArray['name'];
			$dropdown->dropdown_type    	= $type;
		
			$dropdown->save(); 
			
			$dropdownId	=	$dropdown->id;
								
			foreach ($thisData['data'] as $language_id => $value) {
				$modelDropDownDescription				=  new DropDownDescription();
				$modelDropDownDescription->language_id	=	$language_id;
				$modelDropDownDescription->parent_id	=	$dropdownId;
				$modelDropDownDescription->name			=	$value['name'];		
				$modelDropDownDescription->save();
			}
			Session::flash('flash_notice', trans("messages.master.master_add_success_message")); 
			return Redirect::to('admin/dropdown-manager/'.$type);
		}
	}//end saveDropDown()

	 /**
	 * Function for display page  for edit DropDown page
	 *
	 * @param $Id ad id of DropDown 
	 * @param $type as category of dropdown 
	 *
	 * @return view page. 
	 */	
	public function editDropDown($Id,$type){
	
		### breadcrumbs Start ###
		// Breadcrums   is  added   here dynamically
		
		Breadcrumb::addBreadcrumb(trans("messages.master.dashboard"),URL::to('admin/dashboard'));
		Breadcrumb::addBreadcrumb(studly_case($type),URL::to('admin/dropdown-manager/'.$type));
		Breadcrumb::addBreadcrumb(trans("messages.master.edit"),'');
		
		$breadcrumbs 	= 	Breadcrumb::generate();
		### breadcrumbs End ###
		
		$dropdown				=	DropDown::find($Id);
		$dropdownDescription	=	DropDownDescription::where('parent_id', '=',  $Id)->get();
		$multiLanguage		 	=	array();
		
		if(!empty($dropdownDescription)){
			foreach($dropdownDescription as $description) {
				$multiLanguage[$description->language_id]['name']			=	$description->name;				
			}
		}

		$languages	=	Language::where('active', '=', '1')->get(array('title','id'));
		
		$default_language	=	Config::get('default_language');
		$language_code 		=   $default_language['language_code'];
		
		return  View::make('admin.dropdown.edit',array('breadcrumbs' => $breadcrumbs,'languages' => $languages,'language_code' => $language_code,'dropdown' => $dropdown,'multiLanguage' => $multiLanguage,'type'=>$type));
	}// end editDropDown()

	 /**
	 * Function for update DropDown 
	 *
	 * @param $Id ad id of DropDown 
	 * @param $type as category of dropdown 
	 *
	 * @return redirect page. 
	 */
	function updateDropDown($Id,$type=''){
		$this_data				=	Input::all();
		$dropdown 				= 	DropDown:: find($Id);
		$default_language		=	Config::get('default_language');
		$language_code 			=   $default_language['language_code'];
		$dafaultLanguageArray	=	$this_data['data'][$language_code];
		
		$validator = Validator::make(
			array(
				'name' 		=> $dafaultLanguageArray['name'],
				'image' 		=> Input::file('image'),
			),
			array(
				'name' 		=> 'required',
				'image' 		=> 'mimes:jpeg,jpg,png,gif',
			)
		);
		
		if ($validator->fails())
		{	
			return Redirect::to('admin/dropdown-manager/edit-dropdown/'.$Id.'/'.$type)
				->withErrors($validator)->withInput();
		}else{
			$dropdown->name	= $dafaultLanguageArray['name'];
			
			if (Input::hasFile('image')){
				$extension 	=	 Input::file('image')->getClientOriginalExtension();
				$fileName	=	time().'-resource-image.'.$extension;
				if(Input::file('image')->move(MASTERS_IMAGE_ROOT_PATH, $fileName)){
					$dropdown->image	= 	$fileName;
				}
			}
			$dropdown->save();
			
			$dropdownId		=	$dropdown->id;
			$dropdownId		=	$Id;
			
			DropDownDescription::where('parent_id', '=', $Id)->delete();
			
			foreach ($this_data['data'] as $language_id => $value) {
				$modelDropDownDescription					=  new DropDownDescription();
				$modelDropDownDescription->language_id		=	$language_id;
				$modelDropDownDescription->name				=	$value['name'];	
				$modelDropDownDescription->parent_id		=	$dropdownId;
				$modelDropDownDescription->save();					
			}
			Session::flash('flash_notice',trans("messages.master.master_edit_success_message")); 
			return Redirect::intended('admin/dropdown-manager/'.$type);
		}
	}// end updateDropDown()

	 /**
	 * Function for update DropDown  status
	 *
	 * @param $Id as id of DropDown 
	 * @param $Status as status of DropDown 
	 * @param $type as category of dropdown 
	 *
	 * @return redirect page. 
	 */	
	public function updateDropDownStatus($Id = 0, $Status = 0,$type=''){
		
		
		if($status == 1){
			$message	=	trans("messages.master.master_activate_message");
		}else{
			$message	=	trans("messages.master.master_deactivate_message");
		}
		
		$model				=	DropDown::find($Id);
		$model->is_active	=	$Status;
		$model->save();
		Session::flash('flash_notice',$message); 
		return Redirect::to('admin/dropdown-manager/'.$type);
	}// end updateDropDownStatus()
	
	/**
	 * Function for delete DropDown 
	 *
	 * @param $Id as id of DropDown 
	 * @param $type as category of dropdown 
	 *
	 * @return redirect page. 
	 */	
	public function deleteDropDown($Id = 0,$type=''){
		$dropdown		=	DropDown::find($Id) ;
		$dropdown->description()->delete();
		if($type=='faq'){
		$dropdown->faq()->delete();
		}
		$dropdown->delete();
		Session::flash('flash_notice', trans("messages.master.master_delete_message"));  
		return Redirect::to('admin/dropdown-manager/'.$type);
	}// end deleteDropDown()
	
		
/**
 * Function for multiple delete
 *
 * @param $type as type of dropdown
 *
 * @return redirect page. 
 */
 		
	public function performMultipleAction($type = 0){
		if(Request::ajax()){
			$actionType = ((Input::get('type'))) ? Input::get('type') : '';
			
			if(!empty($actionType) && !empty(Input::get('ids'))){
				if($actionType	==	'delete'){
					$dropdown		=	DropDown::whereIn('id', Input::get('ids'));
						$dropdown->description()->delete();
					if($type=='faq'){
						$dropdown->faq()->delete();
					}
					$dropdown->delete();
					}
					Session::flash('flash_notice', trans("messages.user_management.action_performed_message")); 
				}
		}
	}//end performMultipleAction()
	
}// end DropDownController
