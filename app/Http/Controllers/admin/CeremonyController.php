<?php
namespace App\Http\Controllers\admin;
use App\Http\Controllers\BaseController;
use App\Model\Ceremony;
use mjanssen\BreadcrumbsBundle\Breadcrumbs as Breadcrumb;
use Auth, Blade, Config, Cache, Cookie, DB, File, Hash, Input, Mail, mongoDate, Redirect, Request, Response, Session, URL, View, Validator;

/**
 * Ceremony Controller 
 *
 * Add your methods in the class below
 *
 * This file will render views from views/admin/Ceremony
 */
 
class CeremonyController extends BaseController {
 
 /**
 * Function for display list of all Ceremony 
 *
 * @param null
 *
 * @return view page. 
 */	
	public function listCeremony(){
		// Breadcrums   is  added   here dynamically
		Breadcrumb::addBreadcrumb(trans("messages.global.breadcrumbs_dashboard"),URL::to('admin/dashboard'));
		Breadcrumb::addBreadcrumb(trans("messages.ceremony.breadcrumbs_Ceremony_module"),URL::to('admin/ceremony-manager'));
		$breadcrumbs 	= 	Breadcrumb::generate();
		
		$DB = Ceremony::query();
		
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
		return  View::make('admin.Ceremony.index', compact('breadcrumbs','result' ,'searchVariable','sortBy','order'));
	}// end listCeremony()

 /**
 * Function for display Ceremony add page 
 *
 * @param null
 *
 * @return view page. 
 */	
	public function addCeremony(){
		//  Breadcrumbs 
		Breadcrumb::addBreadcrumb(trans("messages.global.breadcrumbs_dashboard"),URL::to('admin/dashboard'));
		Breadcrumb::addBreadcrumb(trans("messages.ceremony.breadcrumbs_Ceremony_module"),URL::to('admin/ceremony-manager'));
		Breadcrumb::addBreadcrumb(trans("messages.ceremony.breadcrumbs_Ceremony_add"),'');
		$breadcrumbs 	= 	Breadcrumb::generate();
		
		return  View::make('admin.Ceremony.add',compact('breadcrumbs')); 
	}// end addCeremony()
	
 /**
 * Function for save Ceremony
 *
 * @param null
 *
 * @return redirect page. 
 */	

	public function saveCeremony(){


		$validationRules	= array(
			'name' 			=> 'required',
			'ceremony_for' => 'required',
			'description' => 'required',
			'date' => 'required|date',
			'total_seats' => 'required|min:1|integer',
			'price' => 'required|numeric|min:1',
			'address' => 'required',
			'image' => 'required',
			'image.*' => 'image|mimes:'.IMAGE_EXTENSION.'|max:2048'
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
			$imageArr = array();

			if(input::hasFile('image')){

					foreach(input::file('image') as $image){
						$extension 	=	 $image->getClientOriginalExtension();
						$fileName	=	mt_rand(99999, 999999) . time().'-event-image.'.$extension;
						
						if($image->move(CEREMONY_EVENT_ROOT_PATH, $fileName)){
							$imageArr[]	= $fileName;
						}
					}
				}

			$images = implode(',', $imageArr);

			Ceremony::insert(
					array(
						'name' 				=> Input::get('name'),
						'description' 		=> Input::get('description'),
						'address' 		=> Input::get('address'),
						'latitude' 		=> Input::get('latitude'),
						'longitude' 		=> Input::get('longitude'),
						'date' 				=> Input::get('date'),
						'total_seats' 		=> Input::get('total_seats'),
						'remaining_seats' 	=> Input::get('total_seats'),
						'price' 			=> Input::get('price'),
						'ceremony_for' 		=> Input::get('ceremony_for'),
						'image' 			=> $images,
						'status'			=> 1,
						'created_at' 		=> DB::raw('NOW()'),
						'updated_at' 		=> DB::raw('NOW()'),
				)
			);
				//   flash message will be  display message	
			Session::flash('flash_notice', trans("messages.ceremony.Ceremony_add"));
			return Redirect::to('admin/ceremony-manager');
			
		}
		return  View::make('admin.Ceremony.add',array('data' => $data)); 
	}// end saveCeremony()
	
 /**
 * Function for display edit Ceremony page 
 *
 * @param $CeremonyId as id of ad
 *
 * @return view page. 
 */	
	
	public function editCeremony($CeremonyId = 0){
		//  Breadcrumbs 
		Breadcrumb::addBreadcrumb(trans("messages.global.breadcrumbs_dashboard"),URL::to('admin/dashboard'));
		Breadcrumb::addBreadcrumb(trans("messages.ceremony.breadcrumbs_Ceremony_module"),URL::to('admin/ceremony-manager'));
		Breadcrumb::addBreadcrumb(trans("messages.ceremony.breadcrumbs_Ceremony_edit"),'');
		$breadcrumbs 	= 	Breadcrumb::generate();
		
		
		$result			=	Ceremony::where('id', '=', $CeremonyId)->get();		
		return  View::make('admin.Ceremony.edit',compact('breadcrumbs', 'result')); 
	}//end editCeremonys()
	
 /**
 * Function for  update Ceremony
 *
 * @param null
 *
 * @return redirect page. 
 */	
	public function updateCeremony(){
		$validationRules	=  array(
				'name' 			=> 'required',
				'ceremony_for'  => 'required',
				'date' => 'required',
				'description' => 'required',
				'address' => 'required',
				'price' => 'required|numeric|min:1'
		);
		$validator = Validator::make(
			Input::all(),
			$validationRules
		);
		if ($validator->fails()){
			return Redirect::to('admin/ceremony-manager/edit-ceremony/'.Input::get('id'))
				->withErrors($validator)->withInput();	
				
		}else{
			$CeremonyId = Input::get('id');
			$result	=	DB::table('ceremony')->where('id', '=', $CeremonyId)->first();
			$images = $result->image;
			if(!empty($images)){
				$imageArr = explode(',', $images);
			}else{
				$imageArr = array();
			}
			



			if(input::hasFile('image')){
				foreach(input::file('image') as $image){
						$extension 	=	 $image->getClientOriginalExtension();
						$fileName	=	mt_rand(99999, 999999) . time().'-event-image.'.$extension;
						
						if($image->move(CEREMONY_EVENT_ROOT_PATH, $fileName)){
							$imageArr[]	= $fileName;
						}
				}
			}

			$images = implode(',', $imageArr);



			Ceremony::where('id', Input::get('id'))
				->update(
					array(
					'name' => Input::get('name'),
					'description' => Input::get('description'),
					'address' => Input::get('address'),
					'latitude' => Input::get('latitude'),
					'longitude' => Input::get('longitude'),
					'date' => Input::get('date'),
					'image' => $images,
					'price' => Input::get('price'),
					'ceremony_for' 		=> Input::get('ceremony_for'),
					'updated_at' 	=> DB::raw('NOW()')
				));	
			
			Session::flash('flash_notice', trans('messages.ceremony.Ceremony_update')); 
			return Redirect::to('admin/ceremony-manager');
			
		}
	}// end updateCeremony()
	
 /**
 * Function for delete Ceremony
 *
 * @param $CeremonyId as id of ad
 *
 * @return redirect page. 
 */	
 
	public function deleteCeremony($CeremonyId = 0){
		if($CeremonyId){
			$result	=	DB::table('ceremony')->where('id', '=', $CeremonyId)->first();
			$ceremony	=	Ceremony::find($CeremonyId);
			$ceremony->delete();
			Session::flash('flash_notice',trans('messages.ceremony.Ceremony_delete')); 
		}
		return Redirect::to('admin/ceremony-manager');
	}// end deleteCeremony()


	public function deleteCeremonyImage($CeremonyId = 0, $CeremonyImage){
		if($CeremonyId != '' &&  $CeremonyImage != ''){
			$result	=	DB::table('ceremony')->where('id', '=', $CeremonyId)->first();
			if($result){
				$images = $result->image;
				$imgArray = explode(',', $images);

				$key = array_search($CeremonyImage, $imgArray);
				if($key !== null){
					unset($imgArray[$key]);
					@unlink(CEREMONY_EVENT_ROOT_PATH.$CeremonyImage);
				}

				$updatedImages = implode(',', $imgArray);

				Ceremony::where('id', $CeremonyId)
				->update(
					array(
					'image' => $updatedImages,
				));

				Session::flash('flash_notice','Image Has been deleted successfully'); 
				return Redirect::to('admin/ceremony-manager/edit-ceremony/'.$CeremonyId);
			}
			
		}
		return Redirect::to('admin/ceremony-manager');
	}// end deleteCeremony()
	
 /**
 * Function for update Ceremony status
 *
 * @param $CeremonyId 	as id of ad
 * @param $Ceremonystatus as status of ad
 *
 * @return redirect page. 
 */	
 
	public function updateCeremonystatus($CeremonyId = 0, $Ceremonytatus = 0){
		Ceremony::where('id', '=', $CeremonyId)->update(array('status' => $Ceremonytatus));
		Session::flash('flash_notice', trans('messages.ceremony.Ceremony_status')); 
		return Redirect::to('admin/ceremony-manager');
	} //end  updateCeremonystatus()
	
	
 
}
