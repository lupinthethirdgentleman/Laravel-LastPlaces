@extends('layouts.inner')
@section('content')
<style>
.product-slider { padding: 45px; }

.product-slider #carousel { border: 4px solid #1089c0; margin: 0; }

.product-slider #thumbcarousel { margin: 12px 0 0; padding: 0 45px; }

.product-slider #thumbcarousel .item { text-align: center; }

.product-slider #thumbcarousel .item .thumb { border: 4px solid #cecece; width: 20%; margin: 0 2%; display: inline-block; vertical-align: middle; cursor: pointer; max-width: 98px; }

.product-slider #thumbcarousel .item .thumb:hover { border-color: #1089c0; }

.product-slider .item img { width: 100%; height: auto; }

.carousel-control { color: #0284b8; text-align: center; text-shadow: none; font-size: 30px; width: 30px; height: 30px; line-height: 20px; top: 23%; }

.carousel-control:hover, .carousel-control:focus, .carousel-control:active { color: #333; }

.carousel-caption, .carousel-control .fa { font: normal normal normal 30px/26px FontAwesome; }
.carousel-control { background-color: rgba(0, 0, 0, 0); bottom: auto; font-size: 20px; left: 0; position: absolute; top: 30%; width: auto; }

.carousel-control.right, .carousel-control.left { background-color: rgba(0, 0, 0, 0); background-image: none; }


/* Always set the map height explicitly to define the size of the div
* element that contains the map. */
#map {
  /*height: 79%;
  width: 100%;
  position: absolute;*/
}

.header_hwe
{
    margin-top: -250px;
    color: #FFFF;
}
</style>
<?php 
//use Illuminate\Support\Facades\Crypt;

?>
<?php 
  function isAMultipleOf4($n) 
  { 
        
      // if true, then 'n'  
      // is a multiple of 4 
      if (($n & 3) == 0) 
          return true; 
    
      // else 'n' is not  
      // a multiple of 4 
      return false; 
  }
?>
<div class="page_title">
 <img src='<?php if($result['header_image'] != '' && File::exists(TRIP_HEADER_IMAGE_ROOT_PATH.$result['header_image'])) {echo TRIP_HEADER_IMAGE_URL.$result['header_image'];} else { echo WEBSITE_IMG_URL."inner_pages.jpg";}?>' style="width:100%;height:250px;"/>
    <div class="container">
        <div class="row">
            <div class="col-sm-offset-4 header_hwe">
                <h1>{{ isset($result['tripname']) ? $result['tripname'] : ''}} </h1>
            </div>
        </div>
    </div>
</div>
<div class="landing_page">
  <div class="container">
  	<div class="row">
    	<div class="col-sm-4">
        	<div class="trip_list_sidebar">
                  <h2>{{ trans('messages.otherdestinations') }}</h2>
                   @foreach($countryListToFetch as $countryList)

                   @if($countryList->image != '' && File::exists(COUNTRY_IMAGE_ROOT_PATH.$countryList->image))
						<div class="sidebar-last-place">	
         				 <p class="country_sidebar_image"> <a href="{{ URL::to('country-trips/' . $countryList->slug) }}"> <img src="<?php echo COUNTRY_IMAGE_URL.$countryList->image; ?>" width="550" height="480"  alt="" class="img-responsive"/> </a>
                 </p>
                     <p class="country_sidebar_name"><a href="{{ URL::to('country-trips/' . $countryList->slug) }}">{{$countryList->destinationCountryDescription[0]->source_col_description}}</a></p>
                     <div class="country_sidebar_desc">{{$countryList->destinationCountryDescription[1]->source_col_description}}</div>
            </div>
                    @else
                  	 <p> <a href="{{ URL::to('country-trips/' . $countryList->slug) }}">{{ HTML::image('img/noimg.png', '', array('width' => '80', 'height' => '80' )) }} </a></p>
                     <p><a href="{{ URL::to('country-trips/' . $countryList->slug) }}">{{$countryList->name}}</a></p>
                     @endif
                   @endforeach
            </div>
        </div>
    	<div class="col-sm-8">
        	<div class="landing_text" style="margin-bottom:10px;">
              <div class="trip_detail_image">
                        <?php 
                            $j=0;
                            $totalPhotos = $photoGalleryObj->count();
                            $alreadyExist = array();
                            if($totalPhotos > 0){
                          ?>

                  <div class="product-slider">
                    <div id="carousel" class="carousel slide" data-ride="carousel">
                      <div class="carousel-inner">
                        <?php $i=0;?>
                         @foreach($photoGalleryObj as $photoGallery)
                            <?php $activeClass = ($i==0)?'active':'';?>  
                            <div class="item <?php echo $activeClass ;?>">
                              <!--
                               <img src="<?php echo PHOTOGALLERY_IMAGE_URL.$photoGallery->image; ?>"> 

                             -->
                              <img src="<?php echo WEBSITE_URL .'image.php?width=650px&height=400px&cropratio=13:8&image=' . PHOTOGALLERY_IMAGE_URL.$photoGallery->image;?>">
                            </div>
                          <?php $i++;?>  
                         @endforeach
                      </div>
                    </div>
                    <div class="clearfix">
                      <div id="thumbcarousel" class="carousel slide" data-interval="false">
                        <div class="carousel-inner">
                
                          <div class="item active">
                              @foreach($photoGalleryObj as $photoGallery)
                                <div data-target="#carousel" data-slide-to="{{$j}}" class="thumb">
                              <!--    <img src="<?php echo PHOTOGALLERY_IMAGE_URL.$photoGallery->image; ?>">-->
                                <img src="<?php echo WEBSITE_URL .'image.php?width=100px&height=70px&cropratio=10:7&image=' . PHOTOGALLERY_IMAGE_URL.$photoGallery->image;?>">
                                </div>
                                <?php 
                                 array_push($alreadyExist, $photoGallery->image);
                                  $j++; 
                                  if($j >=4){
                                    break;
                                  }
                                ?>
                              @endforeach
                          </div>
                          <?php 
                            if($totalPhotos >4){

                              $inActive = '<div class="item">';
                              $checkLine = 0;
                              foreach($photoGalleryObj as $photoGallery){

                                if($checkLine>0){
                                  if(isAMultipleOf4($checkLine) == 'yes'){
                                    $inActive .= '</div><div class="item">';    
                                  }
                                }

                                if(!in_array($photoGallery->image,$alreadyExist)){
                                    
                                    $inActive .= '<div data-target="#carousel" data-slide-to="'.$j.'" class="thumb"><img src="'.WEBSITE_URL .'image.php?width=100px&height=70px&cropratio=10:7&image=' . PHOTOGALLERY_IMAGE_URL.$photoGallery->image.'" data-xt="'.$checkLine.'"></div>';
                                    array_push($alreadyExist, $photoGallery->image);
                                    $j++;
                                    $checkLine++;
                                }

                              }

                              $inActive .='</div>';
                              echo $inActive;
                            }
                          ?>
                       
                        </div> 
                      <!-- /carousel-inner --> 
                      <a class="left carousel-control" href="#thumbcarousel" role="button" data-slide="prev"> 
                        <i class="fa fa-angle-left" aria-hidden="true"></i> 
                      </a> 
                      <a class="right carousel-control" href="#thumbcarousel" role="button" data-slide="next">
                        <i class="fa fa-angle-right" aria-hidden="true"></i> 
                      </a> 
                        <!-- /thumbcarousel --> 
                    </div>
                  </div>
              </div>
              <?php                               
                  }else{
                    ?>
              @if($result['image'] != '' && File::exists(TRIP_IMAGE_ROOT_PATH.$result['image']))
                  <img src="<?php echo TRIP_IMAGE_URL.$result['image']; ?>" width="550" height="480"  alt=""/>
              @else
                  {{ HTML::image('img/noimg.png', '', array('width' => '550', 'height' => '480' )) }}
              @endif
                    <?php
                  } 
                ?>

            </div>
              <div class="trip_detail_top">
              <h4 class="trip_duration">  	{{ trans('messages.tripduration')}} - {{ isset($result['tripdays']) ? $result['tripdays'] . ' ' .trans('messages.days') : ''}} </h4>
               <h4 class="trip_name">  {{ isset($result['tripname']) ? $result['tripname'] .'' : ''}} </h4>
                <h4 class="trip_price"> {{trans('messages.pricestartfrom')}} € {{ isset($result['baseprice']) ? $result['baseprice'] .'' : ''}} </h4>
                {{ isset($result['description']) ? $result['description'] : ''}} 
              </div>
              
              <div class="trip_tabs">
                  <!-- Nav tabs -->
                  <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#overview" aria-controls="overview" role="tab" data-toggle="tab">{{trans('messages.overview')}}</a></li>
                    <li role="presentation"><a href="#Itenary" aria-controls="Itenary" role="tab" data-toggle="tab">{{trans('messages.detaileditenary')}}</a></li>
                    <li role="presentation"><a href="#coninfo" aria-controls="coninfo" role="tab" data-toggle="tab">{{trans('messages.countryinfo')}}</a></li>
                    @if(sizeof($client_result)>0)
                    	<li role="presentation"><a href="#clientsay" aria-controls="clientsay" role="tab" data-toggle="tab">{{trans('messages.clientsays')}}</a></li>
                    @endif
                    @if(sizeof($packageList)>0)
                    	<li role="presentation"><a href="#trippackage" class="trip_pac" aria-controls="trippackage" role="tab" data-toggle="tab">{{trans('messages.trippackage')}}</a></li>
                    @endif
                      <li role="presentation"><a href="#tripmap" aria-controls="tripmap" role="tab" data-toggle="tab">{{trans('messages.tripmap')}}</a></li>
                  </ul>
                
                  <!-- Tab panes -->
                  <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="overview">{{ isset($result['overview']) ? $result['overview'] : ''}}</div>
                    <div role="tabpanel" class="tab-pane" id="Itenary">{{ isset($result['itinerary']) ? $result['itinerary'] : ''}}</div>
                    <div role="tabpanel" class="tab-pane" id="coninfo">{{ isset($country_info_result['countryinfo']) ? $country_info_result['countryinfo'] : ''}}</div>
                    @if(sizeof($client_result)>0)
                    	<div role="tabpanel" class="tab-pane" id="clientsay">
                        	@foreach($client_result as $client)
                              <p>{{ $client['clientname'] }}</p>
                              <p>{{ $client['review'] }}
                            @endforeach
                        </div>
                    @endif
                    
                    @if(sizeof($packageList)>0)
                      <div role="tabpanel" class="tab-pane" id="trippackage">
                          <table class="table">
                            <thead>
                              <tr>
                                <th scope="col">{{trans('messages.date')}}</th>
                                <th scope="col">{{trans('messages.price')}}</th>
                                <th scope="col">{{trans('messages.singlesupplement')}}</th>
                                <th scope="col">{{trans('messages.status')}}</th>
                                <th scope="col">{{trans('messages.book')}}</th>

                              </tr>
                            </thead>
                            <tbody>
                              @foreach($packageList as $package)
                                <tr>
                                  <td>{{ $package->trip_date }}</td>
                                  <td>{{ $package->price }}</td>
                                  <td>{{ $package->supplement }}</td>
                                  <td>{{ $package->status_name }}</td>
                                   <td><a class="btn btn-info" href="{{URL::to('/package-booking/'.$package->trip_id . '/'.$package->id)}}">{{ trans('messages.booknow') }}</a></td>
                                </tr>
                              @endforeach
                            </tbody>
                          </table>
                      </div>
                    @endif

                    <div role="tabpanel" class="tab-pane" id="tripmap" style="width:100%;height:400px;">
                      <div id="map"></div>
                    </div>
                    
                  </div>                
              </div>
              <div class="trip_actions">
              	<a class="btn btn-primary" href="{{ URL::to('trip-enquiry/'.$result['trip_id']) }}"><i class="fa fa-phone" aria-hidden="true"></i> {{ trans('messages.contact_us') }}</a>
              	<a class="btn btn-info" id="book_now_button" href="#trippackage">{{ trans('messages.booknow') }}</a>
              </div>        
            </div>
        </div>
    </div>
  </div>
</div>

</div>

<!--Include the above in your HEAD tag -->
<script>
    $("#book_now_button").click(function(){
      $(".trip_pac").trigger('click');
    })
    var map;
    var marker;
    var infowindow;
    var red_icon =  'http://maps.google.com/mapfiles/ms/icons/red-dot.png' ;
    var purple_icon =  'http://maps.google.com/mapfiles/ms/icons/purple-dot.png' ;
    var locations = <?php echo $locations_info; ?>;

    function initMap() {
        var france = {lat: <?php echo $countrydetail['lat'] ?>, lng: <?php echo $countrydetail['lng'] ?>};
         map = new google.maps.Map(document.getElementById('map'), {
            center: france,
            zoom: 7
        });
        infowindow = new google.maps.InfoWindow({
          position: map.getCenter()
        });
      


        var i ; var confirmed = 0;
        for (i = 0; i < locations.length; i++) {

            marker = new google.maps.Marker({
                position: new google.maps.LatLng(locations[i][1], locations[i][2]),
                map: map,
                icon :   locations[i][4] === '1' ?  red_icon  : purple_icon,
                gestureHandling: 'greedy',
                html: document.getElementById('form')
            });

            google.maps.event.addListener(marker, 'mouseover', (function(marker, i) {
                return function() {
                    confirmed =  locations[i][4] === '1' ?  'checked'  :  0;
                    $("#confirmed").prop(confirmed,locations[i][4]);
                    $("#id").val(locations[i][0]);
                    $("#description").text(locations[i][3]);
                    $("#form").show();
                    infowindow.setContent(marker.html);
                    infowindow.open(map, marker);
                }
            })(marker, i));
        }
    }

    function saveData() {
        var confirmed = document.getElementById('confirmed').checked ? 1 : 0;
        var id = document.getElementById('id').value;
        var url = 'locations_model.php?confirm_location&id=' + id + '&confirmed=' + confirmed ;
        downloadUrl(url, function(data, responseCode) {
            if (responseCode === 200  && data.length > 1) {
                infowindow.close();
                window.location.reload(true);
            }else{
                infowindow.setContent("<div style='color: purple; font-size: 25px;'>Inserting Errors</div>");
            }
        });
    }


    function downloadUrl(url, callback) {
        var request = window.ActiveXObject ?
            new ActiveXObject('Microsoft.XMLHTTP') :
            new XMLHttpRequest;

        request.onreadystatechange = function() {
            if (request.readyState == 4) {
                callback(request.responseText, request.status);
            }
        };

        request.open('GET', url, true);
        request.send(null);
    }


</script>

<div style="display: none" id="form">
    <table class="map1">
      <span id="description"></span>
        <!--<tr>
            <input name="id" type='hidden' id='id'/>
            <td><a>Description:</a></td>
            <td><textarea disabled id='description1' placeholder='Description'></textarea></td>
        </tr>
         <tr>
            <td><b>Confirm Location ?:</b></td>
            <td><input id='confirmed' type='checkbox' name='confirmed'></td>
        </tr>

        <tr><td></td><td><input type='button' value='Save' onclick='saveData()'/></td></tr> -->
    </table>
</div>
<script async defer
        src="https://maps.googleapis.com/maps/api/js?language=en&key=AIzaSyC7TBLzQTQU90SbPEIltIJe9vPGlzdkw8g&callback=initMap">
</script>

@include('layouts.main.footer_top')
@stop