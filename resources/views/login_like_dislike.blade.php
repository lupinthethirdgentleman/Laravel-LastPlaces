<div class="most_review_likes most_review_likes_{{$mostrevmemb1->id}}" >
  <div class="total_likes like_unlike" buttonType="1" id="like_{{$mostrevmemb1->id}}" hcpId="{{$mostrevmemb1->id}}"> 
    <?php 
       $currentUserLikeDetail = App\Model\Like::where('user_id',Auth::user()->id)->where('hcp_id',$mostrevmemb1->id)->first();
       $likeAndDislike        = (isset($currentUserLikeDetail->like))?$currentUserLikeDetail->like:0; 
    ?>
    <?php 
     $likeCount           = App\Model\Like::where('hcp_id',$mostrevmemb1->id)->where('like',1)->count();
     $disLikeCount        = App\Model\Like::where('hcp_id',$mostrevmemb1->id)->where('like',2)->count();
  ?> 

    @if($likeAndDislike==2)
     {{ HTML::image('img/like_icon.png', '', array('width' => '38', 'height' => '38')) }}
    @else
      {{ HTML::image('img/like_icon_act.png', '', array('width' => '38', 'height' => '38')) }}
    @endif
  <span class="total_like_count">
      {{ $likeCount}}
    </span>
  </div>

  <div class="total_dislikes like_unlike" buttonType="2" hcpId="{{$mostrevmemb1->id}}" id="dislike_{{$mostrevmemb1->id}}">
    @if($likeAndDislike==2)
      {{ HTML::image('img/dislike_icon_act.png', '', array('width' => '38', 'height' => '38')) }}  
    
    @else
      {{ HTML::image('img/dislike_icon.png', '', array('width' => '38', 'height' => '38')) }}
    @endif
  
    <span class="total_like_count">
    {{ $disLikeCount}}
    </span></div>
</div>