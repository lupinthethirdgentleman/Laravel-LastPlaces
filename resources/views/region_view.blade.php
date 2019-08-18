@extends('layouts.inner')
@section('content')

<div class="page_title">
    <div class="container">
        <div class="row">
            <div class="col-sm-offset-4">
              <!--  Region Heading comes here  -->
                <h1>{{ isset($result['heading']) ? $result['heading'] : ''}} </h1>
            </div>
        </div>
    </div>
</div>
<div class="landing_page">
  <div class="container">
    <!--
    <div class="landing_text" style="margin-bottom:10px;">
      
    </div> -->
    <!--  Code for Region Introduction Part  -->
    <div class="regiondesc">
        <div class="row">
                       
            <div class="col-sm-12">
              <h2>{{ isset($result['heading']) ? $result['heading'] : ''}} </h2>   
            <p> {{ isset($result['introduction']) ? $result['introduction'] : ''}}</p>
            </div>
        </div>
    </div>

  </div>
</div>

<div class="container">
   
    @if(sizeof($country_result)>0)
    @foreach($country_result as $country_res)
    <div class="region_view-box">
    <div class="row">
     <div class="col-sm-5">
     	        @if($country_res['img'] != '' && File::exists(COUNTRY_IMAGE_ROOT_PATH.$country_res['img']))
                  <div class="region_view-box-img">
                  		<img src="<?php echo COUNTRY_IMAGE_URL.$country_res['img']; ?>" width="550" height="480"  alt=""/>
                	</div>
                @else
                  <div>
                 	 {{ HTML::image('img/noimg.png', '', array('width' => '80', 'height' => '80' )) }}
                  </div>
                @endif
     
     </div>
      <div class="col-sm-7">
        <div class="region_view-box-text">  
              <h2>{{$country_res['name'] }}</h2>
              <h3>{{ $country_res['heading'] }}</h3>
              <p>{{ $country_res['description'] }}</p>
               <a href="{{ URL::to('country-trips/'.$country_res['slug']) }}" class="btn btn-primary">{{ trans('messages.viewtours') }}</a>
        </div>
      </div>
    </div>
    
    </div>
  
   		
           
   
    @endforeach
    @else
    <p>No Country Found</p>
    @endif
  </div>

</div>
@include('layouts.main.footer_top')
@stop