<?php
$hcpArray = array();
$companyArray = array();
function print_stars($num) {
    //echo '<ul class="review-rating star-rating">';
    for ($n=1; $n<=5; $n++) {
        echo '<span class="fa fa-1x fa-star';
        if ($num==$n+.5) {
            echo '-half-empty';
        } elseif ($num<$n) {
            echo '-o';
        };
        echo '"></span>';
    };
    //echo '</ul>';
}
?>
<style>
.star-rating {
  line-height:32px;
  font-size:1.25em;
}

.star-rating .fa-star{color:#f8ad18;}
</style>
@extends('admin.layouts.default')

@section('content')
{{ HTML::script('js/admin/bootstrap-modal.min.js') }}
{{ HTML::style('css/admin/bootmodel.css') }}

 <script type="text/javascript">
 	function showFullComment(i){
 		var cmt = $('#comment_'+i).text();
 		$('#modelContent').text(cmt);
 		$("#getting_basic_list_popover").modal('show');
 	}
 </script>
<!--pop div start here-->
<div aria-hidden="false" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="getting_basic_list_popover" class="modal fade in" style="display: none;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<a data-dismiss="modal" class="close" href="javascript:void(0)">
				<span style="float:right" class="no-ajax" aria-hidden="true">x</span>
				<span class="sr-only no-ajax"></span></a>	
				<h4 class="modal-title" id="myModalLabel">
					Comment
				</h4>
			</div>
			<div class="modal-body">
				<div class="mws-panel-body no-padding" id="modelContent"></div>
				<div class="clearfix">&nbsp;</div>
			</div>
		</div>
	</div>
</div>
<!-- popup div end here-->

<div id="mws-themer">
	<div id="mws-themer-content" style="<?php echo ($searchVariable) ? 'right: 256px;' : ''; ?>">
		<div id="mws-themer-ribbon"></div>
		<div id="mws-themer-toggle" class="<?php echo ($searchVariable) ? 'opened' : ''; ?>">
			<i class="icon-bended-arrow-left"></i> 
			<i class="icon-bended-arrow-right"></i>
		</div>
		{{ Form::open(['method' => 'get','role' => 'form','url' => 'admin/list-review','class' => 'mws-form']) }}
		{{ Form::hidden('display') }}
		<div class="mws-themer-section">
			<!-- <div id="mws-theme-presets-container" class="mws-themer-section">
				<label>{{ trans("HCP") }}</label><br/>
				{{ Form::text('hcp',((isset($searchVariable['hcp'])) ? $searchVariable['hcp'] : ''), ['class' => 'small']) }}
			</div>
			<div id="mws-theme-presets-container" class="mws-themer-section">
				<label>{{ trans("Company") }}</label><br/>
				{{ Form::text('company',((isset($searchVariable['company'])) ? $searchVariable['company'] : ''), ['class' => 'small']) }}
			</div>
			<div id="mws-theme-presets-container" class="mws-themer-section">
				<label>{{ trans("Location") }}</label><br/>
				{{ Form::text('location',((isset($searchVariable['location'])) ? $searchVariable['location'] : ''), ['class' => 'small']) }}
			</div> -->
			<div id="mws-theme-presets-container" class="mws-themer-section">
				<label>{{ trans("Rating") }}</label><br/>
				{{ Form::text('rating',((isset($searchVariable['rating'])) ? $searchVariable['rating'] : ''), ['class' => 'small']) }}
			</div>
		</div>
		<div class="mws-themer-separator"></div>
		<div class="mws-themer-section" style="height:0px">
			<ul><li class="clearfix"><span></span> <div id="mws-textglow-op"></div></li> </ul>
		</div>
		<div class="mws-themer-section">
			<input type="submit" value='{{ trans("messages.search.text") }}' class="btn btn-primary btn-small">
			<a href="{{URL::to('admin/list-review')}}"  class="btn btn-default btn-small">{{ trans("messages.reset.text") }}</a>
		</div>
		{{ Form::close() }}
	</div>
</div>
<?php
//print_r("<pre>");
//print_r($locationName);die;
?>
<div  class="mws-panel grid_8 mws-collapsible">
	<div class="mws-panel-header">
		<span>
			<i class="icon-table"></i> {{ trans("Reviews") }} </span>
				<!-- <a href="{{URL::to('admin/list-company/add-company')}}" class="btn btn-success btn-small align">{{ trans("Add New Comapny") }} </a> -->
	</div>
	<div class="mws-panel-body no-padding dataTables_wrapper">
		<table class="mws-table mws-datatable">
			<thead>
				<tr>
					<th width="10%">{{ trans("HCP") }}</th>
					<th width="10%">{{ trans("Company") }}</th>
					<th width="25%">{{ trans("Location") }}</th>
					<th width="10%">{{ trans("Health Benefit") }}</th>			
					<th width="15%">{{ trans("Rating") }}</th>
					<th width="10%">{{ trans("Comment") }}</th>
					<th width="7%">{{ trans("Status") }}</th>		
					<th width="13%">{{ trans("messages.system_management.action") }}</th>
				</tr>
			</thead>
			<tbody id="powerwidgets">
				@if(!$result->isEmpty())
				<?php $i=0 ;?>
				@foreach($result as $record)
				<tr class="items-inner">
					<td data-th='HCP'>
						@foreach($hcp_list as $hcp_lst)
						{{ $record->hcp_id == $hcp_lst->id ? $hcp_lst->full_name : ''}}
						@endforeach
					</td>
					<td data-th='Company Name'>
						@foreach($company_list as $cmpany_list)
						{{ $record->company_id == $cmpany_list->id ? $cmpany_list->Name : ''}}
						@endforeach
					</td>

					<td data-th='Company Location'>
						@foreach($locationName as $location)
							@if($record->hcp_id == $location->hcp_id && $record->company_id == $location->company_id)
								{{ $location->Name }}
							@endif
						@endforeach
					</td>
					
					<td data-th='Health Benefit'>
						@foreach($benefit_list as $benefit_key=>$benefit_value)
						{{ $record->health_benefit == $benefit_key ? $benefit_value : ''}}
						@endforeach
					</td>
					
					<td data-th='stars'>
						<div class="star-rating">
                    		{{ print_stars($record->rating) }}
                    	</div>
					</td>
					
					<td data-th='Comment'>
						<a href="#" onclick="showFullComment(<?php echo $i; ?>)"><?php echo substr($record->comment,0,50).'...'; ?></a>
						<span style="display:none;" id="comment_{{ $i }}">{{$record->comment }}</span>
					</td>
					
					<td data-th='Status'>
						@if($record->status == 1)
							<span class="label label-success">Active</span>
						@else
							<span class="label label-warning">Inactive</span>
						@endif
					</td>

					<td data-th='{{ trans("messages.system_management.action") }}'>
						@if($record->status == 1)
							<a href="{{URL::to('admin/list-review/update-status/'.$record->id)}}" class="btn btn-danger btn-small change_status">Mark inactive</a>
						@else
							<a href="{{URL::to('admin/list-review/update-status/'.$record->id)}}" class="btn btn-success btn-small change_status">Mark active</a>
						@endif
						<a href="{{URL::to('admin/delete-review/'.$record->id)}}" class="btn btn-danger btn-small delete_user">{{ trans("messages.system_management.delete") }} </a>
					</td>
				</tr>
				<?php $i++ ;?>
				 @endforeach
				 @else
					<table class="mws-table mws-datatable details">	
						<tr>
							<td align="center" width="100%"> {{ trans("messages.system_management.no_record_found_message") }}</td>
						</tr>	
					</table>  
				@endif 
			</tbody>
		</table>
	</div>
	@include('pagination.default', ['paginator' => $result])
</div>

 
{{ HTML::script('js/admin/lightbox.js') }}
<!-- js for equal height of the div  -->
{{ HTML::script('js/admin/jquery.matchHeight-min.js') }}

<script type="text/javascript">
	
var action_url = '<?php echo WEBSITE_URL; ?>admin/list-company/multiple-action';
 /* for equal height of the div */	
 
$(function() {
	$('.eqaul-height').matchHeight();
});
$('.change_status').click(function(e){
	e.stopImmediatePropagation();
	var url = $(this).attr('href');
	bootbox.confirm("Do you want to change the status ?",
	function(result){
		if(result){
			window.location.href=url;
		}
	});
	e.preventDefault();
});
 </script>

{{ HTML::script('js/admin/multiple_delete.js') }}
@stop
