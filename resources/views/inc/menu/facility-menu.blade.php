<!-- Dashboard Sidebar
 ================================================== -->
<div class="dashboard-sidebar">
    <div class="dashboard-sidebar-inner" data-simplebar>
        <div class="dashboard-nav-container">

            <!-- Responsive Navigation Trigger -->
            <a href="#" class="dashboard-responsive-nav-trigger">
                <span class="hamburger hamburger--collapse">
                    <span class="hamburger-box">
                        <span class="hamburger-inner"></span>
                    </span>
                </span>
                <span class="trigger-title">Dashboard Navigation</span>
            </a>

            <!-- Navigation -->
            <div class="dashboard-nav">
                <div class="dashboard-nav-inner">
                    <ul data-submenu-title="Dashboard">
                        <li><a href="/"><i class="icon-material-outline-dashboard"></i> Dashboard</a></li>
                    </ul>
                    <ul data-submenu-title="Profile Setup">
                        <li><a href="/profile-setup"><i class="icon-feather-user"></i> Profile Setup</a></li>
                    </ul>
                    <ul data-submenu-title="Jobs">
                        <li class="{{ request()->is('job/create') ? ' active' : '' }}">
                            <a href="/job/create">
                                <i class="icon-material-outline-star-border"></i> Add Job
                            </a>
                        </li>

                        <li class="@if (url(\Request::getRequestUri()) == url('/jobs')) {{ ' active' }} @endif">
                            <a href="/jobs">
                                <i class="icon-material-outline-star-border"></i> Active Jobs
                            </a>
                        </li>
                        <li class="@if (url(\Request::getRequestUri()) == url('/jobs?inactive=1')) {{ 'active' }} @endif">
                            <a href="/jobs?inactive=1">
                                <i class="icon-material-outline-star-border"></i> Inactive Jobs
                            </a>
                        </li>
                    </ul>
                    <ul data-submenu-title="Nurseify">
                        <li class="">
                            <a href="https://www.nurseify.app/" target="_blank">
                                <i class="icon-line-awesome-home"></i>
                                Nurseify Homepage
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Dashboard Sidebar / End -->
