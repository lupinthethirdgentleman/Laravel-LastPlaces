@extends('layouts.inner')
@section('content')

<div class="page_title">
    <div class="container">
        <div class="row">
            <div class="col-sm-offset-4">
                <h1>Testimonials</h1>
            </div>
        </div>
    </div>
</div>

<div class="main_content">
  <div class="container">

<div class="row">
    <div class="col-md-12">
    @if(sizeOf($testimonialLists)>0)
    @foreach($testimonialLists as $testimonial)
    <div class="testiminial-block">
      <div class="row">
        <div class="col-md-2 col-sm-2">
          @if($testimonial->image != '' && File::exists(TESTIMONIAL_IMAGE_ROOT_PATH.$testimonial->image))
          <img src="<?php echo TESTIMONIAL_IMAGE_URL.$testimonial->image; ?>" width="80" height="80"  alt=""/>
          @else
          {{ HTML::image('img/noimg.png', '', array('width' => '80', 'height' => '80' )) }}
          @endif
          </div>
        <div class="col-md-8 col-sm-8 testimonial-content">
          <h3>{{ $testimonial->title}}</h3>
          <p>{{ $testimonial->comment}}</p>
          <div class="testimonial-author">
            {{ $testimonial->client_name}} <span>({{ $testimonial->client_designation}})</span>
          </div>
        </div>
      </div>
    </div>

    @endforeach
    @else
    <h3 style="text-align:center">No Testimonial Found</h3>
    @endif
    
    
    

  </div>    
</div>
</div>

</div>
@include('layouts.main.footer_top')
@stop