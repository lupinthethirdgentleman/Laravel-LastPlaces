@extends('admin.layouts.default')
@section('content')

<script type="text/javascript">
	var action_url = '<?php echo route("$modelName.Multipleaction"); ?>';
</script>

{{ HTML::script('js/admin/multiple_delete.js') }}

<!-- search form start here-->
<div id="mws-themer">
	<div id="mws-themer-content" style="<?php echo ($searchVariable) ? 'right: 256px;' : ''; ?>">
		<div id="mws-themer-ribbon"></div>
		<div id="mws-themer-toggle" class="<?php echo ($searchVariable) ? 'opened' : ''; ?>">
			<i class="icon-bended-arrow-left"></i> 
			<i class="icon-bended-arrow-right"></i>
		</div>
		{{ Form::open(['method' => 'get','role' => 'form','route' => "$modelName.index",'class' => 'mws-form']) }}
			{{ Form::hidden('display') }}
			<div class="mws-themer-section">
				<div id="mws-theme-presets-container" class="mws-themer-section">
					<label>{{ trans("messages.$modelName.page_name") }}</label><br/>
					{{ Form::text('page_name',((isset($searchVariable['page_name'])) ? $searchVariable['page_name'] : ''), ['class' => 'small']) }}
				</div>
			</div>
			
			<div class="mws-themer-section">
				<div id="mws-theme-presets-container" class="mws-themer-section">
					<label>{{ trans("messages.$modelName.block_name") }}</label><br/>
					{{ Form::text('block_name',((isset($searchVariable['block_name'])) ? $searchVariable['block_name'] : ''), ['class' => 'small']) }}
				</div>
			</div>
			
			<div class="mws-themer-separator"></div>
			<div class="mws-themer-section" style="height:0px">
				<ul>
					<li class="clearfix">
						<span></span> 
						<div id="mws-textglow-op"></div>
					</li>
				</ul>
			</div>
			
			<div class="mws-themer-section">
				<input type="submit" value="{{ trans('messages.search.text') }}" class="btn btn-primary btn-small">
				<a href='{{ route("$modelName.index")}}'  class="btn btn-default btn-small">{{ trans('messages.global.reset') }}</a>
			</div>
		{{ Form::close() }}
	</div>
</div>
<!-- search form end here-->


<div  class="mws-panel grid_8 mws-collapsible">
	<!-- table heading and add new button-->
	<div class="mws-panel-header">
		<span>
		<i class="icon-table"></i> {{ trans("messages.$modelName.table_heading_index") }} </span>
		@if(Config::get('app.debug'))
			<a href='{{route("$modelName.add")}}'  class="btn btn-success btn-small align">{{ trans("messages.$modelName.add_new") }} </a>
		@endif
	</div>
	<!-- action perform -->
	<div class="dataTables_wrapper" style="background-color:#CCCCCC">
		<div id="DataTables_Table_0_length" class="dataTables_length">
			<?php 
				$actionTypes	= array(
						'delete' 		=> trans("messages.global.delete_all"),
					 );
				?>
			<!-- {{ Form::open() }}
				{{ Form::checkbox('is_checked','',null,['class'=>'left checkAllUser'])}}
				{{ Form::select('action_type',array(''=>trans("messages.global.select_action"))+$actionTypes,$actionTypes,['class'=>'deleteall selectUserAction'])}}
			{{ Form::close() }} -->
		</div>
	</div>
	
	<div class="mws-panel-body no-padding dataTables_wrapper">
		<table class="mws-table mws-datatable">
			<thead>
				<tr>
					<!-- <th></th> -->
					<th width="30%">
						{{
						link_to_route(
							"$modelName.index",
							trans("messages.$modelName.page_name"),
							array(
							'sortBy' => 'page_name',
							'order' => ($sortBy == 'page_name' && $order == 'desc') ? 'asc' : 'desc'
							),
							array('class' => (($sortBy == 'page_name' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'page_name' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
							)
						}}
					</th>
					<th width="30%">
						{{
						link_to_route(
							"$modelName.index",
							trans("messages.$modelName.block_name"),
							array(
							'sortBy' => 'block_name',
							'order' => ($sortBy == 'block_name' && $order == 'desc') ? 'asc' : 'desc'
							),
							array('class' => (($sortBy == 'block_name' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'block_name' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
							)
						}}
					</th>
					<th width="20%">
						{{
							link_to_route(
								"$modelName.index",
								trans("messages.$modelName.created_at"),
								array(
								'sortBy' => 'created_at',
								'order' => ($sortBy == 'created_at' && $order == 'desc') ? 'asc' : 'desc'
								),
								array('class' => (($sortBy == 'created_at' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'created_at' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
								)
						}}
					</th>
					<th>{{ trans("messages.global.action") }}</th>
				</tr>
			</thead>
			<tbody id="powerwidgets">
				@if(!$model->isEmpty())
					@foreach($model as $result)
						<tr class="items-inner">
							<!-- <td data-th='{{ trans("messages.$modelName.select") }}'>
								{{ Form::checkbox('status',$result->id,null,['class'=> 'userCheckBox'] )}}
							</td> -->
							<td data-th='{{ trans("messages.$modelName.page_name") }}'>{{ $result->page_name }}</td>
							<td data-th='{{ trans("messages.$modelName.block_name") }}'>{{ strip_tags(Str::limit($result->block_name, 300)) }}</td>
							<td data-th='{{ trans("messages.$modelName.created_at") }}'>{{ date(Config::get("Reading.date_format") , strtotime($result->created_at)) }}</td>
							<td data-th='{{ trans("messages.global.action") }}'>
								<a href='{{route("$modelName.edit","$result->id")}}' class="btn btn-info btn-small">{{ trans("messages.global.edit") }} </a>
								@if(Config::get('app.debug'))
									<a href='{{route("$modelName.delete","$result->id")}}' data-delete="delete" class="delete_user btn btn-danger btn-small">{{ trans("messages.global.delete") }}</a>
								@endif
							</td>
						</tr>
					@endforeach  
				@else
					<table class="mws-table mws-datatable details">
						<tr>
							<td align="center" width="100%" > {{ trans("messages.global.no_record_found_message") }}</td>
						</tr>
					</table>
				@endif 
			</tbody>
		</table>
	</div>
	@include('pagination.default', ['paginator' => $model])
</div>

@stop

