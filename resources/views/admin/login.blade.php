<!DOCTYPE html>
<html class="no-js css-menubar" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="bootstrap admin template">
    <meta name="author" content="">
    <meta name="csrf-token" content="<?php echo csrf_token() ?>">
    <title>LOGIN | AIRFORCE ONE EXPRESS</title>
    <!-- Favicon -->
    <link rel="shortcut icon" href="<?php echo urlAdminImage().'/favicon.png' ?>">
    <!-- Stylesheets -->
    <link rel="stylesheet" href="<?php echo urlThemes().'/remark/css/bootstrap.min.css' ?>">
    <link rel="stylesheet" href="<?php echo urlThemes().'/remark/css/bootstrap-extend.min.css' ?>">
    <link rel="stylesheet" href="<?php echo urlThemes().'/remark/css/site.min.css' ?>">
    <!-- Plugins -->
    <link rel="stylesheet" href="<?php echo urlThemes().'/remark/vendor/animsition/animsition.css' ?>">
    <link rel="stylesheet" href="<?php echo urlThemes().'/remark/vendor/asscrollable/asScrollable.css' ?>">
    <link rel="stylesheet" href="<?php echo urlThemes().'/remark/vendor/bootstrap-sweetalert/sweetalert.css' ?>">
    <!-- Fonts -->
    <link rel="stylesheet" href="<?php echo urlThemes().'/remark/fonts/weather-icons/weather-icons.css' ?>">
    <link rel="stylesheet" href="<?php echo urlThemes().'/remark/fonts/web-icons/web-icons.min.css' ?>">
    <link rel="stylesheet" href="<?php echo urlThemes().'/remark/fonts/roboto/roboto.css' ?>" >

    <!-- Custom -->
    <link rel="stylesheet" href="<?php echo urlThemes().'/remark/css/login.css' ?>">
    <link rel="stylesheet" href="<?php echo urlBase().'/assets/admin/css/authen/login.css' ?>">
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
<body class="mw-body-login page-login layout-full mw-white">
    <!--[if lt IE 8]>
        <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->
    <div class="page animsition vertical-align text-center" data-animsition-in="fade-in" data-animsition-out="fade-out">>
        <div class="page-content vertical-align-middle">
            <div class="brand">
                <img class="brand-img" width="100%" src="{{ urlAdminImage().'/logo-header.png' }}" alt="...">
                <h3 class="brand-text mw-grey" style="margin: 40px 0 20px 0">บริษัท แอร์ฟอร์ส วัน เอ็กเพรส จำกัด</h3>
            </div>
            <form style="margin: auto">
                <!-- Username -->
                <div class="form-group">
                    <label class="sr-only" for="inputName">รหัสพนักงาน</label>
                    <input type="text" class="form-control" id="mw-input-empid" name="empid" placeholder="รหัสพนักงาน"
                    style="text-align: center;" value="0001">
                    <div class="mw-text-error" id="form-error-empid"></div>
                </div>
                <!-- Password -->
                <div class="form-group">
                    <label class="sr-only" for="inputPassword">พาสเวิร์ด</label>
                    <input type="password" class="form-control" id="mw-input-pwd" name="passwd" placeholder="พาสเวิร์ด"
                    style="text-align: center;" value="1234">
                    <div class="mw-text-error mw-white" id="form-error-passwd"></div>
                </div>
                <!-- SignUp Button -->
                <div id="mw-btn-login" class="btn btn-primary btn-block">Sign In</div>
                <div class="mw-text-error" id="form-error-invalid"></div>
            </form>
            <p class="text-align-center" style="margin-top: 80px">AIRFORCE ONE EXPRESS CO., LTD.</p>
            <p class="text-align-center">{{ '© 2016 - '.date('Y').'. All RIGHT RESERVED.' }}</p>
        </div>
    </div>

    <!-- Data -->
    <div id="mw-url-auth" data-value="{{ \URL::route('admin.authen.login.post') }}"></div>

    <!-- Core  -->
    <script src="<?php echo urlThemes().'/remark/vendor/jquery/jquery.js' ?>"></script>
    <script src="<?php echo urlThemes().'/remark/vendor/bootstrap/bootstrap.js' ?>"></script>
    <script src="<?php echo urlThemes().'/remark/vendor/animsition/jquery.animsition.js' ?>"></script>
    <!-- Plugins -->
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
    <script src="<?php echo urlBase().'/assets/admin/js/authen/login.js' ?>"></script>
</body>
</html>