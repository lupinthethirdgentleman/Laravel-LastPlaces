@extends('layouts.inner')
@section('content')
<style>
.lastplaces-divider-inner{
  width:100%;
  border-color:#c6b689;
  border-top-width:1px;
  border-bottom-width:1px;
  height:1px;
  border-style: dashed;
}
.lastplaces_seperator{
  padding-top: 20px;
padding-bottom: 20px;
font-size: 14px;

font-style: normal;

font-weight: 400;
}
</style>
<div class="page_title">
    <div class="container">
        <div class="row">
            <div class="col-sm-offset-4">
                <h1>{{isset($blogData['blog_description']['name'])?$blogData['blog_description']['name']:'Blog View' }}</h1>
            </div>
        </div>
    </div>
</div>

<div class="main_content">
  <div class="container">
      <div class="row">
          <div class="col-sm-4">
           <?php /*
                <div class="section_heading">
                   <div style="background:#f3efe4; padding:6px 15px; font-size:16px; font-weight:600; color:#3c3b3b; margin-bottom:15px;">Recent Blogs</div>
                   <div class="trip_list_sidebar">
                  @foreach($recentBlogObj as $recentBlogList)
                   @if($recentBlogList->image != '' && File::exists(BLOG_IMAGE_ROOT_PATH.$recentBlogList->image))
                     <div class="sidebar-last-place" style="border-bottom:none;">  
                       <div class=""> 
                        <a href="{{ URL::to('blogs/' . $recentBlogList->slug) }}"> 
                        <img src="<?php echo WEBSITE_URL .'image.php?width=320px&height=160px&cropratio=2:1&image=' . BLOG_IMAGE_URL.$recentBlogList->image;?>" width="550" height="480"  alt="" class="img-responsive"/> 
                        </a>
                       </div>
                           <p class="country_sidebar_name"><a href="{{ URL::to('blogs/' . $recentBlogList->slug) }}">{{$recentBlogList->name}}</a></p>
                            <p class="country_sidebar_name">{{$recentBlogList->description}}</p>
                    
                    </div>
                       <div class="lastplaces_seperator">
                          <div class="lastplaces-divider-inner" ></div>
                       </div>
                    @else
                     <p> <a href="{{ URL::to('blogs/' . $recentBlogList->slug) }}">{{ HTML::image('img/noimg.png', '', array('width' => '80', 'height' => '80' )) }} </a></p>
                     <p><a href="{{ URL::to('blogs/' . $recentBlogList->slug) }}">{{$recentBlogList->name}}</a></p>
                     @endif
                   @endforeach
                   </div>
               </div>  */ ?>
               <div class="trip_list_sidebar">
                  <h2>Recent Blogs</h2>
                   @foreach($recentBlogObj as $recentBlogList)

                   @if($recentBlogList->image != '' && File::exists(BLOG_IMAGE_ROOT_PATH.$recentBlogList->image))
            <div class="sidebar-last-place">  
                 <p class="country_sidebar_image"> <a href="{{ URL::to('blogs/' . $recentBlogList->slug) }}"> <img src="<?php echo BLOG_IMAGE_URL.$recentBlogList->image; ?>" width="550" height="480"  alt="" class="img-responsive"/> </a>
                 </p>
                     <p class="country_sidebar_name"><a href="{{ URL::to('blogs/' . $recentBlogList->slug) }}">{{$recentBlogList->blogDescription->name}}</a></p>
                     <div class="country_sidebar_desc">{{ strip_tags(Str::limit($recentBlogList->blogDescription->description, 250)) }}</div>
            </div>
                    @else
                     <p> <a href="{{ URL::to('blogs/' . $recentBlogList->slug) }}">{{ HTML::image('img/noimg.png', '', array('width' => '80', 'height' => '80' )) }} </a></p>
                     <p><a href="{{ URL::to('blogs/' . $recentBlogList->slug) }}">{{$recentBlogList->name}}</a></p>
                     @endif
                   @endforeach
            </div>
          </div>
          <div class="col-sm-8">

             <div class="blog_data">
                

                  <img src="{{ url('/uploads/blog') }}/{{$blogData['image']}}" alt="{{$blogData['image']}}" class="img-responsive"> 

                  <div class="bigpost_time">
                          <i class="fa fa-clock-o" aria-hidden="true"></i>
                          {{date('M d, Y',strtotime($blogData['created_at']))}}
                          &nbsp&nbsp
                          <i class="fa fa-user" aria-hidden="true"></i>
                          {{ $blogData['blogs_author']['first_name'] . " " . $blogData['blogs_author']['last_name']}}
                </div>
                
                 <p>{{isset($blogData['blog_description']['description'])?$blogData['blog_description']['description']:'' }}</p>
              </div>

                <button type="button" class="btn btn-info comment-btn" data-toggle="collapse" data-target="#blog_comment_form">{{trans('messages.clicktocomment')}}</button> 

                 <div id="blog_comment_form" class="collapse bigpost-comment-form"> 
                                 <form action ="{{URL::to('/save-comment')}}" method="post" name="comment-form" id="comment-form">
                                      <input type="hidden" value="{{$blogData['id']}}" name="blog_id">
                                      <div class="form-group">
                                          <label for="firstName" class="control-label">Full Name</label>
                                          
                                              <input type="text" id="name" name="name" placeholder="Full Name" class="form-control" required />
                                         
                                      </div>
                                      <div class="form-group">
                                          <label for="firstName" class=" control-label">Email</label>
                                          
                                              <input type="email" id="email" name="email" placeholder="Email" class="form-control" required />
                                          
                                      </div>
                                      <div class="form-group">
                                          <label for="firstName" class=" control-label">Comment</label>
                                          
                                              <textarea id="comment" name="comment" placeholder="Comment" class="form-control" required></textarea>
                                         
                                      </div>
                                      <div class="form-group">
                                          
                                              <button type="submit" class="btn btn-primary btn-block">Submit</button>
                                       </div>
                                 </form>
             </div>  

              <div class="blog_comment_list">
                  <h3>{{ trans('messages.comments') }}</h3>

                 @foreach($blogData['blog_comments'] as $blog_comment_list)
                  <div class="blog_comment">
                      <p class="cm_name">{{ trans('messages.name') }} : {{$blog_comment_list['full_name']}}</p>
                      <p class="cm_email">{{ trans('messages.email') }} : {{$blog_comment_list['email']}}</p>
                      <p class="cm_comment">{{ trans('messages.comment') }} : {{$blog_comment_list['comment']}}</p>
                 </div>
                 @endforeach

             </div>

          </div>
      </div>
 </div>
</div>
@include('layouts.main.footer_top')
@stop