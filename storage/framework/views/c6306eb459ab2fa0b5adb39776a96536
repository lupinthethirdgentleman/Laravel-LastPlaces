<div class="footer_top">

</div>
<script type="text/javascript">

  $( document ).ready(function() {
    $("body").on("click",".like_unlike", function(){
     likeAndDislike = $(this).attr('buttonType');
     hcpId          = $(this).attr('hcpId');
      $.ajax({
           type:'POST',
           url:'<?php echo URL::to('/likeOrDislike')?>',
           data:{'likeAndDislike':likeAndDislike,'user':hcpId},
           success:function(data){
              $(".most_review_likes_"+hcpId).html(data.html);
           },
           error:function(x){
              console.log(x);
           }
      })
    });

    $("body").on("click",".like_unlike_member", function(){
     likeAndDislike = $(this).attr('buttonType');
     hcpId          = $(this).attr('hcpId');
      $.ajax({
           type:'POST',
           url:'<?php echo URL::to('/likeOrDislikeMember')?>',
           data:{'likeAndDislike':likeAndDislike,'user':hcpId},
           success:function(data){
              $(".member_like_section").html(data.html);
           },
           error:function(x){
              console.log(x);
           }
      })
    });
  });




</script>