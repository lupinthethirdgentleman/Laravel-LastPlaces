@extends('admin.layouts.default')

@section('content')

{{ HTML::script('js/admin/bootstrap-modal.min.js') }}
{{ HTML::style('css/admin/bootmodel.css') }}

<!--pop js start here-->
<script type="text/javascript">
	/* For open payment detail popup */
	function getPopupClient(id){
		$.ajax({
			url: '<?php echo URL::to('admin/payment-manager/pricing_details')?>/'+id,
			type: "POST",
			success : function(r){
				$("#getting_basic_list_popover").html(r);
				$("#getting_basic_list_popover").modal('show');
			}
		});
	}
</script>
<!--pop js end here-->

<!--pop div start here-->
<div aria-hidden="false" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="getting_basic_list_popover" class="modal fade in" style="display: none;">
</div>
<!-- popup div end here-->

<div id="mws-themer">
	<div id="mws-themer-content" style="<?php echo ($searchVariable) ? 'right: 256px;' : ''; ?>">
		<div id="mws-themer-ribbon"></div>
		<div id="mws-themer-toggle" class="<?php echo ($searchVariable) ? 'opened' : '';  ?>">
			<i class="icon-bended-arrow-left"></i> 
			<i class="icon-bended-arrow-right"></i>
		</div>
		
		{{ Form::open(['method' => 'get','role' => 'form','url' => 'admin/payment-manager','class' => 'mws-form']) }}
		{{ Form::hidden('display') }}
		<div class="mws-themer-section">
		

			
			{{  Form::label('user_id', 'Email', ['class' => 'mws-form-label']) }}
			<div id="mws-theme-presets-container" class="mws-themer-section">
				{{ Form::text(
						'email',
						((isset($searchVariable['user_id'])) ? $searchVariable['user_id'] : ''),
						['class' => 'large input-medium','id'=>'user_id'])
				}}
			</div>
			
			{{  Form::label('from', 'Payment Date', ['class' => 'mws-form-label']) }}
			<div id="mws-theme-presets-container" class="mws-themer-section">
				{{  Form::label('from', 'From', ['class' => 'mws-form-label']) }}
				{{ Form::text(
						'from',
						((isset($searchVariable['from'])) ? $searchVariable['from'] : ''),
						['class' => 'small input-medium','id'=>'schedule_start','readonly'=>'true'])
				}}
				<div class="error-message help-inline">
					{{ $errors->first('from') }}
				</div>
				{{  Form::label('from', 'To', ['class' => 'mws-form-label']) }}
				{{ Form::text(
						'to',
						((isset($searchVariable['to'])) ? $searchVariable['to'] : ''),
						['class' => 'small input-medium','id'=>'schedule_end','readonly'=>'true'])
				}}
				<div class="error-message help-inline">
					{{ $errors->first('to') }}
				</div>
			</div>
			
		</div>
		<div class="mws-themer-separator"></div>
		<div class="mws-themer-section" style="height:0px">
			<ul><li class="clearfix"><span></span> <div id="mws-textglow-op"></div></li> </ul>
		</div>
		<div class="mws-themer-section">
			<input type="submit" value="Search" class="btn btn-primary btn-small">
			<a href="{{URL::to('admin/payment-manager')}}"  class="btn btn-default btn-small">Reset</a>
		</div>
		{{ Form::close() }}
	</div>
</div>

<div  class="mws-panel grid_8 mws-collapsible">
	<div class="mws-panel-header">
		<span>
			<i class="icon-table"></i> {{ 'Payment Manager' }} </span>
	</div>
	<div class="dataTables_wrapper" style="background-color:#CCCCCC">
		<div id="DataTables_Table_0_length" class="dataTables_length">
		</div>
	</div>
	<div class="mws-panel-body no-padding dataTables_wrapper">
		<table class="mws-table mws-datatable">
			<thead>
				<tr>
					<th >{{'Trip Name'}}</th>
					<th >{{'User Name'}}</th>
					<th >{{'User Email'}}</th>
					<th >{{'Booking Type'}}</th>
					<th width='12%' >{{ 'Transaction Id' }}</th>
					<th >{{'Payment Status'}}</th>
					<th width='20%'>{{
							link_to_action(
								'UsersPaymentController@listPayment',
								'Payment Date',
								array(
									'sortBy' => 'created_at',
									'order' => ($sortBy == 'created_at' && $order == 'desc') ? 'asc' : 'desc'
								),
							   array('class' => (($sortBy == 'created_at' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'created_at' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
							)
						}} 
					</th>
					
					<th width='15%'> {{ 'Action' }}</th>
				</tr>
			</thead>
			<tbody>
			@if(!$results->isEmpty())
				@foreach($results as $data)
				<tr>

					<td>{{ isset($data->trip_name) ? $data->trip_name :''  }}</td>
					<td>{{ isset($data->name) ? $data->name :''  }}</td>
					<td>{{ isset($data->email) ? $data->email :''  }}</td>
					<td>@if($data->booking_type == 1)
							{{ 'Package Booking' }}
						@else
							{{ 'Tailored Booking' }}
						@endif	
					</td>
					<td>
						{{ isset($data->transaction_id) ? $data->transaction_id :''  }}
					</td>
					<td>{{ isset($data->ack) ? $data->ack :''  }}</td>
	

					<td>{{ date(Config::get("Reading.date_format"),strtotime($data->created_at)) }}</td>
					<td><span class="btn btn-warning btn-small" onclick="getPopupClient({{ $data->id }})">{{ 'View detail' }} </span></td>
				
				</tr>
				@endforeach
				@else
					<table class="mws-table mws-datatable">	
						<tr>
							<td align="center" width="100%"> {{ 'No Records Found' }}</td>
						</tr>	
					</table>
				@endif 
			</tbody>
		</table>
	</div>
		@include('pagination.default', ['paginator' => $results,'searchVariable'=>$searchVariable])
</div>
<!-- Datepicker js and css use  for datepicker-->
{{ HTML::script('js/admin/bootstrap-datepicker.js') }}
{{ HTML::style('css/admin/datepicker _ui.css') }}

<script>
/* for datepicker used in searching */
$( "#schedule_start" ).datepicker({
		maxDate	  : '-0D',
		dateFormat: 'yy-mm-dd',
		changeMonth: true,
		changeYear : true,
		numberOfMonths	: 1,
		onClose: function( selectedDate ) {
			$( "#schedule_end" ).datepicker( "option", "minDate", selectedDate );
		}
	});
   $( "#schedule_end" ).datepicker({
		maxDate	  : '-0D',
		dateFormat: 'yy-mm-dd',
		changeMonth: true,
		changeYear : true,
		numberOfMonths: 1,
		onClose: function( selectedDate ) {
			$( "#schedule_start" ).datepicker( "option", "maxDate", selectedDate );
		}
	});
</script>
@stop
