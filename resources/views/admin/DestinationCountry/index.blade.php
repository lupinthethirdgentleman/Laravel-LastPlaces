@extends('admin.layouts.default')

@section('content')


<div id="mws-themer">
	<div id="mws-themer-content" style="<?php echo ($searchVariable) ? 'right: 256px;' : ''; ?>">
		<div id="mws-themer-ribbon"></div>
		<div id="mws-themer-toggle" class="<?php echo ($searchVariable) ? 'opened' : ''; ?>">
			<i class="icon-bended-arrow-left"></i> 
			<i class="icon-bended-arrow-right"></i>
		</div>
	
		{{ Form::open(['method' => 'get','role' => 'form','url' => 'admin/list-destination-country','class' => 'mws-form']) }}
		{{ Form::hidden('display') }}
		<div class="mws-themer-section">
			<div id="mws-theme-presets-container" class="mws-themer-section">
				<label>{{ trans("Name") }}</label><br/>
				{{ Form::text('name',((isset($searchVariable['name'])) ? $searchVariable['name'] : ''), ['class' => 'small']) }}
			</div>
			<div id="mws-theme-presets-container" class="mws-themer-section">
				<label>{{ trans("Heading") }}</label><br/>
				{{ Form::text('heading',((isset($searchVariable['heading'])) ? $searchVariable['heading'] : ''), ['class' => 'small']) }}
			</div>
		</div>
		<div class="mws-themer-separator"></div>
		<div class="mws-themer-section" style="height:0px">
			<ul><li class="clearfix"><span></span> <div id="mws-textglow-op"></div></li> </ul>
		</div>
		<div class="mws-themer-section">
			<input type="submit" value='{{ trans("messages.search.text") }}' class="btn btn-primary btn-small">
			<a href="{{URL::to('admin/list-destination-country')}}"  class="btn btn-default btn-small">{{ trans("messages.reset.text") }}</a>
		</div>
		{{ Form::close() }}
	</div>
</div>

<div  class="mws-panel grid_8 mws-collapsible">
	<div class="mws-panel-header">
		<span>
			<i class="icon-table"></i> {{ trans("Destination Country") }} </span>
				<a href="{{URL::to('admin/list-destination-country/add-destination-country')}}" class="btn btn-success btn-small align">{{ trans("Add New Destination Country") }} </a>
	</div>
	<div class="mws-panel-body no-padding dataTables_wrapper">
		<table class="mws-table mws-datatable">
			<thead>
				<tr>
					<th width="10%">
						{{
							link_to_route(
							"DestinationCountry.index",
							trans("Name"),
							array(
								'sortBy' => 'name',
								'order' => ($sortBy == 'first_name' && $order == 'desc') ? 'asc' : 'desc'
							),
							array('class' => (($sortBy == 'name' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'name' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
							)
						}}
					</th>
					<th width="10%">
						{{
							link_to_route(
							"DestinationCountry.index",
							trans("Heading"),
							array(
								'sortBy' => 'heading',
								'order' => ($sortBy == 'heading' && $order == 'desc') ? 'asc' : 'desc'
							),
							array('class' => (($sortBy == 'heading' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'heading' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
							)
						}}
					</th>
					<th width="10%">{{ trans("Description") }}</th>
					<th width="10%">
						{{
							link_to_route(
								"DestinationCountry.index",
								trans("messages.global.created"),
								array(
									'sortBy' => 'created_at',
									'order' => ($sortBy == 'created_at' && $order == 'desc') ? 'asc' : 'desc'
								),
							   array('class' => (($sortBy == 'created_at' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'created_at' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
							)
						}}
					</th>
					<th width="5%">{{ trans("messages.system_management.status") }}</th>
					<th width="15%">{{ trans("messages.system_management.action") }}</th>
				</tr>
			</thead>
			<tbody id="powerwidgets">
				@if(!$result->isEmpty())
				@foreach($result as $record)
				<tr class="items-inner">
					<td data-th='Name'>{{ $record->name }}</td>
					<td data-th='Heading'>{{ $record->heading }}</td>
					<td data-th='Description'>{{ strip_tags(Str::limit($record->description, 300)) }}</td>
					<td data-th='Created'>{{ date(Config::get("Reading.date_format"),strtotime($record->created_at)) }}</td>
					<td data-th='status'>
						@if($record->active == 1)
							<span class="label label-success">Active</span>
						@else
							<span class="label label-warning">Inactive</span>
						@endif
					</td>
					<td data-th='{{ trans("messages.system_management.action") }}'>
						@if($record->is_highlight == 0)
							<a href='{{route("DestinationCountry.highlight",array($record->id))}}'  class="btn btn-warning btn-small">Mark as highlight</a>
						@else
							<a href='{{route("DestinationCountry.highlight",array($record->id))}}'  class="btn btn-danger btn-small">Remove as highlight</a>
						@endif
						@if($record->active == 1)
							<a href="{{URL::to('admin/list-destination-country/update-status/'.$record->id)}}" class="btn btn-danger btn-small change_status">Mark inactive</a>
						@else
							<a href="{{URL::to('admin/list-destination-country/update-status/'.$record->id)}}" class="btn btn-success btn-small change_status">Mark active</a>
						@endif
						<!-- <a href="{{URL::to('admin/list-hcp/update-status/'.$record->id)}}" class="btn btn-warning btn-small change_status">Change Status</a> -->
						<a href="{{URL::to('admin/list-destination-country/edit-destination-country/'.$record->id)}}" class="btn btn-info btn-small">{{ trans("messages.system_management.edit") }} </a>
						<!-- <a href="{{URL::to('admin/list-destination-country/view-destination-country/'.$record->id)}}" class="btn btn-info btn-small">{{ trans("messages.system_management.view") }} </a> -->
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
