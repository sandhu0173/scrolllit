<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

<title>{{ config('app.name', 'Laravel') }}</title>

        <meta name="description" content="">

        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <meta name="apple-mobile-web-app-title" content="Scrolller">
        <link rel="apple-touch-icon" sizes="200x200" href="">

        <link rel="icon" type="image/png" sizes="16x16" href="">
        <link rel="icon" type="image/png" sizes="200x200" href="">
        <link rel="icon" type="image/png" sizes="32x32" href="">

        <link rel="manifest" href="">
        <link href="https://cdn.jsdelivr.net/fontawesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}">    
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/custom.css') }}">    
        <link rel="canonical" href="">
        <meta property="og:image" content="">
        <meta property="og:description" content="">
        <meta property="og:title" content="">
        <meta property="og:url" content="">

        <meta name="twitter:image" content="">
        <meta name="twitter:card" content="summary">
        <meta name="twitter:description" content="">
        <meta name="twitter:title" content="">

    </head>
<body cz-shortcut-listen="true" class="">
@yield('content')
</body>

</html>