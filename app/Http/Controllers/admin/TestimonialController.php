<?php
namespace App\Http\Controllers\admin;

use App\Http\Controllers\BaseController;
use App\Model\Testimonial;
use App\Model\TestimonialDescription;
use App\Model\Language;
use mjanssen\BreadcrumbsBundle\Breadcrumbs as Breadcrumb;
use Auth, Blade, Config, Cache, Cookie, DB, File, Hash, Input, Mail, mongoDate, Redirect, Request, Response, Session, URL, View, Validator;

/**
 * TestimonialController Controller
 *
 * Add your methods in the class below
 *
 * This file will render views from views/admin/testimonial
 */
 
class TestimonialController extends BaseController {

	public $model	=	'Testimonial';
	
	public function __construct() {
		View::share('modelName',$this->model);
	}

	 /**
	 * Function for display all Testimonial    
	 *
	 * @param null
	 *
	 * @return view page. 
	 */
	public function listTestimonial(){
	
		### breadcrumbs Start ###
		Breadcrumb::addBreadcrumb(trans("messages.global.breadcrumbs_dashboard"),route('admin_dashboard'));
		Breadcrumb::addBreadcrumb(trans("messages.$this->model.breadcrumbs_module"),route($this->model.'.index'));
		$breadcrumbs 	= 	Breadcrumb::generate();
		### breadcrumbs End ###
		
		$DB				=	Testimonial::query();
		$searchVariable	=	array(); 
		$inputGet		=	Input::get();
		## Searching on the basis of comment ##
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
		$model = $DB->orderBy($sortBy, $order)->paginate(Config::get("Reading.records_per_page"));
		return  View::make("admin.$this->model.index",compact('breadcrumbs','model','searchVariable','sortBy','order'));
	}// end listTestimonial()

 /**
 * Function for display page  for add new Testimonial  
 *
 * @param null
 *
 * @return view page. 
 */
	public function addTestimonial(){
	
		### breadcrumbs Start ###
		// Breadcrums   is  added   here dynamically
		Breadcrumb::addBreadcrumb(trans("messages.global.breadcrumbs_dashboard"),route('admin_dashboard'));
		Breadcrumb::addBreadcrumb(trans("messages.$this->model.breadcrumbs_module"),route($this->model.'.index'));
		Breadcrumb::addBreadcrumb(trans("messages.$this->model.breadcrumbs_add"),'');
		$breadcrumbs 	= 	Breadcrumb::generate();
		### breadcrumbs End ###
		
		$languages			=	Language::where('is_active', '=',1)->get(array('title','id'));
		$language_code		=	Config::get('default_language.language_code');
		return  View::make("admin.$this->model.add",compact('languages' ,'language_code','breadcrumbs'));
	} //end addTestimonial()
	
	 /**
	 * Function for save added Testimonial page
	 *
	 * @param nullFantastic, I'm totally blown away by Testimonial Generator.
	 *
	 * @return redirect page. 
	 */
	function saveTestimonial(){
		$thisData				=	Input::all();
		$default_language		=	Config::get('default_language');
		$language_code 			=   $default_language['language_code'];
		$dafaultLanguageArray	=	$thisData['data'][$language_code];
		
		$validator = Validator::make(
			array(
				'client_name' 			=> $dafaultLanguageArray['client_name'],
				'image'					=> $dafaultLanguageArray['image'],
				'client_designation' 	=> $dafaultLanguageArray['client_designation'],
				'title' 				=> $dafaultLanguageArray['title'],
				'comment' 				=> $dafaultLanguageArray['comment'],
			),
			array(
				'client_name' 			=> 'required',
				'image'					=> 'image|required|mimes:jpeg,png,bmp,gif,svg',
				'client_designation' 	=> 'required',
				'title' 				=> 'required',
				'comment' 				=> 'required',
			)
		);
		
		if ($validator->fails())
		{	return Redirect::back()
				->withErrors($validator)->withInput();
		}else{
			$model 						= new Testimonial;
			$model->client_name    		= $dafaultLanguageArray['client_name'];
			$model->client_designation  = $dafaultLanguageArray['client_designation'];
			$model->title   			= $dafaultLanguageArray['title'];
			$model->comment   			= $dafaultLanguageArray['comment'];
			
			if(Input::file()) {
				$extension 	=	 $dafaultLanguageArray['image']->getClientOriginalExtension();
				$fileName	=	time().'-Testimonial-image.'.$extension;
				if($dafaultLanguageArray['image']->move(TESTIMONIAL_IMAGE_ROOT_PATH, $fileName)){
						$model->image			=	$fileName;
				}
			}

			$model->save();
			
			$modelId	=	$model->id;		
			foreach ($thisData['data'] as $language_id => $descriptionResult) {
				$modelDescription							=   new TestimonialDescription();
				$modelDescription->language_id				=	$language_id;
				$modelDescription->parent_id				=	$modelId;
				$modelDescription->client_name				=	$descriptionResult['client_name'];
				$modelDescription->client_designation		=	$descriptionResult['client_designation'];
				$modelDescription->title					=	$descriptionResult['title'];
				$modelDescription->comment					=	$descriptionResult['comment'];

				if($model->image) {
					$modelDescription->image =	$model->image;
				}

				$modelDescription->save();
			}
			Session::flash('flash_notice',  trans("messages.$this->model.added_message"));  
			return Redirect::route("$this->model.index");
		}
	}//end saveTestimonial()

	 /**
	 * Function for display page  for edit Testimonial page
	 *
	 * @param $Id as id of Testimonial 
	 *
	 * @return view page. 
	 */	
	public function editTestimonial($modelId){
		$model				=	Testimonial::findorFail($modelId);
		$modelDescriptions	=	TestimonialDescription::where('parent_id','=',$modelId)->get();
		
		// Breadcrums   is  added   here dynamically
		Breadcrumb::addBreadcrumb(trans("messages.global.breadcrumbs_dashboard"),route('admin_dashboard'));
		Breadcrumb::addBreadcrumb(trans("messages.$this->model.breadcrumbs_module"),route($this->model.'.index'));
	    Breadcrumb::addBreadcrumb(trans("messages.$this->model.breadcrumbs_edit"),'');
		$breadcrumbs 	= 	Breadcrumb::generate();
		### breadcrumbs End ###
		
		$multiLanguage			=	array();
		if(!empty($modelDescriptions)){
			foreach($modelDescriptions as $modelDescription) {
				$multiLanguage[$modelDescription->language_id]['client_name']			=	$modelDescription->client_name;
				$multiLanguage[$modelDescription->language_id]['client_designation']	=	$modelDescription->client_designation;
				$multiLanguage[$modelDescription->language_id]['title']					=	$modelDescription->title;
				$multiLanguage[$modelDescription->language_id]['comment']				=	$modelDescription->comment;
				$multiLanguage[$modelDescription->language_id]['image']					=	$model->image;				
			}
		}
		
		$languages				=	Language::where('is_active', '=',ACTIVE)->get(array('title','id'));
		$language_code			=	Config::get('default_language.language_code');
		
		return  View::make("admin.$this->model.edit",compact('breadcrumbs','languages','language_code','model','multiLanguage'));
	}// end editTestimonial()

	 /**
	 * Function for update Testimonial 
	 *
	 * @param $Id ad id of Testimonial 
	 *
	 * @return redirect page. 
	 */
	function updateTestimonial($modelId){
		$model 					= 	Testimonial:: findOrFail($modelId);
		$this_data				=	Input::all();
		$language_code			=	Config::get('default_language.language_code');
		$dafaultLanguageArray	=	$this_data['data'][$language_code];

		$validator = Validator::make(
			array(
				'client_name' 			=> $dafaultLanguageArray['client_name'],
				'image'					=> $dafaultLanguageArray['image'],
				'client_designation' 	=> $dafaultLanguageArray['client_designation'],
				'title' 				=> $dafaultLanguageArray['title'],
				'comment' 				=> $dafaultLanguageArray['comment'],
			),
			array(
				'client_name' 			=> 'required',
				'image'					=> 'image|mimes:jpeg,png,bmp,gif,svg',
				'client_designation' 	=> 'required',
				'title' 				=> 'required',
				'comment' 				=> 'required',
			)
		);
		
		if ($validator->fails())
		{	return Redirect::back()
				->withErrors($validator)->withInput();
		}else{
			$model->client_name    		= $dafaultLanguageArray['client_name'];
			$model->client_designation  = $dafaultLanguageArray['client_designation'];
			$model->title   			= $dafaultLanguageArray['title'];
			$model->comment   			= $dafaultLanguageArray['comment'];

			if($dafaultLanguageArray['image']) {
				$extension 	=	 $dafaultLanguageArray['image']->getClientOriginalExtension();
				$fileName	=	time().'-Testimonial-image.'.$extension;
				if($dafaultLanguageArray['image']->move(TESTIMONIAL_IMAGE_ROOT_PATH, $fileName)){
						$model->image			=	$fileName;
				}
				$image 			=	Testimonial::where('id',$modelId)->pluck('image');
				@unlink(TESTIMONIAL_IMAGE_ROOT_PATH.$image);
			}

			$model->save();
			$modelId	=	$model->id;
			TestimonialDescription::where('parent_id',$model->id)->delete();
			foreach ($this_data['data'] as $language_id => $descriptionResult) {
				$modelDescription						=  new TestimonialDescription();
				$modelDescription->language_id			=	$language_id;
				$modelDescription->parent_id			=	$modelId;
				$modelDescription->client_name			=	$descriptionResult['client_name'];
				$modelDescription->client_designation	=	$descriptionResult['client_designation'];
				$modelDescription->title				=	$descriptionResult['title'];
				$modelDescription->comment				=	$descriptionResult['comment'];

				if($model->image) {
					$modelDescription->image =	$model->image;
				}

				$modelDescription->save();					
			}
			Session::flash('flash_notice',  trans("messages.$this->model.updated_message"));
			return Redirect::route("$this->model.index");
		}
	
	}// end updateTestimonial()

	 /**
	 * Function for update Testimonial  status
	 *
	 * @param $Id as id of Testimonial 
	 * @param $Status as status of Testimonial 
	 *
	 * @return redirect page. 
	 */	
	public function updateTestimonialStatus($modelId = 0, $modelStatus = 0){
		Testimonial::where('id', '=', $modelId)->update(array('status' => $modelStatus));
		TestimonialDescription::where('parent_id', '=', $modelId)->update(array('status' => $modelStatus));
		Session::flash('flash_notice', trans("messages.$this->model.status_updated_message")); 
		return Redirect::route("$this->model.index");
	}// end updateTestimonialStatus()


	
/**
 * Function for delete Testimonial 
 *
 * @param $Id as id of Testimonial 
 *
 * @return redirect page. 
 */	
 
	public function deleteTestimonial($modelId = 0){
		if($modelId){
			$model = Testimonial::findorFail($modelId);
			$model->description()->delete();
			$model->delete();
			Session::flash('flash_notice',trans("messages.$this->model.deleted_message")); 
		}
		return Redirect::route("$this->model.index");
	}// end deleteTestimonial()
	
}// end TestimonialController class
