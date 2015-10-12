<!DOCTYPE html>
<html lang="es">
<head>
    <!-- Required meta tags always come first -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>
        @section('title')
            Dayscore
        @show
    </title>
    <!-- Bootstrap CSS -->
    <link rel="shortcut icon" type="image/x-icon" media="all" href="/favicon.ico"/>
    <link rel= "icon" media="all" type="image/vnd.microsoft.icon" href="/favicon.ico" />

    <link rel="stylesheet" href="{{ asset('css/vendor.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @yield('styles')
    <link href='https://fonts.googleapis.com/css?family=Lato:400,100,300,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">
</head>
<body>
<div class="container-fluid" id="section-navbar">
    @include('partials.nav')
</div>
<div class="container-fluid" id="section-content">
    <div class="container">
        @yield('breadcrumb')
        @yield('content')
    </div>
</div>
<script src="{{ asset('js/vendor.js') }}"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
{!! Toastr::render() !!}
@yield('scripts')
</body>
</html>