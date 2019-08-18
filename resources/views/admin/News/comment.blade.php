@extends('admin.layouts.default')
@section('content')

{{ HTML::script('js/admin/multiple_delete.js') }}
{{ HTML::style('css/admin/lightbox.css') }}

<div class="mws-panel grid_8 mws-collapsible">
	<!-- table heading and add new button-->
	<div class="mws-panel-header">
		<span>
		<i class="icon-table"></i> {{ trans("messages.$modelName.table_heading_comment") }} </span>
		
	</div>	
	
	<!-- table secttion start here-->
	<div class="mws-panel-body no-padding dataTables_wrapper">

		<table class="mws-table mws-datatable">
			<thead>
				<tr>
					<!-- <th></th>  -->
					<th width="30%">
					{{ trans("messages.$modelName.name") }}	
										
					</th>
					<th>{{ trans("messages.$modelName.email") }}</th>

					<th width="30%">{{ trans("messages.$modelName.comment") }}</th>
					
					<th>{{ trans("messages.global.action") }}</th>
				</tr>
			</thead>
			<tbody id="powerwidgets">
						<?php
						
						if(!$model->isEmpty())
						{
							foreach($model as $result){ ?>

							<tr class="items-inner">
								
								<td data-th='{{ trans("messages.$modelName.full_name") }}'>
									{{ strip_tags(Str::limit($result->full_name, 120)) }}
								</td>
								
								<td data-th='{{ trans("messages.$modelName.email") }}'>
									{{ strip_tags(Str::limit($result->email, 250)) }}
								</td>
								<td data-th='{{ trans("messages.$modelName.comment") }}'>
									{{ strip_tags(Str::limit($result->comment, 120)) }}
								</td>
								<td  data-th='{{ trans("messages.global.action") }}'>
									
									<a href='{{route("$modelName.delete_comment","$result->id")}}' class="delete_user btn btn-danger btn-small no-ajax ">{{ trans("messages.global.delete") }} </a>
									
								</td>
								
							</tr>
					
				<?php }} else { ?>
					<tr>
							<td align="center" width="100%" > {{ trans("messages.global.no_record_found_message") }}</td>
						</tr>
				<?php }?>
			</tbody>
		</table>
	</div>
	@include('pagination.default', ['paginator' => $model])
</div>

@stop
