<?php
namespace App\Http\Controllers\admin;

use App\Http\Controllers\BaseController;
use App\Model\AdminUser;
use App\Model\EmailTemplate;
use App\Model\EmailAction;
use mjanssen\BreadcrumbsBundle\Breadcrumbs as Breadcrumb;
use Auth,Blade,Config,Cache,Cookie,DB,File,Hash,Input,Mail,mongoDate,Redirect,Request,Response,Session,URL,View,Validator;

/**
 * Emailtemplate Controller
 *
 * Add your methods in the class below
 *
 * This file will render views from views/admin/emailtemplates
 */
 
class EmailtemplateController extends BaseController {

 /**
 * Function for display list of all email templates
 *
 * @param null
 *
 * @return view page. 
 */
	public function listTemplate(){
		
		### breadcrumbs Start ###
		// Breadcrums   is  added   here dynamically
		Breadcrumb::addBreadcrumb(trans("messages.system_management.dashboard"),URL::to('admin/dashboard'));
		Breadcrumb::addBreadcrumb(trans("messages.system_management.email_templates"),URL::to('admin/email-manager'));
		$breadcrumbs 	= 	Breadcrumb::generate();
		### breadcrumbs End ###
		
		$DB				=	EmailTemplate::query();
		$searchVariable	=	array(); 
		$inputGet		=	Input::get();
		
		if ((Input::get() && isset($inputGet['display'])) || isset($inputGet['page']) ){
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
		
		$result	 	= 	$DB->orderBy($sortBy, $order)->paginate(Config::get("Reading.records_per_page"));
		
		return  View::make('admin.emailtemplates.index', compact('breadcrumbs','result','searchVariable','sortBy','order'));
	}// end listTemplate()

 /**
 * Function for display page for add email template
 *
 * @param null
 *
 * @return view page. 
 */
	public function addTemplate(){
		
		### breadcrumbs Start ###
		// Breadcrums   is  added   here dynamically
		Breadcrumb::addBreadcrumb(trans("messages.system_management.dashboard"),URL::to('admin/dashboard'));
		Breadcrumb::addBreadcrumb(trans("messages.system_management.email_templates"),URL::to('admin/email-manager'));
		Breadcrumb::addBreadcrumb(trans("messages.system_management.add_email_template"),'');
		$breadcrumbs 	= 	Breadcrumb::generate();
		### breadcrumbs End ###
		
		$Action_options	=	EmailAction::lists('action','action');
		
		return  View::make('admin.emailtemplates.add',compact('Action_options','breadcrumbs'));
	}// end addTemplate()
	
 /**
 * Function for display save email template
 *
 * @param null
 *
 * @return redirect page. 
 */
	public function saveTemplate(){
		$validator = Validator::make(
			Input::all(),
			array(
				'name' 			=> 'required',
				'subject' 		=> 'required',
				'action' 		=> 'required',
				'constants' 	=> 'required',
				'body' 			=> 'required'
			)
		);
		if ($validator->fails())
		{	
			return Redirect::to('admin/email-manager/add-template')
				->withErrors($validator)->withInput();
		}else{			
			EmailTemplate::insert(
				array(
					'name'		 	=> Input::get('name'),
					'subject' 		=> Input::get('subject'),
					'action' 		=> Input::get('action'),
					'body'			=> Input::get('body'),
					'created_at' 	=> DB::raw('NOW()'),
					'updated_at' 	=> DB::raw('NOW()')
				)
			);
			
			Session::flash('flash_notice', trans("messages.system_management.template_added_message")); 
			return Redirect::intended('admin/email-manager');
		}
	}//  end saveTemplate()

/**
 * Function for display page for edit email template page
 *
 * @param $Id as id of email template
 *
 * @return view page. 
 */
	public function editTemplate($Id){
		
		### breadcrumbs Start ###
		// Breadcrums   is  added   here dynamically
		Breadcrumb::addBreadcrumb(trans("messages.system_management.dashboard"),URL::to('admin/dashboard'));
		Breadcrumb::addBreadcrumb(trans("messages.system_management.email_templates"),URL::to('admin/email-manager'));
		Breadcrumb::addBreadcrumb(trans("messages.system_management.edit_email_template"),'');
		
		$breadcrumbs 	= 	Breadcrumb::generate();
		
		### breadcrumbs End ###
		
		$Action_options	=	EmailAction::lists('action','action')->toArray();
		$emailTemplate	=	EmailTemplate::find($Id);
		
		return  View::make('admin.emailtemplates.edit',compact('Action_options','emailTemplate','breadcrumbs'));
	} // end editTemplate()

/**
 * Function for update email template
 *
 * @param $Id as id of email template
 *
 * @return redirect page. 
 */
	public function updateTemplate($Id){
		$validator = Validator::make(
			Input::all(),
			array(
				'name' 			=> 'required',
				'subject' 		=> 'required',
				'body' 			=> 'required'
			)
		);
		if ($validator->fails())
		{	
			return Redirect::to('admin/email-manager/edit-template/'.$Id)
				->withErrors($validator)->withInput();
		}else{
			EmailTemplate::where('id', $Id)
				->update(
					array(
						'name'		 	=> Input::get('name'),
						'subject' 		=> Input::get('subject'),
						'body'			=> Input::get('body'),
						'updated_at' 	=> DB::raw('NOW()')
					)
				);
			Session::flash('flash_notice', trans("messages.system_management.template_updated_message")); 
			return Redirect::intended('admin/email-manager');
		}
	} // end updateTemplate()
	
 /**
 * Function for get all  defined constant  for email template
 *
 * @param null
 *
 * @return all  constant defined for template. 
 */
	public function getConstant(){
		if(Request::ajax() && Input::get()){
			$constantName 	= 	Input::get('constant');
			$options		= 	EmailAction::where('action', '=', $constantName)->lists('options','action'); 
			
			$a = explode(',',$options[$constantName]);
			echo json_encode($a);
		}
		exit;
	}// end getConstant()
	
/**
 * Function for delete multiple template
 *
 * @param null
 *
 * @return view page. 
 */
 
	public function performMultipleAction(){
		if(Request::ajax()){
			$actionType = ((Input::get('type'))) ? Input::get('type') : '';
			
			if(!empty($actionType) && !empty(Input::get('ids'))){
				if($actionType	==	'delete'){
					EmailTemplate::whereIn('id', Input::get('ids'))->delete();
					Session::flash('flash_notice','Selected template deleted successfully.'); 
				}
			}
		}
	}//end performMultipleAction()
	
}// end EmailtemplateController
