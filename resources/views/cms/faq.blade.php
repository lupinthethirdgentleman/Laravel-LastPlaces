@extends('layouts.inner')

@section('content')

<div class="main_content">
	<div class="container">
        <div class="page_title">
            <h1>FAQ</h1>
        </div>
        <div class="row">
        	<div class="col-sm-12">
                <div class="panel-group faq-section" id="accordion">
                	<?php $i=1; ?>
             @foreach ($faq as $faq_data)
                <div class="panel panel-default">
	                <div class="panel-heading">
	                <h4 class="panel-title">
	                <a data-toggle="collapse" data-parent="#accordion" href="#collapse{{$i}}">{{$faq_data->question}}</a>
	                </h4>
	                </div>

	                <div id="collapse{{$i}}" class="panel-collapse collapse">
	                <div class="panel-body">{{$faq_data->answer}}</div>
	                </div>
                </div>
                 <?php $i++; ?>
                @endforeach

                <?php /*
                <div class="panel panel-default">
                <div class="panel-heading">
                <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse3">Collapsible Group 3</a>
                </h4>
                </div>
                <div id="collapse3" class="panel-collapse collapse">
                <div class="panel-body">Lorem ipsum dolor sit amet, consectetur adipisicing elit,
                sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</div>
                </div>
                </div> */?>
                </div> 
            </div>
        </div>
    </div>
</div>

@include('layouts.main.footer_top')
@stop