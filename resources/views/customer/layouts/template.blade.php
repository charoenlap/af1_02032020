<!DOCTYPE html>
<html lang="en">
    <head>
        <title><?php echo $meta->title ?></title>
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
        <meta name="keywords" content="AIRFORCEONE">
        <meta name="author" content="MEWITTY">
        <meta name="google-signin-client_id" content="<?php //echo config('credential.providers.google.client_id') ?>">
        <!-- META IOS -->
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="#062C3F">
        <meta name="apple-mobile-web-app-title" content="AIRFORCEONE">
        <link rel="apple-touch-icon" href="<?php echo urlHomeImage().'/logo-icon-app.png' ?>">
        <meta name="msapplication-TileImage" content="<?php echo urlHomeImage().'/logo-icon-app.png' ?>">
        <meta name="msapplication-TileColor" content="#062C3F">
        <!-- Favicon -->
        <link rel="shortcut icon" href="<?php echo urlHomeImage().'/logo-favicon.png' ?>">
        <!-- Font -->
        <link href="{{ urlThemes().'/mist/fonts/font-awesome/font-awesome.css' }}" rel="stylesheet" >
        <link href="{{ urlThemes().'/mist/fonts/brand-icons/brand-icons.min.css'}}" rel='stylesheet' type='text/css'/>
        <link href="{{ urlThemes().'/mist/fonts/web-icons/web-icons.min.css'}}" rel='stylesheet' type='text/css'/>
        <link href="{{ urlThemes().'/fonts/Kanit/Kanit.css'}}" rel='stylesheet' type='text/css'/>
        <!-- Bootstrap core CSS -->
        <link href="{{ urlThemes().'/mist/css/bootstrap.css' }}" rel="stylesheet"/>
        <link href="{{ urlThemes().'/mist/css/bootstrap-extend.css' }}" rel="stylesheet"/>
        <link href="{{ urlThemes().'/mist/css/hover-dropdown-menu.css' }}" rel="stylesheet"/>
        <!-- Icomoon Icons -->
        <link href="{{ urlThemes().'/mist/css/icons.css' }}" rel="stylesheet" />
        <!-- Revolution Slider -->
        <link href="{{ urlThemes().'/mist/css/revolution-slider.css' }}" rel="stylesheet" />
        <link href="{{ urlThemes().'/mist/rs-plugin/css/settings.css' }}" rel="stylesheet" />
        <!-- Animations -->
        <link href="{{ urlThemes().'/mist/css/animate.min.css' }}" rel="stylesheet" />
        <!-- Owl Carousel Slider -->
        <link href="{{ urlThemes().'/mist/css/owl/owl.carousel.css' }}" rel="stylesheet" />
        <link href="{{ urlThemes().'/mist/css/owl/owl.theme.css' }}" rel="stylesheet" />
        <link href="{{ urlThemes().'/mist/css/owl/owl.transitions.css' }}" rel="stylesheet" />
        <!-- PrettyPhoto Popup -->
        <link href="{{ urlThemes().'/mist/css/prettyPhoto.css' }}" rel="stylesheet" />
        <!-- Custom Style -->
        <link href="{{ urlThemes().'/mist/css/style.css' }}" rel="stylesheet" />
        <link href="{{ urlThemes().'/mist/css/responsive.css' }}" rel="stylesheet" />
        <!-- VENDOR -->
        <link href="{{ urlThemes().'/mist/vendor/bootstrap-sweetalert/sweetalert.css' }}" rel="stylesheet" >

        <link href="{{ urlThemes().'/mist/vendor/bootstrap-datepicker/bootstrap-datepicker.min.css' }}" rel="stylesheet" >
        <link href="{{ urlThemes().'/mist/vendor/bootstrap-tokenfield/bootstrap-tokenfield.min.css' }}" rel="stylesheet" >
        <link href="{{ urlThemes().'/mist/vendor/clockpicker/clockpicker.min.css' }}" rel="stylesheet">
        <link href="{{ urlThemes().'/mist/vendor/datatables-bootstrap/dataTables.bootstrap.css' }}" rel="stylesheet">
        <link href="{{ urlThemes().'/mist/vendor/datatables-fixedheader/dataTables.fixedHeader.css' }}" rel="stylesheet">
        <link href="{{ urlThemes().'/mist/vendor/datatables-responsive/dataTables.responsive.css' }}" rel="stylesheet">
        <link href="{{ urlThemes().'/mist/vendor/dropify/dropify.css' }}" rel="stylesheet">
        <link href="{{ urlThemes().'/mist/vendor/fade-slideshow/css/jssor.slider.custom.css' }}" rel="stylesheet" />
        <link href="{{ urlThemes().'/mist/vendor/jquery-labelauty/jquery-labelauty.css' }}" rel="stylesheet" />
        <link href="{{ urlThemes().'/mist/vendor/webui-popover/webui-popover.css' }}" rel="stylesheet" />
        <!-- Chart -->
        <?php if (helperRouteModule() == 'dashboard'): ?>
        <link href="<?php echo urlThemes().'/mist/vendor/chartist-js/chartist.css' ?>" rel="stylesheet">
        <link href="<?php echo urlThemes().'/mist/vendor/chartist-plugin-tooltip/chartist-plugin-tooltip.min.css' ?>" rel="stylesheet">
        <?php endif ?>
         <!-- CUSTOM -->
        <link href="<?php echo urlBase().'/assets/home/css/site.css' ?>" type="text/css" rel="stylesheet" />
        <link rel="stylesheet" href="<?php echo urlThemes().'/remark/vendor/alertify-js/alertify.css' ?>">
        <!-- Module CSS -->
        <?php if (file_exists(helperDirPublic().'/assets/home/css/'.helperRouteModule().'/'.helperRouteAction().'.css')): ?>
            <link rel="stylesheet" href="<?php echo urlBase().'/assets/home/css/'.helperRouteModule().'/'.helperRouteAction().'.css' ?>">
        <?php endif ?>
    </head>
    <body>
        <!-- VUE -->
        <script src="<?php echo urlBase().'/assets/js/vue.js' ?>"></script>
        <!-- Response Flash Alert-->
        <div class="page-response"><?php echo helperResponseGet() ?></div>
        <!-- HEADER -->
        {!! view('customer.layouts.header', $data) !!}
        <div id="page">
            {!! view($page, $data) !!}
        </div>
        <!-- FOOTER -->
        {!! view('customer.layouts.footer', $data) !!}

        <!-- Scripts -->
        
        <script type="text/javascript" src="{{ urlThemes().'/mist/js/bootstrap.min.js' }}"></script>
        <!-- Menu jQuery plugin -->
        <script type="text/javascript" src="{{ urlThemes().'/mist/js/hover-dropdown-menu.js' }}"></script>
        <script type="text/javascript" src="{{ urlThemes().'/mist/js/jquery.hover-dropdown-menu-addon.js' }}"></script>
        <script type="text/javascript" src="{{ urlThemes().'/mist/js/jquery.easing.1.3.js' }}"></script>
        <script type="text/javascript" src="{{ urlThemes().'/mist/js/jquery.sticky.js' }}"></script>
        <script type="text/javascript" src="{{ urlThemes().'/mist/js/bootstrapValidator.min.js' }}"></script>
        <script type="text/javascript" src="{{ urlThemes().'/mist/rs-plugin/js/jquery.themepunch.tools.min.js' }}"></script>
        <script type="text/javascript" src="{{ urlThemes().'/mist/rs-plugin/js/jquery.themepunch.revolution.min.js' }}"></script>
        <script type="text/javascript" src="{{ urlThemes().'/mist/js/revolution-custom.js' }}"></script>
        <script type="text/javascript" src="{{ urlThemes().'/mist/js/jquery.mixitup.min.js' }}"></script>
        <script type="text/javascript" src="{{ urlThemes().'/mist/js/jquery.appear.js' }}"></script>
        <script type="text/javascript" src="{{ urlThemes().'/mist/js/effect.js' }}"></script>
        <script type="text/javascript" src="{{ urlThemes().'/mist/js/owl.carousel.min.js' }}"></script>
        <script type="text/javascript" src="{{ urlThemes().'/mist/js/jquery.prettyPhoto.js' }}"></script>
        <script type="text/javascript" src="{{ urlThemes().'/mist/js/jquery.parallax-1.1.3.js' }}"></script>
        <script type="text/javascript" src="{{ urlThemes().'/mist/js/jquery.countTo.js' }}"></script>
        <!-- Vendor -->
        <script src="{{ urlThemes().'/mist/vendor/bootbox/bootbox.js' }}"></script>
        <script src="{{ urlThemes().'/mist/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js' }}"></script>
        <script src="{{ urlThemes().'/mist/vendor/bootstrap-sweetalert/sweetalert.min.js' }}"></script>
        <script src="{{ urlThemes().'/mist/vendor/bootstrap-tokenfield/bootstrap-tokenfield.min.js' }}"></script>
        <script src="{{ urlThemes().'/mist/vendor/ckeditor/ckeditor.js' }}"></script>
        <script src="{{ urlThemes().'/mist/vendor/clockpicker/jquery-clockpicker.min.js' }}"></script>
        <script src="{{ urlThemes().'/mist/vendor/datatables/jquery.dataTables.js' }}"></script>
        <script src="{{ urlThemes().'/mist/vendor/datatables-fixedheader/dataTables.fixedHeader.js' }}"></script>
        <script src="{{ urlThemes().'/mist/vendor/datatables-bootstrap/dataTables.bootstrap.js' }}"></script>
        <script src="{{ urlThemes().'/mist/vendor/datatables-responsive/dataTables.responsive.js' }}"></script>
        <script src="{{ urlThemes().'/mist/vendor/datatables-tabletools/dataTables.tableTools.js' }}"></script>
        <script src="{{ urlThemes().'/mist/vendor/dropify/dropify.min.js' }}"></script>
        <script src="{{ urlThemes().'/mist/vendor/fade-slideshow/js/jssor.slider-22.1.8.min.js' }}"></script>
        <script src="{{ urlThemes().'/mist/vendor/fade-slideshow/js/jssor.slider.custom.js' }}"></script>
        <script src="{{ urlThemes().'/mist/vendor/jquery-labelauty/jquery-labelauty.js' }}"></script>
        <script src="{{ urlThemes().'/mist/vendor/webui-popover/jquery.webui-popover.js' }}"></script>

        <!-- Custom Js Code -->
        <script type="text/javascript" src="{{ urlBase().'/assets/js/jquery-3.3.1.min.js' }}"></script>
        <script type="text/javascript" src="{{ urlThemes().'/mist/js/custom.js' }}"></script>
        <script type="text/javascript" src="{{ urlBase().'/assets/home/js/site.js' }}"></script>
        <script type="text/javascript" src="{{ urlBase().'/assets/js/fb.js' }}"></script>
        <script type="text/javascript" src="{{ urlBase().'/assets/js/md5.js' }}"></script>
        
        <script type="text/javascript" src="{{ urlThemes().'/remark/vendor/alertify-js/alertify.js' }}"></script>

        <!-- Module Script -->
        <?php if (file_exists(helperDirPublic().'/assets/home/js/'.helperRouteModule().'/'.helperRouteAction().'.js')): ?>
            <script src="<?php echo urlBase().'/assets/home/js/'.helperRouteModule().'/'.helperRouteAction().'.js' ?>"></script>
        <?php endif ?>
        <!-- Prevent to open in new window -->
        <script>(function(a,b,c){if(c in b&&b[c]){var d,e=a.location,f=/^(a|html)$/i;a.addEventListener("click",function(a){d=a.target;while(!f.test(d.nodeName))d=d.parentNode;"href"in d&&(d.href.indexOf("http")||~d.href.indexOf(e.host))&&(a.preventDefault(),e.href=d.href)},!1)}})(document,window.navigator,"standalone")</script>
    </body>
</html>