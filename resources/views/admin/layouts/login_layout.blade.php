<!DOCTYPE html>
<html>
	<head>
		<title><?php echo Config::get("Site.title"); ?></title>
		{{ HTML::style('css/admin/bootstrap.css') }}
		{{ HTML::style('css/admin/icons/icol16.css') }}
		{{ HTML::style('css/admin/fonts/icomoon/style.css') }}
		{{ HTML::style('css/admin/icons/icol32.css') }}
		{{ HTML::style('css/admin/mws-theme.css') }}
		{{ HTML::style('css/admin/themer.css') }}
		{{ HTML::style('css/admin/login.css') }}
		{{ HTML::script('js/admin/jquery-2.1.1.min.js') }}
		{{ HTML::script('js/admin/core/mws.js') }}
		<script>
			// for close the message 
			$(document).ready(function(){
				$(".close pull-right").click(function(){
					$(".mws-form-message").hide();
				})
			});
		</script>
	</head>
	<body>
		@if(Session::has('flash_notice'))
		<div class="grid_8 mws-collapsible" style="padding:20px">
			<div class="mws-form-message success">
				<a href="javascript:void(0);" class="close pull-right">x</a>
				<p>
					{{ Session::get('flash_notice') }}
				</p>
			</div>
		</div>
		@endif
		@if(Session::has('error'))
		<div class="grid_8 mws-collapsible" style="padding:20px">
			<div class="mws-form-message error">
				<a href="javascript:void(0);" class="close pull-right">x</a>
				<p>
					{{ Session::get('error') }}
				</p>
			</div>
		</div>
		@endif
		@if(Session::has('success'))
		<div class="grid_8 mws-collapsible" style="padding:20px">
			<div class="mws-form-message success">
				<a href="javascript:void(0);" class="close pull-right">x</a>
				<p>
					{{ Session::get('success') }}
				</p>
			</div>
		</div>
		@endif
		<div id="mws-login-wrapper">
			<!--
			<div class="mws-form-row">
				<img src="<?php echo WEBSITE_URL .'image.php?width=550px&height=180px&image='. WEBSITE_IMG_URL; ?>demo1.jpg">
			</div>
			-->
			<div style="text-align:center;" class="mws-form-row">
				<span style="font-size:34px;color:rgba(21, 100, 176, 0.9);font-weight:bold;">Last Places</span>
			</div>
			<div class="clearfix">&nbsp;</div>
			@yield('content')
		</div>
	</body>
</html>

