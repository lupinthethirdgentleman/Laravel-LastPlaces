@extends('admin.layouts.default')

@section('content')

<div id="mws-themer">
	<div id="mws-themer-content" style="<?php echo ($searchVariable) ? 'right: 256px;' : ''; ?>">
		<div id="mws-themer-ribbon"></div>
		<div id="mws-themer-toggle" class="<?php echo ($searchVariable) ? 'opened' : ''; ?>">
			<i class="icon-bended-arrow-left"></i> 
			<i class="icon-bended-arrow-right"></i>
		</div>
	
		{{ Form::open(['role' => 'form','route' => "$modelName.index",'class' => 'mws-form']) }}
		{{ Form::hidden('display') }}
		
		<div class="mws-themer-section">
			<div id="mws-theme-presets-container" class="mws-themer-section">
				<label>{{ trans("messages.$modelName.name") }}</label><br/>
				{{ Form::text('name',((isset($searchVariable['name'])) ? $searchVariable['name'] : ''), ['class' => 'small']) }}
			</div>
		</div>
		
		<div class="mws-themer-section">
			<div id="mws-theme-presets-container" class="mws-themer-section">
				<label>{{ trans("messages.$modelName.email") }}</label><br/>
				{{ Form::text('email',((isset($searchVariable['email'])) ? $searchVariable['email'] : ''), ['class' => 'small']) }}
			</div>
		</div>
		
<!-- 		<div class="mws-themer-section">
			<div id="mws-theme-presets-container" class="mws-themer-section">
				<label>{{ trans("messages.$modelName.subject") }}</label><br/>
				{{ Form::text('subject',((isset($searchVariable['subject'])) ? $searchVariable['subject'] : ''), ['class' => 'small']) }}
			</div>
		</div> -->
		
		<div class="mws-themer-section">
			<div id="mws-theme-presets-container" class="mws-themer-section">
				<label>{{ trans("messages.$modelName.message") }}</label><br/>
				{{ Form::text('message',((isset($searchVariable['message'])) ? $searchVariable['message'] : ''), ['class' => 'small']) }}
			</div>
		</div>
		
		<div class="mws-themer-section" style="height:0px">
			<ul><li class="clearfix"><span></span> <div id="mws-textglow-op"></div></li> </ul>
		</div>
		
		<div class="mws-themer-section">
			<input type="submit" value='{{ trans("messages.global.search") }}' class="btn btn-primary btn-small">
			<a href='{{ route("$modelName.index")}}'  class="btn btn-default btn-small">{{ trans("messages.global.reset") }}</a>
		</div>
		
		{{ Form::close() }}
		
	</div>
</div>

<div  class="mws-panel grid_8 mws-collapsible">
	<div class="mws-panel-header">
		<span>
			<i class="icon-table"></i> {{ trans("messages.$modelName.table_heading_index") }} 
		</span>
	</div>
	<div class="dataTables_wrapper" style="background-color:#CCCCCC">
		<div id="DataTables_Table_0_length" class="dataTables_length">

		</div>
	</div>
	<div class="mws-panel-body no-padding dataTables_wrapper">
		<table class="mws-table mws-datatable">
			<thead>
				<tr>
					<th width="16%">
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
					<th width="25%">
						{{
							link_to_route(
							"$modelName.index",
							trans("messages.$modelName.email"),
							array(
								'sortBy' => 'email',
								'order' => ($sortBy == 'email' && $order == 'desc') ? 'asc' : 'desc'
							),
							array('class' => (($sortBy == 'email' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'email' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
							)
						}}
					</th>
					<!-- <th width="20%">{{ trans("messages.$modelName.subject") }}</th> -->
					<th width="24%">{{ trans("messages.$modelName.message") }}</th>
					<th>{{ trans("messages.global.action") }}</th>
				</tr>
			</thead>
			<tbody>
				<?php
				if(!$model->isEmpty()){
				foreach($model as $result){?>
				<tr>
					<td data-th='{{ trans("messages.$modelName.name") }}'>{{ $result->name }}</td>
					<td data-th='{{ trans("messages.$modelName.email") }}'><a href="mailTo: {{ $result->email }} "> {{ $result->email }} </a></td>
				<!-- 	<td data-th='{{ trans("messages.$modelName.subject") }}'> {{ $result->subject }} </td> -->
					<td data-th='{{ trans("messages.$modelName.message") }}'>{{ strip_tags(Str::limit( $result->message, 300)) }}</td>
					<td data-th='{{ trans("messages.$modelName.action") }}'>
						<a href='{{ route("$modelName.view","$result->id")}}' class="btn btn-inverse btn-small">{{ trans("messages.$modelName.view") }} </a>

						<a href='{{ route("$modelName.view","$result->id")}}#reply' data-delete="delete" class="btn btn-danger btn-small no-ajax">{{ trans("messages.contact.reply") }} </a>
					</td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
		<?php }else{ ?>
		<table class="mws-table mws-datatable details">	
			<tr>
				<td align="center" width="100%" >  {{ trans("messages.global.no_record_found_message") }} </td>
			</tr>	
			<?php  } ?>
		</table>
	</div>
	@include('pagination.default', ['paginator' => $model])
</div>
@stop
