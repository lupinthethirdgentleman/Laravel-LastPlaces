<?php
namespace App\Http\Controllers;
use Auth,Blade,Config,Cache,Cookie,DB,File,Hash,Input,Mail,mongoDate,Redirect,Request,Response,Session,URL,View,Validator,Str,App;
use mjanssen\BreadcrumbsBundle\Breadcrumbs as Breadcrumb;
use App\Model\EmailLog;
use App\Model\Clickmap;
use App\Model\Cms;
use App\Model\Meta;
use App\Model\Category;
use App\Http\Controllers\upload;
use App\Model\User;
use App\Model\Language;

/**
 * Base Controller
 *
 * Add your methods in the class below
 *
 * This is the base controller called everytime on every request
 */
 
class BaseController extends Controller {
	
	public function __construct() {
		if(Request::segment(1) == 'admin'){
			
		}

		
		//echo Session::get('currentLanguageId');

		/* For set default language id*/
		if(!Session::has('currentLanguageId')){
			Session::put('currentLanguageId', Config::get('default_language.language_code'));
		}

		$currentLanguageCode 	=	Input::get('lang');
		
		if($currentLanguageCode!=''){
			App::setLocale($currentLanguageCode);
			$currentLanguageDetail 	=   Language::where('lang_code',$currentLanguageCode)
				->first();
			$currentLanguageId		=	$currentLanguageDetail->id; 
			Session::put('currentLanguageId',$currentLanguageId);
			
		}
		$cId = Session::get('currentLanguageId');
		$lDetail = Language::where('id',$cId)
					->first();
		
		App::setLocale($lDetail->lang_code);
		//echo "string"; die;
		//echo Session::get('currentLanguageId');

		if(Request::segment(1) == 'admin'){
			App::setLocale('en');
		}
		
		/* For set meta data */
		if(Request::segment(1) != 'admin'){ 
			$seo_page_file_path = Request::segment(1);
			if($seo_page_file_path == 'pages'){
				$pagePath				= 	Request::segment(2);
				$seoData				=	DB::table('cms_pages')->select('meta_title','meta_description','meta_keywords')->where('slug',$pagePath)->first();
				
				if(!empty($seoData)){
					$title				=	$seoData->meta_title;
					$metaKeywords		=	$seoData->meta_keywords;
					$metaDescription	=	$seoData->meta_description;
				}else{
					$title				=	Config::get("Site.title");
					$metaKeywords		=	Config::get("Site.meta_keywords");
					$metaDescription	=	Config::get("Site.meta_description");
				}
			}else{
				$seoData				=	DB::table('metas')->select('meta_title','description','meta_keyword')->where('page_id',$seo_page_file_path)->first();
				
				if(!empty($seoData)){
					$title				=	$seoData->meta_title;
					$metaKeywords		=	$seoData->meta_keyword;
					$metaDescription	=	$seoData->description;
				}else{
					$title				=	Config::get("Site.title");
					$metaKeywords		=	Config::get("Site.meta_keywords");
					$metaDescription	=	Config::get("Site.meta_description");
				}				
			}
			
			View::share('pageTitle', $title);
			View::share('metaKeywords', $metaKeywords);
			View::share('metaDescription', $metaDescription);
			/* For set meta data */
		}
	}// end function __construct()
	
/**
 * Setup the layout used by the controller.
 *
 * @return layout
 */
	protected function setupLayout(){
		if(Request::segment(1) != 'admin'){
			
		}
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}//end setupLayout()
	

	public function uploder(){
		require_once('upload_class.php');
		
		if(isset($_GET['CKEditorFuncNum'])){
			// where you have put class.upload.php

			$msg = '';                                     // Will be returned empty if no problems
			$callback = ($_GET['CKEditorFuncNum']);        // Tells CKeditor which function you are executing
			if($_FILES['upload']['type']=='image/jpeg' || $_FILES['upload']['type']=='image/jpg' || $_FILES['upload']['type']=='image/gif' || $_FILES['upload']['type']=='image/png'){
			
				$handle = new upload($_FILES['upload']);       // Create a new upload object 
				if ($handle->uploaded) {
					$handle->image_resize         = false;
					$handle->process(CK_EDITOR_ROOT_PATH);  // directory for the uploaded image
					$image_url = CK_EDITOR_URL . $handle->file_dst_name;          // URL for the uploaded image
					if ($handle->processed) {
						 $handle->clean();
					} else {
						$msg =  'error : ' . $handle->error;
					}
				}
				
			}
			else{
				$msg =  'error : Please select image file';
			}
			$output = '<script type="text/javascript">window.parent.CKEDITOR.tools.callFunction('.$callback.', "'.$image_url .'","'.$msg.'");</script>';
			echo $output;
			exit;
		}
		else{
			$this->redirect('/');
		}
	}// end uploder()
	

/** 
 * Function to make slug according model from any certain field
 *
 * @param title     as value of field
 * @param modelName as section model name
 * @param limit 	as limit of characters
 * 
 * @return string
 */	
	public function getSlug($title, $fieldName,$modelName,$limit = 30){
		$slug 		= 	 substr(Str::slug($title),0 ,$limit);
		$Model		=	"\App\Model\\$modelName";
		$slugCount 	=  count($Model::where($fieldName, 'regexp', "/^{$slug}(-[0-9]*)?$/i")->get());
		return ($slugCount > 0) ? $slug."-".$slugCount : $slug;
	}//end getSlug()
	
/** 
 * Function to make slug without model name from any certain field
 *
 * @param title     as value of field
 * @param tableName as table name
 * @param limit 	as limit of characters
 * 
 * @return string
 */	
	public function getSlugWithoutModel($title, $fieldName='' ,$tableName,$limit = 30){ 	
		$slug 		=	substr(Str::slug($title),0 ,$limit);
		$slug 		=	Str::slug($title);
		$DB 		= 	DB::table($tableName);
		$slugCount 	= 	count( $DB->whereRaw("$fieldName REGEXP '^{$slug}(-[0-9]*)?$'")->get() );
		return ($slugCount > 0) ? $slug."-".$slugCount: $slug;
	}//end getSlugWithoutModel()

/** 
 * Function to search result in database
 *
 * @param data  as form data array
 *
 * @return query string
 */		
	public function search($data){
		unset($data['display']);
		unset($data['_token']);
		$ret	=	'';
		if(!empty($data )){
			foreach($data as $fieldName => $fieldValue){
				$ret	.=	"where('$fieldName', 'LIKE',  '%' . $fieldValue . '%')";
			}
			return $ret;
		}
	}//end search()
	
/** 
 * Function to send email form website
 *
 * @param string $to            as to address
 * @param string $fullName      as full name of receiver
 * @param string $subject       as subject
 * @param string $messageBody   as message body
 *
 * @return void
 */
	public function sendMail($to,$fullName,$subject,$messageBody, $from = '',$files = false,$path='',$attachmentName='') {
		$data				=	array();
		$data['to']			=	$to;
		$data['from']		=	(!empty($from) ? $from : Config::get("Site.email"));
		$data['fullName']	=	$fullName;
		$data['subject']	=	$subject;
		$data['filepath']	=	$path;
		$data['attachmentName']	=	$attachmentName;
		if($files===false){
			Mail::send('emails.template', array('messageBody'=> $messageBody), function($message) use ($data){
				$message->to($data['to'], $data['fullName'])->from($data['from'])->subject($data['subject']);

			});
		}else{
			if($attachmentName!=''){
				Mail::send('emails.template', array('messageBody'=> $messageBody), function($message) use ($data){
					$message->to($data['to'], $data['fullName'])->from($data['from'])->subject($data['subject'])->attach($data['filepath'],array('as'=>$data['attachmentName']));
				});
			}else{
				Mail::send('emails.template', array('messageBody'=> $messageBody), function($message) use ($data){
					$message->to($data['to'], $data['fullName'])->from($data['from'])->subject($data['subject'])->attach($data['filepath']);
				});
			}
		}
		 
		 DB::table('email_logs')->insert(
			array(
				'email_to'	 => $data['to'],
				'email_from' => $data['from'],
				'subject'	 => $data['subject'],
				'message'	 =>	$messageBody,
				'created_at' => DB::raw('NOW()')
			)
		); 
	}//end sendMail()
	
/** 
 * Function for user profile
 *
 * param userId as user id
 */ 
	
	
/** 
 * Function to convert video in mp4
 *
 * param source target width and height  
 */ 
	public function convertToMp4($source, $target, $width, $height){
		// $commandString = FFMPEG_CONVERT_COMMAND.'ffmpeg -i '.$source.' -vcodec libx264 -acodec libfaac -qscale 1  -r 30  -ar 44100 -vf scale='.$width.':'.$height.' -async 1  -f mp4 '.$target;
		$commandString 	= FFMPEG_CONVERT_COMMAND.'ffmpeg -i '.$source.' -vcodec libx264 -qscale 1  -r 30  -ar 44100 -vf scale='.$width.':'.$height.' -async 1  -f mp4 '.$target;
		$command 		= exec($commandString);
	}//end convertToMp4()
	
/** 
 * Function to convert video in webm
 *
 * param source target width and height  
 */ 	
	public function convertToWebm($source, $target, $width, $height){
		$commandString 	= FFMPEG_CONVERT_COMMAND.'ffmpeg -i '.$source.' -vcodec libvpx -acodec libvorbis -qscale 1  -r 30 -ar 44100 -vf scale='.$width.':'.$height.' -async 1  -f webm '.$target;
		$command 		= exec($commandString);
	}//end convertToWebm()
	
	
/** 
 *  Function to generate thumbnails from video
 *
 * param source target width and height  
 */ 	
	public function generateThumbnail($source, $target, $width='', $height=''){
		$commandString 	= FFMPEG_CONVERT_COMMAND.'ffmpeg -i '.$source.' -f mjpeg -ss 00:00:01 -frames 1 -vf scale='.$width.':'.$height.' '.$target;
		$command 		= exec($commandString);
	}// end generateThumbnail()
	
/* Function to delete file
 *
 *@param $mainPath and $fileName
 * 
 */
	public function deleteFileRecursive($mainPath, $fileName){
		$commandString 	=	exec('find '.$mainPath.' -type f -name '.$fileName.'.* -exec rm -rf {} \;');
	}// end deleteFileRecursive()

}
// end BaseController class
