<?php
namespace App\Http\Controllers\admin;

use App\Http\Controllers\BaseController;
use App\Model\AdminUser;
use App\Model\Meta;
use App\Model\MetaDescription;
use App\Model\Language;
use mjanssen\BreadcrumbsBundle\Breadcrumbs as Breadcrumb;
use Auth,Blade,Config,Cache,Cookie,DB,File,Hash,Input,Mail,mongoDate,Redirect,Request,Response,Session,URL,View,Validator;
/**
 * MetaController Controller
 *
 * Add your methods in the class below
 *
 * This file will render views from views/admin/meta
 */
 
class MetaController extends BaseController {

 /**
 * Function for display all Meta    
 *
 * @param null
 *
 * @return view page. 
 */
 
	public function listMeta(){

		### breadcrumbs Start ###
		// Breadcrums   is  added   here dynamically
		Breadcrumb::addBreadcrumb(trans("messages.system_management.dashboard"),URL::to('admin/dashboard'));
		Breadcrumb::addBreadcrumb(trans("messages.system_management.seo_list"),URL::to('admin/meta-manager'));
		$breadcrumbs 	= 	Breadcrumb::generate();
		### breadcrumbs End ###
		
		$DB				=	Meta::query();
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
		
		return  View::make('admin.meta.index',compact('breadcrumbs','result','searchVariable','sortBy','order'));
	}// end listMeta()

 /**
 * Function for display page  for add new Meta  
 *
 * @param null
 *
 * @return view page. 
 */
	public function addMeta(){
		### breadcrumbs Start ###
		// Breadcrums   is  added   here dynamically
		Breadcrumb::addBreadcrumb(trans("messages.system_management.dashboard"),URL::to('admin/dashboard'));
		Breadcrumb::addBreadcrumb(trans("messages.system_management.seo_list"),URL::to('admin/meta-manager'));
		Breadcrumb::addBreadcrumb(trans("messages.system_management.add"),'');
		$breadcrumbs 	= 	Breadcrumb::generate();
		### breadcrumbs End ###
		
		$languages	=	Language::where('active', '=', '1')->get(array('title','id'));
		
		$default_language	=	Config::get('default_language');
		$language_code 		=   $default_language['language_code'];
		
		return  View::make('admin.meta.add',compact('languages' ,'language_code','breadcrumbs'));
	} //end addMeta()
	
 /**
 * Function for save added Meta page
 *
 * @param null
 *
 * @return redirect page. 
 */
	function saveMeta(){
		$thisData				=	Input::all();
		$default_language		=	Config::get('default_language');
		$language_code 			=   $default_language['language_code'];
		$dafaultLanguageArray	=	$thisData['data'][$language_code];
		
		$validator = Validator::make(
			array(
				'meta_keyword' 				=> $dafaultLanguageArray['meta_keyword'],
				'meta_title' 				=> $dafaultLanguageArray['meta_title'],
				'description' 				=> $dafaultLanguageArray['description'],
				'page_id' 					=> Input::get('page_id'),
			),
			array(
				'meta_keyword' 				=> 'required',
				'meta_title' 				=> 'required',
				'description' 				=> 'required',
				'page_id' 					=> 'required',
			),
			array(
				'page_id.required' 					=> 'Page name field is required',
			)
		);
		
		if ($validator->fails())
		{	
			return Redirect::to('admin/meta-manager/add-meta')
				->withErrors($validator)->withInput();
		}else{
			
			$meta = new Meta;

			$meta->meta_keyword    				= $dafaultLanguageArray['meta_keyword'];
			$meta->slug    						= $this->getSlugWithoutModel($dafaultLanguageArray['meta_title'] ,'slug', 'metas');
			$meta->meta_title    				= $dafaultLanguageArray['meta_title'];
			$meta->description   				= $dafaultLanguageArray['description'];
			$meta->page_id						= Input::get('page_id');
			$meta->save();
			
			$metaId	=	$meta->id;
								
			foreach ($thisData['data'] as $language_id => $value) {
	
				$modelMetaDescription				=  new MetaDescription();
				$modelMetaDescription->language_id	=	$language_id;
				$modelMetaDescription->parent_id		=	$metaId;
				$modelMetaDescription->meta_keyword			=	$value['meta_keyword'];	
				$modelMetaDescription->meta_title	=	$value['meta_title'];	
				$modelMetaDescription->description	=	$value['description'];	
				$modelMetaDescription->page_id		= Input::get('page_id');
				$modelMetaDescription->save();
					
			}
			Session::flash('flash_notice', trans("messages.system_management.seo_added_message")); 
			return Redirect::to('admin/meta-manager');
		}
	}//end saveMeta()

 /**
 * Function for display page  for edit Meta page
 *
 * @param $Id ad id of Meta 
 *
 * @return view page. 
 */	
	public function editMeta($Id){
	
		### breadcrumbs Start ###
		// Breadcrums   is  added   here dynamically
		Breadcrumb::addBreadcrumb(trans("messages.system_management.dashboard"),URL::to('admin/dashboard'));
		Breadcrumb::addBreadcrumb(trans("messages.system_management.seo_list"),URL::to('admin/meta-manager'));
		Breadcrumb::addBreadcrumb(trans("messages.system_management.edit"),'');
		$breadcrumbs 	= 	Breadcrumb::generate();
		### breadcrumbs End ###
		
		$meta		=	Meta::find($Id);
		
		$metaDescription	=	MetaDescription::where('parent_id', '=',  $Id)->get();
		
		$multiLanguage		=	array();
		
		if(!empty($metaDescription)){
			foreach($metaDescription as $description) {
				$multiLanguage[$description->language_id]['meta_keyword']			=	$description->meta_keyword;
				$multiLanguage[$description->language_id]['meta_title']	=	$description->meta_title;
				$multiLanguage[$description->language_id]['description']		=	$description->description;				
			}
		}

		$languages	=	Language::where('active', '=', '1')->get(array('title','id'));
		
		$default_language	=	Config::get('default_language');
		$language_code 		=   $default_language['language_code'];
		
		return  View::make('admin.meta.edit',array('breadcrumbs' => $breadcrumbs,'languages' => $languages,'language_code' => $language_code,'meta' => $meta,'multiLanguage' => $multiLanguage));
	}// end editMeta()

 /**
 * Function for update Meta 
 *
 * @param $Id ad id of Meta 
 *
 * @return redirect page. 
 */
	function updateMeta($Id){
		$this_data				=	Input::all();
		$meta 					= 	Meta:: find($Id);
		$default_language		=	Config::get('default_language');
		$language_code 			=   $default_language['language_code'];
		$dafaultLanguageArray	=	$this_data['data'][$language_code];
		
		$validator = Validator::make(
			array(
				'meta_keyword' 				=> $dafaultLanguageArray['meta_keyword'],
				'meta_title' 				=> $dafaultLanguageArray['meta_title'],
				'description' 				=> $dafaultLanguageArray['description'],
				'page_id' 					=> Input::get('page_id'),
			),
			array(
				'meta_keyword' 				=> 'required',
				'meta_title' 				=> 'required',
				'description' 				=> 'required',
				'page_id' 					=> 'required',
			),
			array(
				'page_id.required' 					=> 'Page name field is required',
			)
		);
		
		if ($validator->fails())
		{	
			return Redirect::to('admin/meta-manager/edit-meta/'.$Id)
				->withErrors($validator)->withInput();
		}else{
		
			$meta->meta_keyword    				= $dafaultLanguageArray['meta_keyword'];
			$meta->meta_title    				= $dafaultLanguageArray['meta_title'];
			$meta->description   				= $dafaultLanguageArray['description'];
			$meta->page_id   					= Input::get('page_id');
			$meta->save();
			$metaId	=	$meta->id;
			$metaId	=	$Id;
			
			MetaDescription::where('parent_id', '=', $Id)->delete();
			
			foreach ($this_data['data'] as $language_id => $value) {
				$modelMetaDescription				=  new MetaDescription();
				$modelMetaDescription->language_id	=	$language_id;
				$modelMetaDescription->parent_id	=	$metaId;
				$modelMetaDescription->meta_keyword	=	$value['meta_keyword'];	
				$modelMetaDescription->meta_title	=	$value['meta_title'];	
				$modelMetaDescription->description	=	$value['description'];	
				$modelMetaDescription->page_id   	= Input::get('page_id');
				$modelMetaDescription->save();					
			}
			Session::flash('flash_notice', trans("messages.system_management.seo_updated_message")); 
			return Redirect::intended('admin/meta-manager');
		}
	}// end updateMeta()

 /**
 * Function for update Meta  status
 *
 * @param $Id as id of Meta 
 * @param $Status as status of Meta 
 *
 * @return redirect page. 
 */	
	public function updateMetaStatus($Id = 0, $Status = 0){
		$model				=	Meta::find($Id);
		$model->is_active	=	$Status;
		$model->save();
		Session::flash('flash_notice', trans("messages.system_management.seo_status_msg")); 
		return Redirect::to('admin/meta-manager');
	}// end updateMetaStatus()
	
/**
 * Function for delete Meta 
 *
 * @param $Id as id of Meta 
 *
 * @return redirect page. 
 */	
	public function deleteMeta($Id = 0){
		$meta	=	Meta::find($Id) ;
		$meta->description()->delete();
		$meta->delete();
		Session::flash('flash_notice', trans("messages.system_management.seo_delete_msg"));  
		return Redirect::to('admin/meta-manager');
	}// end deleteMeta()
	
/**
 * Function for delete meta
 *
 * @param null
 *
 * @return redirect page. 
 */
 		
	public function performMultipleAction(){
		if(Request::ajax()){
			$actionType = ((Input::get('type'))) ? Input::get('type') : '';
			
			if(!empty($actionType) && !empty(Input::get('ids'))){
				if($actionType	==	'delete'){
					Meta::whereIn('id', Input::get('ids'))->delete();
					
				}
			}
			Session::flash('flash_notice', trans("messages.system_management.action_performed_message")); 
		}
	}//end performMultipleAction()
	
}// end MetaController class
