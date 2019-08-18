<?php
namespace App\Http\Controllers\admin;
use App\Http\Controllers\BaseController;
use App\Model\Newsletter;
use App\Model\NewsletterTemplate;
use App\Model\NewsLettersubscriber;
use App\Model\Subscriber;
use App\Model\User;
use mjanssen\BreadcrumbsBundle\Breadcrumbs as Breadcrumb;
use Auth,Blade,Config,Cache,Cookie,DB,File,Hash,Input,Mail,mongoDate,Redirect,Request,Response,Session,URL,View,Validator;

/**
 * NewsLetter Controller
 *
 * Add your methods in the class below
 *
 * This file will render views from views/admin/newsletter
 */
 
class NewsLetterController extends BaseController {

	
 /**
 * Function for display all newslatter template
 *
 * @param null
 *
 * @return view page. 
 */
	public function listTemplate() { 
			
		Breadcrumb::addBreadcrumb(trans("messages.system_management.dashboard"),URL::to('admin/dashboard'));
		Breadcrumb::addBreadcrumb(trans("messages.system_management.newsletter_template"),URL::to('admin/news-letter/newsletter-templates'));
		Breadcrumb::addBreadcrumb(trans("messages.system_management.newsletter"),URL::to('admin/news-letter'));
		$breadcrumbs 	= 	Breadcrumb::generate();
		
		$DB	=	Newsletter::query();
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
		
		return  View::make('admin.newsletter.index',compact('breadcrumbs','result','searchVariable','sortBy','order'));
	}// end listTemplate()
	
 /**
 * Function for display page  for edit newslatter template
 *
 * @param null
 *
 * @return view page. 
 */
	function editTemplate($Id){
	
		Breadcrumb::addBreadcrumb(trans("messages.system_management.dashboard"),URL::to('admin/dashboard'));
		Breadcrumb::addBreadcrumb(trans("messages.system_management.newsletter"),URL::to('admin/news-letter/newsletter-templates'));
		Breadcrumb::addBreadcrumb(trans("messages.system_management.edit_newsletter"),URL::to('admin/news-letter'));
		
		$breadcrumbs 	= 	Breadcrumb::generate();
		
		
		$result						=	Newsletter::find($Id);
		$newsletter_template_id		=	$result->newsletter_template_id;
		
		$allReadySubscriberArray	=	NewsLettersubscriber::
										where('status', '=', 1)->
										whereIn('id',
								function($query) use ($newsletter_template_id)
									{
										$query->select('newsletter_subscriber_id')
											  ->from('subscribers')
											  ->whereRaw('subscribers.newsletter_id = '.$newsletter_template_id);
									})->
										lists('email','id'); 
		
		$subscriberArray			=	NewsLettersubscriber::where('status', '=', 1)->lists('email','id'); 
		
		return  View::make('admin.newsletter.edit',compact('result','subscriberArray','allReadySubscriberArray','breadcrumbs'));
	}//end editTemplate()
	
 /**
 * Function for save updated newslatter template
 *
 * @param $Id as id of template 
 *
 * @return redirect page. 
 */
	function updateTemplate($Id){
		$validator = Validator::make(
			Input::all(),
			array(
				'scheduled_time' 	=> 'required',
				'subject' 			=> 'required',
				'newsletter_subscriber_id' 	=> 'required',
				'body' 				=> 'required'
			),array('newsletter_subscriber_id.required' => 'The newsletter subscribers field is required.')
		);
		
		if ($validator->fails())
		{	
			return Redirect::to('admin/news-letter/edit-template/'.$Id)
				->withErrors($validator)->withInput();
		}else{
			
			$subscriberArray	=	NewsLettersubscriber::where('status', '=', 1)->lists('email','id'); 
			
			NewsLetter::where('id','=',$Id)
				->update(array(
					'scheduled_time'  		 => 	Input::get('scheduled_time'),
					'subject'  				 => 	Input::get('subject'),
					'body' 	   				 => 	Input::get('body'),
					'newsletter_template_id' => 	$Id,
					'status'				 => 	0
				));
			
			Subscriber::where('newsletter_id', '=', $Id)->delete();
			
			if(Input::get('newsletter_subscriber_id') == ''){
				 foreach($subscriberArray as $to =>$email){
					Subscriber::insert(
						array(
							'newsletter_subscriber_id' =>  $to,
							'newsletter_id' =>  $Id
						));
				}
			}else{
				foreach(Input::get('newsletter_subscriber_id') as $to){
					Subscriber::insert(
						array(
							'newsletter_subscriber_id' =>  $to,
							'newsletter_id' =>  $Id
						));
				}   
			}
			Session::flash('flash_notice',trans("messages.system_management.newsletter_has_been_updated_successfully")); 
			return Redirect::to('admin/news-letter');
		}
	}// end updateTemplate()
	
 /**
 * Function for display all newslatter template
 *
 * @param null
 *
 * @return view page. 
 */
	function newsletterTemplates(){
		### breadcrumbs Start ###
		Breadcrumb::addBreadcrumb(trans("messages.system_management.dashboard"),URL::to('admin/dashboard'));
		Breadcrumb::addBreadcrumb(trans("messages.system_management.newsletter_template"),URL::to('admin/news-letter/newsletter-templates'));
		$breadcrumbs 	= 	Breadcrumb::generate();
		### breadcrumbs End ###
		
		$DB				=	NewsletterTemplate::query();
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
		
		return  View::make('admin.newsletter.newsletter_templates',compact('breadcrumbs','result','searchVariable','sortBy','order'));
	}// end newsletterTemplates()
	
 /**
 * Function for display page for add new newslatter template
 *
 * @param null
 *
 * @return view page. 
 */
	function addTemplates(){
		### breadcrumbs Start ###
		Breadcrumb::addBreadcrumb(trans("messages.system_management.dashboard"),URL::to('admin/dashboard'));
		Breadcrumb::addBreadcrumb(trans("messages.system_management.newsletter_template"),URL::to('admin/news-letter/newsletter-templates'));
		Breadcrumb::addBreadcrumb(trans("messages.system_management.add_newsletter_templates"),'');
		$breadcrumbs 	= 	Breadcrumb::generate();
		### breadcrumbs End ###
		return  View::make('admin.newsletter.add_newsletter_templates',compact('breadcrumbs'));
	}
	
 /**
 * Function for save created template
 *
 * @param null
 *
 * @return redirect page. 
 */
	function saveTemplates(){
		$validator = Validator::make(
			Input::all(),
			array(
				'subject' 	=> 'required',
				'body' 		=> 'required'
			)
		);
		
		if ($validator->fails())
		{	
			return Redirect::to('admin/news-letter/add-template')
				->withErrors($validator)->withInput();
		}else{
		
			NewsletterTemplate::insert(array(
					'subject'  		=> 	Input::get('subject'),
					'body' 	   		=> 	Input::get('body'),
					'created_at' 	=> DB::raw('NOW()'),
					'updated_at' 	=> DB::raw('NOW()')
				));
			
			Session::flash('flash_notice', trans("messages.system_management.your_newsletter_template_has_been_saved_successfully")); 
			return Redirect::to('admin/news-letter/newsletter-templates');
		}
	}
	
 /**
 * Function for display page for edit newslatter template
 *
 * @param $Id as id of newslatter
 *
 * @return view page. 
 */
	function editNewsletterTemplate($Id){
		
		### breadcrumbs Start ###
		Breadcrumb::addBreadcrumb(trans("messages.system_management.dashboard"),URL::to('admin/dashboard'));
		Breadcrumb::addBreadcrumb(trans("messages.system_management.newsletter_template"),URL::to('admin/news-letter/newsletter-templates'));
		Breadcrumb::addBreadcrumb(trans("messages.system_management.edit_newsletter_templates"),'');
		
		$breadcrumbs 	= 	Breadcrumb::generate();
		### breadcrumbs End ###
		
		$result		    =	NewsletterTemplate::find($Id);
		return  View::make('admin.newsletter.edit_newsletter_templates',compact('result','breadcrumbs'));
	}// end editNewsletterTemplate()
	
 /**
 * Function for save updated newslatter
 *
 * @param $Id as id of newslatter
 *
 * @return redirect page. 
 */
	function updateNewsletterTemplate($Id){
		$validator = Validator::make(
			Input::all(),
			array(
				'subject' 	=> 'required',
				'body' 		=> 'required'
			)
		);
		
		if ($validator->fails())
		{	
			return Redirect::to('admin/news-letter/edit-newsletter-templates/'.$Id)
				->withErrors($validator)->withInput();
		}else{
		
				NewsletterTemplate::where('id', $Id)
				->update(array(
					'subject'  		=> 	Input::get('subject'),
					'body' 	   		=> 	Input::get('body'),
					'updated_at' 	=> DB::raw('NOW()')
				));
			
			Session::flash('flash_notice',trans("messages.system_management.newsletter_template_has_been_updated_successfully")); 
			return Redirect::to('admin/news-letter/newsletter-templates');
		}
	}//end updateNewsletterTemplate()
	
 /**
 * Function for send newslatter template
 *
 * @param $Id as id of newslatter
 *
 * @return view page. 
 */
	function sendNewsletterTemplate($Id){
		
		### breadcrumbs Start ###
		Breadcrumb::addBreadcrumb(trans("messages.system_management.dashboard"),URL::to('admin/dashboard'));
		Breadcrumb::addBreadcrumb(trans("messages.system_management.newsletter_template"),URL::to('admin/news-letter/newsletter-templates'));
		Breadcrumb::addBreadcrumb(trans("messages.system_management.send_newsletter"),'');
		$breadcrumbs 	= 	Breadcrumb::generate();
		
		$subscriberArray	=	NewsLettersubscriber::where('status', '=', 1)->lists('email','id'); 
		$result				=	NewsletterTemplate::find($Id);
		
		return  View::make('admin.newsletter.send_newsletter_templates',compact('result','subscriberArray','breadcrumbs'));
	}
	
 /**
 * Function for update send newslatter
 *
 * @param $Id as id of newslatter
 *
 * @return redirect page. 
 */
	function updateSendNewsletterTemplate($Id){
	
		$validator = Validator::make(
			Input::all(),
			array(
				'scheduled_time' 	=> 'required',
				'subject' 			=> 'required',
				'body' 				=> 'required'
			)
		);
		
		if ($validator->fails())
		{	
			return Redirect::to('admin/news-letter/send-newsletter-templates/'.$Id)
				->withErrors($validator)->withInput();
		}else{
			 $newsLetterInsertId	=	NewsLetter::insertGetId(array(
					'scheduled_time'  		 => 	Input::get('scheduled_time'),
					'subject'  				 => 	Input::get('subject'),
					'body' 	   				 => 	Input::get('body'),
					'newsletter_template_id' => 	$Id,
					'status'				 => 	0
				)); 
			
			if(Input::get('newsletter_subscriber_id') == ''){
				$subscriberArray	=	NewsLettersubscriber::where('status', '=', 1)->lists('email','id'); 
				 foreach($subscriberArray as $to =>$email){
					Subscriber::insert(
						array(
							'newsletter_subscriber_id' =>  $to,
							'newsletter_id' =>  $newsLetterInsertId
						));
				}
			}else{
				foreach(Input::get('newsletter_subscriber_id') as $to=>$val){
					Subscriber::insert(
						array(
							'newsletter_subscriber_id' =>  $val,
							'newsletter_id' =>  $Id
						));
				}   
			}
			Session::flash('flash_notice', trans("messages.system_management.newsletter_sent_successfully") ); 
			return Redirect::to('admin/news-letter/newsletter-templates');
		}
	}//end updateSendNewsletterTemplate()
	
 /**
 * Function for display list of all newslatter subscriber
 *
 * @param null
 *
 * @return view page. 
 */
	function subscriberList(){ 
		### breadcrumbs Start ###
		Breadcrumb::addBreadcrumb(trans("messages.system_management.dashboard"),URL::to('admin/dashboard'));
		Breadcrumb::addBreadcrumb(trans("messages.system_management.newsletter_template"),URL::to('admin/news-letter/newsletter-templates'));
		Breadcrumb::addBreadcrumb(trans("messages.system_management.newsletter_subscribers"),'');
		$breadcrumbs 	= 	Breadcrumb::generate();
		### breadcrumbs End ###
		
		$DB				=	NewsLettersubscriber::query();
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
		
		return  View::make('admin.newsletter.subscriber_list',compact('breadcrumbs','result','searchVariable','sortBy','order'));
	}// end subscriberList()
	
 /**
 * Function for change status of subscriber
 *
 * @param $Id  as id of subscriber
 * @param $status  as status of subscriber
 *
 * @return redirect page. 
 */
	function subscriberActive($Id,$status){
		NewsLettersubscriber::where('id', '=', $Id)->update(array('status' => $status));
		
		Session::flash('flash_notice', trans("messages.system_management.status_changed_successfully")); 
		
		return Redirect::to('admin/news-letter/subscriber-list');
	}//end subscriberActive()
	
 /**
 * Function for delete newslatter subscriber
 *
 * @param $Id as subscriber id
 *
 * @return redirect page. 
 */
	function subscriberDelete($Id){
		NewsLettersubscriber::where('id', '=', $Id)->delete();
		
		Session::flash('flash_notice', trans("messages.system_management.newslettersubscriber_deleted_successfully") ); 
		
		return Redirect::to('admin/news-letter/subscriber-list');
	}//end subscriberDelete()
	
 /**
 * Function for delete template
 *
 * @param $Id id of template
 *
 * @return redirect page. 
 */
	function templateDelete($Id){
		if($Id){
			$obj	=  Newsletter::find($Id);
			$obj->delete();
		}
		Session::flash('flash_notice', trans("messages.system_management.newsletter_template_deleted_successfully") ); 
		return Redirect::to('admin/news-letter');
	}//end templateDelete()
	
 /**
 * Function for delete news template
 *
 * @param $Id id of template
 *
 * @return redirect page. 
 */
	function deleteNewsTemplate($Id){
		if($Id){
			NewsletterTemplate::where('id' ,'=', $Id)->delete();
		}
		Session::flash('flash_notice', trans("messages.system_management.newsletter_deleted_successfully")); 
		return Redirect::to('admin/news-letter/newsletter-templates');
	}//end deleteNewsTemplate()
	
 /**
 * Function for display list of all newslatter subscriber
 *
 * @param $Id id of template
 *
 * @return view page. 
 */
	function viewSubscrieber($id){
		
		
		$result						=	Newsletter::find($id);
		$newsletter_template_id		=	$result->newsletter_template_id;
		
		$result			=	NewsLettersubscriber::
								where('status', '=', 1)->
								whereIn('id',
								function($query) use ($newsletter_template_id)
									{
										$query->select('newsletter_subscriber_id')
											  ->from('subscribers')
											  ->whereRaw('subscribers.newsletter_id = '.$newsletter_template_id);
									})->
								lists('email','id'); 
		return  View::make('admin.newsletter.view_subscrieber',compact('result'));
	}//end viewSubscrieber()
	
 /**
 * Function for add subscriber
 *
 * @param null
 * 
 * @return view page. 
 */
	public function addSubscriber(){
		
		### breadcrumbs Start ###
		Breadcrumb::addBreadcrumb(trans("messages.system_management.dashboard"),URL::to('admin/dashboard'));
		Breadcrumb::addBreadcrumb(trans("messages.system_management.newsletter_subscribers"),URL::to('admin/news-letter/subscriber-list'));
		Breadcrumb::addBreadcrumb(trans("messages.system_management.add"),'');
		$breadcrumbs 	= 	Breadcrumb::generate();
		
		if(Request::isMethod('post')){
			
			$validator = Validator::make(
				Input::all(),
				array(
					'email' 	=> 'required|email|unique:newsletter_subscribers',
				)
			);
		
			if ($validator->fails())
			{	
				return Redirect::back()
					->withErrors($validator)->withInput();
			}else{
				$encId			=	md5(time() . Input::get('email'));
				NewsLettersubscriber::insert(array(
						'email'	  		=>  Input::get('email'),
						'name'	  		=>  Input::get('name'),
						'is_verified' 	=>  1,
						'status' 		=>  1,
						'enc_id' 		=>  $encId
					));
				
				Session::flash('flash_notice', trans("messages.system_management.subscriber_add_successfully")); 
				return Redirect::to('admin/news-letter/subscriber-list');
			}
		}
		return  View::make('admin.newsletter.add_subscriber',compact('breadcrumbs'));
	}//end addSubscriber()
	
	
	public function deleteMultipleSubscriber(){
		if(Request::ajax()){
			$actionType = ((Input::get('type'))) ? Input::get('type') : '';
			
			if(!empty($actionType) && !empty(Input::get('ids'))){
				if($actionType	==	'active'){
					NewsLettersubscriber::whereIn('id', Input::get('ids'))->update(array('status' => 1));
				}
				elseif($actionType	==	'inactive'){
					NewsLettersubscriber::whereIn('id', Input::get('ids'))->update(array('status' => 0));
				}
				elseif($actionType	==	'delete'){
					NewsLettersubscriber::whereIn('id', Input::get('ids'))->delete();
				}
				Session::flash('success', trans("messages.system_management.action_performed_message")); 
				
			}
		}
	}
	
}// end NewsLetterController class
