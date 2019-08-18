
@extends('layouts.pdf')
@section('content')

<?php 
  function print_stars1($num) {

      //echo '<ul class="review-rating star-rating">';
      for ($n=1; $n<=5; $n++) {
          echo '<span class="fa fa-1x fa-star';
          if ($num==$n+.5) {
              echo '-half-empty';
          } elseif ($num<$n) {
              echo '-o';
          };
          echo '"></span>';
      };
      //echo '</ul>';
  };
 

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



<table>
<tbody>
 <?php foreach($all_member_list as $final_list){
 	$review = $final_list['review'];
	$member = $final_list['member'];
	$title = $final_list['title'];
	$hcpId = $final_list['hcpId'];
	$negativePercentage = $final_list['negativePercentage'];
   ?>
<div class="main_content">
  <div class="wreview_top">
    <div class="container">
      <div class="row">
        <div class="col-sm-12 text-center">
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
              <div class=" big_stars">
                <?php 
                  $rateTotal = 0;
                  foreach($member->review as $rew){
                    $rateTotal += $rew->rating;
                  }
                  if($rateTotal >0){
                    $param = $rateTotal/$member->review->count();
                    echo '<div class="star-rating">';
                               print_stars1($param) ;
                   echo '</div>';
                   $width = ($param/5)*100;
                  }else{
                    $width =0;
                    echo '<div class="star-rating">';
                              print_stars1(0) ;
                    echo '</div>';
                  }
                  //echo '<div style="width:'.$width.'%"></div>';
                ?>
              </div>
              <div class="review_count">
                <strong>{{ $member->review->count()}}</strong> Reviews
                <p>Negative Review : <strong>{{$negativePercentage}}%</strong> </p>
              </div>

              <div class="wreview_company"><img src="<?php echo HCP_IMAGE_URL.$member->company->image ?>" width="307" height="75"  alt=""/></div>
          <span>{{ $member->company->Name}}</span>
          <div class="wreview_company_address">{{ $member->companylocation->Name}}</div>
          <?php /*
           <div class="member_like_section">
              @include('like_dislike_member')
          </div>  */ ?>

            </div>
          </div>
        </div>
        </div>

     
    </div>
  </div>
  <?php /* ?>

  <div class="container">
    <div class="row">
      <div class="col-sm-12">
        
        <h1>Reviews</h1>
        @if(!$review->isEmpty())
          <?php $inc =0; ?>
          @foreach($review as $rev)
          <div class="review_content_boxc" style="margin-top:15px;">
            <div class="rcbox_top">
              <div class="latest_review_time">{{ timeAgo(($rev->created_at), true) }}</div>
              <div class="latest_review_stars">
                <div class="">
                  <?php 
                    if($rev->rating >0){
                        $width = ($rev->rating/5)*100;
                        echo '<div class="star-rating">';
                              print_stars1($rev->rating) ;
                        echo '</div>';
                       // echo '<div style="width:'.$width.'%"></div>';
                    }else{
                        //echo '<div style="width:0%"></div>';
                      echo '<div class="star-rating">';
                              print_stars1(0) ;
                      echo    '</div>';
                    }
                    ?>
                </div>
                <div class="review_rating">({{$rev->rating}})</div>
              </div>
              <div class="latest_review_content">{{ $rev->comment }}</div>
            </div>
            <div class="rcbox_bot"></div>
          </div>
          <?php $inc++; ?>
          @endforeach
        @else
          <div class="review_content_boxc" style="margin-top:15px;">
            <div class="rcbox_top">
              <p>There is no review.</p>
            </div>
          </div>
        @endif
      </div>
    </div>
  </div>
  <?php */ ?>
</div>
   <?php } ?>

</tbody>

</table>




@stop