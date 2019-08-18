@extends('admin.layouts.default')

@section('content')
 
{{HTML::script('js/bootstrap.min.js')}}

<!-- View user detail div  -->
<div  class="mws-panel grid_8 mws-collapsible">
	<div class="mws-panel-header" style="height:8%">
		<span>
			<i class="icon-table"></i> {{ trans("Company Details") }} 
		</span>
			
		<a href="{{URL::to('admin/list-company')}}" class="btn btn-success btn-small align" style="margin-left:5px"> {{ trans("messages.user_management.back") }}</a>
			
	</div>
	<div class="mws-panel-body no-padding dataTables_wrapper"> 
		<table class="mws-table mws-datatable">
			<tbody>
				<tr>
					<th class="text-right" width="30%">Company Name</th>
					<td data-th='Company Name'>{{ isset($compnayDetails->Name) ? $compnayDetails->Name:'' }}</td>
				</tr>
				<!-- <tr>
					<th class="text-right" width="30%">Location</th>
					<td data-th='Company Location'>{{ isset($compnayDetails->location) ? $compnayDetails->location : ''}}</td>
				</tr> -->
				<tr>
					<th class="text-right" width="30%">Image</th>
					<td data-th='First Name'>
					
							@if(isset($compnayDetails->image) && COMPANY_IMAGE_ROOT_PATH.$compnayDetails->image)
								{{ HTML::image( COMPANY_IMAGE_URL.$compnayDetails->image, $compnayDetails->image , array(  'height' => 70 )) }}
							@endif
					</td>
				</tr>

				<tr>
					<th class="text-right" width="30%">Status</th>
					@if(isset($compnayDetails->status) && !empty($compnayDetails->status) && $compnayDetails->status == 1 )
						<td data-th='Status'><span class="label label-success">Activated</span></td>
					@else
						<td data-th='Status'><span class="label label-warning">Deactivate</span></td>
					@endif
				</tr>
			</tbody>
		</table>
	</div>
</div>
<!--View user detail div end here -->

</div>

@stop
