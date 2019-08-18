<?php
namespace App\Http\Controllers\admin;

use App\Http\Controllers\BaseController;
use App\Model\Menu;
use App\Model\User;
use mjanssen\BreadcrumbsBundle\Breadcrumbs as Breadcrumb;
use Auth,Blade,Config,Cache,Cookie,DB,File,Hash,Input,Mail,mongoDate,Redirect,Request,Response,Session,URL,View,Validator;
/**

 * Menus Controller
 *
 * Add your methods in the class below
 *
 * This file will render views from views/admin/menus
 */
 
class MenusController extends BaseController {

 /**
 * Function for display list of all menus
 *
 * @param $type for identified menu or unidentified menu 
 *
 * @return view page. 
 */
 
	public function listMenus($type = 'identified'){
		Breadcrumb::addBreadcrumb(trans("messages.system_management.dashboard"),URL::to('admin/dashboard'));
		Breadcrumb::addBreadcrumb($type.' Menus','');
		$breadcrumbs=Breadcrumb::generate();
		
		$DB = Menu::query();
		
		$searchVariable	=	array(); 
		$inputGet		=	Input::get();
		
		// search result 
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
		// sorting and order arrangement
		$sortBy = (Input::get('sortBy')) ? Input::get('sortBy') : 'updated_at';
	    $order  = (Input::get('order')) ? Input::get('order')   : 'DESC';
		
		$result 	= 	$DB->where('type' ,$type)->orderBy($sortBy, $order)->paginate(Config::get("Reading.records_per_page"));
	
		// menu  file write 
		//$this->menuFileWrite();
		return  View::make('admin.menus.index', compact('breadcrumbs','result' ,'searchVariable','sortBy','order','type'));
	}// end listMenus()

 /**
 * Function for display page  for add menu
 *
 * @param $type for identified menu or unidentified menu 
 *
 * @return view page. 
 */
	public function addMenu($type='identified'){
		Breadcrumb::addBreadcrumb(trans("messages.system_management.dashboard"),URL::to('admin/dashboard'));
		Breadcrumb::addBreadcrumb($type.' Menus',URL::to('admin/menus/'.$type));
		Breadcrumb::addBreadcrumb(trans("messages.system_management.add"),'');
		$breadcrumbs=Breadcrumb::generate();
		
		$listMenu	=	Menu::select('url','menu_name','id')
							->where('is_active',1)
							->where('type','header')
							->where('parent_id',0)
							->orderBy('order','asc')
							->lists('menu_name','id')->toArray();
		
		return  View::make('admin.menus.add',array('type'=>$type,'breadcrumbs'=>$breadcrumbs,'listMenu'=>$listMenu)); 
	}// end addMenu()
 
 /**
 * Function for save created menu
 *
 * @param $type for identified menu or unidentified menu 
 *
 * @return redirect page. 
 */
 	public function saveMenu($type=''){
		$order=Input::get('order');
		
		// check order  already  exist or not 
		$menu = Menu::where('order', '=',$order)
                    ->Where('type','=',$type)
                    ->get();
					
		if($menu){
			$validator = Validator::make(
				Input::all(),
				array(
					'menu_name' => 'required',
					'url' => 'required',
					'order' => 'required|numeric',
				)
			);
		}else{		
			$validator = Validator::make(
				Input::all(),
				array(
					'menu_name' => 'required',
					'url' => 'required',
					'order' => 'required|numeric',
				)
			);
		}
	
		if ($validator->fails()){
			return Redirect::to("admin/menus/$type/add-menu")
				->withErrors($validator)->withInput();	
		}else{ 
				Menu::insert(
					array(
						'menu_name' => Input::get('menu_name'),
						'parent_id' => Input::get('parent_id'),
						'url' => Input::get('url'),
						'order' => Input::get('order'),
						'type' =>$type,
						'is_active'=>1
					)
				);
				
				Session::flash('flash_notice',trans("messages.system_management.menu_added_message")); 
				return Redirect::to("admin/menus/$type");
			
		}
		// menu  file write 
		$this->menuFileWrite();
		return  View::make('admin.menus.add',array('data' => $data)); 
	}// end saveMenu()

 /**
 * Function for display page for edit menu
 *
 * @param $menuId as id of menu
 * @param $type for identified menu or unidentified menu 
 *
 * @return view page. 
 */
	public function editMenu($menuId = 0,$type='identified'){
	
		Breadcrumb::addBreadcrumb(trans("messages.system_management.dashboard"),URL::to('admin/dashboard'));
		Breadcrumb::addBreadcrumb($type.' Menus',URL::to('admin/menus/'.$type	));
		Breadcrumb::addBreadcrumb(trans("messages.system_management.edit"),'');
		$breadcrumbs=Breadcrumb::generate();
				
		$menusDetail	=	Menu::where('id', '=', $menuId)->get();
		return  View::make('admin.menus.edit',array('menuid'=>$menuId,'type'=>$type, 'result' => $menusDetail,'breadcrumbs'=>$breadcrumbs)); 
	}// end editMenu()
	
 /**
 * Function for save updated  menu
 *
 * @param $menuId as id of menu
 * @param $type for identified menu or unidentified menu 
 *
 * @return redirect page. 
 */
	public function updateMenu($menuId='0',$type='identified'){
		$validator = Validator::make(
			Input::all(),
			array(
				'menu_name' => 'required',
			)
		);
	
		if ($validator->fails()){
			return Redirect::to('admin/menus/edit-menu/'.$menuId.'/'.$type)
				->withErrors($validator)->withInput();	
				
		}else{
				Menu::where('id', $menuId)
				->update(
					array(
					'menu_name' => Input::get('menu_name'),
					'updated_at' 	=> DB::raw('NOW()')
				));	
			// menu  file write 
			$this->menuFileWrite();
			Session::flash('flash_notice', trans("messages.system_management.menu_updated_message")); 
			return Redirect::to('admin/menus/'.$type);
		}
	}// end updateMenu()
	
 /**
 * Function for delete menu
 *
 * @param $menuId as id of menu
 * @param $type for identified menu or unidentified menu 
 *
 * @return redirect page. 
 */
	public function deleteMenu($menuId = 0,$type=''){
		if($menuId){
			Menu::delete($menuId);
			Session::flash('flash_notice',trans("messages.management.menu_delete_msg")); 
		}
		// menu  file write 
		$this->menuFileWrite();
		return Redirect::to("admin/menus/$type");
	}// end deleteMenu()

 /**
 * Function for use update client status
 *
 @param $menuId as id of menu
 @param $menuStatus as status of menu
 @param $type for identified menu or unidentified menu 
 *
 * @return redirect page. 
 */
 	public function updateClientStatus($menuId = 0, $menuStatus = 0,$type=''){
		Menu::where('id', '=', $menuId)->update(array('is_active' => $menuStatus));
		Session::flash('flash_notice', trans("messages.management.menu_status_msg")); 
		return Redirect::to("admin/menus/$type");
	}// end updateClientStatus()

 /**
 * Function for write file on add and edit menu
 *
 * @param null
 *
 * @return void. 
 */
	public function menuFileWrite() {
		/* query for lissst  menus */
		$listMenu	=	Menu::where('is_active',1)
							->where('type','header')
							->where('parent_id',0)
							->orderBy('order','asc')
							->get();	
							
		$menus		=  '';
		
		$start 		=	'array(';
		$end 		=	')';
		if(!empty($listMenu)){
			foreach($listMenu as $key=> $value){
				$slug	=	$value->url;
				$menu	=	$value->menu_name;
				
				$listSubMenu	=	array();
				$listSubMenu	=	Menu::where('is_active',1)->where('type','header')->where('parent_id',$value->id)->orderBy('order','asc')->get();	
				// pr($listSubMenu);
				
				$menuFile	= '';
				$submenus	= '';
				if(!empty($listSubMenu)){
					foreach($listSubMenu as $subKey=> $subMenuValue){
						
						$subMenuSlug	=	$subMenuValue->url;
						$subMenu		=	$subMenuValue->menu_name;
						$subkey			=	'"'.$subMenuSlug.'"';
						$subvalue		=	'"'.$subMenu.'"';
						$submenus		.= 	$subkey.'=>'.$subvalue.','."\n";
					}
						$file 			 = 	MENU_FILE_PATH;
						$menuFile 		 = '<?php ' . "\n";
						$menuFile 		.=  'Config::set("'.$slug.'", '.$start.$submenus.$end.');' . "\n";
						$menuFile 		.= '?> ' . "\n";
						$subMenuWritten  = 	File::put($file, $menuFile); 
				}
				$key			=	'"'.$slug.'"';
			    $value			=	'"'.$menu.'"';
				$menus.= $key.'=>'.$value.','."\n";
			}
		}
		$file 	= 	MENU_FILE_PATH;
		
		/* menu  file  write*/
		$settingfile 	=  '<?php ' . "\n";
		$settingfile   .=  'Config::set("menus", '.$start.$menus.$end.');' . "\n";

		$bytes_written = File::append($file, $settingfile);
		if ($bytes_written === false)
		{
			die("Error writing to file");
		}
	}// end menuFileWrite()

/**
 * Function for delete menu
 *
 * @param null
 *
 * @return redirect page. 
 */
 		
	public function performMultipleAction(){
		if(Request::ajax()){
			$actionType = ((Input::get('type'))) ? Input::get('type') : '';
			
			if(!empty($actionType) && !empty(Input::get('ids'))){
				if($actionType	==	'delete'){
					Menu::whereIn('id', Input::get('ids'))->delete();
					$this->menuFileWrite();
					Session::flash('flash_notice', 'Menu deleted successfully.'); 
				}
			}
		}
	}//end performMultipleAction()
	
}//end MenusController
