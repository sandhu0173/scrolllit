<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <title>{{ config('app.name', 'Laravel') }}</title>
        <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

<link rel="stylesheet" type="text/css" href="{{ asset('assets/font-awesome-4.7.0/css/font-awesome.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}">
    </head>
    <body>
    <div id="app">
  <router-view></router-view>
</div>
        <script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
        
       
    </body>
</html>
