<!DOCTYPE html>
<html class="no-js css-menubar" lang="en">
<head>
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
    <meta name="keywords" content="AIRFORCEONE-EXPRESS">
    <meta name="author" content="MEWITTY">
    <!-- Favicon -->
    <link rel="shortcut icon" href="<?php echo urlHomeImage().'/logo-favicon.png?' ?>">
    <!-- Stylesheets -->
    <link rel="stylesheet" href="<?php echo urlThemes().'/remark/css/bootstrap.min.css' ?>">
    <link rel="stylesheet" href="<?php echo urlThemes().'/remark/css/bootstrap-extend.min.css' ?>">
    <link rel="stylesheet" href="<?php echo urlThemes().'/remark/css/site.min.css' ?>">
    <!-- Plugins -->
    <link rel="stylesheet" href="<?php echo urlThemes().'/remark/vendor/animsition/animsition.css' ?>">
    <link rel="stylesheet" href="<?php echo urlThemes().'/remark/vendor/asscrollable/asScrollable.css' ?>">
    <link rel="stylesheet" href="<?php echo urlThemes().'/remark/vendor/bootstrap-sweetalert/sweetalert.css' ?>">
    <!-- Fonts -->
    <link rel="stylesheet" href="<?php echo urlThemes().'/remark/fonts/font-awesome/font-awesome.css' ?>">
    <link rel="stylesheet" href="<?php echo urlThemes().'/fonts/Kanit/Kanit.css' ?>" >

    <!-- Custom -->
    <link rel="stylesheet" href="<?php echo urlThemes().'/remark/css/login.css' ?>">
    <link rel="stylesheet" href="<?php echo urlBase().'/assets/home/css/authen/login.css' ?>">
    <link rel="stylesheet" href="<?php echo urlBase().'/assets/home/css/site.css' ?>">
    <!--[if lt IE 9]>
      <script src="../../assets/html5shiv/html5shiv.min.js"></script>
      <![endif]-->
    <!--[if lt IE 10]>
      <script src="../../assets/media-match/media.match.min.js"></script>
      <script src="../../assets/respond/respond.min.js"></script>
      <![endif]-->
    <!-- Scripts -->
    <script src="<?php echo urlThemes().'/remark/vendor/modernizr/modernizr.js' ?>"></script>
    <script src="<?php echo urlThemes().'/remark/vendor/breakpoints/breakpoints.js' ?>"></script>
    <script>Breakpoints();</script>
</head>
<body class="mw-body-login page-login layout-full">
    <!--[if lt IE 8]>
        <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->
    <div class="page text-center" style="margin-top: 30px !important" data-animsition-in="fade-in" data-animsition-out="fade-out">
        <img class="mw-hero-login hidden-xs" src="{{ urlHomeImage().'/hero.png' }}">
        <div class="mw-register-box">
            <div class="brand">
                <img class="brand-img mw-pd-20" width="80%"
                src="{{ urlHomeImage().'/logo-header.png' }}" alt="AIRFORCEONE-EXPRESS">
            </div>
            <div class="mw-mg-tb-10">
                <h1 style="font-size: 1em;">บริษัท แอร์ฟอร์ส วัน เอ็กเพลส <br/> ยินดีต้อนรับลูกค้าทุกท่าน</h1>
                <!-- Username -->
                <div class="form-group">
                    <label class="sr-only" for="inputName">Email</label>
                    <input type="text" class="form-control" id="mw-input-email" name="email" placeholder="Email *">
                </div>
                <!-- Password -->
                <div class="form-group">
                    <label class="sr-only" for="inputPassword">รหัสผ่าน</label>
                    <input type="password" class="form-control" id="mw-input-pwd" name="password" placeholder="รหัสผ่าน *">
                </div>
                <!-- SignUp Button -->
                <div class="mw-text-error" id="form-error-invalid"></div>
                <div id="mw-btn-login" class="btn btn-color btn-block" style="font-size: 16px">เข้าสู่ระบบ</div>
                <hr style="margin-top: 10px" />
                <!-- REGISTER -->
                <div class="row mw-mg-tp-20">
                    <div class="col-xs-12 mw-center">
                        <p class="mw-black mw-inline" style="margin-right: 10px">บริการรับส่งเอกสารและพัสดุภายในประเทศ </p>
                        <div id="mw-btn-register" class="btn btn-color btn-outline mw-inline" style="font-size: 16px">
                            ลงทะเบียน
                        </div>
                    </div>
                </div>
                <hr />
                <div class="row mw-mg-tp-20">
                    <div class="col-xs-6 mw-center">
                        <a class="btn mw-black" style="font-size: 1em" href="#">
                            <i class="icon fa-phone"></i>
                            ติดต่อเรา
                        </a>
                    </div>
                    <div class="col-xs-6 mw-center">
                        <a class="btn btn-link mw-black" style="font-size: 1em" href="#">
                            ? ลืมรหัสผ่าน
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <img class="mw-bg-login" src="{{ urlHomeImage().'/bg-login.jpg' }}">
    </div>

    <!-- Data -->
    <div id="mw-url-auth" data-value="<?php echo \URL::route('home.authen.login.post') ?>"></div>
    <!-- Core  -->
    <script src="<?php echo urlThemes().'/remark/vendor/jquery/jquery.js' ?>"></script>
    <script src="<?php echo urlThemes().'/remark/vendor/bootstrap/bootstrap.js' ?>"></script>
    <script src="<?php echo urlThemes().'/remark/vendor/animsition/jquery.animsition.js' ?>"></script>
    <!-- Plugins -->
    <script src="<?php echo urlThemes().'/remark/vendor/bootbox/bootbox.js' ?>"></script>
    <script src="<?php echo urlThemes().'/remark/vendor/bootstrap-sweetalert/sweetalert.min.js' ?>"></script>
    <script src="<?php echo urlThemes().'/remark/vendor/screenfull/screenfull.js' ?>"></script>
    <!-- Scripts -->
    <script src="<?php echo urlThemes().'/remark/js/core.js' ?>"></script>
    <script src="<?php echo urlThemes().'/remark/js/site.js' ?>"></script>
    <script src="<?php echo urlThemes().'/remark/js/sections/menu.js' ?>"></script>
    <script src="<?php echo urlThemes().'/remark/js/sections/gridmenu.js' ?>"></script>
    <script src="<?php echo urlThemes().'/remark/js/sections/sidebar.js' ?>"></script>
    <script src="<?php echo urlThemes().'/remark/js/configs/config-colors.js' ?>"></script>
    <script src="<?php echo urlThemes().'/remark/js/configs/config-tour.js' ?>"></script>
    <script src="<?php echo urlThemes().'/remark/js/components/asscrollable.js' ?>"></script>
    <script src="<?php echo urlThemes().'/remark/js/components/animsition.js' ?>"></script>
    <script src="<?php echo urlThemes().'/remark/js/components/slidepanel.js' ?>"></script>
    <script src="<?php echo urlThemes().'/remark/js/components/switchery.js' ?>"></script>
    <script src="<?php echo urlThemes().'/remark/js/components/matchheight.js' ?>"></script>
    <script src="<?php echo urlThemes().'/remark/js/components/jvectormap.js' ?>"></script>
    <!-- Custom -->
    <script src="<?php echo urlBase().'/assets/js/md5.js' ?>"></script>
    <script src="<?php echo urlBase().'/assets/home/js/authen/login.js' ?>"></script>
</body>
</html>