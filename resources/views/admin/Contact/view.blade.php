@extends('admin.layouts.default')

@section('content')

<div  class="mws-panel grid_8 mws-collapsible">
	<div class="mws-panel-header">
		<span>
			<i class="icol-status-online"></i> {{ trans("messages.$modelName.view_contact") }} 
		</span>
		<a href='{{ route("$modelName.index")}}' class="btn btn-success btn-small align">{{ trans("messages.global.back") }}  </a>
	</div>
	
	<div class="mws-panel-body no-padding dataTables_wrapper">
		<table class="mws-table mws-datatable">
			<tbody>
				<tr>
					<th width="30%" class="text-right">{{ trans("messages.$modelName.name") }}</th>
					<td data-th='{{ trans("messages.$modelName.name") }}'>{{ $model->name }}</td>
				</tr>
				<tr>
					<th width="30%" class="text-right">{{ trans("messages.$modelName.email") }}</th>
					<td data-th='{{ trans("messages.$modelName.email") }}'>{{ $model->email }}</td>
				</tr>
<!-- 				<tr>
					<th width="30%" class="text-right">{{ trans("messages.$modelName.subject") }}</th>
					<td data-th='{{ trans("messages.$modelName.subject") }}'>{{ $model->subject }}</td>
				</tr> -->
				<tr>
					<th width="30%" valign="top" class="text-right">{{ trans("messages.$modelName.message") }}</th>
					<td data-th='{{ trans("messages.$modelName.message") }}'>{{ $model->message }}</td>
				</tr>
				<tr>
					<th width="30%" class="text-right">{{ trans("messages.global.created") }}</th>
					<td data-th='{{ trans("messages.$modelName.created") }}'>{{ date(Config::get("Reading.date_format") , strtotime($model->created_at)) }}</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>

<!-- reply section start here -->
<div class="mws-panel grid_8" id="reply">
	<div class="mws-panel-header">
		<span>
			<i class="fa fa-exclamation-circle "></i>{{ trans("messages.$modelName.reply")}}
		</span>
	</div>
	<div class="mws-panel-body no-padding dataTables_wrapper">
			<span class="contactMsg"></span>
			{{ Form::open(['role' => 'form','url'=>route("$modelName.reply","$modelId"),'class' => 'mws-form']) }}
				<div class="mws-form-inline">
					<div class="mws-form-row">
						<div class="mws-form-message info">
							{{ trans("messages.$modelName.message_will_be_attached_in_email") }}
						</div>
					</div>
					<div class="mws-from-row contactMessageBox">
						{{  Form::label('body', trans("messages.$modelName.message"), ['class' => 'mws-form-label']) }}
						<div class="input textarea">
						{{ Form::textarea("message",'', ['id' => 'body','rows' => 5,'cols'=>80,'class'=>'form-control contactHeight','required']) }}
						</div>
						<div class="error-message help-inline">
							{{ $errors->first('message') }}
						</div>
					</div>
				</div>
				<div class="mws-button-row">
					<input type="submit" value='{{ trans("messages.$modelName.reply") }}' class="btn btn-danger">
						<a href="{{Request::url()}}" class="btn primary"><i class=\"icon-refresh\"></i> {{ trans("messages.global.reset") }} </a>
				</div>
			{{ Form::close() }}
		</div>
	</div>
<!-- reply section end here -->

@stop
