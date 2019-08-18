@extends('admin.layouts.default')

@section('content')
 
{{HTML::script('js/bootstrap.min.js')}}

<!-- View user detail div  -->
<div  class="mws-panel grid_8 mws-collapsible">
	<div class="mws-panel-header" style="height:8%">
		<span>
			<i class="icon-table"></i> {{ trans("messages.user_management.user_detail") }} 
		</span>
			
			<a href="{{URL::to('admin/users')}}" class="btn btn-success btn-small align" style="margin-left:5px"> {{ trans("messages.user_management.back") }}</a>
			<a href="{{URL::to('admin/users/edit-user/'.$userDetails->id)}}" class="btn btn-primary btn-small align">{{ trans("messages.user_management.edit") }}</a>
	</div>

	<div class="mws-panel-body no-padding dataTables_wrapper"> 
		<table class="mws-table mws-datatable">
			<tbody>
				<tr>
					<th class="text-right" width="30%">Full Name</th>
					<td data-th='Full Name'>{{ ucfirst($userDetails->first_name) }} {{ ucfirst($userDetails->last_name) }}</td>
				</tr>
				<tr>
					<th class="text-right" width="30%">Email</th>
					<td data-th='Email'><a href="mailTo:{{ $userDetails->email }}">{{ $userDetails->email }}</a></td>
				</tr>
				<tr>
					<th class="text-right" width="30%">Phone</th>
					<td data-th='Phone'>{{ $userDetails->phone }}</td>
				</tr>
				<tr>
					<th class="text-right" width="30%">Created On</th>
					<td data-th='Created On'>{{ date(Config::get("Reading.date_format") , strtotime($userDetails->created_at)) }}</td>
				</tr>
				
			</tbody>
		</table>
	</div>
</div>
<!--View user detail div end here -->




</div>

@stop
