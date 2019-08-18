@extends('admin.layouts.default')

@section('content')
 
{{HTML::script('js/bootstrap.min.js')}}

<!-- View user detail div  -->
<div  class="mws-panel grid_8 mws-collapsible">
	<div class="mws-panel-header" style="height:8%">
		<span>
			<i class="icon-table"></i> {{ trans("DA/HCP Details") }} 
		</span>
		
		<a href="{{URL::to('admin/list-hcp')}}" class="btn btn-success btn-small align" style="margin-left:5px"> {{ trans("messages.user_management.back") }}</a>
	</div>
	<div class="mws-panel-body no-padding dataTables_wrapper"> 
		<table class="mws-table mws-datatable">
			<tbody>
				<tr>
					<th class="text-right" width="30%">Company Name</th>
					<td data-th='Company Name'>{{ isset($companyDetails->Name) ? $companyDetails->Name:'' }}</td>
				</tr>
				<tr>
					<th class="text-right" width="30%">Company Location</th>
					<td data-th='Location'>{{ isset($companyDetails->location) ? $companyDetails->location : ''}}</td>
				</tr>
				<tr>
					<th class="text-right" width="30%">First Name</th>
					<td data-th='Location'>{{ isset($hcpDetails->first_name) ? $hcpDetails->first_name : ''}}</td>
				</tr><tr>
					<th class="text-right" width="30%">Middle Name</th>
					<td data-th='Location'>{{ isset($hcpDetails->middle_name) ? $hcpDetails->middle_name : ''}}</td>
				</tr>
				<tr>
					<th class="text-right" width="30%">Last Name</th>
					<td data-th='Location'>{{ isset($hcpDetails->last_name) ? $hcpDetails->last_name : ''}}</td>
				</tr><tr>
					<th class="text-right" width="30%">Profession</th>
					<td data-th='Location'>{{ isset($hcpDetails->profession) ? $hcpDetails->profession : ''}}</td>
				</tr>
				<tr>
					<th class="text-right" width="30%">Image</th>
					<td data-th='First Name'>
					
							@if(isset($hcpDetails->image) && HCP_IMAGE_ROOT_PATH.$hcpDetails->image)
								{{ HTML::image( HCP_IMAGE_URL.$hcpDetails->image, $hcpDetails->image , array( 'width' => 70 , 'height' => 200 )) }}
							@endif
					</td>
				</tr>

				<tr>
					<th class="text-right" width="30%">Created On</th>
					<td data-th='Created On'>{{ date(Config::get("Reading.date_format") , strtotime($hcpDetails->created_at)) }}</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
<!--View user detail div end here -->




</div>

@stop
