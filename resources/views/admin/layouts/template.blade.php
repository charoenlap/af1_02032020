<!DOCTYPE html>
<html class="no-js css-menubar" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="bootstrap admin template">
    <meta name="author" content="">
    <meta name="csrf-token" content="<?php echo csrf_token() ?>">
    <title>AIRFORCE ONE EXPRESS</title>
    <!-- Favicon -->
    <link rel="shortcut icon" href="<?php echo urlAdminImage().'/favicon.png' ?>">
    <!-- Stylesheets -->
    <link rel="stylesheet" href="<?php echo urlThemes().'/remark/css/bootstrap.css' ?>">
    <link rel="stylesheet" href="<?php echo urlThemes().'/remark/css/bootstrap-extend.css' ?>">
    <link rel="stylesheet" href="<?php echo urlThemes().'/remark/css/site.min.css' ?>">
    <!-- Plugins -->
    <link rel="stylesheet" href="<?php echo urlThemes().'/remark/vendor/alertify-js/alertify.css' ?>">
    <link rel="stylesheet" href="<?php echo urlThemes().'/remark/vendor/animsition/animsition.css' ?>">
    <link rel="stylesheet" href="<?php echo urlThemes().'/remark/vendor/asscrollable/asScrollable.css' ?>">
    <link rel="stylesheet" href="<?php echo urlThemes().'/remark/vendor/bootstrap-datepicker/bootstrap-datepicker.min.css' ?>">
    <link rel="stylesheet" href="<?php echo urlThemes().'/remark/vendor/bootstrap-select/bootstrap-select.min.css' ?>">
    <link rel="stylesheet" href="<?php echo urlThemes().'/remark/vendor/bootstrap-sweetalert/sweetalert.css' ?>">
    <link rel="stylesheet" href="<?php echo urlThemes().'/remark/vendor/bootstrap-tokenfield/bootstrap-tokenfield.css' ?>">
    <link rel="stylesheet" href="<?php echo urlThemes().'/remark/vendor/bootstrap-touchspin/bootstrap-touchspin.min.css' ?>">
    <link rel="stylesheet" href="<?php echo urlThemes().'/remark/vendor/clockpicker/clockpicker.css' ?>">
    <link rel="stylesheet" href="<?php echo urlThemes().'/remark/vendor/datatables-bootstrap/dataTables.bootstrap.css' ?>">
    <link rel="stylesheet" href="<?php echo urlThemes().'/remark/vendor/datatables-fixedheader/dataTables.fixedHeader.css' ?>">
    <link rel="stylesheet" href="<?php echo urlThemes().'/remark/vendor/datatables-responsive/dataTables.responsive.css' ?>">
    <link rel="stylesheet" href="<?php echo urlThemes().'/remark/vendor/dropify/dropify.css' ?>">
    <link rel="stylesheet" href="<?php echo urlThemes().'/remark/vendor/filament-tablesaw/tablesaw.css' ?>">
    <link rel="stylesheet" href="<?php echo urlThemes().'/remark/vendor/flag-icon-css/flag-icon.css' ?>">
    <link rel="stylesheet" href="<?php echo urlThemes().'/remark/vendor/jquery-labelauty/jquery-labelauty.css' ?>" />
    <link rel="stylesheet" href="<?php echo urlThemes().'/remark/vendor/select2/select2.min.css' ?>">
    <link rel="stylesheet" href="<?php echo urlThemes().'/remark/vendor/switchery/switchery.min.css' ?>">
    <link rel="stylesheet" href="<?php echo urlThemes().'/remark/vendor/webui-popover/webui-popover.css' ?>">

    <!-- Fonts -->
    <link rel="stylesheet" href="<?php echo urlThemes().'/remark/fonts/font-awesome/font-awesome.css' ?>">
    <link rel="stylesheet" href="<?php echo urlThemes().'/remark/fonts/web-icons/web-icons.min.css' ?>">
    <link rel="stylesheet" href="<?php echo urlThemes().'/remark/fonts/roboto/roboto.css' ?>" >

    <!-- Custom -->
    <link rel="stylesheet" href="<?php echo urlBase().'/assets/admin/css/site.css' ?>">
     <!-- Module CSS -->
    <?php if (file_exists(helperDirPublic().'/assets/admin/css/'.helperRouteModule().'/'.helperRouteAction().'.css')): ?>
        <link rel="stylesheet" href="<?php echo urlBase().'/assets/admin/css/'.helperRouteModule().'/'.helperRouteAction().'.css' ?>">
    <?php endif ?>
    <!-- Chart -->
    <?php if (in_array(helperRouteModule(), ['dashboard', 'report', 'report_booking'])): ?>
    <link rel="stylesheet" href="<?php echo urlThemes().'/remark/vendor/chartist-js/chartist.css' ?>">
    <link rel="stylesheet" href="<?php echo urlThemes().'/remark/vendor/chartist-plugin-tooltip/chartist-plugin-tooltip.min.css' ?>">
    <?php endif ?>

    <!--[if lt IE 9]>
      <script src="../assets/html5shiv/html5shiv.min.js"></script>
      <![endif]-->
    <!--[if lt IE 10]>
      <script src="../assets/media-match/media.match.min.js"></script>
      <script src="../assets/respond/respond.min.js"></script>
      <![endif]-->
    <!-- Scripts -->
    <script src="<?php echo urlThemes().'/remark/vendor/modernizr/modernizr.js' ?>"></script>
    <script src="<?php echo urlThemes().'/remark/vendor/breakpoints/breakpoints.js' ?>"></script>
    <script src="<?php echo urlThemes().'/remark/vendor/jquery/jquery-2.1.3.min.js' ?>"></script>

    <script src="http://af1express.com/assets/themes/datatable/datatables.min.js"></script>
    <script src="<?php echo urlThemes().'/remark/vendor/datatables-fixedheader/dataTables.fixedHeader.js' ?>"></script>
    <script src="<?php echo urlThemes().'/remark/vendor/datatables-bootstrap/dataTables.bootstrap.js' ?>"></script>
    <script src="<?php echo urlThemes().'/remark/vendor/datatables-responsive/dataTables.responsive.js' ?>"></script>
    <script src="<?php echo urlThemes().'/remark/vendor/datatables-tabletools/dataTables.tableTools.js' ?>"></script>
    <script>Breakpoints();</script>
</head>
<body class="dashboard {{ helperMenubarIsFold() }}">
    <!--[if lt IE 8]>
        <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->
    <script src="{{ urlBase().'/assets/js/vue.js' }}"></script>
    <!-- Header -->
    <?php echo view("admin.layouts.header", $data) ?>
    <!-- Sidebar -->
    <?php echo view("admin.layouts.sidebar", $data) ?>
    <div class="page">
        <!-- Response Flash Alert-->
        <div class="page-response">{!! helperResponseGet() !!}</div>
        <!-- Page -->
        <?php echo view($page, $data); ?>
    </div>

    <!-- Footer -->
    <?php //echo view("admin.layouts.footer") ?>

    <!-- Core  -->
    

    <script src="<?php echo urlThemes().'/remark/vendor/bootstrap/bootstrap.js' ?>"></script>
    <script src="<?php echo urlThemes().'/remark/vendor/animsition/jquery.animsition.js' ?>"></script>
    <script src="<?php echo urlThemes().'/remark/vendor/ashoverscroll/jquery-asHoverScroll.js' ?>"></script>
    <script src="<?php echo urlThemes().'/remark/vendor/asscroll/jquery-asScroll.js' ?>"></script>
    <script src="<?php echo urlThemes().'/remark/vendor/asscrollable/jquery.asScrollable.all.js' ?>"></script>
    <script src="<?php echo urlThemes().'/remark/vendor/mousewheel/jquery.mousewheel.js' ?>"></script>
    <!-- Plugins -->
    <script src="<?php echo urlThemes().'/remark/vendor/jquery-ui/jquery-ui.js' ?>"></script>
    <script src="<?php echo urlThemes().'/remark/vendor/alertify-js/alertify.js' ?>"></script>
    <script src="<?php echo urlThemes().'/remark/vendor/bootbox/bootbox.js' ?>"></script>
    <script src="<?php echo urlThemes().'/remark/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js' ?>"></script>
    <script src="<?php echo urlThemes().'/remark/vendor/bootstrap-select/bootstrap-select.min.js' ?>"></script>
    <script src="<?php echo urlThemes().'/remark/vendor/bootstrap-sweetalert/sweetalert.min.js' ?>"></script>
    <script src="<?php echo urlThemes().'/remark/vendor/bootstrap-tokenfield/bootstrap-tokenfield.js' ?>"></script>
    <script src="<?php echo urlThemes().'/remark/vendor/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js' ?>"></script>
    <script src="<?php echo urlThemes().'/remark/vendor/ckeditor/ckeditor.js' ?>"></script>
    <script src="<?php echo urlThemes().'/remark/vendor/clockpicker/bootstrap-clockpicker.js' ?>"></script>
    <script src="<?php echo urlThemes().'/remark/vendor/webui-popover/jquery.webui-popover.js' ?>"></script>

    <!-- Scripts -->
    <script src="<?php echo urlThemes().'/remark/js/core.js' ?>"></script>
    <script src="<?php echo urlThemes().'/remark/js/site.js' ?>"></script>
    <script src="<?php echo urlThemes().'/remark/js/sections/menu.js' ?>"></script>
    <script src="<?php echo urlThemes().'/remark/js/sections/menubar.js' ?>"></script>
    <script src="<?php echo urlThemes().'/remark/js/sections/gridmenu.js' ?>"></script>
    <script src="<?php echo urlThemes().'/remark/js/sections/sidebar.js' ?>"></script>
    <script src="<?php echo urlThemes().'/remark/js/configs/config-colors.js' ?>"></script>
    <script src="<?php echo urlThemes().'/remark/js/configs/config-tour.js' ?>"></script>
    <script src="<?php echo urlThemes().'/remark/js/components/asscrollable.js' ?>"></script>
    <script src="<?php echo urlThemes().'/remark/js/components/animsition.js' ?>"></script>
    <script src="<?php echo urlThemes().'/remark/js/components/slidepanel.js' ?>"></script>
    <script src="<?php echo urlThemes().'/remark/js/components/switchery.js' ?>"></script>
    <script src="<?php echo urlThemes().'/remark/js/components/matchheight.js' ?>"></script>
    <script src="<?php echo urlThemes().'/remark/js/components/material.js' ?>"></script>
    <script src="<?php echo urlThemes().'/remark/js/components/jvectormap.js' ?>"></script>
    <!-- Vendor -->
    <script src="<?php echo urlThemes().'/remark/vendor/filament-tablesaw/tablesaw.js' ?>"></script>
    <script src="<?php echo urlThemes().'/remark/vendor/filament-tablesaw/tablesaw-init.js' ?>"></script>

    <script src="<?php echo urlThemes().'/remark/vendor/dropify/dropify.min.js' ?>"></script>
    <script src="<?php echo urlThemes().'/remark/vendor/jquery-labelauty/jquery-labelauty.js' ?>"></script>
    <script src="<?php echo urlThemes().'/remark/vendor/row-sorter/RowSorter.js' ?>"></script>
    <script src="<?php echo urlThemes().'/remark/vendor/screenfull/screenfull.js' ?>"></script>
    <script src="<?php echo urlThemes().'/remark/vendor/select2/select2.min.js' ?>"></script>
    <script src="<?php echo urlThemes().'/remark/vendor/switchery/switchery.min.js' ?>"></script>


    <!-- Custom -->
    

    <script src="<?php echo urlBase().'/assets/admin/js/site.js' ?>"></script>
    <script src="<?php echo urlBase().'/assets/js/md5.js' ?>"></script>
    <!-- Module Script -->
    <?php if (file_exists(helperDirPublic().'/assets/admin/js/'.helperRouteModule().'/'.helperRouteAction().'.js')): ?>
        <script src="<?php echo urlBase().'/assets/admin/js/'.helperRouteModule().'/'.helperRouteAction().'.js' ?>"></script>
    <?php endif ?>
    <!-- Chart -->
    <?php if (in_array(helperRouteModule(), ['dashboard', 'report', 'report_booking', 'report_messenger'])): ?>
    <script src="<?php echo urlThemes().'/remark/vendor/chartist-js/chartist.min.js' ?>"></script>
    <script src="<?php echo urlThemes().'/remark/vendor/chartist-plugin-tooltip/chartist-plugin-tooltip.min.js' ?>"></script>
    <?php endif ?>
</body>
</html>