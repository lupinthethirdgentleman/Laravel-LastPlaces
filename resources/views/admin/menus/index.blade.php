@extends('admin.layouts.default')

@section('content')

<script type="text/javascript">
 var action_url = '<?php echo WEBSITE_URL; ?>admin/menus/multiple-action';
 </script>
{{ HTML::script('js/admin/multiple_delete.js') }}

<div id="mws-themer">
	<div id="mws-themer-content" style="<?php echo ($searchVariable) ? 'right: 256px;' : ''; ?>">
		<div id="mws-themer-ribbon"></div>
		<div id="mws-themer-toggle" class="<?php echo ($searchVariable) ? 'opened' : ''; ?>">
			<i class="icon-bended-arrow-left"></i> 
			<i class="icon-bended-arrow-right"></i>
		</div>
	
		{{ Form::open(['role' => 'form','url' => 'admin/menus/'.$type,'class' => 'mws-form']) }}
		{{ Form::hidden('display') }}
		<div class="mws-themer-section">
			<div id="mws-theme-presets-container" class="mws-themer-section">
				<label>{{ 'Menu Name' }}</label><br/>
				{{ Form::text('menu_name',((isset($searchVariable['menu_name'])) ? $searchVariable['menu_name'] : ''), ['class' => 'small']) }}
			</div>
		</div>
		<div class="mws-themer-separator"></div>
		<div class="mws-themer-section" style="height:0px">
			<ul><li class="clearfix"><span></span> <div id="mws-textglow-op"></div></li> </ul>
		</div>
		<div class="mws-themer-section">
			<input type="submit" value="{{ trans('messages.system_management.search') }}" class="btn btn-primary btn-small">
			<a href="{{URL::to('admin/menus/'.$type)}}"  class="btn btn-default btn-small">{{ trans('messages.system_management.reset') }}</a>
		</div>
		{{ Form::close() }}
	</div>
</div>


<div  class="mws-panel grid_8 mws-collapsible">
	<div class="mws-panel-header">
		<span>
			<i class="icon-table"></i> {{ ucfirst($type) . ' '.trans("messages.system_management.menus_list") }} </span>
		    <a href="{{URL::to('admin/menus/'.$type.'/add-menu')}}" class="btn btn-success btn-small align">{{ trans("messages.system_management.add") }} </a>
	</div>
<!--
	<div class="dataTables_wrapper" style="background-color:#CCCCCC">
		<div id="DataTables_Table_0_length" class="dataTables_length">
			<?php 
			$actionTypes	= array(
					'delete' 		=> trans("messages.global.delete_all"),
				 );
			?>
			{{ Form::open() }}
				{{ Form::checkbox('is_checked','',null,['class'=>'left checkAllUser'])}}
				{{ Form::select('action_type',array(''=>'Select Action')+$actionTypes,$actionTypes,['class'=>'deleteall selectUserAction'])}}
			{{ Form::close() }}
		</div>
	</div>
-->
	<div class="mws-panel-body no-padding dataTables_wrapper">
		<table class="mws-table mws-datatable">
			<thead>
				<tr>
					<th width="40%">
						{{
						link_to_route(
							'Menus.index',
							'Menu Name',
							array(
								'type'=>$type,
								'sortBy' => 'menu_name',
								'order' => ($sortBy == 'menu_name' && $order == 'desc') ? 'asc' : 'desc'
							),
						   array('class' => (($sortBy == 'menu_name' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'name' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
						)
					}}
					</th>
					<th>{{ 'Parent' }}</th>
					<th>{{ trans("messages.system_management.action") }}</th>
				</tr>
			</thead>
			<tbody id="powerwidgets">
				<?php
				
				if(!$result->isEmpty()){
				
				foreach($result as $key => $record){
					
				?>
				<tr class="inner-class">
<!--
					<td data-th='Select'>
					{{ Form::checkbox('status',$record->id,null,['class'=> 'userCheckBox'] )}}
					</td>
-->
					<td data-th='Menu Name'>{{ $record->menu_name }}</td>
					<td data-th='Parent'></td>
					
					<td data-th='{{ trans("messages.system_management.action") }}'>
						<a href="{{URL::to('admin/menus/edit-menu/'.$record->id.'/'.$type)}}" class="btn btn-info btn-small no-ajax">{{ trans("messages.system_management.edit") }} </a>
					</td>
				</tr>
				<?php } ?>
			</tbody>
		</table>

		<?php }else{ ?>
		<table class="mws-table mws-datatable details">	
			<tr>
				<td align="center" width="100%" > {{ trans("messages.system_management.no_record_found_message") }} </td>
			</tr>	
			<?php  } ?>
		</table>
	</div>

</div>
@stop
