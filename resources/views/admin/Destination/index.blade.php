@extends('admin.layouts.default')

{{ HTML::style('css/admin/styles.css') }}

{{ HTML::style('css/admin/lightbox.css') }}


@section('content')

<style>
.blueicon {
    color: #5bc0de;
}
.redicon {
    color: #d9534f;
}
.greenicon {
    color: #82b964;
}
</style>

<!-- Searching div -->  
{{ HTML::script('js/admin/lightbox.js') }}
<!-- js for equal height of the div  -->
{{ HTML::script('js/admin/jquery.matchHeight-min.js') }}

{{ HTML::script('js/admin/multiple_delete.js') }}

@stop