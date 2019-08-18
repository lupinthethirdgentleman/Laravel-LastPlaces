@extends('admin.layouts.default')
@section('content')

<script type="text/javascript">
	var action_url = '<?php echo route("$modelName.Multipleaction"); ?>';
</script>

{{ HTML::script('js/admin/multiple_delete.js') }}
{{ HTML::style('css/admin/lightbox.css') }}

<!-- search form start here-->
<div id="mws-themer">
	<div id="mws-themer-content" style="<?php echo ($searchVariable) ? 'right: 256px;' : ''; ?>">
		<div id="mws-themer-ribbon"></div>
		<div id="mws-themer-toggle" class="<?php echo ($searchVariable) ? 'opened' : ''; ?>">
			<i class="icon-bended-arrow-left"></i> 
			<i class="icon-bended-arrow-right"></i>
		</div>
		
		{{ Form::open(['method' => 'get','role' => 'form','route' =>"$modelName.index",'class' => 'mws-form']) }}
			{{ Form::hidden('display') }}
			<div class="mws-themer-section">
				<div id="mws-theme-presets-container" class="mws-themer-section">
					<label>{{ trans("messages.$modelName.name") }}</label><br/>
					{{ Form::text('name',((isset($searchVariable['name'])) ? $searchVariable['name'] : ''), ['class' => 'small']) }}
				</div>
			</div>
			<div class="mws-themer-section" style="height:0px">
				<ul>
					<li class="clearfix">
						<span></span> 
						<div id="mws-textglow-op"></div>
					</li>
				</ul>
			</div>
			<div class="mws-themer-section">
				<input type="submit" value="{{ trans('messages.global.search') }}" class="btn btn-primary btn-small">
				<a href='{{ route("$modelName.index")}}'   class="btn btn-default btn-small">{{ trans("messages.global.reset") }}</a>
			</div>
		{{ Form::close() }}
	</div>
</div>
<!-- search form end here-->


<div class="mws-panel grid_8 mws-collapsible">
	<!-- table heading and add new button-->
	<div class="mws-panel-header">
		<span>
		<i class="icon-table"></i> {{ trans("messages.$modelName.table_heading_index") }} </span>
		<a href='{{ route("$modelName.add")}}'  class="btn btn-success btn-small align">{{ trans("messages.$modelName.add_new") }} </a>
	</div>	
	
	<!-- action perform -->
	<div class="dataTables_wrapper" style="background-color:#CCCCCC">
		<!-- <div id="DataTables_Table_0_length" class="dataTables_length">
			<?php 
				$actionTypes	= array(
						'delete' 		=> trans("messages.global.delete_all"),
						'inactive' 		=> trans("messages.global.mark_as_inactive"),
						'active' 		=> trans("messages.global.mark_as_active"),
					 );
				?>
			{{ Form::open() }}
			{{ Form::checkbox('is_checked','',null,['class'=>'left checkAllUser'])}}
			{{ Form::select('action_type',array(''=> trans("messages.user_management.select_action"))+$actionTypes,$actionTypes,['class'=>'deleteall selectUserAction'])}}
			{{ Form::close() }}
		</div> -->
	</div>
	
	<!-- table secttion start here-->
	<div class="mws-panel-body no-padding dataTables_wrapper">
		<table class="mws-table mws-datatable">
			<thead>
				<tr>
					<!-- <th></th>  -->
					<th width="30%">						
						{{
							link_to_route(
								"$modelName.index",
								trans("messages.$modelName.name"),
								array(
									'sortBy' => 'name',
									'order' => ($sortBy == 'name' && $order == 'desc') ? 'asc' : 'desc'
								),
								array('class' => (($sortBy == 'name' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'name' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
								)
						}}
					</th>
					<th>
						{{ trans("messages.$modelName.image") }}
					</th>
					<th width="30%">{{ trans("messages.$modelName.description") }}</th>
					<th >{{ trans("messages.global.status") }}</th>
					<th>{{ trans("messages.global.action") }}</th>
				</tr>
			</thead>
			<tbody id="powerwidgets">
				<?php
					if(!$model->isEmpty()){
						foreach($model as $result){ ?>
							<tr class="items-inner">
								<!-- <td data-th='{{ trans("messages.global.select") }}'>
									{{ Form::checkbox('is_active',$result->id,null,['class'=> 'userCheckBox'] )}}
								</td>
								 -->
								<td data-th='{{ trans("messages.$modelName.name") }}'>
									{{ strip_tags(Str::limit($result->name, 120)) }}
								</td>
								
								<td  data-th='{{ trans("messages.$modelName.image") }}'>
									@if($result->image!='' && File::exists(NEWS_IMAGE_ROOT_PATH.$result->image))
									<a class="items-image" data-lightbox="roadtrip<?php echo $result->image; ?>" href="<?php echo NEWS_IMAGE_URL.$result->image; ?>">
									
									{{ HTML::image( NEWS_IMAGE_URL.$result->image, $result->image , array( 'width' => 70, 'height' => 70 )) }}
									
									</a>
									@endif
								</td>
								
								<td data-th='{{ trans("messages.$modelName.description") }}'>
									{{ strip_tags(Str::limit($result->description, 250)) }}
								</td>
								
								<td data-th='{{ trans("messages.global.is_active") }}'>
									@if($result->is_active	== 1)
									<span class="label label-success" >{{ trans("messages.global.activated") }}</span>
									@else
									<span class="label label-warning" >{{ trans("messages.global.deactivated") }}</span>
									@endif
								</td>
								
								<td  data-th='{{ trans("messages.global.action") }}'>
									@if($result->is_highlight == 0)
										<a href='{{route("$modelName.highlight",array($result->id))}}'  class="btn btn-warning btn-small">Mark as highlight</a>
									@endif
									
									@if($result->is_active)
										<a href='{{route("$modelName.status",array($result->id,0))}}'  class="is_active_user btn btn-warning btn-small">{{ trans("messages.global.inactive") }} </a>
									@else
										<a href='{{route("$modelName.status",array($result->id,1))}}'  class="is_active_user btn btn-success btn-small">{{ trans("messages.global.activate") }}</a>
									@endif
									<a href='{{route("$modelName.edit","$result->id")}}'  class="btn btn-info btn-small">{{trans("messages.global.edit") }} </a>
									<a href='{{route("$modelName.delete","$result->id")}}' class="delete_user btn btn-danger btn-small no-ajax ">{{ trans("messages.global.delete") }} </a>
									<a href='{{route("$modelName.comment","$result->id")}}'  class="btn btn-info btn-small">{{trans("messages.global.comments") }} </a>
								</td>
								
							</tr>
				<?php }}else{ ?>
						<tr>
							<td align="center" width="100%" > {{ trans("messages.global.no_record_found_message") }}</td>
						</tr>
				<?php  } ?>
			</tbody>
		</table>
	</div>
	@include('pagination.default', ['paginator' => $model])	
</div>


<style>
	.span1{
	margin-right:10px;
	width:60px;
	}
</style>

{{ HTML::script('js/admin/lightbox.js') }}

<script type="text/javascript">
	// when click on order by field value,button will appear to change the order by value
	function change(obj){
		id_array		=	obj.id.split("_");
		current_id		=	id_array[2]; 
		current_order	=	id_array[1];
		order_by		=	$("#order_by_"+current_id).val();
		$("#change_div"+current_id).show();
		$("#link_"+current_order+"_"+current_id).hide();
		return false; 
	 }
</script>
@stop

