<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta>
<title>
	<?php 
		$pre = Config::get("Site.title");
		if(isset($title)){
			echo $pre.' - '.$title;
		}
		else{
			echo $pre;
		}
	?>		
</title>
<link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,600,700" rel="stylesheet">
{{ HTML::style('css/font-awesome.min.css') }}
{{ HTML::style('css/pnotify.core.css') }}
{{ HTML::style('css/bootstrap.css') }}
{{ HTML::style('css/custom.css') }}
{{ HTML::script('js/jquery.min.js') }}
{{ HTML::script('js/jquery.form.js') }}

</head>

<body>
@include('layouts.main.header')
@yield('content')
@include('layouts.main.footer')
{{ HTML::script('js/pnotify-master/pnotify.core.js') }}
{{ HTML::script('js/bootstrap.min.js') }}
{{ HTML::script('js/bootstrap.js') }}

<script type="text/javascript">
				function notice(title,message,type){
				new PNotify({
					title: title,
					addclass: "stack-topright",
					text: message,
					type : type,
					hide: true,
					shadow: true,
					delay: 6000,
					mouse_reset: true,
					buttons: {
						closer: true ,
						sticker:false
					}
				});
			}

</script>
</body>
</html>