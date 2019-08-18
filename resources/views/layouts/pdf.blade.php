<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
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

{{ HTML::style('css/bootstrap.css') }}
{{ HTML::style('css/owl.carousel.min.css') }}
{{ HTML::style('css/dahcp_style.css') }}
{{ HTML::style('css/pnotify.core.css') }}

{{ HTML::script('js/jquery-2.1.1.min.js') }}	
{{ HTML::script('js/jquery.form.js') }}
{{ HTML::script('js/owl.carousel.min.js') }}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>



@yield('content')

{{ HTML::script('js/pnotify-master/pnotify.core.js') }}
{{ HTML::script('js/bootstrap.min.js') }}
{{ HTML::script('js/bootstrap.js') }}

</body>
</html>