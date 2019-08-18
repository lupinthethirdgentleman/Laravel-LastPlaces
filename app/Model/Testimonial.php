<?php 
namespace App\Model; 
use Eloquent;
/**
 * Testimonial Model
 */
 
class Testimonial extends Eloquent  {

	
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
 
	protected $table = 'testimonials';
	
	/**
	 * Function for  bind AdminTestimonialDescription model   
	 *
	 * @param null 
	 *
	 * return query
	 */	
 
	 public function description(){
		 return $this->hasMany('App\Model\TestimonialDescription','parent_id');
	 }//end description()
 
	 /**
	* hasMany  function for bind TestimonialDescription model 
	*
	* @param null
	* 
	* @return query
	*/
	
	public function accordingLanguage()
    {
		$currentLanguageId	=	Session::get('currentLanguageId');
        return $this->hasMany('TestimonialDescription','parent_id')->select('client_name','comment','parent_id')->where('language_id' , $currentLanguageId);
		
    } //end accordingLanguage()
	
	/**
	 * function for find result form database function
	 *
	 * @param $limit 	
	 * @param $fields 	as fields which need to select
	 * 
	 * @return array
	 */	
	public static function getResult($fields = array(),$limit = 2){
	
		$currentLanguageId	=	Session::get('currentLanguageId');
		
		$testimonialResult		=	 Testimonial::with('accordingLanguage')->select($fields)->where('is_active',1)->take($limit)->orderBy('updated_at','DESC')->get()->toArray();
	
		$response	=	array();
		foreach($testimonialResult as $key => $result){
			if (isset($result['according_language']) && (is_array($result))) {
				if(isset($result['according_language'][0]) && !empty($result['according_language'][0])){ 
					$currentLanguageData	=	$result['according_language'][0];
					$response[$key]			=	$currentLanguageData;
					unset($result['according_language']);
				}
			}
		}
		return $response;
		
	} //end getResult()
		
}// end Testimonial class
