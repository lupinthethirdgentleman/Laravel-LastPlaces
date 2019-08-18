@extends('admin.layouts.default')
@section('content')

{{ HTML::script('js/admin/bootstrap-datepicker.js') }}
{{ HTML::style('css/admin/datepicker _ui.css') }}

<script type="text/javascript">
	/* For delete element */
	$(function(){
		$( "#visiting_time" ).datepicker({
				changeMonth: true,
				changeYear : true,
				numberOfMonths	: 1,
		});
	});
</script>

<!-- search form start here-->
<div id="mws-themer">
	<div id="mws-themer-content" style="<?php echo ($searchVariable) ? 'right: 256px;' : ''; ?>">
		<div id="mws-themer-ribbon"></div>
		<div id="mws-themer-toggle" class="<?php echo ($searchVariable) ? 'opened' : ''; ?>">
			<i class="icon-bended-arrow-left"></i> 
			<i class="icon-bended-arrow-right"></i>
		</div>
	
		{{ Form::open(['role' => 'form','route' => "$modelName.index",'class' => 'mws-form']) }}
		{{ Form::hidden('display') }}
		<div class="mws-themer-section">
			<div id="mws-theme-presets-container" class="mws-themer-section">
				<label>{{ trans("messages.$modelName.visitor_name") }}</label><br/>
				{{ Form::text('visitor_name',((isset($searchVariable['visitor_name'])) ? $searchVariable['visitor_name'] : ''), ['class' => 'small']) }}
			</div>
		</div>
		<div class="mws-themer-separator"></div>
		<div class="mws-themer-section">
			<div id="mws-theme-presets-container" class="mws-themer-section">
				<label>{{ trans("messages.$modelName.visitor_ip_address")  }}</label><br/>
				{{ Form::text('visitor_ip',((isset($searchVariable['visitor_ip'])) ? $searchVariable['visitor_ip'] : ''), ['class' => 'small']) }}
			</div>
		</div>
		<div class="mws-themer-section">
			<div id="mws-theme-presets-container" class="mws-themer-section">
				<label>{{ trans("messages.$modelName.user_browser_device")  }}</label><br/>
				{{ Form::text('user_browser_device',((isset($searchVariable['user_browser_device'])) ? $searchVariable['user_browser_device'] : ''), ['class' => 'small']) }}
			</div>
		</div>
		<div class="mws-themer-section">
			<div id="mws-theme-presets-container" class="mws-themer-section">
				<label>{{ trans("messages.$modelName.visitng_date")  }}</label><br/>
				{{ Form::text('visit_time',((isset($searchVariable['visit_time'])) ? $searchVariable['visit_time'] : ''), ['class' => 'small','id'=>'visiting_time','readonly'=>'true']) }}
			</div>
		</div>
		<div class="mws-themer-separator"></div>
		
		<div class="mws-themer-section" style="height:0px">
			<ul><li class="clearfix"><span></span> <div id="mws-textglow-op"></div></li> </ul>
		</div>
		<div class="mws-themer-section">
			<input type="submit" value='{{ trans("messages.global.search") }}' class="btn btn-primary btn-small">
			<a href='{{ route("$modelName.index")}}'   class="btn btn-default btn-small">{{ trans("messages.global.reset")}}</a>
		</div>
		{{ Form::close() }}
	</div>
</div>
<!-- search form end here-->

<div  class="mws-panel grid_8 mws-collapsible">
	<div class="mws-panel-header">
		<span>
			<i class="icon-table"></i> {{ trans("messages.$modelName.table_heading_index") }} 
		</span>
	</div>
	<div class="dataTables_wrapper" style="background-color:#CCCCCC">
		<div id="DataTables_Table_0_length" class="dataTables_length">
			<?php //echo $this->element('paging_info'); ?>
		</div>
	</div>
	<div class="mws-panel-body no-padding dataTables_wrapper">
		<table class="mws-table mws-datatable">
			<thead>
				<tr>
					<th>
					{{
						link_to_route(
							"$modelName.index",
							trans("messages.$modelName.visitor_name"),
							array(
								'sortBy' => 'visitor_name',
								'order' => ($sortBy == 'visitor_name' && $order == 'desc') ? 'asc' : 'desc'
							),
						   array('class' => (($sortBy == 'visitor_name' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'visitor_name' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
						)
					}}
					</th>
					<th>
					{{
						link_to_route(
							"$modelName.index",
							trans("messages.$modelName.visitor_ip_address"),
							array(
								'sortBy' => 'visitor_ip',
								'order' => ($sortBy == 'visitor_ip' && $order == 'desc') ? 'asc' : 'desc'
							),
						   array('class' => (($sortBy == 'visitor_ip' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'visitor_ip' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
						)
					}}
					</th>
					<th>{{ trans("messages.$modelName.location") }}</th>
					<th>
					{{
						link_to_route(
							"$modelName.index",
							trans("messages.$modelName.location"),
							array(
								'sortBy' => 'user_browser_device',
								'order' => ($sortBy == 'user_browser_device' && $order == 'desc') ? 'asc' : 'desc'
							),
						   array('class' => (($sortBy == 'user_browser_device' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'user_browser_device' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
						)
					 }}
					</th>
					<th>
					{{
						link_to_route(
							"$modelName.index",
							trans("messages.$modelName.browser_name"),
							array(
								'sortBy' => 'browser_name',
								'order' => ($sortBy == 'browser_name' && $order == 'desc') ? 'asc' : 'desc'
							),
						   array('class' => (($sortBy == 'browser_name' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'browser_name' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
						)
					 }}
					 </th>
					<th>{{ trans("messages.$modelName.browser_name")  }}</th>
					<th>{{  trans("messages.$modelName.page_count") }}</th>
					<th>
					{{
						link_to_route(
							"$modelName.index",
							trans("messages.$modelName.visitng_date_and_time"),
							array(
								'sortBy' => 'visit_time',
								'order' => ($sortBy == 'visit_time' && $order == 'desc') ? 'asc' : 'desc'
							),
						   array('class' => (($sortBy == 'visit_time' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'visit_time' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
						)
					}}
					 </th>
					<th >{{ trans("messages.$modelName.action") }}</th>
				</tr>
			</thead>
			<tbody>
				<?php
				if(!$model->isEmpty()){
				foreach($model as $result){ ?>
				<tr>
					<td data-th='{{ trans("messages.$modelName.visitor_name")  }}'>{{ $result->visitor_name }}</td>
					<td data-th='{{ trans("messages.$modelName.visitor_ip_address") }}'>{{ long2ip($result->visitor_ip) }}</td>
					<td data-th='{{ trans("messages.$modelName.location") }}'>{{ $result->country_name }}</td>
					<td data-th='{{ trans("messages.$modelName.user_browser_device") }}'>{{ $result->user_browser_device }}</td>
					<td data-th='{{ trans("messages.$modelName.browser_name") }}'>{{ $result->browser_name }}</td>
					<td data-th='{{ trans("messages.$modelName.browser_version") }}'>{{ $result->browser_version }}</td>
					<td data-th='{{ trans("messages.$modelName.page_count") }}'>
					<?php 
						$view_pages		=	explode(',', $result->view_pages);
					?>
					<a href='{{ route("$modelName.detail","$result->id")}}#visitor_view_pages'>{{ count($view_pages) }}</a>
					</td>
					<td data-th='{{ trans("messages.$modelName.visitng_date_and_time") }}'>{{ date(Config::get("Reading.date_format") ,$result->visit_time) }}</td>
					<td data-th='{{  trans("messages.$modelName.action") }}'>
						<a href='{{ route("$modelName.detail","$result->id")}}' class="btn btn-info btn-small">{{ trans("messages.$modelName.view_details") }} </a>
					</td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
		<?php }else{ ?>
		<table class="mws-table mws-datatable details">	
			<tr>
				<td align="center" width="100%" > {{'No Records Found'}}</td>
			</tr>	
			<?php  } ?>
		</table>
	</div>
		@include('pagination.default', ['paginator' => $model])
</div>
@stop
