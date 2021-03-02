<nav class="site-navbar navbar navbar-default navbar-fixed-top navbar-mega" role="navigation">
    <div class="mw-header-navbar navbar-header">
        <!-- Menu Sideber -->
        <button type="button" class="mw-header-hamburger navbar-toggle hamburger hamburger-close navbar-toggle-left hided" data-toggle="menubar">
            <span class="sr-only">Toggle navigation</span>
            <span class="mw-header-hamburger-bar hamburger-bar"></span>
        </button>
        <!-- Brand -->
        <div class="navbar-brand navbar-brand-center site-gridmenu-toggle mw-pd-0" data-toggle="gridmenu">
            <img id="mw-header-brand" width="100%" src="{{ urlAdminImage().'/logo-header.jpg' }}" >
        </div>
        <!-- Menu Topbar -->
        <button type="button" class="mw-header-menu-right navbar-toggle collapsed" data-target="#site-navbar-collapse" data-toggle="collapse">
            <i class="icon wb-more-horizontal" aria-hidden="true"></i>
        </button>
    </div>
    <div class="mw-navbar-container navbar-container container-fluid">
        <!-- Navbar Collapse -->
        <div class="collapse navbar-collapse navbar-collapse-toolbar" id="site-navbar-collapse">
            <!-- Navbar Toolbar -->
            <ul class="nav navbar-toolbar">
                <li class="hidden-float" id="toggleMenubar">
                    <a data-toggle="menubar" href="#" role="button">
                        <i class="icon hamburger hamburger-arrow-left hided">
                          <span class="sr-only">Toggle menubar</span>
                          <span class="hamburger-bar"></span>
                        </i>
                    </a>
                </li>
                <li class="hidden-xs" id="toggleFullscreen">
                    <a class="icon icon-fullscreen" data-toggle="fullscreen" href="#" role="button">
                        <span class="sr-only">Toggle fullscreen</span>
                    </a>
                </li>
            </ul>
            <!-- End Navbar Toolbar -->
            <!-- Navbar Toolbar Right -->
            <ul class="nav navbar-toolbar navbar-right navbar-toolbar-right">
                <!-- View Web -->
                <li>
                    <a href="{{ \URL::route('home.main.index.get') }}" target="_blank">
                        <i class="icon fa-external-link"></i>
                    </a>
                </li>
                <!-- Name -->
                <li>
                    <a href="#">{{ $userAdmin->getFullname() }}</a>
                </li>
                <!-- User -->
                <li class="dropdown">
                    <a class="navbar-avatar dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false" data-animation="scale-up" role="button">
                        <!-- Avatar -->
                        <span class="avatar avatar-online">
                            <img style="max-height: 30px; height: 30px" src="{{ urlAdminImage().'/avatar.jpg' }}" >
                            <i></i>
                        </span>
                    </a>
                    <ul class="dropdown-menu" role="menu">
                        <!-- Profile -->
                        <li role="presentation">
                            <a class="mw-black" href="{{ \URL::route('admin.profile.index.get') }}" >
                                <span class="icon wb-user"></span>
                                {{ " ".config('labels.menu.my_profile.'.helperLang()) }}
                            </a>
                        </li>
                        <!-- Logout -->
                        <li role="presentation">
                            <a class="mw-black mw-black-hover" href="{{ \URL::route('admin.authen.logout.get') }}" role="menuitem">
                                <i class="icon wb-power" aria-hidden="true"></i>
                                {{ " ".config('labels.menu.logout.'.helperLang()) }}
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
        <!-- End Navbar Collapse -->
        <!-- Site Navbar Seach -->
        <div class="collapse navbar-search-overlap" id="site-navbar-search">
            <form role="search">
                <div class="form-group">
                    <div class="input-search">
                        <i class="input-search-icon wb-search" aria-hidden="true"></i>
                        <input type="text" class="form-control" name="site-search" placeholder="Search...">
                        <button type="button" class="input-search-close icon wb-close" data-target="#site-navbar-search" data-toggle="collapse" aria-label="Close"></button>
                    </div>
                </div>
            </form>
        </div>
    <!-- End Site Navbar Seach -->
    </div>

    <!-- Use by custom.js for change language. -->
    <div id="mw-url-ajax" data-value="{{ \URL::route('admin.ajax_center.post') }}"></div>
</nav>