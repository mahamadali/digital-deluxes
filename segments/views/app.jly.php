<!DOCTYPE html>
<html lang="en">
    <head>
        <title>@plot('title')</title>
        <meta charset="utf-8">
        <meta content="Templines" name="author">
        <meta content="TeamHost" name="description">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="HandheldFriendly" content="true">
        <meta name="format-detection" content="telephone=no">
        <meta content="IE=edge" http-equiv="X-UA-Compatible">
        <link rel="shortcut icon" href="{{ url('assets/frontend/img/favicon.png') }}" type="image/x-icon">
        <link rel="stylesheet" href="{{ url('assets/frontend/css/libs.min.css') }}">
        <link rel="stylesheet" href="{{ url('assets/frontend/css/main.css') }}">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Marcellus&display=swap" rel="stylesheet">
        @plot('styles')
    </head>
    <body class="page-home">

        <input id="toggle" type="checkbox">
        <script type="text/javascript">
            document.getElementById("toggle").addEventListener("click", function() {
                document.getElementsByTagName('body')[0].classList.toggle("dark-theme");
            });

        </script>


        <!-- Loader-->
        <div id="page-preloader">
            <div class="preloader-1">
                <div class="loader-text">Loading</div>
                <span class="line line-1"></span>
                <span class="line line-2"></span>
                <span class="line line-3"></span>
                <span class="line line-4"></span>
                <span class="line line-5"></span>
                <span class="line line-6"></span>
                <span class="line line-7"></span>
                <span class="line line-8"></span>
                <span class="line line-9"></span>
            </div>

        </div>
        <!-- Loader end-->

        <div class="page-wrapper">
            @include('layout/header')
            <div class="page-content">
                @include('layout/aside')
                @plot('content')
                @include('layout/alert')
            </div>
        </div>
        @plot('popup')
        <script src="{{ url('assets/frontend/js/libs.js') }}"></script>
        <script src="{{ url('assets/frontend/js/main.js') }}"></script>
        @plot('scripts')
    </body>
</html>