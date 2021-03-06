<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/css/app.css">
    <title>@yield('title','learn-laravel')</title>
</head>
<body>
@include('layout.header')
<div class="container">
    <div class="col-md-offset-1 col-md-10">
        @include('shared.message')
        @yield('content')
        @include('layout.footer')
    </div>
</div>
<script src="/js/app.js"></script>
</body>
</html>