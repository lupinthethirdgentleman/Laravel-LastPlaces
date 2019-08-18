@extends('admin.layouts.default')

@section('content')


<script type="text/javascript">
var action_url = '<?php echo WEBSITE_URL; ?>admin/news-letter/delete-multiple-subscriber';
 /* for equal height of the div */	
</script>

{{ HTML::script('js/admin/multiple_delete.js') }}

<div id="mws-themer">
	<div id="mws-themer-content" style="<?php echo ($searchVariable) ? 'right: 256px;' : ''; ?>">
		<div id="mws-themer-ribbon"></div>
		<div id="mws-themer-toggle" class="<?php echo ($searchVariable) ? 'opened' : ''; ?>">
			<i class="icon-bended-arrow-left"></i> 
			<i class="icon-bended-arrow-right"></i>
		</div>
	
		{{ Form::open(['method' => 'get','role' => 'form','url' => 'admin/news-letter/subscriber-list','class' => 'mws-form']) }}
		{{ Form::hidden('display') }}
		<div class="mws-themer-section">
			<div id="mws-theme-presets-container" class="mws-themer-section">
				<label>{{ trans("messages.system_management.email") }}</label><br/>
				{{ Form::text('email',((isset($searchVariable['email'])) ? $searchVariable['email'] : ''), ['class' => 'small']) }}
			</div>
		</div>
		<div class="mws-themer-separator"></div>
		<div class="mws-themer-section" style="height:0px">
			<ul><li class="clearfix"><span></span> <div id="mws-textglow-op"></div></li> </ul>
		</div>
		<div class="mws-themer-section">
			<input type="submit" value="{{ trans('messages.system_management.search') }}" class="btn btn-primary btn-small">
			<a href="{{URL::to('admin/news-letter/subscriber-list')}}"  class="btn btn-default btn-small">{{ trans('messages.system_management.reset') }}</a>
		</div>
		{{ Form::close() }}
	</div>
</div>

<div  class="mws-panel grid_8 mws-collapsible">
	<div class="mws-panel-header">
		<span><i class="icon-table"></i> {{ trans("messages.system_management.newsletter_subscribers") }} </span>
			<a href="{{URL::to('admin/news-letter/newsletter-templates')}}" class="btn btn-success btn-small align">{{ trans("messages.system_management.back") }} </a>
			<a href="{{URL::to('admin/news-letter/add-subscriber')}}"  style=" margin-right: 10px !important;" class="btn btn-success btn-small align">{{ trans("messages.system_management.add_subscriber")
 }} </a> &nbsp;
	</div>
		<div class="dataTables_wrapper" style="background-color:#CCCCCC">
		<div id="DataTables_Table_0_length" class="dataTables_length">
			<?php 
			$actionTypes	= array(
					'delete' 		=> trans('messages.global.delete_all'),
					'inactive' 		=> trans('messages.global.mark_as_inactive'),
					'active' 		=> trans('messages.global.mark_as_active'),
				 );
			?>
			{{ Form::open() }}
				{{ Form::checkbox('is_checked','',null,['class'=>'checkAllUser'])}}
				{{ Form::select('action_type',array(''=>trans("messages.user_management.select_action"))+$actionTypes,$actionTypes,['class'=>'deleteall selectUserAction'])}}
			{{ Form::close() }}
		</div>
	</div>
	<div class="mws-panel-body no-padding dataTables_wrapper">
		<table class="mws-table mws-datatable">
			<thead>
				<tr>
					<th></th>
					<th width="40%">
						{{
							link_to_route(
							'Subscriber.subscriberList',
							trans("messages.system_management.email"),
							array(
								'sortBy' => 'email',
								'order' => ($sortBy == 'email' && $order == 'desc') ? 'asc' : 'desc'
							),
							array('class' => (($sortBy == 'email' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'email' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
							)
						}}
					</th>
					<th width="20%">
						{{
							link_to_route(
							'Subscriber.subscriberList',
							trans("messages.system_management.created"),
							array(
								'sortBy' => 'created_at',
								'order' => ($sortBy == 'created_at' && $order == 'desc') ? 'asc' : 'desc'
							),
							array('class' => (($sortBy == 'created_at' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'created_at' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
							)
						}}
					</th>
					<th>{{ trans("messages.system_management.action") }}</th>
				</tr>
			</thead>
			<tbody  id="powerwidgets">
				@if(!$result->isEmpty())
				@foreach($result as $record)
				<tr class="items-inner">
					<td data-th='{{ trans("messages.system_management.select") }}'>
					{{ Form::checkbox('status',$record->id,null,['class'=> 'userCheckBox'] )}}
					</td>
					<td data-th='{{ trans("messages.system_management.email") }}'>{{ $record->email }}</td>
					<td data-th='{{ trans("messages.system_management.created") }}'>{{ date(Config::get("Reading.date_format"),strtotime($record->created_at)) }}</td>
					<td data-th='{{ trans("messages.system_management.action") }}'>
						@if($record->status)
							<a href="{{URL::to('admin/news-letter/subscriber-active/'.$record->id.'/0')}}" class="btn btn-warning btn-small status_user">{{ trans("messages.system_management.inactivate")
 }} </a>
						@else
							<a href="{{URL::to('admin/news-letter/subscriber-active/'.$record->id.'/1')}}" class="btn btn-success btn-small status_user">{{ trans("messages.system_management.activate") }} </a>
						@endif
						
						<a href="{{URL::to('admin/news-letter/subscriber-delete/'.$record->id)}}"  class="btn btn-danger btn-small delete_user no-ajax">{{ trans("messages.system_management.delete")
}} </a>
					</td>
				</tr>
				 @endforeach  
			</tbody>
		</table>
		@else
		<table class="mws-table mws-datatable details">	
			<tr>
				<td align="center" width="100%" > {{ 'No Records Found' }}</td>
			</tr>	
			@endif 
		</table>
	</div>
@include('pagination.default', ['paginator' => $result])
	
</div>
@stop
