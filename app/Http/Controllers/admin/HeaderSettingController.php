<?php
namespace App\Http\Controllers\admin;
use App\Http\Controllers\BaseController;
use App\Model\Setting;
use App\Model\User;
use App\Model\Language;
use App\Model\BlogDescription;
use mjanssen\BreadcrumbsBundle\Breadcrumbs as Breadcrumb;
use Auth,Blade,Config,Cache,Cookie,DB,File,Hash,Input,Mail,mongoDate,Redirect,Request,Response,Session,URL,View,Validator;

/**
 * Blogs Controller
 *
 * Add your methods in the class below
 *
 * This file will render views from views/admin/Blog
 */

class HeaderSettingController extends BaseController {

	 /**
	 * Function for display list of all  HeaderSetting
	 *
	 * @param null
	 *
	 * @return view page. 
	 */
	 
	public $model	=	'HeaderSetting';
	
	public function __construct() {
		View::share('modelName',$this->model);
	}
 
	public function listHeaderSetting(){
		### Breadcrumb Start ###
		Breadcrumb::addBreadcrumb(trans("messages.global.breadcrumbs_dashboard"),route('admin_dashboard'));
		Breadcrumb::addBreadcrumb(trans("messages.$this->model.breadcrumbs_module"),route($this->model.'.index'));
		$breadcrumbs 	= 	Breadcrumb::generate();
		### Breadcrumb End ###
		
		$DB 				= Setting::query();
		$searchVariable		=	array(); 
		$inputGet			=	Input::get();
		
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
		return  View::make("admin.$this->model.index", compact('breadcrumbs','model','sortBy','order','searchVariable'));
	} // end listBlog()

	 /**
	 * Function for display page for add new HeaderSetting
	 *
	 * @param null
	 *
	 * @return view page. 
	 */
 
	/**
	 * Function for display page for edit image and description for Blog
	 *
	 * @param $blogId id  of image for Blog
	 *
	 * @return view page. 
	 */
	public function editHeaderSetting($modelId = 0){
		$model				=	Setting::where('id', '=', $modelId)->firstOrFail();
		//echo "<pre>";print_r($model);die;
		Breadcrumb::addBreadcrumb(trans("messages.global.breadcrumbs_dashboard"),route('admin_dashboard'));
	    Breadcrumb::addBreadcrumb(trans("messages.$this->model.breadcrumbs_edit"),'');
		$breadcrumbs 		= 	Breadcrumb::generate();
		
		// $header_list = DB::table('settings')->where('id', $modelId)->get();
		

		return  View::make("admin.$this->model.edit",compact('model','breadcrumbs')); 
	} // end editBlog()
	
	 /**
	 * Function for save updated image and description for Blog
	 *
	 * @param null
	 *
	 * @return redirect page. 
	 */
	public function updateHeaderSetting(){
		$modelId				=	Input::get('id');
		$model					= 	Setting::find($modelId);
		$thisData				=	Input::all();
		
		$validator = Validator::make(
			array(
				'image' 			=> Input::file('image'),
				),
			array(
				'image' 			=> 'image',
			)
		);
	
		if ($validator->fails()){
			return Redirect::back()
				->withErrors($validator)->withInput();	
		}else{
			if(Input::hasFile('image')){
				$oldImage = $model->value;
				$extension 	=	 Input::file('image')->getClientOriginalExtension();
				$fileName	=	 time()."-$this->model.".$extension;
				if(Input::file('image')->move(HEADER_IMAGE_ROOT_PATH, $fileName)){
					$model->value		=  $fileName;
				}
				DB::table('settings')
            			->where('id', $modelId)
            			->update(['value' => $fileName]);
				if(File::exists(HEADER_IMAGE_ROOT_PATH.$model->value)){
					@unlink(HEADER_IMAGE_ROOT_PATH.$model->oldImage);
				}
			}
			Session::flash('flash_notice',  trans("messages.$this->model.updated_message")); 
			return Redirect::route("$this->model.edit","392");
		}
	}// end updateBlog()
	
	 /**
	 * Function for delete blog
	 *
	 * @param $blogId as id of Blog
	 *
	 * @return redirect page. 
	 */
	
}// end HeaderSettingController class
