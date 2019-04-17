<!DOCTYPE html>
<html lang="kg">
<head>
    <title>{{ config('app.name', 'Megazon') }}</title>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="OneTech shop project">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="/styles/bootstrap4/bootstrap.min.css">
    <link href="/plugins/fontawesome-free-5.0.1/css/fontawesome-all.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="/plugins/OwlCarousel2-2.2.1/owl.carousel.css">
    <link rel="stylesheet" type="text/css" href="/plugins/OwlCarousel2-2.2.1/owl.theme.default.css">
    <link rel="stylesheet" type="text/css" href="/plugins/OwlCarousel2-2.2.1/animate.css">
    <link rel="stylesheet" type="text/css" href="/plugins/slick-1.8.0/slick.css">
    <link rel="stylesheet" type="text/css" href="/styles/main_styles.css">
    <link rel="stylesheet" type="text/css" href="/styles/responsive.css">
    <link rel="stylesheet" href="/plugins/pnotify/pnotify.custom.min.css">
    <link rel="stylesheet" href="/plugins/PACE/themes/blue/pace-theme-minimal.css">
    <link rel="stylesheet" type="text/css" href="/plugins/jquery-ui-1.12.1.custom/jquery-ui.css">
</head>

<body>
<div id="app">
    <vue-app></vue-app>
</div>
<script src="/js/jquery-3.3.1.min.js"></script>
<script src="/styles/bootstrap4/popper.js"></script>
<script src="/styles/bootstrap4/bootstrap.min.js"></script>
<script src="/plugins/pnotify/pnotify.custom.min.js"></script>
<script src="/plugins/greensock/TweenMax.min.js"></script>
<script src="/plugins/greensock/TimelineMax.min.js"></script>
<script src="/plugins/scrollmagic/ScrollMagic.min.js"></script>
<script src="/plugins/greensock/animation.gsap.min.js"></script>
<script src="/plugins/greensock/ScrollToPlugin.min.js"></script>
<script src="/plugins/OwlCarousel2-2.2.1/owl.carousel.js"></script>
<script src="/plugins/Isotope/isotope.pkgd.min.js"></script>
<script src="/plugins/slick-1.8.0/slick.js"></script>
<script src="/plugins/easing/easing.js"></script>
<script src="/plugins/PACE/pace.min.js"></script>
<script src="/plugins/jquery-ui-1.12.1.custom/jquery-ui.js"></script>
<script src="/plugins/zoom/jquery.elevateZoom-3.0.8.min.js"></script>
<script src="/plugins/image-compressor-master/image-compressor.min.js"></script>
<script src="{{ asset('js/app.js') }}"></script>
</body>

</html>
