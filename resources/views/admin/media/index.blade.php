@extends('admin.layouts.default')

@section('content')

<script type="text/javascript">
	var action_url = '<?php echo route("$modelName.Multipleaction"); ?>';
</script>

{{ HTML::script('js/admin/multiple_delete.js') }}
{{ HTML::style('css/admin/lightbox.css') }}

<style>
	.span1{
		margin-right:10px;
		width:60px;
	}
</style>

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
					<label>{{ trans("messages.$modelName.title") }}</label><br/>
					{{ Form::text('title',((isset($searchVariable['title'])) ? $searchVariable['title'] : ''), ['class' => 'small']) }}
				</div>
			</div>
			<div class="mws-themer-separator"></div>
			<div class="mws-themer-section" style="height:0px">
				<ul><li class="clearfix"><span></span> <div id="mws-textglow-op"></div></li> </ul>
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
			<a href='{{route("$modelName.add")}}'  class="btn btn-success btn-small align">{{ trans("messages.$modelName.add_new") }} </a>
		</span>
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
				{{ Form::select('action_type',array(''=>trans('messages.global.select_action'))+$actionTypes,$actionTypes,['class'=>'deleteall selectUserAction'])}}
			{{ Form::close() }}
		</div>
	</div>
	
	<div class="mws-panel-body no-padding dataTables_wrapper">
		<table class="mws-table mws-datatable">
			<thead>
				<tr>
					<th></th>
					<th width="15%">
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
					<th width="18%">
						{{
							link_to_route(
							"$modelName.index",
							trans("messages.$modelName.logo"),
								array(
									'sortBy' => 'logo',
									'order' => ($sortBy == 'logo' && $order == 'desc') ? 'asc' : 'desc'
								),
							   array('class' => (($sortBy == 'logo' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'logo' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
							)
						}}
					</th>
				
					<th width="20%">
						{{
							link_to_route(
								"$modelName.index",
								trans("messages.$modelName.order_by"),
								array(
									'sortBy' => 'order_by',
									'order' => ($sortBy == 'order_by' && $order == 'desc') ? 'asc' : 'desc'
								),
							   array('class' => (($sortBy == 'order_by' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'order_by' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
							)
						}}
					</th>
					<th>
						{{
							link_to_route(
								"$modelName.index",
								trans("messages.$modelName.created_at"),
								array(
									'sortBy' => 'order_by',
									'order' => ($sortBy == 'created_at' && $order == 'desc') ? 'asc' : 'desc'
								),
							   array('class' => (($sortBy == 'created_at' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'created_at' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
							)
						}}
					</th>
					<th>{{ trans("messages.global.status") }}</th>
					<th>{{ trans("messages.global.action") }}</th>
				</tr>
			</thead>
			<tbody id="powerwidgets">
				@if(!$model->isEmpty())
					@foreach($model as $result)
						<tr class="items-inner">
							<td data-th="select">{{ Form::checkbox('status',$result->id,null,['class'=> 'userCheckBox'] )}}</td>
							<td data-th='{{ trans("messages.$modelName.title") }}'>{{ $result->title }}</td>
							<td data-th='{{ trans("messages.$modelName.logo") }}'>	
								@if ((File::exists(MEDIA_IMAGE_ROOT_PATH.$result->logo))&&$result->logo!='') 
								<a class="items-image" data-lightbox="roadtrip<?php echo $result->logo; ?>" href="<?php echo MEDIA_IMAGE_URL.$result->logo; ?>">
									<img src="<?php echo WEBSITE_URL .'image.php?width=120px&height=140px&image=' . MEDIA_IMAGE_URL.$result->logo	;?>" /> 
								</a>
								@else
									{{ 'No Image' }}
								@endif
							</td>
							<td data-th='{{ trans("messages.$modelName.order_by	") }}'>
								<div style="color:#0088CC;cursor:pointer;text-align:center;" id="link_<?php echo $result->order_by."_".$result->id ?>" onclick="change(this)">
									{{ $result->order_by }}
								</div>
								<div id="change_div<?php echo $result->id ?>" style="display:none; ">
									{{ Form::text(
											'order_by', 
											$result->order_by,
											['class'=>'span1','id'=>'order_by_'.$result->id]
										) 
									}}
									<a class="btn btn btn-success"  id="link_<?php echo $result->order_by."_".$result->id ?>" onclick="order(this)"  href="javascript:void(0);">
										<i class="fa fa-check"></i>
									</a>
								</div>
							</td>
							<td data-th='{{ trans("messages.$modelName.created_at") }}'>{{ date(Config::get('Reading.date_format'),strtotime($result->created_at)) }}</td>
							<td data-th='{{ trans("messages.$modelName.status") }}'>
								@if($result->is_active ==1)
									<span class="label label-success">{{ trans("messages.global.activate") }}</span>
								@else
									<span class="label label-warning">{{ trans("messages.global.deactivate") }}</span>
								@endif
							</td>
							<td data-th='{{ trans("messages.global.action") }}'>
								@if($result->is_active)
									<a href='{{route("$modelName.status",INACTIVE)}}' class="status_user btn btn-warning btn-small ">{{ trans("messages.global.activated") }}</a>
								@else
									<a href='{{route("$modelName.status",ACTIVE)}}' class="status_user btn btn-success btn-small">{{ trans("messages.global.deactivated") }} </a>
								@endif
									<a href='{{route("$modelName.delete","$result->id")}}' data-delete="delete" class="delete_user btn btn-danger btn-small">{{ trans("messages.global.delete") }}</a>
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
	
</div>

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
		url: "<?php  echo route("$modelName.order"); ?>",
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
