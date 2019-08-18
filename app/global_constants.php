<?php
/* Global constants for site */
define('FFMPEG_CONVERT_COMMAND', '');


define("ADMIN_FOLDER", "admin/");
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', base_path());
define('APP_PATH', app_path());


	
define('WEBSITE_URL', url('/').'/');
define('WEBSITE_JS_URL', WEBSITE_URL . 'js/');
define('WEBSITE_CSS_URL', WEBSITE_URL . 'css/');
define('WEBSITE_IMG_URL', WEBSITE_URL . 'img/');
define('WEBSITE_UPLOADS_ROOT_PATH', ROOT . DS . 'uploads' .DS );
define('WEBSITE_UPLOADS_URL', WEBSITE_URL . 'uploads/');

define('WEBSITE_ADMIN_URL', WEBSITE_URL.ADMIN_FOLDER );
define('WEBSITE_ADMIN_IMG_URL', WEBSITE_ADMIN_URL . 'img/');
define('WEBSITE_ADMIN_JS_URL', WEBSITE_ADMIN_URL . 'js/');
define('WEBSITE_ADMIN_FONT_URL', WEBSITE_ADMIN_URL . 'fonts/');
define('WEBSITE_ADMIN_CSS_URL', WEBSITE_ADMIN_URL . 'css/');

define('SETTING_FILE_PATH', APP_PATH . DS . 'settings.php');
define('MENU_FILE_PATH', APP_PATH . DS . 'menus.php');

define('CK_EDITOR_URL', WEBSITE_UPLOADS_URL . 'ckeditor_pic/');
define('CK_EDITOR_ROOT_PATH', WEBSITE_UPLOADS_ROOT_PATH . 'ckeditor_pic' . DS);


define('SLIDER_URL', WEBSITE_UPLOADS_URL . 'slider/');
define('SLIDER_ROOT_PATH', WEBSITE_UPLOADS_ROOT_PATH .  'slider' . DS); 


define('USER_PROFILE_IMAGE_URL', WEBSITE_UPLOADS_URL . 'user/');
define('USER_PROFILE_IMAGE_ROOT_PATH', WEBSITE_UPLOADS_ROOT_PATH .  'user' . DS); 

define('CEREMONY_EVENT_IMAGE_URL', WEBSITE_UPLOADS_URL . 'event/');
define('CEREMONY_EVENT_ROOT_PATH', WEBSITE_UPLOADS_ROOT_PATH .  'event' . DS); 

define('ADS_IMAGE_URL', WEBSITE_UPLOADS_URL . 'ads/');
define('ADS_IMAGE_ROOT_PATH', WEBSITE_UPLOADS_ROOT_PATH .  'ads' . DS); 

define('MEDIA_IMAGE_URL', WEBSITE_UPLOADS_URL . 'media_partner/');
define('MEDIA_IMAGE_ROOT_PATH', WEBSITE_UPLOADS_ROOT_PATH .  'media_partner' . DS); 

define('BLOG_IMAGE_URL', WEBSITE_UPLOADS_URL . 'blog/');
define('BLOG_IMAGE_ROOT_PATH', WEBSITE_UPLOADS_ROOT_PATH .  'blog' . DS);

define('NEWS_IMAGE_URL', WEBSITE_UPLOADS_URL . 'news/');
define('NEWS_IMAGE_ROOT_PATH', WEBSITE_UPLOADS_ROOT_PATH .  'news' . DS);

define('HEADER_IMAGE_URL', WEBSITE_UPLOADS_URL . 'header/');
define('HEADER_IMAGE_ROOT_PATH', WEBSITE_UPLOADS_ROOT_PATH .  'header' . DS); 


define('MASTERS_IMAGE_URL', WEBSITE_UPLOADS_URL . 'masters/');
define('MASTERS_IMAGE_ROOT_PATH', WEBSITE_UPLOADS_ROOT_PATH .  'masters' . DS); 


define('COMPANY_IMAGE_URL', WEBSITE_UPLOADS_URL . 'company/');
define('COMPANY_IMAGE_ROOT_PATH', WEBSITE_UPLOADS_ROOT_PATH .  'company' . DS); 

define('HCP_IMAGE_URL', WEBSITE_UPLOADS_URL . 'hcp/');
define('HCP_IMAGE_ROOT_PATH', WEBSITE_UPLOADS_ROOT_PATH .  'hcp' . DS);

define('TESTIMONIAL_IMAGE_URL', WEBSITE_UPLOADS_URL . 'testimonial/');
define('TESTIMONIAL_IMAGE_ROOT_PATH', WEBSITE_UPLOADS_ROOT_PATH .  'testimonial' . DS);

define('COUNTRY_IMAGE_URL', WEBSITE_UPLOADS_URL . 'country/');
define('COUNTRY_IMAGE_ROOT_PATH', WEBSITE_UPLOADS_ROOT_PATH .  'country' . DS);

define('HEADER_COUNTRY_IMAGE_URL', WEBSITE_UPLOADS_URL . 'headerimage/');
define('HEADER_COUNTRY_IMAGE_ROOT_PATH', WEBSITE_UPLOADS_ROOT_PATH .  'headerimage' . DS); 

define('TRIP_HEADER_IMAGE_URL', WEBSITE_UPLOADS_URL . 'tripheaderimage/');
define('TRIP_HEADER_IMAGE_ROOT_PATH', WEBSITE_UPLOADS_ROOT_PATH .  'tripheaderimage' . DS); 

define('TRIP_IMAGE_URL', WEBSITE_UPLOADS_URL . 'trip/');
define('TRIP_IMAGE_ROOT_PATH', WEBSITE_UPLOADS_ROOT_PATH .  'trip' . DS);

define('PHOTOGALLERY_IMAGE_URL', WEBSITE_UPLOADS_URL . 'photogallery/');
define('PHOTOGALLERY_IMAGE_ROOT_PATH', WEBSITE_UPLOADS_ROOT_PATH .  'photogallery' . DS);

//define('PHOTONATUREGALLERY_IMAGE_URL', WEBSITE_UPLOADS_URL . 'photonaturegallery/');
//define('PHOTONATUREGALLERY_IMAGE_ROOT_PATH', WEBSITE_UPLOADS_ROOT_PATH .  'photonaturegallery' . DS);

//define('PHOTOCOUNTRYGALLERY_IMAGE_URL',WEBSITE_UPLOADS_URL.'photocountrygallery/');
//define('PHOTOCOUNTRYGALLERY_IMAGE_ROOT_PATH', WEBSITE_UPLOADS_ROOT_PATH .  'photocountrygallery' . DS);

define('DOCUMENT_ROOT_PATH', WEBSITE_UPLOADS_ROOT_PATH .  'document' . DS); 

$config	=	array();

define('ALLOWED_TAGS_XSS', '<a><strong><b><p><br><i><font><img><h1><h2><h3><h4><h5><h6><span></div><em><table><ul><li><section><thead><tbody><tr><td><iframe><pre>');

define('ADMIN_ID', 1);
define('ADMIN_USER', 1);
define('FRONT_USER', 2);

Config::set("Site.currency", "€");
Config::set("Site.currencyCode", "EUR");

Config::set('defaultLanguage', 'English');
Config::set('defaultLanguageCode', 'en');
Config::set("redsys.url_ok", URL::to('/redsys-response-ok'));
Config::set("redsys.url_ko", URL::to('/redsys-response-ko'));

Config::set("redsys.url_ok_package", URL::to('/package-redsys-response-ok'));
Config::set("redsys.url_ko_package", URL::to('/package-redsys-response-ko'));

//Custom Credit Payment
Config::set("custom-redsys.url_ok", URL::to('/custom-redsys-response-ok'));
Config::set("custom-redsys.url_ko", URL::to('/custom-redsys-response-ko'));
Config::set("custom-redsys.url_ok_package", URL::to('/custom-package-redsys-response-ok'));
Config::set("custom-redsys.url_ko_package", URL::to('/custom-package-redsys-response-ko'));



Config::set('default_language.message', 'All the fields in English language are mandatory.');

Config::set('newsletter_template_constant',array('USER_NAME'=>'USER_NAME','TO_EMAIL'=>'TO_EMAIL','WEBSITE_URL'=>'WEBSITE_URL','UNSUBSCRIBE_LINK'=>'UNSUBSCRIBE_LINK'));


//////////////// extension 

define('IMAGE_EXTENSION','jpeg,jpg,png,gif,bmp');
define('PDF_EXTENSION','pdf');
define('DOC_EXTENSION','doc,xls');
define('VIDEO_EXTENSION','mpeg,avi,mp4,webm,flv,3gp,m4v,mkv,mov,moov');


define('TEXT_ADMIN_ID',1);
define('TEXT_FRONT_USER_ID',2);


define('IMAGE_INFO', '<div class="mws-form-message info">
	<a class="close pull-right" href="javascript:void(0);">&times;</a>
	<ul style="padding-left:12px">
		<li>Allowed file types are gif, jpeg, png, jpg.</li>
		<li>Large files may take some time to upload so please be patient and do not hit reload or your back button</li>
	</ul>
</div>');

define('HEADER_IMAGE_INFO', '<div class="mws-form-message info">
	<a class="close pull-right" href="javascript:void(0);">&times;</a>
	<ul style="padding-left:12px">
		<li>Allowed file types are gif, jpeg, jpg.</li>
		<li>Large files may take some time to upload so please be patient and do not hit reload or your back button</li>
		<li>File Size Must be 1900X800 or greater.</li>
	</ul>
</div>');

define('VIDEO_INFO', '<div class="mws-form-message info">
	<a class="close pull-right" href="javascript:void(0);">&times;</a>
	<ul style="padding-left:12px">
		<li>Allowed video types are '.VIDEO_EXTENSION.'</li>
		<li>Large files may take some time to upload so please be patient and do not hit reload or your back button</li>
	</ul>
</div>');

define('DOC_INFO', '<div class="mws-form-message info">
	<a class="close pull-right" href="javascript:void(0);">&times;</a>
	<ul style="padding-left:12px">
		<li>Allowed doc types are '.DOC_EXTENSION.'</li>
		<li>Large files may take some time to upload so please be patient and do not hit reload or your back button</li>
	</ul>
</div>');


Config::set('text_search',array(
	'dashboard.'		=> 'Dashboard',
	'user_managmt.'		=> 'User Management',
	'ads_manager.'		=> 'Ads Manager',
	'media_partner.'	=> 'Media Partner',
	'language_manager.'	=> 'Lanaguage Manager',
	'masters.'			=> 'Masters',
	'management.'		=> 'Management',
	'settings.'			=> 'Settings',
	'Blog.'				=> 'Blogs',
	'Block.'			=> 'Block',
	'Visitor.'			=> 'Visitor',
	'Testimonial.'		=> 'Testimonial',
	'Contact.'			=> 'Contact',
	'Slider.'			=> 'Slider',
));	

/**  System document url path **/
if (!defined('SYSTEM_DOCUMENT_URL')) {
    define('SYSTEM_DOCUMENT_URL', WEBSITE_UPLOADS_URL . 'systemdocuments/');
}

/**  System document upload directory path **/
if (!defined('SYSTEM_DOCUMENTS_UPLOAD_DIRECTROY_PATH')){
    define('SYSTEM_DOCUMENTS_UPLOAD_DIRECTROY_PATH', WEBSITE_UPLOADS_ROOT_PATH . 'systemdocuments' . DS);
}

/**  Active Inactive global constant **/
define('ACTIVE',1);
define('INACTIVE',0);

define('NEGATIVE_REVIEW_NUMBER',2);



	
	

	

	
	

	
 
