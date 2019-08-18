@extends('admin.layouts.default')

@section('content')


<div id="mws-themer">
	<div id="mws-themer-content" style="<?php echo ($searchVariable) ? 'right: 256px;' : ''; ?>">
		<div id="mws-themer-ribbon"></div>
		<div id="mws-themer-toggle" class="<?php echo ($searchVariable) ? 'opened' : ''; ?>">
			<i class="icon-bended-arrow-left"></i> 
			<i class="icon-bended-arrow-right"></i>
		</div>
	
		{{ Form::open(['method' => 'get','role' => 'form','url' => 'admin/list-trip-status','class' => 'mws-form']) }}
		{{ Form::hidden('display') }}
		<div class="mws-themer-section">
			<div id="mws-theme-presets-container" class="mws-themer-section">
				<label>{{ trans("Status Name") }}</label><br/>
				{{ Form::text('status_name',((isset($searchVariable['status_name'])) ? $searchVariable['status_name'] : ''), ['class' => 'small']) }}
			</div>
			<!-- <div id="mws-theme-presets-container" class="mws-themer-section">
				<label>{{ trans("Trip Name") }}</label><br/>
				{{ Form::text('trip_id',((isset($searchVariable['trip_id'])) ? $searchVariable['trip_id'] : ''), ['class' => 'small']) }}
			</div> -->
		</div>
		<div class="mws-themer-separator"></div>
		<div class="mws-themer-section" style="height:0px">
			<ul><li class="clearfix"><span></span> <div id="mws-textglow-op"></div></li> </ul>
		</div>
		<div class="mws-themer-section">
			<input type="submit" value='{{ trans("messages.search.text") }}' class="btn btn-primary btn-small">
			<a href="{{URL::to('admin/list-trip-status')}}"  class="btn btn-default btn-small">{{ trans("messages.reset.text") }}</a>
		</div>
		{{ Form::close() }}
	</div>
</div>

<div  class="mws-panel grid_8 mws-collapsible">
	<div class="mws-panel-header">
		<span>
			<i class="icon-table"></i> {{ trans("Trip Status") }} </span>
				<a href="{{URL::to('admin/list-trip-status/add-trip-status')}}" class="btn btn-success btn-small align">{{ trans("Add New Trip Status") }} </a>
	</div>
	<div class="mws-panel-body no-padding dataTables_wrapper">
		<table class="mws-table mws-datatable">
			<thead>
				<tr>
					<th width="10%">
						{{
							link_to_route(
							"TripStatus.index",
							trans("status_name"),
							array(
								'sortBy' => 'status_name',
								'order' => ($sortBy == 'status_name' && $order == 'desc') ? 'asc' : 'desc'
							),
							array('class' => (($sortBy == 'status_name' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'status_name' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
							)
						}}
					</th>
					<th width="5%">{{ trans("messages.system_management.created_at") }}</th>
					<th width="5%">{{ trans("messages.system_management.status") }}</th>
					<th width="15%">{{ trans("messages.system_management.action") }}</th>
				</tr>
			</thead>
			<tbody id="powerwidgets">
				@if(!$result->isEmpty())
				@foreach($result as $record)
				<tr class="items-inner">
					<td data-th='Description'>{{ $record->status_name }}</td>
					<td data-th='Created'>{{ date(Config::get("Reading.date_format"),strtotime($record->created_at)) }}</td>
					<td data-th='status'>
						@if($record->active == 1)
							<span class="label label-success">Active</span>
						@else
							<span class="label label-warning">Inactive</span>
						@endif
					</td>
					<td data-th='{{ trans("messages.system_management.action") }}'>
						@if($record->active == 1)
							<a href="{{URL::to('admin/list-trip-status/update-status/'.$record->id)}}" class="btn btn-danger btn-small change_status">Mark inactive</a>
						@else
							<a href="{{URL::to('admin/list-trip-status/update-status/'.$record->id)}}" class="btn btn-success btn-small change_status">Mark active</a>
						@endif
						<!-- <a href="{{URL::to('admin/list-hcp/update-status/'.$record->id)}}" class="btn btn-warning btn-small change_status">Change Status</a> -->
						<a href="{{URL::to('admin/list-trip-status/edit-trip-status/'.$record->id)}}" class="btn btn-info btn-small">{{ trans("messages.system_management.edit") }} </a>
						<!-- <a href="{{URL::to('admin/list-trip-status/view-trip-status/'.$record->id)}}" class="btn btn-info btn-small">{{ trans("messages.system_management.view") }} </a> -->
					</td>
				</tr>
				 @endforeach
				 @else
					<table class="mws-table mws-datatable details">	
						<tr>
							<td align="center" width="100%"> {{ trans("messages.system_management.no_record_found_message") }}</td>
						</tr>	
					</table>  
				@endif 
			</tbody>
		</table>
	</div>
	@include('pagination.default', ['paginator' => $result])
</div>


{{ HTML::script('js/admin/lightbox.js') }}
<!-- js for equal height of the div  -->
{{ HTML::script('js/admin/jquery.matchHeight-min.js') }}

<script type="text/javascript">
	
var action_url = '<?php echo WEBSITE_URL; ?>admin/list-hcf/multiple-action';
 /* for equal height of the div */	
 
$(function() {
	$('.eqaul-height').matchHeight();
});
$('.change_status').click(function(e){
	e.stopImmediatePropagation();
	var url = $(this).attr('href');
	bootbox.confirm("Do you want to changen the status ?",
	function(result){
		if(result){
			window.location.href=url;
		}
	});
	e.preventDefault();
});

 </script>

{{ HTML::script('js/admin/multiple_delete.js') }}


@stop
