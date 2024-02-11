<!-- Header Container
================================================== -->
<header id="header-container" class="fullwidth dashboard-header not-sticky">
	<!-- Header -->
	<div id="header">
		<div class="container">
			<!-- Left Side Content -->
			<div class="left-side">
				<!-- Logo -->
				<div id="logo">
                    <a href="/admin"><img style="width:150px; height:auto;max-height:inherit;" src="data:image/jpeg;base64,{{ base64_encode($logo) }}" alt=""></a>
				</div>
				<!-- Main Navigation -->
                <!-- include inc.menu-->
                @include('inc.menu')
                <!-- Main Navigation / End -->
			</div>
			<!-- Left Side Content / End -->


			<!-- Right Side Content / End -->
			<div class="right-side">

				<!-- User Menu -->
				<div class="header-widget">

					<!-- Messages -->
					<div class="header-notifications user-menu">
						<div class="header-notifications-trigger">
							<a href="#">
                            <div class="user-avatar status-online">
                            <img src="data:image/jpeg;base64,{{ base64_encode($profilePlaceholder) }}" alt="">
                            </div>
                            </a>
						</div>
						<!-- Dropdown -->
						<div class="header-notifications-dropdown">

							<!-- User Status -->
							<div class="user-status">

								<!-- User Name / Avatar -->
								<div class="user-details">
									<div class="user-avatar status-online">
                                    <img src="data:image/jpeg;base64,{{ base64_encode($profilePlaceholder) }}" alt="">
                                    </div>
									<div class="user-name">
                                        {{Auth()->user()->getFullNameAttribute()}}
                                        <span>{{Auth()->user()->getRoleNames()->first()}}</span>
                                    </div>
								</div>
						</div>
						<ul class="user-menu-small-nav">
                        <li>
                            <a href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">
                                    <i class="icon-material-outline-power-settings-new"></i> {{ __('Logout') }}
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </li>
						</ul>
						</div>
					</div>

				</div>
				<!-- User Menu / End -->
			</div>
			<!-- Right Side Content / End -->
		</div>
	</div>
	<!-- Header / End -->
</header>
<div class="clearfix"></div>
<!-- Header Container / End -->
