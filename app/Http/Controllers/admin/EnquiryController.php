<?php
namespace App\Http\Controllers\admin;

use App\Http\Controllers\BaseController;
use App\Model\AdminUser;
use App\Model\Enquiry;
use App\Model\EmailTemplate;
use App\Model\EmailAction;
use mjanssen\BreadcrumbsBundle\Breadcrumbs as Breadcrumb;
use Auth,Blade,Config,Cache,Cookie,DB,File,Hash,Input,Mail,mongoDate,Redirect,Request,Response,Session,URL,View,Validator;
/**
 * Enquiry Controller
 *
 * Add your methods in the class below
 *
 * This file will render views from views/admin/Enquiry
 */
 
class EnquiryController extends BaseController {
	
	public $model	=	'Enquiry';
	
	public function __construct() {
		View::share('modelName',$this->model);
	}

	 /**
	 * Function for display list of  all Enquiry
	 *
	 * @param null
	 *
	 * @return view page. 
	 */
	public function listEnquiry(){
		
		### breadcrumbs Start ###
		// Breadcrums   is  added   here dynamically
		Breadcrumb::addBreadcrumb(trans("messages.global.breadcrumbs_dashboard"),route('admin_dashboard'));
		Breadcrumb::addBreadcrumb(trans("messages.$this->model.breadcrumbs_module"),route($this->model.'.index'));
		$breadcrumbs 	= 	Breadcrumb::generate();
		### breadcrumbs End ###
		
		$DB = Enquiry::query();
		
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
		
		$model 	= 	$DB->orderBy($sortBy, $order)->paginate(Config::get("Reading.records_per_page"));
		return  View::make("admin.$this->model.index",compact('breadcrumbs','model' ,'searchVariable','sortBy','order'));
	} // end listEnquiry()

	 /**
	 * Function for display Enquiry detail
	 *@param $modelId as id of Enquiry
	 *
	 * @return view page. 
	 */
	 
	public function viewEnquiry($modelId = 0){
		### breadcrumbs Start ###
		// Breadcrums   is  added   here dynamically
		Breadcrumb::addBreadcrumb(trans("messages.global.breadcrumbs_dashboard"),route('admin_dashboard'));
		Breadcrumb::addBreadcrumb(trans("messages.$this->model.breadcrumbs_module"),route($this->model.'.index'));;
		Breadcrumb::addBreadcrumb(trans("messages.$this->model.breadcrumbs_view"),'');
		$breadcrumbs 	= 	Breadcrumb::generate();
		$DB = Enquiry::query();
		### breadcrumbs End ###
		
		if($modelId){
			$model	=	$DB->with('trip')
							->where('id' ,$modelId)
							->first();
			return  View::make("admin.$this->model.view", compact('model','breadcrumbs','modelId'));
		} 
	} // end viewEnquiry()
	
	 /**
	 * Function for delete Enquiry
	 * 
	 * @param $modelId as id 
	 *
	 * @return redirect page. 
	 */
	public function deleteEnquiry($modelId = 0){
		if($modelId){
			$model = Enquiry::findorFail($modelId);
			$model->description()->delete();
			$model->delete();
			Session::flash('flash_notice',trans("messages.$this->model.deleted_message")); 
		}
		return Redirect::route("$this->model.index");
	}// end deleteEnquiry()
	
	/**
	 * Function to reply a user 
	 * 
	 * @param $modelId as id 
	 *
	 * @return view page. 
	 */	
	public function replyToUser($Id){
		if(!empty(Input::all())){
			$validationRules	= array('message'	=> 'required');
			$validator = Validator::make(
						Input::all(),
						$validationRules
					);
			if($validator->fails()){
				 return Redirect::back()->withErrors($validator)->withInput();
			}else{ 
				$userData	=	Enquiry::where('id',$Id)->first();
				##### send email to user from admin,to inform user that your message has been received successfully #####
					
				$emailActions		=  EmailAction::where('action','=','enquiry_reply_to_user')->get()->toArray();
				$emailTemplates		=  EmailTemplate::where('action','=','enquiry_reply_to_user')->get(array('name','subject','action','body'))->toArray();
				$cons 				=  explode(',',$emailActions[0]['options']);
				$constants 			=  array();
				
				foreach($cons as $key=>$val){
					$constants[] = '{'.$val.'}';
				}
				
				$name		=	 $userData->name;
				$email		=	 $userData->email;
				$message	=	 Input::get('message');
				$trip_name	=	 Input::get('trip_name');
				
				$subject 		=  $emailTemplates[0]['subject'];
				
				$rep_Array 		=  array($name,$trip_name,$message); 
				$messageBody	=  str_replace($constants, $rep_Array, $emailTemplates[0]['body']);
				 
				$this->sendMail($email,$name,$subject,$messageBody,Config::get("Site.email"));
				
				Session::flash('success','You have Successfully replied to '. $name);
				return Redirect::route("$this->model.index");
			}	
			
		}		
	}//end replyToUser()
	
}// end EnquiryController
