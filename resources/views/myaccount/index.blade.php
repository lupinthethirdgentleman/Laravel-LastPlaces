@extends('layouts.inner')
@section('content')
<?php 
 // dd($response);
?>
<div class="main_content">
  <div class="container">
    <div class="row">
      <div class="col-sm-4 sidebar">
        <div class="dashboard_menu">
          <ul>
            <li  class="active"><a href="{{url('/dashboard')}}">My Review</a></li>
            <li><a href="{{url('/manage-profile')}}">Change Password</a></li>
          </ul>
        </div>
      </div>
      <div class="col-sm-8">
        <?php $inc =0; ?>
          @if(!$response->isEmpty())
             @foreach ($response as $re)
                <div class="review_content_box">
                  <div class="rcbox_top">
                   <!--  <div class="latest_review_title"><a href="#">I didnot liked her attitude</a></div> -->
                    <div class="latest_review_to">Review to <a href="#" >{{$re->full_name}}</a></div>
                    <div class="latest_review_time">{{date('Y-m-d H:i',strtotime($re->created_at))}}</div>
                    <div class="latest_review_stars">
                      <div class="review_star">
                        <?php
                          $rate = $re->rating;
                          $width = ($rate/5)*100;
                          echo '<div style="width:'.$width.'%"></div>';
                        ?>
                      </div>
                      <div class="review_rating">({{$re->rating}})</div>
                    </div>
                    <div class="latest_review_content" id="hidebox{{$inc}}">{{ Str::limit($re->comment, $limit = 130, $end = '...') }}</div>
                    <div class="latest_review_content" id="box{{$inc}}" style="display:none;">{{ $re->comment}}</div>
                  </div>
                  <div class="rcbox_bot">
                   <!--  <div class="latest_review_like"><a href="#"><img src="img/like_icon.png" width="38" height="38" alt=""/></a></div>
                    <div class="latest_review_dislike"><a href="#"><img src="img/dislike_icon.png" width="38" height="38" alt=""/></a></div> -->
                    <div class="latest_review_action"><a href="javascript:void(0)" id = "{{$inc}}" class="btn btn-success readmore-btn">Read More</a></div>
                  </div>
                </div>
                <?php $inc++; ?>
            @endforeach
           @else
              <div class="review_content_box">
                  <div class="rcbox_top">
                      <p><b>You have not reviwed to anyone yet</b></p>
                  </div>
              </div>
          @endif
          @include('pagination.dashboard')
        <!-- <div class="page_pagination">
            <nav aria-label="Page navigation">
                <ul class="pagination">
                    <li>
                      <a href="#" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                      </a>
                    </li>
                    <li class="active"><a href="#">1</a></li>
                    <li><a href="#">2</a></li>
                    <li><a href="#">3</a></li>
                    <li><a href="#">4</a></li>
                    <li><a href="#">5</a></li>
                    <li>
                      <a href="#" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                      </a>
                    </li>
                </ul>
            </nav>
        </div> -->
      </div>
    </div>
  </div>
</div>

@include('layouts.main.footer_top')
@stop
