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
		<span> {{ trans("Add new Country Nature Review") }} </span>
		<a href="{{URL::to('admin/photo-country-manager-nature/list')}}" class="btn btn-success btn-small align">{{ trans("messages.system_management.back_to_cms") }} </a>
	</div>

	
	{{ Form::open(['role' => 'form','url' => 'admin/photo-country-manager-nature/save','class' => 'mws-form' , 'enctype'=>"multipart/form-data"]) }}
	<div class="mws-panel-body no-padding">
		

		<div class="mws-form-inline">
			<div class="mws-form-row ">
				{{ HTML::decode( Form::label('country_id', trans("Country Name").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label chosen-select' ])) }}
				<div class="mws-form-item">
						{{ Form::select(
							 'country_id',
							 [null => 'Please Select Country Name']+ $countryList,
							 '',
							 ['class'=>'small','id'=>'country_id']
							) 
						}}						<div class="error-message help-inline">
						<?php echo $errors->first('country_id'); ?>
					</div>
				</div>
			</div>
		</div>

	<script>
		$(document).ready(function(e){
			$("#country_id").chosen();
		})
	</script>		

	</div>


	<div class="mws-panel-body no-padding tab-content">
		
		<div class="mws-form-row ">
				{{ HTML::decode( Form::label('image', 'Image <span class="requireRed"></span>', ['class' => 'mws-form-label floatleft'])) }}
				<!-- Toll tip div start here -->
				<span class='tooltipHelp' title="" data-html="true" data-toggle="tooltip"  data-original-title="<?php echo "The attachment must be a file of type:".IMAGE_EXTENSION; ?>" style="cursor:pointer;">
					<i class="fa fa-question-circle fa-2x"> </i>
				</span>
				<!-- Toll tip div end here -->
				<div class="mws-form-item">
					{{ Form::file('image[]',['multiple'=>'true']) }}
					<div class="error-message help-inline">
						<?php echo $errors->first('image'); ?>
					</div>
				</div>
		</div>
		
	</div> 

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

