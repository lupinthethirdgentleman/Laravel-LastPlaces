@extends('admin.layouts.default')

@section('content')


<script type="text/javascript">
	var action_url = '<?php echo route("$modelName.Multipleaction"); ?>';
 </script>
{{ HTML::script('js/admin/multiple_delete.js') }}
{{ HTML::style('css/admin/lightbox.css') }}

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
				<label>{{ trans("messages.$modelName.order") }}</label><br/>
				{{ Form::text('slider_order',((isset($searchVariable['slider_order'])) ? $searchVariable['slider_order'] : ''), ['class' => 'small']) }}
			</div>
		</div>
		<div class="mws-themer-separator"></div>
		<div class="mws-themer-section">
			<div id="mws-theme-presets-container" class="mws-themer-section">
				<label>{{ trans("messages.$modelName.slider_text") }}</label><br/>
				{{ Form::text('slider_text',((isset($searchVariable['slider_text'])) ? $searchVariable['slider_text'] : ''), ['class' => 'small']) }}
			</div>
		</div>
		
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
			<a href='{{route("$modelName.add")}}'  class="btn btn-success btn-small align">{{ trans("messages.$modelName.add_new") }} </a>
		
	</div>
	<div class="dataTables_wrapper" style="background-color:#CCCCCC">
		<div id="DataTables_Table_0_length" class="dataTables_length">
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
			
		</div>
	</div>
	<div class="mws-panel-body no-padding dataTables_wrapper">
		<table class="mws-table mws-datatable">
			<thead>
				<tr>
					<th></th>
					<th width="30%">{{ trans("messages.$modelName.slider_text") }}</th>
					<th>
					{{ trans("messages.$modelName.image") }}
					</th>
					<th>
						{{
							link_to_route(
							"$modelName.index",
							trans("messages.$modelName.order"),
							array(
							'sortBy' => 'slider_order',
							'order' => ($sortBy == 'slider_order' && $order == 'desc') ? 'asc' : 'desc'
							),
							array('class' => (($sortBy == 'slider_order' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'slider_order' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
							)
						}}
					</th>
					<th >{{ trans("messages.global.status") }}</th>
					<th>{{ trans("messages.global.action") }}</th>
				</tr>
			</thead>
			<tbody id="powerwidgets">
				<?php
				
				if(!$model->isEmpty()){
				foreach($model as $result){ ?>
				<tr class="items-inner">
					<td data-th='{{ trans("messages.$modelName.select") }}'>
					{{ Form::checkbox('status',$result->id,null,['class'=> 'userCheckBox'] )}}
					</td>
					<td data-th='{{ trans("messages.$modelName.slider_text") }}'>
						{{ strip_tags(Str::limit($result->slider_text, 120)) }}
					</td>
					<td data-th='{{ trans("messages.$modelName.image") }}'>
						
						@if($result->slider_image!='' && File::exists(SLIDER_ROOT_PATH.$result->slider_image))
						<a class="items-image" data-lightbox="roadtrip<?php echo $result->slider_image; ?>" href="<?php echo SLIDER_URL.$result->slider_image; ?>">
							{{ HTML::image( SLIDER_URL.$result->slider_image, $result->slider_image , array( 'width' => 70, 'height' => 70 )) }}
						</a>	
						@endif
					</td>
					<td  data-th='{{ trans("messages.$modelName.order") }}'>
						<span style="color:#0088CC;cursor:pointer;" id="link_<?php echo $result->slider_order."_".$result->id ?>" onclick="change(this)">
							{{ $result->slider_order }}
						</span>
							<div id="change_div<?php echo $result->id ?>" style="display:none; ">
								{{ Form::text(
										'order_by', 
										$result->slider_order,
										['class'=>'span1','id'=>'order_by_'.$result->id]
									) 
								}}
								<a class="btn btn btn-success"  id="link_<?php echo $result->slider_order."_".$result->id ?>" onclick="order(this)"  href="javascript:void(0);">
									<i class="fa fa-check"></i>
								</a>
							</div>
					</td>
					<td  data-th='{{ trans("messages.$modelName.status") }}'>
						@if($result->is_active	== 1)
						<span class="label label-success" >{{ trans("messages.global.activated") }}</span>
						@else
						<span class="label label-warning" >{{ trans("messages.global.deactivated") }}</span>
						@endif
					</td>
					<td  data-th='{{ trans("messages.$modelName.action") }}'>
						@if($result->is_active)
							<a href='{{route("$modelName.status",array($result->is_active,0))}}'  class="status_user btn btn-warning btn-small">{{ trans("messages.global.inactive") }} </a>
						@else
							<a href='{{route("$modelName.status",array($result->is_active,1))}}'  class="status_user btn btn-success btn-small">{{ trans("messages.global.activate") }}</a>
						@endif
						<a href='{{route("$modelName.edit","$result->id")}}' class="btn btn-info btn-small">{{ trans("messages.global.edit") }} </a>
						<a href='{{route("$modelName.delete","$result->id")}}' data-delete="delete" class="delete_user btn btn-danger btn-small">{{ trans("messages.global.delete") }}</a>
					</td>
				</tr>
				<?php } ?>
			</tbody>
		</table>		
		<?php }else{ ?>
		<table class="mws-table mws-datatable details">	
			<tr>
				<td align="center" width="100%" > {{ trans("messages.global.no_record_found_message") }}</td>
			</tr>	
			<?php  } ?>
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
 
 // for update the orderby value
  function order(obj){
	
	id_array		=	obj.id.split("_");
	current_id		=	id_array[2]; 
	current_order	=	id_array[1]; 
	order_by		=	$("#order_by_"+current_id).val();
	$.ajax({
		type: "POST",
		url: "<?php  echo route('Slider.change_order'); ?>",
		data: { current_id: current_id,current_order: current_order,order_by: order_by },
		success : function(res){
			if(res.success != 1) {
				alert(res.message); return false; 
			}else{
			
			//$("#order_by_"+current_id).css({'border-color':'#CCCCCC'});
			$("#change_div"+current_id).hide();
			$("#link_"+current_order+"_"+current_id).html(res.order_by);
			$("#link_"+current_order+"_"+current_id).show();
				return true;
		}
	 }
	}) 
		return false; 
 }
 
</script>

@stop
