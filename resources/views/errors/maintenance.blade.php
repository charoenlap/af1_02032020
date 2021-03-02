<html>
  <head>
    <meta charset="utf-8">
    <meta property="og:url" content="<?php echo url('/') ?>" />
    <meta property="og:type" content="<?php echo 'website' ?>" />
    <meta property="og:site_name" content="<?php echo "SIAM'S LIP" ?>" />
    <meta property="og:title" content="<?php echo "SIAM'S LIP" ?>" />
    <meta property="og:description" content="<?php echo "SIAM'S LIP" ?>" />
    <meta property="og:image" content="<?php echo '/assets/home/images/logo.png' ?>" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>SIAM'S LIP</title>

    <meta name="description" content="">
    <meta name="author" content="SIAM'S LIP">
    <link rel="shortcut icon" href="<?php echo urlHomeImage().'/favicon.png' ?>">
    <!-- <link href='//fonts.googleapis.com/css?family=Lato' rel='stylesheet' type='text/css'> -->
    <link rel="stylesheet" href="/assets/errors/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/errors/css/bootstrap-extend.min.css">
    <link rel="stylesheet" href="/assets/errors/css/site.min.css">

    <link rel="stylesheet" href="/assets/errors/fonts/brand-icons/brand-icons.min.css">
    <link rel="stylesheet" href="/assets/errors/fonts/font-awesome/font-awesome.css">
    <link rel="stylesheet" href="/assets/errors/fonts/web-icons/web-icons.min.css">
    <link rel="stylesheet" href="/assets/themes/mist/fonts/Kanit/Kanit.css">
    <style>
      body {
        margin: 0;
        padding: 0;
        width: 100%;
        height: 100%;
        /*color: #B0BEC5;*/
        display: table;
        font-weight: 100;
        font-family: 'Kanit';
      }
      .mw-font {
        font-family: 'Kanit' !important;
      }
      @media screen and (max-width: 766px) {
        .mw-font {
          font-size: 1em;
        }
      }
      .container {
        text-align: center;
        display: table-cell;
        vertical-align: middle;
      }

      .content {
        text-align: center;
        display: inline-block;
      }

      .title {
        font-size: 72px;
        margin-bottom: 40px;
      }
      .icon-spin {
        -webkit-animation: icon-spin 2s infinite linear;
             -o-animation: icon-spin 2s infinite linear;
                animation: icon-spin 2s infinite linear;
      }
    </style>
  </head>
  <body>
    <div class="container">
      <div class="page-content vertical-align-middle" style="width: 100%;">
          <div class="col-md-5 col-md-offset-1 col-sm-12" style="border: 1px solid #b65657; padding: 0">
            <img src="/assets/home/images/logo-tmp.jpg" width="100%">
          </div>
          <div class="col-md-5 col-sm-12" style="margin-top: 9%">
            <i class="icon wb-settings icon-spin page-maintenance-icon" style="font-size: 64px" aria-hidden="true"></i>
            <h2 class="mw-font">Coming Soon</h2>
            <h3 class="mw-font">SIAM'S LIP ไซแอมลิป</h3>
            <footer class="">
                <div style="font-size:16px; margin-bottom: 40px;">
                  <a class="mw-font" href="#"
                    style="color: #424242; font-size:16px; text-decoration: none;">
                      <i class="icon fa-check-square-o" aria-hidden="true"></i> admin@mewitty.com
                  </a>
                  <a href="javascript:void(0)" style="text-decoration: none; color: rgba(55,71,79,.6); font-size:16px; margin: 10px">
                  :
                  </a>
                  <a class="mw-font" href="javascript:void(0)" style="color: #424242; font-size:16px; text-decoration: none;">
                    <i class="icon fa fa-phone" aria-hidden="true"></i> 064-559-2234
                  </a>
                </div>
                <p class="mw-font">Website by MEWITTY DEGITAL MEDIA CO., LTD.</p>
                <p class="mw-font">© 2016 - {{ date('Y') }}. All RIGHT RESERVED.</p>
            </footer>
          </div>
        </div>
    </div>
  </body>
</html>
