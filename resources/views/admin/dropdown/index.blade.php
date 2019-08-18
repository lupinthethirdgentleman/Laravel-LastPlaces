@extends('admin.layouts.default')

@section('content')

<script type="text/javascript">
	// using this variable in multiple-delete.js file  
 var action_url = '<?php echo WEBSITE_URL; ?>admin/dropdown-manager/multiple-action/$type';
</script>
{{ HTML::script('js/admin/multiple_delete.js') }}



<div id="mws-themer">
	<div id="mws-themer-content" style="<?php echo ($searchVariable) ? 'right: 256px;' : ''; ?>">
		<div id="mws-themer-ribbon"></div>
		<div id="mws-themer-toggle" class="<?php echo ($searchVariable) ? 'opened' : ''; ?>">
			<i class="icon-bended-arrow-left"></i> 
			<i class="icon-bended-arrow-right"></i>
		</div>
	
		{{ Form::open(['method' => 'get','role' => 'form','url' => 'admin/dropdown-manager/'.$type,'class' => 'mws-form']) }}
		{{ Form::hidden('display') }}
		<div class="mws-themer-section">
			<div id="mws-theme-presets-container" class="mws-themer-section">
				<label>{{ 'Name' }}</label><br/>
				{{ Form::text('name',((isset($searchVariable['name'])) ? $searchVariable['name'] : ''), ['class' => 'small']) }}
			</div>
		</div>
		<div class="mws-themer-separator"></div>
		<div class="mws-themer-section" style="height:0px">
			<ul><li class="clearfix"><span></span> <div id="mws-textglow-op"></div></li> </ul>
		</div>
		<div class="mws-themer-section">
			<input type="submit" value="Search" class="btn btn-primary btn-small">
			<a href="{{URL::to('admin/dropdown-manager/'.$type)}}"  class="btn btn-default btn-small">Reset</a>
		</div>
		{{ Form::close() }}
	</div>
</div>

<div  class="mws-panel grid_8 mws-collapsible">
	<div class="mws-panel-header">
		<span>
			<i class="icon-table"></i> {{ studly_case($type) }} </span>
			<!-- <a href="{{URL::to('admin/dropdown-manager/add-dropdown/'.$type)}}" class="btn btn-success btn-small align">{{ 'Add New '.studly_case($type) }} </a> -->
	</div>
	<div class="dataTables_wrapper" style="background-color:#CCCCCC">
		<div id="DataTables_Table_0_length" class="dataTables_length">
				<?php 
			$actionTypes	= array(
					'delete' 		=> 'Delete',
				 );
			?>
			<!-- {{ Form::open() }}
				{{ Form::checkbox('is_checked','',null,['class'=>'left checkAllUser'])}}
				{{ Form::select('action_type',array(''=>'Select Action')+$actionTypes,$actionTypes,['class'=>'deleteall selectUserAction'])}}
			{{ Form::close() }} -->
		</div>
	</div>
	<div class="mws-panel-body no-padding dataTables_wrapper">
		<table class="mws-table mws-datatable">
			<thead>
				<tr>
					<!-- <th></th> -->
					<th width="40%">
						{{
							link_to_route(
								'DropDown.listDropDown',
								'Name',
								array(
									$type,
									'sortBy' => 'name',
									'order' => ($sortBy == 'name' && $order == 'desc') ? 'asc' : 'desc'
								),
							   array('class' => (($sortBy == 'name' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'name' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
							)
						}}
					</th>
					
					<th width="30%">
						{{
							link_to_route(
								'DropDown.listDropDown',
								'Created ',
								array(
									$type,
									'sortBy' => 'created_at',
									'order' => ($sortBy == 'created_at' && $order == 'desc') ? 'asc' : 'desc'
								),
							   array('class' => (($sortBy == 'created_at' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'created_at' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
							)
						}}
					</th>
					<th>{{ 'Action' }}</th>
				</tr>
			</thead>
			<tbody id="powerwidgets">
				@if(!$result->isEmpty())
				@foreach($result as $record)
				<tr class="items-inner">
					<!-- <td data-th="select">{{ Form::checkbox('status',$record->id,null,['class'=> 'userCheckBox'] )}}</td> -->
					<td data-th='Name'>{{ $record->name }}</td>
					<td data-th='Created At'>{{ date(Config::get("Reading.date_format") , strtotime($record->created_at)) }}</td>
					<td data-th='Action'>						
						<a href="{{URL::to('admin/dropdown-manager/edit-dropdown/'.$record->id.'/'.$type)}}" class="btn btn-info btn-small">{{ 'Edit' }} </a>
						<!-- <a href="{{URL::to('admin/dropdown-manager/delete-dropdown/'.$record->id.'/'.$type)}}"  class="delete_user btn btn-danger btn-small">{{ 'Delete' }} </a> -->
					</td>
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
	@include('pagination.default', ['paginator' => $result])
</div>
@stop
