<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>@yield('title')</title>
<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0">
<link href="{{ asset("assets/lib/css/font-awesome.css") }}" rel="stylesheet" type="text/css">
<link href="{{ asset("assets/lib/css/moonicon.css") }}" rel="stylesheet" type="text/css">
<link href="{{ asset("assets/lib/css/bootstrap.css") }}" rel="stylesheet" type="text/css">
<link href="{{ asset("assets/css/style.css") }}" rel="stylesheet" type="text/css">
@stack('styles')
</head>

<body>
@include('layout.header')

@yield('content')

@include('layout.footer')

<script src="{{ asset("assets/lib/js/jquery-1.12.3.min.js") }}"></script> 
<script src="{{ asset("assets/lib/js/bootstrap.min.js") }}" type="text/javascript"></script> 
@stack('scripts')
</body>
</html>