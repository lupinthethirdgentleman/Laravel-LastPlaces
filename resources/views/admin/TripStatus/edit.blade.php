@extends('admin.layouts.default')

@section('content')
<!-- CKeditor start here-->

{{ HTML::style('css/admin/custom_li_bootstrap.css') }}
{{ HTML::script('js/bootstrap.js') }}
{{ HTML::script('js/admin/ckeditor/ckeditor.js') }}
<!-- CKeditor ends-->

<div class="mws-panel grid_8">
	<div class="mws-panel-header" style="height: 46px;">
		<span> {{ trans("Edit Trip Status") }} </span>
		<a href="{{URL::to('admin/list-trip-status')}}" class="btn btn-success btn-small align">{{ trans("messages.system_management.back_to_cms") }} </a>
	</div>


	{{ Form::open(['role' => 'form','url' => 'admin/update-trip-status/'.$TripStatus->id,'class' => 'mws-form', 'files' => true]) }}
	
	<div class="mws-panel-body no-padding tab-content">
		<div class="mws-form-inline">
			<div class="mws-form-row ">
				{{ HTML::decode( Form::label('status_name', trans("Status Name").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) }}
				<div class="mws-form-item">
					{{ Form::text('status_name',isset($TripStatus->status_name) ? $TripStatus->status_name :'',['class'=>'small']) }}
					<div class="error-message help-inline">
						<?php echo $errors->first('status_name'); ?>
					</div>
				</div>
			</div>
		</div>
		<div class="mws-button-row">
			<div class="input" >
				<input type="submit" value="{{ trans('messages.system_management.save') }}" class="btn btn-danger">
				<a href="{{URL::to('admin/list-trip-status/edit-trip-status/'.$TripStatus->id)}}" class="btn primary"><i class=\"icon-refresh\"></i> {{ trans('messages.system_management.reset') }}</a>
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
				 url: '<?php echo url('admin/list-hcp/getlocation'); ?>/'+company_id,
				 type: "get",
				 dataType: "json",
                success:function(data) {
                    $('select[name="company_location"]').empty();
     	             $.each(data, function(key, value) {

                        $('select[name="company_location"]').append('<option>Please Select Company Location</option><option value="'+ key +'">'+ value +'</option>');

                    });
                }
			})	
		});
		var company_id = $('#company_id').val();
		$.ajax({
			 url: '<?php echo url('admin/list-hcp/getlocation'); ?>/'+company_id,
			 type: "get",
			 dataType: "json",
            success:function(data) {
                $('select[name="company_location"]').empty();
                $.each(data, function(key, value) {

                    $('select[name="company_location"]').append('<option value="">Please Select Company Location</option><option value="'+ key +'" selected="selected">'+ value +'</option>');
                });
            }
		})	
	});
</script>

@stop
