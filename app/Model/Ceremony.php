<?php
namespace App\Model; 
use Eloquent;
/**
 * Ads Model
 */
class Ceremony extends Eloquent {

/**
 * The database table used by the model.
 *
 * @var string
 */
	protected $table = 'ceremony';

	protected $appends = ['ceremony_image'];

    
	public function getCeremonyImageAttribute(){
		if($this->image !=''){
			$imgArray = explode(',', $this->image);
			foreach ($imgArray as $key => $value) {
				if(file_exists(CEREMONY_EVENT_ROOT_PATH.$value)){
					$image1[] = CEREMONY_EVENT_IMAGE_URL.$value;	
				}
			}
		}else{
			$image1 = array(WEBSITE_URL . 'img/no_image.png');
		}
		return $image1;    
	}
	
}// end Ads class
