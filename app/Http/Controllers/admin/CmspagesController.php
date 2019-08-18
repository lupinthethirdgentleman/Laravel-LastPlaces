<?php
namespace App\Http\Controllers\admin;
use App\Http\Controllers\BaseController;
use App\Model\Cms;
use App\Model\CmsDescription;
use App\Model\Language;
use mjanssen\BreadcrumbsBundle\Breadcrumbs as Breadcrumb;
use Auth,Blade,Config,Cache,Cookie,DB,File,Hash,Input,Mail,mongoDate,Redirect,Request,Response,Session,URL,View,Validator;

/**
 * Cms Controller
 *
 * Add your methods in the class below
 *
 * This file will render views from views/admin/Cms
 */
 
class CmsPagesController extends BaseController {
	
	
	public $model	=	'Cms';
	
	public function __construct() {
		View::share('modelName',$this->model);
	}
		
 /**
 * Function for display all cms page
 *
 * @param null
 *
 * @return view page. 
 */
 
	public function listCms(){	
	
		### Breadcrums is added here dynamically ### 
		Breadcrumb::addBreadcrumb(trans("messages.global.breadcrumbs_dashboard"),route('admin_dashboard'));
		Breadcrumb::addBreadcrumb(trans("messages.$this->model.breadcrumbs_module"),route($this->model.'.index'));
		$breadcrumbs 	= 	Breadcrumb::generate();
		### breadcrumbs End ###
		
		$DB	=	Cms::query();
		$searchVariable	=	array(); 
		$inputGet		=	Input::get();
		
		if ((Input::get() && isset($inputGet['display'])) || isset($inputGet['page'])){
			$searchData	=	Input::get();
			unset($searchData['display']);
			unset($searchData['_token']);
			
			if(isset($searchData['page'])){
				unset($searchData['page']);
			}
			
			if(isset($searchData['records_per_page'])){
				unset($searchData['records_per_page']);
			}
			
			foreach($searchData as $fieldName => $fieldValue){
				if(!empty($fieldValue)){
					$DB->where("$fieldName",'like','%'.$fieldValue.'%');
					$searchVariable	=	array_merge($searchVariable,array($fieldName => $fieldValue));
				}
			}
		}
		
		if(Input::get('records_per_page')!=''){
			$searchVariable	=	array_merge($searchVariable,array('records_per_page' => Input::get('records_per_page')));
		}
		
		$sortBy = (Input::get('sortBy')) ? Input::get('sortBy') : 'updated_at';
	    $order  = (Input::get('order')) ? Input::get('order')   : 'DESC';
		 $recordPerPagePagination			=	(Input::get('records_per_page')!='') ? Input::get('records_per_page'):Config::get("Reading.records_per_page"); 
	    
	    
		$model  = $DB->orderBy($sortBy, $order)->paginate($recordPerPagePagination);
		
		return  View::make("admin.$this->model.index",compact('breadcrumbs','model','searchVariable','sortBy','order'));
	}// end listcms()

 /**
 * Function for display page  for add new cms page 
 *
 * @param null
 *
 * @return view page. 
 */
	public function addCms(){
		
		### Breadcrums is added here dynamically ###
		Breadcrumb::addBreadcrumb(trans("messages.global.breadcrumbs_dashboard"),route('admin_dashboard'));
		Breadcrumb::addBreadcrumb(trans("messages.$this->model.breadcrumbs_module"),route($this->model.'.index'));;
		Breadcrumb::addBreadcrumb(trans("messages.$this->model.breadcrumbs_add"),'');
		$breadcrumbs 	= 	Breadcrumb::generate();
		### breadcrumbs End ###
		
		$languages	=	Language::where('is_active', '=', ACTIVE)->get(array('title','id'));
		$language_code		=	Config::get('default_language.language_code');
		
		return  View::make("admin.$this->model.add",compact('languages' ,'language_code','breadcrumbs'));
	} //end addCms()
	
 /**
 * Function for save added cms page
 *
 * @param null
 *
 * @return redirect page. 
 */
	function saveCms(){
		
		$thisData				=	Input::all();
		
		$default_language		=	Config::get('default_language');
		$language_code 			=   $default_language['language_code'];
		$dafaultLanguageArray	=	$thisData['data'][$language_code];
		
		$validator = Validator::make(
			array(
				'name' 				=> Input::get('name'),
				'title' 			=> $dafaultLanguageArray['title'],
				'body' 				=> $dafaultLanguageArray['body'],
				'meta_title' 		=> $dafaultLanguageArray['meta_title'],
				'meta_description'  => $dafaultLanguageArray['meta_description'],
				'meta_keywords' 	=> $dafaultLanguageArray['meta_keywords']
			),
			array(
				'name' 				=> 'required',
				'title' 			=> 'required',
				'body' 				=> 'required',
				'meta_title' 		=> 'required',
				'meta_description' 	=> 'required',
				'meta_keywords' 	=> 'required'
			)
		);
		
		if ($validator->fails())
		{	
			return Redirect::back()
				->withErrors($validator)->withInput();
		}else{
				$model = new Cms;

				$model->name    			= Input::get('name');
				$model->title   			= $dafaultLanguageArray['title'];
				$model->body   			= $dafaultLanguageArray['body'];
				$model->meta_title   		= $dafaultLanguageArray['meta_title'];
				$model->meta_description  = $dafaultLanguageArray['meta_description'];
				$model->meta_keywords   	= $dafaultLanguageArray['meta_keywords'];
				$model->slug   			= $this->getSlug($dafaultLanguageArray['title'],'slug','Cms');
				
				$model->save();
				$modelId	=	$model->id;
							
				foreach ($thisData['data'] as $language_id => $descriptionResult) {
					if (is_array($descriptionResult))
						foreach ($descriptionResult as $key => $value) {
							CmsDescription::insert(
								array(
									'language_id'				=>	$language_id,
									'foreign_key'				=>	$modelId,
									'source_col_name'			=>	$key,
									'source_col_description'	=>	$value,
								)
							);
						}
				}
				
				Session::flash('flash_notice',  trans("messages.$this->model.added_message"));  
				return Redirect::route("$this->model.index");
		}
	}//end saveCms()

 /**
 * Function for display page  for edit cms page
 *
 * @param $Id ad id of cms page
 *
 * @return view page. 
 */	
	public function editCms($modelId){
	
		$model		=	Cms::findorFail($modelId);
		$modelDescription	=	CmsDescription::where('foreign_key', '=',  $modelId)->get();
		
		### Breadcrums   is  added   here dynamically ###
		Breadcrumb::addBreadcrumb(trans("messages.global.breadcrumbs_dashboard"),route('admin_dashboard'));
		Breadcrumb::addBreadcrumb(trans("messages.$this->model.breadcrumbs_module"),route($this->model.'.index'));;
		Breadcrumb::addBreadcrumb(trans("messages.$this->model.breadcrumbs_edit"),'');
		$breadcrumbs 	= 	Breadcrumb::generate();
		### breadcrumbs End ###
		
		$multiLanguage		=	array();
		
		if(!empty($modelDescription)){
			foreach($modelDescription as $description) {
				$multiLanguage[$description->language_id][$description -> source_col_name]	=	$description->source_col_description;						
			}
		}
		
		$languages				=	Language::where('is_active', '=', ACTIVE)->get(array('title','id'));
		$language_code			=	Config::get('default_language.language_code');
		return  View::make("admin.$this->model.edit",compact('breadcrumbs','languages','language_code','model','multiLanguage'));
		
	}// end editCms()

	/**
	 * Function for update Cms
	 *
	 * @param $modelId as id of Cms 
	 *
	 * @return redirect page. 
	 */
	function updateCms($modelId){
	
		$this_data				=	Input::all();
		
		$default_language		=	Config::get('default_language');
		$model 					= 	Cms:: findorFail($modelId);
		$activeLanguageCode		=	Config::get('default_language.language_code');
		$dafaultLanguageArray	=	$this_data['data'][$activeLanguageCode];
			
		$validator = Validator::make(
			array(
				'name' 				=> Input::get('name'),
				'title' 			=> $dafaultLanguageArray['title'],
				'body' 				=> $dafaultLanguageArray['body'],
				'meta_title' 		=> $dafaultLanguageArray['meta_title'],
				'meta_description'  => $dafaultLanguageArray['meta_description'],
				'meta_keywords' 	=> $dafaultLanguageArray['meta_keywords']
			),
			array(
				'name' 				=> 'required',
				'title' 			=> 'required',
				'body' 				=> 'required',
				'meta_title' 		=> 'required',
				'meta_description' 	=> 'required',
				'meta_keywords' 	=> 'required'
			)
		);
		
		if ($validator->fails()){	
			return Redirect::back()
				->withErrors($validator)->withInput();
		}else{

			$model->name    			= Input::get('name');
			$model->title    			= $dafaultLanguageArray['title'];
			$model->body   				= $dafaultLanguageArray['body'];
			$model->meta_title   		= $dafaultLanguageArray['meta_title'];
			$model->meta_description   	= $dafaultLanguageArray['meta_description'];
			$model->meta_keywords   	= $dafaultLanguageArray['meta_keywords'];
			$model->save();
			
			CmsDescription::where('foreign_key', '=', $modelId)->delete();
			
			foreach ($this_data['data'] as $language_id => $cms) {
				if (is_array($cms))
					foreach ($cms as $key => $value) {
						CmsDescription::insert(
							array(
								'language_id'				=>	$language_id,
								'foreign_key'				=>	$modelId,
								'source_col_name'			=>	$key,
								'source_col_description'	=>	$value,
							)
						);
					}
			}
		
			Session::flash('flash_notice',  trans("messages.$this->model.updated_message"));
			return Redirect::route("$this->model.index");
		}
	}// end updateCms()

 /**
 * Function for update cms page status
 *
 * @param $modelId as id of cms page
 * @param $modelStatus as status of cms page
 *
 * @return redirect page. 
 */	
	public function updateCmsStatus($modelId = 0, $modelStatus = 0){
		Cms::where('id', '=', $modelId)->update(array('is_active' => $modelStatus));
		Session::flash('flash_notice', trans("messages.$this->model.status_updated_message")); 
		return Redirect::route("$this->model.index");
	}// end updateCmstatus()
	
/**
 * Function for delete,active or deactivate multiple cms
 *
 * @param null
 *
 * @return view page. 
 */
 
	public function performMultipleAction(){
		if(Request::ajax()){
			$actionType = ((Input::get('type'))) ? Input::get('type') : '';
			if(!empty($actionType) && !empty(Input::get('ids'))){
				if($actionType	==	'delete'){
					CmsDescription::whereIn('foreign_key', Input::get('ids'))->delete();
					Cms::whereIn('id', Input::get('ids'))->delete();
				}elseif($actionType	==	'active'){
					Cms::whereIn('id', Input::get('ids'))->update(array('is_active' => ACTIVE));
				}
				elseif($actionType	==	'inactive'){
					Cms::whereIn('id', Input::get('ids'))->update(array('is_active' => INACTIVE));
				}
				Session::flash('success', trans("messages.global.action_performed_message")); 
			}
		}
	}//end performMultipleAction()
	
}// end CmsController()
