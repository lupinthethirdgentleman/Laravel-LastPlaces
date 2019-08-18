<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo Config::get("Site.title"); ?></title>

{{ HTML::style('css/bootstrap.css') }}
{{ HTML::style('css/owl.carousel.min.css') }}
{{ HTML::style('css/dahcp_style.css') }}

{{ HTML::style('css/autocomplete.css') }}

{{ HTML::script('js/jquery.min.js') }}

{{ HTML::script('js/bootstrap.js') }}

{{ HTML::script('js/autocomplete.min.js') }}

<!-- {{ HTML::script('js/PassRequirements.js') }} -->

{{ HTML::script('js/owl.carousel.min.js') }}

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

</head>

<body>
@include('layouts.main.header')

@yield('content')

@include('layouts.main.footer')

</body>
</html>





