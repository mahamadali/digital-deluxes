<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>{{ setting('app.title') }}</title>
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


</head>

<body class="page-login">


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
        <main class="page-first-screen">
            <div class="uk-grid uk-grid-small uk-child-width-1-2@s uk-flex-middle uk-width-1-1" data-uk-grid>
                <div class="">
                    <img src="{{ url('assets/img/2.png') }}" alt="logo" class="animation-navspinv">
                    <!-- <span>{ { setting('app.title') } }</span>
                    <br> -->
                    <h1>Join now and play mighty games!</h1>
                </div>
                <div>
                    <div class="form-login">
                        <!-- <div class="form-login__social">
                            <ul class="social">
                                <li><a href="http://www.google.com"><img src="assets/img/google.svg" alt="google"></a></li>
                                <li><a href="http://www.facebook.com"><img src="assets/img/facebook.svg" alt="facebook"></a></li>
                                <li><a href="http://www.twitter.com"><img src="assets/img/twitter.svg" alt="twitter"></a></li>
                            </ul>
                        </div> -->
                        <div class="form-login__box">
                            <!-- <div class="uk-heading-line uk-text-center"><span>or with Email</span></div> -->
                            <form action="#!">
                                <div class="uk-margin"><input class="uk-input" type="email" placeholder="Email"></div>
                                <div class="uk-margin"><input class="uk-input" type="text" placeholder="Username"></div>
                                <div class="uk-margin"><input class="uk-input" type="password" placeholder="Password"></div>
                                <div class="uk-margin"><button class="uk-button uk-button-danger uk-width-1-1" type="button">Register</button></div>
                                <div class="uk-text-center"><span>Already have an account?</span><a class="uk-margin-small-left" href="01_login-in.html">Log In</a></div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="{{ url('assets/frontend/js/libs.js') }}"></script>
    <script src="{{ url('assets/frontend/js/main.js') }}"></script>
</body>

</html>
