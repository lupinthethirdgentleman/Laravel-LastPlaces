@extends('admin.layouts.default')

{{ HTML::style('css/admin/styles.css') }}

{{ HTML::style('css/admin/lightbox.css') }}


@section('content')

<style>

.blueicon {
    color: #5bc0de;
}
.redicon {
    color: #d9534f;
}
.greenicon {
    color: #82b964;
}
</style>


<!-- Searching div -->  
<div id="mws-themer">
	<div id="mws-themer-content" style="<?php echo ($searchVariable) ? 'right: 256px;' : ''; ?>"> 
		<div id="mws-themer-ribbon"></div>
		<div id="mws-themer-toggle" class="<?php echo ($searchVariable) ? 'opened' : ''; ?>">
			<i class="icon-bended-arrow-left"></i> 
			<i class="icon-bended-arrow-right"></i>
		</div>
	
		{{ Form::open(['role' => 'form','url' => 'admin/users','class' => 'mws-form']) }}
		{{ Form::hidden('display') }}
		<div class="mws-themer-section">
			<div id="mws-theme-presets-container" class="mws-themer-section">
				<label>{{ trans("messages.user_management.filter_by_status") }}</label><br/>
				{{ Form::select('active',array(''=>trans('messages.user_management.please_select_status'),0=>'Inactive',1=>'Active'),((isset($searchVariable['active'])) ? $searchVariable['active'] : ''), ['class' => 'small']) }}
			</div>
		</div>
		
		<!-- <div class="mws-themer-section">
			<div id="mws-theme-presets-container" class="mws-themer-section">
				<label>{{ trans("messages.user_management.filter_by_user") }}</label><br/>
				{{ Form::text('full_name',((isset($searchVariable['full_name'])) ? $searchVariable['full_name'] : ''), ['class' => 'small']) }}
			</div>
		</div> -->
		<div class="mws-themer-section">
			<div id="mws-theme-presets-container" class="mws-themer-section">
				<label>{{ trans("messages.user_management.filter_by_email") }}</label><br/>
				{{ Form::text('email',((isset($searchVariable['email'])) ? $searchVariable['email'] : ''), ['class' => 'small']) }}
			</div>
		</div>
		<div class="mws-themer-separator"></div>
		<div class="mws-themer-section" style="height:0px">
			<ul><li class="clearfix"><span></span> <div id="mws-textglow-op"></div></li> </ul>
		</div>
		<div class="mws-themer-section">
			<input type="submit" value="{{ trans('messages.search.text') }}" class="btn btn-primary btn-small">
			<a href="{{URL::to('admin/users')}}"  class="btn btn-default btn-small">{{ trans("messages.reset.text") }}</a>
		</div>
		{{ Form::close() }}
	</div>
</div>
<!-- End here -->

<div class="mws-panel grid_8 mws-collapsible">
	<div class="mws-panel-header">
		<span>
			<i class="icon-table"></i> {{ trans("messages.user_management.users_text") }}
		</span>
		<a href="{{URL::to('admin/users/add-user')}}" class="btn btn-success btn-small align">{{ trans("messages.user_management.add_user") }} </a> 
	</div>
	<div class="dataTables_wrapper" style="background-color:#CCCCCC">
		<div id="DataTables_Table_0_length" class="dataTables_length">
			<?php 
			$actionTypes	= array(
					'delete' 		=> trans('messages.global.delete_all'),
					'inactive' 		=> trans('messages.global.mark_as_inactive'),
					'active' 		=> trans('messages.global.mark_as_active'),
					'verified' 		=> trans('messages.global.mark_as_verified'),
					'notverified' 	=> trans('messages.global.mark_as_not_verified'),
					
				 );
			?>
			<!--{{ Form::open() }}
				{{ Form::checkbox('is_checked','',null,['class'=>'checkAllUser'])}}
				{{ Form::select('action_type',array(''=>trans("messages.user_management.select_action"))+$actionTypes,$actionTypes,['class'=>'deleteall selectUserAction'])}}
			{{ Form::close() }}-->
		</div>
	</div>
	<div class="row" id="powerwidgets">
		<div class="col-md-12 bootstrap-grid"> 
			<div class="powerwidget col-grey" id="user-directory" data-widget-editbutton="false">
					<div id="items" class="items-switcher items-view-grid">
						<ul>
						<?php
			
						if(!$result->isEmpty()){
						
						foreach($result as $key => $record){

						?>
							<li class="eqaul-height">
								<div class="items-inner clearfix eqaul-height">									
										<!-- <h3 class="items-title">{{ $record->full_name }}</h3> -->
										@if($record->active	==1)
											<span class="label label-success" >{{ trans("messages.user_management.activated") }}</span>
										@else
											<span class="label label-warning" >{{ trans("messages.user_management.deactivated") }}</span>
										@endif
										@if($record->is_verified	==1)
											<span class="label label-success" >{{ trans("messages.user_management.verified") }}</span>
										@else
											<span class="label label-warning" >{{ trans("messages.user_management.not_verified") }}</span>
										@endif
										
									<div class="items-details">
										<strong>{{ trans("messages.user_management.first_name") }}:</strong>
												{{ $record->first_name }}
											<br />
										<strong>{{ trans("messages.user_management.last_name") }}:</strong>
												{{ $record->last_name }}
											<br />
										<strong>{{ trans("messages.user_management.email") }}:</strong> 
											<a href="mailto:{{ $record->email }}" class="redicon">
												{{ $record->email }}
											</a>
											<br />
											<!-- <strong>{{ trans("messages.user_management.user_register_date") }}:</strong> 
											{{ date(Config::get("Reading.date_format") , strtotime($record->created_at)) }} -->
											<!-- <br/>
											<strong>Last Login:</strong> 
											@if($record->user_last_login['created_at']!='')
												{{ date(Config::get('Reading.date_format'),strtotime($record->user_last_login['created_at'])) }}
											@else
												{{ 'User not login'}}
											@endif -->
									</div>
									<div class="control-buttons img" style="margin-bottom:0px;"> 
										<!-- @if($record->active)
											<a href="{{URL::to('admin/users/update-status/'.$record->id.'/0')}}" class="status_user"      title="{{ trans('messages.global.mark_as_inactive') }}">
												<i class="fa fa-ban  redicon"></i>
											</a>
										@else
											<a href="{{URL::to('admin/users/update-status/'.$record->id.'/1')}}" class="status_user"
											title="{{ trans('messages.global.mark_as_active') }}"
											>
												<i class="fa fa-check greenicon"></i> 
											</a>
										@endif -->
										
										<a href="{{URL::to('admin/users/view-user/'.$record->id)}}" title="{{ trans('messages.global.view') }}">
											<i class="fa fa-eye blueicon"></i>
										</a>
											
										<a title="{{ trans('messages.global.edit') }}" href="{{URL::to('admin/users/edit-user/'.$record->id)}}" >
											<i class="fa fa-cog greenicon"></i>
										</a>
										
										<a title="{{ trans('messages.global.delete') }}" href="{{ URL::to('admin/users/delete-user/'.$record->id) }}"  class="delete_user">
											<i class="fa fa-times redicon"></i>
										</a>
									
										<!-- <a title="{{ trans('messages.user_management.send_login_credentials') }}" href="{{ URL::to('admin/users/send-credential/'.$record->id) }}">
											<i class="fa fa-share greenicon"></i>
										</a> -->
										
									</div>
								</div>
							</li>
						<?php } 
						}else{?>
						<table class="mws-table mws-datatable details">	
							<tbody>
								<tr>
									<td align="center">{{ trans("messages.user_management.no_record_found_message") }}</td>
								</tr>
							</tbody>
						</table>
						<?php } ?>
						</ul>
					<!-- End Widget --> 
					</div>
			<!-- /Inner Row Col-md-12 --> 
			</div>
		</div>
		
	</div>
	@include('pagination.default', ['paginator' => $result])	
</div>

{{ HTML::script('js/admin/lightbox.js') }}
<!-- js for equal height of the div  -->
{{ HTML::script('js/admin/jquery.matchHeight-min.js') }}

<script type="text/javascript">
	
var action_url = '<?php echo WEBSITE_URL; ?>admin/list-hcf/multiple-action';
 /* for equal height of the div */	
 
$(function() {
	$('.eqaul-height').matchHeight();
});

 </script>
{{ HTML::script('js/admin/multiple_delete.js') }}

@stop
