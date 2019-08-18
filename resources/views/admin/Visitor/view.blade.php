@extends('admin.layouts.default')

@section('content')

<div  class="mws-panel grid_8 mws-collapsible">
	<div class="mws-panel-header">
		<span>
			<i class="icon-table"></i> {{ trans("messages.$modelName.visitor_history") }} 
		</span>
		<a href="{{URL::to('admin/visitors')}}" class="btn btn-success btn-small align">{{ trans("messages.visitors.go_back") }}  </a>
	</div>
	
	<div class="mws-panel-body no-padding dataTables_wrapper">
		<table class="mws-table mws-datatable">
			<tbody>
				<tr>
					<th width="20%">{{ trans("messages.$modelName.visitor_type") }} </th>
					<td data-th='{{ trans("messages.visitors.visitor_type") }} '>{{ $visitorDetail->visitor_type }}</td>
				</tr>
				<tr>
					<th width="20%">{{ trans("messages.$modelName.visitor_name") }} </th>
					<td data-th='{{ trans("messages.visitors.visitor_name") }}'>{{ $visitorDetail->visitor_name }}</td>
				</tr>
				<tr>
					<th width="20%">{{ trans("messages.$modelName.visitor_ip_address") }}</th>
					<td data-th='{{ trans("messages.visitors.visitor_ip_address") }}'>{{ long2ip($visitorDetail->visitor_ip) }}</td>
				</tr>
				<tr>
					<th width="20%" valign="top">{{ trans("messages.$modelName.browser_name") }}</th>
					<td data-th='{{ trans("messages.visitors.browser_name") }}'>{{ $visitorDetail->browser_name }}</td>
				</tr>
				<tr>
					<th width="20%" valign="top">{{ trans("messages.$modelName.browser_version") }}</th>
					<td data-th='{{ trans("messages.visitors.browser_version") }}'>{{ $visitorDetail->browser_version }}</td>
				</tr>
				<tr>
					<th width="20%">{{ trans("messages.$modelName.visitng_date_and_time") }}</th>
					<td data-th='{{ trans("messages.visitors.visitng_date_and_time") }}'>{{ date(Config::get("Reading.date_format") , $visitorDetail->visit_time) }}</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>


<div  class="mws-panel grid_8 mws-collapsible">
	<div class="mws-panel-header">
		<span>
			<i class="icon-table"></i> {{ trans("messages.$modelName.visit_pages") }} 
		</span>
	</div>
	<div class="mws-panel-body no-padding dataTables_wrapper" id="visitor_view_pages">
		<table class="mws-table mws-datatable">
			<thead>
				<tr>
					<th width="50%">{{ trans("messages.$modelName.page_url") }} </th>
					<th width="50%">{{ trans("messages.$modelName.visitng_date_and_time") }} </th>
				</tr>
			</thead>
			
			<tbody>
			<?php
				$view_pages		=	explode(',', $visitorDetail->view_pages);
			?>
			<?php if(!empty($view_pages)) {
				foreach($view_pages as $page) { 
				$pageArray	=	explode('=', $page);
			?>
				<tr>
					<td data-th='{{ trans("messages.visitors.page_url") }}'>	
						<?php echo $pageArray[0]; ?>
					</td>
					<td data-th='{{ trans("messages.visitors.visitng_date_and_time") }}'>
						{{ date(Config::get("Reading.date_format") ,$pageArray[1]) }}
					</td>
				<tr>
			<?php }
				}else{ ?>
				<tr><td colspan="2" align="center"><?php echo __('visitors.message_no_record'); ?></td><tr>
			<?php } ?>
			</tbody>
		</table>
	</div>
</div>
@stop
