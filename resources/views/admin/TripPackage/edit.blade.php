@extends('admin.layouts.default')

@section('content')
<!-- CKeditor start here-->

{{ HTML::style('css/admin/custom_li_bootstrap.css') }}
{{ HTML::style('//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css') }}
{{ HTML::script('js/bootstrap.js') }}
{{ HTML::script('js/admin/ckeditor/ckeditor.js') }}
{{ HTML::script('https://code.jquery.com/ui/1.12.1/jquery-ui.js') }}
<!-- CKeditor ends-->

<div class="mws-panel grid_8">
	<div class="mws-panel-header" style="height: 46px;">
		<span> {{ trans("Edit Trip Package") }} </span>
		<a href="{{URL::to('admin/list-trip-package')}}" class="btn btn-success btn-small align">{{ trans("messages.system_management.back_to_cms") }} </a>
	</div>


	{{ Form::open(['role' => 'form','url' => 'admin/update-trip-package/'.$TripPackage->id,'class' => 'mws-form', 'files' => true]) }}
	
	<div class="mws-panel-body no-padding tab-content">
		<div class="mws-form-inline">

			<div class="mws-form-row ">
				{{ HTML::decode( Form::label('trip_name', trans("Trip Name").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) }}
				<div class="mws-form-item">
						{{ Form::select(
							 'trip_name',
							 [null => 'Please Select Trip Name']+ $tripList,
							 isset($TripPackage->trip_id) ? $TripPackage->trip_id :'',
							 ['class'=>'small','id'=>'trip_id']
							) 
						}}						<div class="error-message help-inline">
						<?php echo $errors->first('trip_name'); ?>
					</div>
				</div>
			</div>

			<div class="mws-form-row ">
				{{ HTML::decode( Form::label('status_name', trans("Status Name").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) }}
				<div class="mws-form-item">
						{{ Form::select(
							 'status_name',
							 [null => 'Please Select Status Name']+ $statusList,
							 isset($TripPackage->status_id) ? $TripPackage->status_id :'',
							 ['class'=>'small','id'=>'status_id']
							) 
						}}						<div class="error-message help-inline">
						<?php echo $errors->first('status_name'); ?>
					</div>
				</div>
			</div>

			<div class="mws-form-row">
				{{ HTML::decode( Form::label('trip_date', trans("Date").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) }}
				<div class="mws-form-item">
					{{ Form::text('trip_date',isset($TripPackage->trip_date) ? date('d-m-Y',strtotime($TripPackage->trip_date)) :'',['class'=>'small','id'=>'datepicker','autocomplete'=>'off']) }}
					<div class="error-message help-inline">
						<?php echo $errors->first('trip_date'); ?>
					</div>
				</div>
				<!-- <img class="ui-datepicker-trigger" src="images/calendar.gif" alt="Select date" title="Select date"> -->
				<!-- <p>Format options:<br>
				  <select id="format">
				    <option value="mm/dd/yy">Default - mm/dd/yy</option>
				    <option value="yy-mm-dd">ISO 8601 - yy-mm-dd</option>
				    <option value="d M, y">Short - d M, y</option>
				    <option value="d MM, y">Medium - d MM, y</option>
				    <option value="DD, d MM, yy">Full - DD, d MM, yy</option>
				    <option value="&apos;day&apos; d &apos;of&apos; MM &apos;in the year&apos; yy">With text - 'day' d 'of' MM 'in the year' yy</option>
				  </select>
				</p> -->
			</div>
			
			<div class="mws-form-row ">
				{{ HTML::decode( Form::label('trip_price', trans("Price").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) }}
				<div class="mws-form-item">
					{{ Form::text('trip_price',isset($TripPackage->price) ? $TripPackage->price :'',['class'=>'small']) }}
					<div class="error-message help-inline">
						<?php echo $errors->first('trip_price'); ?>
					</div>
				</div>
			</div>
			<div class="mws-form-row ">
				{{ HTML::decode( Form::label('supplement', trans("Supplement").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) }}
				<div class="mws-form-item">
					{{ Form::text('supplement',isset($TripPackage->supplement) ? $TripPackage->supplement :'',['class'=>'small']) }}
					<div class="error-message help-inline">
						<?php echo $errors->first('supplement'); ?>
					</div>
				</div>
			</div>
		</div>
		<div class="mws-button-row">
			<div class="input" >
				<input type="submit" value="{{ trans('messages.system_management.save') }}" class="btn btn-danger">
				<a href="{{URL::to('admin/list-trip-package/edit-trip-package/'.$TripPackage->id)}}" class="btn primary"><i class=\"icon-refresh\"></i> {{ trans('messages.system_management.reset') }}</a>
			</div>
		</div>
	</div>
	{{ Form::close() }} 
</div>

<script>
	$(function(){
	 $( "#datepicker" ).datepicker();
	 $( "#datepicker" ).datepicker( "option", "dateFormat", "dd-mm-yy" );
	 $( "#format" ).on( "change", function() {
	 $( "#datepicker" ).datepicker( "option", "dateFormat", $( this ).val() );
	 });
	});

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
