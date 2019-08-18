<?php
namespace App\Http\Controllers\admin;
use App\Http\Controllers\BaseController;
use App\Model\BlockDescription;
use App\Model\Block;
use App\Model\Language;
use mjanssen\BreadcrumbsBundle\Breadcrumbs as Breadcrumb;
use Auth,Blade,Config,Cache,Cookie,DB,File,Hash,Input,Mail,mongoDate,Redirect,Request,Response,Session,URL,View,Validator;

/**
 * BlockController Controller
 *
 * Add your methods in the class below
 *
 */
 
class BlockController extends BaseController {

	public $model	=	'Block';
	
	public function __construct() {
		View::share('modelName',$this->model);
	}
	
	/**
	 * Function for display all Block 
	 *
	 * @param null
	 *
	 * @return view page. 
	 */
	public function listBlock(){
		### breadcrumbs Start ###
		// Breadcrums   is  added   here dynamically
		Breadcrumb::addBreadcrumb(trans("messages.global.breadcrumbs_dashboard"),URL::to('admin/dashboard'));
		Breadcrumb::addBreadcrumb(trans("messages.$this->model.breadcrumbs_module"),route($this->model.'.index'));
		$breadcrumbs 	= 	Breadcrumb::generate();
		### breadcrumbs End ###
		
		$DB					=	Block::query();
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
		$sortBy = (Input::get('sortBy')) ? Input::get('sortBy') : 'updated_at';
	    $order  = (Input::get('order')) ? Input::get('order')   : 'DESC';
		$model = $DB->orderBy($sortBy, $order)->paginate(Config::get("Reading.records_per_page"));
		
		return  View::make("admin.$this->model.index",compact('breadcrumbs','model','searchVariable','sortBy','order'));
	}// end listBlock()

	 /**
	 * Function for display page  for add new Block  
	 *
	 * @param null
	 *
	 * @return view page. 
	 */
	 
	public function addBlock(){
		### breadcrumbs Start ###
		// Breadcrums   is  added   here dynamically
		Breadcrumb::addBreadcrumb(trans("messages.global.breadcrumbs_dashboard"),route('admin_dashboard'));
		Breadcrumb::addBreadcrumb(trans("messages.$this->model.breadcrumbs_module"),route($this->model.'.index'));;
		Breadcrumb::addBreadcrumb(trans("messages.$this->model.breadcrumbs_add"),'');
		$breadcrumbs 	= 	Breadcrumb::generate();
		### breadcrumbs End ###
		
		$languages	        =	Language::where('is_active', '=',ACTIVE)->get(array('title','id'));
		$language_code		=	Config::get('default_language.language_code');
		return  View::make("admin.$this->model.add",compact('languages' ,'language_code','breadcrumbs'));
	} //end addBlock()
	
	 /**
	 * Function for save added Block page
	 *
	 * @param null
	 *
	 * @return redirect page. 
	 */
	 
	function saveBlock(){
		$thisData				=	Input::all();
		$language_code			=	Config::get('default_language.language_code');
		$dafaultLanguageArray	=	$thisData['data'][$language_code];
		
		$validator = Validator::make(
			array(
				'page_name' 		=> Input::get('page_name'),
				'block_name' 		=> Input::get('block_name'),
				'description' 		=> $dafaultLanguageArray['description'],
			),
			array(
				'page_name' 		=> 'required',
				'block_name' 		=> 'required',
				'description' 		=> 'required',
			)
		);
		
		if ($validator->fails()){	
			return Redirect::back()
				->withErrors($validator)->withInput();
		}else{
			$model = new Block;
			$model->page_name    		= Input::get('page_name');
			$model->block_name    		= Input::get('block_name');
			$model->page    			= $this->getSlug(Input::get('page_name'), 'page',$this->model);
			$model->block    			= $this->getSlug(Input::get('block_name'),'block',$this->model);
			$model->description   		= $dafaultLanguageArray['description'];
			$model->save();
			
			$modelId	=	$model->id;
			foreach ($thisData['data'] as $language_id => $descriptionResult) {
				$modelDescription					=  new BlockDescription();
				$modelDescription->language_id		=	$language_id;
				$modelDescription->parent_id		=	$modelId;
				$modelDescription->description		=	$descriptionResult['description'];	
				$modelDescription->save();
			}
			Session::flash('flash_notice',  trans("messages.$this->model.added_message"));  
			return Redirect::route("$this->model.index");
		}
	}//end saveBlock()

	 /**
	 * Function for display page  for edit Block page
	 *
	 * @param $modelId as id of Block page
	 *
	 * @return view page. 
	 */	
	 
	public function editBlock($modelId){
		$model				=	Block::findorFail($modelId);
		$modelDescriptions	=	BlockDescription::where('parent_id','=',$modelId)->get();
		
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
	}// end editBlock()

	 /**
	 * Function for update Block 
	 *
	 * @param $modelId as id of Block 
	 *
	 * @return redirect page. 
	 */
	 
	function updateBlock($modelId){
		$this_data				=	Input::all();
		$model 					= 	Block:: findorFail($modelId);
		$activeLanguageCode		=	Config::get('default_language.language_code');
		$dafaultLanguageArray	=	$this_data['data'][$activeLanguageCode];
		
		$validator = Validator::make(
			array(
				'page_name' 		=> Input::get('page_name'),
				'block_name' 		=> Input::get('block_name'),
				'description' 		=> $dafaultLanguageArray['description'],
			),
			array(
				'page_name' 		=> 'required',
				'block_name' 		=> 'required',
				'description' 		=> 'required',
			)
		);
		
		if ($validator->fails())
		{	
			return Redirect::back()
				->withErrors($validator)->withInput();
		}else{
			$model->page_name    		= Input::get('page_name');
			$model->block_name    		= Input::get('block_name');
			$model->description   		= $dafaultLanguageArray['description'];
			$model->save();
			BlockDescription::where('parent_id',$model->id)->delete();
			foreach ($this_data['data'] as $languageId => $descriptionResult) {
				$modelDescription				=  new BlockDescription();
				$modelDescription->language_id	=	$languageId;
				$modelDescription->parent_id	=	$modelId;
				$modelDescription->description	=	$descriptionResult['description'];	
				$modelDescription->save();
			}
			Session::flash('flash_notice',  trans("messages.$this->model.updated_message"));
			return Redirect::route("$this->model.index");
		}
	}// end updateBlock()

	/**
	 * Function for update Block  status
	 *
	 * @param $modelId as id of Block 
	 * @param $modelStatus as status of Block 
	 *
	 * @return redirect page. 
	 */	
	 
	public function updateBlockStatus($modelId = 0, $modelStatus = 0){
		Block::where('id', '=', $modelId)->update(array('status' => $modelStatus));
		Session::flash('flash_notice', trans("messages.$this->model.status_updated_message")); 
		return Redirect::route("$this->model.index");
	}// end updateBlockStatus()

	/**
	 * Function for delete Block 
	 *
	 * @param $modelId as id of Block 
	 *
	 * @return redirect page. 
	 */	
	 
	public function deleteBlock($modelId = 0){
		if($modelId){
			$model = Block::findorFail($modelId);
			$model->description()->delete();
			$model->delete();
			Session::flash('flash_notice',trans("messages.$this->model.deleted_message")); 
		}
		return Redirect::route("$this->model.index");
	} // end deleteBlock()
	
	
	/**
	 * Function for delete multiple Block
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
					Blog::whereIn('id', Input::get('ids'))->update(array('status' => ACTIVE));
				}
				elseif($actionType	==	'inactive'){
					Blog::whereIn('id', Input::get('ids'))->update(array('status' => 0));
				}
				elseif($actionType	==	'delete'){
					Blog::whereIn('id', Input::get('ids'))->delete();
				}
				Session::flash('success', trans("messages.global.action_performed_message")); 
			}
		}
	}//end performMultipleAction()
	
}// end BlockController
