<?php
namespace App\Http\Controllers\admin;

use App\Http\Controllers\BaseController;
use App\Model\Hcp;
use App\Model\Company;
use App\Model\Language;
use App\Model\DestinationCountry;
use App\Model\DestinationCountryDescription;
use mjanssen\BreadcrumbsBundle\Breadcrumbs as Breadcrumb;
use Auth,Blade,Config,Cache,Cookie,DB,File,Hash,Input,Mail,mongoDate,Redirect,Request,Response,Session,URL,View,Validator;

/**
 * Hcp Controller
 *
 * Add your methods in the class below
 *
 * This file will render views from views/admin/HCP
 */
 
class DestinationCountryController extends BaseController {

	public $model	=	'DestinationCountry';
	
	public function __construct() {
		View::share('modelName',$this->model);
	}

	/**
	* Function for display all DestinationCountry
	*
	* @param null
	*
	* @return view page. 
	*/
 
	public function listDestinationCountry(){	
	
		### breadcrumbs Start ###
		// Breadcrums   is  added   here dynamically
		Breadcrumb::addBreadcrumb(trans("messages.system_management.dashboard"),URL::to('admin/dashboard'));
		Breadcrumb::addBreadcrumb(trans("Destination Country"),'');
		$breadcrumbs 	= 	Breadcrumb::generate();
		### breadcrumbs End ###
		
		$DB	=	DestinationCountry::query();
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

		// $result = $DB->orderBy($sortBy, $order)
		// 			 ->paginate(Config::get("Reading.records_per_page"));
		$result = $DB->orderBy('name','asc')
					 ->paginate(Config::get("Reading.records_per_page"));
		//$location_list = DB::table('locations')->lists('Name','id');



		return  View::make('admin.DestinationCountry.index',compact('breadcrumbs','result','searchVariable','sortBy','order'));
	}// end listHcp()



	/**
	* Function for display page  for add new Hcp
	*
	* @param null
	*
	* @return view page. 
	*/
	public function addDestinationCountry(){
		
		### breadcrumbs Start ###
		// Breadcrums   is  added   here dynamically
		Breadcrumb::addBreadcrumb(trans("messages.system_management.dashboard"),URL::to('admin/dashboard'));
		Breadcrumb::addBreadcrumb(trans("Destination Country"),URL::to('admin/list-destination-country'));
		Breadcrumb::addBreadcrumb(trans("Add New Destination Country"),'');
		$breadcrumbs 	= 	Breadcrumb::generate();
		### breadcrumbs End ###

		$languages	=	Language::where('is_active', '=', ACTIVE)->get(array('title','id'));
		$language_code		=	Config::get('default_language.language_code');		
		
		$regionList = DB::table('region')->lists('name','id');

		return  View::make('admin.DestinationCountry.add',compact('breadcrumbs','regionList','languages' ,'language_code'));
	} //end addHcp()


	
	/**
	* Function for save added Destination Country
	*
	* @param null
	*
	* @return redirect page. 
	*/
	function saveDestinationCountry(){
		ini_set('post_max_size',250);
		$thisData				=	Input::all();

		$default_language		=	Config::get('default_language');
		$language_code 			=   $default_language['language_code'];
		$dafaultLanguageArray	=	$thisData['data'][$language_code];

		$validator = Validator::make(
			array(
				'region_name' 		=> Input::get('region_name'),
				'image'				=> Input::file('image'),
				'header_image'		=> Input::file('header_image'),
				'name' 				=> $dafaultLanguageArray['name'],
				'heading' 			=> $dafaultLanguageArray['heading'],
				'description' 		=> $dafaultLanguageArray['description'],
				'countryinfo' 		=> $dafaultLanguageArray['countryinfo'],
				'art_architecture' 	=> $dafaultLanguageArray['art_architecture'],
				'nature' 			=> $dafaultLanguageArray['nature'],
				'travel' 			=> $dafaultLanguageArray['travel'],

			),
			array(
				'region_name' 		=> 'required',
				'image'				=> 'required|image|mimes:jpeg,png,bmp,gif,svg',
				'header_image'		=> 'required|image|mimes:jpeg,png,bmp,gif,svg',
				'name' 				=> 'required',
				'heading' 			=> 'required',
				'description' 		=> 'required',
				'countryinfo' 		=> 'required',
				'art_architecture' 	=> 'required',
				'nature' 			=> 'required',
				'travel' 			=> 'required',

			)
		);
		
		if ($validator->fails())
		{  return Redirect::back()->withErrors($validator)->withInput();

		}else{

			$obj 	 			= new DestinationCountry;
			$obj->region_id  	= Input::get('region_name');
			$obj->name  	= $dafaultLanguageArray['name'];
			$obj->heading  	= $dafaultLanguageArray['heading'];
			$obj->description  	= $dafaultLanguageArray['description'];
			$obj->countryinfo  	= $dafaultLanguageArray['countryinfo'];
			$obj->art_architecture  	= $dafaultLanguageArray['art_architecture'];
			$obj->nature  	= $dafaultLanguageArray['nature'];
			$obj->travel  	= $dafaultLanguageArray['travel'];
			$obj->slug	 			=  $this->getSlug($obj->name,'slug','DestinationCountry');

			if(input::hasFile('image')){
					$extension 	=	 Input::file('image')->getClientOriginalExtension();
					$fileName	=	time().'-country-image.'.$extension;
					
					if(Input::file('image')->move(COUNTRY_IMAGE_ROOT_PATH, $fileName)){
						$obj->image			=	$fileName;
					}
				}

			if(input::hasFile('header_image')){
					$extension_hwe 	=	 Input::file('header_image')->getClientOriginalExtension();
					$fileName_Hwe	=	time().'-country-header_image.'.$extension_hwe;
					
					if(Input::file('header_image')->move(HEADER_COUNTRY_IMAGE_ROOT_PATH, $fileName_Hwe)){
						$obj->header_image			=	$fileName_Hwe;
					}
				}
				
			$obj->save();
			$modelId	=	$obj->id;
							
				foreach ($thisData['data'] as $language_id => $descriptionResult) {
					if (is_array($descriptionResult))
						foreach ($descriptionResult as $key => $value) {
							DestinationCountryDescription::insert(
								array(
									'language_id'				=>	$language_id,
									'parent_id'					=>	$modelId,
									'source_col_name'			=>	$key,
									'source_col_description'	=>	$value,
								)
							);
						}
				}
			
			Session::flash('flash_notice', trans("Destination Country added successfully!")); 
			return Redirect::to('admin/list-destination-country');
		}
	}//end saveHcp()



	/**
	* Function for display page  for edit Hcp
	*
	* @param $Id ad id of Hcp
	*
	* @return view page. 
	*/	
	public function editDestinationCountry($Id){

		$DestinationCountry		  =	DestinationCountry::find($Id);
		$DestinationCountryDescription	=	DestinationCountryDescription::where('parent_id', '=',  $Id)->get();
	
		### breadcrumbs Start ###
		// Breadcrums   is  added   here dynamically
		Breadcrumb::addBreadcrumb(trans("messages.system_management.dashboard"),URL::to('admin/dashboard'));
		Breadcrumb::addBreadcrumb(trans("Destination Country"),URL::to('admin/list-destination-country'));
		Breadcrumb::addBreadcrumb(trans("Edit Destination Country"),'');
		$breadcrumbs 	= 	Breadcrumb::generate();
		
		### breadcrumbs End ###

		$multiLanguage		=	array();
		
		if(!empty($DestinationCountryDescription)){
			foreach($DestinationCountryDescription as $description) {
				$multiLanguage[$description->language_id][$description -> source_col_name]	=	$description->source_col_description;						
			}
		}
		
		$regionList  = DB::table('region')->lists('name','id');

		$languages				=	Language::where('is_active', '=', ACTIVE)->get(array('title','id'));
		$language_code			=	Config::get('default_language.language_code');

		return  View::make('admin.DestinationCountry.edit',compact('DestinationCountry','breadcrumbs','languages','language_code','multiLanguage','regionList'));
	}// end editHcp()



	/**
	* Function for update cms page
	*
	* @param $Id ad id of cms page
	*
	* @return redirect page. 
	*/
	function updateDestinationCountry($Id){
		ini_set('post_max_size',250);
			$thisData				=	Input::all();

			$default_language		=	Config::get('default_language');
			$obj 					= 	DestinationCountry:: findorFail($Id);
			$activeLanguageCode		=	Config::get('default_language.language_code');
			$dafaultLanguageArray	=	$thisData['data'][$activeLanguageCode];
		
		$validator = Validator::make(
			array(
				'region_name' 		=> Input::get('region_name'),
				'image'				=> Input::file('image'),
				'header_image'		=> Input::file('header_image'),
				'name' 				=> $dafaultLanguageArray['name'],
				'heading' 			=> $dafaultLanguageArray['heading'],
				'description' 		=> $dafaultLanguageArray['description'],
				'countryinfo' 		=> $dafaultLanguageArray['countryinfo'],
				'art_architecture' 	=> 'required',
				'nature' 			=> 'required',
				'travel' 			=> 'required',

			),
			array(
				'region_name' 		=> 'required',
				'image'				=> 'image|mimes:jpeg,png,bmp,gif,svg',
				'header_image'		=> 'image|mimes:jpeg,png,bmp,gif,svg|max:5000kb',
				//'header_image'		=> 'dimensions:min_width=1280,min_height=500',
				'name' 				=> 'required',
				'heading' 			=> 'required',
				'description' 		=> 'required',
				'countryinfo' 		=> 'required',
				'art_architecture' 	=> 'required',
				'nature' 			=> 'required',
				'travel' 			=> 'required',

			)
		);
		/*$fileinfo = @getimagesize(Input::file('header_image'));
		$width = $fileinfo[0];
   		$height = $fileinfo[1];
   		*/

		if ($validator->fails())
		{  return Redirect::back()->withErrors($validator)->withInput();

		}else{
			$obj->region_id  	= Input::get('region_name');
			$obj->name  	= $dafaultLanguageArray['name'];
			$obj->heading  	= $dafaultLanguageArray['heading'];
			$obj->description  	= $dafaultLanguageArray['description'];
			$obj->countryinfo  	= $dafaultLanguageArray['countryinfo'];
			$obj->art_architecture  	= $dafaultLanguageArray['art_architecture'];
			$obj->nature  	= $dafaultLanguageArray['nature'];
			$obj->travel  	= $dafaultLanguageArray['travel'];

			$obj->slug	 			=  $this->getSlug($obj->name,'slug','DestinationCountry');

			if(input::hasFile('image')){
				$extension 	=	 Input::file('image')->getClientOriginalExtension();

				$fileName	=	time().'-destination-country-image.'.$extension;
			
				if(Input::file('image')->move(COUNTRY_IMAGE_ROOT_PATH, $fileName)){
					$obj->image			=	$fileName;
				}
				$image 			=	DestinationCountry::where('id',$Id)->pluck('image');
				@unlink(COUNTRY_IMAGE_ROOT_PATH.$image);
			}

			if(input::hasFile('header_image')){
				$extension 	=	 Input::file('header_image')->getClientOriginalExtension();
				$fileName	=	time().'-destination-header-country-image.'.$extension;
			
				if(Input::file('header_image')->move(HEADER_COUNTRY_IMAGE_ROOT_PATH, $fileName)){
					$obj->header_image			=	$fileName;
				}
				$image 			=	DestinationCountry::where('id',$Id)->pluck('header_image');
				@unlink(HEADER_COUNTRY_IMAGE_ROOT_PATH.$image);
			}



			$obj->save();
			DestinationCountryDescription::where('parent_id', '=', $Id)->delete();
							
				foreach ($thisData['data'] as $language_id => $descriptionResult) {
					if (is_array($descriptionResult))
						foreach ($descriptionResult as $key => $value) {
							DestinationCountryDescription::insert(
								array(
									'language_id'				=>	$language_id,
									'parent_id'				=>	$Id,
									'source_col_name'			=>	$key,
									'source_col_description'	=>	$value,
								)
							);
						}
				}
			
			Session::flash('flash_notice', trans("Destination Country update successfully!")); 
			return Redirect::to('admin/list-destination-country');
		}
	}// end updateCms()



	/**
	* Function for delete Hcp 
	*
	* @param $Id as id of Hcp 
	* @param $Status as status of Hcp page
	*
	* @return redirect page. 
	*/	
	public function deleteDestinationCountry($Id = 0){
		
		$deleteDestinationCountry = DestinationCountry::where('id', '=', $Id)->delete();

		Session::flash('flash_notice',  trans("Destination Country deleted successfully!")); 
		return Redirect::to('admin/list-destination-country');
	}// end updateCmstatus()




	/**
	* Function for display Hcp detail
	*
	* @param $userId 	as id of Hcp
	*
	* @return view page. 
	*/
	public function viewDestinationCountry($Id = 0){
	
		Breadcrumb::addBreadcrumb(trans("messages.user_management.dashboard"),URL::to('admin/dashboard'));
		Breadcrumb::addBreadcrumb(trans("Destination Country"),URL::to('admin/list-destination-country'));
		Breadcrumb::addBreadcrumb(trans("messages.user_management.view"),Request::url());
		$breadcrumbs 	= 	Breadcrumb::generate();
		
		if($Id){
			$DestinationCountryDetails		= DestinationCountry::find($Id); 			
			$companyDetails = Company::where('id',$hcpDetails->company_id)->first();
			return View::make('admin.Hcp.view', compact('DestinationCountryDetails','breadcrumbs','companyDetails'));
		}
	} // end viewHcp()

	
	function updateStatus($id){
		$currentStatus = DestinationCountry::where('id',$id)->select('active')->first();
		$newStatus = ($currentStatus->active ==1)?0:1;
		$data = array();
		$data['active'] = $newStatus;
		DestinationCountry::where('id',$id)->update($data);
		return Redirect::to('admin/list-destination-country');
	}

	function markHighlight($id){
		$currentHighlight = DestinationCountry::where('id',$id)->select('is_highlight')->first();
		$newHighlight = ($currentHighlight->is_highlight ==1)?0:1;
		$data = array();
		$data['is_highlight'] = $newHighlight;
		DestinationCountry::where('id',$id)->update($data);
		//Session::flash('success', trans("messages.destination_country_highlight_success"));
		return Redirect::to('admin/list-destination-country');
	}
	
}// end HcpController()
