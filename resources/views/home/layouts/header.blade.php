<header id="mw-header-home">
    <!-- TOP BAR -->
    <div class="navbar navbar-default mw-bg-color-grey hidden-xs" role="navigation" style="max-height: 240px">
        <div class="mw-bg-white mw-center mw-header-logo">
            <img width="100%" class="" alt="AIR FORCEONE EXPRESS" style="margin-left: 10px"
            src="{{ urlHomeImage().'/logo-header.png' }}" />
        </div>
        @if (helperRouteModule() !== 'public_tracking')
        <div class="mw-header-search-promotion" style="padding: 60px 10px 0 10px">
            <div class="row">
                <div class="col-xs-12 mw-pd-0">
                    <h1 class="mw-center mw-font-2" style="font-size: 2em">ติดตามและตรวจสอบสถานะการขนส่ง</h1>
                    <div class="input-group">
                        <input type="text" name="tracking_key"
                        class="form-control mw-center mw-font" placeholder="เลขใบนำส่งพัสดุ XXXXXX"
                        style="text-transform:uppercase; font-size: 20px; min-width: 400px">
                        <div id="mw-btn-header-track" class="mw-bg-color mw-white mw-font input-group-addon btn">
                            ตรวจสอบ
                        </div>
                    </div>
                </div>
                <div class="col-sm-3 col-sm-offset-0 col-xs-6 col-xs-offset-3">
                </div>
                <div class="col-xs-1">
                </div>
            </div>
        </div>
        @endif
    </div>
    <!-- SLOGAN BAR -->
    <div class="mw-bg-color navbar navbar-default mw-header-bar-slogan hidden-xs">
    </div>
    <!-- MENU BAR -->
    <div class="navbar navbar-default mw-menu-bar-contain">
        <!-- MOBILE MENU BAR -->
        <div class="navbar-header mw-bg-color mw-xs-fixed hidden-lg hidden-md hidden-sm">
            <div class="navbar navbar-default mw-menu-bar-slogan mw-bg-color hidden-lg hidden-md hidden-sm">
                <!-- <img class="mw-header-logo logo-mobile navbar-toggle mw-mg-0" data-toggle="collapse"
                alt="AIR FORCEONE EXPRESS" src="{{ urlAdminImage().'/logo_header.png' }}" /> -->
                <h4 class="mw-white pull-left" style="padding: 12px; margin: 0;">AIR FORCEONE EXPRESS</h4>
                <button id="mw-btn-collapse" type="button" class="navbar-toggle mw-mg-0"
                    data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar mw-bg-white"></span>
                    <span class="icon-bar mw-bg-white"></span>
                    <span class="icon-bar mw-bg-white"></span>
                </button>
            </div>
        </div>
        <!-- MENU BAR -->
        <div class="navbar-collapse mw-menu-bar-collapse collapse mw-bg-grey">
            <ul class="mw-menu-bar nav navbar-nav pull-left mw-left" style="width: 100%">
                <!-- SEARCH -->
                <li class="hidden-lg hidden-md hidden-sm mw-pd-10">
                    <div class="input-group mw-btn-search " style="">
                        <span class="input-group-addon mw-white mw-btn-search-product"><i class="fa-search"></i> ค้นหาสินค้า</span>
                        <input type="text" name="mw_search" class="form-control mw-bg-color mw-font">
                    </div>
                </li>
                <!-- LEFT -->
                <li style="margin: 0">
                    <a class="mw-black" href="{{ \URL::route('home.main.index.get') }}" style="font-size: 14px">
                        Home
                    </a>
                </li>
                <li style="margin: 0">
                    <a class="mw-black" href="#"  style="font-size: 14px">
                        Pricing
                    </a>
                </li>
                <li >
                    <a class="mw-black" href="{{ \URL::route('home.public_tracking.index.get') }}"  style="font-size: 14px">
                        Tracking
                    </a>
                </li>
                <!-- RIGHT -->
                <!-- LOGIN -->
                @if($userCustomer->isLogged())
                <li class="pull-right shoping-cart mw-color-header-2">
                    <a class="mw-bg-color-2 mw-white" href="{{ \URL::route('home.tracking.index.get') }}"
                    style="font-size: 14px; margin-right: 20px">
                        ข้อมูลการส่งพัสดุ <i class="fa-angle-double-right"></i>
                    </a>
                </li>
                <li class="pull-right" style="font-size: 14px; margin-right: 20px; margin-top: 4px;">
                    ยินดีต้อนรับ {{ $userCustomer->getName() }}
                </li>
                @else
                <li class="pull-right shoping-cart mw-color-header-2">
                    <a class="mw-btn-popup-login mw-black" href="#" style="font-size: 14px; margin-right: 20px">
                        <i class="fa-key"></i>
                        เข้าสู่ระบบ
                    </a>
                </li>
                @endif
            </ul>
        </div>
    </div>
    <!-- MENU BAR WHEN SCROLL DOWN -->
    <div class="navbar navbar-default mw-header-small mw-menu-bar-contain" role="navigation">
        <div style="width: 100%; height: 60px;">
            <ul class="nav navbar-nav mw-bg-white mw-black" style="width: 100%; height: 60px;">
                <li style="margin: 5px 0">
                    <a href="#">
                    </a>
                </li>
                <!-- LOGO -->
                <li class="pull-left" style="margin-left: 2%;">
                    <img style="height: 60px" alt="AIR FORCEONE EXPRESS" src="{{ urlHomeImage().'/logo-header.png' }}" />
                </li>
                <!-- CONTACT -->
                <li class="pull-right" style="margin: 5px 0; width: 40%; padding-top: 1%">
                    <h1 class="mw-right mw-font-2" style="margin-right: 5%; font-size: 1.4em">
                        We makes it easier for EVERYONE.
                    </h1>
                </li>
            </ul>
        </div>
        <div style="width: 100%;">
            <ul class="nav navbar-nav mw-bg-color mw-color" style="height: 10px">
                <li></li>
            </ul>
        </div>
        <div style="width: 100%">
            <ul class="nav navbar-nav mw-bg-grey mw-white" style="height: 30px">
                 <li style="margin: 0">
                    <a class="mw-black" href="{{ \URL::route('home.main.index.get') }}" style="font-size: 14px">
                        Home
                    </a>
                </li>
                <li style="margin: 0">
                    <a class="mw-black" href="#"  style="font-size: 14px">
                        Pricing
                    </a>
                </li>
                <li >
                    <a class="mw-black" href="{{ \URL::route('home.public_tracking.index.get') }}"  style="font-size: 14px">
                        Tracking
                    </a>
                </li>
                <!-- LOGIN -->
                @if($userCustomer->isLogged())
                <li class="pull-right shoping-cart mw-color-header-2">
                    <a class="mw-bg-color-2 mw-white" href="{{ \URL::route('home.tracking.index.get') }}"
                    style="font-size: 14px; margin-right: 20px; height: 30px; line-height: 30px;">
                        ข้อมูลการส่งพัสดุ <i class="fa-angle-double-right"></i>
                    </a>
                </li>
                <li class="pull-right" style="font-size: 14px; margin-right: 20px; margin-top: 4px;">
                    ยินดีต้อนรับ {{ $userCustomer->getName() }}
                </li>
                @else
                <li class="pull-right shoping-cart mw-color-header-2">
                    <a class="mw-btn-popup-login mw-black" href="#" style="font-size: 14px; margin-right: 20px; margin-top: 4px">
                        <i class="fa-key"></i>
                        เข้าสู่ระบบ
                    </a>
                </li>
                @endif
            </ul>
        </div>
    </div>
    <!-- DATA -->
    <div id="mw-url-tracking" data-url="{{ \URL::route('home.public_tracking.index.get') }}"></div>
</header>

