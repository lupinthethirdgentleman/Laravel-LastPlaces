@extends('admin.layouts.default')

@section('content')
{{ HTML::script('js/admin/bootstrap-modal.min.js') }}
{{ HTML::style('css/admin/bootmodel.css') }}

<!--pop js start here-->
<script type="text/javascript">
	/* For open Email detail popup */
	
	function getPopupClient(id){
		$.ajax({
			url: '<?php echo URL::to('admin/email-logs/email_details')?>/'+id,
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
	<div id="mws-themer-content" style="<?php   echo ($searchVariable) ? 'right: 256px;' : '';  ?>">
		<div id="mws-themer-ribbon"></div>
		<div id="mws-themer-toggle" class="<?php echo ($searchVariable) ? 'opened' : '';   ?>">
			<i class="icon-bended-arrow-left"></i> 
			<i class="icon-bended-arrow-right"></i>
		</div>
		
		{{ Form::open(['method' => 'get','role' => 'form','url' => 'admin/email-logs','class' => 'mws-form']) }}
		{{ Form::hidden('display') }}
		<?php
			$email_to	=	Input::get('email_to'); 
			$subject	=	Input::get('subject'); 
		?>
		<div class="mws-themer-section">
		
			{{  Form::label('email_to', trans('messages.system_management.email_to') , ['class' => 'mws-form-label']) }}
			<div id="mws-theme-presets-container" class="mws-themer-section">
						{{ Form::text(
								'email_to', 
								 isset($email_to) ? Input::get('email_to') :'', 
								 ['class' =>'form-control','id'=>'country']) 
						}}
			</div>
			{{  Form::label('subject', trans('messages.system_management.subject'), ['class' => 'mws-form-label']) }}
			<div id="mws-theme-presets-container" class="mws-themer-section">
						{{ Form::text(
								'subject', 
								 isset($subject) ? $subject :'', 
								 ['class' =>'form-control','id'=>'country']) 
						}}
			</div>
		</div>
		<div class="mws-themer-separator"></div>
		<div class="mws-themer-section" style="height:0px">
			<ul><li class="clearfix"><span></span> <div id="mws-textglow-op"></div></li> </ul>
		</div>
		<div class="mws-themer-section">
			<input type="submit" value="{{ trans('messages.system_management.search') }}" class="btn btn-primary btn-small">
			<a href="{{URL::to('admin/email-logs')}}"  class="btn btn-default btn-small">{{ trans('messages.system_management.reset') }}</a>
		</div>
		{{ Form::close() }}
	</div>
</div>


<div  class="mws-panel grid_8 mws-collapsible">
	<div class="mws-panel-header">
		<span>
			<i class="icon-table"></i> {{ trans("messages.system_management.email_logs") }} 
		</span>
	</div>
	<div class="dataTables_wrapper" style="background-color:#CCCCCC">
		<div id="DataTables_Table_0_length" class="dataTables_length">
		</div>
	</div>
	<div class="mws-panel-body no-padding dataTables_wrapper">
		<table class="mws-table mws-datatable">
			<thead>
				<tr>
					<th>{{ trans('messages.system_management.email_to') }}</th>
					<th width='25%'>{{ trans('messages.system_management.email_from') }}</th>
					<th  width='20%'>{{ trans('messages.system_management.subject') }}</th>
					<th>{{
							link_to_route(
								'EmailLogs.listEmail',
								'Mail Sent On',
								array(
									'sortBy' => 'created_at',
									'order' => ($sortBy == 'created_at' && $order == 'desc') ? 'asc' : 'desc'
								),
							   array('class' => (($sortBy == 'created_at' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'created_at' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
							)
						}} 
					</th>
					<th> {{ trans('messages.system_management.action') }}</th>
				</tr>
			</thead>
			<tbody>
			@if(!$result->isEmpty())
				@foreach($result as $data)
				<tr>
					<td data-th="{{ trans('messages.system_management.email_to') }}">{{ $data->email_to }}</td>
					<td data-th="{{ trans('messages.system_management.email_from') }}">{{ $data->email_from }}</td>
					<td data-th="{{ trans('messages.system_management.subject') }}">{{ $data->subject}}</td>
					<td data-th="{{ trans('messages.system_management.created') }}">{{ date(Config::get("Reading.date_format"),strtotime($data->created_at)) }}</td>
					<td data-th="{{ trans('messages.system_management.action') }}"><span class="btn btn-warning btn-small" onclick="getPopupClient({{ $data->id }})">{{ trans("messages.system_management.view_email_logs") }} </span></td>
				</tr>
				@endforeach
			@else
				<table class="mws-table mws-datatable details">	
					<tr>
						<td align="center" width="100%"> {{ trans("messages.system_management.no_records_found") }}</td>
					</tr>	
				</table>  
			@endif 
			</tbody>
		</table>
	</div>
		@include('pagination.default', ['paginator' => $result])
</div>
@stop
