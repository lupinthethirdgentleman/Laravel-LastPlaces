<?php
namespace App\Http\Controllers\admin;
use App\Http\Controllers\BaseController;
use App\Model\AdminUser;
use App\Model\User;
use mjanssen\BreadcrumbsBundle\Breadcrumbs as Breadcrumb;
use Auth,Blade,Config,Cache,Cookie,DB,File,Hash,Input,Mail,mongoDate,Redirect,Request,Response,Session,URL,View,Validator;

/**
 * BlockController Controller
 *
 * Add your methods in the class below
 *
 */
 
class LanguageSettingsController extends BaseController {

/**
 * Function for display all created text and message for different language
 *
 * @param null
 *
 * @return view page. 
*/
	public function listLanguageSetting(){	
	

		$this->settingFileWrite(); 
		
		### breadcrumbs Start ###
		Breadcrumb::addBreadcrumb('Dashboard',URL::to('admin/dashboard'));
		Breadcrumb::addBreadcrumb('Language Settings',URL::to('admin/language-settings'));
		$breadcrumbs 	= 	Breadcrumb::generate();
		### breadcrumbs End ###
		
		$DB				=	AdminLanguageSetting::query();
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
		
		return  View::make('admin.languages.index',compact('breadcrumbs','result','searchVariable','sortBy','order'));
	} // listLanguageSetting()

/**
 * Function for display page for  add new text or message
 *
 * @param null
 *
 * @return view page. 
 */
	public function addLanguageSetting(){
		
		### breadcrumbs Start ###
		Breadcrumb::addBreadcrumb('Dashboard',URL::to('admin/dashboard'));
		Breadcrumb::addBreadcrumb('Language Settings',URL::to('admin/language-settings'));
		Breadcrumb::addBreadcrumb('Add New Word',URL::to('admin/language-settings'));
		$breadcrumbs 		= 	Breadcrumb::generate();
		### breadcrumbs End ###	
		
		$languages			=	Language::where('active', '=', '1')->get();
		
		$default_language	=	Config::get('default_language');
		$language_code 		=   $default_language['language_code'];
		
		return  View::make('admin.languages.add',compact('languages' ,'language_code','breadcrumbs'));
	
	} // end addLanguageSetting()

/**
 * Function for save new text or message 
 *
 * @param null
 *
 * @return redirect page. 
 */
	public function saveLanguageSetting(){	
		$thisData	=	Input::all();
		$validator  = Validator::make(
			$thisData,
			array(
				'default' 		=> 'required'
			)
		);
		
		if ($validator->fails())
		{	
			return Redirect::to('admin/language-settings/add-setting')
				->withErrors($validator)->withInput();
		}else{
			
			$msgid		=	Input::get('default');
			foreach($thisData['language'] as $key => $val){
				$obj	 = 	new AdminLanguageSetting;

				$obj->msgid    		=  trim($msgid);
				$obj->locale   		=  trim($key);
				$obj->msgstr   		=  $val;
				$obj->save();
			}
			$this->settingFileWrite();
			return Redirect::to('admin/language-settings')
				->with('success',trans("messages.system_Management.language_add_msg") );
		}
	}// end saveLanguageSetting()
 
/**
 * Function for display page for edit text or message
 *
 * @param $Id as id of created text or message
 *
 * @return view page. 
*/
	function editLanguageSetting($Id){ 
	
		### breadcrumbs Start ###
		Breadcrumb::addBreadcrumb('Dashboard',URL::to('admin/dashboard'));
		Breadcrumb::addBreadcrumb('Language Settings',URL::to('admin/language-settings'));
		Breadcrumb::addBreadcrumb('Edit',URL::to('admin/language-settings'));
		$breadcrumbs 	= 	Breadcrumb::generate();
		### breadcrumbs End ###
		
		$result		=	AdminLanguageSetting::find($Id);
		return  View::make('admin.languages.edit',compact('Id','result','breadcrumbs'));
	} // end editLanguageSetting()

/**
 * Function for save changed message or text 
 *
 * @param $Id as id of created text or message
 *
 * @return redirect page. 
*/
	function updateLanguageSetting($Id){ 
		$thisData	=	Input::all();
		$validator  = Validator::make(
			$thisData,
			array(
				'word'  => 'required'
			)
		);
		
		if ($validator->fails())
		{	
			return Redirect::to('admin/language-settings/edit-setting/'.$Id)
				->withErrors($validator)->withInput();
		}else{
			
			$obj	 	 	=	AdminLanguageSetting::find($Id);
			$obj->msgstr   	= 	trim(Input::get('word'));
			$obj->save();
			$this->settingFileWrite();
			
			return Redirect::to('admin/language-settings')
				->with('success',trans("messages.system_Management.language_edit_msg"));
		}
	} // end updateLanguageSetting()
/**
 * Function for write file on create and update text  or message 
 *
 * @param null
 *
 * @return void. 
 */
	public function settingFileWrite(){ 
		
		$DB			=	AdminLanguageSetting::query();
		$list		=	$DB->get()->toArray();
		
		$languages	=	language::where('active', '=', '1')->get(array('folder_code','lang_code'));
		
		foreach($languages as $key => $val){
			$currLangArray	=	'<?php return array(';
			foreach($list as $listDetails){
				if($listDetails['locale'] == $val->lang_code){
					$currLangArray	.=  '"'.$listDetails['msgid'].'"=>"'.$listDetails['msgstr'].'",'."\n";
				}
			}
			$currLangArray	.=	');';
			
			$file 			= 	 ROOT.DS.'app'.DS.'lang'.DS.$val->lang_code.DS.'messages.php';
			$bytes_written  = 	 File::put($file, $currLangArray);
			if ($bytes_written === false)
			{
				die("Error writing to file");
			}
		}
	}// end settingFileWrite()
	
}
