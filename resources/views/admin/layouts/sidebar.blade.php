<div class="mw-site-menubar site-menubar">
    <div class="menu-module"></div>
    <div class="site-menubar-body">
        <ul class="site-menu">
            <!-- SHIPPING -->
            @if ($userAdmin->canSeeTheseMenu(['booking', 'job', 'connote']))
            <li class="site-menu-category" style="margin-top: 0px;">SHIPPING</li>
            @endif
            @if ($userAdmin->canSeeTheseMenu(['booking']))
            <li class="mw-menubar-item site-menu-item has-sub {{ (helperRouteModule() == 'booking') ? 'active' : '' }}">
                <a class="mw-menubar-a animsition-link" href="{{ \URL::route('admin.booking.index.get') }}">
                    <i class="site-menu-icon fa-truck" aria-hidden="true"></i>
                    <span class="site-menu-title">
                        งานรับของ
                    </span>
                </a>
            </li>
            @endif
            @if ($userAdmin->canSeeTheseMenu(['job']))
            <li class="mw-menubar-item site-menu-item has-sub {{ (helperRouteModule() == 'job') ? 'active' : '' }}">
                <a class="mw-menubar-a animsition-link" href="{{ \URL::route('admin.job.index.get') }}">
                    <i class="site-menu-icon fa-fighter-jet" aria-hidden="true"></i>
                    <span class="site-menu-title">
                        งานส่งของ
                    </span>
                </a>
            </li>
            @endif
            @if ($userAdmin->canSeeTheseMenu(['connote']))
            <li class="mw-menubar-item site-menu-item has-sub {{ (helperRouteModule() == 'connote') ? 'active' : '' }}">
                <a class="mw-menubar-a animsition-link" href="{{ \URL::route('admin.connote.index.get') }}">
                    <i class="site-menu-icon fa-newspaper-o" aria-hidden="true"></i>
                    <span class="site-menu-title">
                        CONNOTE
                        <!-- {{ config('labels.menu.connote.'.helperLang()) }} -->
                    </span>
                </a>
            </li>
            @endif
            <!-- EMPLOYEE -->
            @if ($userAdmin->canSeeTheseMenu(['employee', 'position']))
            <li class="site-menu-category" style="margin-top: 0px;">EMPLOYEE</li>
            @endif
            @if ($userAdmin->canSeeTheseMenu(['employee']))
            <li class="mw-menubar-item site-menu-item has-sub {{ (helperRouteModule() == 'employee') ? 'active' : '' }}">
                <a class="mw-menubar-a animsition-link" href="{{ \URL::route('admin.employee.index.get') }}">
                    <i class="site-menu-icon fa-user" aria-hidden="true"></i>
                    <span class="site-menu-title">
                        {{ config('labels.menu.employee.'.helperLang()) }}
                    </span>
                </a>
            </li>
            @endif
            @if ($userAdmin->canSeeTheseMenu(['position']))
            <li class="mw-menubar-item site-menu-item has-sub {{ (helperRouteModule() == 'position') ? 'active' : '' }}">
                <a class="mw-menubar-a animsition-link" href="{{ \URL::route('admin.position.index.get') }}">
                    <i class="site-menu-icon fa-lock" aria-hidden="true"></i>
                    <span class="site-menu-title">
                        {{ config('labels.menu.position.'.helperLang()) }}
                    </span>
                </a>
            </li>
            @endif
            <!-- CUSTOMER -->
            @if ($userAdmin->canSeeTheseMenu(['customer']))
            <li class="site-menu-category" style="margin-top: 0px;">CUSTOMER</li>
            @endif
            @if ($userAdmin->canSeeTheseMenu(['customer']))
            <li class="mw-menubar-item site-menu-item has-sub {{ (helperRouteModule() == 'customer') ? 'active' : '' }}">
                <a class="mw-menubar-a animsition-link" href="{{ \URL::route('admin.customer.index.get') }}">
                    <i class="site-menu-icon fa-heart" aria-hidden="true"></i>
                    <span class="site-menu-title">
                        {{ config('labels.menu.customer.'.helperLang()) }}
                    </span>
                </a>
            </li>
            @endif
            <!-- REPORT -->
            @if ($userAdmin->canSeeTheseMenu(['report', 'report_booking', 'report_messenger']))
            <li class="site-menu-category" style="margin-top: 0px;">REPORT</li>
            @endif
            @if ($userAdmin->canSeeTheseMenu(['report']))
            <!-- <li class="mw-menubar-item site-menu-item has-sub {{ (helperRouteModule() == 'report') ? 'active' : '' }}">
                <a class="mw-menubar-a animsition-link" href="{{ \URL::route('admin.report.index.get') }}">
                    <i class="site-menu-icon fa-line-chart" aria-hidden="true"></i>
                    <span class="site-menu-title">
                        Connote Report
                    </span>
                </a>
            </li> -->

            <li class="mw-menubar-item site-menu-item has-sub {{ (helperRouteModule() == 'report') ? 'active' : '' }}">
                <a class="mw-menubar-a animsition-link" href="{{ \URL::route('admin.report.index_new.get') }}">
                    <i class="site-menu-icon fa-line-chart" aria-hidden="true"></i>
                    <span class="site-menu-title">
                        Connote Report
                    </span>
                </a>
            </li>
            @endif
            @if ($userAdmin->canSeeTheseMenu(['report_booking']))
            <li class="mw-menubar-item site-menu-item has-sub {{ (helperRouteModule() == 'report_booking') ? 'active' : '' }}">
                <a class="mw-menubar-a animsition-link" href="{{ \URL::route('admin.report_booking.index.get') }}">
                    <i class="site-menu-icon fa-area-chart" aria-hidden="true"></i>
                    <span class="site-menu-title">
                        Booking Report
                    </span>
                </a>
            </li>
            @endif
            @if ($userAdmin->canSeeTheseMenu(['report_messenger']))
            <li class="mw-menubar-item site-menu-item has-sub {{ (helperRouteModule() == 'report_messenger') ? 'active' : '' }}">
                <a class="mw-menubar-a animsition-link" href="{{ \URL::route('admin.report_messenger.index.get') }}">
                    <i class="site-menu-icon fa-user" aria-hidden="true"></i>
                    <span class="site-menu-title">
                        Messenger Report
                    </span>
                </a>
            </li>
            @endif
        </ul>
    </div>
</div>

