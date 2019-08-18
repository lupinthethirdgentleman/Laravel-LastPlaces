<?php
namespace App\Http\Controllers\admin;
use App\Http\Controllers\BaseController;
use App\Model\Ads;
use mjanssen\BreadcrumbsBundle\Breadcrumbs as Breadcrumb;
use Auth, Blade, Config, Cache, Cookie, DB, File, Hash, Input, Mail, mongoDate, Redirect, Request, Response, Session, URL, View, Validator;

/**
 * Ads Controller 
 *
 * Add your methods in the class below
 *
 * This file will render views from views/admin/Ads
 */
 
class AdsController extends BaseController {
 
 /**
 * Function for display list of all ads 
 *
 * @param null
 *
 * @return view page. 
 */	
	public function listAds(){
		// Breadcrums   is  added   here dynamically
		Breadcrumb::addBreadcrumb(trans("messages.global.breadcrumbs_dashboard"),URL::to('admin/dashboard'));
		Breadcrumb::addBreadcrumb(trans("messages.ads.breadcrumbs_Ads_module"),URL::to('admin/ads-manager'));
		$breadcrumbs 	= 	Breadcrumb::generate();
		
		$DB = Ads::query();
		
		//  search 
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
		//  this  is use for  sorting  result click  on   field name sortby and  order will  be pass  
		$sortBy = (Input::get('sortBy')) ? Input::get('sortBy') : 'updated_at';
	    $order  = (Input::get('order')) ? Input::get('order')   : 'DESC';
		
		$result 	= 	$DB->orderBy($sortBy, $order)->paginate(Config::get("Reading.records_per_page"));
		return  View::make('admin.Ads.index', compact('breadcrumbs','result' ,'searchVariable','sortBy','order'));
	}// end listAds()

 /**
 * Function for display ads add page 
 *
 * @param null
 *
 * @return view page. 
 */	
	public function addAds(){
		//  Breadcrumbs 
		Breadcrumb::addBreadcrumb(trans("messages.global.breadcrumbs_dashboard"),URL::to('admin/dashboard'));
		Breadcrumb::addBreadcrumb(trans("messages.ads.breadcrumbs_Ads_module"),URL::to('admin/ads-manager'));
		Breadcrumb::addBreadcrumb(trans("messages.ads.breadcrumbs_Ads_add"),'');
		$breadcrumbs 	= 	Breadcrumb::generate();
		
		return  View::make('admin.Ads.add',compact('breadcrumbs')); 
	}// end addAds()
	
 /**
 * Function for save ads
 *
 * @param null
 *
 * @return redirect page. 
 */	

	public function saveAds(){
		$validationRules	= array(
			'ad_name' => 'required',
			'ad_image' => 'required|mimes:jpeg,jpg,png,gif',
			'from' => 'required',
			'to' => 'required',
		);
		
		// this is use  for validation
		$validator = Validator::make(
			Input::all(),
			$validationRules
		);
	
		//  if  validation error then redirect with error otherwise result will be save
		if ($validator->fails()){
			return Redirect::back()
				->withErrors($validator)->withInput();	
				
		}else{
			
			$extension 	=	 Input::file('ad_image')->getClientOriginalExtension();
			$filead_name	=	time().'-Ads-ad_image.'.$extension;
			if(Input::file('ad_image')->move(ADS_IMAGE_ROOT_PATH, $filead_name)){
				
				Ads::insert(
					array(
						'ad_name' 		=> Input::get('ad_name'),
						'ad_image' 		=> $filead_name,
						'ad_link' 		=> Input::get('link'),
						'ads_from' 		=> Input::get('from'),
						'ads_to' 		=> Input::get('to'),
						'created_at' 	=> DB::raw('NOW()'),
						'updated_at' 	=> DB::raw('NOW()'),
					)
				);
				//   flash message will be  display message	
				Session::flash('flash_notice', trans("messages.ads.Ads_add"));
				return Redirect::to('admin/ads-manager');
			}
		}
		return  View::make('admin.adsmanager.add',array('data' => $data)); 
	}// end saveAds()
	
 /**
 * Function for display edit ads page 
 *
 * @param $AdsId as id of ad
 *
 * @return view page. 
 */	
	
	public function editAds($AdsId = 0){
		//  Breadcrumbs 
		Breadcrumb::addBreadcrumb(trans("messages.global.breadcrumbs_dashboard"),URL::to('admin/dashboard'));
		Breadcrumb::addBreadcrumb(trans("messages.ads.breadcrumbs_Ads_module"),URL::to('admin/ads-manager'));
		Breadcrumb::addBreadcrumb(trans("messages.ads.breadcrumbs_Ads_edit"),'');
		$breadcrumbs 	= 	Breadcrumb::generate();
		
		
		$result			=	Ads::where('id', '=', $AdsId)->get();		
		return  View::make('admin.Ads.edit',compact('breadcrumbs', 'result')); 
	}//end editAds()
	
 /**
 * Function for  update ads
 *
 * @param null
 *
 * @return redirect page. 
 */	
	public function updateAds(){
		$validationRules	=  array(
				'ad_name' => 'required',
				'ad_image' => 'mimes:jpeg,jpg,png,gif',
		);
		$validator = Validator::make(
			Input::all(),
			$validationRules
		);
		if ($validator->fails()){
			return Redirect::to('admin/ads-manager/edit-ads/'.Input::get('id'))
				->withErrors($validator)->withInput();	
				
		}else{
			if(Input::file('ad_image')){
				$extension 		=	 Input::file('ad_image')->getClientOriginalExtension();
				$filead_name		=	time().'-Ads-ad_image.'.$extension;
				Input::file('ad_image')->move(ADS_IMAGE_ROOT_PATH, $filead_name);
				
				$result			=	Ads::where('id', '=', Input::get('id'))->first();
				//  delete  old image
				@unlink(ADS_IMAGE_ROOT_PATH.$result->ad_image);
				
			}else{
				$AdsDetail		=	Ads::where('id', '=', Input::get('id'))->get();
				$filead_name	=	$AdsDetail[0]->ad_image;
			}
				
			Ads::where('id', Input::get('id'))
				->update(
					array(
					'ad_name' => Input::get('ad_name'),
					'ad_image' => $filead_name,
					'ad_link' => Input::get('link'),

					'ads_from' => Input::get('from'),
					'ads_to' => Input::get('to'),
					'updated_at' 	=> DB::raw('NOW()')
				));	
			
			Session::flash('flash_notice', trans('messages.ads.Ads_update')); 
			return Redirect::to('admin/ads-manager');
			
		}
	}// end updateAds()
	
 /**
 * Function for delete ads
 *
 * @param $AdsId as id of ad
 *
 * @return redirect page. 
 */	
 
	public function deleteAds($AdsId = 0){
		if($AdsId){
			$result			=	DB::table('ads')->where('id', '=', $AdsId)->first();
			@unlink(ADS_IMAGE_ROOT_PATH.$result->ad_image);
			$ads	=	Ads::find($AdsId);
			$ads->delete();
			//DB::table('ads_pages')->where('ad_id',$AdsId)->delete();
			Session::flash('flash_notice',trans('messages.ads.Ads_delete')); 
		}
		return Redirect::to('admin/ads-manager');
	}// end deleteAds()
	
 /**
 * Function for update ads status
 *
 * @param $AdsId 	as id of ad
 * @param $Adstatus as status of ad
 *
 * @return redirect page. 
 */	
 
	public function updateAdstatus($AdsId = 0, $Adstatus = 0){
		Ads::where('id', '=', $AdsId)->update(array('status' => $Adstatus));
		Session::flash('flash_notice', trans('messages.ads.Ads_status')); 
		return Redirect::to('admin/ads-manager');
	} //end  updateAdstatus()
	
	
 /**
 * Function for show list page and  ads on page
 *
 * @param null
 *
 * @return view page. 
 */	
 
	public function listAdsPage(){
		//  Breadcrumbs 
		Breadcrumb::addBreadcrumb('Dashboard',URL::to('admin/dashboard'));
		Breadcrumb::addBreadcrumb('List Ads Page',URL::to('admin/adspage-manager'));
		$breadcrumbs 	= 	Breadcrumb::generate();
		
		$DB = DB::table('ads_pages');
		
		$searchVariable	=	array(); 
		$inputGet		=	Input::get();
		
		
		if (Input::get() && isset($inputGet['display']) || isset($inputGet['page']) ) {
			$search = true;
			$searchData	=	Input::get();
			unset($searchData['display']);
			unset($searchData['_token']);
			
			if(isset($searchData['page'])){
				unset($searchData['page']);
			}
			
			foreach($searchData as $fieldad_name => $fieldValue){
				if(!empty($fieldValue)){
						 $DB_ads = DB::table('ads');
					     $id_of_searchs=$DB_ads->where("$fieldad_name",'like','%'.$fieldValue.'%')->lists('id');
						 if(!empty($id_of_searchs)){
						 $DB->whereIn("ad_id",$id_of_searchs);
						 }else{
							$DB->where("ad_id",'$');
						 }
					    $searchVariable	=	array_merge($searchVariable,array($fieldad_name => $fieldValue));
				}
			}
		}
		$sortBy = (Input::get('sortBy')) ? Input::get('sortBy') : 'updated_at';
	    $order  = (Input::get('order')) ? Input::get('order')   : 'DESC';
		
		$result 	= 	$DB->orderBy($sortBy, $order)->paginate(Config::get("Reading.records_per_page"));

		return  View::make('admin.adsmanager.indexpage', compact('data','result' ,'searchVariable','sortBy','order','breadcrumbs'));
	}// end listAdsPage()
	
 /**
 * Function for display page, for add ads on page 
 *
 * @param null
 *
 * @return view page. 
 */	
	public function addAdsPage(){
		Breadcrumb::addBreadcrumb('Dashboard',URL::to('admin/dashboard'));
		Breadcrumb::addBreadcrumb('List Ads Page',URL::to('admin/adspage-manager'));
		Breadcrumb::addBreadcrumb('Add Ads Page');
		$breadcrumbs 	= 	Breadcrumb::generate();
		
		$ad_names_array		=	 DB::table('ads')->where('is_active','1')->lists('ad_name','id');
		
		$page_names_array	=	 DB::table('pages')->where('status','1')->lists('name','id');
		
		return  View::make('admin.adsmanager.addpage',compact('breadcrumbs','page_names_array','ad_names_array')); 
	}// end addAdsPage()
	
 /**
 * Function for save ads on page
 *
 * @param null
 *
 * @return redirect page. 
 */	
	public function saveAdsPage(){
	
		$validator = Validator::make(
			Input::all(),
			array(
				'page_name' => 'required',
				'ads_name' => 'required',
			)
		);
		
		if ($validator->fails()){
			return Redirect::to('admin/adspage-manager/add-ads')
				->withErrors($validator)->withInput();	
				
		}else{
			$string_page_name	='##'.implode('##', Input::get('page_name')).'##';
			 
			DB::table('ads_pages')->insert(
				array(
					'page_id' =>$string_page_name,
					'ad_id' => Input::get('ads_name'),
					'created_at' 	=> DB::raw('NOW()')
				)
			);
			
			Session::flash('flash_notice', trans('messages.adspage_manager.adspage_add_msg')); 
			return Redirect::to('admin/adspage-manager');
		}
	}// end saveAdsPage()
	
 /**
 * Function for show page for edit Ads added on page 
 *
 * @param $AdsId as id of ad
 *
 * @return view page. 
 */	
	public function editAdsPage($AdsId = 0){

		Breadcrumb::addBreadcrumb('Dashboard',URL::to('admin/dashboard'));
		Breadcrumb::addBreadcrumb('List Ads Page',URL::to('admin/adspage-manager'));
		Breadcrumb::addBreadcrumb('Edit Ads Page');
		$breadcrumbs 	= 	Breadcrumb::generate();
		
		$ad_names_array		=	 DB::table('ads')->lists('ad_name','id');
		$page_names_array	=	 DB::table('pages')->lists('name','id');
		
		$result			    =	DB::table('ads_pages')->where('id', '=', $AdsId)->get();
		
		return View::make('admin.adsmanager.editpage',compact('breadcrumbs', 'result','ad_names_array','page_names_array')); 
	}// end editAdsPage()
	
 /**
 * Function for update ads added  on page
 *
 * @param null
 *
 * @return redirect page. 
 */	
	public function updateAdsPage(){
	
		$validator = Validator::make(
			Input::all(),
			array(
				'page_name' => 'required',
				'ads_name' => 'required',
			)
		);
	
		if ($validator->fails()){
			return Redirect::to('admin/adspage-manager/edit-ads/'.Input::get('id'))
				->withErrors($validator)->withInput();	
		}else{		
			  $string_page_name	=	'##'.implode('##', Input::get('page_name')).'##';
			 DB::table('ads_pages')
				->where('id', Input::get('id'))
				->update(
					array(
						'page_id' =>$string_page_name,
						'ad_id' => Input::get('ads_name'),
						'updated_at' 	=> DB::raw('NOW()')
			));	
			
			Session::flash('flash_notice', trans('messages.adspage_manager.adspage_edit_msg')); 
			return Redirect::to('admin/adspage-manager');
			
		}
	}// end updateAdsPage()
	
 /**
 * Function for delete ads added on page
 *
 * @param $AdsId as id of ad
 *
 * @return redirect page. 
 */	
 
	public function deleteAdsPage($AdsId = 0){
		if($AdsId){
			$result			=	DB::table('ads_pages')->where('id', '=', $AdsId)->first();
			@unlink(ADS_IMAGE_ROOT_PATH.$result->ad_image);
			
			DB::table('ads_pages')->delete($AdsId);
			Session::flash('flash_notice', trans('messages.adspage_manager.adspage_delete_msg')); 
		}
		return Redirect::to('admin/adspage-manager');
	}// end deleteAdsPage()	
}
