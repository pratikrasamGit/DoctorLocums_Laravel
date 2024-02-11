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
                        <li><a href="/admin"><i class="icon-material-outline-dashboard"></i> Dashboard</a></li>
                    </ul>
                    <ul data-submenu-title="Nurses">
                        <li class="{{ request()->is('admin/nurses/create') ? ' active' : '' }}">
                            <a href="{{ route('nurses.create') }}">
                                <i class="icon-material-outline-star-border"></i> Add Nurse
                            </a>
                        </li>
                        <li
                            class="{{ !preg_match('/create/', Request::url()) && preg_match('/admin\/nurses/', Request::url()) ? ' active' : '' }}">
                            <a href="{{ route('nurses.index') }}">
                                <i class="icon-material-outline-star-border"></i> Nurses
                            </a>
                        </li>
                    </ul>
                    <ul data-submenu-title="Facilities">
                        <li class="{{ request()->is('admin/facilities/create') ? ' active' : '' }}">
                            <a href="{{ route('facilities.create') }}">
                                <i class="icon-material-outline-star-border"></i> Add Facility
                            </a>
                        </li>
                        <li
                            class="{{ !preg_match('/create/', Request::url()) && preg_match('/admin\/facilities/', Request::url())? ' active': '' }}">
                            <a href="{{ route('facilities.index') }}">
                                <i class="icon-material-outline-star-border"></i> Facilities
                            </a>
                        </li>
                        <li
                            class="{{ !preg_match('/create/', Request::url()) && preg_match('/admin\/departments/', Request::url())? ' active': '' }}">
                            <a href="{{ route('departments.index') }}">
                                <i class="icon-material-outline-star-border"></i> Departments
                            </a>
                        </li>
                    </ul>
                    <ul data-submenu-title="Jobs">
                        <li class="{{ request()->is('admin/jobs/create') ? ' active' : '' }}">
                            <a href="{{ route('jobs.create') }}">
                                <i class="icon-material-outline-star-border"></i> Add Job
                            </a>
                        </li>
                        <li
                            class="{{ !preg_match('/create/', Request::url()) && preg_match('/admin\/jobs/', Request::url()) ? ' active' : '' }}">
                            <a href="{{ route('jobs.index') }}">
                                <i class="icon-material-outline-star-border"></i> Jobs
                            </a>
                        </li>
                    </ul>
                    <ul data-submenu-title="Keywords">
                        <li class="{{ request()->is('admin/keywords/create') ? ' active' : '' }}">
                            <a href="{{ route('keywords.create') }}">
                                <i class="icon-material-outline-star-border"></i> Add Keyword
                            </a>
                        </li>
                        <li
                            class="{{ !preg_match('/create/', Request::url()) && preg_match('/admin\/keywords/', Request::url()) ? ' active' : '' }}">
                            <a href="{{ route('keywords.index') }}">
                                <i class="icon-material-outline-star-border"></i> Keywords
                            </a>
                        </li>
                    </ul>
                    @role('Administrator')
                        <ul data-submenu-title="Admin Users">
                            <li class="{{ request()->is('admin/adminusers/create') ? ' active' : '' }}">
                                <a href="{{ route('adminusers.create') }}">
                                    <i class="icon-material-outline-star-border"></i> Add User
                                </a>
                            </li>
                            <li
                                class="{{ !preg_match('/create/', Request::url()) && preg_match('/admin\/adminusers/', Request::url())? ' active': '' }}">
                                <a href="{{ route('adminusers.index') }}">
                                    <i class="icon-material-outline-star-border"></i> Admin Users
                                </a>
                            </li>
                        </ul>
                    @endrole
                </div>
            </div>
            <!-- Navigation / End -->

        </div>
    </div>
</div>
<!-- Dashboard Sidebar / End -->
