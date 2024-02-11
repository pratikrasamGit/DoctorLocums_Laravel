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
                <span class="trigger-title">Profile Navigation</span>
            </a>
            <!-- Navigation -->
            <div class="dashboard-nav">
                <div class="dashboard-nav-inner">

                    <ul data-submenu-title="Profile Setup">
                        <li class="@if ($activetab == 'personalInfo') active @endif">
                            <a href="{{ route('personal-detail', [$nurse->id]) }}">
                                <i class="icon-material-outline-account-circle"></i>
                                Personal Info
                            </a>
                        </li>
                        <li class="@if ($activetab == 'availability') active @endif">
                            <a href="{{ route('availability', [$nurse->id]) }}">
                                <i class="icon-line-awesome-money"></i>
                                Hourly Rate & Availability
                            </a>
                        </li>
                        <li class="@if ($activetab == 'certifications') active @endif">
                            <a href="{{ route('work-history', [$nurse->id]) }}">
                                <i class="icon-line-awesome-files-o"></i>
                                Work History & Certifications
                            </a>
                        </li>
                        <li class="@if ($activetab == 'roleInterest') active @endif">
                            <a href="{{ route('role-interest', [$nurse->id]) }}">
                                <i class="icon-material-outline-assignment"></i>
                                Role Interest
                            </a>
                        </li>
                    </ul>
                    <ul data-submenu-title="Onboarding">
                        <li class="@if ($activetab == 'scheduleOnboarding') active @endif">
                            <a href="{{ route('schedule-onboarding', [$nurse->id]) }}">
                                <i class="icon-line-awesome-calendar-o"></i>
                                Schedule Onboarding
                            </a>
                        </li>
                        {{-- <li class="@if ($activetab == 'dlUploads') active @endif">
								<a href="{{route('schedule-onboarding', [$nurse->id])}}">
									<i class="icon-line-awesome-calendar-o"></i>
									Uploads
								</a>
							</li> --}}
                        @isdev
                        <li class="@if ($activetab == 'gigWage') active @endif">
                            <a href="{{ route('create-gigwage-account', [$nurse->id]) }}">
                                <i class="icon-line-awesome-envelope"></i>
                                Direct Deposit Account
                            </a>
                        </li>
                        @endisdev
                    </ul>
                    <ul data-submenu-title="Job Offers">
                        <li class="@if ($activetab == 'nurseOffers') active @endif">
                            <a href="{{ route('nurse-offer', [$nurse->id]) }}">
                                <i class="icon-line-awesome-envelope"></i>
                                Offers
                            </a>
                        </li>
                    </ul>
                </div>


                <ul data-submenu-title="Nurseify">
                    <li class="">
                        <a href="https://www.nurseify.app/" target="_blank">
                            <i class="icon-line-awesome-home"></i>
                            Nurseify Homepage
                        </a>
                    </li>
                </ul>
            </div>
            <!-- Navigation / End -->
        </div>
    </div>
</div>
<!-- Dashboard Sidebar / End -->
