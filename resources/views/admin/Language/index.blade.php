@extends('admin.layouts.default')

@section('content')

<script type="text/javascript">
	var action_url = '<?php echo route("$modelName.Multipleaction"); ?>';
</script>

{{ HTML::script('js/admin/multiple_delete.js') }}

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
				<label>{{ trans("messages.$modelName.title") }}</label><br/>
				{{ Form::text('title',((isset($searchVariable['title'])) ? $searchVariable['title'] : ''), ['class' => 'small']) }}
			</div>
		</div>
		
		<div class="mws-themer-separator"></div>
		<div class="mws-themer-section" style="height:0px">
			<ul><li class="clearfix"><span></span> <div id="mws-textglow-op"></div></li> </ul>
		</div>
		
		<div class="mws-themer-section">
			<input type="submit" value='{{ trans("messages.$modelName.search") }}' class="btn btn-primary btn-small">
			<a href='{{route("$modelName.index")}}'  class="btn btn-default btn-small">{{ trans("messages.$modelName.reset") }}</a>
		</div>
		{{ Form::close() }}
	</div>
</div>

<div  class="mws-panel grid_8 mws-collapsible">
	<div class="mws-panel-header">
		<span>
			<i class="icon-table"></i> {{ trans("messages.$modelName.language") }}
		</span>
		
	</div>
	<div class="dataTables_wrapper" style="background-color:#CCCCCC">

	</div>
	<div class="mws-panel-body no-padding dataTables_wrapper">
		<table class="mws-table mws-datatable">
			<thead>
				<tr>
					
					<th width="20%">
					{{
						link_to_route(
							"$modelName.index",
							trans("messages.$modelName.title"),
							array(
								'sortBy' => 'title',
								'order' => ($sortBy == 'title' && $order == 'desc') ? 'asc' : 'desc'
							),
						   array('class' => (($sortBy == 'title' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'title' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
						)
					}}
					</th>
					<th width="20%">
						{{
							link_to_route(
								"$modelName.index",
								trans("messages.$modelName.folder_code"),
								array(
									'sortBy' => 'folder_code',
									'order' => ($sortBy == 'folder_code' && $order == 'desc') ? 'asc' : 'desc'
								),
							   array('class' => (($sortBy == 'folder_code' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'folder_code' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
							)
						}}
					</th>
						
					<th width="20%">
						{{
							link_to_route(
								"$modelName.index",
								trans("messages.$modelName.language_code"),
								array(
									'sortBy' => 'lang_code',
									'order' => ($sortBy == 'lang_code' && $order == 'desc') ? 'asc' : 'desc'
								),
							   array('class' => (($sortBy == 'lang_code' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'lang_code' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
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
						
						<td data-th='{{ trans("messages.$modelName.title") }}'>{{ $result->title }}</td>
						<td data-th='{{ trans("messages.$modelName.folder_code") }}'>{{ $result->folder_code }}</td>
						<td data-th='{{ trans("messages.$modelName.language_code") }}'>{{ $result->lang_code }}</td>
						<td data-th='{{ trans("messages.$modelName.action") }}'>
							@if($result->is_active)
								<a href='{{route("$modelName.status",array($result->id,0))}}' class="status_user btn btn-success btn-small ">{{ trans("messages.global.active") }} </a>
							@else
								<a href='{{route("$modelName.status",array($result->id,1))}}' class="status_user btn btn-warning btn-small">{{ trans("messages.global.inactive") }}</a>
							@endif
								@if($default_lang == $result->id )
									<a href="javascript:void(0)" class="btn btn-primary  btn-small">{{ trans("messages.$modelName.default") }}</a>
								@elseif($result->is_active==1)
									<a href="{{URL::to('admin/language/default/'.$result->id.'/'.$result->title.'/'.$result->folder_code)}}" class="btn btn  btn-small">{{ trans("messages.$modelName.make_it_default") }}</a>
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
