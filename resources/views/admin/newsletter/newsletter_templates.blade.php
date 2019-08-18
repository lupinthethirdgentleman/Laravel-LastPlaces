@extends('admin.layouts.default')

@section('content')


<script type="text/javascript">
	var action_url = '<?php echo WEBSITE_URL; ?>admin/users/multiple-action';
</script>
 {{ HTML::script('js/admin/multiple_delete.js') }}



<div id="mws-themer">
	<div id="mws-themer-content" style="<?php echo ($searchVariable) ? 'right: 256px;' : ''; ?>">
		<div id="mws-themer-ribbon"></div>
		<div id="mws-themer-toggle" class="<?php echo ($searchVariable) ? 'opened' : ''; ?>">
			<i class="icon-bended-arrow-left"></i> 
			<i class="icon-bended-arrow-right"></i>
		</div>
	
		{{ Form::open(['method' => 'get','role' => 'form','url' => 'admin/news-letter/newsletter-templates','class' => 'mws-form']) }}
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
			<a href="{{URL::to('admin/news-letter/newsletter-templates')}}"  class="btn btn-default btn-small">{{ trans('messages.system_management.reset') }}</a>
		</div>
		{{ Form::close() }}
	</div>
</div>

<div  class="mws-panel grid_8 mws-collapsible">
	<div class="mws-panel-header">
		<span><i class="icon-table"></i> {{ trans("messages.system_management.newsletter_template") }} </span>
			<a href="{{URL::to('admin/news-letter/add-template')}}" style=" margin-right: 10px !important;" class="btn btn-success btn-small align">{{ trans("messages.system_management.add_template") }} </a>
			<a href="{{URL::to('admin/news-letter/subscriber-list')}}"  style=" margin-right: 10px !important;" class="btn btn-success btn-small align">{{ trans("messages.system_management.subscriber_list") }} </a> &nbsp;
			<a href="{{URL::to('admin/news-letter')}}" style=" margin-right: 10px !important;" class="btn btn-success btn-small align">{{ trans("messages.system_management.scheduled_newletter") }} </a>	 &nbsp;
		
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
							'NewsTemplates.newsletterTemplates',
							'Subject',
							array(
								'sortBy' => 'subject',
								'order' => ($sortBy == 'subject' && $order == 'desc') ? 'asc' : 'desc'
							),
						   array('class' => (($sortBy == 'subject' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'subject' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
						)
					}}
					</th>
					
					<th width="18%">
					{{
						link_to_route(
							'NewsTemplates.newsletterTemplates',
							'Created',
							array(
								'sortBy' => 'created_at',
								'order' => ($sortBy == 'created_at' && $order == 'desc') ? 'asc' : 'desc'
							),
						   array('class' => (($sortBy == 'created_at' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'created_at' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
						)
					}}
	                </th>
					<th width="18%">
					{{
						link_to_route(
							'NewsTemplates.newsletterTemplates',
							'Updated',
							array(
								'sortBy' => 'updated_at',
								'order' => ($sortBy == 'updated_at' && $order == 'desc') ? 'asc' : 'desc'
							),
						   array('class' => (($sortBy == 'updated_at' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'updated_at' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
						)
					}}
	                </th>
					<th>{{ 'Action' }}</th>
				</tr>
			</thead>
			<tbody>
				@if(!$result->isEmpty())
				@foreach($result as $record)
				<tr>
					<td data-th='Subject'>{{ $record->subject }}</td>
					<td data-th='Scheduled Date'>{{ date(Config::get("Reading.date_format"),strtotime($record->created_at)) }}</td>
					<td data-th='Created'>{{ date(Config::get("Reading.date_format"),strtotime($record->updated_at)) }}</td>
					<td data-th='Action'>
						<a href="{{URL::to('admin/news-letter/edit-newsletter-templates/'.$record->id)}}" class="btn btn-success btn-small">{{ trans('messages.system_management.edit') }}</a>
						<a href="{{URL::to('admin/news-letter/delete-newsletter-template/'.$record->id)}}" class="delete_user btn btn-danger btn-small no-ajax">{{ trans('messages.system_management.delete') }}</a>
						<a href="{{URL::to('admin/news-letter/send-newsletter-templates/'.$record->id)}}" class="btn btn-info btn-small">{{ trans('messages.system_management.send') }} </a>
						
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
