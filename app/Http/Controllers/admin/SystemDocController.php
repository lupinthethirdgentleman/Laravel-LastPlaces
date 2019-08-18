<?php
namespace App\Http\Controllers\admin;

use App\Http\Controllers\BaseController;
use App\Model\SystemDoc;
use mjanssen\BreadcrumbsBundle\Breadcrumbs as Breadcrumb;
use Auth,Blade,Config,Cache,Cookie,DB,File,Hash,Input,Mail,mongoDate,Redirect,Request,Response,Session,URL,View,Validator;

/**
 * SystemDocController Controller
 *
 * Add your methods in the class below
 *
 * This file will render views from views/admin/systemdoc
 */
 
class SystemDocController extends BaseController {

	 /**
	 * Function for display all Document 
	 *
	 * @param null
	 *
	 * @return view page. 
	 */
 
	public function listDoc(){	
		### breadcrumbs Start ###
		// Breadcrums   is  added   here dynamically
		Breadcrumb::addBreadcrumb(trans("messages.system_management.dashboard"),URL::to('admin/dashboard'));
		Breadcrumb::addBreadcrumb(trans("messages.system_management.document_manager"),URL::to('admin/system-doc-manager'));
		$breadcrumbs 	= 	Breadcrumb::generate();
		### breadcrumbs End ###
		
		$DB	=	SystemDoc::query();
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
		
		return  View::make('admin.systemdoc.index',compact('breadcrumbs','result','searchVariable','sortBy','order'));
	}// end listBlock()

	
	 /**
	 * Function for display page  for add new Document
	 *
	 * @param null
	 *
	 * @return view page. 
	 */
	public function addDoc(){
		### breadcrumbs Start ###
		// Breadcrums   is  added   here dynamically
		Breadcrumb::addBreadcrumb(trans("messages.system_management.dashboard"),URL::to('admin/dashboard'));
		Breadcrumb::addBreadcrumb(trans("messages.system_management.document_manager"),URL::to('admin/system-doc-manager'));
		Breadcrumb::addBreadcrumb('Add Document ','');
		$breadcrumbs 	= 	Breadcrumb::generate();
		### breadcrumbs End ###

		return  View::make('admin.systemdoc.add',compact('breadcrumbs'));
	} //end addBlock()
	
	 /**
	 * Function for save document
	 *
	 * @param null
	 *
	 * @return redirect page. 
	 */
	function saveDoc(){
		$thisData				=	Input::all();
		
		$validator = Validator::make(
			$thisData,
			array(
				'title' 		=> 'required',
				'file' 			=> 'required',
			)
		);
		
		if ($validator->fails())
		{	
			return Redirect::back()
				->withErrors($validator)->withInput();
		}else{
		
			if(Input::hasFile('file')){
				$extension 	=	Input::file('file')->getClientOriginalExtension();
				$file_name	=	time().'file.'.$extension; 
				Input::file('file')->move(SYSTEM_DOCUMENTS_UPLOAD_DIRECTROY_PATH, $file_name);
			}else{
				$file_name	= '';
			} 
			$doc = new SystemDoc;
			$doc->title    				= Input::get('title');
			$doc->name    				= $file_name;
			$doc->save();
			Session::flash('flash_notice', trans("messages.management.doc_add_msg")); 
			return Redirect::to('admin/system-doc-manager');
		}
	}//end saveBlock()

	 /**
	 * Function for display page  for edit Document page
	 *
	 * @param $Id ad id of Document page
	 *
	 * @return view page. 
	 */	
	public function editDoc($Id){
		### breadcrumbs Start ###
		// Breadcrums   is  added   here dynamically
		Breadcrumb::addBreadcrumb('Dashboard',URL::to('admin/dashboard'));
		Breadcrumb::addBreadcrumb('Document Manager',URL::to('admin/system-doc-manager'));
		Breadcrumb::addBreadcrumb('Edit Document ','');
		$breadcrumbs 		= 	Breadcrumb::generate();
		### breadcrumbs End ###
		$docs				=	SystemDoc::find($Id);
		return  View::make('admin.systemdoc.edit',array('breadcrumbs' => $breadcrumbs,'doc' => $docs));
	}// end editBlock()

	 /**
	 * Function for update Document 
	 *
	 * @param $Id ad id of Document 
	 *
	 * @return redirect page. 
	 */
	function updateDoc($Id){
		$this_data				=	Input::all();
		$doc 					= 	SystemDoc:: find($Id);
		
		$validator = Validator::make(
			$this_data,
			array(
				'title' 		=> 'required',
			)
		);
		
		if ($validator->fails())
		{	
			return Redirect::back()
				->withErrors($validator)->withInput();
		}else{	
			if(Input::hasFile('file')){
				$extension 	=	Input::file('file')->getClientOriginalExtension();
				$file_name	=	time().'file.'.$extension; 
				Input::file('file')->move(SYSTEM_DOCUMENTS_UPLOAD_DIRECTROY_PATH, $file_name);
				@unlink(SYSTEM_DOCUMENTS_UPLOAD_DIRECTROY_PATH.$doc->name);
				$doc->name    = $file_name;
			}
			$doc->title    				= Input::get('title');
			$doc->save();
			Session::flash('flash_notice',  trans("messages.management.doc_edit_msg")); 
			return Redirect::intended('admin/system-doc-manager');
		}
	}// end updateDoc()

	 /**
	 * Function for update Doc  status
	 *
	 * @param $Id as id of Document 
	 * @param $Status as status of Document 
	 *
	 * @return redirect page. 
	 */	
	public function updateDocStatus($Id = 0, $Status = 0){
		$model				=	SystemDoc::find($Id);
		$model->is_active	=	$Status;
		$model->save();
		Session::flash('flash_notice', trans("messages.management.doc_status_msg")); 
		return Redirect::to('admin/system-doc-manager');
	}// end updateDocStatus()

	/**
	 * Function for delete document 
	 *
	 * @param $Id as id of document 
	 *
	 * @return redirect page. 
	 */	
	public function deleteDoc($Id = 0){
		if($Id){
			$doc	=	SystemDoc::find($Id) ;
			//delete from folder
			@unlink(SYSTEM_DOCUMENTS_UPLOAD_DIRECTROY_PATH.$doc->name);
			$doc->delete();	
		}
		Session::flash('flash_notice',trans("messages.management.doc_delete_msg"));  
		return Redirect::to('admin/system-doc-manager');
	}// end deleteDoc()
	
	/**
	 * Function for delete multiple doc
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
					SystemDoc::whereIn('id', Input::get('ids'))->delete();
					Session::flash('flash_notice',trans("messages.management.doc_all_delete_msg")); 
				}
			}
		}
	}//end performMultipleAction()
	
}// end BlockController
