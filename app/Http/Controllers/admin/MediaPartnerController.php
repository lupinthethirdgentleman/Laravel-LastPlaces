<?php
namespace App\Http\Controllers\admin;
use App\Http\Controllers\BaseController;
use App\Model\Media;
use App\Model\User;
use mjanssen\BreadcrumbsBundle\Breadcrumbs as Breadcrumb;
use Auth,Blade,Config,Cache,Cookie,DB,File,Hash,Input,Mail,mongoDate,Redirect,Request,Response,Session,URL,View,Validator;

/**
 * MediaPartnerController
 *
 * Add your methods in the class below
 *
 * This file will render views from views/admin/media
 */
 
class MediaPartnerController extends BaseController {

	public $model	=	'Media';
	
	public function __construct() {
		View::share('modelName',$this->model);
	}

	 /**
	 * Function for display list of all media
	 *
	 * @param null
	 *
	 * @return view page. 
	 */

	public function listPage(){
		### breadcrumbs Start ###
		Breadcrumb::addBreadcrumb(trans("messages.global.breadcrumbs_dashboard"),route('admin_dashboard'));
		Breadcrumb::addBreadcrumb(trans("messages.$this->model.breadcrumbs_module"),route($this->model.'.index'));
		$breadcrumbs 	= 	Breadcrumb::generate();
		### breadcrumbs End ###
		
		$DB 	 =  Media::query();
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
		$model 		= 	$DB->orderBy($sortBy, $order)->paginate(Config::get("Reading.records_per_page"));						
		return  View::make("admin.$this->model.index",compact('sortBy','order','breadcrumbs','model','searchVariable'));
	}//emd listPage()
	
	 /**
	 * Function for display add  media page
	 *
	 * @param null
	 *
	 * @return view page. 
	 */
	public function addMedia(){
		### breadcrumbs Start ###
		Breadcrumb::addBreadcrumb(trans("messages.global.breadcrumbs_dashboard"),route('admin_dashboard'));
		Breadcrumb::addBreadcrumb(trans("messages.$this->model.breadcrumbs_module"),route($this->model.'.index'));;
		Breadcrumb::addBreadcrumb(trans("messages.$this->model.breadcrumbs_add"),'');
		$breadcrumbs 	= 	Breadcrumb::generate();
		### breadcrumbs End ###
		return  View::make("admin.$this->model.add",compact('breadcrumbs'));
	}//end addMedia
	
	 /**
	 * Function for save added media 
	 *
	 * @param null
	 *
	 * @return view page. 
	 */
	public function saveMediaPartner(){
		$formData	=	Input::all();
		if(!empty($formData)){
			$validator = Validator::make(
				Input::all(),
				array(
					'title'			=> 'required',
					'logo' 			=> 'required|image',
					'order' 		=> 'required|numeric|unique:media_partners,order_by',
				)
			);
		}
		if ($validator->fails()){
			 return Redirect::back()->withErrors($validator)->withInput();
		}else{
			$model			=	new Media;
			if(Input::hasFile('logo')){
				$extension 	=	 Input::file('logo')->getClientOriginalExtension();
				$fileName	=	 time()."-$this->model-image".$extension;
				if(Input::file('logo')->move(MEDIA_IMAGE_ROOT_PATH, $fileName)){
					$model->logo    			=  $fileName;
				}
			}
			$model->title		=	Input::get('title');
			$model->order_by	=	Input::get('order');
			$model->save();
			Session::flash('flash_notice',  trans("messages.$this->model.added_message"));  
			return Redirect::route("$this->model.index");
		}
	}//ens saveMediaPartner()
	
	/**
	 * Function for update media status
	 *
	 * @param $Id as media id 
	 * $status
	 *
	 * @return view page. 
	 */
	public function updateMediaStatus($modelId = 0,$modelStatus=0){	
		Media::where('id', '=', $modelId)->update(array('status' => $modelStatus));
		Session::flash('flash_notice', trans("messages.$this->model.status_updated_message")); 
		return Redirect::route("$this->model.index");
	}//end updateMediaStatus()
	
	 /**
	 * Function for delete media
	 *
	 * @param $Id as media id
	 *
	 * @return view page. 
	 */
	public function deleteMedia($modelId = 0){			
		if($modelId){
			$model = Media::findorFail($modelId);
			if(File::exists(MEDIA_IMAGE_ROOT_PATH.$model->logo)){
				@unlink(MEDIA_IMAGE_ROOT_PATH.$model->logo);
			}
			$model->delete();
			Session::flash('flash_notice',trans("messages.$this->model.deleted_message")); 
		}
		return Redirect::route("$this->model.index");
	}//end deleteMedia()

	 /**
	 * Function for update the orderby field
	 *
	 * @param null
	 *
	 * @return view page. 
	 */
	public function changeOrderBy(){
		$order_by			=	Input::get('order_by'); 
		$id					=	Input::get('current_id');
		$validator  = 	Validator::make(
			Input::all(),
			array(
				'order_by' 		=> 'required|numeric|unique:media_partners,order_by,'.$id,
				)
		);
		
		$message	= $validator->messages()->toArray();
		if ($validator->fails()){	
			$response	=	array(
				'success' => false,
				'message'=> $message['order_by'],
			);
			return Response::json($response); die;			
		}else{
			Media::where('id',$id)->update(
						array(
							'order_by' => $order_by,
						)
					);
			$response	=	array(
							'success' => 1,
							'order_by' => $order_by,
						);
			return Response::json($response); die;	
		}
	}//end changeOrderBy()
	
	/**
	 * Function for delete,activate,deactivate media
	 *
	 * @param null
	 *
	 * @return view page. 
	 */
	public function performMultipleAction(){
		if(Request::ajax()){
			$actionType = ((Input::get('type'))) ? Input::get('type') : '';
			if(!empty($actionType) && !empty(Input::get('ids'))){
				if($actionType	==	'active'){
					Media::whereIn('id', Input::get('ids'))->update(array('status' => ACTIVE));
				}
				elseif($actionType	==	'inactive'){
					Media::whereIn('id', Input::get('ids'))->update(array('status' =>INACTIVE));
				}
				elseif($actionType	==	'delete'){
					Media::whereIn('id', Input::get('ids'))->delete();
				}
				Session::flash('success', trans("messages.global.action_performed_message")); 
			}
		}
	}//end performMultipleAction()
	
}//end MediaPartnerController
