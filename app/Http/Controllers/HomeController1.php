<?php
/**
 * Home Controller
 */
namespace App\Http\Controllers;
use Auth,Blade,Config,Cache,Cookie,DB,File,Hash,Input,Mail,mongoDate,Redirect,Request,Response,Session,URL,View,Validator;
use App\Model\Company;
use App\Model\CompanyLocation;
use App\Model\Block;
use App\Model\Blog;
use App\Model\Setdepartures;
use App\Model\DestinationCountry;
use Illuminate\Support\Facades\Log;
use App\Model\User;
use App\Model\EmailAction;
use App\Model\EmailTemplate;
use App\Model\Hcp;
use App\Model\Trip;
use App\Model\Like;
use App\Model\Review;
use Excel;
use PDF;


class HomeController extends BaseController {

public function memberList(){
	$title = 'Members';
	$negativeReviews = Review::where('rating','<=',NEGATIVE_REVIEW_NUMBER)->where('status',1)->get();
	$totalReviews = Review::where('status',1)->get();
	
	$name =  \Input::get('name');

	$locationName="";
	// dd($location_id);
	$memberLists = [];

	if($name!=''){
		$memberLists = Hcp::with('company','companylocation','review','like','dislike')->where('full_name','like','%'.$name.'%')->where('status',1)->paginate(10);
	}else{
		$memberLists = Hcp::with('company','companylocation','review','like','dislike')->where('status',1)->paginate(10);
	}

	// dd($memberLists);
	/*$memberLists->appends('name',$name);*/
 	
 	return view('member_list',compact('memberLists','title','negativeReviews','totalReviews','locationName','name'))->with("search_type","SEARCH MEMBER")->with("search_field_name","name");
}

public function memberListLocation(){
	$title = 'Members';
	$negativeReviews = Review::where('rating','<=',NEGATIVE_REVIEW_NUMBER)->where('status',1)->get();
	$totalReviews = Review::where('status',1)->get();

	$memberLists = [];
	$name = "";
	$locationName =  \Input::get('locationName');
	if($locationName != ""){
			$location_id = $this->getLocationId($locationName);
			if($location_id!=''){
			$memberLists = Hcp::with('company','companylocation','review','like','dislike')->where('location',$location_id)->where('status',1)->paginate(10);
			}
	}else{
		$memberLists = Hcp::with('company','companylocation','review','like','dislike')->where('status',1)->paginate(10);
	}
	

	return view('member_list',compact('memberLists','title','negativeReviews','totalReviews','locationName','name'))->with("search_type","ASSESMENT CENTRE")->with("search_field_name","locationName");

}

public function getLocationId($location){

	$id =  DB::table('locations')->where('name',$location)->select('id')->first();

	if(!empty($id)){

		return $id->id;	

	}else{

		return false;
	}
	
}

public function TestimonialList(){
	$title = 'Testimonials';
	$testimonialLists = [];
	$lang = \App::getLocale();
	//$testimonialLists = DB::table('testimonials')->where('status',1)->paginate(10);
	$testimonialLists = DB::select( DB::raw("SELECT * FROM testimonial_descriptions WHERE language_id = (select id from languages WHERE languages.lang_code = '$lang') and status=1 group by parent_id") );
 	
 	return view('testimonial_list',compact('testimonialLists','title'));
}

public function writeReview($hcpId){
	$title = 'Write review';
	$negativeReviews = Review::where('rating','<=',NEGATIVE_REVIEW_NUMBER)->where('status',1)->get();
	$totalReviews = Review::where('status',1)->get();
	$negativeReviewsMember = Review::where('hcp_id',$hcpId)->where('rating','<=',NEGATIVE_REVIEW_NUMBER)->where('status',1)->count();
	$totalReviewsMember = Review::where('hcp_id',$hcpId)->where('status',1)->count();
	if($negativeReviewsMember > 0 && $totalReviewsMember>0){
		if($negativeReviewsMember < $totalReviewsMember){
			$negativePercentage = round(($negativeReviewsMember/$totalReviewsMember)*100);
		}else{
			$negativePercentage = 0;	
		}
	}else{
		$negativePercentage = 0;
	}
	$member 	= Hcp::with('company','review','like','dislike')->where('id',$hcpId)->where('status',1)->firstOrFail();	
 	$companyId 	= $member->company_id;
 	$health_benefit_list = DB::table('dropdown_managers')->where('dropdown_type', 'health_benefit')->lists('name','id');
 	$company_list = DB::table('companies')->lists('Name','id');
 	$moremember = Hcp::with('company','review','like','dislike')->where('company_id',$companyId)->where('id','!=',$hcpId)->where('status',1)->get();
 	//dd($moremember);
 	$isAlreadyReview = Review::where('hcp_id',$hcpId)->where('user_id',Auth::user()->id)->count();
 	$review = Review::where('hcp_id',$hcpId)->where('status',1)->select('rating','comment','created_at')->paginate(3);
 	return view('write_review',compact('title','member','moremember','health_benefit_list','company_list','review','isAlreadyReview','hcpId','negativeReviews','totalReviews','negativePercentage'));
}

public function saveReview(){
	if(Request::ajax()){ 
			$formData	=	Input::all();
			if(!empty($formData)){
				######### validation rule #########
				$validationRules	=	array(
					'rating_value' 		=> 'required',
					'health_benefit' 			=> 'required',
					'company_id' 		=> 'required',
					'comment' 	=> 'required|min:50',
				);
				$validator = Validator::make(
					Input::all(),
					$validationRules
				);
				if (!$validator->fails()){
										
					######### array for save review #########
					$reviewData										=  array();
					$reviewData['hcp_id'] 							=  Input::get('hcp_id');
					$reviewData['rating'] 							=  Input::get('rating_value');
					$reviewData['user_id'] 							=  Auth::id();
					$reviewData['company_id'] 						=  Input::get('company_id');
					$reviewData['health_benefit'] 					=  Input::get('health_benefit');
					$reviewData['comment'] 							=  Input::get('comment');
					$reviewData['created_at']						=  date('Y-m-d H:i:s');
					$reviewData['updated_at']						=  date('Y-m-d H:i:s');
					######### save review #########
					
					$insertId								=	Review::insertGetId($reviewData);
				
					$allErrors		=	'';
					$title			=	'';
					$url 			=	'';

					$allErrors	=	trans("Review Added Successfully");
					$title		=	trans("Write Review");
					
					$response	=	array(
						'success' 		=> 1,
						'success_msg' 	=> $allErrors,
						'title' 		=> $title,
						'url'			=>	$url
					);
					return Response::json($response); die;	
				}else{
					
					$allErrors='<ul>';
					foreach ($validator->errors()->all('<li>:message</li>') as $message){
							$allErrors .=  $message; 
					}
					$allErrors .= '</ul>'; 
					$response	=	array(
						'success' => false,
						'errors' => $allErrors
					);
					return  Response::json($response); die;
				}	
			}
		}

} //saveReview	

/** 
 * Function to display website home page
 *
 * @param null
 * 
 * @return view page
 */
	public function index(){

		$blocksCall = Block::with('description')
						->where('block','call-us')
						->first()->toArray();
							
		$blocksDream = Block::with('description')
						->where('block','dream-header')
						->first()->toArray();

		$blocksBlog2 = Block::with('description')
						->where('block','home-blog-header')
						->first()->toArray();

						//print_r($blocksBlog);die;
						
		$blocksBlog = Blog::with('blogDescription','blogComments','blogsAuthor')
						->where('is_active',1)
						->orderBy('created_at')
						->limit(3)
						->get();

		$blocksBlogForHighlight = Blog::with('blogDescription','blogComments','blogsAuthor')
		->where('is_active',1)
		->where('is_highlight',1)
		->orderBy('created_at')
		->limit(3)
		->get();
	
		$highlightBlogCount = Blog::with('blogDescription','blogComments')
								->where('is_active',1)
								->where('is_highlight',1)
								->count();

		$highlightCountry = DestinationCountry::with('destinationCountryDescription')
								->where('active',1)
								->where('is_highlight',1)
								->limit(9)
								->get();

		//echo "<pre>";print_r($blocksBlog);die;
	
			
								
		return View::make('home.index',compact('blocksCall','blocksDream','blocksBlog','highlightBlogCount','highlightCountry','blocksBlog2','blocksBlogForHighlight'));
	}//end index()


	public function blogs(){
		$blocksBlog = Blog::with('blogDescription','blogComments','blogsAuthor')
						->where('is_active',1)
						->orderBy('created_at')
						->paginate(2);
	//	echo "<pre>";
	//	print_r($blocksBlog);die;
		return View::make('blog.index',compact('blocksBlog'));
	}

	public function setdepartures(){
		
		//$tripslist = setdepartures::paginate(10);
		/*$tripslist=$DB->join('destination_countrys',function($join){
					$join->on('destination_country.id', '=', 'trips.country_id');
				})->where('destination_country.active', '1')->paginate(10);*/
				
			$tripslist=	DB::table('trips')
				->select('trips.image','trips.tripname','trips.description','trips.tripdates','trips.tripdays','trips.baseprice','trips.slug','destination_country.name')
				->join('destination_country','destination_country.id','=','trips.country_id')
				->where('destination_country.active','1')->where('trips.is_active','1')->paginate(10);

			/*$tripslist=	 setdepartures::query("select * from trips as a,destination_country as b where a.country_id=b.id and b.active=1")->paginate(10);*/

		$countryList = DB::table('destination_country')->where('active','1')->orderBy('name','asc')->lists('name','id');		
		return View::make('setdeparture.index',compact('tripslist','countryList'));
	}

	public function saveComment(){
		DB::table('blog_comments')->insert(
			array(
				'blog_id'   => Input::get('blog_id'),
				'comment' 	=> Input::get('comment'),
				'full_name' => Input::get('name'),
				'email'     => Input::get('email'),
				'created'	=> date('Y-m-d H:i:s')
			)
		);
		Session::flash('success',trans("Your comment was posted successfully."));	
		//return Redirect::to('/');
    	return Redirect::back();
    	}
	
/** 
 * Function to display contact us page
 *
 * @param null
 * 
 * @return view page
 */
	public function contactUs(){
		$title = 'Contact us'; 
		
		$userProfileDetail	=	array();

		$blocks	= array();
		//$blocks	=	Block::getResult('contact-us',array('id','description','block'));
		return View::make('cms.contact_us',compact('title','blocks','userProfileDetail'));
		
	}//end contactUs()

	public function contactUsPost(){
		$allData	=	Input::all();
		if(!empty($allData)){
			$validator = Validator::make(
				Input::all(),
				array(
					'first_name' 	=> 'required',
					'last_name' 	=> 'required',
					'email' 		=> 'required|email',
					'comment' 		=> 'required',
				),
				array(
					'first_name.required' 	=> trans('messages.The fasdfirst name field is required.'),
					'last_name.required' 	=> trans('messages.The last name field is required.'),
					'email.required' 		=> trans('messages.The email field is required.'),
					'email.email' 			=> trans('messages.The email must be a valid email address.'),
					'comment.required' 		=> trans('messages.dasThe comment field is required.'),
				)
			);
			
			if ($validator->fails()){	
					$allErrors	= '<ul>';
					foreach ($validator->errors()->all('<li>:message</li>') as $message)
					{
							$allErrors .=  $message; 
					}
					$allErrors .= '</ul>'; 
					$response	=	array(
						'success' 	=> false,
						'errors' 	=> $allErrors
					);
					return  Response::json($response); die;
			}else{
				DB::table('contact_us')->insert(
					array(
						'name'=> Input::get('first_name').' '.Input::get('lastname'),
						'email' 	=> Input::get('email'),
						'message' 	=> Input::get('comment'),
					)
				);
				
				//send email to site admin with user information,to inform that user wants to contact
				
				$emailActions		=  EmailAction::where('action','=','contact_us')->get()->toArray();
				$emailTemplates		=  EmailTemplate::where('action','=','contact_us')->get(array('name','subject','action','body'))->toArray();
				$cons 				=  explode(',',$emailActions[0]['options']);
				$constants 			=  array();
				
				foreach($cons as $key=>$val){
					$constants[] = '{'.$val.'}';
				}
				
				$firstname	=	 Input::get('first_name');
				$lastname	=	 Input::get('last_name');
				$email		=	 Input::get('email');
				$comment	=	 Input::get('comment');
				
				$subject 		=  $emailTemplates[0]['subject'];
				$rep_Array 		=  array($firstname,$lastname,$email,$comment); 
				$messageBody	=  str_replace($constants, $rep_Array, $emailTemplates[0]['body']);
				 
				$this->sendMail(Config::get("Site.contact_email"),'Admin',$subject,$messageBody,$email);
				$response	=	array(
					'success' 	=>	'1',
					'errors' 	=>	trans("messages.Thanks for contact us.")
				);
				return  Response::json($response); die;	
			}
		}
	}



	/** 
 * Function to display trip enquiry page
 *
 * @param null
 * 
 * @return view page
 */
	public function TripEnquiry($trip_id){
		$title = 'Trip Enquiry'; 
		$allData	=	Input::all();
		$userProfileDetail	=	array();


		if(!empty($allData)){
			$validator = Validator::make(
				Input::all(),
				array(
					'first_name' 	=> 'required',
					'last_name' 	=> 'required',
					'email' 		=> 'required|email',
					'message' 		=> 'required',
				),
				array(
					'first_name.required' 	=> trans('The first name field is required.'),
					'last_name.required' 	=> trans('.The last name field is required.'),
					'email.required' 		=> trans('The email field is required.'),
					'email.email' 			=> trans('The email must be a valid email address.'),
					'message.required' 		=> trans('The message field is required.'),
				)
			);
			
			if ($validator->fails()){	
					$allErrors	= '<ul>';
					foreach ($validator->errors()->all('<li>:message</li>') as $message)
					{
							$allErrors .=  $message; 
					}
					$allErrors .= '</ul>'; 
					$response	=	array(
						'success' 	=> false,
						'errors' 	=> $allErrors
					);
					return  Response::json($response); die;
			}else{
				DB::table('trip_enquiry')->insert(
					array(
						'name'=> Input::get('first_name').' '.Input::get('lastname'),
						'email' 	=> Input::get('email'),
						'phone' 	=> Input::get('phone'),
						'message' 	=> Input::get('message'),
					)
				);
				
				//send email to site admin with user information,to inform that user wants to contact
				
				$emailActions		=  EmailAction::where('action','=','trip_enquiry')->get()->toArray();
				$emailTemplates		=  EmailTemplate::where('action','=','trip_enquiry')->get(array('name','subject','action','body'))->toArray();
				$cons 				=  explode(',',$emailActions[0]['options']);
				$constants 			=  array();
				
				foreach($cons as $key=>$val){
					$constants[] = '{'.$val.'}';
				}
				
				$firstname	=	 Input::get('first_name');
				$lastname	=	 Input::get('last_name');
				$email		=	 Input::get('email');
				$message	=	 Input::get('message');
				
				$subject 		=  $emailTemplates[0]['subject'];
				$rep_Array 		=  array($firstname,$lastname,$email,$message); 
				$messageBody	=  str_replace($constants, $rep_Array, $emailTemplates[0]['body']);
				 
				$this->sendMail(Config::get("Site.email"),'Admin',$subject,$messageBody,$email);
				$response	=	array(
					'success' 	=>	'1',
					'errors' 	=>	trans("messages.Thanks for contact us.")
				);
				return  Response::json($response); die;	
			}
		}
		$blocks	= array();
		//$blocks	=	Block::getResult('contact-us',array('id','description','block'));
		$tripDetail = Trip::where('id',$trip_id)->get()->first();
		return View::make('enquiry_view',compact('title','blocks','userProfileDetail','tripDetail'));
		
	}//end TripEnquiry()


	public function SaveTripEnquiry(){
		$title = 'Trip Enquiry'; 
		$allData	=	Input::all();
		$userProfileDetail	=	array();

		$curr_time = date("Y-m-d h:i:s a", time());

		if(!empty($allData)){
			$validator = Validator::make(
				Input::all(),
				array(
					'first_name' 	=> 'required',
					'last_name' 	=> 'required',
					'email' 		=> 'required|email',
					'message' 		=> 'required',
				),
				array(
					'first_name.required' 	=> trans('The first name field is required.'),
					'last_name.required' 	=> trans('.The last name field is required.'),
					'email.required' 		=> trans('The email field is required.'),
					'email.email' 			=> trans('The email must be a valid email address.'),
					'message.required' 		=> trans('The message field is required.'),
				)
			);
			
			if ($validator->fails()){	
					$allErrors	= '<ul>';
					foreach ($validator->errors()->all('<li>:message</li>') as $message)
					{
							$allErrors .=  $message; 
					}
					$allErrors .= '</ul>'; 
					$response	=	array(
						'success' 	=> false,
						'errors' 	=> $allErrors
					);
					return  Response::json($response); die;
			}else{
				DB::table('trip_enquiry')->insert(
					array(
						'name'			=> Input::get('first_name').' '.Input::get('lastname'),
						'trip_id'		=> Input::get('trip_id'),
						'email' 		=> Input::get('email'),
						'phone' 		=> Input::get('phone'),
						'message' 		=> Input::get('message'),
						'created_at'	=> $curr_time,
						'updated_at'	=> $curr_time,
					)
				);
				
				//send email to site admin with user information,to inform that user wants to contact
				
				$emailActions		=  EmailAction::where('action','=','trip_enquiry')->get()->toArray();
				$emailTemplates		=  EmailTemplate::where('action','=','trip_enquiry')->get(array('name','subject','action','body'))->toArray();
				$cons 				=  explode(',',$emailActions[0]['options']);
				$constants 			=  array();
				
				foreach($cons as $key=>$val){
					$constants[] = '{'.$val.'}';
				}
				
				$firstname	=	 Input::get('first_name');
				$lastname	=	 Input::get('last_name');
				$email		=	 Input::get('email');
				$message	=	 Input::get('message');
				$tripname	=	 Input::get('trip_name');
				
				$subject 		=  $emailTemplates[0]['subject'];
				$rep_Array 		=  array($tripname,$firstname,$lastname,$email,$message); 
				$messageBody	=  str_replace($constants, $rep_Array, $emailTemplates[0]['body']);
				 
				$this->sendMail(Config::get("Site.email"),'Admin',$subject,$messageBody,$email);
				$response	=	array(
					'success' 	=>	'1',
					'errors' 	=>	trans("messages.Thanks for contact us.")
				);
				return  Response::json($response); die;	
			}
		}
		$blocks	= array();
		//$blocks	=	Block::getResult('contact-us',array('id','description','block'));
		return View::make('enquiry_view',compact('title','blocks','userProfileDetail'));
		
	}//end TripEnquiry()
	
/** 
 * Function to display cms page on website
 *
 * @param slug as slug of cms page
 * 
 * @return view page
 */	
	public function showCms($slug)
	{ 
		
		$title = ucfirst(str_replace('-', ' ', $slug));
		$lang			=	\App::getLocale();
		$cmsPagesDetail	=	DB::select( DB::raw("SELECT * FROM cms_page_descriptions WHERE foreign_key = (select id from cms_pages WHERE cms_pages.slug = '$slug') AND language_id = (select id from languages WHERE languages.lang_code = '$lang')") );
		
		 if(empty($cmsPagesDetail)){
			return Redirect::to('/');
		} 
		$result	=	array();
		
		foreach($cmsPagesDetail as $cms){
			$key	=	$cms->source_col_name;
			$value	=	$cms->source_col_description;
			$result[$cms->source_col_name]	=	$cms->source_col_description;
		}
		
		return View::make('cms.index' , compact('result','slug','title'));
	}//end showCms()

	public function latestReview(){
		Review::where('status',1)->orderBY('created_at','desc')->get();
	}
	public function likeOrDislike(){
		if(Request::ajax()){ 
			$hcpId = Input::get('user');
			$likeAndDislike = Input::get('likeAndDislike');
			
			$userData['like'] 	=  $likeAndDislike;

			Like::updateOrCreate(
					 	array('user_id'=>Auth::user()->id,'hcp_id'=>$hcpId),
						 $userData
					)
					->where('user_id',Auth::user()->id)
					->where('hcp_id',Input::get('user'));

			$totalLike 		= Like::where('hcp_id',$hcpId)->where('like',1)->count();
			$totalDisLike   = Like::where('hcp_id',$hcpId)->where('like',2)->count();

			$html = View::make('login_like_dislike_ajax',compact('totalLike','totalDisLike','likeAndDislike','hcpId'))->render();

			$response = array(
				'html' => $html
			);

			return  Response::json($response); die;
		}
	}
	public function likeOrDislikeMember(){
		if(Request::ajax()){ 
			$hcpId = Input::get('user');
			
			$likeAndDislike = Input::get('likeAndDislike');
			
			$userData['like'] 	=  $likeAndDislike;

			Like::updateOrCreate(
					 	array('user_id'=>Auth::user()->id,'hcp_id'=>$hcpId),
						 $userData
					)
					->where('user_id',Auth::user()->id)
					->where('hcp_id',Input::get('user'));

			$totalLike 		= Like::where('hcp_id',$hcpId)->where('like',1)->count();
			$totalDisLike   = Like::where('hcp_id',$hcpId)->where('like',2)->count();

			$html = View::make('like_dislike_member_ajax',compact('totalLike','totalDisLike','likeAndDislike','hcpId'))->render();

			$response = array(
				'html' => $html
			);

			return  Response::json($response); die;
		}
	}

	public function downloadReview($hcpId){

		$negativeReviewsMember = Review::where('hcp_id',$hcpId)->where('rating','<=',NEGATIVE_REVIEW_NUMBER)->where('status',1)->count();
		$totalReviewsMember = Review::where('hcp_id',$hcpId)->where('status',1)->count();
		if($negativeReviewsMember > 0 && $totalReviewsMember >0){
			if($negativeReviewsMember < $totalReviewsMember){
				$negativePercentage = round(($negativeReviewsMember/$totalReviewsMember)*100);
			}else{
				$negativePercentage = 0;	
			}
		}else{
			$negativePercentage = 0;
		}
		$title = 'Download review';
		$member 	= Hcp::with('company','review','like','dislike')->where('id',$hcpId)->where('status',1)->firstOrFail();
		$review = Review::where('hcp_id',$hcpId)->where('status',1)->get();
		$data = array(
				'review' 			=> $review,
				'member' 			=> $member,
				'title' 			=> $title,
				'hcpId' 			=> $hcpId,
				'negativePercentage'=> $negativePercentage
			);


		$view = View::make('download', compact('data'));
        $html = $view->render();

		//echo $html; exit;

		$pdf = PDF::make();
		$pdf->addPage($html);

		//$pdf->send();
		$fileName = 'review_data'.$hcpId.'.pdf';
		$pdf->saveAs(DOCUMENT_ROOT_PATH . $fileName); 

		$headers = [
              'Content-Type' => 'application/pdf',
           ];

		return response()->download(DOCUMENT_ROOT_PATH . $fileName, $fileName, $headers);


		/*$pdf = PDF::loadView('download', compact('data'));
        return $pdf->download('pdfview.pdf');*/


		/*return Excel::create('review_pdf'.$hcpId, function($excel)  use ($data) {
		    $excel->sheet('New sheet', function($sheet)  use ($data) {
		        $sheet->loadView('download', compact('data'));
		    });

		})->download("pdf");*/



		//return View::make('download',compact('data'));

	}

	public function downloadSearchResult(){
		$memberLists = [];
		$locationName =  \Input::get('locationName');
		$hcpId = "";
		if($locationName != ""){
			$location_id = $this->getLocationId($locationName);
			if($location_id!=''){
				$memberLists = Hcp::select('id')->where('location',$location_id)->where('status',1)->paginate(10);
			}
			}else{
				$memberLists = Hcp::select('id')->where('status',1)->paginate(10);
			}
				$all_member_list = array();
			if(!$memberLists){
				 return redirect('/');
			}
			foreach($memberLists as $memberId){
				$hcpId =  $memberId->id;

				$negativeReviewsMember = Review::where('hcp_id',$hcpId)->where('rating','<=',NEGATIVE_REVIEW_NUMBER)->where('status',1)->count();
				$totalReviewsMember = Review::where('hcp_id',$hcpId)->where('status',1)->count();

				 if($negativeReviewsMember > 0 && $totalReviewsMember >0){
						if($negativeReviewsMember < $totalReviewsMember){
							$negativePercentage = round(($negativeReviewsMember/$totalReviewsMember)*100);
						}else{
							$negativePercentage = 0;	
						}
					}else{
						$negativePercentage = 0;
					}

					$title = 'Download review';
					$member 	= Hcp::with('company','review','like','dislike')->where('id',$hcpId)->where('status',1)->firstOrFail();
					$review = Review::where('hcp_id',$hcpId)->where('status',1)->get();

					$all_member_list[] = array(
							'review' 			=> $review,
							'member' 			=> $member,
							'title' 			=> $title,
							'hcpId' 			=> $hcpId,
							'negativePercentage'=> $negativePercentage
						);
			}

			//print_r($all_member_list);

			$view = View::make('download_search_report', compact('all_member_list'));
       		$html = $view->render();

       		$pdf = PDF::make();
			$pdf->addPage($html);

			//$pdf->send();
			$fileName = 'search_download_data'.$hcpId.'.pdf';
			$pdf->saveAs(DOCUMENT_ROOT_PATH . $fileName); 

			$headers = [
              'Content-Type' => 'application/pdf',
           ];

		return response()->download(DOCUMENT_ROOT_PATH . $fileName, $fileName, $headers);


	}

	public function get_locations(){
		$data = DB::table('locations')->select('name')->get();
		return Response::json( $data );
	}

	public function departures_filter()
	{

		$from_date=Input::get('from_date');
		$to_date=Input::get('to_date');
		$destination=Input::get('destination');

		$from_date=date("Y-m-d", strtotime($from_date));
		$to_date=date("Y-m-d", strtotime($to_date));

		if($from_date=='1970-01-01')
		{
			$from_date='';	
		}
		if($to_date=='1970-01-01')
		{
			$to_date='';	
		}

		if(!empty($from_date) && !empty($to_date) && !empty($destination))
		{
			//$filterdetails=Setdepartures::whereBetween('tripdates', [$from_date, $to_date])->where('country_id','like',"%".$destination."%")->get();

			$filterdetails=	DB::table('trips')
				->select('trips.image','trips.tripname','trips.description','trips.tripdates','trips.tripdays','trips.baseprice','trips.slug','destination_country.name')
				->join('destination_country','destination_country.id','=','trips.country_id')
				->where('destination_country.active','1')
				->where('trips.is_active','1')
				->whereBetween('trips.tripdates', [$from_date, $to_date])->where('trips.country_id','like',"%".$destination."%")
				->get();
		}
		else if(empty($from_date) && empty($to_date) && !empty($destination))
		{
			//$filterdetails=Setdepartures::where('country_id','like',"%".$destination."%")->get();

			$filterdetails=	DB::table('trips')
				->select('trips.image','trips.tripname','trips.description','trips.tripdates','trips.tripdays','trips.baseprice','trips.slug','destination_country.name')
				->join('destination_country','destination_country.id','=','trips.country_id')
				->where('destination_country.active','1')
				->where('trips.is_active','1')
				->where('trips.country_id',$destination)
				->get();

			
		}
		else if(!empty($from_date) && !empty($to_date) && empty($destination))
		{
			$filterdetails=	DB::table('trips')
				->select('trips.image','trips.tripname','trips.description','trips.tripdates','trips.tripdays','trips.baseprice','trips.slug','destination_country.name')
				->join('destination_country','destination_country.id','=','trips.country_id')
				->where('destination_country.active','1')
				->where('trips.is_active','1')
				->whereBetween('trips.tripdates', [$from_date, $to_date])
				->get();
		}
		else
		{
			$filterdetails=	DB::table('trips')
				->select('trips.image','trips.tripname','trips.description','trips.tripdates','trips.tripdays','trips.baseprice','trips.slug','destination_country.name')
				->join('destination_country','destination_country.id','=','trips.country_id')
				->where('destination_country.active','1')
				->where('trips.is_active','1')
				->get();
		}
		
		return (String)view::make('setdeparture.searchlist',compact('filterdetails'));

		//Log::info((String)view::make('setdeparture.searchlist',compact('filterdetails')));
	}

}// end HomeController class
