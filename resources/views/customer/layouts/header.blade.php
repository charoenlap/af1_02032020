<header id="sticker" class="mw-header-sticky">
    <div class="sticky-menu">
        <div class="mw-bg-white navbar navbar-default" role="navigation" style="padding: 5px 10px;">
            <div class="row">
                <div class="col-md-12">
                    <div class="navbar-header mw-header-logo-group">
                        <!-- Button For Responsive toggle -->
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar mw-bg-color"></span>
                        <span class="icon-bar mw-bg-color"></span>
                        <span class="icon-bar mw-bg-color"></span></button>
                        <!-- Logo -->
                        <a class="" href="{{ \URL::route('home.main.index.get') }}">
                            <img style="height: 70px" class="mw-site-logo" alt="AIRFORCEONE EXPRESS" src="{{ urlHomeImage().'/logo-header.png' }}">
                        </a>
                    </div>
                    <div class="navbar-collapse collapse mw-header-collapase" aria-expanded="false">
                        <ul class="nav navbar-nav md" style="margin-right: 20px">
                            <li class="shoping-cart">
                                <a href="{{ \URL::route('home.tracking.index.get') }}" class="btn mw-color">
                                    <i class="icon fa fa-search" aria-hidden="true"></i>
                                    TRACKING
                                </a>
                            </li>
                            <li class="shoping-cart">
                                <a class="has-submenu btn mw-color" href="{{ \URL::route('home.booking.index.get') }}"
                                style="height: 60px; font-size: 1em">
                                    <i class="fa-plus"></i> เปิดงานใหม่
                                </a>
                            </li>
                            <li class="shoping-cart">
                                <a class="has-submenu mw-color" href="#" onclick="return false" style="height: 60px; font-size: 1em">
                                    <i class="wb-user-circle"></i> {{ $userCustomer->getEmail() }}
                                </a>
                                <ul class="dropdown-menu mw-border-color">
                                    <!-- PROFILE -->
                                    <li>
                                        <a class="mw-black" href="{{ \URL::route('home.profile.index.get') }}" >
                                            <i class="fa fa-user"></i> ข้อมูลส่วนตัว
                                        </a>
                                    </li>
                                    <!-- TRACKING -->
                                    <li>
                                        <a class="mw-black" href="{{ \URL::route('home.tracking.index.get') }}" >
                                            <i class="fa fa-search"></i> ติดตามสถานะการส่งพัสดุ
                                        </a>
                                    </li>
                                    <!-- HISTORY -->
                                    <li>
                                        <a class="mw-black" href="{{ \URL::route('home.history.index.get') }}" >
                                            <i class="fa fa-clock-o"></i> ประวัติการใช้บริการ
                                        </a>
                                    </li>
                                    <!-- SIGN OUT -->
                                    <li>
                                        <a class="mw-black" href="{{ \URL::route('home.authen.logout.get') }}" >
                                            <i class="fa fa-sign-out"></i> ออกจากระบบ
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>


