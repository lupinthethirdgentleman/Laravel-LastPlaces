@extends('admin.layouts.default')

@section('content')

<script type="text/javascript">
 var action_url = '<?php echo WEBSITE_URL; ?>admin/cms-manager/multiple-action';
 </script>
{{ HTML::script('js/admin/multiple_delete.js') }}



<div id="mws-themer">
	<div id="mws-themer-content" style="<?php echo ($searchVariable) ? 'right: 256px;' : ''; ?>">
		<div id="mws-themer-ribbon"></div>
		<div id="mws-themer-toggle" class="<?php echo ($searchVariable) ? 'opened' : ''; ?>">
			<i class="icon-bended-arrow-left"></i> 
			<i class="icon-bended-arrow-right"></i>
		</div>
	
		{{ Form::open(['method' => 'get','role' => 'form','url' => 'admin/location','class' => 'mws-form']) }}
		{{ Form::hidden('display') }}
		
		<div class="mws-themer-section">
			<div id="mws-theme-presets-container" class="mws-themer-section">
				<label>{{ 'Country Name' }}</label><br/>
				{{ Form::text('name',((isset($searchVariable['name'])) ? $searchVariable['name'] : ''), ['class' => 'small']) }}
			</div>
		</div>
		
		<div class="mws-themer-section">
			<div id="mws-theme-presets-container" class="mws-themer-section">
				<label>{{ 'Country ISO Code' }}</label><br/>
				{{ Form::text('country_iso_code',((isset($searchVariable['country_iso_code'])) ? $searchVariable['country_iso_code'] : ''), ['class' => 'small']) }}
			</div>
		</div>
		
		<div class="mws-themer-section">
			<div id="mws-theme-presets-container" class="mws-themer-section">
				<label>{{ 'Country Code' }}</label><br/>
				{{ Form::text('country_code',((isset($searchVariable['country_code'])) ? $searchVariable['country_code'] : ''), ['class' => 'small']) }}
			</div>
		</div>
		
		<div class="mws-themer-separator"></div>
		<div class="mws-themer-section" style="height:0px">
			<ul><li class="clearfix"><span></span> <div id="mws-textglow-op"></div></li> </ul>
		</div>
		<div class="mws-themer-section">
			<input type="submit" value="Search" class="btn btn-primary btn-small">
			<a href="{{URL::to('admin/location')}}"  class="btn btn-default btn-small">Reset</a>
		</div>
		{{ Form::close() }}
	</div>
</div>


<div  class="mws-panel grid_8 mws-collapsible">
	<div class="mws-panel-header">
		<span>
			<i class="icon-table"></i> {{ trans("messages.system_management.table_heading_Country_module_index") }} 
		</span>
		<a href="{{URL::to('admin/location/add-country') }}" class="btn btn-success btn-small align">{{ trans("messages.system_management.add_new_Country") }}</a>
	</div>
	<div class="dataTables_wrapper" style="background-color:#CCCCCC">
		<div id="DataTables_Table_0_length" class="dataTables_length">
			<?php 
			$actionTypes	= array(
					'inactive' 		=> trans('messages.global.mark_as_inactive'),
					'active' 		=> trans('messages.global.mark_as_active'),
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
					<th width="5%"></th>
					<th width="15%">
						{{
							link_to_route(
							'Country.index',
							'Country Name',
							array(
								'sortBy' => 'name',
								'order' => ($sortBy == 'name' && $order == 'desc') ? 'asc' : 'desc'
							),
							array('class' => (($sortBy == 'name' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'name' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
							)
						}}
					</th>
					<th width="15%">
						{{
							link_to_route(
							'Country.index',
							'Country Iso Code',
							array(
								'sortBy' => 'country_iso_code',
								'order' => ($sortBy == 'country_iso_code' && $order == 'desc') ? 'asc' : 'desc'
							),
							array('class' => (($sortBy == 'country_iso_code' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'country_iso_code' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
							)
						}}
					</th>
					<th width="15%">
						{{
							link_to_route(
							'Country.index',
							'Country Code',
							array(

								'sortBy' => 'country_code',
								'order' => ($sortBy == 'country_code' && $order == 'desc') ? 'asc' : 'desc'
							),
							array('class' => (($sortBy == 'country_code' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'country_code' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
							)
						}}
					</th>
					<th width="10%">{{ 'Country Order' }}</th>
					<th width="10%">{{ 'Status' }}</th>
					<th>{{ 'Action' }}</th>
				</tr>
			</thead>
			<tbody id="powerwidgets">
				@if(!$result->isEmpty())
					@foreach($result as $record)
						<tr class="items-inner">
							<td data-th=' {{ trans("messages.system_management.select") }}'>
							{{ Form::checkbox('status',$record->id,null,['class'=> 'userCheckBox'] )}}
							</td>
							<td data-th='Name'>{{ $record->name }}</td>
							<td data-th='Country Iso Code'>{{ $record->country_iso_code }}</td>
							<td data-th='Country Code'>{{ $record->country_code }}</td>
							<td data-th='Country Order'>{{ $record->country_order }}</td>
							<td data-th='Status'>
								@if($record->status==1)
									<span class="label label-success">Activated</span>
								@else
									<span class="label label-warning">Deactivated</span>
								@endif
							</td>
							<td data-th='Action'>
								
								<a href="{{URL::to('admin/Country-setting/edit-new-text/'.$record->id)}}" class="btn btn-info btn-small "  >{{ trans("messages.global.edit") }} </a>
								
								@if($record->status == 1)
									<a href="{{URL::to('admin/Country-setting/edit-new-text/'.$record->id)}}" class="btn btn-warning btn-small"  >{{ trans("messages.global.deactivate") }} </a>
								@else
									<a href="{{URL::to('admin/Country-setting/edit-new-text/'.$record->id)}}" class="btn btn-success btn-small "  >{{ trans("messages.global.activate") }} </a>
								@endif
								
								@if($record->is_default == 0)
									<a href="{{URL::to('admin/Country-setting/edit-new-text/'.$record->id)}}" class="btn btn-danger btn-small "  >{{ trans("messages.global.not_default") }} </a>
								@else
									<a href="{{URL::to('admin/Country-setting/edit-new-text/'.$record->id)}}" class="btn btn-success btn-small"  >{{ trans("messages.global.default") }} </a>
								@endif
								
								<a href="{{URL::to('admin/Country-setting/edit-new-text/'.$record->id)}}" class="btn btn-info btn-small"  >{{ trans("messages.system_management.states") }} </a>
								
							</td>
						</tr>
					@endforeach
					@else
						<table class="mws-table mws-datatable details">	
							<tr>
								<td align="center" width="100%" > {{ 'No Records Found' }}</td>
							</tr>	
						</table>
					@endif 
				
			</tbody>
		</table>
		
	</div>

	@include('pagination.default', ['paginator' => $result])
</div>
@stop
