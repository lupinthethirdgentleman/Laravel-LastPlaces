<?php
namespace App\Providers;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use View,DB,Session;
use App\Model\Region;
use App\Model\Country;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $DB = Region::query();
        $regionList = $DB->with('Country')
                         ->where('is_active',1)
                         ->get();
        $lang           =   \App::getLocale();               
        $regionDetail =   DB::select( DB::raw("SELECT * FROM region_description WHERE foreign_key in (select id from region where is_active=1) AND language_id = (select id from languages WHERE languages.lang_code = '$lang')") );
        $regionCount = DB::select( DB::raw("SELECT * FROM region where is_active = 1 ") );
        
        $region_result = array();
        $i = 0;
        if(count($regionCount)>0) {
            foreach($regionCount as $rc) {
                $rc_id = $rc->id;
                foreach($regionDetail as $region) {
                    if($rc_id==$region->foreign_key) {
                        $key = $region->source_col_name;
                        $value = $region->source_col_description;
                        $region_result[$i][$region->source_col_name] = $region->source_col_description;
                    }
                }
                $i++;
            }
        }
        //echo "<pre>"; print_r($region_result);die;
        
        View::share('regionList',$regionList);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
		\Blade::setRawTags('{{', '}}');
		\Blade::setContentTags('{{{', '}}}');
		\Blade::setEscapedContentTags('{{{', '}}}');
 
    }
	
}
