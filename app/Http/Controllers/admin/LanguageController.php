<?php
namespace App\Http\Controllers\admin;

use App\Http\Controllers\BaseController;
use App\Model\Setting;
use App\Model\Language;
use mjanssen\BreadcrumbsBundle\Breadcrumbs as Breadcrumb;
use Auth,Blade,Config,Cache,Cookie,DB,File,Hash,Input,Mail,mongoDate,Redirect,Request,Response,Session,URL,View,Validator;

/**
 * Language Controller
 *
 * Add your methods in the class below
 *
 * This file will render views from views/admin/language
 */
 
class LanguageController extends BaseController {

	 /**
	 * Function for display list of all languages
	 *
	 * @param null
	 *
	 * @return view page. 
	 */

	public $model	=	'Language';
	
	public function __construct() {
		View::share('modelName',$this->model);
	}
	
	public function listLanguage(){
		### breadcrumbs Start ###
		Breadcrumb::addBreadcrumb(trans("messages.global.breadcrumbs_dashboard"),route('admin_dashboard'));
		Breadcrumb::addBreadcrumb(trans("messages.$this->model.breadcrumbs_module"),route($this->model.'.index'));
		$breadcrumbs 	= 	Breadcrumb::generate();
		### breadcrumbs End ###
		
		 $DB = Language::query();
		 $searchVariable	=	array(); 
		 $inputGet		=	Input::get();
		 
		 if (Input::get() && isset($inputGet['display'])) {
			 $search = true;
			 $searchData	=	Input::get();
			 unset($searchData['display']);
			 unset($searchData['_token']);
			 foreach($searchData as $field_name => $fieldValue){
				 if(!empty($fieldValue)){
					 $DB->where("$field_name",'like','%'.$fieldValue.'%');
					 $searchVariable	=	array_merge($searchVariable,array($field_name => $fieldValue));
				 }
			}
		}
		
		$default_lang	 =  Setting::where('key','default_language.language_code')->pluck('value');
		$sortBy 		 = 	(Input::get('sortBy')) ? Input::get('sortBy') : 'created_at';
	    $order  		 = 	(Input::get('order')) ? Input::get('order')   : 'DESC';
	    
		$model 			 = 	$DB->orderBy($sortBy, $order)->paginate(Config::get("Reading.records_per_page"));
		return  View::make("admin.$this->model.index",compact('sortBy','order','breadcrumbs','model','searchVariable','default_lang'));
	}//end listLanguage()
	
	 /**
	 * Function for display add languages page
	 *
	 * @param null
	 *
	 * @return view page. 
	 */	
	public function addLanguage(){
		### breadcrumbs Start ###
		// Breadcrums   is  added   here dynamically
		Breadcrumb::addBreadcrumb(trans("messages.global.breadcrumbs_dashboard"),route('admin_dashboard'));
		Breadcrumb::addBreadcrumb(trans("messages.$this->model.breadcrumbs_module"),route($this->model.'.index'));;
		Breadcrumb::addBreadcrumb(trans("messages.$this->model.breadcrumbs_add"),'');
		$breadcrumbs 	= 	Breadcrumb::generate();
		
		### breadcrumbs End ###
		return  View::make("admin.$this->model.add",compact('breadcrumbs'));
	}//end addLanguage()
	

	 /**
	 * Function for save added languages
	 *
	 * @param null
	 *
	 * @return view page. 
	 */	
	public function saveLanguage(){
		$formData	=	Input::all();
			
			if(!empty($formData)){
				$validator = Validator::make(
					Input::all(),
					array(
						'title'				=> 'required',
						'languagecode' 		=> 'required',
						'foldercode' 		=> 'required',
						)
					);
			}
			
			if ($validator->fails()){
				 return Redirect::back()->withErrors($validator)->withInput();
			}else{
					Language::insert(
						array(
							'title' => Input::get('title'),
							'lang_code' => Input::get('languagecode'),
							'folder_code'=> Input::get('foldercode')
						)
					);
			Session::flash('flash_notice',  trans("messages.$this->model.added_message"));  
			return Redirect::route("$this->model.index");
			
		}
	}//end saveLanguage()

	 /**
	 * Function for update active/inactive status
	 *
	 * @param $Id and $status 
	 *
	 * @return view page. 
	 */	
	public function updateLanguageStatus($modelId = 0,$modelStatus=0){	
		Language::where('id', '=', $modelId)->update(array('is_active' => $modelStatus));
		Session::flash('flash_notice', trans("messages.$this->model.status_updated_message")); 
		return Redirect::route("$this->model.index");
		
	}//end updateLanguageStatus()
	
	 /**
	 * Function for delete language
	 *
	 * @param $Id as language id
	 *
	 * @return view page. 
	 */	
	public function deleteLanguage($modelId = 0){
		if($modelId){
			$model = Language::findorFail($modelId);
			$model->delete();
			Session::flash('flash_notice',trans("messages.$this->model.deleted_message")); 
		}
		return Redirect::route("$this->model.index");
	}//end deleteLanguage()
	
	 /**
	 * Function for set defaultlanguage
	 *
	 * @param $language_id as language id
	 * $name as title
	 * $folder_code as folder code 
	 *
	 * @return view page. 
	 */	
	public function updateDefaultLanguage($language_id = 0, $name = 0,$folder_code=0){
		
		//delete existing record
		 $obj	 =  Setting::where('key','default_language.language_code')->delete();
		 $obj	 =  Setting::where('key','default_language.name')->delete();
	     $obj	 =  Setting::where('key','default_language.folder_code')->delete();
	    
	    //insert into settings table
	    
	    $languageDataArray = array(
			'0'=> array(
					'key'=>'default_language.language_code',
					'value'=>$language_id,
					'title'=>'Default language for front',
					'input_type'=>'text',
					'editable'=>'1',
					'created_at'=>DB::raw('NOW()')
				),
			'1'=>array(
					'key'=>'default_language.name',
					'value'=>$name,
					'title'=>'Default language name',
					'input_type'=>'text',
					'editable'=>'1',
					'created_at'=>DB::raw('NOW()')
				),
			'2'=>array(
					'key'=>'default_language.folder_code',
					'value'=>$folder_code,
					'title'=>'Default language folder code',
					'input_type'=>'text',
					'editable'=>'1',
					'created_at'=>DB::raw('NOW()')
				)
		);
	   
	   foreach($languageDataArray as $value){
			Setting::insert($value);
		} 
		//write into file
		$this->settingFileWrite();
		Session::put('language_id',$language_id);
		Session::flash('flash_notice',trans("messages.languages.language_mark_default_message")); 	
		return Redirect::route("$this->model.index");
	}//end updateDefaultLanguage()

	/**
	 * function for write file on update and create
	 *
	 *@param null
	 *
	 * @return void
	 */	
	public function settingFileWrite() {
		$DB		=	Setting::query();
		$list	=	$DB->orderBy('key','ASC')->get(array('key','value'))->toArray();
		
        $file = SETTING_FILE_PATH;
		$settingfile = '<?php ' . "\n";
		foreach($list as $value){
			$val		  =	 str_replace('"',"'",$value['value']);
			$settingfile .=  '$app->make('.'"config"'.')->set("'.$value['key'].'", "'.$val.'");' . "\n"; 
		}
		$bytes_written = File::put($file, $settingfile);
		if ($bytes_written === false)
		{
			die("Error writing to file");
		}
	}//end settingFileWrite()
	
	/**
	 * Function for delete,active,deactive user
	 *
	 * @param $userId as id of users
	 *
	 * @return redirect page. 
	 */
	public function performMultipleAction($userId = 0){
		if(Request::ajax()){
			$actionType = ((Input::get('type'))) ? Input::get('type') : '';
			
			if(!empty($actionType) && !empty(Input::get('ids'))){
				if($actionType	==	'active'){
					Language::whereIn('id', Input::get('ids'))->update(array('active' => 1));
					
				}
				elseif($actionType	==	'inactive'){
					Language::whereIn('id', Input::get('ids'))->update(array('active' => 0));
					
				}
				elseif($actionType	==	'delete'){
					Language::whereIn('id', Input::get('ids'))->delete();
					
				}
				Session::flash('flash_notice', trans("messages.global.action_performed_message")); 
			}
		}
	}//end performMultipleAction()
	
}//end LanguageController
