<?php
namespace App\Http\Controllers\admin;
use App\Http\Controllers\BaseController;
use App\Model\News;
use App\Model\User;
use App\Model\Language;
use App\Model\NewsDescription;
use mjanssen\BreadcrumbsBundle\Breadcrumbs as Breadcrumb;
use Auth,Blade,Config,Cache,Cookie,DB,File,Hash,Input,Mail,mongoDate,Redirect,Request,Response,Session,URL,View,Validator;

/**
 * News Controller
 *
 * Add your methods in the class below
 *
 * This file will render views from views/admin/News
 */

class NewsController extends BaseController {

	 /**
	 * Function for display list of all  News
	 *
	 * @param null
	 *
	 * @return view page. 
	 */
	 
	public $model	=	'News';
	
	public function __construct() {
		View::share('modelName',$this->model);
	}
 
	public function listNews(){
		### Breadcrumb Start ###
		Breadcrumb::addBreadcrumb(trans("messages.global.breadcrumbs_dashboard"),route('admin_dashboard'));
		Breadcrumb::addBreadcrumb(trans("messages.$this->model.breadcrumbs_module"),route($this->model.'.index'));
		$breadcrumbs 	= 	Breadcrumb::generate();
		### Breadcrumb End ###
		
		$DB 				= News::query();
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
	} // end listNews()

	 /**
	 * Function for display page for add new News
	 *
	 * @param null
	 *
	 * @return view page. 
	 */
 
	public function addNews(){
		Breadcrumb::addBreadcrumb(trans("messages.global.breadcrumbs_dashboard"),route('admin_dashboard'));
		Breadcrumb::addBreadcrumb(trans("messages.$this->model.breadcrumbs_module"),route($this->model.'.index'));
	    Breadcrumb::addBreadcrumb(trans("messages.$this->model.breadcrumbs_add"),'');
		$breadcrumbs 	= 	Breadcrumb::generate();
		
		$languages			=	Language::where('is_active','=',ACTIVE)->get(array('title','id'));
		$language_code		=	Config::get('default_language.language_code');
		$authorList 		= User::where('active','1')->select('id','first_name','last_name')->get();
		return  View::make("admin.$this->model.add",compact('breadcrumbs','language_code','languages','authorList')); 
	} // end addNews()

	 /**
	 * Function for save images and description  for News
	 *
	 * @param null
	 *
	 * @return redirect page. 
	 */
	 
	public function saveNews(){
	//	echo "<pre>";
	//	print_r(Input::all());
	//	die;
		$thisData				=	Input::all();
		$language_code			=	Config::get('default_language.language_code');
		$dafaultLanguage		=	$thisData['data'][$language_code];
		
		$validator = Validator::make(
			array(
				'image' 			=> Input::file('image'),
				'name' 				=> $dafaultLanguage["'name'"],
				'description' 		=> $dafaultLanguage["'description'"],
				),
			array(
				'image' 			=> 'required|image',
				'name' 				=> 'required',
				'description'		=> 'required',
			)
		);
	
		if ($validator->fails()){
			return Redirect::back()
				->withErrors($validator)->withInput();	
		}else{
			$model = new News;
			if(Input::hasFile('image')){
				$extension 	=	 Input::file('image')->getClientOriginalExtension();
				$fileName	=	 time()."-$this->model-image".$extension;
				if(Input::file('image')->move(NEWS_IMAGE_ROOT_PATH, $fileName)){
					$model->image    			=  $fileName;
				}
			}
			$model->name    					=  $dafaultLanguage["'name'"];
			$model->author_id    				=  $thisData["author_id"];
			$model->description   				=  $dafaultLanguage["'description'"];
			$model->slug   						=  $this->getSlug($dafaultLanguage["'name'"],'slug',"News");
			$model->is_active    					=  ACTIVE;
			$model->save();
			$modelId							=	$model->id;
			foreach ($thisData['data'] as $language_id => $result){
				NewsDescription::insert(
					array(
						'language_id'		=>	$language_id,
						'parent_id'			=>	$modelId,
						'name'				=>	$result["'name'"],
						'description'		=>	$result["'description'"]
					)
				);
			}
			Session::flash('flash_notice',  trans("messages.$this->model.added_message")); 
			return Redirect::route("$this->model.index");
		}
	} // end saveNews()

	 /**
	 * Function for display page for edit image and description for News
	 *
	 * @param $newsId id  of image for News
	 *
	 * @return view page. 
	 */
	public function editNews($modelId = 0){
		$model				=	News::where('id', '=', $modelId)->firstOrFail();

		$NewsDescription	=	NewsDescription::where('parent_id', '=',$modelId)->get();

		Breadcrumb::addBreadcrumb(trans("messages.global.breadcrumbs_dashboard"),route('admin_dashboard'));
		Breadcrumb::addBreadcrumb(trans("messages.$this->model.breadcrumbs_module"),route($this->model.'.index'));
	    Breadcrumb::addBreadcrumb(trans("messages.$this->model.breadcrumbs_edit"),'');
		$breadcrumbs 		= 	Breadcrumb::generate();
		
		$multiLanguage		=	array();
		if(!empty($NewsDescription)){
			foreach($NewsDescription as $description) {
				$multiLanguage[$description->language_id]['name']			=	$description->name;						
				$multiLanguage[$description->language_id]['description']	=	$description->description;						
			}
		}
		$languages		=	Language::where('is_active', '=',ACTIVE)->get(array('title','id'));
		$language_code	=	Config::get('default_language.language_code');
		$authorList 		= User::where('active','1')->select('id','first_name','last_name')->get();

		return  View::make("admin.$this->model.edit",compact('model','breadcrumbs','languages','language_code','multiLanguage','authorList')); 
	} // end editNews()
	
	 /**
	 * Function for save updated image and description for News
	 *
	 * @param null
	 *
	 * @return redirect page. 
	 */
	public function updateNews(){
		$modelId				=	Input::get('id');
		$model					= 	News::find($modelId);
		$thisData				=	Input::all();
		
		$language_code			=	Config::get('default_language.language_code');
		$dafaultLanguage		=	$thisData['data'][$language_code];
		
		$validator = Validator::make(
			array(
				'image' 			=> Input::file('image'),
				'name' 				=> $dafaultLanguage["'name'"],
				'description' 		=> $dafaultLanguage["'description'"]
				),
			array(
				'image' 			=> 'image',
				'name' 				=> 'required',
				'description'		=> 'required',
			)
		);
	
		if ($validator->fails()){
			return Redirect::back()
				->withErrors($validator)->withInput();	
		}else{
			if(Input::hasFile('image')){
				$oldImage = $model->image;
				$extension 	=	 Input::file('image')->getClientOriginalExtension();
				$fileName	=	 time()."-$this->model.".$extension;
				if(Input::file('image')->move(NEWS_IMAGE_ROOT_PATH, $fileName)){
					$model->image    			=  $fileName;
				}
				if(File::exists(NEWS_IMAGE_ROOT_PATH.$model->image)){
					@unlink(NEWS_IMAGE_ROOT_PATH.$model->oldImage);
				}
			}
			
			$model->name    					=  $dafaultLanguage["'name'"];
			$model->author_id    				=  $thisData["author_id"];
			$model->description   				=  $dafaultLanguage["'description'"];
			$model->is_active    					=  ACTIVE;
			$model->save();
			

			NewsDescription::where('parent_id',$modelId)->delete();
			
			foreach ($thisData['data'] as $language_id => $result){
				NewsDescription::insert(
					array(
						'language_id'		=>	$language_id,
						'parent_id'			=>	$modelId,
						'name'				=>	$result["'name'"],
						'description'		=>	$result["'description'"]
					)
				);
			}
			Session::flash('flash_notice',  trans("messages.$this->model.updated_message")); 
			return Redirect::route("$this->model.index");
		}
	}// end updateNews()
	
	 /**
	 * Function for delete news
	 *
	 * @param $newsId as id of News
	 *
	 * @return redirect page. 
	 */
 
	public function deleteNews($modelId = 0){
			$model = News::findorFail($modelId);
			if(File::exists(NEWS_IMAGE_ROOT_PATH.$model->image)){
				@unlink(NEWS_IMAGE_ROOT_PATH.$model->image);
			}
			$model->newsDescription()->delete();
			$model->delete();
			Session::flash('flash_notice',trans("messages.$this->model.deleted_message")); 
		return Redirect::route("$this->model.index");
	} // end deleteNews()
	
	 /**
	 * Function for change is_active of News
	 *
	 * @param $newsId as id of News
	 * @param $newsis_active as is_active of News
	 *
	 * @return redirect page. 
	 */	
	 
	public function updateNewsStatus($modelId = 0, $modelStatus = 0){
		News::where('id', '=', $modelId)->update(array('is_active' => $modelStatus));
		Session::flash('flash_notice', trans("messages.$this->model.status_updated_message")); 
		return Redirect::route("$this->model.index");
	} // end updateNewsStatus()
	
	/**
	 * Function for delete,active,deactive News 
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
					News::whereIn('id', Input::get('ids'))->update(array('is_active' => ACTIVE));
				}
				elseif($actionType	==	'inactive'){
					News::whereIn('id', Input::get('ids'))->update(array('is_active' => 0));
				}
				elseif($actionType	==	'delete'){
					News::whereIn('id', Input::get('ids'))->delete();
				}
				Session::flash('success', trans("messages.global.action_performed_message")); 
			}
		}
	}//end performMultipleAction()

	public function markHighlight($modelId = 0){
		News::where('is_highlight', '=', 1)->update(array('is_highlight' => 0));
		News::where('id', '=', $modelId)->update(array('is_highlight' => 1));
		Session::flash('success', trans("messages.news_highlight_success"));
		return Redirect::route("$this->model.index");
	}

	public function commentNews($modelId = 0){

		Breadcrumb::addBreadcrumb(trans("messages.global.breadcrumbs_dashboard"),route('admin_dashboard'));
		Breadcrumb::addBreadcrumb(trans("messages.$this->model.breadcrumbs_module_comment"),route($this->model.'.index'));
		$breadcrumbs 	= 	Breadcrumb::generate();
		### Breadcrumb End ###
		
		$model	= DB::table('news_comments')->where('news_id',$modelId)->orderBy('id','desc')->paginate(10);

		return  View::make("admin.$this->model.comment", compact('breadcrumbs','model'));
		
	}

	public function deleteComment($modelId = 0){

			DB::delete('delete from news_comments where id = ?',[$modelId]);
			
			Session::flash('flash_notice',trans("messages.$this->model.deleted_message")); 
		return Redirect::route("$this->model.index");
	}
} // end NewsController class