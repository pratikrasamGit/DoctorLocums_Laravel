        <div class="col-xl-12">   		
			<!-- Tabs Container -->
			<div class="tabs tabs-num">
				<div class="tabs-header">
					<ul>
						<li class="active"><a href="#tab-1" data-tab-id="1">Personal Info <span>My Details</span></a></li>
						<li><a href="#tab-2" data-tab-id="2">Schedule Onboarding <span>Meeting</span></a></li>
						<li><a href="#tab-3" data-tab-id="3">Hourly Rate <span>& Availability</span></a></li>
						<li><a href="#tab-4" data-tab-id="4"> Work History <span>& Certifications</span></a></li>
						<li><a href="#tab-5" data-tab-id="5">Role <span>Interest</span></a></li>
					</ul>
					<div class="tab-hover"></div>
					<nav class="tabs-nav">
						<span class="tab-prev"><i class="icon-material-outline-keyboard-arrow-left"></i></span>
						<span class="tab-next"><i class="icon-material-outline-keyboard-arrow-right"></i></span>
					</nav>
				</div>
				<!-- Tab Content -->
				<div class="tabs-content">
					<div class="tab active" data-tab-id="1">
						
                        @include('nurses.partials.detail-personal')
                        @include('users.password')
					</div>
					<div class="tab" data-tab-id="2">
						<iframe data-v-1957a982="" width="100%" height="500" src="https://www.vcita.com/v/wdkfhg23lenm7bcq//online_scheduling?&amp;o=ZGlyZWN0&amp;s=https%3A%2F%2Flive.vcita.com%2Fsite%2Fwdkfhg23lenm7bcq%2Fonline-scheduling%3Fo%3DZGlyZWN0%26topUrl%3DaHR0cHM6Ly9saXZlLnZjaXRhLmNvbS9zaXRlL3dka2ZoZzIzbGVubTdiY3Evb25saW5lLXNjaGVkdWxpbmc%253D%26isWidget%3Dfalse&amp;topUrl=aHR0cHM6Ly9saXZlLnZjaXRhLmNvbS9zaXRlL3dka2ZoZzIzbGVubTdiY3Evb25saW5lLXNjaGVkdWxpbmc%3D&amp;isWidget=false&amp;&amp;vitrage_iframe=true&amp;portal_iframe=true" frameborder="0" class="flex"></iframe>
					</div>
					<div class="tab" data-tab-id="3">
						@include('nurses.partials.detail-availability')
					</div>
					<div class="tab" data-tab-id="4">
						@include('nurses.partials.detail-certificates')
					</div>
					<div class="tab" data-tab-id="5">
						@include('nurses.partials.detail-assessment')
					</div>
				</div>
			</div>
			<!-- Tabs Container / End -->
		</div>