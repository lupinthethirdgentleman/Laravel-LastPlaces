@extends('admin.layouts.default')

@section('content')

<div id="mws-themer">
	<div id="mws-themer-content" style="<?php echo ($searchVariable) ? 'right: 256px;' : ''; ?>">
		<div id="mws-themer-ribbon"></div>
		<div id="mws-themer-toggle" class="<?php echo ($searchVariable) ? 'opened' : ''; ?>">
			<i class="icon-bended-arrow-left"></i> 
			<i class="icon-bended-arrow-right"></i>
		</div>
	
		{{ Form::open(['method' => 'get','role' => 'form','url' => 'admin/language-settings','class' => 'mws-form']) }}
		{{ Form::hidden('display') }}
		<div class="mws-themer-section">
			<div id="mws-theme-presets-container" class="mws-themer-section">
				<label>{{ 'Title' }}</label><br/>
				{{ Form::text('msgid',((isset($searchVariable['msgid'])) ? $searchVariable['msgid'] : ''), ['class' => 'small']) }}
			</div>
		</div>
		<div class="mws-themer-separator"></div>
		<div class="mws-themer-section" style="height:0px">
			<ul><li class="clearfix"><span></span> <div id="mws-textglow-op"></div></li> </ul>
		</div>
		<div class="mws-themer-section">
			<input type="submit" value="Search" class="btn btn-primary btn-small">
			<a href="{{URL::to('admin/language-settings')}}"  class="btn btn-default btn-small">Reset</a>
		</div>
		{{ Form::close() }}
	</div>
</div>

<div  class="mws-panel grid_8 mws-collapsible">
	<div class="mws-panel-header">
		<span>
			<i class="icon-table"></i> {{ trans("messages.management.language_settings") }} </span>
<!--
			<a href="{{URL::to('admin/language-settings/add-setting')}}" class="btn btn-success btn-small align">{{ 'Add New' }} </a>
-->
		
	</div>
	<div class="dataTables_wrapper" style="background-color:#CCCCCC">
		<div id="DataTables_Table_0_length" class="dataTables_length">
			
		</div>
	</div>
	<div class="mws-panel-body no-padding dataTables_wrapper">
		<table class="mws-table mws-datatable" style="font-size:13px;">
			<thead>
				<tr>
					<th width="20%">
					{{
						link_to_action(
							'LanguageSettingsController@listLanguageSetting',
							'Title',
							array(
								'sortBy' => 'msgid',
								'order' => ($sortBy == 'msgid' && $order == 'desc') ? 'asc' : 'desc'
							),
						   array('class' => (($sortBy == 'msgid' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'msgid' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
						)
					}}
					</th>
					<th width="40%">{{ 'String' }}</th>
					<th width="15%"> 	
					{{
						link_to_action(
							'LanguageSettingsController@listLanguageSetting',
							'Folder Code',
							array(
								'sortBy' => 'locale',
								'order' => ($sortBy == 'locale' && $order == 'desc') ? 'asc' : 'desc'
							),
						   array('class' => (($sortBy == 'locale' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'locale' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
						)
					}}
	                </th> 
					<th>
					{{
						link_to_action(
							'LanguageSettingsController@listLanguageSetting',
							'Created',
							array(
								'sortBy' => 'created_at',
								'order' => ($sortBy == 'created_at' && $order == 'desc') ? 'asc' : 'desc'
							),
						   array('class' => (($sortBy == 'created_at' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'created_at' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
						)
					}}</th>
<!--
					<th>{{ 'Action' }}</th>
-->
				</tr>
			</thead>
			<tbody>
				@if(!$result->isEmpty())
				@foreach($result as $record) 
				<tr>
					<td data-th='title'>{{ $record->msgid }}</td>
					<td data-th='String'>
						<div id="actual_div_{{ $record->id }}">
								{{ $record->msgstr }}
						</div>
						<div style="display:none;" id="edit_div_{{ $record->id }}">
							&nbsp;
						</div>	
					
					</td>
					<td data-th='Folder Code'>{{ $record->locale }}</td>
					<td data-th='Created At'>{{ $record->created_at->format(Config::get("Reading.date_format")); }}</td>
<!--
					<td data-th='Action'>
						<a id="edit_<?php echo $record->id; ?>" href="{{URL::to('admin/language-settings/edit-setting/'.$record->id)}}" class="edit_button btn btn-info btn-small">{{ trans("messages.edit") }} </a>
					</td>
-->
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
	@include('pagination.default', ['paginator' => $model])	
</div>
@stop
	
