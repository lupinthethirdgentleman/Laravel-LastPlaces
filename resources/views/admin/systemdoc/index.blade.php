@extends('admin.layouts.default')

@section('content')

<script type="text/javascript">
 var action_url = '<?php echo WEBSITE_URL; ?>admin/system-doc-manager/multiple-action';
 </script>
{{ HTML::script('js/admin/multiple_delete.js') }}

<div id="mws-themer">
	<div id="mws-themer-content" style="<?php echo ($searchVariable) ? 'right: 256px;' : ''; ?>">
		<div id="mws-themer-ribbon"></div>
		<div id="mws-themer-toggle" class="<?php echo ($searchVariable) ? 'opened' : ''; ?>">
			<i class="icon-bended-arrow-left"></i> 
			<i class="icon-bended-arrow-right"></i>
		</div>
	
		{{ Form::open(['method' => 'get','role' => 'form','url' => 'admin/system-doc-manager','class' => 'mws-form']) }}
		{{ Form::hidden('display') }}
		
		<div class="mws-themer-section">
			<div id="mws-theme-presets-container" class="mws-themer-section">
				<label>{{ trans("messages.system_management.title") }}</label><br/>
				{{ Form::text('title',((isset($searchVariable['title'])) ? $searchVariable['title'] : ''), ['class' => 'small']) }}
			</div>
		</div>
		
		<div class="mws-themer-separator"></div>
		<div class="mws-themer-section" style="height:0px">
			<ul><li class="clearfix"><span></span> <div id="mws-textglow-op"></div></li> </ul>
		</div>
		<div class="mws-themer-section">
			<input type="submit" value="{{ trans('messages.search.text') }}" class="btn btn-primary btn-small">
			<a href="{{URL::to('admin/system-doc-manager')}}"  class="btn btn-default btn-small">{{ trans('messages.reset.text') }}</a>
		</div>
		{{ Form::close() }}
	</div>
</div>

<div  class="mws-panel grid_8 mws-collapsible">
	<div class="mws-panel-header">
		<span>
			<i class="icon-table"></i> {{ trans("messages.system_management.docs") }} </span>
			@if(Config::get('app.debug'))
			<a href="{{URL::to('admin/system-doc-manager/add-doc')}}" class="btn btn-success btn-small align">{{ trans("messages.system_management.add_docs") }} </a>
			@endif
	</div>
	<div class="dataTables_wrapper" style="background-color:#CCCCCC">
		<div id="DataTables_Table_0_length" class="dataTables_length">
			<?php 
			$actionTypes	= array(
					'delete' 		=> trans("messages.global.delete_all"),
				 );
			?>
			{{ Form::open() }}
				{{ Form::checkbox('is_checked','',null,['class'=>'left checkAllUser'])}}
				{{ Form::select('action_type',array(''=>trans("messages.user_management.select_action"))+$actionTypes,$actionTypes,['class'=>'deleteall selectUserAction'])}}
			{{ Form::close() }}
		</div>
	</div>
	<div class="mws-panel-body no-padding dataTables_wrapper">
		<table class="mws-table mws-datatable">
			<thead>
				<tr>
					<th></th>
					<th width="20%">
						{{
							link_to_route(
							'SystemDoc.index',
							trans('Name'),
							array(
							'sortBy' => 'title',
							'order' => ($sortBy == 'title' && $order == 'desc') ? 'asc' : 'desc'
							),
							array('class' => (($sortBy == 'title' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'title' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
							)
						}}
					</th>
					<th width="50%">
						{{ trans("messages.system_management.url") }}
					</th>
					<th width="30%">{{ trans("messages.system_management.action") }}</th>
				</tr>
			</thead>
			<tbody id="powerwidgets">
				@if(!$result->isEmpty())
					@foreach($result as $record)
					<tr class="items-inner">
						<td data-th='{{ trans("messages.system_management.select") }}'>
							{{ Form::checkbox('status',$record->id,null,['class'=> 'userCheckBox'] )}}
						</td>
						<td data-th='{{ trans("messages.system_management.title") }}'>{{ $record->title }}</td>
						<td data-th='{{ trans("messages.system_management.url") }}'>{{ SYSTEM_DOCUMENT_URL.$record->name }}</td>
						<td data-th='{{ trans("messages.system_management.action") }}'>
							<button type="submit" class="btn btn-success btn-small select_url ml5">Select Url</button>
							<a href="{{URL::to('admin/system-doc-manager/edit-doc/'.$record->id)}}" class="btn btn-info btn-small">{{ trans("messages.system_management.edit") }} </a>
							
							@if(Config::get('app.debug'))
							<a href="{{URL::to('admin/system-doc-manager/delete-doc/'.$record->id)}}" data-delete="delete" class="delete_user btn btn-danger btn-small">{{ trans("messages.system_management.delete") }}</a>
							@endif
						</td>
					</tr>
					 @endforeach  
					@else
						<table class="mws-table mws-datatable details">	
							<tr>
								<td align="center" width="100%" > {{ trans("messages.system_management.no_record_found_message") }}</td>
							</tr>	
						</table>
					@endif 
			</tbody>
		</table>
	</div>
	@include('pagination.default', ['paginator' => $result])
</div>
@stop
