<!-- For flash messages -->
@if(Session::has('flash_notice'))
<div class="alert alert-success flash-message alert-dismissible alert-index" role="alert">
	<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
 {{ Session::get('flash_notice') }}
</div>
@endif

@if(Session::has('error'))
<div class="alert alert-danger flash-message alert-dismissible alert-index" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  {{ Session::get('error') }}
</div>
@endif

@if(Session::has('success'))
<div class="alert alert-success flash-message alert-dismissible alert-index" role="alert">
	<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	{{ Session::get('success') }}
</div>	
@endif



