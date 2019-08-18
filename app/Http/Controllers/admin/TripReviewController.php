<?php

namespace App\Http\Controllers\admin;


use App\Http\Requests;
use App\Http\Controllers\BaseController;
use App\Model\AdminUser;
use App\Model\User;
use App\Model\Language;
use App\Model\NewsLettersubscriber;
use App\Model\EmailTemplate;
use App\Model\EmailAction;
use App\Model\TripReview;
use App\Model\TripReviewDescription;
use mjanssen\BreadcrumbsBundle\Breadcrumbs as Breadcrumb;
use Auth,Blade,Config,Cache,Cookie,DB,File,Hash,Input,Mail,mongoDate,Redirect,Request,Response,Session,URL,View,Validator;

class TripReviewController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
        public $model   =   'Tripreview';

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

        $DB             =   TripReview::query()->with('trip');

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
        $model = $DB->orderBy($sortBy, $order)
                    ->paginate(Config::get("Reading.records_per_page"));
                   // ->join('destination_country','trips.country_id','=','destination_country.id');

      //  print_r($model);die;
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
        $tripList = DB::table('trips')->lists('tripname','id');
        
        return  View::make("admin.$this->model.add",compact('languages' ,'language_code','breadcrumbs','tripList'));
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
        //echo "<pre>";
        //print_r($thisData);die;
        $validator = Validator::make(
            array(
                'trip_id'           => $thisData['trip_id'],
                'clientname'           => $dafaultLanguageArray['clientname'],
                'review'    => $dafaultLanguageArray['review'],
            ),
            array(
                 'trip_id'           => "required",
                'clientname'           => "required",
                'review'    => "required",
            )
        );

        if ($validator->fails())
        {   return Redirect::back()
                ->withErrors($validator)->withInput();
        }else{
            $model                      = new TripReview;
            $model->trip_id         = $thisData['trip_id'];
            $model->clientname  =     $dafaultLanguageArray['clientname'];
            $model->review          = $dafaultLanguageArray['review'];

            $model->save();
            $modelId	=	$model->id;
         
       //     print_r($thisData['data']);
           
            foreach ($thisData['data'] as $language_id => $descriptionResult) {
					if (is_array($descriptionResult))
						foreach ($descriptionResult as $key => $value) {
							TripReviewDescription::insert(
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
        $tripReview		=	TripReview::findorFail($id);
		$tripReviewDescription	=	TripReviewDescription::where('foreign_key', '=',  $id)->get();

		//echo "<pre>";
		//print_r($modelDescription);
		### Breadcrums   is  added   here dynamically ###
		Breadcrumb::addBreadcrumb(trans("messages.global.breadcrumbs_dashboard"),route('admin_dashboard'));
		Breadcrumb::addBreadcrumb(trans("messages.$this->model.breadcrumbs_module"),route($this->model.'.index'));;
		Breadcrumb::addBreadcrumb(trans("messages.$this->model.breadcrumbs_edit"),'');
		$breadcrumbs 	= 	Breadcrumb::generate();
		### breadcrumbs End ###
		$multiLanguage		=	array();
		
		if(!empty($tripReviewDescription)){
			foreach($tripReviewDescription as $description) {
				$multiLanguage[$description->language_id][$description -> source_col_name]	=	$description->source_col_description;						
			}
		}
        $tripList = DB::table('trips')->lists('tripname','id');

		$languages				=	Language::where('is_active', '=', ACTIVE)->get(array('title','id'));
		$language_code			=	Config::get('default_language.language_code');
		return  View::make("admin.$this->model.edit",compact('breadcrumbs','languages','language_code','tripReview','multiLanguage','tripList'));
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
		$model 					= 	TripReview:: findorFail($id);
		$activeLanguageCode		=	Config::get('default_language.language_code');
		$dafaultLanguageArray	=	$thisData['data'][$activeLanguageCode];

		 $validator = Validator::make(
            array(
                'trip_id'           => $thisData['trip_id'],
                'clientname'           => $dafaultLanguageArray['clientname'],
                'review'    => $dafaultLanguageArray['review'],
            ),
            array(
                 'trip_id'           => "required",
                'clientname'           => "required",
                'review'    => "required",
            )
        );

		 if ($validator->fails()){	
			return Redirect::back()
				->withErrors($validator)->withInput();
		}else{
            $model->trip_id         = $thisData['trip_id'];
            $model->clientname  =     $dafaultLanguageArray['clientname'];
            $model->review          = $dafaultLanguageArray['review'];

            $model->save();

		    TripReviewDescription::where('foreign_key', '=', $id)->delete();

			 foreach ($thisData['data'] as $language_id => $descriptionResult) {
					if (is_array($descriptionResult))
						foreach ($descriptionResult as $key => $value) {
							TripReviewDescription::insert(
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
		TripReview::where('id', '=', $modelId)->update(array('is_active' => $modelStatus));
		Session::flash('flash_notice', trans("messages.$this->model.status_updated_message")); 
		return Redirect::route("$this->model.index");
	}// end updateCmstatus()
}
