<?php 
//$reviews = App\Model\Review::with('hcp','company','dropdown')->orderBY('created_at','desc')->get();
$reviews = DB::table('reviews')->join('hcp as h', 'h.id', '=', 'reviews.hcp_id')
                            ->join('companies as c', 'h.company_id', '=', 'c.id')
                            ->join('dropdown_managers','dropdown_managers.id','=','reviews.health_benefit')
                            ->where('h.status',1)
                            ->where('reviews.status',1)
                            ->orderBY('reviews.created_at','desc')
                            ->get();
/*print_r($reviews);
die;*/
function print_stars($num) {
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
<?php if(!empty($reviews)){ ?>
<div class="latest_reviews">
	<div class="container">
    	<h2 class="section_heading">Latest Reviews</h2>
        <div class="owl-carousel">
        	@foreach($reviews as $review)
            <div class="item">
            	<div class="review_content_box">
                	<div class="rcbox_top">
                    	<div class="latest_review_title"><a href="#">{{ $review->name}}</a></div>
                    	<div class="latest_review_to">Review to <a href="#">{{ $review->full_name}}</a></div>
                    	<div class="latest_review_time">{{ (timeAgo(($review->created_at), true)) }};</div>
                    	<?php 
                            //$rating = round_half( $review->rating );
                            //print_stars($review->rating);
                        ?>
                        <div class="latest_review_stars">
                            
                              <div class="row">
                                <div class="col-lg-12">
                                  <div class="star-rating">
                                    {{ print_stars($review->rating) }}
                                    <input type="hidden" name="whatever3" class="rating-value" value="{{$review->rating}}">
                                    ({{ $review->rating}})
                                  </div>
                                </div>
                              </div>
                        	<!-- <div class="review_star">
                            	<div style="width:60%"></div>
                            </div> -->
                        </div>
                    	<div class="latest_review_content" style="min-height: 75px;">{{ str_limit($review->comment, $limit = 155, $end = '...') }}</div>
                    </div>
                	<div class="rcbox_bot">
                        <!-- <div class="latest_review_like"><a href="#">
                        {{ HTML::image('img/like_icon.png', '', array('width' => '38', 'height' => '38')) }} 
                        </a></div>
                    	<div class="latest_review_dislike"><a href="#">
                      	{{ HTML::image('img/dislike_icon.png', '', array('width' => '38', 'height' => '38')) }} 
                        </a></div> -->
                        <div class="latest_review_action">
                            <!-- <a href="{{ URL::to('download_review',$review->hcp_id)}}" class="btn btn-success" title="Download review"><i class="fa fa-download" aria-hidden="true"></i></a> -->
                            <a href="{{ URL::to('write_review',$review->hcp_id)}}" class="btn btn-success">Read More</a>
                        </div>
                  </div>
                </div>
            </div>
            @endforeach
        </div>
        <script type="text/javascript">
		  $(document).ready(function() {
			$('.owl-carousel').owlCarousel({
			  loop:true,
			  margin:20,
			  nav: true,
			  dots: false,
			  responsiveClass: true,
			  responsive: {
				0:{
				  items:1
				},
				568:{
				  items:2
				},
				768:{
				  items:3
				},
				1024:{
				  items:4,
				  loop:false,
				  margin:30
				}
			  }
			})
		  })
		</script>
    </div>
</div>
<?php } ?>