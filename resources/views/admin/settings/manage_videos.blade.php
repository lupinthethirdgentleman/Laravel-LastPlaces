@extends('admin.layouts.default')

@section('content')

{{ VIDEO_INFO }}
<div class="mws-panel grid_8">
	<div class="mws-panel-header">
		<span>Manage Videos</span>
	</div>
	<div class="mws-panel-body no-padding">
	
		{{ Form::open(['role' => 'form','url' => 'admin/settings/manage-videos','class' => 'mws-form', 'files' => true]) }}
			<div class="mws-form-inline">
				<div class="mws-form-row">
					<label class="mws-form-label"  style="width:300px;">About Us Page Video</label>
					
					<div class="mws-form-item" style="float:left; margin:0">
						{{ Form::file("about_us_video") }} 
					</div>
					<div style="float:left">
						@if(!empty($allVideos) && isset($allVideos->about_us_video) && !empty($allVideos->about_us_video) && isset($allVideos->about_us_video_thumb))
						<video class="video-responsive" width="150" height="150" poster="{{ FIXED_VIDEO_THUMB_URL . $allVideos->about_us_video_thumb}}" controls >
							<source src="{{ FIXED_VIDEO_MP4_URL . $allVideos->about_us_video }}.mp4" type="video/mp4">
							<source src="{{ FIXED_VIDEO_WEBM_URL . $allVideos->about_us_video }}.webm" type="video/webm">
							Your browser does not support the video tag.
						</video> 
						@endif
					</div>
					<div class="error-message help-inline">
							<?php echo $errors->first('about_us_video'); ?>
					</div>
				</div>
			</div>
			<div class="mws-form-inline">
				<div class="mws-form-row">
					<label class="mws-form-label"  style="width:300px;">Home Page Video</label>
					
					<div class="mws-form-item" style="float:left; margin:0">
						{{ Form::file("home_video") }} 
					</div>
					<div style="float:left">
						@if(!empty($allVideos) && isset($allVideos->home_video) && !empty($allVideos->home_video) && isset($allVideos->home_video_thumb))
						<video width="150" height="150" poster="{{ FIXED_VIDEO_THUMB_URL . $allVideos->home_video_thumb}}" controls >
							<source src="{{ FIXED_VIDEO_MP4_URL . $allVideos->home_video }}.mp4" type="video/mp4">
							<source src="{{ FIXED_VIDEO_WEBM_URL . $allVideos->home_video }}.webm" type="video/webm">
							Your browser does not support the video tag.
						</video> 
						@endif
					</div>
					<div class="error-message help-inline">
							<?php echo $errors->first('home_video'); ?>
					</div>
				</div>
			</div>
			<div class="mws-form-inline">
				<div class="mws-form-row">
					<label class="mws-form-label"  style="width:300px;">How it Works Page Video</label>
					
					<div class="mws-form-item" style="float:left; margin:0">
						{{ Form::file("how_it_works_video") }} 
					</div>
					<div style="float:left">
						@if(!empty($allVideos) && isset($allVideos->how_it_works_video) && !empty($allVideos->how_it_works_video) && isset($allVideos->how_it_works_video_thumb))
						<video width="150" height="150" poster="{{ FIXED_VIDEO_THUMB_URL . $allVideos->how_it_works_video_thumb}}" controls >
							<source src="{{ FIXED_VIDEO_MP4_URL . $allVideos->how_it_works_video }}.mp4" type="video/mp4">
							<source src="{{ FIXED_VIDEO_WEBM_URL . $allVideos->how_it_works_video }}.webm" type="video/webm">
							Your browser does not support the video tag.
						</video> 
						@endif
					</div>
					<div class="error-message help-inline">
							<?php echo $errors->first('how_it_works_video'); ?>
					</div>
				</div>
			</div>
			<div class="mws-button-row">
				<input type="submit" value="Submit" class="btn btn-danger">
			</div>
		{{ Form::close() }} 
	</div>    	
</div>

@stop