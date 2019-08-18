<?php

namespace App\Http\Controllers\admin;


use App\Http\Requests;
use App\Http\Controllers\BaseController;
use App\Model\AdminUser;
use App\Model\User;
use App\Model\Trip;
use App\Model\Language;
use App\Model\NewsLettersubscriber;
use App\Model\EmailTemplate;
use App\Model\EmailAction;
use App\Model\TripDescription;
use mjanssen\BreadcrumbsBundle\Breadcrumbs as Breadcrumb;
use Auth,Blade,Config,Cache,Cookie,DB,File,Hash,Input,Mail,mongoDate,Redirect,Request,Response,Session,URL,View,Validator;

class TripController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
        public $model   =   'Trip';

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

       // $DB             =   Trip::query()->with('country');
         $DB             =   Trip::query();
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

        $sortBy = (Input::get('sortBy')) ? Input::get('sortBy') : 'trips.updated_at';
        $order  = (Input::get('order')) ? Input::get('order')   : 'DESC';
        $model = $DB->select('trips.is_active','trips.created_at','trips.id','trips.image','trips.tripname','trips.description','trips.tripdates','trips.tripdays','trips.baseprice','trips.slug','destination_country.name')
                    ->join('destination_country','destination_country.id','=','trips.country_id')
                    ->where('destination_country.active','1')
                    ->orderBy($sortBy, $order)
                    ->paginate(Config::get("Reading.records_per_page"));
                   // ->join('destination_country','trips.country_id','=','destination_country.id');
                   
        return View::make("admin.$this->model.index",compact('breadcrumbs','model','searchVariable','sortBy','order'));
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
        $countryList = DB::table('destination_country')->orderBy('name','asc')->lists('name','id');
       
        return  View::make("admin.$this->model.add",compact('languages' ,'language_code','breadcrumbs','countryList'));
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
       
        $validator = Validator::make(
            array(
                'tripname'          => $dafaultLanguageArray['tripname'],
                'image'             => Input::file('image'),
                'header_image'      => Input::file('header_image'),
                'description'       => $dafaultLanguageArray['description'],
                'tripdays'          => $thisData['tripdays'],
                'overview'          => $dafaultLanguageArray['overview'],
                'itinerary'         => $dafaultLanguageArray['itinerary'],
               // 'countryinfo'    => $dafaultLanguageArray['countryinfo'],
                'country_id'        => $thisData['country_id'],
                'baseprice'         => $thisData['baseprice']

            ),
            array(
                'tripname'           => "required|unique:trips",
                'image'              => 'image|required|mimes:jpeg,png,bmp,gif,svg',
                'header_image'       => 'image|required|mimes:jpeg,png,bmp,gif,svg',
                'description'        => 'required',
                'tripdays'           => 'required|integer',
                'overview'          => "required",
                'itinerary'          => 'required',
             //   'countryinfo'    => 'required',
                'country_id'         => 'required',
                'baseprice'          => 'required|integer',
            )
        );

        if ($validator->fails())
        {   return Redirect::back()
                ->withErrors($validator)->withInput();
        }else{
            $model                     =   new Trip;
            $model->country_id         =   $thisData['country_id'];
            $model->tripname           =   $dafaultLanguageArray['tripname'];
            $model->description        =   $dafaultLanguageArray['description'];
            $model->tripdays           =   $thisData['tripdays'];
            $model->tripdates          =  date("Y-m-d", strtotime($thisData['tripdate']));
            $model->overview           =   $dafaultLanguageArray['overview'];
            $model->itinerary          =   $dafaultLanguageArray['itinerary'];
            $model->baseprice          =   $thisData['baseprice'];
            $model->slug               =   $this->getSlug($dafaultLanguageArray['tripname'],'slug','Cms');   

            if(input::hasFile('image')){
                    $extension  =    Input::file('image')->getClientOriginalExtension();
                    $fileName   =   time().'-trip-image.'.$extension;
                    
                    if(Input::file('image')->move(TRIP_IMAGE_ROOT_PATH, $fileName)){
                        $model->image         =   $fileName;
                    }
                }

                 if(input::hasFile('header_image')){
                    $extension  =    Input::file('header_image')->getClientOriginalExtension();
                    $fileName   =   time().'-trip-header-image.'.$extension;
                    
                    if(Input::file('header_image')->move(TRIP_HEADER_IMAGE_ROOT_PATH, $fileName)){
                        $model->header_image         =   $fileName;
                    }
                }

            $model->save();
            $modelId	=	$model->id;
         
       //     print_r($thisData['data']);
           
            foreach ($thisData['data'] as $language_id => $descriptionResult) {
					if (is_array($descriptionResult))
						foreach ($descriptionResult as $key => $value) {
							TripDescription::insert(
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
        $trip		=	Trip::findorFail($id);
		$tripDescription	=	TripDescription::where('foreign_key', '=',  $id)->get();

		//echo "<pre>";
		//print_r($modelDescription);
		### Breadcrums   is  added   here dynamically ###
		Breadcrumb::addBreadcrumb(trans("messages.global.breadcrumbs_dashboard"),route('admin_dashboard'));
		Breadcrumb::addBreadcrumb(trans("messages.$this->model.breadcrumbs_module"),route($this->model.'.index'));;
		Breadcrumb::addBreadcrumb(trans("messages.$this->model.breadcrumbs_edit"),'');
		$breadcrumbs 	= 	Breadcrumb::generate();
		### breadcrumbs End ###
		$multiLanguage		=	array();
		
		if(!empty($tripDescription)){
			foreach($tripDescription as $description) {
				$multiLanguage[$description->language_id][$description -> source_col_name]	=	$description->source_col_description;						
			}
		}
        $countryList = DB::table('destination_country')->orderBy('name','asc')->lists('name','id');

		$languages				=	Language::where('is_active', '=', ACTIVE)->get(array('title','id'));
		$language_code			=	Config::get('default_language.language_code');
		return  View::make("admin.$this->model.edit",compact('breadcrumbs','languages','language_code','trip','multiLanguage','countryList'));
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
    	$thisData				=	Input::all();

		$default_language		=	Config::get('default_language');
		$model 					= 	Trip:: findorFail($id);
		$activeLanguageCode		=	Config::get('default_language.language_code');
		$dafaultLanguageArray	=	$thisData['data'][$activeLanguageCode];
    $duplicacy_checkObj = Trip::where('id','!=',$id)->where('tripname',$dafaultLanguageArray['tripname'])->count();
    if($duplicacy_checkObj)
        return Redirect::back()->withErrors(["The name is already used as other Region's name"])->withInput();
    

    $model          =   Trip:: findorFail($id);



		 $validator = Validator::make(
            array(
                'tripname'          => $dafaultLanguageArray['tripname'],
                'description'       => $dafaultLanguageArray['description'],
                'image'             => Input::file('image'),
                'header_image'      => Input::file('header_image'),
                'tripdays'          => $thisData['tripdays'],
                'overview'          => $dafaultLanguageArray['overview'],
                'itinerary'         => $dafaultLanguageArray['itinerary'],
              //  'countryinfo'    => $dafaultLanguageArray['countryinfo'],
                'country_id'        => $thisData['country_id'],
                 'baseprice'        => $thisData['baseprice']

            ),
            array(
                'tripname'           => "required",
                'image'              => 'image|mimes:jpeg,png,bmp,gif,svg',
                'header_image'       => 'image|mimes:jpeg,png,bmp,gif,svg',
                'description'        => 'required',
                'tripdays'           => 'required|integer',
                'overview'           => "required",
                'itinerary'          => 'required',
             //   'countryinfo'    => 'required',
                'country_id'         => 'required',
                'baseprice'          => 'required|integer',
            )
        );

		 if ($validator->fails()){	
			return Redirect::back()
				->withErrors($validator)->withInput();
		}else{
		   //$model                      = new Trip;
            $model->country_id         = $thisData['country_id'];
            $model->tripname           = $dafaultLanguageArray['tripname'];
            $model->description        = $dafaultLanguageArray['description'];
            $model->tripdays           = $thisData['tripdays'];
            $model->tripdates          =  date("Y-m-d", strtotime($thisData['tripdate']));
            $model->overview           = $dafaultLanguageArray['overview'];
            $model->itinerary          = $dafaultLanguageArray['itinerary'];
            $model->baseprice          = $thisData['baseprice'];
            $model->slug               = $this->getSlug($dafaultLanguageArray['tripname'],'slug','Cms');   

            if(input::hasFile('image')){
                    $extension  =    Input::file('image')->getClientOriginalExtension();
                    $fileName   =   time().'-trip-image.'.$extension;
                    
                    if(Input::file('image')->move(TRIP_IMAGE_ROOT_PATH, $fileName)){
                        $model->image         =   $fileName;
                    }
                }

            if(input::hasFile('header_image')){
                    $extension  =    Input::file('header_image')->getClientOriginalExtension();
                    $fileName   =   time().'-trip-header-image.'.$extension;
                    
                    if(Input::file('header_image')->move(TRIP_HEADER_IMAGE_ROOT_PATH, $fileName)){
                        $model->header_image         =   $fileName;
                    }
                }


            $model->save();

		    TripDescription::where('foreign_key', '=', $id)->delete();

			 foreach ($thisData['data'] as $language_id => $descriptionResult) {
					if (is_array($descriptionResult))
						foreach ($descriptionResult as $key => $value) {
							TripDescription::insert(
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
		Trip::where('id', '=', $modelId)->update(array('is_active' => $modelStatus));
		Session::flash('flash_notice', trans("messages.$this->model.status_updated_message")); 
		return Redirect::route("$this->model.index");
	}// end updateCmstatus()
}
