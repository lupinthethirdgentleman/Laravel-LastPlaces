<?php 
     $currentUserLikeDetail = App\Model\Like::where('user_id',Auth::user()->id)->where('hcp_id',$member->id)->first();
     $likeAndDislike        = (isset($currentUserLikeDetail->like))?$currentUserLikeDetail->like:0; 
?>
<?php 
     $likeCount           = App\Model\Like::where('hcp_id',$member->id)->where('like',1)->count();
     $disLikeCount        = App\Model\Like::where('hcp_id',$member->id)->where('like',2)->count();
?> 
@if($likeAndDislike==2)
  
  <div class="like_unlike_member" buttonType="1" hcpId="{{$hcpId}}" style="margin-bottom: 12px;">
  <span class="total_like_count"> 
       <img src="<?php echo 'img/like_icon.png?width=38px&height=38px&cropratio=1:1'?>" width="38" height="38"  alt=""/>
      {{ $likeCount }}
    </span> 
  </div>

@else

  <div class="like_unlike_member" buttonType="1" hcpId="{{$hcpId}}" style="margin-bottom: 12px;">
    <img src="<?php echo 'img/like_icon_act.png' ?>" width="38" height="38"  alt=""/>
    <span class="total_like_count">
      {{ $likeCount }}
    </span>
  </div>

@endif

<?php //echo "dsfsdfsd"; ?>

@if($likeAndDislike==2)

  <div class=" like_unlike_member" buttonType="2" hcpId="{{$hcpId}}">
    <img src="<?php echo 'img/dislike_icon_act.png' ?>" width="38" height="38"  alt=""/>
    <span class="total_like_count">
      {{ $disLikeCount }}
    </span>
  </div>  

@else
 
  <div class=" like_unlike_member" buttonType="2" hcpId="{{$hcpId}}">
    <img src="<?php echo 'img/dislike_icon.png'?>" width="38" height="38"  alt=""/>
    <span class="total_like_count">
      {{ $disLikeCount }}
    </span>
  </div>
  
@endif

