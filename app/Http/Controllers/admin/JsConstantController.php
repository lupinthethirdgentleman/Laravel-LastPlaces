<?php
namespace App\Http\Controllers\admin;

use App\Http\Controllers\BaseController;
use App\Model\JsConstant;
use App\Model\User;
use App\Model\Setting;
use App\Model\Language;
use App\Model\TextDescription;
use mjanssen\BreadcrumbsBundle\Breadcrumbs as Breadcrumb;
use Auth, Blade, Config, Cache, Cookie, DB, File, Hash, Input, Mail, mongoDate, Redirect, Request, Response, Session, URL, View, Validator;
/**
 * text Settings Controller
 *
 * Add your methods in the class below
 *
 * This file will render views from views/admin/JsConstant
 */

class jsConstantController extends BaseController
{
    
    /**
     * function for list all settings
     *
     * @param  null
     * 
     * @return view page
     */
    public function textList($type)
    {
        ### breadcrumbs Start ###
        Breadcrumb::addBreadcrumb(trans("messages.global.breadcrumbs_dashboard"), URL::to('admin/dashboard'));
        Breadcrumb::addBreadcrumb(trans("messages.settings.breadcrumbs_JsConstant_module"), '');
        $breadcrumbs = Breadcrumb::generate();
        ### breadcrumbs End ###
        
        $DB = JsConstant::query();
        
        $searchVariable = array();
        $inputGet       = Input::get();
        
        if ((Input::get() && isset($inputGet['display'])) || isset($inputGet['page'])) {
            $searchData = Input::get();
            unset($searchData['display']);
            unset($searchData['_token']);
            
            if (isset($searchData['page'])) {
                unset($searchData['page']);
            }
            
            foreach ($searchData as $fieldName => $fieldValue) {
                
                if (!empty($fieldValue)) {
                    if ($fieldName == 'module') {
                        $DB->where("key_value", 'like', '%' . $fieldValue . '%');
                    } else {
                        $DB->where("$fieldName", 'like', '%' . $fieldValue . '%');
                    }
                    $searchVariable = array_merge($searchVariable, array(
                        $fieldName => $fieldValue
                    ));
                }
            }
        }
        
        $sortBy = (Input::get('sortBy')) ? Input::get('sortBy') : 'updated_at';
        $order  = (Input::get('order')) ? Input::get('order') : 'DESC';
        
        $result        = $DB->where('js_constant_type',$type)->orderBy($sortBy, $order)->paginate(Config::get("Reading.records_per_page"));
        $languageArray = Language::where('is_active', '=', '1')->lists('title', 'id')->toArray();
        
        return View::make('admin.JsConstant.index', compact('sortBy', 'order', 'breadcrumbs', 'result', 'searchVariable', 'languageArray','type'));
    } // end listSetting()
    
    /**
     * function for display add text page  
     *
     * @param  null
     * 
     * @return view page
     */
    
    public function addText($type)
    {
        
        ### breadcrumbs Start ###
        Breadcrumb::addBreadcrumb(trans("messages.global.breadcrumbs_dashboard"), URL::to('admin/dashboard'));
        Breadcrumb::addBreadcrumb(trans("messages.settings.breadcrumbs_JsConstant_module"), '');
        Breadcrumb::addBreadcrumb(trans("messages.settings.breadcrumbs_JsConstant_add"), '');
        $breadcrumbs = Breadcrumb::generate();
        ### breadcrumbs End ###
        
        $languages = Language::where('is_active', '=', '1')->get(array(
            'title',
            'id'
        ));
        
        $default_language = Config::get('default_language');
      
        $language_code    = $default_language['language_code'];
        
        return View::make('admin.JsConstant.add', compact('breadcrumbs', 'languages', 'language_code', 'type'));
    } // end addText()
    
    /**
     * function for save added text
     *
     * @param  null
     * 
     * @return view page
     */
    
    public function saveText($type)
    {
        $thisData = Input::all();
        
        $default_language     = Config::get('default_language');
        $language_code        = $default_language['language_code'];
        $dafaultLanguageArray = $thisData['data'][$language_code];
        
        if (!empty($thisData)) {
            $validator = Validator::make(array(
                'key' => Input::get('key'),
                'value' => $dafaultLanguageArray
            ), array(
                'key' => 'required',
                'value' => 'required'
            ));
            if ($validator->fails()) {
                return Redirect::back()->withErrors($validator)->withInput();
            } else {
                $keyValue = Input::get('key');
                foreach ($thisData['data'] as $key => $val) {
                    if ($val) {
                        $obj              			= new JsConstant;
                        $obj->key_value  	 		= trim($keyValue);
                        $obj->language_id 			= $key;
                        $obj->value       			= $val;
						$obj->js_constant_type      = $type;
                        $obj->save();
                    }
                }
                $this->settingFileWrite();
            }
            
            Session::flash('flash_notice', trans("messages.settings.JsConstant_add"));
            return Redirect::to('admin/jsconstant-setting/'.$type);
            
        }
    } //end saveText()
    
     /**
     * function for display edit text page 
     *
     * @param  $Id as text id 
     * 
     * @return view page
     */
    
    public function editText($Id = 0,$type)
    {
        
        $result = JsConstant::where('id', $Id)->first();
        
        ### breadcrumbs Start ###
        Breadcrumb::addBreadcrumb(trans("messages.global.breadcrumbs_dashboard"), URL::to('admin/dashboard'));
        Breadcrumb::addBreadcrumb(trans("messages.settings.breadcrumbs_JsConstant_module"), '');
        Breadcrumb::addBreadcrumb(trans("messages.settings.breadcrumbs_JsConstant_edit"), '');
        $breadcrumbs = Breadcrumb::generate();
        ### breadcrumbs End ###
        
        $result = JsConstant::find($Id);
        
        return View::make('admin.JsConstant.edit', compact('breadcrumbs', 'result','type'));
    } //end editText()
    
    
    /**
     * function for update text
     *
     * @param $Id as text id
     * 
     * @return view page
     */
    
    public function updateText($Id = 0,$type)
    {
        $thisData = Input::all();
        if (!empty($thisData)) {
            $validator = Validator::make(array(
                'value' => Input::get('value')
            ), array(
                'value' => 'required'
            ));
            
            if ($validator->fails()) {
                return Redirect::back()->withErrors($validator)->withInput();
            } else {
                
                $obj        = JsConstant::find($Id);
                $obj->value = trim(Input::get('value'));
				$obj->js_constant_type      = $type;
                $obj->save();
                
                $this->settingFileWrite();
                
                Session::flash('flash_notice', trans("messages.settings.JsConstant_update"));
                return Redirect::to('admin/jsconstant-setting/'.$type);
            }
        }
        
    } //end updateText()
    
    /**
     * function for delete text
     *
     * @param $Id as text id
     * 
     * @return view page
     */
    
    public function deleteText($Id = 0)
    {
        if ($Id) {
            $result = JsConstant::where('id', $Id)->delete();
        }
        Session::flash('flash_notice',trans("messages.settings.JsConstant_delete"));
         return Redirect::to('admin/jsconstant-setting/'.$type);
    } //end deleteText()
    
    /**
     * Function for write file on create and update text  or message 
     *
     * @param null
     *
     * @return void. 
     */
    public function settingFileWrite()
    {
        
        $DB   = JsConstant::query();
        $list = $DB->get()->toArray();
        
        $languages = language::where('is_active', '=', '1')->get(array(
            'id',
            'folder_code',
            'lang_code'
        ));
        
        foreach ($languages as $key => $val) {
            
            $currLangArray = '<?php return array(';
            
            foreach ($list as $listDetails) {
                if ($listDetails['language_id'] == $val->id || $listDetails['language_id'] == 0) {
                    $currLangArray .= '"' . $listDetails['key_value'] . '"=>"' . $listDetails['value'] . '",' . "\n";
                }
            }
            $currLangArray .= ');';
            
            $file = ROOT . DS . 'resources' . DS . 'lang' . DS . $val->lang_code . DS . 'messages.php';
            
            $bytes_written = File::put($file, $currLangArray);
            if ($bytes_written === false) {
                die("Error writing to file");
            }
        }
        
    } //end settingFileWrite()
    
} //end JsConstantController class
