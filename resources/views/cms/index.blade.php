@extends('layouts.inner')

@section('content')
<div class="page_title">
    <div class="container">
        <div class="row">
            <div class="col-sm-offset-4">
                <h1>{{ isset($result['title']) ? $result['title'] : ''}} </h1>
            </div>
        </div>
    </div>
</div>
<div class="landing_page">
	<div class="container">
		<div class="landing_text" style="margin-bottom:10px;">
			{{ isset($result['body']) ? $result['body'] : ''}}
		</div>
	</div>
</div>

@include('layouts.main.footer_top')
@stop
