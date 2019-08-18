@extends('admin.layouts.default')

@section('content')

<div id="mws-themer">
	<div id="mws-themer-content" style="<?php echo ($searchVariable) ? 'right: 256px;' : ''; ?>">
		<div id="mws-themer-ribbon"></div>
		<div id="mws-themer-toggle" class="<?php echo ($searchVariable) ? 'opened' : ''; ?>">
			<i class="icon-bended-arrow-left"></i> 
			<i class="icon-bended-arrow-right"></i>
		</div>
	
		{{ Form::open(['method' => 'get','role' => 'form','url' => 'admin/text-setting','class' => 'mws-form']) }}
		{{ Form::hidden('display') }}
		
		<div class="mws-themer-section">
			<div id="mws-theme-presets-container" class="mws-themer-section">
				<label>{{ trans("messages.settings.key") }}</label><br/>
				{{ Form::text('key_value',((isset($searchVariable['key_value'])) ? $searchVariable['key_value'] : ''), ['class' => 'small']) }}
			</div>
		</div>
		
		<div class="mws-themer-section">
			<div id="mws-theme-presets-container" class="mws-themer-section">
				<label>{{ trans("messages.settings.value") }}</label><br/>
				{{ Form::text('value',((isset($searchVariable['value'])) ? $searchVariable['value'] : ''), ['class' => 'small']) }}
			</div>
		</div>
		
		<div class="mws-themer-section">
			<div id="mws-theme-presets-container" class="mws-themer-section">
				<label>{{ trans("messages.settings.search_by_module") }}</label><br/>
				{{ Form::select('module',array(''=>'Select Module')+Config::get('text_search'),((isset($searchVariable['module'])) ? $searchVariable['module'] : ''), ['class' => 'small']) }}
			</div>
		</div>
		
		<div class="mws-themer-separator"></div>
		<div class="mws-themer-section" style="height:0px">
			<ul><li class="clearfix"><span></span> <div id="mws-textglow-op"></div></li> </ul>
		</div>
		<div class="mws-themer-section">
			<input type="submit" value="Search" class="btn btn-primary btn-small">
			<a href="{{URL::to('admin/text-setting')}}"  class="btn btn-default btn-small">Reset</a>
		</div>
		{{ Form::close() }}
	</div>
</div>


<div  class="mws-panel grid_8 mws-collapsible">
	<div class="mws-panel-header">
		<span>
			<i class="icon-table"></i> {{ trans("messages.settings.manage_text") }} 
		</span>
		<a href="{{URL::to('admin/text-setting/add-new-text')}}" class="btn btn-success btn-small align">{{ trans("messages.settings.add_new_setting") }}</a>
	</div>
	<div class="dataTables_wrapper" style="background-color:#CCCCCC">
		<div id="DataTables_Table_0_length" class="dataTables_length">
		</div>
	</div>
	<div class="mws-panel-body no-padding dataTables_wrapper">
		<table class="mws-table mws-datatable">
			<thead>
				<tr>
					<th>{{ trans("messages.settings.language") }}</th>
					<th width="35%">
						{{
							link_to_route(
							'TextSetting.index',
							trans("messages.settings.key"),
							array(
								'sortBy' => 'key_value',
								'order' => ($sortBy == 'key_value' && $order == 'desc') ? 'asc' : 'desc'
							),
							array('class' => (($sortBy == 'key_value' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'key_value' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
							)
						}}
					</th>
					<th width="35%">
						{{
							link_to_route(
							'TextSetting.index',
							trans("messages.settings.value"),
							array(
								'sortBy' => 'value',
								'order' => ($sortBy == 'value' && $order == 'desc') ? 'asc' : 'desc'
							),
							array('class' => (($sortBy == 'value' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'value' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
							)
						}}
					</th>
						
					<th>{{ trans("messages.global.action") }}</th>
				</tr>
			</thead>
			<tbody>
				@if(!$result->isEmpty())
					@foreach($result as $record)
						<tr>
							<td data-th='{{ trans("messages.settings.language")}}'>
								@if(array_key_exists($record->language_id ,$languageArray))
									{{ $languageArray[$record->language_id] }}
								@endif
							</td>
							<td data-th='Key'>{{ $record->key_value }}</td>
							<td data-th='Value'>{{ $record->value }}</td>
							<td data-th='Action'>
								<a href="{{URL::to('admin/text-setting/edit-new-text/'.$record->id)}}" class="btn btn-info btn-small ml5"  >{{ trans("messages.settings.edit") }} </a>
							</td>
						</tr>
					@endforeach
					@else
						<table class="mws-table mws-datatable details">	
							<tr>
								<td align="center" width="100%" > {{ trans("messages.settings.no_record_found_message") }}</td>
							</tr>	
						</table>
					@endif 
			</tbody>
		</table>
	</div>

	@include('pagination.default', ['paginator' => $result])
</div>
@stop
