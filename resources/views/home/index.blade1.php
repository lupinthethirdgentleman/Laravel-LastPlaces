@extends('layouts.inner')
@section('content')
<?php $blogCount = 0; 
    //echo $highlightBlogCount;
    //die ;
    $header_list = DB::table('settings')->where('id', '392')->first();

?>

<div class="main_slider"><img src="{{ HEADER_IMAGE_URL.''.$header_list->value; }}" width="1903" height="878" alt=""/></div>

<div class="slider_bottom">
	<div class="container">
    	{{isset($blocksCall['description']['description'])?$blocksCall['description']['description']:''}}
    </div>
</div>

<div class="dream_true">
	<div class="container">
    	<div class="dream_header">
        	{{ isset($blocksDream['description']['description'])?$blocksDream['description']['description']:'' }}
        </div>
        <ul class="dream_list">
           
            @foreach($highlightCountry as $highlight_country)
        	<li class="dream_item">
				<div class="dream_image">
                    <a href="{{ URL::to('country-trips/'.$highlight_country->slug) }}">
                        <!-- <img src="<?php echo COUNTRY_IMAGE_URL.$highlight_country->image; ?>" width="410" height="271" alt="" /> -->
                        <img src="<?php echo WEBSITE_URL.'image.php?width=410px&height=271px&image='.COUNTRY_IMAGE_URL.$highlight_country->image;?>" alt="" />
                        <?php //echo "<pre>"; print_r($highlight_country);die;
                        //echo $highlight_country->destinationCountryDescription[0]->source_col_description;die; ?>
                        <span class="dream_title">
                            {{ $highlight_country->destinationCountryDescription[0]->source_col_description }}
                        </span>
                    </a>
                </div>
            </li>
            @endforeach
        	<!-- <li class="dream_item/">
				<div class="dream_image"><a href="#"><img src="img/dream_img2.jpg" width="410" height="271" alt=""/><span class="dream_title">kenya</span></a></div>
            </li>
        	<li class="dream_item">
				<div class="dream_image"><a href="#"><img src="img/dream_img3.jpg" width="410" height="271" alt=""/><span class="dream_title">{{ trans('messages.trip-to') }}</span></a></div>
            </li>
        	<li class="dream_item">
				<div class="dream_image"><a href="#"><img src="img/dream_img4.jpg" width="410" height="271" alt=""/><span class="dream_title">{{ trans('messages.trip-to') }} Svalbard</span></a></div>
            </li>
        	<li class="dream_item">
				<div class="dream_image"><a href="#"><img src="img/dream_img5.jpg" width="410" height="271" alt=""/><span class="dream_title">{{ trans('messages.safari activitie') }}</span></a></div>
            </li>
        	<li class="dream_item">
				<div class="dream_image"><a href="#"><img src="img/dream_img6.jpg" width="410" height="271" alt=""/><span class="dream_title">{{ trans('messages.rio-carnival') }}</span></a></div>
				<div class="dream_title"></div>
            </li> -->
        </ul>
    </div>
</div>

<div class="our_blogs">
	<div class="container">
    	<div class="blog_header">
        	{{ isset($blocksBlog2['description']['description'])?$blocksBlog2['description']['description']:'' }}
        </div>
        <div class="row">
        	<div class="col-sm-6">
            	<div class="blogpost_big">
                    @if(isset($highlightBlogCount) && $highlightBlogCount >0)
                       
                        @foreach($blocksBlogForHighlight as $highblogs)

                            @if($highblogs->is_highlight == 1)

                                <a href="{{ URL::to('blogs/'.$highblogs->slug) }}">
                                    <!-- <img src="{{ url('/uploads/blog') }}/{{$highblogs->image}}" width="569" height="533"  alt=""/> -->
                                    <img src="<?php echo WEBSITE_URL.'image.php?width=569px&height=533px&image='.BLOG_IMAGE_URL.$highblogs->image;?>" />
                                </a>
                                <div class="bigpost_content">
                                    <div class="bigpost_top">
                                        <div class="bigpost_time"><i class="fa fa-clock-o" aria-hidden="true"></i>{{date('M d, Y',strtotime($highblogs->created_at))}}</div>
                                        <div class="bigpost_comments"><i class="fa fa-comments-o" aria-hidden="true"></i> {{$highblogs->blogComments->count()}} {{ trans('messages.Comments') }}</div>
                                        <div class="bigpost_comments"><i class="fa fa-user" aria-hidden="true"></i> 
                                        {{ $highblogs->blogsAuthor->first_name . " " . $highblogs->blogsAuthor->last_name }}
                                       </div>
                                    </div>
                                    <div class="bigpost_description"><a href="{{ URL::to('blogs/'.$highblogs->slug) }}">{{str_limit($highblogs->description, 100);}}</a></div>
                                </div>
                            @endif

                        @endforeach

                    @else
                        

                        @foreach($blocksBlog as $highblogs)

                            <a href="{{ URL::to('blogs/'.$highblogs->slug) }}">
                                <!-- <img src="{{ url('/uploads/blog') }}/{{$highblogs->image}}" width="569" height="533"  alt=""/> -->
                                    <img src="<?php echo WEBSITE_URL.'image.php?width=569px&height=533px&image='.BLOG_IMAGE_URL.$highblogs->image;?>" />
                                </a>
                            <div class="bigpost_content">
                                <div class="bigpost_top">
                                    <div class="bigpost_time"><i class="fa fa-clock-o" aria-hidden="true"></i>{{date('M d, Y',strtotime($highblogs->created_at))}}</div>
                                    <div class="bigpost_comments"><i class="fa fa-comments-o" aria-hidden="true"></i> {{$highblogs->blogComments->count()}} {{ trans('messages.Comments') }}</div>
                                     <div class="bigpost_comments"><i class="fa fa-user" aria-hidden="true"></i> 
                                        {{ $highblogs->blogsAuthor->first_name . " " . $highblogs->blogsAuthor->last_name }}
                                       </div>
                                </div>
                                <div class="bigpost_description">{{str_limit($highblogs->description, 100);}}</div>
                            </div>

                            <?php

                            if($blogCount == 0){

                                break;

                            }

                            ?>
                        
                        @endforeach 

                    @endif
                    

                	<!-- <a href="#"><img src="img/blog_img1.jpg" width="569" height="533"  alt=""/></a>
                    <div class="bigpost_content">
                    	<div class="bigpost_top">
                        	<div class="bigpost_time"><i class="fa fa-clock-o" aria-hidden="true"></i> September 11, 2017</div>
                        	<div class="bigpost_comments"><i class="fa fa-comments-o" aria-hidden="true"></i> 0 Comments</div>
                        </div>
                    	<div class="bigpost_description">Sit amet molestie felis blandit vel enean malesuada risus vel aliquet</div>
                    </div> 
                     -->
                </div>
            </div>
        	<div class="col-sm-6">
            	<ul class="home_blog_list">
	               
                    @foreach($blocksBlog as $blogs)
                        <li class="hbl_item">
                            <div class="row">
                                <div class="col-sm-4">
                                    <a href="{{ URL::to('blogs/'.$blogs->slug) }}">
                                        <img src="<?php echo WEBSITE_URL.'image.php?width=163px&height=163px&image='.BLOG_IMAGE_URL.$blogs->image;?>" alt=""/>
                                    </a>
                                </div>
                                <div class="col-sm-8">
                                    <div class="bigpost_description_heading">
                                        <a href="{{ URL::to('blogs/'.$blogs->slug) }}">
                                        {{$blogs->blogDescription->name}}
                                     </a>
                                    </div>
                                    <div class="bigpost_description">
                                        {{str_limit($blogs->blogDescription->description, 150)}}
                                    </div>
                                    <div class="bigpost_top">
                                        <div class="bigpost_time"><i class="fa fa-clock-o" aria-hidden="true"></i>
                                            {{date('M d, Y',strtotime($blogs->created_at))}}
                                        </div>
                                    <div class="bigpost_comments"><i class="fa fa-comments-o" aria-hidden="true"></i> 
                                        {{$blogs->blogComments->count()}} {{ trans('messages.Comments') }}
                                    </div>
                                    <div class="bigpost_authorname"><i class="fa fa-user" aria-hidden="true"></i> 
                                        {{ $blogs->blogsAuthor->first_name . " " . $blogs->blogsAuthor->last_name }}
                                    </div>
                                    </div>                                
                                </div>
                            </div>
                        </li>

                    @endforeach

                   
                 <!--     <li class="hbl_item">
                    	<div class="row">
                        	<div class="col-sm-4"><a href="#"><img src="img/blog_img2.jpg" width="163" height="163"  alt=""/></a></div>
                        	<div class="col-sm-8">
                            	<div class="bigpost_description">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut in lorem sed</div>
                    			<div class="bigpost_top">
                                	<div class="bigpost_time"><i class="fa fa-clock-o" aria-hidden="true"></i> September 11, 2017</div>
                                    <div class="bigpost_comments"><i class="fa fa-comments-o" aria-hidden="true"></i> 0 Comments</div>
                                </div>                                
                            </div>
                        </div>
                    </li>
                	<li class="hbl_item">
                    	<div class="row">
                        	<div class="col-sm-4"><a href="#"><img src="img/blog_img3.jpg" width="163" height="163"  alt=""/></a></div>
                        	<div class="col-sm-8">
                            	<div class="bigpost_description">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut in lorem sed</div>
                    			<div class="bigpost_top">
                                	<div class="bigpost_time"><i class="fa fa-clock-o" aria-hidden="true"></i> September 11, 2017</div>
                                    <div class="bigpost_comments"><i class="fa fa-comments-o" aria-hidden="true"></i> 0 Comments</div>
                                </div>                                
                            </div>
                        </div>
                    </li>
                	<li class="hbl_item">
                    	<div class="row">
                        	<div class="col-sm-4"><a href="#"><img src="img/blog_img4.jpg" width="163" height="163"  alt=""/></a></div>
                        	<div class="col-sm-8">
                            	<div class="bigpost_description">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut in lorem sed</div>
                    			<div class="bigpost_top">
                                	<div class="bigpost_time"><i class="fa fa-clock-o" aria-hidden="true"></i> September 11, 2017</div>
                                    <div class="bigpost_comments"><i class="fa fa-comments-o" aria-hidden="true"></i> 0 Comments</div>
                                </div>                                
                            </div>
                        </div>
                    </li> -->

                </ul>
            </div>
        </div>
    </div>
</div>

@stop