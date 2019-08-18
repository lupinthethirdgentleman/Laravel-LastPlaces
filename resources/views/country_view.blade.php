@extends('layouts.inner')
@section('content')


<style>
  
  /*.page_title
  {
    background-image: url('<?php //if($result['header_image'] != '' && File::exists(HEADER_COUNTRY_IMAGE_ROOT_PATH.$result['header_image'])) {echo HEADER_COUNTRY_IMAGE_URL.$result['header_image'];} else { echo WEBSITE_IMG_URL."inner_pages.jpg";}?>');
    height:250px;margin-bottom:30px
  }*/
.header_hwe
{
    margin-top: -250px;
    color: #FFFF;
}
</style>
<div class="page_title">
  <img src='<?php if($result['header_image'] != '' && File::exists(HEADER_COUNTRY_IMAGE_ROOT_PATH.$result['header_image'])) {echo HEADER_COUNTRY_IMAGE_URL.$result['header_image'];} else { echo WEBSITE_IMG_URL."inner_pages.jpg";}?>' style="width:100%;height:250px;"/>
  <div class="container">
    <div class="row">
      <div class="col-sm-offset-4 header_hwe">
        <h1>{{ isset($result['heading']) ? $result['heading'] : ''}} </h1>
      </div>
    </div>
  </div>
</div>
<div class="container">
  <div class="region_view_country">
    <div class="region_view_country_sm-6" style="align-self: normal !important" >
      <div class="region_view_country-img">
        @if($result['image'] != '' && File::exists(COUNTRY_IMAGE_ROOT_PATH.$result['image']))
        <!-- <img src="<?php echo COUNTRY_IMAGE_URL.$result['image']; ?>" width="550" height="480"  alt=""/> -->
        <img src="<?php echo WEBSITE_URL.'image.php?width=550px&height=480px&image='.COUNTRY_IMAGE_URL.$result['image'];?>" alt="" />
        @else
        {{ HTML::image('img/noimg.png', '', array('width' => '80', 'height' => '80' )) }}
        @endif
      </div>
    </div>
  </div>
  <div class="" style="align-self: normal !important"> 
    <div class="landing_text">
      <div class="trip_tabs">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
          <li role="presentation" class="active"><a href="#overview" aria-controls="overview" role="tab" data-toggle="tab" aria-expanded="true">{{ trans("messages.General")}}</a></li>
          <li role="presentation" class=""><a href="#Itenary" aria-controls="Itenary" role="tab" data-toggle="tab" aria-expanded="false">{{ trans("messages.Tribes")}}</a></li>
          <li role="presentation"><a href="#coninfo" aria-controls="coninfo" role="tab" data-toggle="tab">{{ trans("messages.Art & Architecture")}}</a></li>
          <li role="presentation"><a href="#clientsay" aria-controls="clientsay" role="tab" data-toggle="tab">{{ trans("messages.Nature")}}</a></li>
          <li role="presentation"><a href="#clientsay_hwe" aria-controls="clientsay" role="tab" data-toggle="tab">{{ trans("messages.Travel Infomation")}}</a></li>
          
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
          <div role="tabpanel" class="tab-pane active" id="overview">
          @if(!empty($result['description']))
           <section id="readmore_description">
            {{ str_limit($result['description'], $limit = 1000, $end = '<a  href="javascript:void(0)" onclick="readMore1(this)"> Read More</a>') }}
           </section>
           <section style="display:none;" id="readless_description">
            {{ $result['description'] }} <a  href="javascript:void(0)" onclick="readLess1(this)">{{ trans("messages.Read Less")}}</a>
           </section>
           @endif
          </div>
          <div role="tabpanel" class="tab-pane" id="Itenary">
            @if(!empty($result['countryinfo']))
             <section id="readmore_countryinfo">
              {{ str_limit($result['countryinfo'], $limit = 1000, $end = '<a  href="javascript:void(0)" onclick="readMore2(this)"> Read More</a>') }}
             </section>
             <section style="display:none;" id="readless_countryinfo">
              {{ $result['countryinfo'] }} <a  href="javascript:void(0)" onclick="readLess2(this)">{{ trans("messages.Read Less")}}</a>
             </section>
             @endif
          </div>
          <div role="tabpanel" class="tab-pane" id="coninfo">
            
            @if((isset($result['art_architecture'])) && (!empty($result['art_architecture'])))
             <section id="readmore_art">
              {{ str_limit($result['art_architecture'], $limit = 1000, $end = '<a  href="javascript:void(0)" onclick="readMore3(this)"> Read More</a>') }}
             </section>

             <section style="display:none;" id="readless_art">
              {{ $result['art_architecture'] }} <a  href="javascript:void(0)" onclick="readLess3(this)">{{ trans("messages.Read Less")}}</a>
             </section>

            @endif
          </div>
          <div role="tabpanel" class="tab-pane" id="clientsay">
            @if(!empty($result['nature']))
             <section id="readmore_nature">
              {{ str_limit($result['nature'], $limit = 1000, $end = '<a  href="javascript:void(0)" onclick="readMore4(this)"> Read More</a>') }}
             </section>
             <section style="display:none;" id="readless_nature">
              {{ $result['nature'] }} <a  href="javascript:void(0)" onclick="readLess4(this)">{{ trans("messages.Read Less")}}</a>
             </section>
             @endif
          </div>
           <div role="tabpanel" class="tab-pane" id="clientsay_hwe">
          
            @if(!empty($result['travel']))
             <section id="readmore_travel">
              {{ str_limit($result['travel'], $limit = 1000, $end = '<a  href="javascript:void(0)" onclick="readMore5(this)"> Read More</a>') }}
             </section>
             <section style="display:none;" id="readless_travel">
              {{ $result['travel'] }} <a  href="javascript:void(0)" onclick="readLess5(this)">{{ trans("messages.Read Less")}}</a>
             </section>
            @endif
          </div>
          <div role="tabpanel" class="tab-pane" id="tripmap" style="width:100%;height:400px;">
          </div>
        </div>

      </div>
    </div>
  </div>
</div>
<div class="landing_page">
  <div class="container">
    <div class="landing_text" style="margin-bottom:10px;">
    </div>
  </div>
</div>

<div class="container">

  @if(sizeof($trip_result)>0)
  @foreach($trip_result as $trip_res)
  <div class="region_view-box">
    <div class="row">
      <div class="col-sm-5">
        @if($trip_res['img'] != '' && File::exists(TRIP_IMAGE_ROOT_PATH.$trip_res['img']))
        <div class="region_view-box-img">
          <a href="{{ URL::to('trips/'.''.$result['name'].'/'.$trip_res['slug']) }}">
            <!-- <img src="<?php echo TRIP_IMAGE_URL.$trip_res['img']; ?>" width="550" height="480"  alt=""/> -->
            <img src="<?php echo WEBSITE_URL.'image.php?width=550px&height=480px&image='.TRIP_IMAGE_URL.$trip_res['img'];?>" alt="" />
          </a>
        </div>
        @else
        <div>
          <a href="{{ URL::to('trips/'.''.$result['name'].'/'.$trip_res['slug']) }}" >
          {{ HTML::image('img/noimg.png', '', array('width' => '80', 'height' => '80' )) }}
          </a>
        </div>
        @endif
      </div>
      <div class="col-sm-7">
        <div class="region_view-box-text">
          <h2>
            <a href="{{ URL::to('trips/'.''.$result['slug'].'/'.$trip_res['slug']) }}">{{$trip_res['tripname'] }}
            </a>
          </h2>
          <p>{{ $trip_res['description'] }}</p>
          <a href="{{ URL::to('trips/'.''.$result['slug'].'/'.$trip_res['slug']) }}" class="btn btn-primary">{{trans('messages.viewtripdetails')}}</a>
        </div>
      </div>
    </div>
  </div>
  @endforeach
  @else
  <p class="no_trip_found">{{trans('messages.notripsfound')}}</p>
  @endif
</div>

</div>
@include('layouts.main.footer_top')
<script type="text/javascript">
 /* function readMore(e){

    $(e).parent().hide();
    $(e).parent().next().show();
   // document.getElementById("read_more").style.display = 'none';
    //document.getElementById("read_less").style.display = 'block';
   
  }

  function readLess(e){
    //document.getElementById("read_more").style.display = 'block';
    //document.getElementById("read_less").style.display = 'none';
    $(e).parent().hide();
    $(e).parent().prev().show();
  }
*/
  function readMore1(e){
    document.getElementById("readmore_description").style.display = 'none';
    document.getElementById("readless_description").style.display = 'block';
   
  }

  function readLess1(e){
    document.getElementById("readmore_description").style.display = 'block';
    document.getElementById("readless_description").style.display = 'none';
    
  }
   function readMore2(e){
    document.getElementById("readmore_countryinfo").style.display = 'none';
    document.getElementById("readless_countryinfo").style.display = 'block';
   
  }

  function readLess2(e){
    document.getElementById("readmore_countryinfo").style.display = 'block';
    document.getElementById("readless_countryinfo").style.display = 'none';
    
  }

   function readMore3(e){
    document.getElementById("readmore_art").style.display = 'none';
    document.getElementById("readless_art").style.display = 'block';
   
  }

  function readLess3(e){
    document.getElementById("readmore_art").style.display = 'block';
    document.getElementById("readless_art").style.display = 'none';
    
  }

  function readMore4(e){
    document.getElementById("readmore_nature").style.display = 'none';
    document.getElementById("readless_nature").style.display = 'block';
   
  }

  function readLess4(e){
    document.getElementById("readmore_nature").style.display = 'block';
    document.getElementById("readless_nature").style.display = 'none';
    
  }

   function readMore5(e){
    document.getElementById("readmore_travel").style.display = 'none';
    document.getElementById("readless_travel").style.display = 'block';
   
  }

  function readLess5(e){
    document.getElementById("readmore_travel").style.display = 'block';
    document.getElementById("readless_travel").style.display = 'none';
    
  }



</script>
@stop