<?php
DB::enableQueryLog() ;
Route::get('/base/uploder','BaseController@uploder');
Route::post('/base/uploder','BaseController@uploder'); 
include(app_path().'/global_constants.php');
//include(app_path().'/libraries/CustomHelper.php');

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/


############################################### language routing start here ########################################


### Payment Manager routing
Route::get('/payment-manager','UsersPaymentController@listPayment');
Route::any('/payment-manager/pricing_details/{id}','UsersPaymentController@pricingDetail');

Route::any('checkout', array(
	'as' => 'checkout',
	'uses' => 'PaymentController@checkout'
));

Route::post('checkout-redsys', array(
	'as' => 'checkout',
	'uses' => 'RedSysController@packagePayRedsys'
));

Route::get('savemembership', array(
	'as' => 'savemembership',
	'uses' => 'PaymentController@saveMembership'
));

			
Route::get('language1/{lang}', function($l)
{
    $languages = Cache::get('languages');
    if(array_key_exists($l, $languages)){
        Session::put('language', $l);
        Session::put('currentLanguage', $languages[$l]);
		$allLanguages		=	Cache::get('languageDetails');
		$currentLanguageId	=	$allLanguages[$l];
		Session::put('currentLanguageId', $currentLanguageId);
    }
	return Redirect::back();
});

/*Route::get('template', function(){
	return View::make("emails.template"); }
);*/
############################################### language routing end here ########################################

############################################### Front Routing start here ########################################
	

	Route::group(array('middleware' => 'App\Http\Middleware\GuestFront'), function() {

		
		// logout route
		Route::get('logout', array(
			'as' => 'logout',
			'uses' => 'LoginController@logout'
		));
		

								
		// home route
		Route::get('/', 'HomeController@index');
		Route::get('/blogs', 'HomeController@blogs');
		Route::get('/news', 'HomeController@news');
		Route::get('/setdepartures', 'HomeController@setdepartures');
		Route::post('/setdepartures', 'HomeController@departures_filter');

		Route::post('/save-comment', 'HomeController@saveComment');
		Route::post('/save-comment1', 'HomeController@saveComment1');
		Route::get('/getSuggestionlocation', 'HomeController@get_locations');
		Route::post('/likeOrDislike', 'HomeController@likeOrDislike');
		Route::post('/likeOrDislikeMember', 'HomeController@likeOrDislikeMember');

		//login route
		Route::post('login', array(
			'as' => 'login',
			'uses' => 'LoginController@login'
		));

		Route::get('login', array(
			'as' => 'login-view',
			'uses' => 'LoginController@loginView'
		));
		/*Route::get('add-dahcp', array(
			'as' => 'add-dahcp',
			'uses' => 'AdddahcpController@adddahcpView'
		));*/
		
		// Route::any('login/{validstring}', array(
		// 	'as' => 'login',
		// 	'uses' => 'LoginController@Login'
		// ));
		
		// account regiter,forget password, change password, reset password route 
		Route::any('sign-up', array(
			'as' => 'front-sign-up',
			'uses' => 'RegistrationController@getIndex'
		));
		
		Route::get('account-verification/{validate_string}','RegistrationController@accountVerification');
		Route::post('registration','RegistrationController@postIndex');	
		Route::any('forget_password', array(
			'as' => 'front-forgot-password',
			'uses' => 'LoginController@sendPassword'
		));
		Route::get('send-verification-link/{valistring}','RegistrationController@sendVerificationLink');
		Route::get('send-verification-link','RegistrationController@sendVerificationLink');

		Route::any('send_password','LoginController@sendPassword');
		Route::get('reset_password/{validstring}','LoginController@resetPassword');
		Route::post('save_password/{validstring}','LoginController@resetPasswordSave');
		
		///////////////// cms page /////////////////////////
		//page
		Route::get('/pages/{slug}','HomeController@showCms');
		Route::get('/faq','FaqController@index');
		// resource
		Route::get('resources', 'ResourcesController@index');
		Route::get('resources/downloadfile/{name}/{id}', 'ResourcesController@downloadFile');
		
		Route::any('resources-save/{file}/{id}', 'ResourcesController@saveDownloadFile');
		
		// faq 
		Route::any('faqs','FaqController@index');
		// about us
		Route::get('about-us','HomeController@aboutUs');
		// contact us
		Route::get('contact-us','HomeController@contactUs');
		Route::post('contact-us','HomeController@contactUsPost');
		// how  it work
		Route::any('how-it-works','HomeController@howItWorks');
		// sitemap
		Route::any('sitemap','HomeController@sitemap');
		//pricing
		Route::any('pricing', 'HomeController@pricing');
		// testimonial
		Route::any('testimonials', 'HomeController@allTestimonial');
		// brochure
		Route::any('brochures', 'HomeController@allBrochures');
		Route::any('brochures/{slug}', 'HomeController@brochuresDetail');


		// routes for country design
		Route::get('/country/algeria',array('as'=>'Country.view','uses'=>'CountryController@view'));
		Route::get('/region/africa',array('as'=>'Region.view','uses'=>'RegionController@view'));
		Route::get('/trips/africa/algerian-odyssey',array('as'=>'Trip.view','uses'=>'TripController@view'));
	 //   Route::get('/blogs/blogslug',array('as'=>'Blog.view','uses'=>'BlogController@view'));



		///

		// blog
		/*Route::get('blog/{slug}', 'BlogsController@detail');
		Route::post('blog/{slug}', 'BlogsController@detail');
		Route::get('blog_more_comment/{blog_id}/{offset}','BlogsController@moreComment');
		Route::controller('blog', 'BlogsController');*/
		// news
		// Route::get('news', 'NewsPageController@getIndex');
		// Route::get('news-all', 'NewsPageController@getIndex');
		// Route::get('news/{slug}', 'NewsPageController@detail');
		// free download
		Route::any('free-download', 'FreeDownloadsController@index');
		Route::get('free-download/downloadfile/{name}/{id}', 'FreeDownloadsController@downloadFile');
		//tips
		Route::any('tips/{slug}', 'TipsController@index');
		//video vault
		Route::get('video-vault', 'VideoVaultController@index');
		// newsletter
		Route::post('newsletter_subscription','RegistrationController@newsLetterSubscription');
		Route::get('newsletter-unsubscribe/{id}','RegistrationController@newsLetterUnSubscribe');
		Route::get('newsletter-unsubscribed/{id}','RegistrationController@newsLetterUnSubscribe');
		Route::get('newsletter-verification/{encId}','RegistrationController@newsletterVerification');	
		//job
		Route::any('jobs','JobsController@index');
		Route::any('jobs/save_criteria','JobsController@saveCriteria');
		Route::any('all-jobs','JobsController@jobList');
		Route::any('load-more-indeed-jobs/{start}','JobsController@loadmoreIndeedJobs');
		Route::any('delete-job-alert/{model}/{id}','JobsController@deleteJobAlert');
		Route::any('user-saved-job','JobsController@userSavedJob');
		Route::any('user-apply-job-popup','JobsController@userApplyJobPopup');
		Route::any('apply-job','JobsController@userApplyJob');
		Route::any('apply-resume-for-jobs','JobsController@applyResumeForJobs');
		Route::any('user-brochure-popup','JobsController@userBrochurePopup');
		Route::any('user-save-job-popup','JobsController@userSaveJobPopup');
		
		// city state ajax route
		Route::post('ajaxdata/get_states','AjaxdataController@getStates');
		Route::post('ajaxdata/get_cities','AjaxdataController@getCities');
		Route::post('ajaxdata/get_statelist','AjaxdataController@states');
		Route::post('ajaxdata/get_citieslist','AjaxdataController@cities');
		Route::post('ajaxdata/get_captcha','AjaxdataController@getCaptcha');
		
		Route::any('preview-resume/{validateString}', array(
			'as' => 'preview-brochure',
			'uses' => 'MyBrochureController@previewBrochure'
		));

		Route::any('preview-resume/{validateString}/{download}', array(
			'as' => 'preview-brochure',
			'uses' => 'MyBrochureController@previewBrochure'
		));

		
	});
	
	Route::group(array('middleware' => 'App\Http\Middleware\AuthFront'), function() {
		Route::get('dashboard', array(
			'as' => 'user-dashboard',
			'uses' => 'MyaccountController@index'
		));
		Route::get('manage-profile','MyaccountController@manageProfile');
		Route::post('save-profile','MyaccountController@saveProfile');
		Route::get('add_dahcp','AdddahcpController@adddahcpView');
		Route::post('save_dahcp','AdddahcpController@postIndex');
		Route::get('dahcp/getlocation/{id}','AdddahcpController@GetCountryLocation');
		Route::get('write_review/{hcpId}','HomeController@writeReview');
		Route::get('download_review/{hcpId}','HomeController@downloadReview');

		Route::post('save_review','HomeController@saveReview');
	});


############################################### Front Routing end here ##################################################################


	
################################################################# Admin Routing start here###################################################

Route::get('/base/uploder','BaseController@uploder');
Route::post('/base/uploder','BaseController@uploder'); 

Route::group(array('prefix' => 'admin'), function()
{
	Route::group(array('middleware' => 'App\Http\Middleware\GuestAdmin','namespace'=>'admin'), function() {
		Route::get('','AdminLoginController@login');
		Route::any('/login','AdminLoginController@login');
		Route::get('/forget_password','AdminLoginController@forgetPassword');
		Route::get('/reset_password/{validstring}','AdminLoginController@resetPassword');
		Route::post('/send_password','AdminLoginController@sendPassword');
		Route::post('/save_password','AdminLoginController@resetPasswordSave');
			
		/* Route::any('/lock_screen','AdminLoginController@LockScreen');
		Route::any('/loggedIn','AdminLoginController@LoggedOut'); */
		
	});
	
	Route::group(array('middleware' => 'App\Http\Middleware\AuthAdmin','namespace'=>'admin'), function() {
		/* Demo user Listing */
		Route::get('/user-manager','AdminDashBoardController@usersListing'); 
		
		Route::any('ajaxdata/get_states','AdminAjaxdataController@getStates');
		Route::any('ajaxdata/get_cities','AdminAjaxdataController@getCities');
		
		Route::get('/logout','AdminLoginController@logout');
		Route::get('/dashboard',array('as'=>'admin_dashboard','uses'=>'AdminDashBoardController@showdashboard'));
		//Route::get('/dashboard','AdminDashBoardController@showdashboard');
		Route::get('/myaccount','AdminDashBoardController@myaccount');
		Route::post('/myaccount','AdminDashBoardController@myaccountUpdate');
		Route::get('/destinations','DestinationsController@index');
		###email manager  routing
		
		
		###email manager  routing
		Route::get('/email-manager',array('as'=>'EmailTemplate.index','uses'=>'EmailtemplateController@listTemplate'));
		Route::get('/email-manager/add-template',array('as'=>'EmailTemplate.add','uses'=>'EmailtemplateController@addTemplate'));
		Route::post('/email-manager/add-template','EmailtemplateController@saveTemplate');
		Route::get('/email-manager/edit-template/{id}',array('as'=>'EmailTemplate.edit','uses'=>'EmailtemplateController@editTemplate'));
		Route::post('/email-manager/edit-template/{id}','EmailtemplateController@updateTemplate');
		Route::post('/email-manager/get-constant','EmailtemplateController@getConstant');
		
		Route::any('email-manager/multiple-action','EmailtemplateController@performMultipleAction');
		
		### cms manager  routing
		/*Route::any('/cms-manager',array('as'=>'Cms.index','uses'=>'CmspagesController@listCms'));
		Route::get('cms-manager/add-cms','CmspagesController@addCms');
		Route::post('cms-manager/add-cms','CmspagesController@saveCms');
		Route::get('cms-manager/edit-cms/{id}','CmspagesController@editCms');
		Route::post('cms-manager/edit-cms/{id}','CmspagesController@updateCms');
		Route::get('cms-manager/update-status/{id}/{status}','CmspagesController@updateCmsStatus');
		Route::any('cms-manager/multiple-action','CmspagesController@performMultipleAction');*/
		Route::any('/cms-manager',array('as'=>'Cms.index','uses'=>'CmspagesController@listCms'));
		Route::get('cms-manager/add-cms',array('as'=>'Cms.add','uses'=>'CmspagesController@addCms'));
		Route::post('cms-manager/add-cms',array('as'=>'Cms.save','uses'=>'CmspagesController@saveCms'));
		Route::get('cms-manager/edit-cms/{id}',array('as'=>'Cms.edit','uses'=>'CmspagesController@editCms'));
		Route::post('cms-manager/edit-cms/{id}',array('as'=>'Cms.update','uses'=>'CmspagesController@updateCms'));
		Route::get('cms-manager/update-status/{id}/{status}',array('as'=>'Cms.status','uses'=>'CmspagesController@updateCmsStatus'));
		Route::any('cms-manager/multiple-action',array('as'=>'Cms.Multipleaction','uses'=>'CmspagesController@performMultipleAction'));
		
		### pricing manager  routing
		Route::any('/pricing-manager','PricingController@listPricing');
		Route::get('pricing-manager/edit-pricing/{id}','PricingController@editPricing');
		Route::post('pricing-manager/edit-pricing/{id}','PricingController@updatePricing');
		Route::get('pricing-manager/update-status/{id}/{status}','PricingController@updatePricingStatus');
		### company manager  routing
		/*Route::get('/company-manager','CompanyController@listCompany');
		Route::get('company-manager/add-company','CompanyController@addCompany');
		Route::post('company-manager/add-company','CompanyController@saveCompany');
		Route::get('company-manager/edit-company/{id}','CompanyController@editCompany');
		Route::post('company-manager/edit-company/{id}','CompanyController@updateCompany');
		Route::get('company-manager/update-status/{id}/{status}','CompanyController@updateCompanyStatus');
		Route::delete('company-manager/delete-company/{id}','CompanyController@deleteCompany');
		### job manager  routing
		Route::get('/job-manager','CompanyController@listJob');
		Route::get('job-manager/add-job','CompanyController@addJob');
		Route::post('job-manager/add-job','CompanyController@saveJob');
		Route::get('job-manager/edit-job/{id}','CompanyController@editJob');
		Route::post('job-manager/edit-job/{id}','CompanyController@updateJob');
		Route::get('job-manager/update-job-status/{id}/{status}','CompanyController@updateJobStatus');
		Route::delete('job-manager/delete-job/{id}','CompanyController@deleteJob');*/
		### Payment Manager routing
		Route::get('/payment-manager','UsersPaymentController@listPayment');
		Route::any('/payment-manager/pricing_details/{id}','UsersPaymentController@pricingDetail');
		### Email Logs Manager routing
		Route::get('/email-logs',array('as'=>'EmailLogs.listEmail','uses'=>'EmailLogsController@listEmail'));
		Route::any('/email-logs/email_details/{id}','EmailLogsController@EmailDetail');
		
				### setting manager  routing
		Route::any('/settings',array('as'=>'Setting.index','uses'=>'SettingsController@listSetting'));
		Route::get('/settings/add-setting',array('as'=>'Setting.add','uses'=>'SettingsController@addSetting'));
		Route::post('/settings/add-setting',array('as'=>'Setting.save','uses'=>'SettingsController@saveSetting'));
		Route::get('/settings/edit-setting/{id}',array('as'=>'Setting.edit','uses'=>'SettingsController@editSetting'));
		Route::post('/settings/edit-setting/{id}',array('as'=>'Setting.update','uses'=>'SettingsController@updateSetting'));
		Route::get('/settings/prefix/{slug}',array('as'=>'Setting.prefix_index','uses'=>'SettingsController@prefix'));
		Route::post('/settings/prefix/{slug}',array('as'=>'Setting.prefix_update','uses'=>'SettingsController@updatePrefix'));
		Route::delete('/settings/delete-setting/{id}',array('as'=>'Setting.delete','uses'=>'SettingsController@deleteSetting'));






		Route::get('/menus/{type}',array('as'=>'Menus.index','uses'=>'MenusController@listMenus'));
		Route::post('/menus/{type}','MenusController@listMenus');
		Route::get('/menus/{type}/add-menu','MenusController@addMenu');
		Route::post('/menus/{type}/add-menu','MenusController@addMenu');
		Route::get('/menus/edit-menu/{menuid}/{type}','MenusController@editMenu');
		Route::post('/menus/save-menu/{type}','MenusController@saveMenu');
		Route::post('/menus/edit-menu/{menuid}/{type}','MenusController@editMenu');
		Route::post('/menus/save-menu/{menuid}/{type}','MenusController@updateMenu');
		Route::delete('/menus/delete-menu/{menuid}/{type}','MenusController@deleteMenu');
		Route::get('menus/update-status/{id}/{status}/{type}','MenusController@updateClientStatus');
		
		Route::post('menus/multiple-action','MetaController@performMultipleAction');
		
		###faq  module  routing
		Route::get('/faqs-manager',array('as'=>'Faq.listFaq','uses'=>'FaqsController@listFaq'));
		Route::post('/faqs-manager','FaqsController@listFaq');
		Route::get('faqs-manager/add-faqs','FaqsController@addFaq');
		Route::post('faqs-manager/add-faqs','FaqsController@saveFaq');
		Route::get('faqs-manager/edit-faqs/{id}','FaqsController@editFaq');
		Route::post('faqs-manager/edit-faqs/{id}','FaqsController@updateFaq');
		Route::get('faqs-manager/update-status/{id}/{status}','FaqsController@updateFaqStatus');
		Route::any('faqs-manager/delete-faqs/{id}','FaqsController@deleteFaq');
		Route::get('faqs-manager/view-faqs/{id}','FaqsController@viewFaq');
		
		Route::post('faqs-manager/multiple-action','FaqsController@performMultipleAction');
		
		###slider manager routing 
		Route::get('/slider-manager',array('as'=>'Slider.index','uses'=>'SlidersController@listSlider'));
		Route::get('slider-manager/add-slider',array('as'=>'Slider.add','uses'=>'SlidersController@addSlider'));
		Route::post('slider-manager/add-slider',array('as'=>'Slider.save','uses'=>'SlidersController@saveSlider'));
		Route::get('slider-manager/edit-slider/{id}',array('as'=>'Slider.edit','uses'=>'SlidersController@editSlider'));
		Route::post('slider-manager/edit-slider',array('as'=>'Slider.update','uses'=>'SlidersController@updateSlider'));
		Route::any('slider-manager/delete-slider/{id}',array('as'=>'Slider.delete','uses'=>'SlidersController@deleteSlider'));
		Route::get('slider-manager/update-status/{id}/{status}',array('as'=>'Slider.status','uses'=>'SlidersController@updateSliderStatus'));
		Route::any('slider-manager/change_order',array('as'=>'Slider.change_order','uses'=>'SlidersController@changeSliderOrder'));
		Route::post('slider-manager/multiple-action',array('as'=>'Slider.Multipleaction','uses'=>'SlidersController@performMultipleAction'));
		
		###Blog manager routing 
		Route::get('/blog-manager',array('as'=>'Blog.listBlog','uses'=>'BlogController@listBlog'));
		Route::get('blog-manager/add-blog','BlogController@addBlog');
		Route::post('blog-manager/add-blog',array('as'=>'Blog.add','uses'=>'BlogController@saveBlog'));
		Route::get('blog-manager/edit-blog/{id}','BlogController@editBlog');
		Route::post('blog-manager/edit-blog',array('as'=>'Blog.update','uses'=>'BlogController@updateBlog'));
		Route::get('blog-manager/comment-blog/{id}','BlogController@commentBlog');
		Route::any('/blog-manager/delete-comment/{id}','BlogController@deleteComment');
		Route::any('blog-manager/delete-blog/{id}','BlogController@deleteBlog');
		Route::get('blog-manager/update-status/{id}/{status}','BlogController@updateBlogStatus');
		Route::get('blog-manager/mark-highlight/{id}',array('as'=>'Blog.highlight','uses'=>'BlogController@markHighlight'));
		Route::post('blog-manager/multiple-action','BlogController@performMultipleAction');

		###News manager routing 
		Route::get('/news-manager',array('as'=>'News.listNews','uses'=>'NewsController@listNews'));
		Route::get('news-manager/add-news','NewsController@addNews');
		Route::post('news-manager/add-news',array('as'=>'News.add','uses'=>'NewsController@saveNews'));
		Route::get('news-manager/edit-news/{id}','NewsController@editNews');
		Route::post('news-manager/edit-news',array('as'=>'News.update','uses'=>'NewsController@updateNews'));
		Route::get('news-manager/comment-news/{id}','NewsController@commentNews');
		Route::any('/news-manager/delete-comment/{id}','NewsController@deleteComment');
		Route::any('news-manager/delete-news/{id}','NewsController@deleteNews');
		Route::get('news-manager/update-status/{id}/{status}','NewsController@updateNewsStatus');
		Route::get('news-manager/mark-highlight/{id}',array('as'=>'News.highlight','uses'=>'NewsController@markHighlight'));
		Route::post('news-manager/multiple-action','NewsController@performMultipleAction');

		###Blog manager routing 
		Route::get('header-setting-manager/edit-header-setting/{id}',array('as'=>'HeaderSetting.edit','uses'=>'HeaderSettingController@editHeaderSetting'));
		Route::post('header-setting-manager/edit-header-setting',array('as'=>'HeaderSetting.update','uses'=>'HeaderSettingController@updateHeaderSetting'));

		#Routes for PHOTO Manager
		Route::get('photo-manager/add',array('as'=>'Photo.addPhoto','uses'=>'PhotoController@create'));
		Route::get('photo-manager/list',array('as'=>'Photo.index','uses'=>'PhotoController@index'));
		Route::post('photo-manager/save',array('as'=>'Photo.save','uses'=>'PhotoController@store'));
		Route::get('photo-manager/view-photos/{trip_id}',array('as'=>'Photo.edit','uses'=>'PhotoController@edit'));
		Route::get('photo-manager/delete-photo/{trip_id}',array('as'=>'Photo.delete','uses'=>'PhotoController@destroy'));

		#Routes for PHOTO Country Manager Tribes
		Route::get('photo-country-manager/add',array('as'=>'PhotoCountry.addPhoto','uses'=>'PhotoCountryController@create'));
		Route::get('photo-country-manager/list',array('as'=>'PhotoCountry.index','uses'=>'PhotoCountryController@index'));
		Route::post('photo-country-manager/save',array('as'=>'PhotoCountry.save','uses'=>'PhotoCountryController@store'));
		Route::get('photo-country-manager/view-photos/{country_id}',array('as'=>'PhotoCountry.edit','uses'=>'PhotoCountryController@edit'));
		Route::get('photo-country-manager/delete-photo/{country_id}',array('as'=>'PhotoCountry.delete','uses'=>'PhotoCountryController@destroy'));
		Route::any('photo-country-manager/save-title',array('as'=>'PhotoCountry.savetitle','uses'=>'PhotoCountryController@savetitle'));

		#Routes for PHOTO Country Manager Nature
		Route::get('photo-country-manager-nature/add',array('as'=>'PhotoCountryNature.addPhoto','uses'=>'PhotoCountryNatureController@create'));
		Route::get('photo-country-manager-nature/list',array('as'=>'PhotoCountryNature.index','uses'=>'PhotoCountryNatureController@index'));
		Route::post('photo-country-manager-nature/save',array('as'=>'PhotoCountryNature.save','uses'=>'PhotoCountryNatureController@store'));
		Route::get('photo-country-manager-nature/view-photos/{country_id}',array('as'=>'PhotoCountryNature.edit','uses'=>'PhotoCountryNatureController@edit'));
		Route::get('photo-country-manager-nature/delete-photo/{country_id}',array('as'=>'PhotoCountryNature.delete','uses'=>'PhotoCountryNatureController@destroy'));
		Route::any('photo-country-manager-nature/save-title',array('as'=>'PhotoCountryNature.savetitle','uses'=>'PhotoCountryNatureController@savetitle'));


		### resource slider manager routing 
		Route::get('/resource-slider-manager','SliderResourceController@listSlider');
		Route::get('resource-slider-manager/add-slider','SliderResourceController@addSlider');
		Route::post('resource-slider-manager/add-slider','SliderResourceController@saveSlider');
		Route::get('resource-slider-manager/edit-slider/{id}','SliderResourceController@editSlider');
		Route::post('resource-slider-manager/edit-slider','SliderResourceController@updateSlider');
		Route::delete('resource-slider-manager/delete-slider/{id}','SliderResourceController@deleteSlider');
		Route::get('resource-slider-manager/update-status/{id}/{status}','SliderResourceController@updateSliderStatus');
		
		### contact manager routing 
		Route::any('/contact-manager',array('as'=>'Contact.index','uses'=>'ContactsController@listContact'));
		Route::get('contact-manager/view-contact/{id}',array('as'=>'Contact.view','uses'=>'ContactsController@viewContact'));
		Route::delete('contact-manager/delete-contact/{id}',array('as'=>'Contact.delete','uses'=>'ContactsController@deleteContact'));
		Route::any('/contact-manager/reply-to-user/{id}',array('as'=>'Contact.reply','uses'=>'ContactsController@replyToUser'));

		### enquiry manager routing 
		Route::any('/enquiry-manager',array('as'=>'Enquiry.index','uses'=>'EnquiryController@listEnquiry'));
		Route::get('enquiry-manager/view-enquiry/{id}',array('as'=>'Enquiry.view','uses'=>'EnquiryController@viewEnquiry'));
		Route::delete('enquiry-manager/delete-enquiry/{id}',array('as'=>'Enquiry.delete','uses'=>'EnquiryController@deleteEnquiry'));
		Route::any('/enquiry-manager/reply-to-user/{id}',array('as'=>'Enquiry.reply','uses'=>'EnquiryController@replyToUser'));
		
		## users routing start here //
		Route::get('/users',array('as'=>'Users.index','uses'=>'UsersController@listUsers'));
		Route::post('users','UsersController@listUsers');
		Route::get('users/view-user/{id}','UsersController@viewUser');
		Route::get('users/update-status/{id}/{status}','UsersController@updateUserStatus');
		Route::any('users/delete-user/{id}','UsersController@deleteUser');
		Route::get('users/verify-user/{id}','UsersController@verifiedUser');
		Route::get('users/add-user','UsersController@addUser');
		Route::post('users/add-user','UsersController@saveUser');	
		Route::get('users/edit-user/{id}','UsersController@editUser');
		Route::post('users/edit-user/{id}','UsersController@updateUser');	
		Route::any('users/send-credential/{id}','UsersController@sendCredential');	
		
		Route::post('users/multiple-action','UsersController@performMultipleAction');	
		
		### Language setting start //
		Route::get('/language-settings',array('as'=>'Language.index','uses'=>'LanguageSettingsController@listLanguageSetting'));
		Route::get('/language-settings/add-setting',array('as'=>'Language.add','uses'=>'LanguageSettingsController@addLanguageSetting'));
		Route::post('/language-settings/add-setting',array('as'=>'Language.save','uses'=>'LanguageSettingsController@saveLanguageSetting'));
		Route::get('/language-settings/edit-setting/{id}',array('as'=>'Language.edit','uses'=>'LanguageSettingsController@editLanguageSetting'));
		Route::post('/language-settings/edit-setting/{id}',array('as'=>'Language.update','uses'=>'LanguageSettingsController@updateLanguageSetting'));		
		
		### Template  routing start here 
		Route::get('/news-letter','NewsLetterController@listTemplate');
		Route::post('/news-letter','NewsLetterController@listTemplate');
		Route::get('/news-letter/edit-template/{id}','NewsLetterController@editTemplate');
		Route::post('/news-letter/edit-template/{id}','NewsLetterController@updateTemplate');
		
		Route::get('/news-letter','NewsLetterController@listTemplate');
		Route::get('/news-letter',array('as'=>'NewsLetter.listTemplate','uses'=>'NewsLetterController@listTemplate'));
		Route::post('/news-letter','NewsLetterController@listTemplate');
		Route::get('/news-letter/edit-template/{id}','NewsLetterController@editTemplate');
		Route::post('/news-letter/edit-template/{id}','NewsLetterController@updateTemplate');
		Route::get('/news-letter/newsletter-templates',array('as'=>'NewsTemplates.newsletterTemplates','uses'=>'NewsLetterController@newsletterTemplates'));
		Route::get('/news-letter/add-template','NewsLetterController@addTemplates');
		Route::any('/news-letter/add-subscriber','NewsLetterController@addSubscriber');
		Route::post('/news-letter/add-template','NewsLetterController@saveTemplates');
		Route::get('/news-letter/edit-newsletter-templates/{id}','NewsLetterController@editNewsletterTemplate');
		Route::post('/news-letter/edit-newsletter-templates/{id}','NewsLetterController@updateNewsletterTemplate');
		Route::get('/news-letter/send-newsletter-templates/{id}','NewsLetterController@sendNewsletterTemplate');
		Route::post('/news-letter/send-newsletter-templates/{id}','NewsLetterController@updateSendNewsletterTemplate');
		Route::get('/news-letter/subscriber-list',array('as'=>'Subscriber.subscriberList','uses'=>'NewsLetterController@subscriberList'));
		Route::get('/news-letter/subscriber-active/{id}/{status}','NewsLetterController@subscriberActive');
		Route::any('news-letter/subscriber-delete/{id}','NewsLetterController@subscriberDelete');
		Route::any('news-letter/delete-template/{id}','NewsLetterController@templateDelete');
		Route::any('news-letter/view-subscriber/{id}','NewsLetterController@viewSubscrieber');
		Route::any('news-letter/delete-newsletter-template/{id}','NewsLetterController@deleteNewsTemplate');
		Route::post('news-letter/delete-multiple-subscriber','NewsLetterController@deleteMultipleSubscriber');
		
		##Block manager  module  routing start here
		Route::get('/block-manager',array('as'=>'Block.index','uses'=>'BlockController@listBlock'));
		Route::get('block-manager/add-block',array('as'=>'Block.add','uses'=>'BlockController@addBlock'));
		Route::post('block-manager/add-block',array('as'=>'Block.save','uses'=>'BlockController@saveBlock'));
		Route::get('block-manager/edit-block/{id}',array('as'=>'Block.edit','uses'=>'BlockController@editBlock'));
		Route::post('block-manager/edit-block/{id}',array('as'=>'Block.update','uses'=>'BlockController@updateBlock'));
		Route::get('block-manager/update-status/{id}/{status}',array('as'=>'Block.status','uses'=>'BlockController@updateBlockStatus'));
		Route::any('block-manager/delete-block/{id}',array('as'=>'Block.delete','uses'=>'BlockController@deleteBlock'));		
		Route::post('block-manager/multiple-action',array('as'=>'Block.Multipleaction','uses'=>'BlockController@performMultipleAction'));
		
		##System Doc routing start here
		Route::get('/system-doc-manager',array('as'=>'SystemDoc.index','uses'=>'SystemDocController@listDoc'));
		Route::post('/system-doc-manager','SystemDocController@listDoc');
		Route::get('system-doc-manager/add-doc','SystemDocController@addDoc');
		Route::post('system-doc-manager/add-doc','SystemDocController@saveDoc');
		Route::get('system-doc-manager/edit-doc/{id}','SystemDocController@editDoc');
		Route::post('system-doc-manager/edit-doc/{id}','SystemDocController@updateDoc');
		Route::get('system-doc-manager/update-status/{id}/{status}','SystemDocController@updateDocStatus');
		Route::any('system-doc-manager/delete-doc/{id}','SystemDocController@deleteDoc');		
		Route::post('system-doc-manager/multiple-action','SystemDocController@performMultipleAction');
				
		##Testimonial manager routing 
		Route::any('/testimonial-manager',array('as'=>'Testimonial.index','uses'=>'TestimonialController@listTestimonial'));
		Route::get('testimonial-manager/add-testimonial',array('as'=>'Testimonial.add','uses'=>'TestimonialController@addTestimonial'));
		Route::post('testimonial-manager/add-testimonial',array('as'=>'Testimonial.save','uses'=>'TestimonialController@saveTestimonial'));
		Route::get('testimonial-manager/edit-testimonial/{id}',array('as'=>'Testimonial.edit','uses'=>'TestimonialController@editTestimonial'));
		Route::post('testimonial-manager/edit-testimonial/{id}',array('as'=>'Testimonial.update','uses'=>'TestimonialController@updateTestimonial'));
		Route::get('testimonial-manager/update-status/{id}/{status}',array('as'=>'Testimonial.status','uses'=>'TestimonialController@updateTestimonialStatus'));
		Route::get('testimonial-manager/delete-testimonial/{id}',array('as'=>'Testimonial.delete','uses'=>'TestimonialController@deleteTestimonial'));
		Route::delete('testimonial-manager/delete-testimonial/{id}',array('as'=>'Testimonial.delete','TestimonialController@deleteTestimonial'));
		Route::get('testimonial-manager/mark-highlight/{id}/{status}','TestimonialController@markHighlight');	
		
		## Destination Manager Routing
		Route::any('/region-manager',array('as'=>'Region.index','uses'=>'RegionController@index'));
		Route::get('/region-manager/add-region',array('as'=>'Region.add','uses'=>'RegionController@create'));
		Route::post('/region-manager/save',array('as'=>'Region.save','uses'=>'RegionController@store'));
		Route::get('/region-manager/edit-region/{id}',array('as'=>'Region.edit','uses'=>'RegionController@edit'));
		Route::post('/region-manager/edit-region/{id}',array('as'=>'Region.edit','uses'=>'RegionController@update'));
		Route::get('/region-manager/update-status/{id}/{status}',array('as'=>'Region.status','uses'=>'RegionController@updateStatus'));

		## Trip Manager Routing
	Route::any('/trip-manager',array('as'=>'Trip.index','uses'=>'TripController@index'));
	Route::get('/trip-manager/add-trip',array('as'=>'Trip.add','uses'=>'TripController@create'));
    Route::post('/trip-manager/save',array('as'=>'Trip.save','uses'=>'TripController@store'));
    Route::any('/trip-manager/delete-trip/{id}',array('as'=>'Trip.delete','uses'=>'TripController@deleteTrip'));
    Route::get('/trip-manager/edit-trip/{id}',array('as'=>'Trip.edit','uses'=>'TripController@edit'));
    Route::post('/trip-manager/edit-trip/{id}',array('as'=>'Trip.update','uses'=>'TripController@update'));
  Route::get('/trip-manager/update-status/{id}/{status}',array('as'=>'Trip.status','uses'=>'TripController@updateStatus'));
  Route::get('/trip-reviews/',array('as'=>'Tripreview.index','uses'=>'TripReviewController@index'));
    Route::get('/trip-reviews/add-trip',array('as'=>'Tripreview.add','uses'=>'TripReviewController@create'));
     Route::post('/trip-reviews/save',array('as'=>'Tripreview.save','uses'=>'TripReviewController@store'));
       Route::get('/trip-reviews/edit-review/{id}',array('as'=>'Tripreview.edit','uses'=>'TripReviewController@edit'));
    Route::post('/trip-reviews/edit-review/{id}',array('as'=>'Tripreview.update','uses'=>'TripReviewController@update'));
    Route::get('/trip-reviews/update-status/{id}/{status}',array('as'=>'Tripreview.status','uses'=>'TripReviewController@updateStatus'));















		//News manager  module  routing start here
		Route::get('/news-manager',array('as'=>'News.index','uses'=>'NewsController@listNews'));
		Route::post('/news-manager',array('as'=>'News.index','uses'=>'NewsController@listNews'));
		
		Route::get('news-manager/add-news',array('as'=>'News.add','uses'=>'NewsController@addNews'));
		Route::post('news-manager/add-news',array('as'=>'News.save','uses'=>'NewsController@saveNews'));
		
		Route::get('news-manager/edit-news/{id}',array('as'=>'News.edit','uses'=>'NewsController@editNews'));
		Route::post('news-manager/edit-news/{id}',array('as'=>'News.update','uses'=>'NewsController@updateNews'));
		
		Route::get('news-manager/comment-news/{id}',array('as'=>'News.comment','uses'=>'NewsController@commentNews'));

		Route::get('news-manager/update-status/{id}/{status}',array('as'=>'News.status','uses'=>'NewsController@updateNewsStatus'));
		Route::get('news-manager/mark-highlight/{id}',array('as'=>'News.highlight','uses'=>'NewsController@markHighlight'));
		
		Route::any('news-manager/delete-news/{id}',array('as'=>'News.delete','uses'=>'NewsController@deleteNews'));
		
		Route::post('news-manager/multiple-action',array('as'=>'News.Multipleaction','uses'=>'NewsController@performMultipleAction'));
		Route::get('/news-manager/view-comment/{id}',array('as'=>'News.view_comment','uses'=>'NewsController@viewComments'));
		Route::any('/news-manager/delete-comment/{id}',array('as'=>'News.delete_comment','uses'=>'NewsController@deleteComment'));
		
		//Blog manager  module  routing start here
		Route::get('/blog-manager',array('as'=>'Blog.index','uses'=>'BlogController@listBlog'));
		Route::post('/blog-manager',array('as'=>'Blog.index','uses'=>'BlogController@listBlog'));
		
		Route::get('blog-manager/add-blog',array('as'=>'Blog.add','uses'=>'BlogController@addBlog'));
		Route::post('blog-manager/add-blog',array('as'=>'Blog.save','uses'=>'BlogController@saveBlog'));
		
		Route::get('blog-manager/edit-blog/{id}',array('as'=>'Blog.edit','uses'=>'BlogController@editBlog'));
		Route::post('blog-manager/edit-blog/{id}',array('as'=>'Blog.update','uses'=>'BlogController@updateBlog'));
		
		Route::get('blog-manager/comment-blog/{id}',array('as'=>'Blog.comment','uses'=>'BlogController@commentBlog'));

		Route::get('blog-manager/update-status/{id}/{status}',array('as'=>'Blog.status','uses'=>'BlogController@updateBlogStatus'));
		Route::get('blog-manager/mark-highlight/{id}',array('as'=>'Blog.highlight','uses'=>'BlogController@markHighlight'));
		
		Route::any('blog-manager/delete-blog/{id}',array('as'=>'Blog.delete','uses'=>'BlogController@deleteBlog'));
		
		Route::post('blog-manager/multiple-action',array('as'=>'Blog.Multipleaction','uses'=>'BlogController@performMultipleAction'));
		Route::get('/blog-manager/view-comment/{id}',array('as'=>'Blog.view_comment','uses'=>'BlogController@viewComments'));
		Route::any('/blog-manager/delete-comment/{id}',array('as'=>'Blog.delete_comment','uses'=>'BlogController@deleteComment'));
		
		//Video manager  module  routing start here
		Route::get('/video-manager','VideoController@listVideo');
		Route::post('/video-manager','VideoController@listVideo');
		Route::get('video-manager/add-video','VideoController@addVideo');
		Route::post('video-manager/add-video','VideoController@saveVideo');
		Route::get('video-manager/edit-video/{id}','VideoController@editVideo');
		Route::post('video-manager/edit-video/{id}','VideoController@updateVideo');
		Route::get('video-manager/update-status/{id}/{status}','VideoController@updateVideoStatus');
		Route::get('video-manager/mark-highlight/{id}','VideoController@markHighlight');
		Route::get('video-manager/delete-video/{id}','VideoController@deleteVideo');
		Route::delete('video-manager/delete-video/{id}','VideoController@deleteVideo');
		//Brochure manager  module  routing start here
		Route::get('/brochure-manager','BrochureController@listBrochure');
		Route::post('/brochure-manager','BrochureController@listBrochure');
		Route::get('brochure-manager/add-brochure','BrochureController@addBrochure');
		Route::post('brochure-manager/add-brochure','BrochureController@saveBrochure');
		Route::get('brochure-manager/edit-brochure/{id}','BrochureController@editBrochure');
		Route::post('brochure-manager/edit-brochure/{id}','BrochureController@updateBrochure');
		Route::get('brochure-manager/update-status/{id}/{status}','BrochureController@updateBrochureStatus');
		Route::get('brochure-manager/mark-highlight/{id}','BrochureController@markHighlight');
		Route::get('brochure-manager/delete-brochure/{id}','BrochureController@deleteBrochure');
		Route::delete('brochure-manager/delete-brochure/{id}','BrochureController@deleteBrochure');
		//free download manager  module  routing start here
		Route::get('/freedownload-manager','FreeDownloadController@listFreeDownload');
		Route::post('/freedownload-manager','FreeDownloadController@listFreeDownload');
		Route::get('freedownload-manager/add-freedownload','FreeDownloadController@addFreeDownload');
		Route::post('freedownload-manager/add-freedownload','FreeDownloadController@saveFreeDownload');
		Route::get('freedownload-manager/edit-freedownload/{id}','FreeDownloadController@editFreeDownload');
		Route::post('freedownload-manager/edit-freedownload/{id}','FreeDownloadController@updateFreeDownload');
		Route::get('freedownload-manager/update-status/{id}/{status}','FreeDownloadController@updateFreeDownloadStatus');
		Route::get('freedownload-manager/downloadfile/{name}/{id}', 'FreeDownloadController@downloadFile');
		Route::get('freedownload-manager/mark-highlight/{id}','FreeDownloadController@markHighlight');
		Route::get('freedownload-manager/delete-freedownload/{id}','FreeDownloadController@deleteFreeDownload');
		Route::delete('freedownload-manager/delete-freedownload/{id}','FreeDownloadController@deleteFreeDownload');
		//Resource download manager  module  routing start here
		Route::get('/resource-manager','ResourceController@listResource');
		Route::post('/resource-manager','ResourceController@listResource');
		Route::get('resource-manager/add-resource','ResourceController@addResource');
		Route::post('resource-manager/add-resource','ResourceController@saveResource');
		Route::get('resource-manager/edit-resource/{id}','ResourceController@editResource');
		Route::post('resource-manager/edit-resource/{id}','ResourceController@updateResource');
		Route::get('resource-manager/update-status/{id}/{status}','ResourceController@updateResourceStatus');
		Route::get('resource-manager/downloadfile/{name}/{id}', 'ResourceController@downloadFile');
		Route::get('resource-manager/mark-highlight/{id}','ResourceController@markHighlight');
		Route::get('resource-manager/delete-resource/{id}','ResourceController@deleteResource');
		Route::delete('resource-manager/delete-resource/{id}','ResourceController@deleteResource');
		Route::delete('news-letter/delete-newsletter-template/{id}','NewsLetterController@deleteNewsTemplate');
		
		Route::get('/meta-manager',array('as'=>'Meta.listMeta','uses'=>'MetaController@listMeta'));
		Route::post('/meta-manager','MetaController@listMeta');
		Route::get('meta-manager/add-meta','MetaController@addMeta');
		Route::post('meta-manager/add-meta','MetaController@saveMeta');
		Route::get('meta-manager/edit-meta/{id}','MetaController@editMeta');
		Route::post('meta-manager/edit-meta/{id}','MetaController@updateMeta');
		Route::get('meta-manager/update-status/{id}/{status}','MetaController@updateMetaStatus');
		Route::get('meta-manager/delete-meta/{id}','MetaController@deleteMeta');
		Route::delete('meta-manager/delete-meta/{id}','MetaController@deleteMeta');
		
		Route::post('meta-manager/multiple-action','MetaController@performMultipleAction');
		
		// Dropdown manager  module  routing start here
		

		Route::get('dropdown-manager/add-dropdown/{type}','DropDownController@addDropDown');
		Route::post('dropdown-manager/add-dropdown/{type}','DropDownController@saveDropDown');
		Route::get('dropdown-manager/edit-dropdown/{id}/{type}','DropDownController@editDropDown');
		Route::post('dropdown-manager/edit-dropdown/{id}/{type}','DropDownController@updateDropDown');
		Route::get('dropdown-manager/delete-dropdown/{id}/{type}','DropDownController@deleteDropDown');
		Route::delete('dropdown-manager/delete-dropdown/{id}/{type}','DropDownController@deleteDropDown');
		Route::get('/dropdown-manager/{type}',array('as'=>'DropDown.listDropDown','uses'=>'DropDownController@listDropDown'));
		Route::get('/dropdown-manager/{type}/{isimage}',array('as'=>'DropDown.listDropDown','uses'=>'DropDownController@listDropDown'));
		Route::post('/dropdown-manager/{type}','DropDownController@listDropDown');
		
		Route::any('dropdown-manager/multiple-action/{type}','DropDownController@performMultipleAction');
		
		//Tip  manager  module  routing start here
		Route::get('/tip-manager','TipController@listTipCategory');
		Route::post('/tip-manager','TipController@listTipCategory');
		Route::get('tip-manager/add-tip-category','TipController@addTipCategory');
		Route::post('tip-manager/add-tip-category','TipController@saveTipCategory');
		Route::get('tip-manager/edit-tip-category/{id}','TipController@editTipCategory');
		Route::post('tip-manager/edit-tip-category/{id}','TipController@updateTipCategory');
		Route::get('tip-manager/update-category-status/{id}/{status}','TipController@updateTipCategoryStatus');
		Route::get('tip-manager/delete-tip-category/{id}','TipController@deleteTipCategory');
		Route::delete('tip-manager/delete-tip-category/{id}','TipController@deleteTipCategory');
		Route::get('tip-manager/list-tip/{categoryid}','TipController@listTip');
		Route::post('tip-manager/list-tip/{categoryid}','TipController@listTip');
		Route::get('tip-manager/add-tip/{categoryid}','TipController@addTip');
		Route::post('tip-manager/add-tip/{categoryid}','TipController@saveTip');
		Route::get('tip-manager/edit-tip/{id}/{categoryid}','TipController@editTip');
		Route::post('tip-manager/edit-tip/{id}/{categoryid}','TipController@updateTip');
		Route::get('tip-manager/update-status/{id}/{status}/{categoryid}','TipController@updateTipStatus');
		Route::get('tip-manager/delete-tip/{id}/{categoryid}','TipController@deleteTip');
		Route::delete('tip-manager/delete-tip/{id}/{categoryid}','TipController@deleteTip');		


		//Promo code routing starts
		Route::get('/promocodes-manager',array('as'=>'Promocodes.index','uses'=>'PromocodesController@listPromocodes'));
		Route::post('/promocodes-manager','PromocodesController@listPromocodes');
		Route::get('/promocodes-manager/add-promocodes','PromocodesController@addPromocodes');
		Route::post('/promocodes-manager/add-promocodes','PromocodesController@savePromocodes');
		Route::get('/promocodes-manager/edit-promocodes/{id}','PromocodesController@editPromocodes');
		Route::post('/promocodes-manager/edit-promocodes','PromocodesController@updatePromocodes');
		Route::get('promocodes-manager/delete-promocodes/{id}','PromocodesController@deletePromocodes');
		Route::post('promocodes-manager/delete-promocodes/{id}','PromocodesController@deletePromocodes');
		Route::get('promocodes-manager/update-status/{id}/{status}','PromocodesController@updatePromocodestatus');


		//GraduationCeremony
		Route::get('/ceremony-manager',array('as'=>'Ceremony.index','uses'=>'CeremonyController@listCeremony'));
		Route::post('/ceremony-manager','CeremonyController@listCeremony');
		Route::get('/ceremony-manager/add-ceremony','CeremonyController@addCeremony');
		Route::post('/ceremony-manager/add-ceremony','CeremonyController@saveCeremony');
		Route::get('/ceremony-manager/edit-ceremony/{id}','CeremonyController@editCeremony');
		Route::post('/ceremony-manager/edit-ceremony','CeremonyController@updateCeremony');
		Route::get('ceremony-manager/delete-ceremony/{id}','CeremonyController@deleteCeremony');
		Route::post('ceremony-manager/delete-ceremony/{id}','CeremonyController@deleteCeremony');
		Route::get('ceremony-manager/update-status/{id}/{status}','CeremonyController@updateCeremonystatus');
		Route::get('ceremony-manager/delete-ceremony-image/{id}/{img}','CeremonyController@deleteCeremonyImage');




		// Ceremony Order
		Route::get('/booking-manager',array('as'=>'Booking.index','uses'=>'BookingController@listBooking'));
		Route::post('/booking-manager','BookingController@listBooking');
		Route::get('booking-manager/update-status/{id}/{status}','BookingController@updateBookingstatus');

		// Route::get('/payment-manager',array('as'=>'Payment.index','uses'=>'PaymentController@listPayment'));
		// Route::post('/payment-manager','PaymentController@listPayment');
		

		
		
		
		// Ads  routing start here //
		Route::get('/ads-manager',array('as'=>'Ads.index','uses'=>'AdsController@listAds'));
		Route::post('/ads-manager','AdsController@listAds');
		Route::get('/ads-manager/add-ads','AdsController@addAds');
		Route::post('/ads-manager/add-ads','AdsController@saveAds');
		Route::get('/ads-manager/edit-ads/{id}','AdsController@editAds');
		Route::post('/ads-manager/edit-ads','AdsController@updateAds');
		Route::get('ads-manager/delete-ads/{id}','AdsController@deleteAds');
		Route::post('ads-manager/delete-ads/{id}','AdsController@deleteAds');
		Route::get('ads-manager/update-status/{id}/{status}','AdsController@updateAdstatus');
		
		Route::get('/adspage-manager',array('as'=>'AdsPage.index','uses'=>'AdsController@listAdsPage'));
		Route::post('/adspage-manager','AdsController@listAdsPage');
		Route::get('/adspage-manager/add-ads','AdsController@addAdsPage');
		Route::post('/adspage-manager/add-ads','AdsController@saveAdsPage');
		Route::get('/adspage-manager/edit-ads/{id}','AdsController@editAdsPage');
		Route::post('/adspage-manager/edit-ads','AdsController@updateAdsPage');
		Route::delete('adspage-manager/delete-ads/{id}','AdsController@deleteAdsPage');
		Route::post('adspage-manager/delete-ads/{id}','AdsController@deleteAdsPage');
		Route::get('adspage-manager/update-status/{id}/{status}','AdsController@updateAdstatusPage');
		
		//Route::get('/category-manager/edit-sub-category/{id}','CategoryController@viewSubCategory');
		//text setting
	
		Route::get('text-setting',array('as'=>'TextSetting.index','uses'=>'TextSettingController@textList'));
		Route::get('text-setting/add-new-text','TextSettingController@addText');
		Route::any('text-setting/save-new-text','TextSettingController@saveText');
		Route::any('text-setting/edit-new-text/{id}','TextSettingController@editText');
		Route::any('text-setting/update-new-text/{id}','TextSettingController@updateText');
		Route::any('text-setting/delete-text/{id}','TextSettingController@deleteText');
		
		
		Route::get('jsconstant-setting/{type}',array('as'=>'JsConstant.index','uses'=>'JsConstantController@textList'));
		Route::get('jsconstant-setting/add-new-text/{type}','JsConstantController@addText');
		Route::any('jsconstant-setting/save-new-text/{type}','JsConstantController@saveText');
		Route::any('jsconstant-setting/edit-new-text/{id}/{type}','JsConstantController@editText');
		Route::any('jsconstant-setting/update-new-text/{id}/{type}','JsConstantController@updateText');
		Route::any('jsconstant-setting/delete-text/{id}','JsConstantController@deleteText');
		
		//Media partner routing
		Route::get('media',array('as'=>'Media.index','uses'=>'MediaPartnerController@listPage'));
		Route::get('media/add-media',array('as'=>'Media.add','uses'=>'MediaPartnerController@addMedia'));
		Route::post('media/save-media',array('as'=>'Media.save','uses'=>'MediaPartnerController@saveMediaPartner'));
		Route::any('media/delete-media/{id}',array('as'=>'Media.delete','uses'=>'MediaPartnerController@deleteMedia'));
		Route::get('media/update-status/{id}/{status}',array('as'=>'Media.status','uses'=>'MediaPartnerController@updateMediaStatus'));
		Route::any('media/change_order',array('as'=>'Media.order','uses'=>'MediaPartnerController@changeOrderBy'));
		Route::any('media/multiple-action',array('as'=>'Media.Multipleaction','uses'=>'MediaPartnerController@performMultipleAction'));
		
		// language routing
		Route::get('language',array('as'=>'Language.index','uses'=>'LanguageController@listLanguage'));
		Route::get('language/add-language',array('as'=>'Language.add','uses'=>'LanguageController@addLanguage'));
		Route::post('language/save-language',array('as'=>'Language.save','uses'=>'LanguageController@saveLanguage'));
		Route::any('language/delete-language/{id}',array('as'=>'Language.delete','uses'=>'LanguageController@deleteLanguage'));
		Route::get('language/update-status/{id}/{status}',array('as'=>'Language.status','uses'=>'LanguageController@updateLanguageStatus'));
		Route::any('language/default/{id}/{langCode}/{folderCode}',array('as'=>'Language.update_default','uses'=>'LanguageController@updateDefaultLanguage'));
		Route::any('language/multiple-action',array('as'=>'Language.Multipleaction','uses'=>'LanguageController@performMultipleAction'));
		
		### visitors manager routing 
		Route::get('visitors',array('as'=>'Visitor.index','uses'=>'VisitorController@listVisitor'));
		Route::post('/visitors','VisitorController@listVisitor');
		Route::get('visitors/view-visitor/{id}',array('as'=>'Visitor.detail','uses'=>'VisitorController@viewVisitor'));	

		##Testimonial manager routing 
		Route::get('/testimonial-manager',array('as'=>'Testimonial.index','uses'=>'TestimonialController@listTestimonial'));
		Route::post('/testimonial-manager','TestimonialController@listTestimonial');
		Route::get('testimonial-manager/add-testimonial','TestimonialController@addTestimonial');
		Route::post('testimonial-manager/add-testimonial','TestimonialController@saveTestimonial');
		Route::get('testimonial-manager/edit-testimonial/{id}','TestimonialController@editTestimonial');
		Route::post('testimonial-manager/edit-testimonial/{id}','TestimonialController@updateTestimonial');
		Route::get('testimonial-manager/update-status/{id}/{status}','TestimonialController@updateTestimonialStatus');
		Route::get('testimonial-manager/delete-testimonial/{id}','TestimonialController@deleteTestimonial');
		Route::delete('testimonial-manager/delete-testimonial/{id}','TestimonialController@deleteTestimonial');
	
		##Location routing 
		Route::any('/location',array('as'=>'Country.index','uses'=>'LocationController@countryList'));
		Route::get('location/add-country','LocationController@addCountry');

		##Review routing
		Route::get('list-review','ReviewController@listReview');
		Route::get('delete-review/{id}','ReviewController@deleteReview');
		Route::get('list-review/update-status/{id}','ReviewController@updateStatus');
		Route::get('list-review/view-review/{id}','ReviewController@viewReview');

		##companyLocation routing
		Route::get('list-location','CompanyLocationController@listLocation');
		Route::post('save-location','CompanyLocationController@saveLocation');
		Route::get('list-location/add-location','CompanyLocationController@addLocation');
		Route::get('list-location/edit-location/{id}','CompanyLocationController@editLocation');
		Route::post('update-location/{id}','CompanyLocationController@updateLocation');
		Route::get('list-location/update-status/{id}','CompanyLocationController@updateStatus');
		Route::get('list-location/update-status/{id}/{status}','CompanyLocationController@updateLocationStatus');
		Route::get('delete-location/{id}','CompanyLocationController@deleteLocation');
		Route::get('list-location/view-location/{id}','CompanyLocationController@viewLocation');

		/*Route::get('get-company','CompanyLocationController@someFunction');*/

		##Company rounting
		Route::get('list-company','CompanyController@listCompany');
		Route::post('save-company','CompanyController@saveCompany');
		Route::get('list-company/add-company','CompanyController@addCompany');
		Route::get('list-company/edit-company/{id}','CompanyController@editCompany');
		Route::post('update-company/{id}','CompanyController@updateCompany');
		Route::get('list-company/update-status/{id}/{status}','CompanyController@updateCompanyStatus');
		Route::get('delete-company/{id}','CompanyController@deleteCompany');
		Route::get('list-company/view-company/{id}','CompanyController@viewCompany');

		##HCP routing
		Route::get('list-hcp',array('as'=>'Hcp.index','uses'=>'HcpController@listHcp'));
		Route::post('save-hcp','HcpController@saveHcp');
		Route::get('list-hcp/add-hcp','HcpController@addHcp');
		Route::get('list-hcp/update-status/{id}','HcpController@updateStatus');
		Route::get('list-hcp/edit-hcp/{id}','HcpController@editHcp');
		Route::post('update-hcp/{id}','HcpController@updateHcp');
		//Route::get('list-hcp/update-status/{id}/{status}','HcpController@updateHcpStatus');
		Route::get('delete-hcp/{id}','HcpController@deleteHcp');
		Route::get('list-hcp/view-hcp/{id}','HcpController@viewHcp');
		Route::get('list-hcp/getlocation/{id}','HcpController@GetCountryLocation');

		##Destination Country routing
		Route::get('list-destination-country',array('as'=>'DestinationCountry.index','uses'=>'DestinationCountryController@listDestinationCountry'));
		Route::post('save-destination-country','DestinationCountryController@saveDestinationCountry');
		Route::get('list-destination-country/add-destination-country','DestinationCountryController@addDestinationCountry');
		Route::get('list-destination-country/update-status/{id}','DestinationCountryController@updateStatus');
		Route::get('list-destination-country/edit-destination-country/{id}','DestinationCountryController@editDestinationCountry');
		Route::post('update-destination-country/{id}','DestinationCountryController@updateDestinationCountry');
		Route::get('list-destination-country/mark-highlight/{id}',array('as'=>'DestinationCountry.highlight','uses'=>'DestinationCountryController@markHighlight'));
		//Route::get('list-hcp/update-status/{id}/{status}','HcpController@updateHcpStatus');
		Route::get('delete-destination-country/{id}','DestinationCountryController@deleteDestinationCountry');
		Route::get('list-destination-country/view-destination-country/{id}','DestinationCountryController@viewDestinationCountry');

		##Trip Package routing
		Route::get('list-trip-package',array('as'=>'TripPackage.index','uses'=>'TripPackageController@listTripPackage'));
		Route::post('save-trip-package','TripPackageController@saveTripPackage');
		Route::get('list-trip-package/add-trip-package','TripPackageController@addTripPackage');
		Route::get('list-trip-package/update-status/{id}','TripPackageController@updateStatus');
		Route::get('list-trip-package/edit-trip-package/{id}','TripPackageController@editTripPackage');
		Route::post('update-trip-package/{id}','TripPackageController@updateTripPackage');
		//Route::get('list-hcp/update-status/{id}/{status}','HcpController@updateHcpStatus');
		Route::get('delete-trip-package/{id}','TripPackageController@deleteTripPackage');
		Route::get('list-trip-package/view-trip-package/{id}','TripPackageController@viewTripPackage');

		##Trip Status routing
		Route::get('list-trip-status',array('as'=>'TripStatus.index','uses'=>'TripStatusController@listTripStatus'));
		Route::post('save-trip-status','TripStatusController@saveTripStatus');
		Route::get('list-trip-status/add-trip-status','TripStatusController@addTripStatus');
		Route::get('list-trip-status/update-status/{id}','TripStatusController@updateStatus');
		Route::get('list-trip-status/edit-trip-status/{id}','TripStatusController@editTripStatus');
		Route::post('update-trip-status/{id}','TripStatusController@updateTripStatus');
		//Route::get('list-hcp/update-status/{id}/{status}','HcpController@updateHcpStatus');
		Route::get('delete-trip-status/{id}','TripStatusController@deleteTripStatus');
		Route::get('list-trip-status/view-trip-status/{id}','TripStatusController@viewTripStatus');

	/*	Route::get('testimonial-manager/add-testimonial','TestimonialController@addTestimonial');
		Route::post('testimonial-manager/add-testimonial','TestimonialController@saveTestimonial');
		Route::get('testimonial-manager/edit-testimonial/{id}','TestimonialController@editTestimonial');
		Route::post('testimonial-manager/edit-testimonial/{id}','TestimonialController@updateTestimonial');
		Route::get('testimonial-manager/update-status/{id}/{status}','TestimonialController@updateTestimonialStatus');
		Route::get('testimonial-manager/delete-testimonial/{id}','TestimonialController@deleteTestimonial');
		Route::delete('testimonial-manager/delete-testimonial/{id}','TestimonialController@deleteTestimonial'); */
		
		
	});
});



Route::any('verifyAccount/{validstring}', array(
			'uses' => 'ApiController@verifyAccount'
		));

 /*Route::get('add_dahcp', function () {
     return view('add_dahcp');
 });*/



	

Route::get('/member_list','HomeController@memberList');
Route::get('/member_list_location','HomeController@memberListLocation');
Route::get('download_search_result/','HomeController@downloadSearchResult');

Route::get('/testimonial_list','HomeController@TestimonialList');

############################################### language switcher routing start here ########################################
Route::get('locale/{locale}', function($locale)
{
   	Session::put('locale', $locale);
		
	return Redirect::back();
});

/*Route::get('template', function(){
	return View::make("emails.template"); }
);*/
############################################### language switcher routing end here ########################################

Route::post('/language', array(
	'Middleware'=>'Localization',
	'uses'=>'LanguageSwitcherController@index'
));

Route::get('region-country/{region}','RegionController@View');
Route::get('country-trips/{country}','CountryController@View');
Route::get('trips/{country}/{trip}','TripController@View');
Route::get('blogs/{slug}','BlogController@View');
Route::get('news/{slug}','NewsController@View');
//Route::get('/blogs/blogslug',array('as'=>'Blog.view','uses'=>'BlogController@view'));

#online booking routes  
Route::get('online-booking/','BookingController@View');
Route::get('package-booking/{trip_id}/{package_id}','BookingController@BookPackage');
Route::post('package-booking/book','BookingController@initiateBooking');
Route::post('save-booking','BookingController@SaveBooking');
Route::post('save-tailored-booking','BookingController@saveTailoredBooking');

Route::get('saveuntailoredbooking', array(
	'as' => 'saveuntailoredbooking',
	'uses' => 'BookingController@saveUntailoredBooking'
));
Route::get('online-booking/getCompany/{region_id}','BookingController@GetCompanyDetail');
Route::get('online-booking/GetRegionTrips/{destination_id}','BookingController@GetRegionTrips');
Route::get('online-booking/GetTripDetails/{trip_id}','BookingController@GetTripDetails');

#Custom Payment
Route::get('custom-payment/','CustomPaymentController@View');
Route::post('save-custom-payment','CustomPaymentController@savePayment');
Route::get('custom-redsys-response-ok','CustomPaymentController@getOkResponse');
Route::get('custom-redsys-response-ko','CustomPaymentController@getKoResponse');
Route::get('custom-package-redsys-response-ok','CustomPaymentController@getOkResponseFromPackage');
Route::get('custom-package-redsys-response-ko','CustomPaymentController@getKoResponseFromPackage');


#routes for photo gallery
Route::get('photo-gallery/tribes','PhotoGalleryController@View');
Route::get('photo-gallery/nature','PhotoGalleryController@View');


#routes for photo gallery
Route::get('trip-enquiry/{trip_id}','HomeController@TripEnquiry');
Route::post('save-trip-enquiry','HomeController@SaveTripEnquiry');

//Routes of RedSys Pay
Route::post('redsys-pay','RedSysController@payWithRedSys');
Route::get('redsys-response-ok','RedSysController@getOkResponse');
Route::get('redsys-response-ko','RedSysController@getKoResponse');
Route::get('package-redsys-response-ok','RedSysController@getOkResponseFromPackage');
Route::get('package-redsys-response-ko','RedSysController@getKoResponseFromPackage');







