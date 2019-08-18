@extends('admin.layouts.default')

@section('content')

<script type="text/javascript">
 var action_url = '<?php echo WEBSITE_URL; ?>admin/meta-manager/multiple-action';
 </script>
{{ HTML::script('js/admin/multiple_delete.js') }}

<div id="mws-themer">
	<div id="mws-themer-content" style="<?php echo ($searchVariable) ? 'right: 256px;' : ''; ?>">
		<div id="mws-themer-ribbon"></div>
		<div id="mws-themer-toggle" class="<?php echo ($searchVariable) ? 'opened' : ''; ?>">
			<i class="icon-bended-arrow-left"></i> 
			<i class="icon-bended-arrow-right"></i>
		</div>
	
		{{ Form::open(['method' => 'get','role' => 'form','url' => 'admin/meta-manager','class' => 'mws-form']) }}
		{{ Form::hidden('display') }}
		<div class="mws-themer-section">
			<div id="mws-theme-presets-container" class="mws-themer-section">
				<label>{{ trans("messages.system_management.page_name") }}</label><br/>
				{{ Form::select('page_id',array(''=>'Select Page')+Config::get('PAGE_LIST'),$searchVariable, ['class' => 'small']) }}
			</div>
		</div>
		<div class="mws-themer-separator"></div>
		<div class="mws-themer-section" style="height:0px">
			<ul><li class="clearfix"><span></span> <div id="mws-textglow-op"></div></li> </ul>
		</div>
		<div class="mws-themer-section">
			<input type="submit" value="{{ trans('messages.system_management.search') }}" class="btn btn-primary btn-small">
			<a href="{{URL::to('admin/meta-manager')}}"  class="btn btn-default btn-small">{{ trans("messages.system_management.reset") }}</a>
		</div>
		{{ Form::close() }}
	</div>
</div>

<div  class="mws-panel grid_8 mws-collapsible">
	<div class="mws-panel-header">
		<span>
			<i class="icon-table"></i> {{ trans("messages.system_management.seo_list") }} </span>
			<a href="{{URL::to('admin/meta-manager/add-meta')}}" class="btn btn-success btn-small align">{{ trans("messages.system_management.add")}} </a>
		
	</div>
	<div class="dataTables_wrapper" style="background-color:#CCCCCC">
		<div id="DataTables_Table_0_length" class="dataTables_length">
			<?php 
			$actionTypes	= array(
					'delete' 		=>  trans("messages.global.delete_all"),
				 );
			?>
			{{ Form::open() }}
				{{ Form::checkbox('is_checked','',null,['class'=>'left checkAllUser'])}}
				{{ Form::select('action_type',array(''=> trans("messages.user_management.select_action"))+$actionTypes,$actionTypes,['class'=>'deleteall selectUserAction'])}}
			{{ Form::close() }}
			
		</div>
	</div>
	<div class="mws-panel-body no-padding dataTables_wrapper">
		<table class="mws-table mws-datatable">
			<thead>
				<tr>
					<th></th>
					<th width="20%">
						{{ trans("messages.system_management.page_name") }}
					</th>
					<th width="20%">
						{{
							link_to_route(
							'Meta.listMeta',
							trans("messages.system_management.meta_title"),
							array(
								'sortBy' => 'meta_title',
								'order' => ($sortBy == 'meta_title' && $order == 'desc') ? 'asc' : 'desc'
							),
							array('class' => (($sortBy == 'meta_title' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'meta_title' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
							)
						}}
					</th>
					<th width="20%">
						{{
							link_to_route(
							'Meta.listMeta',
							trans("messages.system_management.meta_keyword"),
							array(
								'sortBy' => 'meta_keyword',
								'order' => ($sortBy == 'meta_keyword' && $order == 'desc') ? 'asc' : 'desc'
							),
							array('class' => (($sortBy == 'meta_keyword' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'meta_keyword' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
							)
						}}
					</th>
					<th width="30%">
						{{
							link_to_route(
							'Meta.listMeta',
							trans("messages.system_management.description"),
							array(
								'sortBy' => 'description',
								'order' => ($sortBy == 'description' && $order == 'desc') ? 'asc' : 'desc'
							),
							array('class' => (($sortBy == 'description' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'description' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
							)
						}}
					</th>
					<th>{{ trans("messages.system_management.action") }}</th>
				</tr>
			</thead>
			<tbody id="powerwidgets">
				@if(!$result->isEmpty())
				@foreach($result as $record)
				<tr class="inner-class">
					<td data-th='{{ trans("messages.system_management.select") }}'>
					{{ Form::checkbox('status',$record->id,null,['class'=> 'userCheckBox'] )}}
					</td>
					<td data-th='{{ trans("messages.system_management.page_name") }}'>{{ Config::get('PAGE_LIST')[$record->page_id] }}</td>
					<td data-th='{{ trans("messages.system_management.meta_title") }}'>{{ $record->meta_title }}</td>
					<td data-th='{{ trans("messages.system_management.meta_keyword") }}'>{{ $record->meta_keyword }}</td>
					<td data-th='{{ trans("messages.system_management.description") }}'>
						@if(strlen($record->description) < 150)
							{{ $record->description }}
						@else
							{{ substr($record->description,'0',150).'..'}}
						@endif
					</td>
					<td data-th='{{ trans("messages.system_management.action") }}'>
						<a href="{{URL::to('admin/meta-manager/edit-meta/'.$record->id)}}" class="btn btn-info btn-small">{{ trans("messages.system_management.edit") }} </a>		
					</td>
				</tr>
				 @endforeach  
			</tbody>
		</table>
		@else
		<table class="mws-table mws-datatable details">	
			<tr>
				<td align="center" width="100%" > {{ trans("messages.system_management.no_record_found_message") }}</td>
			</tr>	
			@endif 
		</table>
	</div>
	@include('pagination.default', ['paginator' => $result])
</div>
@stop
