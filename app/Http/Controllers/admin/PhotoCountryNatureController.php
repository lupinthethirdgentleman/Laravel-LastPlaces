<?php

namespace App\Http\Controllers\admin;


use App\Http\Requests;
use App\Http\Controllers\BaseController;
use App\Model\AdminUser;
use App\Model\User;
use App\Model\Region;
use App\Model\PhotoCountryNature;
use App\Model\Language;
use App\Model\NewsLettersubscriber;
use App\Model\EmailTemplate;
use App\Model\EmailAction;
use App\Model\RegionDescription;
use mjanssen\BreadcrumbsBundle\Breadcrumbs as Breadcrumb;
use Auth,Blade,Config,Cache,Cookie,DB,File,Hash,Input,Mail,mongoDate,Redirect,Request,Response,Session,URL,View,Validator;

class PhotoCountryNatureController extends BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
        public $model   =   'PhotoCountryNature';

        public function __construct() {
            View::share('modelName',$this->model);
        }

    public function index()
    {
       
            ### breadcrumbs Start ###
        Breadcrumb::addBreadcrumb(trans("messages.global.breadcrumbs_dashboard"),route('admin_dashboard'));
        Breadcrumb::addBreadcrumb(trans("Photo Country Nature Gallery"),route($this->model.'.index'));
        $breadcrumbs    =   Breadcrumb::generate();
        ### breadcrumbs End ###

        $DB             =   PhotoCountryNature::query();
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
        $model = $DB->orderBy($sortBy, $order)->with('Country')->groupBy('country_id')->paginate(Config::get("Reading.records_per_page"));
        
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
        Breadcrumb::addBreadcrumb(trans("Country Photo Nature Gallery"),route($this->model.'.index'));
        Breadcrumb::addBreadcrumb(trans("Add Photos"),'');
        $breadcrumbs    =   Breadcrumb::generate();
        ### breadcrumbs End ###
        
        $languages          =   Language::where('is_active', '=',1)->get(array('title','id'));
        $language_code      =   Config::get('default_language.language_code');
        $countryList = DB::table('destination_country')->lists('name','id');

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
        echo "<pre>";
        $imageDataObj = Input::file('image');

        $image_count =  count($imageDataObj);
        if($image_count == 0){
            return Redirect::back()
                ->withErrors(['image'=>"Select Atleast One Image"])->withInput();
        }

        //
        $thisData               =   Input::all();

        $validator = Validator::make(
            array(
                'country_id'           => $thisData['country_id'],
            ),
            array(
                'country_id'           => "required",
            )
        );

        if ($validator->fails())
        {   return Redirect::back()
                ->withErrors($validator)->withInput();
        }else{

            $insert_batch_array = array();
            for($i=0;$i<$image_count;$i++){
           //  print_r(Input::file('image')[$i]); 
            // echo "<br>";    

                     $extension     =    Input::file('image')[$i]->getClientOriginalExtension();
                    $fileName_temp = pathinfo(Input::file('image')[$i]->getClientOriginalName(), PATHINFO_FILENAME);
                     $fileName   =   time().$fileName_temp . $i . "g-i.".$extension;
                    
                    if(Input::file('image')[$i]->move(PHOTOGALLERY_IMAGE_ROOT_PATH, $fileName)){

                       $insert_batch_array[]= array("country_id"=>$thisData['country_id'],"image"=>$fileName,'created_at'=>date("Y-m-d H:i:s"),'updated_at'=>date("Y-m-d H:i:s")) ;

                     //  $insert_batch_array[]['image'] =  $fileName;
                    }
            }

                PhotoCountryNature::insert($insert_batch_array);
				Session::flash('flash_notice',  trans("Added"));  
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
        $photos		=	PhotoCountryNature::where('country_id','=',$id)->get();

		//echo "<pre>";
		//print_r($modelDescription);
		### Breadcrums   is  added   here dynamically ###
		Breadcrumb::addBreadcrumb(trans("messages.global.breadcrumbs_dashboard"),route('admin_dashboard'));
		Breadcrumb::addBreadcrumb(trans("Country Photo Nature Gallery"),route($this->model.'.index'));;
		Breadcrumb::addBreadcrumb(trans("View Country Photos"),'');
		$breadcrumbs 	= 	Breadcrumb::generate();
		### breadcrumbs End ###
		

		return  View::make("admin.$this->model.edit",compact('breadcrumbs','languages','language_code','photos','multiLanguage'));
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
        $photo = PhotoCountryNature::findorFail($id);

        if(File::exists(PHOTOGALLERY_IMAGE_ROOT_PATH.$photo->image)){
            @unlink(PHOTOGALLERY_IMAGE_ROOT_PATH.$photo->image);
        }
        $photo->delete();
        Session::flash('flash_notice',trans($photo->image."Photo has been deleted successfully"));
        return Redirect::route("$this->model.edit",$photo->country_id);
    }

    public function updateStatus($modelId = 0, $modelStatus = 0){
		Region::where('id', '=', $modelId)->update(array('is_active' => $modelStatus));
		Session::flash('flash_notice', trans("messages.$this->model.status_updated_message")); 
		return Redirect::route("$this->model.index");
	}// end updateCmstatus()

    public function savetitle()
    {
        $image_id=$_POST['id'];
        $image_title=$_POST['image_title'];

        if($image_title!='')
        {
           $PhotoCountry=PhotoCountryNature::where('id','=',$image_id)->update(array('title'=>$image_title));

        }
        return \Response::json(array('success'=>1));
    }
}
