@extends('admin.layouts.default')

@section('content')

{{ HTML::script('js/admin/multiple_delete.js') }}

<script type="text/javascript">
	
/*  for delete testimonial */
$(function(){
	$('[data-delete]').click(function(e){
		
	     e.preventDefault();
		// If the user confirm the delete
		if (confirm('Do you really want to delete this testimonial ?')) {
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
		{{ Form::open(['method' => 'get','role' => 'form','route' => "$modelName.index",'class' => 'mws-form']) }}
		{{ Form::hidden('display') }}
		<div class="mws-themer-section">
			<div id="mws-theme-presets-container" class="mws-themer-section">
				<label>{{ trans("messages.$modelName.client_name") }}</label><br/>
				{{ Form::text('client_name',((isset($searchVariable['client_name'])) ? $searchVariable['client_name'] : ''), ['class' => 'small']) }}
			</div>
		</div>
		<div class="mws-themer-separator"></div>
		<div class="mws-themer-section" style="height:0px">
			<ul><li class="clearfix"><span></span> <div id="mws-textglow-op"></div></li> </ul>
		</div>
		<div class="mws-themer-section">
			<input type="submit" value='{{ trans("messages.global.search") }}' class="btn btn-primary btn-small">
			<a href='{{ route("$modelName.index")}}'  class="btn btn-default btn-small">{{ trans("messages.global.reset") }}</a>
		</div>
		{{ Form::close() }}
	</div>
</div>

<div  class="mws-panel grid_8 mws-collapsible">
	<div class="mws-panel-header">
		<span>
			<i class="icon-table"></i> {{ trans("messages.$modelName.table_heading_index")}}</span>
			<a href='{{URL::to('admin/testimonial-manager/add-testimonial')}}'  class="btn btn-success btn-small align">{{ trans("messages.$modelName.add_new") }} </a>
	</div>
	<div class="dataTables_wrapper" style="background-color:#CCCCCC">
		<div id="DataTables_Table_0_length" class="dataTables_length">
			
		</div>
	</div>
	<div class="mws-panel-body testimonial no-padding dataTables_wrapper">
		<table class="mws-table mws-datatable">
			<thead>
				<tr>
					<th>
						{{
							link_to_route(
								"$modelName.index",
								trans("messages.$modelName.client_name"),
								array(
									'sortBy' => 'client_name',
									'order' => ($sortBy == 'client_name' && $order == 'desc') ? 'asc' : 'desc'
								),
							   array('class' => (($sortBy == 'client_name' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'client_name' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
							)
						}}
					</th>
					<th width="40%">
						{{
							link_to_route(
								"$modelName.index",
								trans("messages.$modelName.comment"),
								array(
									'sortBy' => 'comment',
									'order' => ($sortBy == 'comment' && $order == 'desc') ? 'asc' : 'desc'
								),
							   array('class' => (($sortBy == 'comment' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'comment' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
							)
						}}
					</th>
					 <th>
						{{
							link_to_route(
								"$modelName.index",
								trans("messages.global.created"),
								array(
									'sortBy' => 'created_at',
									'order' => ($sortBy == 'created_at' && $order == 'desc') ? 'asc' : 'desc'
								),
							   array('class' => (($sortBy == 'created_at' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'created_at' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
							)
						}}
					</th>
					<th>{{ trans("messages.global.action") }}</th>
				</tr>
			</thead>
			<tbody>
				@if(!$model->isEmpty())
				@foreach($model as $result)
				<tr>
					<td data-th='{{ trans("messages.$modelName.client_name") }}'>
						{{ $result->client_name }}
					</td> 
					<td data-th='{{ trans("messages.$modelName.comment") }}'>
						@if(strlen($result->comment) < 150)
							{{ $result->comment }}
						@else
							{{ substr($result->comment,'0',150).'..'}}
						@endif
					</td>
					<td data-th='{{ trans("messages.global.created") }}'>
						{{ date(Config::get("Reading.date_format"),strtotime($result->created_at)) }}
					</td> 
					<td  data-th='{{ trans("messages.global.status") }}'>
							@if($result->status)
								<a href="{{ URL::to('admin/testimonial-manager/update-status',array($result->id,0))}}" class="btn btn-success btn-small status_user">{{ trans("messages.global.inactive")}} </a>
							@else
								<a href="{{ URL::to('admin/testimonial-manager/update-status',array($result->id,1))}}" class="btn btn-warning btn-small  status_user">{{ trans("messages.global.active")}}</a>
							@endif
						<br/>
						<a href='{{URL::to('admin/testimonial-manager/edit-testimonial/'.$result->id)}}' class="btn btn-info btn-small mt5">{{ trans("messages.global.edit")}} </a>
						
						
					</td>
				</tr>
				 @endforeach  
			</tbody>
		</table>
		@else
			<table class="mws-table mws-datatable details">
				<tr>
					<td align="center" width="100%" > {{ trans("messages.global.no_record_found_message") }}</td>
				</tr>
			</table>
		@endif
	</div>
	@include('pagination.default', ['paginator' => $model])
</div>
@stop
