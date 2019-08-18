@extends('admin.layouts.default')

@section('content')

<div class="mws-panel grid_8">
	<div class="mws-panel-header">
		<span> {{ trans("messages.$modelName.add_media_partner") }}</span>
		<a href="{{URL::to('admin/media')}}" class="btn btn-success btn-small align">{{ trans("messages.$modelName.back_to_media_parther") }} </a>
	</div>
	<div class="mws-panel-body no-padding">
		{{ Form::open(['role' => 'form','route' => "$modelName.save",'class' => 'mws-form', 'files' => true]) }}
			<div class="mws-form-inline">
				<div class="mws-form-row">
				
					
					{{   HTML::decode(Form::label('Title', trans("messages.$modelName.title").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) }}
					<div class="mws-form-item">
						{{ Form::text('title', null, ['class' => 'small']) }}
						<div class="error-message help-inline">
							<?php echo $errors->first('title'); ?>
						</div>
					</div>
				</div>
				
				<div class="mws-form-row">
					{{  HTML::decode(Form::label('logo', trans("messages.$modelName.logo").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) }}
					<div class="mws-form-item">
						{{ Form::file('logo') }}
							<!-- Toll tip div start here -->
							<span class='tooltipHelp' title="" data-html="true" data-toggle="tooltip"  data-original-title="<?php echo "The attachment must be a file of type:".IMAGE_EXTENSION; ?>" style="cursor:pointer;">
								<i class="fa fa-question-circle fa-2x"> </i>
							</span>
							<!-- Toll tip div end here -->
						<div class="error-message help-inline">
							<?php echo $errors->first('logo'); ?>
						</div>
					</div>
				</div>
				
				<div class="mws-form-row" id="link_div">
					{{ HTML::decode( Form::label('Order', trans("messages.$modelName.order").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label']) ) }}
					<div class="mws-form-item">
						{{ Form::number('order', 1, ['class' => 'small','min'=>'1','default'=>'1']) }}
						<div class="error-message help-inline">
							<?php echo $errors->first('order'); ?>
						</div>
					</div>
				</div>
				
			</div>
			<div class="mws-button-row">
				<div class="input" >
					<input type="submit" value='{{ trans("messages.global.save") }}' class="btn btn-danger">
					
					<a href='{{ route("$modelName.index")}}' class="btn primary"><i class=\"icon-refresh\"></i> {{ trans("messages.global.reset") }}</a>
				</div>
			</div>
		{{ Form::close() }}
	</div>    	
</div>

<!-- for tooltip -->
{{ HTML::script('js/bootstrap.min.js') }}
<!-- for tooltip -->
<script>
	/** For tooltip */
	$('[data-toggle="tooltip"]').tooltip();   
</script>
@stop
