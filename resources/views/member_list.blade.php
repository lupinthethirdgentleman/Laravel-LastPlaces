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

if($locationName!=''){

  $inputValue = $locationName;

}elseif($name !=''){

  $inputValue = $name;

}else{

  $inputValue = '';
}

?>
@extends('layouts.inner')
@section('content')
<div class="main_content">
  <div class="container">
    <div class="search_member">
      <div class="banner_content">
        <?php if(isset($search_type)){
          ?>
              <h2><?php echo $search_type; if($search_type == "ASSESMENT CENTRE" && $_GET['locationName'] && sizeOf($memberLists)>0){?> <a href="{{ URL::to('download_search_result?locationName=') . Input::get('locationName')}}" style="color:#fbae16;" title="Download"> <i class="fa fa-download" aria-hidden="true"></i> </a> <?php } ?> </h2>
          <?php
        }else{
        ?>
              <h2>Search Member</h2>
        <?php
        } ?>
        <form method="get">
        <div class="search_area">
          <?php //if(isset($search_field_name)){echo $search_field_name;}else{ echo 'name'; } ?>
          <input placeholder="Search" type="search" name="@if(!empty($search_field_name)){{$search_field_name}}@else{{'name'}}@endif" value="{{ $inputValue }}" id="assesment_suggestion">
          <button  class="btn btn-search"><img src="img/search_icon.png" alt="" width="34" height="34"></button>
        </div>
      </form>
      </div>
    </div>
    <div class="row">
      @if(sizeOf($memberLists)>0)
        @foreach($memberLists as $member)
        <?php //echo "<pre>"; print_r($member->review); die; ?>
          <div class="col-sm-4 memberls">
            <div class="review_content_box">
              <div class="rcbox_top">
                <div class="most_review_top">
                  <div class="mrtop_left">
                    <div class="most_review_image">
                    <a href="{{ URL::to('write_review',$member->id)}}">  
                    @if($member->image != '' && File::exists(HCP_IMAGE_ROOT_PATH.$member->image))
                     <!--  <img src="<?php echo WEBSITE_URL.'image.php?width=80px&height=80px&cropratio=1:1&image='.HCP_IMAGE_URL.'/',$member->image; ?>" width="80" height="80"  alt=""/> -->
                       <img src="<?php echo HCP_IMAGE_URL.$member->image; ?>" width="80" height="80"  alt=""/>
                    @else
                      {{ HTML::image('img/noimg.png', '', array('width' => '80', 'height' => '80' )) }}
                    @endif
                    </a>
                    </div>
                  </div>
                  <div class="mrtop_right">
                    <div class="most_reviewer_name"><a href="{{ URL::to('write_review',$member->id)}}">{{ $member->full_name }}</a></div>
                    <div class="most_reviewer_job">{{ $member->profession}}</div>
                  </div>
                </div>
                <div class="most_review_company">
                  @if(isset($member->company->image) && COMPANY_IMAGE_ROOT_PATH.$member->company->image)
                    {{ HTML::image( COMPANY_IMAGE_URL.$member->company->image, $member->company->image , array(  'height' => 75, 'width' => 294 )) }}
                  @else
                    {{ HTML::image('img/noimg.png', '', array( 'height' => '70')) }} 
                  @endif
                </div>
                <div class="most_review_company_address">{{ $member->companylocation->Name }}</div>
                <div class="most_review_likes most_review_likes_{{$member->id}}">
                  <div class="total_likes like_unlike" buttonType="1" hcpId="{{$member->id}}}">
                    <img src="img/like_icon.png" width="38" height="38"  alt=""/>
                    <span class="total_like_count">{{ $member->like->count() }}</span></div>
                  <div class="total_dislikes like_unlike" buttonType="2" hcpId="{{$member->id}}"> 
                    <img src="img/dislike_icon.png" width="38" height="38"  alt=""/>
                     <span class="total_like_count">{{ $member->dislike->count() }}</span></div>
                </div>
              </div>
              <div class="rcbox_bot">
                <div class="most_review_stars">
                    @if($member->review->count()!=0)
                    <?php $member_rating='' ?>
                    @foreach($member->review as $member_review)
                    <?php $member_rating += $member_review->rating ?>
                    @endforeach
                    <div class="star-rating">
                    {{ print_stars1(round( ($member_rating)/$member->review->count() )) }}
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
                      <strong>{{ $member->review->count()}}</strong> Reviews
                        <?php 

                          $negCount=0; $totalHcpCount=0; $perNegativeCount=0; 

                          foreach($negativeReviews as $neg){
                            
                            if($neg->hcp_id == $member->id){
                              $negCount++;
                            }

                          }

                          foreach($totalReviews as $tr){

                            if($tr->hcp_id == $member->id){
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
                  <a href="{{ URL::to('download_review',$member->id)}}" class="btn btn-success" title="Download review"><i class="fa fa-download" aria-hidden="true"></i></a>
                  <a href="{{ URL::to('write_review',$member->id)}}" class="btn btn-success">Review Now</a>
                </div>
              </div>
            </div>
          </div>
        @endforeach
        @else
        <h3 style="text-align:center">No DAHCP Found</h3>
      @endif
    </div>
<!-- 
    <div class="page_pagination">
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

    <!-- pagination start here-->
      @if(sizeOf($memberLists) > 0)
          @include('pagination.front')
      @endif
    <!-- pagination end here-->
<?php if(isset($search_type)){
    if($search_type == "ASSESMENT CENTRE"){
?>
    <script>
        $(document).ready(function(e){
          $.ajax({
            url:'<?php echo URL::to('/getSuggestionlocation') ?>',
            data:'',
            success:function(data){
              var array =[];
              $.each(data,function(e){
                array.push(data[e].name);
              })
              setData(array);
            },
            error:function(x){
              console.log(x);
            }
        });

        function setData(data){

          $( "#assesment_suggestion" ).autocomplete({
            source: data
          });
        }
        });
    </script>
<?php
    }
}?>
  </div>
</div>
@include('element.latest_review')
@include('layouts.main.footer_top')
@stop