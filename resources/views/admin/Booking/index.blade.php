@extends('admin.layouts.default')

@section('content')

{{ HTML::script('js/admin/multiple_delete.js') }}
{{ HTML::style('css/admin/lightbox.css') }}

<div id="mws-themer">
	<div id="mws-themer-content" style="<?php echo ($searchVariable) ? 'right: 256px;' : ''; ?>">
		<div id="mws-themer-ribbon"></div>
		<div id="mws-themer-toggle" class="<?php echo ($searchVariable) ? 'opened' : ''; ?>">
			<i class="icon-bended-arrow-left"></i> 
			<i class="icon-bended-arrow-right"></i>
		</div>
	
		{{ Form::open(['role' => 'form','url' => 'admin/booking-manager','class' => 'mws-form']) }}
		{{ Form::hidden('display') }}
		<div class="mws-themer-section">
			<div id="mws-theme-presets-container" class="mws-themer-section">
				<label>{{ 'Booking No.' }}</label><br/>
				{{ Form::text('booking_no',((isset($searchVariable['booking_no'])) ? $searchVariable['booking_no'] : ''), ['class' => 'small']) }}
			</div>
		</div>
		<div class="mws-themer-separator"></div>
		<div class="mws-themer-section" style="height:0px">
			<ul><li class="clearfix"><span></span> <div id="mws-textglow-op"></div></li> </ul>
		</div>
		<div class="mws-themer-section">
			<input type="submit" value="Search" class="btn btn-primary btn-small">
			<a href="{{URL::to('admin/booking-manager')}}"  class="btn btn-default btn-small">{{ trans("messages.global.reset") }}</a>
		</div>
		{{ Form::close() }}
	</div>
</div>

<div  class="mws-panel grid_8 mws-collapsible">
	<div class="mws-panel-header">
		<span>
			<i class="icon-table"></i> {{ trans("messages.booking.table_heading_booking_module_index") }}  </span>
			
		
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
							'Booking.index',
							'Booking No.',
							array(
								'sortBy' => 'booking_no',
								'order' => ($sortBy == 'booking_no' && $order == 'desc') ? 'asc' : 'desc'
							),
						   array('class' => (($sortBy == 'booking_no' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'booking_no' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
						)
					}}</th>
					
					<th>
						User Name
					</th>

					<th>
						Event Name
					</th>

					<th>
						{{
							link_to_route(
								'Booking.index',
								'Seats Booked',
								array(
									'sortBy' => 'seats',
									'order' => ($sortBy == 'seats' && $order == 'desc') ? 'asc' : 'desc'
								),
							   array('class' => (($sortBy == 'seats' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'seats' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
							)
						}}
					</th>

					<th>
						{{
							link_to_route(
								'Booking.index',
								'Price',
								array(
									'sortBy' => 'price',
									'order' => ($sortBy == 'price' && $order == 'desc') ? 'asc' : 'desc'
								),
							   array('class' => (($sortBy == 'price' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'price' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
							)
						}}
					</th>
					<th>
						{{
							link_to_route(
								'Booking.index',
								'Booking Date',
								array(
									'sortBy' => 'created_at',
									'order' => ($sortBy == 'created_at' && $order == 'desc') ? 'asc' : 'desc'
								),
							   array('class' => (($sortBy == 'created_at' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'created_at' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
							)
						}}
					</th>
					
					<th>{{ 'Status' }}</th>
					

					
				</tr>
			</thead>
			<tbody>
				<?php

				if(!$result->isEmpty()){
				foreach($result as $record){?>
				<tr>
					<td data-th='Booking No.'>{{ $record->booking_no }}</td>
					
					<td data-th='User Name'>{{ $record->user->full_name }}</td>

					<td data-th='Ceremony'>{{ $record->ceremony->name ? $record->ceremony->name : "" }}</td>

					<td data-th='Seats Booked'>{{ $record->seats }}</td>

					<td data-th='Price'>{{ Config::get("Site.currency") . $record->price }}</td>

					<td data-th='Booking Date'>{{ date('d-M-Y',strtotime($record->created_at)) }}</td>
				
					<td data-th="Status">
						@if($record->status)
							<span class="label label-success">Successful</span>
						@else
							<span class="label label-warning">Failed</span>
						@endif			
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
		@include('pagination.default', ['paginator' => $result])
</div>

{{ HTML::script('js/admin/lightbox.js') }}

@stop
