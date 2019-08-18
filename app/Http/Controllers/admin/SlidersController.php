<?php
namespace App\Http\Controllers\admin;
use App\Http\Controllers\BaseController;
use App\Model\Slider;
use App\Model\Language;
use App\Model\SliderDescription;
use mjanssen\BreadcrumbsBundle\Breadcrumbs as Breadcrumb;
use Auth,Blade,Config,Cache,Cookie,DB,File,Hash,Input,Mail,mongoDate,Redirect,Request,Response,Session,URL,View,Validator;
/**
 * Sliders Controller
 *
 * Add your methods in the class below
 *
 * This file will render views from views/admin/slider
 */

class SlidersController extends BaseController {

	 /**
	 * Function for display list of all images for slider
	 *
	 * @param null
	 *
	 * @return view page. 
	 */
	 
	public $model	=	'Slider';
	
	public function __construct() {
		View::share('modelName',$this->model);
	}
 
	public function listSlider(){
		### Breadcrumb Start ###
		Breadcrumb::addBreadcrumb(trans("messages.global.breadcrumbs_dashboard"),route('admin_dashboard'));
		Breadcrumb::addBreadcrumb(trans("messages.$this->model.breadcrumbs_module"),route($this->model.'.index'));
		$breadcrumbs 	= 	Breadcrumb::generate();
		### Breadcrumb End ###
		
		$DB = Slider::query();
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
		
		$sortBy 	= 	(Input::get('sortBy')) ? Input::get('sortBy') : 'updated_at';
	    $order  	= 	(Input::get('order')) ? Input::get('order')   : 'DESC';
	    $model 	= 	$DB->orderBy($sortBy,$order)->paginate(Config::get("Reading.records_per_page"));
	    	
		return  View::make("admin.$this->model.index",compact('breadcrumbs','model','sortBy','order','searchVariable'));
		
	} // end listSlider()

	 /**
	 * Function for display page for add new image on slider
	 *
	 * @param null
	 *
	 * @return view page. 
	 */
 
	public function addSlider(){
		Breadcrumb::addBreadcrumb(trans("messages.global.breadcrumbs_dashboard"),route('admin_dashboard'));
		Breadcrumb::addBreadcrumb(trans("messages.$this->model.breadcrumbs_module"),route($this->model.'.index'));;
		Breadcrumb::addBreadcrumb(trans("messages.$this->model.breadcrumbs_add"),'');
		$breadcrumbs 	= 	Breadcrumb::generate();
		
		$languages	=	Language::where('active', '=', '1')->get(array('title','id'));
		$language_code	=	Config::get('default_language.language_code');
		return  View::make("admin.$this->model.add",compact('languages' ,'language_code','breadcrumbs'));
	} // end addSlider()

	 /**
	 * Function for save images and description  for slider
	 *
	 * @param null
	 *
	 * @return redirect page. 
	 */
	public function saveSlider(){
		$thisData				=	Input::all();
		$language_code			=	Config::get('default_language.language_code');
		$dafaultLanguage		=	$thisData['data'][$language_code];
		
		$validator = Validator::make(
			array(
				'image' 			=> Input::file('image'),
				'order' 			=> Input::get('order'),
				'body' 				=> $dafaultLanguage['body']
				),
			array(
				'image' 		=> 'required|image',
				'order' 		=> 'required|numeric|unique:sliders,slider_order',
				'body'			=> 'required',
			)
		);
	
		if ($validator->fails()){	
			return Redirect::back()
				->withErrors($validator)->withInput();
		}else{
			$model = new Slider;
			if(Input::hasFile('image')){
				$extension 	=	 Input::file('image')->getClientOriginalExtension();
				$fileName	=	time().'-slider-image.'.$extension;
				if(Input::file('image')->move(SLIDER_ROOT_PATH, $fileName)){
					$model->slider_image    			=  $fileName;
				}
			}
			$model->slider_text   			= $dafaultLanguage['body'];
			$model->slider_order    		=  Input::get('order');
			$model->is_active    			=  1;
			$model->save();
			$modelId						=	$model->id;
			
			foreach ($thisData['data'] as $language_id => $descriptionResult){
				$modelDescription					=  new SliderDescription();
				$modelDescription->language_id		=	$language_id;
				$modelDescription->parent_id		=	$modelId;
				$modelDescription->slider_text		=	$descriptionResult['body'];	
				$modelDescription->save();
			}
			Session::flash('flash_notice',  trans("messages.$this->model.added_message"));  
			return Redirect::route("$this->model.index");
		}
	} // end saveSlider()

	 /**
	 * Function for display page for edit image and description for slider
	 *
	 * @param $sliderId id  of image for slider
	 *
	 * @return view page. 
	 */
	public function editSlider($modelId = 0){
		$model				=	Slider::findorFail($modelId);
		$modelDescriptions	=	SliderDescription::where('parent_id','=',$modelId)->get();
		
		// Breadcrums   is  added   here dynamically
		Breadcrumb::addBreadcrumb(trans("messages.global.breadcrumbs_dashboard"),route('admin_dashboard'));
		Breadcrumb::addBreadcrumb(trans("messages.$this->model.breadcrumbs_module"),route($this->model.'.index'));
	    Breadcrumb::addBreadcrumb(trans("messages.$this->model.breadcrumbs_edit"),'');
		$breadcrumbs 	= 	Breadcrumb::generate();
		### breadcrumbs End ###
		
		$multiLanguage		=	array();
		if(!empty($modelDescriptions)){
			foreach($modelDescriptions as $modelDescription) {
				$multiLanguage[$modelDescription->language_id]['description']	=	$modelDescription->description;
			}
		}
		$languages				=	Language::where('is_active', '=', ACTIVE)->get(array('title','id'));
		$language_code			=	Config::get('default_language.language_code');
		return  View::make("admin.$this->model.edit",compact('breadcrumbs','languages','language_code','model','multiLanguage'));
	} // end editSlider()
	
	 /**
	 * Function for save updated image and description for slider
	 *
	 * @param null
	 *
	 * @return redirect page. 
	 */
	public function updateSlider(){
		$modelId				=	Input::get('id');
		$model 					=  Slider::findOrFail($modelId);
		$thisData				=	Input::all();
		$language_code			=	Config::get('default_language.language_code');
		$dafaultLanguage		=	$thisData['data'][$language_code];
		
		$validator = Validator::make(
			array(
				'image' 			=> Input::file('image'),
				'order' 			=> Input::get('order'),
				'body' 				=> $dafaultLanguage['body']
				),
			array(
				'image' 		=> 'mimes|image',
				'order' 		=> 'required|numeric|unique:sliders,slider_order,'.$sliderId,
				'body'			=> 'required',
			)
		);
	
		if ($validator->fails()){
			return Redirect::back()
				->withErrors($validator)->withInput();
		}else{
			if(Input::hasFile('image')){
				$extension 	=	 Input::file('image')->getClientOriginalExtension();
				$fileName	=	time().'-slider-image.'.$extension;
				if(Input::file('image')->move(SLIDER_ROOT_PATH, $fileName)){
					$slider->slider_image    			=  $fileName;
				}
			}
			$model->slider_text   			= $dafaultLanguage['body'];
			$model->slider_order    		=  Input::get('order');
			$model->save();
			
			SliderDescription::where('parent_id', '=', $modelId)->delete();
			
			foreach ($thisData['data'] as $language_id => $slider){
				$modelDescription					=  new SliderDescription();
				$modelDescription->language_id		=	$language_id;
				$modelDescription->parent_id		=	$modelId;
				$modelDescription->slider_text		=	$descriptionResult['body'];	
				$modelDescription->save();
			}
			
			Session::flash('flash_notice',  trans("messages.$this->model.updated_message"));
			return Redirect::route("$this->model.index");
		}
	}// end updateSlider()
	
	 /**
	 * Function for display all clients
	 *
	 * @param $sliderId as id of image on slider
	 *
	 * @return redirect page. 
	 */
 
	public function deleteSlider($modelId = 0){
		if($modelId){
			$model = Slider::findorFail($modelId);
			$model->description()->delete();
			$model->delete();
			Session::flash('flash_notice',trans("messages.$this->model.deleted_message")); 
		}
		return Redirect::route("$this->model.index");
	} // end deleteSlider()
	
	 /**
	 * Function for change status of slider image
	 *
	 * @param $sliderId as id of image on slider
	 * @param $sliderStatus as status of image for slider
	 *
	 * @return redirect page. 
	 */	
	public function updateSliderStatus($sliderId = 0, $sliderStatus = 0){
		Slider::where('id', '=', $modelId)->update(array('status' => $modelStatus));
		Session::flash('flash_notice', trans("messages.$this->model.status_updated_message")); 
		return Redirect::route("$this->model.index");
	} // end updateSliderStatus()
	
	/**
	 * Function for delete,active,deactive slider 
	 *
	 * @param $userId as id of users
	 *
	 * @return redirect page. 
	 */
 		
	public function performMultipleAction(){
		if(Request::ajax()){
			$actionType = ((Input::get('type'))) ? Input::get('type') : '';
			
			if(!empty($actionType) && !empty(Input::get('ids'))){
				if($actionType	==	'active'){
					Slider::whereIn('id', Input::get('ids'))->update(array('is_active' => 1));
					
				}
				elseif($actionType	==	'inactive'){
					Slider::whereIn('id', Input::get('ids'))->update(array('is_active' => 0));
					
				}
				elseif($actionType	==	'delete'){
					Slider::whereIn('id', Input::get('ids'))->delete();
					
				}
				
				Session::flash('success', trans("messages.global.action_performed_message")); 
				
			}
		}
	}//end performMultipleAction()
	

	/**
	 * Function for update the orderby field
	 *
	 * @param null
	 *
	 * @return view page. 
	 */
	
	public function changeSliderOrder()
	{
		$order_by			=	Input::get('order_by'); 
		$id					=	Input::get('current_id');
		
		$sliderOrder		=	Slider::where('id',$id)->pluck('slider_order');
		$validator  = 	Validator::make(
					Input::all(),
					array(
						'order_by' 		=> 'required|numeric|unique:sliders,slider_order,'.$id,
					)
		);
		
		$message	= $validator->messages()->toArray();
		
		if ($validator->fails())
		{	
			$response	=	array(
					'success' => false,
					'message'=> $message['order_by'],	
					
			);
			return Response::json($response); die;			
		}else{
			Slider::where('id',$id)->update(
						array(
							'slider_order' => $order_by,
						)
					);
					
			$response	=	array(
					'success' => 1,
					'order_by' => $order_by,
			);
			return Response::json($response); die;		
		}
	}//end changeSliderOrder()
	
}// end SlidersController class
