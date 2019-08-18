@extends('admin.layouts.default')

@section('content')
<!-- CKeditor start here-->

{{ HTML::style('css/admin/custom_li_bootstrap.css') }}
{{ HTML::style('css/chosen.css') }}

{{ HTML::script('js/bootstrap.js') }}
{{ HTML::script('js/chosen.jquery.js') }}

{{ HTML::script('js/admin/ckeditor/ckeditor.js') }}
<style>
.chosen-container-single .chosen-single {
	padding:0px !important;
	height:30px;
}
</style>
<!-- CKeditor ends-->

<div class="mws-panel grid_8">
	<div class="mws-panel-header" style="height: 46px;">
		<span> {{ trans("Add new trip Review") }} </span>
		<a href="{{URL::to('admin/trip-reviews')}}" class="btn btn-success btn-small align">{{ trans("messages.system_management.back_to_cms") }} </a>
	</div>

	@if(count($languages) > 1)
		<div  class="default_language_color">
			{{ Config::get('default_language.message') }}
		</div>
		<div class="wizard-nav wizard-nav-horizontal">
			<ul class="nav nav-tabs">
				@foreach($languages as $value)
				<?php $i = $value->id; ?>
					<li class=" {{ ($i ==  $language_code )?'active':'' }}">
						<a data-toggle="tab" href="#{{ $i }}div">
							{{ $value -> title }}
						</a>
					</li>
				@endforeach
			</ul>
		</div>
	@endif
	
	{{ Form::open(['role' => 'form','url' =>  route("$modelName.update","$tripReview->id"),'class' => 'mws-form']) }}
	<div class="mws-panel-body no-padding">
		@if(count($languages) > 1)
			<div class="text-right mws-form-item" style="margin-right:20px; padding-top:10px; font-size: 12px;">
				<b></b>
			</div>
		@endif

		<div class="mws-form-inline">
			<div class="mws-form-row ">
				{{ HTML::decode( Form::label('trip_id', trans("Trip Name").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label chosen-select' ])) }}
				<div class="mws-form-item">
						{{ Form::select(
							 'trip_id',
							 [null => 'Please Select Trip Name']+ $tripList,
							 $tripReview->trip_id,
							 ['class'=>'small','id'=>'trip_id']
							) 
						}}						<div class="error-message help-inline">
						<?php echo $errors->first('trip_id'); ?>
					</div>
				</div>
			</div>
		</div>

	<script>
		$(document).ready(function(e){
			$("#trip_id").chosen();
		})
	</script>		

	</div>


	<div class="mws-panel-body no-padding tab-content">

		@foreach($languages as $value)
		<?php $i = $value->id; ?>
		<div id="{{ $i }}div" class="tab-pane {{ ($i ==  $language_code )?'active':'' }} ">


		<div class="mws-form-inline">
			<div class="mws-form-row ">
				{{ HTML::decode( Form::label($i.'clientname', trans("Client Name").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) }}
				<div class="mws-form-item">
					{{ Form::text("data[$i][".'clientname'."]",isset($multiLanguage[$i]['clientname'])?$multiLanguage[$i]['clientname']:'', ['class' => 'small']) }}
					<div class="error-message help-inline">
						<?php echo ($i ==  $language_code ) ? $errors->first('clientname') : ''; ?>
					</div>
				</div>
			</div>
			

			<div class="mws-form-row ">
				{{ HTML::decode( Form::label($i.'review', trans("Review").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) }}
					<div class="mws-form-item">
						{{ Form::textarea("data[$i][".'review'."]",isset($multiLanguage[$i]['review'])?$multiLanguage[$i]['review']:'', ['class' => 'small','id' => 'review'.$i]) }}
						<span class="error-message help-inline">
						<?php echo ($i ==  $language_code ) ? $errors->first('review') : ''; ?>
						</span>
					</div>
					<script type="text/javascript">
					/* CKEDITOR fro description */
					CKEDITOR.replace( <?php echo 'review'.$i; ?>,
					{
						height: 350,
						width: 600,
						filebrowserUploadUrl : '<?php echo URL::to('base/uploder'); ?>',
						filebrowserImageWindowWidth : '640',
						filebrowserImageWindowHeight : '480',
						enterMode : CKEDITOR.ENTER_BR
					});
									
					</script>
			</div>
		</div>
		</div> 
	@endforeach
		<div class="mws-button-row">
			<div class="input" >
				<input type="submit" value="{{ trans('messages.system_management.save') }}" class="btn btn-danger">
				<a href="{{URL::to('admin/list-destination-country/add-destination-country')}}" class="btn primary"><i class=\"icon-refresh\"></i> {{ trans('messages.system_management.reset') }}</a>
			</div>
		</div>
	</div>
	{{ Form::close() }} 
</div>

<script>
	$(document).ready(function(){

      	$('#company_id').on('change', function() {
			var company_id = $(this).val();
			$.ajax({
				 url: '<?php echo url('admin/list-destination-country/getlocation'); ?>/'+company_id,
				 type: "get",
				 dataType: "json",
                success:function(data) {
                    $('select[name="company_location"]').empty();
                    $('select[name="company_location"]').append('<option value="">Please Select Company Location');
                    $.each(data, function(key, value) {

                        $('select[name="company_location"]').append('<option value="'+ key +'">'+ value +'</option>');
                    });
                }
			})	
		});
      	   /* localStorage.setItem('todos', $('#company_id').val());
      	    test = localStorage.getItem('todos'); 
      	   // alert(test);
      	    $('#company_id').val(test);*/
  	 	var company_id = $('#company_id').val();
		$.ajax({
			 url: '<?php echo url('admin/list-destination-country/getlocation'); ?>/'+company_id,
			 type: "get",
			 dataType: "json",
            success:function(data) {
                $('select[name="company_location"]').empty();
                $('select[name="company_location"]').append('<option value="">Please Select Company Location');
                $.each(data, function(key, value) {

                    $('select[name="company_location"]').append('<option value="'+ key +'" selected="selected">'+ value +'</option>');
                });
            }
		})	
	});
</script>

@stop

