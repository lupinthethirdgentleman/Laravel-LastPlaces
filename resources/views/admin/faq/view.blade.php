@extends('admin.layouts.default')

@section('content')

<!--- ckeditor js and css start here  here -->
{{ HTML::style('css/admin/custom_li_bootstrap.css') }}
{{ HTML::script('js/bootstrap.js') }}
{{ HTML::script('js/admin/ckeditor/ckeditor.js') }}
<!-- CKeditor js  end css end here-->

<div class="mws-panel grid_8" >
	<div class="mws-panel-header" style="height: 46px;">
		<span> {{ trans("messages.system_management.faq") }} </span>
		<a href="{{URL::to('admin/faqs-manager')}}" class="btn btn-success btn-small align">{{ trans("messages.system_management.back") }} </a>
	</div>
	<div class="wizard-nav wizard-nav-horizontal">
		<ul class="nav nav-tabs">
			@foreach($languages as $value)
				<li class=" {{ ($value->id ==  $language_code )?'active':'' }}">
					<a data-toggle="tab" href="#{{ $value->id }}div">
						{{ $value -> title }}
					</a>
				</li>
			@endforeach
		</ul>
	</div>
	<div class="mws-panel-body no-padding dataTables_wrapper">
		<table  width="100%" align="left"  id="{{  $value->id }}div" class="tab-pane {{ ( $value->id ==  $language_code )?'active':'' }} mws-table mws-datatable">
			@foreach($languages as  $key => $value)
			
				<tr>
					<td>
						<span class="description_heading">
							<strong>{{ (isset($multiLanguage[ $value->id]['question']))? $multiLanguage[$value->id]['question'] :'' }}</strong>	
						</span>
						<hr class="border_1px margin_bottom_5">
						<div class="justify">
							{{ isset($multiLanguage[ $value->id]['answer'])? $multiLanguage[ $value->id]['answer']:'' }}
						</div>
					</td>
				</tr>	
				
			@endforeach
		</table>	
	</div>
</div>

@stop
