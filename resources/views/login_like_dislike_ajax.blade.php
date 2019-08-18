
  <div class="total_likes like_unlike" buttonType="1" hcpId="{{$hcpId}}"> 
    @if($likeAndDislike==2)
     {{ HTML::image('img/like_icon.png', '', array('width' => '38', 'height' => '38')) }}
    @else
      {{ HTML::image('img/like_icon_act.png', '', array('width' => '38', 'height' => '38')) }}
    @endif
    <span class="total_like_count">
      {{ $totalLike }}
    </span>
  </div>

  <div class="total_dislikes like_unlike" buttonType="2" hcpId="{{$hcpId}}"> 
    @if($likeAndDislike==2)
      {{ HTML::image('img/dislike_icon_act.png', '', array('width' => '38', 'height' => '38')) }}  
    
    @else
      {{ HTML::image('img/dislike_icon.png', '', array('width' => '38', 'height' => '38')) }}
    @endif
    <span class="total_like_count">
      {{ $totalDisLike }}
    </span>
  </div>
