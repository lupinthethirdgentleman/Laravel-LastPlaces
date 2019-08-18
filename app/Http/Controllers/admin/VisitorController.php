<?php
namespace App\Http\Controllers\admin;
use App\Http\Controllers\BaseController;
use App\Model\Visitor;
use mjanssen\BreadcrumbsBundle\Breadcrumbs as Breadcrumb;
use Auth,Blade,Config,Cache,Cookie,DB,File,Hash,Input,Mail,mongoDate,Redirect,Request,Response,Session,URL,View,Validator;

/**
 * Contacts Controller
 *
 * Add your methods in the class below
 *
 * This file will render views from views/admin/contact
 */
 
class VisitorController extends BaseController {

	 /**
	 * Function for display list of  all contact
	 *
	 * @param null
	 *
	 * @return view page. 
	 */
	public $model	=	'Visitor';
	
	public function __construct() {
		View::share('modelName',$this->model);
	}
	
	public function listVisitor(){
		### breadcrumbs Start ###
		// Breadcrums   is  added   here dynamically
		Breadcrumb::addBreadcrumb(trans("messages.global.breadcrumbs_dashboard"),route('admin_dashboard'));
		Breadcrumb::addBreadcrumb(trans("messages.$this->model.breadcrumbs_module"),route($this->model.'.index'));
		$breadcrumbs 	= 	Breadcrumb::generate();
		### breadcrumbs End ###
		
		$DB = Visitor::query();
		
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
					if($fieldName	==	'visit_time'){
							$DB->where("$fieldName",'like','%'.strtotime($fieldValue).'%'); 
					}else{
						$DB->where("$fieldName",'like','%'.$fieldValue.'%');
					}
					$searchVariable	=	array_merge($searchVariable,array($fieldName => $fieldValue));
				}
			}
		}
		$sortBy 	= 	(Input::get('sortBy')) ? Input::get('sortBy') : 'created_at';
	    $order  	= 	(Input::get('order')) ? Input::get('order')   : 'DESC';
		$model 		= 	$DB->orderBy($sortBy, $order)->paginate(Config::get("Reading.records_per_page"));
		return  View::make("admin.$this->model.index", compact('breadcrumbs','model' ,'searchVariable','sortBy','order'));
	} // end listVisitors()

	 /**
	 * Function for display visitor detail
	 *
	 @param $visitorId as id of visitor
	 *
	 * @return view page. 
	 */
	public function viewVisitor($modelId = 0){
		$visitorDetail =	Visitor::where('id' ,$modelId)->firstOrFail();
		### breadcrumbs Start ###
		Breadcrumb::addBreadcrumb(trans("messages.global.breadcrumbs_dashboard"),route('admin_dashboard'));
		Breadcrumb::addBreadcrumb(trans("messages.$this->model.breadcrumbs_module"),route($this->model.'.index'));
		$breadcrumbs 	= 	Breadcrumb::generate();
		### breadcrumbs End ###
		return   View::make("admin.$this->model.view", compact('visitorDetail','breadcrumbs'));
	} // end viewVisitor()
	
}// end ContactsController
