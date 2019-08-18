@extends('admin.layouts.default')

@section('content')
<script type="text/javascript">
$(function(){
	/* For delete user detail */
	$('[data-delete]').click(function(e){
		
	     e.preventDefault();
		// If the user confirm the delete
		if (confirm('Do you really want to delete the element ?')) {
			// Get the route URL
			var url = $(this).prop('href');
			// Get the token
			var token = $(this).data('delete');
			// Create a form element
			var $form = $('<form/>', {action: url, method: 'post'});
			// Add the DELETE hidden input method
			var $inputMethod = $('<input/>', {type: 'hidden', name: '_method', value: 'delete'});
			// Add the token hidden input
			var $inputToken = $('<input/>', {type: 'hidden', name: '_token', value: token});
			// Append the inputs to the form, hide the form, append the form to the <body>, SUBMIT !
			$form.append($inputMethod, $inputToken).hide().appendTo('body').submit();
		} 
	});
});
</script>


<div id="mws-themer">
	<div id="mws-themer-content" style="<?php echo ($searchVariable) ? 'right: 256px;' : ''; ?>">
		<div id="mws-themer-ribbon"></div>
		<div id="mws-themer-toggle" class="<?php echo ($searchVariable) ? 'opened' : ''; ?>">
			<i class="icon-bended-arrow-left"></i> 
			<i class="icon-bended-arrow-right"></i>
		</div>
	
		{{ Form::open(['method' => 'get','role' => 'form','url' => 'admin/jsconstant-setting','class' => 'mws-form']) }}
		{{ Form::hidden('display') }}
		
		<div class="mws-themer-section">
			<div id="mws-theme-presets-container" class="mws-themer-section">
				<label>{{ 'Key' }}</label><br/>
				{{ Form::text('key_value',((isset($searchVariable['key_value'])) ? $searchVariable['key_value'] : ''), ['class' => 'small']) }}
			</div>
		</div>
		
		<div class="mws-themer-section">
			<div id="mws-theme-presets-container" class="mws-themer-section">
				<label>{{ 'Value' }}</label><br/>
				{{ Form::text('value',((isset($searchVariable['value'])) ? $searchVariable['value'] : ''), ['class' => 'small']) }}
			</div>
		</div>
		
		<div class="mws-themer-section">
			<div id="mws-theme-presets-container" class="mws-themer-section">
				<label>{{ 'Search by module' }}</label><br/>
				{{ Form::select('module',array(''=>'Select Module')+Config::get('text_search'),((isset($searchVariable['module'])) ? $searchVariable['module'] : ''), ['class' => 'small']) }}
			</div>
		</div>
		
		<div class="mws-themer-separator"></div>
		<div class="mws-themer-section" style="height:0px">
			<ul><li class="clearfix"><span></span> <div id="mws-textglow-op"></div></li> </ul>
		</div>
		<div class="mws-themer-section">
			<input type="submit" value="Search" class="btn btn-primary btn-small">
			<a href="{{URL::to('admin/jsconstant-setting')}}"  class="btn btn-default btn-small">Reset</a>
		</div>
		{{ Form::close() }}
	</div>
</div>


<div  class="mws-panel grid_8 mws-collapsible">
	<div class="mws-panel-header">
		<span>
			<i class="icon-table"></i> {{ trans("messages.settings.table_heading_JsConstant_module_index") }} 
		</span>
		<a href="{{URL::to('admin/jsconstant-setting/add-new-text/'.$type)}}" class="btn btn-success btn-small align">{{ trans("messages.settings.add_new_JsConstant") }}</a>
	</div>
	<div class="dataTables_wrapper" style="background-color:#CCCCCC">
		<div id="DataTables_Table_0_length" class="dataTables_length">
		</div>
	</div>
	<div class="mws-panel-body no-padding dataTables_wrapper">
		<table class="mws-table mws-datatable">
			<thead>
				<tr>
					<th>{{'Language'}}</th>
					<th width="35%">
						{{
							link_to_route(
							'JsConstant.index',
							'Key',
							array(
								$type,
								'sortBy' => 'key_value',
								'order' => ($sortBy == 'key_value' && $order == 'desc') ? 'asc' : 'desc'
							),
							array('class' => (($sortBy == 'key_value' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'key_value' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
							)
						}}
					</th>
					<th width="35%">
						{{
							link_to_route(
							'JsConstant.index',
							'Value',
							array(
								$type,
								'sortBy' => 'value',
								'order' => ($sortBy == 'value' && $order == 'desc') ? 'asc' : 'desc'
							),
							array('class' => (($sortBy == 'value' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'value' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
							)
						}}
					</th>
						
					<th>{{ 'Action' }}</th>
				</tr>
			</thead>
			<tbody>
				@if(!$result->isEmpty())
					@foreach($result as $record)
						<tr>
							<td>
								@if(array_key_exists($record->language_id ,$languageArray))
									{{ $languageArray[$record->language_id] }}
								@endif
							</td>
							<td data-th='Key'>{{ $record->key_value }}</td>
							<td data-th='Value'>{{ $record->value }}</td>
							<td data-th='Action'>
								<a href="{{URL::to('admin/jsconstant-setting/edit-new-text/'.$record->id.'/'.$type)}}" class="btn btn-info btn-small ml5"  >{{ trans("messages.global.edit") }} </a>
							</td>
						</tr>
					@endforeach
					@else
						<table class="mws-table mws-datatable details">	
							<tr>
								<td align="center" width="100%" > {{ 'No Records Found' }}</td>
							</tr>	
						</table>
					@endif 
				
			</tbody>
		</table>
		
	</div>

	@include('pagination.default', ['paginator' => $result])
</div>
@stop
