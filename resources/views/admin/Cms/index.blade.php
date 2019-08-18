@extends('admin.layouts.default')

@section('content')

<script type="text/javascript">
 var action_url = '<?php echo route("$modelName.Multipleaction"); ?>';
 </script>
 
{{ HTML::script('js/admin/multiple_delete.js') }}

<div id="mws-themer">
	<div id="mws-themer-content" style="{{ ($searchVariable && !isset($searchVariable['records_per_page'])) ? 'right: 256px;' : '' }}"> 
		<div id="mws-themer-ribbon"></div>
		<div id="mws-themer-toggle" class="{{ ($searchVariable && !isset($searchVariable['records_per_page'])) ? 'opened' : '' }}">
			<i class="icon-bended-arrow-left"></i> 
			<i class="icon-bended-arrow-right"></i>
		</div>
	
		{{ Form::open(['method' => 'get','role' => 'form','route' => "$modelName.index",'class' => 'mws-form']) }}
		{{ Form::hidden('display') }}
		<div class="mws-themer-section">
			<div id="mws-theme-presets-container" class="mws-themer-section">
				<label>{{ trans("messages.$modelName.page_name") }}</label><br/>
				{{ Form::text('name',((isset($searchVariable['name'])) ? $searchVariable['name'] : ''), ['class' => 'small']) }}
			</div>
		</div>
		<div class="mws-themer-separator"></div>
		<div class="mws-themer-section" style="height:0px">
			<ul><li class="clearfix"><span></span> <div id="mws-textglow-op"></div></li> </ul>
		</div>
		<div class="mws-themer-section">
			<input type="submit" value="{{ trans('messages.global.search') }}" class="btn btn-primary btn-small">
			<a href='{{ route("$modelName.index")}}'  class="btn btn-default btn-small">{{ trans('messages.global.reset') }}</a>
		</div>
		{{ Form::close() }}
	</div>
</div>

<div  class="mws-panel grid_8 mws-collapsible">
	<div class="mws-panel-header">
		<span>
		<i class="icon-table"></i> {{ trans("messages.$modelName.table_heading_index") }} </span>
		@if(Config::get('app.debug'))
			<a href='{{route("$modelName.add")}}'  class="btn btn-success btn-small align">{{ trans("messages.$modelName.add_new") }} </a>
		@endif
	</div>
<!-- 	<div class="dataTables_wrapper" style="background-color:#CCCCCC">
		<div class="dataTables_length" id="DataTables_Table_0_length">
			<div class="pull-left">
				<form class="form-horizontal" method="get">
					<div class="pull-left grytxt12 pr10 pt5">
						Show&nbsp;&nbsp;
					</div>
					<div class="pull-left">
						<select class="lstfld60" style="width:85px;" name="records_per_page" onchange="this.form.submit();">
							<?php 
								$recordPerPageAction	=	Config::get('Reading.records_per_pag_action');
								print_r($recordPerPageAction);
								if($recordPerPageAction!=''){
									$recordPerPageActionArray 	=	explode(',',$recordPerPageAction);
								}
							?>	
							<option value="2" selected="selected">Default</option>
							@if(!empty($recordPerPageActionArray))
								@foreach($recordPerPageActionArray as $value)
									<option @if(Input::get('records_per_page')==$value) selected="selected" @endif >{{ $value }} </option>
								@endforeach
							@endif
						</select> 
					</div>
					<div class="pull-left grytxt12 pr10 pt5">
						&nbsp;&nbsp;Entries		
					</div>
				   <div class="clr"></div>
				</form>
			</div>
		</div>
		<div id="DataTables_Table_0_length" class="dataTables_length right">
			<?php 
			$actionTypes	= array(
					'delete' 		=> trans("messages.global.delete_all"),
					'inactive' 		=> trans('messages.global.mark_as_inactive'),
					'active' 		=> trans('messages.global.mark_as_active'),
				 );
			?>
			{{ Form::open() }}
				{{ Form::checkbox('is_checked','',null,['class'=>'left checkAllUser'])}}
				{{ Form::select('action_type',array(''=>trans("messages.global.select_action"))+$actionTypes,$actionTypes,['class'=>'deleteall selectUserAction'])}}
			{{ Form::close() }}
		</div>
	</div> -->
	<div class="mws-panel-body no-padding dataTables_wrapper">
		<table class="mws-table mws-datatable">
			<thead>
				<tr>
					<!-- <th></th> -->
					<th width="20%">
						{{
							link_to_route(
							"Cms.index",
							trans("messages.$modelName.page_name"),
							array(
								'sortBy' => 'name',
								'order' => ($sortBy == 'name' && $order == 'desc') ? 'asc' : 'desc'
							),
							array('class' => (($sortBy == 'name' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'name' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
							)
						}}
					</th>
					<th width="30%">{{ trans("messages.$modelName.page_description") }}</th>
					<th>{{ trans("messages.global.status") }}</th>
					<th>
						{{
							link_to_route(
							"Cms.index",
							trans("messages.$modelName.modified"),
							array(
								'sortBy' => 'updated_at',
								'order' => ($sortBy == 'updated_at' && $order == 'desc') ? 'asc' : 'desc'
							),
							array('class' => (($sortBy == 'updated_at' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'updated_at' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
							)
						}}
					</th>
					<th>{{ trans("messages.global.action") }}</th>
				</tr>
			</thead>
			<tbody id="powerwidgets">
				@if(!$model->isEmpty())
				@foreach($model as $record)
				<tr class="items-inner">
					<!-- <td data-th=' {{ trans("messages.global.select") }}'>
						{{ Form::checkbox('status',$record->id,null,['class'=> 'userCheckBox'] )}}
					</td> -->
					<td data-th='{{ trans("messages.$modelName.page_name") }}'>{{ $record->name }}</td>
					<td data-th='{{ trans("messages.$modelName.page_description") }}'>{{ strip_tags(Str::limit($record->body, 300)) }}</td>
					<td data-th='{{ trans("messages.global.status") }}'>
					@if($record->is_active	== ACTIVE)
						<span class="label label-success" >{{ trans("messages.global.activated") }}</span>
					@else
						<span class="label label-warning" >{{ trans("messages.global.deactivated") }}</span>
					@endif
					</td>
					<td data-th='{{ trans("messages.$modelName.modified") }}'>
					{{ date(Config::get("Reading.date_format") , strtotime($record->updated_at)) }}
					</td>
					<td data-th='{{ trans("messages.global.action") }}'>
						
						<a href='{{ route( "$modelName.edit",$record->id)}}' class="btn btn-info btn-small">{{ trans("messages.global.edit") }} </a>
						
						@if($record->is_active)
							<a href='{{ route("$modelName.status",array($record->id,INACTIVE))}}' class="status_user btn btn-warning btn-small">{{ trans("messages.global.mark_as_inactive") }}
							</a>
						@else
							<a href='{{ route("$modelName.status",array($record->id,ACTIVE) )}}' class="status_user btn btn-success btn-small">{{ trans("messages.global.mark_as_active") }}
							</a>
						@endif
						
					</td>
				</tr>
				 @endforeach
				 @else
					<table class="mws-table mws-datatable details">	
						<tr>
							<td align="center" width="100%"> {{ trans("messages.global.no_record_found_message") }}</td>
						</tr>	
					</table>  
				@endif 
			</tbody>
		</table>
	</div>
	@include('pagination.default', ['paginator' => $model,'searchVariable'=>$searchVariable])
</div>
@stop
