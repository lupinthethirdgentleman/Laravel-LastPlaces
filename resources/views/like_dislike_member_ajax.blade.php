

@if($likeAndDislike==2)

  <div class=" like_unlike_member" buttonType="1" hcpId="{{$hcpId}}" style="margin-bottom: 12px;">
    <img src="<?php echo WEBSITE_URL.'img/like_icon.png?width=38px&height=38px&cropratio=1:1'?>" width="38" height="38"  alt=""/>
    <span class="total_like_count">
      {{ $totalLike }}
    </span>
  </div>  

@else

  <div class=" like_unlike_member" buttonType="1" hcpId="{{$hcpId}}" style="margin-bottom: 12px;">
    <img src="<?php echo WEBSITE_URL.'img/like_icon_act.png?width=38px&height=38px&cropratio=1:1'?>" width="38" height="38"  alt=""/>
    <span class="total_like_count">
      {{ $totalLike }}
    </span>
  </div>

@endif
    
@if($likeAndDislike==2)

  <div class=" like_unlike_member" buttonType="2" hcpId="{{$hcpId}}" style="">
    <span class="total_like_count">
      <img src="<?php echo WEBSITE_URL.'img/dislike_icon_act.png?width=38px&height=38px&cropratio=1:1'?>" width="38" height="38"  alt=""/>
      {{ $totalDisLike }}
    </span>
  </div>

@else

  <div class=" like_unlike_member" buttonType="2" hcpId="{{$hcpId}}">
    <img src="<?php echo WEBSITE_URL.'img/dislike_icon.png?width=38px&height=38px&cropratio=1:1'?>" width="38" height="38"  alt=""/>
    <span class="total_like_count">
      {{ $totalDisLike }}
    </span>
  </div>
  
@endif
  
