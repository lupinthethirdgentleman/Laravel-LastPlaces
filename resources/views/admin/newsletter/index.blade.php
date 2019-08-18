@extends('admin.layouts.default')

@section('content')

<!--popup  script  and  css start here-->
{{HTML::script('js/admin/libs/jquery-1.8.3.min.js')}}
{{HTML::script('js/admin/jui/js/jquery-ui-1.9.2.min.js')}}
{{HTML::script('js/admin/demo.widget.js')}}

{{HTML::Style('css/admin/jui/css/jquery.ui.all.css')}}
{{HTML::Style('css/admin/mws-theme.css')}}
<!--popup  script  and  css end here-->

<script type="text/javascript">
/* For view subscrieber */
$(function(){
	$(".view-subscrieber").bind("click", function (event) {
		 id	=	$(this).attr('id');
		
		 $.post('<?php echo URL::to('admin/news-letter/view-subscriber')?>/'+id,id, function(r) {
			$("#body").html(r);
				$("#view-subscrieber-dialog").dialog("option", {
					modal: true
				}).dialog("open");
		});
		
		event.preventDefault();
	});
	/* For Delete subscrieber */
	$('[data-delete]').click(function(e){
		
	     e.preventDefault();
		// If the user confirm the delete
		if (confirm('Do you really want to delete the element ?')) {
			// Get the route URL
			var url = $(this).prop('href');
			// Get the token
			var token = $(this).data('delete');
			// Create a form element
			var $form = $('<form/>', {action: url, method: 'post'});
			// Add the DELETE hidden input method
			var $inputMethod = $('<input/>', {type: 'hidden', name: '_method', value: 'delete'});
			// Add the token hidden input
			var $inputToken = $('<input/>', {type: 'hidden', name: '_token', value: token});
			// Append the inputs to the form, hide the form, append the form to the <body>, SUBMIT !
			$form.append($inputMethod, $inputToken).hide().appendTo('body').submit();
		} 
	});
});
</script>

<div id="mws-themer">
	<div id="mws-themer-content" style="<?php echo ($searchVariable) ? 'right: 256px;' : ''; ?>">
		<div id="mws-themer-ribbon"></div>
		<div id="mws-themer-toggle" class="<?php echo ($searchVariable) ? 'opened' : ''; ?>">
			<i class="icon-bended-arrow-left"></i> 
			<i class="icon-bended-arrow-right"></i>
		</div>
	
		{{ Form::open(['method' => 'get','role' => 'form','url' => 'admin/news-letter','class' => 'mws-form']) }}
		{{ Form::hidden('display') }}
		<div class="mws-themer-section">
			<div id="mws-theme-presets-container" class="mws-themer-section">
				<label>{{ trans("messages.system_management.subject") }}</label><br/>
				{{ Form::text('subject',((isset($searchVariable['subject'])) ? $searchVariable['subject'] : ''), ['class' => 'small']) }}
			</div>
		</div>
		<div class="mws-themer-separator"></div>
		<div class="mws-themer-section" style="height:0px">
			<ul><li class="clearfix"><span></span> <div id="mws-textglow-op"></div></li> </ul>
		</div>
		<div class="mws-themer-section">
			<input type="submit" value="{{ trans('messages.system_management.search') }}" class="btn btn-primary btn-small">
			<a href="{{URL::to('admin/news-letter')}}"  class="btn btn-default btn-small">{{ trans('messages.system_management.reset') }}</a>
		</div>
		{{ Form::close() }}
	</div>
</div>


<div id="view-subscrieber-dialog" style="display:none; padding:10px 0px">
	<div class="mws-panel-body no-padding dataTables_wrapper" id="body">
		
	</div>
</div>

<div  class="mws-panel grid_8 mws-collapsible">
	<div class="mws-panel-header">
		<span><i class="icon-table"></i> {{ trans('messages.system_management.newsletter') }}</span>
			<a href="{{URL::to('admin/news-letter/newsletter-templates')}}" class="btn btn-success btn-small align">{{ trans("messages.system_management.back") }} </a>	
	</div>
	<div class="dataTables_wrapper" style="background-color:#CCCCCC">
		<div id="DataTables_Table_0_length" class="dataTables_length">
			<?php //echo $this->element('paging_info'); ?>
		</div>
	</div>
	<div class="mws-panel-body no-padding dataTables_wrapper">
		<table class="mws-table mws-datatable">
			<thead>
				<tr>
					<th width="35%">
					{{
						link_to_route(
							'NewsLetter.listTemplate',
							trans("messages.system_management.subject"),
							array(
								'sortBy' => 'subject',
								'order' => ($sortBy == 'subject' && $order == 'desc') ? 'asc' : 'desc'
							),
						   array('class' => (($sortBy == 'subject' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'subject' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
						)
					}}
					</th>
					<th width="25%">{{
						link_to_route(
							'NewsLetter.listTemplate',
							trans("messages.system_management.scheduled_time"),
							array(
								'sortBy' => 'scheduled_time',
								'order' => ($sortBy == 'scheduled_time' && $order == 'desc') ? 'asc' : 'desc'
							),
						   array('class' => (($sortBy == 'scheduled_time' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'scheduled_time' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
						)
					}}</th>
					<th width="15%">
					{{
						link_to_route(
							'NewsLetter.listTemplate',
							trans("messages.system_management.created"),
							array(
								'sortBy' => 'created_at',
								'order' => ($sortBy == 'created_at' && $order == 'desc') ? 'asc' : 'desc'
							),
						   array('class' => (($sortBy == 'created_at' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'created_at' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
						)
					}}
	                </th>
					<th>{{ trans("messages.system_management.action") }}</th>
				</tr>
			</thead>
			<tbody>
				@if(!$result->isEmpty())
				@foreach($result as $record)
				<tr>
					<td data-th='{{ trans("messages.system_management.subject") }}'>{{ $record->subject }}</td>
					<td data-th='{{ trans("messages.system_management.scheduled_time") }}'>{{ date(Config::get("Reading.date_format"),strtotime($record->scheduled_time)) }}</td>
					<td data-th='{{ trans("messages.system_management.created") }}'>{{ $record->updated_at->format(Config::get("Reading.date_format")); }}</td>
					<td data-th='{{ trans("messages.system_management.action") }}'>
						
						<a href="javascript:void(0)" id="{{$record->id}}" class="view-subscrieber btn btn-info btn-small no-ajax" >{{ trans('messages.system_management.view_subscriber') }} </a>
								
						<a href="{{URL::to('admin/news-letter/edit-template/'.$record->id)}}" class="btn btn-success btn-small">{{ trans('messages.system_management.edit') }} </a>
						<a href="{{URL::to('admin/news-letter/delete-template/'.$record->id)}}" data-delete="delete" class="btn btn-danger btn-small no-ajax">{{ trans('messages.system_management.delete') }} </a>
						
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
