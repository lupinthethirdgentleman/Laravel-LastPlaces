<?php

namespace App\Http\Controllers\admin;


use App\Http\Requests;
use App\Http\Controllers\BaseController;
use App\Model\AdminUser;
use App\Model\User;
use App\Model\Region;
use App\Model\Language;
use App\Model\NewsLettersubscriber;
use App\Model\EmailTemplate;
use App\Model\EmailAction;
use App\Model\RegionDescription;
use mjanssen\BreadcrumbsBundle\Breadcrumbs as Breadcrumb;
use Auth,Blade,Config,Cache,Cookie,DB,File,Hash,Input,Mail,mongoDate,Redirect,Request,Response,Session,URL,View,Validator;

class RegionController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
        public $model   =   'Region';

        public function __construct() {
            View::share('modelName',$this->model);
        }

    public function index()
    {
            ### breadcrumbs Start ###
        Breadcrumb::addBreadcrumb(trans("messages.global.breadcrumbs_dashboard"),route('admin_dashboard'));
        Breadcrumb::addBreadcrumb(trans("messages.$this->model.breadcrumbs_module"),route($this->model.'.index'));
        $breadcrumbs    =   Breadcrumb::generate();
        ### breadcrumbs End ###

        $DB             =   Region::query();
        $searchVariable =   array(); 
        $inputGet       =   Input::get();
        ## Searching on the basis of comment ##
        ## Searching on the basis of comment ##
        if ((Input::get() && isset($inputGet['display'])) || isset($inputGet['page']) ) {
            $searchData =   Input::get();
            unset($searchData['display']);
            unset($searchData['_token']);
            if(isset($searchData['page'])){
                unset($searchData['page']);
            }
            
            foreach($searchData as $fieldName => $fieldValue){
                if(!empty($fieldValue)){
                    $DB->where("$fieldName",'like','%'.$fieldValue.'%');
                    $searchVariable =   array_merge($searchVariable,array($fieldName => $fieldValue));
                }
            }
        }

        $sortBy = (Input::get('sortBy')) ? Input::get('sortBy') : 'updated_at';
        $order  = (Input::get('order')) ? Input::get('order')   : 'DESC';
        $model = $DB->orderBy($sortBy, $order)->paginate(Config::get("Reading.records_per_page"));

        return View::make('admin.Region.index',compact('breadcrumbs','model','searchVariable','sortBy','order'));
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
            ### breadcrumbs Start ###
        // Breadcrums   is  added   here dynamically
        Breadcrumb::addBreadcrumb(trans("messages.global.breadcrumbs_dashboard"),route('admin_dashboard'));
        Breadcrumb::addBreadcrumb(trans("messages.$this->model.breadcrumbs_module"),route($this->model.'.index'));
        Breadcrumb::addBreadcrumb(trans("messages.$this->model.breadcrumbs_add"),'');
        $breadcrumbs    =   Breadcrumb::generate();
        ### breadcrumbs End ###
        
        $languages          =   Language::where('is_active', '=',1)->get(array('title','id'));
        $language_code      =   Config::get('default_language.language_code');
        return  View::make("admin.$this->model.add",compact('languages' ,'language_code','breadcrumbs'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $thisData               =   Input::all();
        $default_language       =   Config::get('default_language');
        $language_code          =   $default_language['language_code'];
        $dafaultLanguageArray   =   $thisData['data'][$language_code];
      //  echo "<pre>";
       // print_r($thisData);

        $validator = Validator::make(
            array(
                'name'           => $dafaultLanguageArray['name'],
                'heading'           => $dafaultLanguageArray['heading'],
                'introduction'    => $dafaultLanguageArray['introduction'],
            ),
            array(
                'name'           => "required|unique:region",
                'heading'           => 'required',
                'introduction'    => 'required',
            )
        );

        if ($validator->fails())
        {   return Redirect::back()
                ->withErrors($validator)->withInput();
        }else{
            $model                      = new Region;
            $model->heading         = $dafaultLanguageArray['heading'];
            $model->introduction  = $dafaultLanguageArray['introduction'];
            $model->name          = $dafaultLanguageArray['name'];
            $model->slug =          $this->getSlug($dafaultLanguageArray['name'],'slug','Cms');   
            $model->save();
            $modelId	=	$model->id;
       //     print_r($thisData['data']);
           
            foreach ($thisData['data'] as $language_id => $descriptionResult) {
            //	echo $language_id;
            	//echo "<br><pre>";
            //	print_r($descriptionResult);
            	// die;
					if (is_array($descriptionResult))
						foreach ($descriptionResult as $key => $value) {
							RegionDescription::insert(
								array(
									'language_id'				=>	$language_id,
									'foreign_key'				=>	$modelId,
									'source_col_name'			=>	$key,
									'source_col_description'	=>	$value,
								)
							);
						}
				}

				Session::flash('flash_notice',  trans("messages.$this->model.added_message"));  
				return Redirect::route("$this->model.index");

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
      //  echo $id;
        $region		=	Region::findorFail($id);
		$regionDescription	=	RegionDescription::where('foreign_key', '=',  $id)->get();

		//echo "<pre>";
		//print_r($modelDescription);
		### Breadcrums   is  added   here dynamically ###
		Breadcrumb::addBreadcrumb(trans("messages.global.breadcrumbs_dashboard"),route('admin_dashboard'));
		Breadcrumb::addBreadcrumb(trans("messages.$this->model.breadcrumbs_module"),route($this->model.'.index'));;
		Breadcrumb::addBreadcrumb(trans("messages.$this->model.breadcrumbs_edit"),'');
		$breadcrumbs 	= 	Breadcrumb::generate();
		### breadcrumbs End ###
		$multiLanguage		=	array();
		
		if(!empty($regionDescription)){
			foreach($regionDescription as $description) {
				$multiLanguage[$description->language_id][$description -> source_col_name]	=	$description->source_col_description;						
			}
		}

		$languages				=	Language::where('is_active', '=', ACTIVE)->get(array('title','id'));
		$language_code			=	Config::get('default_language.language_code');
		return  View::make("admin.$this->model.edit",compact('breadcrumbs','languages','language_code','region','multiLanguage'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        //
  //      echo $id;
    	$this_data				=	Input::all();

		$default_language		=	Config::get('default_language');
		
		$activeLanguageCode		=	Config::get('default_language.language_code');
		$dafaultLanguageArray	=	$this_data['data'][$activeLanguageCode];

          $duplicacy_checkObj = Region::where('id','!=',$id)->where('name',$dafaultLanguageArray['name'])->count();
            if($duplicacy_checkObj)
                return Redirect::back()->withErrors(["The name is already used as other Region's name"])->withInput();
            
$model   =   Region:: findorFail($id);
		 $validator = Validator::make(
            array(
                'name'           => $dafaultLanguageArray['name'],
                'heading'           => $dafaultLanguageArray['heading'],
                'introduction'    => $dafaultLanguageArray['introduction'],
            ),
            array(
                'name'           => "required",
                'heading'           => 'required',
                'introduction'    => 'required',
            )
        );

		 if ($validator->fails()){	
			return Redirect::back()
				->withErrors($validator)->withInput();
		}else{
           
          
           // die();
			$model->heading         = $dafaultLanguageArray['heading'];
			$model->introduction  = $dafaultLanguageArray['introduction'];
			$model->name               = $dafaultLanguageArray['name'];
			$model->save();

		    RegionDescription::where('foreign_key', '=', $id)->delete();

			 foreach ($this_data['data'] as $language_id => $descriptionResult) {
					if (is_array($descriptionResult))
						foreach ($descriptionResult as $key => $value) {
							RegionDescription::insert(
								array(
									'language_id'				=>	$language_id,
									'foreign_key'				=>	$id,
									'source_col_name'			=>	$key,
									'source_col_description'	=>	$value,
								)
							);
						}
				}

			Session::flash('flash_notice',  trans("messages.$this->model.updated_message"));
			return Redirect::route("$this->model.index");

		}
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function updateStatus($modelId = 0, $modelStatus = 0){
		Region::where('id', '=', $modelId)->update(array('is_active' => $modelStatus));
		Session::flash('flash_notice', trans("messages.$this->model.status_updated_message")); 
		return Redirect::route("$this->model.index");
	}// end updateCmstatus()
}
