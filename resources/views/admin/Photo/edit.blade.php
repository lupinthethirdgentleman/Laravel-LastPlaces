@extends('admin.layouts.default')

@section('content')

<!-- CKeditor start here-->
{{ HTML::style('css/admin/custom_li_bootstrap.css') }}
{{ HTML::script('js/bootstrap.js') }}
{{ HTML::script('js/admin/ckeditor/ckeditor.js') }}
<style>
.image_display{
	width:20%;
	float:left;
	margin:10px;
	padding:10px;
}
</style>
<div class="mws-panel grid_8">

	<div class="mws-panel-header" style="height: 46px;">
		<span> {{ trans("messages.$modelName.table_heading_edit") }} </span>
		<a href='{{ route("$modelName.index")}}' class="btn btn-success btn-small align">{{ trans("messages.global.back") }} </a>
	</div>

		

		{{ Form::open(['role' => 'form','url' =>  route("$modelName.edit",""),'class' => 'mws-form']) }}
		
	<div class="mws-panel-body no-padding">
		

		<div class="mws-form-inline">
			
		</div>
	</div>

		<div class="mws-panel-body no-padding tab-content"> 
			@foreach($photos as $photo_display)
			<div class="image_display">
				<img src="<?php echo WEBSITE_URL .'image.php?width=300&height=180px&cropratio=5:3&image=' . PHOTOGALLERY_IMAGE_URL.$photo_display->image;?>" alt="" />
				<div style="margin-top:10px;">
					<a href="{{URL::to('/admin/photo-manager/delete-photo/'.$photo_display->id)}}" class="btn btn-danger">Delete</a>
			    </div>
			</div>
			@endforeach



			<div style="clear:both;"></div>
			<div class="mws-button-row">
				<!--<div class="input" >
					<input type="submit" value="{{ trans('messages.global.delete') }}" class="btn btn-danger">
				</div> -->
			</div>
		</div>



		{{ Form::close() }} 


</div>

@stop
