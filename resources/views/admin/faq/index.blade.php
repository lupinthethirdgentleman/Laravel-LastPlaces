@extends('admin.layouts.default')

@section('content')


<script type="text/javascript">
 var action_url = '<?php echo WEBSITE_URL; ?>admin/faqs-manager/multiple-action';
 </script>
{{ HTML::script('js/admin/multiple_delete.js') }}

<div id="mws-themer">
	<div id="mws-themer-content" style="<?php echo ($searchVariable) ? 'right: 256px;' : ''; ?>">
		<div id="mws-themer-ribbon"></div>
		<div id="mws-themer-toggle" class="<?php echo ($searchVariable) ? 'opened' : ''; ?>">
			<i class="icon-bended-arrow-left"></i> 
			<i class="icon-bended-arrow-right"></i>
		</div>
	
		{{ Form::open(['method' => 'get','role' => 'form','url' => 'admin/faqs-manager','class' => 'mws-form']) }}
		{{ Form::hidden('display') }}
		<div class="mws-form-row ">
			<!-- {{  Form::label('topic', 'Topic', ['class' => 'mws-form-label']) }} -->
			<div class="mws-form-item">

			</div>
		</div>
		<div class="mws-themer-section">
			<div id="mws-theme-presets-container" class="mws-themer-section">
				<label>{{ trans("messages.system_management.question") }}</label><br/>
				{{ Form::text('question',((isset($searchVariable['question'])) ? $searchVariable['question'] : ''), ['class' => 'small']) }}
			</div>
		</div>
		<div class="mws-themer-separator"></div>
		<div class="mws-themer-section">
			<div id="mws-theme-presets-container" class="mws-themer-section">
				<label>{{ trans("messages.system_management.answer") }}</label><br/>
				{{ Form::text('answer',((isset($searchVariable['answer'])) ? $searchVariable['answer'] : ''), ['class' => 'small']) }}
			</div>
		</div>
		<div class="mws-themer-separator"></div>
		<div class="mws-themer-section" style="height:0px">
			<ul><li class="clearfix"><span></span> <div id="mws-textglow-op"></div></li> </ul>
		</div>
		<div class="mws-themer-section">
			<input type="submit" value='{{ trans("messages.system_management.reset")  }}' class="btn btn-primary btn-small">
			<a href="{{URL::to('admin/faqs-manager')}}"  class="btn btn-default btn-small">{{ trans("messages.system_management.reset") }}</a>
		</div>
		{{ Form::close() }}
	</div>
</div>

<div  class="mws-panel grid_8 mws-collapsible">
	<div class="mws-panel-header">
		<span>
			<i class="icon-table"></i>{{ trans("messages.system_management.manage_faq") }}</span>
			<a href="{{URL::to('admin/faqs-manager/add-faqs')}}" class="btn btn-success btn-small align">{{ trans("messages.system_management.add_faq") }} </a>
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
			<!-- {{ Form::open() }}
				{{ Form::checkbox('is_checked','',null,['class'=>'left checkAllUser'])}}
				{{ Form::select('action_type',array(''=> trans("messages.user_management.select_action") )+$actionTypes,$actionTypes,['class'=>'deleteall selectUserAction'])}}
			{{ Form::close() }} -->
			
		</div>
	</div>
	
	<div class="mws-panel-body no-padding dataTables_wrapper">
		<table class="mws-table mws-datatable">
			<thead>
				<tr>
					<!-- <th></th> -->
					<th width="25%">
						{{
							link_to_route(
							'Faq.listFaq',
							trans("messages.system_management.question"),
							array(
								'sortBy' => 'question',
								'order' => ($sortBy == 'question' && $order == 'desc') ? 'asc' : 'desc'
							),
							array('class' => (($sortBy == 'question' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'question' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
							)
						}}
					</th>
					<th width="30%">{{
							link_to_route(
							'Faq.listFaq',
							trans("messages.system_management.answer"),
							array(
								'sortBy' => 'answer',
								'order' => ($sortBy == 'answer' && $order == 'desc') ? 'asc' : 'desc'
							),
							array('class' => (($sortBy == 'answer' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'answer' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
							)
						}}
					</th>
					<th>{{ 'Category Name' }}</th>
					<th>{{ trans("messages.system_management.action") }}</th>
				</tr>
			</thead>
			<tbody id="powerwidgets">
				@if(!$result->isEmpty())
				@foreach($result as $record)
					<tr>
						<!-- <td data-th="{{ trans('messages.system_management.select') }}">
						{{ Form::checkbox('status',$record->id,null,['class'=> 'userCheckBox'] )}}
						</td> -->
						<td data-th="{{ trans('messages.system_management.question') }}">{{ $record->question }}</td>
						<td data-th="{{ trans('messages.system_management.answer') }}">{{ strip_tags(Str::limit($record->answer, 220)) }}</td>
						<td data-th='Category Name '>{{ $record->category->name }}</td>
							<td data-th="{{ trans('messages.system_management.action') }}">
							@if($record->is_active)
								<a href="{{URL::to('admin/faqs-manager/update-status/'.$record->id.'/0')}}" class="status_user btn btn-success btn-small">{{ trans("messages.system_management.inactivate") }}</a>
							@else
								<a href="{{URL::to('admin/faqs-manager/update-status/'.$record->id.'/1')}}" class="status_user btn btn-warning btn-small">{{ trans("messages.system_management.activate") }} </a>
							@endif
							<a href="{{URL::to('admin/faqs-manager/edit-faqs/'.$record->id)}}" class="btn btn-info btn-small">{{ trans("messages.system_management.edit") }} </a>
							<a href="{{URL::to('admin/faqs-manager/view-faqs/'.$record->id)}}" class="btn btn-inverse btn-small">{{ trans("messages.system_management.view") }} </a>
							
							<a href="{{URL::to('admin/faqs-manager/delete-faqs/'.$record->id)}}"  class="delete_user btn btn-danger btn-small no-ajax ">{{ trans("messages.system_management.delete") }} </a>
						</td>
					</tr>
				 @endforeach  
			</tbody>
		</table>
		@else
		<table class="mws-table mws-datatable details">	
			<tr>
				<td align="center" width="100%" > {{ trans("messages.system_management.no_record_found_message") }}</td>
			</tr>	
			@endif 
		</table>
	</div>
	@include('pagination.default', ['paginator' => $result])
</div>
@stop
