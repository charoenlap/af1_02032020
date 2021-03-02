<!DOCTYPE html>
<html lang="en">
    <head>
        <title><?php echo $meta->site_name ?></title>
        <meta charset="utf-8" />
        <meta property="og:url" content="<?php echo $meta->url ?>" />
        <meta property="og:type" content="<?php echo $meta->type ?>" />
        <meta property="fb:app_id" content="<?php echo $meta->app_id ?>" />
        <meta property="og:site_name" content="<?php echo $meta->site_name ?>" />
        <meta property="og:title" content="<?php echo $meta->title ?>" />
        <meta property="og:description" content="<?php echo $meta->description ?>" />
        <meta property="og:image" content="<?php echo $meta->image ?>" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <meta name="csrf-token" content="<?php echo csrf_token() ?>">
        <meta name="keywords" content="<?php echo $meta->description ?>">
        <meta name="author" content="<?php echo $meta->site_name ?>">
        <!-- Favicon -->
        <link rel="shortcut icon" href="<?php echo urlHomeImage().'/logo-favicon.png' ?>">
        <!-- Font -->
        <link href="<?php echo urlThemes().'/mist/fonts/font-awesome/font-awesome.css' ?>" rel="stylesheet" >
        <link href="<?php echo urlThemes().'/mist/fonts/brand-icons/brand-icons.min.css'?>" rel='stylesheet' type='text/css'/>
        <link href="<?php echo urlThemes().'/fonts/Kanit/Kanit.css'?>" rel='stylesheet' type='text/css'/>
        <!-- Bootstrap core CSS -->
        <link href="<?php echo urlThemes().'/mist/css/bootstrap.min.css' ?>" rel="stylesheet"/>
        <!-- Bootstrap core CSS -->
        <link href="<?php echo urlThemes().'/mist/css/bootstrap.min.css' ?>" rel="stylesheet"/>
        <link href="<?php echo urlThemes().'/mist/css/bootstrap-extend.css' ?>" rel="stylesheet"/>
        <link href="<?php echo urlThemes().'/mist/css/hover-dropdown-menu.css' ?>" rel="stylesheet"/>
        <!-- Icomoon Icons -->
        <link href="<?php echo urlThemes().'/mist/css/icons.css' ?>" rel="stylesheet" />
        <!-- Animations -->
        <link href="<?php echo urlThemes().'/mist/css/animate.min.css' ?>" rel="stylesheet" />
        <!-- Owl Carousel Slider -->
        <link href="<?php echo urlThemes().'/mist/css/owl/owl.carousel.css' ?>" rel="stylesheet" />
        <link href="<?php echo urlThemes().'/mist/css/owl/owl.theme.css' ?>" rel="stylesheet" />
        <link href="<?php echo urlThemes().'/mist/css/owl/owl.transitions.css' ?>" rel="stylesheet" />
        <!-- PrettyPhoto Popup -->
        <link href="<?php echo urlThemes().'/mist/css/prettyPhoto.css' ?>" rel="stylesheet" />
        <!-- Custom Style -->
        <link href="<?php echo urlThemes().'/mist/css/style.css' ?>" rel="stylesheet" />
        <link href="<?php echo urlThemes().'/mist/css/responsive.css' ?>" rel="stylesheet" />
        <!-- Color Scheme -->
        <link href="<?php echo urlThemes().'/mist/css/color.css' ?>" rel="stylesheet" />
        <!-- VENDOR -->
        <link href="<?php echo urlThemes().'/mist/vendor/bootstrap-datepicker/bootstrap-datepicker.min.css' ?>" rel="stylesheet" />
        <link href="<?php echo urlThemes().'/mist/vendor/bootstrap-sweetalert/sweetalert.css' ?>" rel="stylesheet" />
        <link href="<?php echo urlThemes().'/mist/vendor/clockpicker/clockpicker.min.css' ?>" rel="stylesheet">
        <link href="<?php echo urlThemes().'/mist/vendor/dropify/dropify.css' ?>" rel="stylesheet" />
        <link href="<?php echo urlThemes().'/mist/vendor/fade-slideshow/css/jssor.slider.custom.css' ?>" rel="stylesheet" />
        <link href="<?php echo urlThemes().'/mist/vendor/webui-popover/webui-popover.css' ?>" rel="stylesheet" />
        <!-- CUSTOM -->
        <link type="text/css" rel="stylesheet" href="<?php echo urlBase().'/assets/home/css/site.css' ?>" />
        <link type="text/css" rel="stylesheet" href="<?php echo urlBase().'/assets/home/css/main/index.css' ?>" />
        <!-- Module CSS -->
        <?php if (file_exists(helperDirPublic().'/assets/home/css/'.helperRouteModule().'/'.helperRouteAction().'.css')): ?>
            <link rel="stylesheet" href="<?php echo urlBase().'/assets/home/css/'.helperRouteModule().'/'.helperRouteAction().'.css' ?>">
        <?php endif ?>
    </head>
    <body class="">
        <!-- VUE -->
        <script src="<?php echo urlBase().'/assets/js/vue.js' ?>"></script>
        <!-- Page Loader -->
        @if (env('APP_ENV') == 'production')
        <div id="pageloader">
            <div class="loader-item fa fa-spin text-color"></div>
        </div>
        @endif

        <!-- PAGE -->
        <div id="page">
            <div class="row">
                <div class="col-sm-12">
                <!-- HEADER -->
                {!! view('home.layouts.header', $data) !!}
                <!-- BODY -->
                {!! view($page, $data) !!}
                <!-- FOOTER -->
                {!! view('home.layouts.footer') !!}
                </div>
            </div>
        </div>
        <!-- Data -->
        <div id="mw-url-auth" data-value="<?php echo \URL::route('home.authen.login.post') ?>"></div>

        <!-- Scripts -->
        <script type="text/javascript" src="<?php echo urlThemes().'/mist/js/jquery.min.js' ?>"></script>
        <!-- Scripts -->
        <script type="text/javascript" src="<?php echo urlThemes().'/mist/js/jquery.min.js' ?>"></script>
        <script type="text/javascript" src="<?php echo urlThemes().'/mist/js/bootstrap.min.js' ?>"></script>
        <!-- Menu jQuery plugin -->
        <script type="text/javascript" src="<?php echo urlThemes().'/mist/js/hover-dropdown-menu.js' ?>"></script>
        <!-- Menu jQuery Bootstrap Addon -->
        <script type="text/javascript" src="<?php echo urlThemes().'/mist/js/jquery.hover-dropdown-menu-addon.js' ?>"></script>
        <!-- Scroll Top Menu -->
        <script type="text/javascript" src="<?php echo urlThemes().'/mist/js/jquery.easing.1.3.js' ?>"></script>
        <!-- Sticky Menu -->
        <script type="text/javascript" src="<?php echo urlThemes().'/mist/js/jquery.sticky.js' ?>"></script>
        <!-- Bootstrap Validation -->
        <script type="text/javascript" src="<?php echo urlThemes().'/mist/js/bootstrapValidator.min.js' ?>"></script>
        <!-- Revolution Slider -->
        <script type="text/javascript" src="<?php echo urlThemes().'/mist/rs-plugin/js/jquery.themepunch.tools.min.js' ?>"></script>
        <script type="text/javascript" src="<?php echo urlThemes().'/mist/rs-plugin/js/jquery.themepunch.revolution.min.js' ?>"></script>
        <script type="text/javascript" src="<?php echo urlThemes().'/mist/js/revolution-custom.js' ?>"></script>
        <!-- Portfolio Filter -->
        <script type="text/javascript" src="<?php echo urlThemes().'/mist/js/jquery.mixitup.min.js' ?>"></script>
        <!-- Animations -->
        <script type="text/javascript" src="<?php echo urlThemes().'/mist/js/jquery.appear.js' ?>"></script>
        <script type="text/javascript" src="<?php echo urlThemes().'/mist/js/effect.js' ?>"></script>
        <!-- Owl Carousel Slider -->
        <script type="text/javascript"  src="<?php echo urlThemes().'/mist/js/owl.carousel.min.js' ?>"></script>
        <!-- Pretty Photo Popup -->
        <script type="text/javascript"  src="<?php echo urlThemes().'/mist/js/jquery.prettyPhoto.js' ?>"></script>
        <!-- Parallax BG -->
        <script type="text/javascript"  src="<?php echo urlThemes().'/mist/js/jquery.parallax-1.1.3.js' ?>"></script>
        <!-- Fun Factor / Counter -->
        <script type="text/javascript"  src="<?php echo urlThemes().'/mist/js/jquery.countTo.js' ?>"></script>
        <!-- Vendor -->
        <script src="<?php echo urlThemes().'/mist/vendor/bootbox/bootbox.js' ?>"></script>
        <script src="<?php echo urlThemes().'/mist/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js' ?>"></script>
        <script src="<?php echo urlThemes().'/mist/vendor/bootstrap-sweetalert/sweetalert.min.js' ?>"></script>
        <script src="<?php echo urlThemes().'/mist/vendor/clockpicker/jquery-clockpicker.min.js' ?>"></script>
        <script src="<?php echo urlThemes().'/mist/vendor/dropify/dropify.min.js' ?>"></script>
        <script src="<?php echo urlThemes().'/mist/vendor/fade-slideshow/js/jssor.slider-22.1.8.min.js' ?>"></script>
        <script src="<?php echo urlThemes().'/mist/vendor/fade-slideshow/js/jssor.slider.custom.js' ?>"></script>
        <script src="<?php echo urlThemes().'/mist/vendor/webui-popover/jquery.webui-popover.js' ?>"></script>
        <script src="<?php echo urlThemes().'/mist/js/custom.js' ?>"></script>

        <!-- Custom Js Code -->
        <script type="text/javascript" src="<?php echo urlBase().'/assets/home/js/site.js' ?>"></script>
        <script type="text/javascript" src="<?php //echo urlBase().'/assets/home/js/custom.js' ?>"></script>
        <script type="text/javascript" src="<?php echo urlBase().'/assets/js/md5.js' ?>"></script>
        <script type="text/javascript" src="<?php //echo urlBase().'/assets/js/fb.js' ?>"></script>
        <!-- Module Script -->
        <?php if (file_exists(helperDirPublic().'/assets/home/js/'.helperRouteModule().'/'.helperRouteAction().'.js')): ?>
            <script src="<?php echo urlBase().'/assets/home/js/'.helperRouteModule().'/'.helperRouteAction().'.js' ?>"></script>
        <?php endif ?>
    </body>
</html>