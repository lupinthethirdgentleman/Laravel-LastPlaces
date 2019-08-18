

@extends('layouts.inner')
@section('content')
<?php 
  //print_r($review);
  function timeAgo($time_ago)
{
    $time_ago = strtotime($time_ago);
    $cur_time   = time();
    $time_elapsed   = $cur_time - $time_ago;
    $seconds    = $time_elapsed ;
    $minutes    = round($time_elapsed / 60 );
    $hours      = round($time_elapsed / 3600);
    $days       = round($time_elapsed / 86400 );
    $weeks      = round($time_elapsed / 604800);
    $months     = round($time_elapsed / 2600640 );
    $years      = round($time_elapsed / 31207680 );
    // Seconds
    if($seconds <= 60){
        return "just now";
    }
    //Minutes
    else if($minutes <=60){
        if($minutes==1){
            return "one minute ago";
        }
        else{
            return "$minutes minutes ago";
        }
    }
    //Hours
    else if($hours <=24){
        if($hours==1){
            return "an hour ago";
        }else{
            return "$hours hrs ago";
        }
    }
    //Days
    else if($days <= 7){
        if($days==1){
            return "yesterday";
        }else{
            return "$days days ago";
        }
    }
    //Weeks
    else if($weeks <= 4.3){
        if($weeks==1){
            return "a week ago";
        }else{
            return "$weeks weeks ago";
        }
    }
    //Months
    else if($months <=12){
        if($months==1){
            return "a month ago";
        }else{
            return "$months months ago";
        }
    }
    //Years
    else{
        if($years==1){
            return "one year ago";
        }else{
            return "$years years ago";
        }
    }
}
?>
<div class="main_content">
  <div class="wreview_top">
    <div class="container">
      <div class="row">
        <div class="col-sm-6">
          <div class="wreview_person">
            <div class="wrp_left">
              <div class="wrp_image">
                @if($member->image != '' && File::exists(HCP_IMAGE_ROOT_PATH.$member->image))
                <!-- <img src="<?php echo WEBSITE_URL.'image.php?width=200px&height=200px&cropratio=1:1&image='.HCP_IMAGE_URL.'/',$member->image; ?>" width="80" height="80"  alt=""/> -->
                <img src="<?php echo HCP_IMAGE_URL.$member->image; ?>" width="80" height="80"  alt=""/>
                @else
                  {{ HTML::image('img/noimg.png', '', array('width' => '80', 'height' => '80' )) }}
                @endif
              </div>
            </div>
            <div class="wrp_right">
              <div class="wrp_name">{{ $member->full_name}}</div>
              <div class="wrp_job">{{ $member->profession }}</div>
              <div class="review_star big_stars">
                <?php 
                  $rateTotal = 0;
                  foreach($member->review as $rew){
                    $rateTotal += $rew->rating;
                  }
                  if($rateTotal >0){
                    $param = $rateTotal/$member->review->count();
                    $width = ($param/5)*100;
                  }else{
                    $width =0;
                  }
                  echo '<div style="width:'.$width.'%"></div>';
                ?>
              </div>
              <div class="review_count">
                <strong>{{ $member->review->count()}}</strong> Reviews
                <p>Negative Review : <strong>{{$negativePercentage}}%</strong> </p>
              </div>
            </div>
          </div>
        </div>
        <div class="col-sm-4">
          <div class="wreview_company"><img src="<?php echo WEBSITE_URL.'uploads/company/'.$member->company->image ?>" width="307" height="75"  alt=""/></div>
          <span>{{ $member->company->Name}}</span>
          <div class="wreview_company_address">{{ $member->companylocation->Name}}</div>
        </div>
        <div class="col-sm-2">
          <div class="member_like_section">
              @include('like_dislike_member')
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="container">
    <div class="row">
      <div class="col-sm-8">
          <div class="login_form gray_box" id="scroll_error_review">
            <h2>Write review on <span style="display:inline-block">{{ $member->full_name}}</span></h2>
            @if(!$isAlreadyReview)
              <div id="error_div_review" style="display:none"></div>
              {{ Form::open( ['role'=>'form', 'url'=>'save_review', 'id'=>'review_form', 'method'=>'post', 'class'=>'form', 'snonvalidate'=>'nonvalidate'] ) }}
              <input type="hidden" name="hcp_id" value="{{ $member->id }}">
              <div class="form-group">
                <div class="row">
                  <div class="col-sm-1">
                    <strong style="margin-top:12px; display:block;">Rating:</strong>
                  </div>
                  <div class="col-sm-11">
                    <!-- <div class="review_star big_stars">
                      <div style="width:90%"></div>
                      </div> -->
                    <section class='rating-widget'>
                      <!-- Rating Stars Box -->
                      <div class='rating-stars text-center'>
                        <ul id='stars'>
                          <li class='star' title='Poor' data-value='1'>
                            <i class='fa fa-star fa-fw'></i>
                          </li>
                          <li class='star' title='Fair' data-value='2'>
                            <i class='fa fa-star fa-fw'></i>
                          </li>
                          <li class='star' title='Good' data-value='3'>
                            <i class='fa fa-star fa-fw'></i>
                          </li>
                          <li class='star' title='Excellent' data-value='4'>
                            <i class='fa fa-star fa-fw'></i>
                          </li>
                          <li class='star' title='WOW!!!' data-value='5'>
                            <i class='fa fa-star fa-fw'></i>
                          </li>
                        </ul>
                      </div>
                      <!--success box-->
                      <div class='success-box' style="display:none">
                        <div class='clearfix'></div>
                        <img alt='tick image' width='32' src='data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTkuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iTGF5ZXJfMSIgeD0iMHB4IiB5PSIwcHgiIHZpZXdCb3g9IjAgMCA0MjYuNjY3IDQyNi42NjciIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDQyNi42NjcgNDI2LjY2NzsiIHhtbDpzcGFjZT0icHJlc2VydmUiIHdpZHRoPSI1MTJweCIgaGVpZ2h0PSI1MTJweCI+CjxwYXRoIHN0eWxlPSJmaWxsOiM2QUMyNTk7IiBkPSJNMjEzLjMzMywwQzk1LjUxOCwwLDAsOTUuNTE0LDAsMjEzLjMzM3M5NS41MTgsMjEzLjMzMywyMTMuMzMzLDIxMy4zMzMgIGMxMTcuODI4LDAsMjEzLjMzMy05NS41MTQsMjEzLjMzMy0yMTMuMzMzUzMzMS4xNTcsMCwyMTMuMzMzLDB6IE0xNzQuMTk5LDMyMi45MThsLTkzLjkzNS05My45MzFsMzEuMzA5LTMxLjMwOWw2Mi42MjYsNjIuNjIyICBsMTQwLjg5NC0xNDAuODk4bDMxLjMwOSwzMS4zMDlMMTc0LjE5OSwzMjIuOTE4eiIvPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8L3N2Zz4K'/>
                        <div class='text-message'></div>
                        <div class='clearfix'></div>
                      </div>
                      {{ Form::hidden(
                      'rating_value', 
                      null,
                      ['class'=>'form-control','id'=>'rating_value','placeholder'=>"Rating", 'required','data-errormessage-value-missing' => 'Rating is required.']
                      ) 
                      }}
                    </section>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <div class="row">
                  <div class="col-sm-12">
                    {{ Form::select(
                    'health_benefit', 
                    array(null => 'Select Basis of application for benefits') + $health_benefit_list,
                    '',
                    ['class'=>'form-control', 'required']
                    ) 
                    }}
                  </div>
                </div>
              </div>
              <!-- <div class="form-group">
                <div class="row">
                  <div class="col-sm-12">
                    {{ Form::select(
                    'company_id', $company_list,
                    '',
                    ['class'=>'form-control', 'required']
                    ) 
                    }}
                  </div>
                </div> 
              </div>-->
              <div class="form-group">
                <div class="row">
                  <div class="col-sm-12">
                    <textarea name="comment" pattern=".{50,}" data-errormessage-pattern-mismatch="Minimum 50 words" data-errormessage-value-missing="Comment is required" required="required"  class="form-control" placeholder="Review Comment"></textarea>
                  </div>
                </div>
              </div>
              <button id="submit_review" type="submit" class="btn btn-success">Submit</button>
              {{ Form::close() }}
            @else
             <span>You have already reviewed to this hcp.</span>
            @endif
          </div>
       
        <!-- Above foreach is for only checking that data is null or not because empty function is not working -->
        
        <h1>Reviews</h1>
        @if(!$review->isEmpty())
          <?php $inc =0; ?>
          @foreach($review as $rev)
          <div class="review_content_boxc" style="margin-top:15px;">
            <div class="rcbox_top">
              <div class="latest_review_time">{{ timeAgo(($rev->created_at), true) }}</div>
              <div class="latest_review_stars">
                <div class="review_star">
                  <?php 
                    if($rev->rating >0){
                        $width = ($rev->rating/5)*100;
                        echo '<div style="width:'.$width.'%"></div>';
                    }else{
                        echo '<div style="width:0%"></div>';
                    }
                    ?>
                </div>
                <div class="review_rating">({{$rev->rating}})</div>
              </div>
              <div class="latest_review_content cus-min-height" id="hidebox{{$inc}}">{{ Str::limit($rev->comment, $limit = 130, $end = '...') }}</div>
              <div class="latest_review_content cus-min-height" id="box{{$inc}}" style="display:none;">{{ $rev->comment}}</div>
            </div>
            <div class="rcbox_bot">
             <!--  <div class="latest_review_like">
                <a href="#"><img src="images/like_icon.png" width="38" height="38" alt=""/></a>
              </div>
              <div class="latest_review_dislike">
                <a href="#"><img src="images/dislike_icon.png" width="38" height="38" alt=""/></a>
              </div> -->
              <div class="latest_review_action f-none"><a href="javascript:void(0)" id = "{{$inc}}" class="readmore-btn">Read More</a></div>
            </div>
          </div>
          <?php $inc++; ?>
          @endforeach
          @include('pagination.write_review')
        @else
          <div class="review_content_boxc" style="margin-top:15px;">
            <div class="rcbox_top">
              <p>There is no review.</p>
            </div>
          </div>
        @endif
      </div>
      <div class="col-sm-4 sidebar">
        <div class="search_member">
          <div class="banner_content">
            <form method="get" action="{{ URL::to('member_list')}}">
            <div class="search_area">
              <input placeholder="Search Member" type="search" name="name" >
              <!-- <button class="btn btn-search"><img src="<?php echo WEBSITE_URL.'img/search_icon.png?width=26px&height=26px&cropratio=1:1'?>" alt="" width="26" height="26"></button> -->
              <a href="{{ URL::to('member_list')}}">
                <button class="btn btn-search">
                {{ HTML::image('img/search_icon.png', '', array('width' => '34', 'height' => '34')) }} 
                </button>
              </a>
            </div>
            </form>
          </div>
        </div>
        @if(!$moremember->isEmpty())
          <h2 class="section_heading">More Members</h2>
        @endif
        <div class="owl-carousel">
          @if(!$moremember->isEmpty())
            @foreach($moremember as $more)
              <div class="item">
                <div class="review_content_box">
                  <div class="rcbox_top">
                    <div class="most_review_top">
                      <div class="mrtop_left">
                        <a href="{{ URL::to('write_review',$more->id)}}">
                          @if($more->image!='' && File::exists(HCP_IMAGE_ROOT_PATH.$more->image) )
                          <img src="<?php echo HCP_IMAGE_URL.$more->image; ?>" width="80" height="80"  alt=""/>
                          @else
                          {{ HTML::image('img/noimg.png', '', array('width' => '80', 'height' => '80')) }}
                          @endif
                        </a>
                      </div>
                      <div class="mrtop_right">
                        <div class="most_reviewer_name"><a href="{{ URL::to('write_review',$more->id)}}">Mr. {{$more->full_name}}</a></div>
                        <div class="most_reviewer_job">{{$more->profession}}</div>
                      </div>
                    </div>
                    <div class="most_review_company">
                      @if(isset($more->company->image) && COMPANY_IMAGE_ROOT_PATH.$more->company->image)
                      {{ HTML::image( COMPANY_IMAGE_URL.$more->company->image, $more->company->image , array(  'height' => 75, 'width' => 307 )) }}
                      @else
                      {{ HTML::image('img/noimg.png', '', array( 'height' => '70')) }} 
                      @endif
                    </div>
                    <?php 
                      $currentUserLikeDetail = App\Model\Like::where('user_id',Auth::user()->id)->where('hcp_id',$more->id)->first();
                      $likeAndDislike = (isset($currentUserLikeDetail->like))?$currentUserLikeDetail->like:0;  
                    ?>
                    <div class="most_review_company_address">{{ $more->company->locat_name }}</div>
                    <div class="most_review_likes most_review_likes_{{$more->id}}">
                      <div class="total_likes like_unlike" buttonType="1" hcpId="{{$more->id}}">
                        @if($likeAndDislike==2)
                         {{ HTML::image('img/like_icon.png', '', array('width' => '38', 'height' => '38')) }}
                        @else
                          {{ HTML::image('img/like_icon_act.png', '', array('width' => '38', 'height' => '38')) }}
                        @endif
                        <span class="total_like_count" buttonType="1" id="like_{{$more->id}}" hcpId="{{$more->id}}">
                        {{ $more->like->count()}}
                        </span>
                      </div>
                      
                      <div class="total_dislikes like_unlike" buttonType="2" hcpId="{{$more->id}}" id="dislike_{{$more->id}}">
                        <!--   <img src="<?php echo WEBSITE_URL.'img/dislike_icon.png?width=38px&height=38px&cropratio=1:1'?>" width="38" height="38"  alt=""/> 
                          <span class="total_like_count">11</span> -->
                        
                        @if($likeAndDislike==2)
                          {{ HTML::image('img/dislike_icon_act.png', '', array('width' => '38', 'height' => '38')) }}  
                        @else
                          {{ HTML::image('img/dislike_icon.png', '', array('width' => '38', 'height' => '38')) }}
                        @endif
                        
                        <span class="total_like_count">
                        {{ $more->dislike->count()}}
                        </span>
                      </div>

                    </div>
                  </div>
                  <div class="rcbox_bot">
                    <div class="most_review_stars">
                      <!-- <div class="review_star">
                        <div style="width:100%"></div>
                        </div> -->
                      @if(count($more->review)>0)
                      <div class="review_star">
                        <?php 
                          $totrating = 0;$tot=0;
                          foreach($more->review as $count){
                               $totrating += $count->rating;
                               $tot++;
                          }
                          $width = (($totrating/$tot)/5)*100;
                          echo '<div style="width:'.$width.'%"></div>';
                          ?>
                      </div>
                      @else
                      <div class="star-rating">
                        <span class="fa fa-1x fa-star-o"></span>
                        <span class="fa fa-1x fa-star-o"></span>
                        <span class="fa fa-1x fa-star-o"></span>
                        <span class="fa fa-1x fa-star-o"></span>
                        <span class="fa fa-1x fa-star-o"></span>
                      </div>
                      @endif
                      <div class="review_count">
                        <strong>{{count($more->review)}}</strong> Reviews
                        <?php 

                          $negCount=0; $totalHcpCount=0; $perNegativeCount=0; 

                          foreach($negativeReviews as $neg){
                            
                            if($neg->hcp_id == $more->id){
                              $negCount++;
                            }

                          }

                          foreach($totalReviews as $tr){

                            if($tr->hcp_id == $more->id){
                              $totalHcpCount++;
                            }

                          }
                          
                          if($negCount>0 && $totalHcpCount>0){

                            if($negCount < $totalHcpCount ){
                              $perNegativeCount = round(($negCount/$totalHcpCount)*100);
                            }
                            else{
                              $perNegativeCount = 0;
                            }

                          }else{

                            $perNegativeCount = 0;

                          }
                          ?>
                        <p>Negative Review : <strong>{{$perNegativeCount}}%</strong> </p>
                      </div>
                    </div>
                    <div class="most_review_action">
                      <a href="{{ URL::to('download_review',$more->id)}}" class="btn btn-success" title="Download review"><i class="fa fa-download" aria-hidden="true"></i></a>
                      <a href="{{ URL::to('write_review',$more->id)}}" class="btn btn-success">Review Now</a>
                    </div>
                  </div>
                </div>
              </div>
            @endforeach
          @endif
          
          <!--  <div class="review_content_box">
            <div class="rcbox_top">
              <div class="most_review_top">
                <div class="mrtop_left">
                  <div class="most_review_image"><img src="<?php echo WEBSITE_URL.'img/alice_img.png?width=80px&height=80px&cropratio=1:1'?>" width="80" height="80"  alt=""/></div>
                </div>
                <div class="mrtop_right">
                  <div class="most_reviewer_name">Mr. Alice wonder land adverd</div>
                  <div class="most_reviewer_job">Nurse</div>
                </div>
              </div>
              <div class="most_review_company"><img src="<?php echo WEBSITE_URL.'img/maximus_logo.png?width=294px&height=75px&cropratio=1:1'?>" width="294" height="75"  alt=""/></div>
              <div class="most_review_company_address">Maximus, Brymbo Enterprise Centre, Blast Road, Brymbo, Wrexham LL11 5BT</div>
              <div class="most_review_likes">
                <div class="total_likes"><img src="<?php echo WEBSITE_URL.'img/like_icon.png?width=38px&height=38px&cropratio=1:1'?>" width="38" height="38"  alt=""/> <span class="total_like_count">305</span></div>
                <div class="total_dislikes"><img src="<?php echo WEBSITE_URL.'img/dislike_icon.png?width=38px&height=38px&cropratio=1:1'?>" width="38" height="38"  alt=""/> <span class="total_like_count">11</span></div>
              </div>
            </div>
            <div class="rcbox_bot">
              <div class="most_review_stars">
                <div class="review_star">
                  <div style="width:100%"></div>
                </div>
                <div class="review_count"><strong>458</strong> Reviews</div>
              </div>
              <div class="most_review_action"><a href="write_review.html" class="btn btn-success">Review Now</a></div>
            </div>
            </div> -->
        </div>
        <script type="text/javascript">
          $(document).ready(function() {
            $('.owl-carousel').owlCarousel({
              loop:true,
              margin:20,
              nav: true,
          items:1,
              dots: false,
              responsiveClass: true,
            })
          })
        </script>
      </div>
    </div>
  </div>
</div>
@include('layouts.main.footer_top')
<script type="text/javascript">
  $(document).ready(function(){
    
    /* 1. Visualizing things on Hover - See next part for action on click */
    $('#stars li').on('mouseover', function(){
      var onStar = parseInt($(this).data('value'), 10); // The star currently mouse on
     
      // Now highlight all the stars that's not after the current hovered star
      $(this).parent().children('li.star').each(function(e){
        if (e < onStar) {
          $(this).addClass('hover');
        }
        else {
          $(this).removeClass('hover');
        }
      });
      
    }).on('mouseout', function(){
      $(this).parent().children('li.star').each(function(e){
        $(this).removeClass('hover');
      });
    });
    
    
    /* 2. Action to perform on click */
    $('#stars li').on('click', function(){
      var onStar = parseInt($(this).data('value'), 10); // The star currently selected
      var stars = $(this).parent().children('li.star');
      
      for (i = 0; i < stars.length; i++) {
        $(stars[i]).removeClass('selected');
      }
      
      for (i = 0; i < onStar; i++) {
        $(stars[i]).addClass('selected');
      }
      
      // JUST RESPONSE (Not needed)
      var ratingValue = parseInt($('#stars li.selected').last().data('value'), 10);
      $('#rating_value').val(ratingValue);
      var msg = "";
      if (ratingValue > 1) {
          $('.success-box').show();
          msg = "Thanks! You rated this " + ratingValue + " stars.";
      }
      else {
          msg = "Thanks! You rated this " + ratingValue + " stars.";
          //msg = "We will improve ourselves. You rated this " + ratingValue + " stars.";
      }
      responseMessage(msg);
      
    });
  
  
    //save review
  
    var options = {
      beforeSubmit:function(){
        $('#error_div_review').hide();
        $("#submit_review").button('loading');
        $("#overlay").show();
      },
      data : {},
      success:function(data){
        $("#submit_review").button('reset');
        $("#overlay").hide();
  
          if(data.success==1){
            $('#error_div_review').hide();
            document.getElementById("review_form").reset();
            notice(data.title,data.success_msg,'success');          
          }else{
            error_msg = '<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>'+data.errors+'</div>';
            $('#error_div_review').hide();
            $('#error_div_review').html(error_msg);
            
            // top position relative to the document
            var pos = $("#scroll_error_review").offset().top;
    
            // animated top scrolling
            $('body, html').animate({scrollTop: 0});
            $('#error_div_review').show('slow');
          }
          return false;
  
      },
      resetForm:false
    };
    // pass options to ajaxform
    $('#review_form').ajaxForm(options)
    
  });
  
  
  function responseMessage(msg) {
    $('.success-box').fadeIn(200);  
    $('.success-box div.text-message').html("<span>" + msg + "</span>");
  }
</script>
@stop

