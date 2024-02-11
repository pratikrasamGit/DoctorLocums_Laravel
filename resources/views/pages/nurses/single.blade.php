@extends('layouts.page')
@section('title', 'Nurseify - ' . $user->getFullNameAttribute())
@section('content')
    @php
    $profileNurse = \Illuminate\Support\Facades\Storage::get('assets/nurses/8810d9fb-c8f4-458c-85ef-d3674e2c540a');
    if ($nurse->user->image) {
        $t = \Illuminate\Support\Facades\Storage::exists('assets/nurses/profile/' . $nurse->user->image);
        if ($t) {
            $profileNurse = \Illuminate\Support\Facades\Storage::get('assets/nurses/profile/' . $nurse->user->image);
        }
    }
    $final_bill_rate = $nurse->facility_hourly_pay_rate;
    if (
        Auth()
            ->user()
            ->hasRole('Nurse')
    ) {
        $final_bill_rate = $nurse->hourly_pay_rate;
    }
    $maskedMobile = str_pad(substr($user->mobile, -4), strlen($user->mobile), 'X', STR_PAD_LEFT);
    @endphp
    <!-- Titlebar
                                ================================================== -->
<div class="single-page-header freelancer-header" data-background-image="images/single-freelancer.jpg">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="single-page-header-inner">
					<div class="left-side">
						<div class="header-image freelancer-avatar"><img src="data:image/jpeg;base64,{{ base64_encode($profileNurse) }}"
                                    alt="{{ $user->getFullNameAttribute() }}"></div>
						<div class="header-details">
							<h3>{{ $user->getFullNameAttribute() }} <span>@if (!Auth()->user()->hasRole('Nurse'))
                                    <p>Nursing License # {{ $nurse->nursing_license_number }}</p>
                                @endif</span></h3>
							<ul>
								<li><div class="star-rating" data-rating="<?= isset($rating['over_all']) && $rating['over_all'] != '' ? $rating['over_all'] : '0.0' ?>"></div></li>
								<li><img class="flag" src="{{asset('images/flags/'.strtolower($nurse->state).'.svg')}}" alt=""> {{ $nurse->getCityStateAttribute() }}</li>
								<li><div class="verified-badge-with-title">Verified</div></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
    <!-- Page Content
                                ================================================== -->
                                <div class="container">
	<div class="row">
		
		<!-- Content -->
		<div class="col-xl-8 col-lg-8 content-right-offset">
			
			<!-- Page Content -->
			<div class="single-page-section">
				<h3 class="margin-bottom-25">About Me</h3>
                {!! $nurse->summary ? $nurse->summary : 'N/A' !!}
            </div>

			<!-- Boxed List -->
			<div class="boxed-list margin-bottom-60">
				<div class="boxed-list-headline">
					<h3><i class="icon-material-outline-thumb-up"></i> Work History and Feedback</h3>
				</div>
                @if (count($nuexperience) > 0)
				<ul class="boxed-list-ul">
                    @foreach ($nuexperience as $nuexp)
					<li>
						<div class="boxed-list-item">
							<!-- Content -->
							<div class="item-content">
								<h4>{{ $nuexp->position_title }}</h4>
								<div class="item-details margin-top-10">
									<div class="star-rating" data-rating="5.0"></div>
									<div class="detail-item"><i class="icon-material-outline-date-range"></i> {{ $nuexp->start_date ? date('m Y', strtotime($nuexp->start_date)) : 'N/A' }}</div>
								</div>
								<div class="item-description">
									<p>{{ $nuexp->description_job_duties }}</p>
								</div>
							</div>
						</div>
					</li>
                    @endforeach
                </ul>
                @else
                    <ul class="boxed-list-ul">
                        <li>
                            <div class="boxed-list-item">
                                <div class="item-content">
                                    <p>No Work History</p>
                                </div>
                            </div>
                        </li>
                    </ul>
                @endif
				</ul>
               

				

			</div>
			<!-- Boxed List / End -->
			
			<!-- Boxed List -->
			<div class="boxed-list margin-bottom-60">
				<div class="boxed-list-headline">
					<h3><i class="icon-material-outline-business"></i> Employment History</h3>
				</div>
				<ul class="boxed-list-ul">
					<li>
						<div class="boxed-list-item">
							<!-- Avatar -->
							<div class="item-image">
								<img src="images/browse-companies-03.png" alt="">
							</div>
							
							<!-- Content -->
							<div class="item-content">
								<h4>Development Team Leader</h4>
								<div class="item-details margin-top-7">
									<div class="detail-item"><a href="#"><i class="icon-material-outline-business"></i> Acodia</a></div>
									<div class="detail-item"><i class="icon-material-outline-date-range"></i> May 2019 - Present</div>
								</div>
								<div class="item-description">
									<p>Focus the team on the tasks at hand or the internal and external customer requirements.</p>
								</div>
							</div>
						</div>
					</li>
					<li>
						<div class="boxed-list-item">
							<!-- Avatar -->
							<div class="item-image">
								<img src="images/browse-companies-04.png" alt="">
							</div>
							
							<!-- Content -->
							<div class="item-content">
								<h4><a href="#">Lead UX/UI Designer</a></h4>
								<div class="item-details margin-top-7">
									<div class="detail-item"><a href="#"><i class="icon-material-outline-business"></i> Acorta</a></div>
									<div class="detail-item"><i class="icon-material-outline-date-range"></i> April 2014 - May 2019</div>
								</div>
								<div class="item-description">
									<p>I designed and implemented 10+ custom web-based CRMs, workflow systems, payment solutions and mobile apps.</p>
								</div>
							</div>
						</div>
					</li>
				</ul>
			</div>
			<!-- Boxed List / End -->

		</div>
		

		<!-- Sidebar -->
		<div class="col-xl-4 col-lg-4">
			<div class="sidebar-container">
				
				<!-- Profile Overview -->
				<div class="profile-overview">
					<div class="overview-item"><strong>${{ $final_bill_rate }}</strong><span>Hourly Rate</span></div>
					<div class="overview-item"><strong>{{ $nurse->experience_as_acute_care_facility ? $nurse->experience_as_acute_care_facility . '+ Years' : 'N/A' }}</strong><span>Jobs Done</span></div>
					<div class="overview-item"><strong>22</strong><span>Rehired</span></div>
				</div>

				<!-- Button -->
				<a href="#small-dialog" class="apply-now-button popup-with-zoom-anim margin-bottom-50">Make an Offer <i class="icon-material-outline-arrow-right-alt"></i></a>

				<!-- Freelancer Indicators -->
				<div class="sidebar-widget">
					<div class="freelancer-indicators">

						<!-- Indicator -->
						<div class="indicator">
							<strong>88%</strong>
							<div class="indicator-bar" data-indicator-percentage="88"><span></span></div>
							<span>Job Success</span>
						</div>

						<!-- Indicator -->
						<div class="indicator">
							<strong>100%</strong>
							<div class="indicator-bar" data-indicator-percentage="100"><span></span></div>
							<span>Recommendation</span>
						</div>
						
						<!-- Indicator -->
						<div class="indicator">
							<strong>90%</strong>
							<div class="indicator-bar" data-indicator-percentage="90"><span></span></div>
							<span>On Time</span>
						</div>	
											
						<!-- Indicator -->
						<div class="indicator">
							<strong>80%</strong>
							<div class="indicator-bar" data-indicator-percentage="80"><span></span></div>
							<span>On Budget</span>
						</div>
					</div>
				</div>
				
				<!-- Widget -->
				<div class="sidebar-widget">
					<h3>Social Profiles</h3>
					<div class="freelancer-socials margin-top-25">
						<ul>
							<li><a href="#" title="Dribbble" data-tippy-placement="top"><i class="icon-brand-dribbble"></i></a></li>
							<li><a href="#" title="Twitter" data-tippy-placement="top"><i class="icon-brand-twitter"></i></a></li>
							<li><a href="#" title="Behance" data-tippy-placement="top"><i class="icon-brand-behance"></i></a></li>
							<li><a href="#" title="GitHub" data-tippy-placement="top"><i class="icon-brand-github"></i></a></li>
						
						</ul>
					</div>
				</div>

				<!-- Widget -->
				<div class="sidebar-widget">
					<h3>Skills</h3>
					<div class="task-tags">
						<span>iOS</span>
						<span>Android</span>
						<span>mobile apps</span>
						<span>design</span>
						<span>Python</span>
						<span>Flask</span>
						<span>PHP</span>
						<span>WordPress</span>
					</div>
				</div>

				<!-- Widget -->
				<div class="sidebar-widget">
					<h3>Attachments</h3>
					<div class="attachments-container">
						<a href="#" class="attachment-box ripple-effect"><span>Cover Letter</span><i>PDF</i></a>
						<a href="#" class="attachment-box ripple-effect"><span>Contract</span><i>DOCX</i></a>
					</div>
				</div>

				<!-- Sidebar Widget -->
				<div class="sidebar-widget">
					<h3>Bookmark or Share</h3>

					<!-- Bookmark Button -->
					<button class="bookmark-button margin-bottom-25">
						<span class="bookmark-icon"></span>
						<span class="bookmark-text">Bookmark</span>
						<span class="bookmarked-text">Bookmarked</span>
					</button>

					<!-- Copy URL -->
					<div class="copy-url">
						<input id="copy-url" type="text" value="" class="with-border">
						<button class="copy-url-button ripple-effect" data-clipboard-target="#copy-url" title="Copy to Clipboard" data-tippy-placement="top"><i class="icon-material-outline-file-copy"></i></button>
					</div>

					<!-- Share Buttons -->
					<div class="share-buttons margin-top-25">
						<div class="share-buttons-trigger"><i class="icon-feather-share-2"></i></div>
						<div class="share-buttons-content">
							<span>Interesting? <strong>Share It!</strong></span>
							<ul class="share-buttons-icons">
								<li><a href="#" data-button-color="#3b5998" title="Share on Facebook" data-tippy-placement="top"><i class="icon-brand-facebook-f"></i></a></li>
								<li><a href="#" data-button-color="#1da1f2" title="Share on Twitter" data-tippy-placement="top"><i class="icon-brand-twitter"></i></a></li>
								<li><a href="#" data-button-color="#dd4b39" title="Share on Google Plus" data-tippy-placement="top"><i class="icon-brand-google-plus-g"></i></a></li>
								<li><a href="#" data-button-color="#0077b5" title="Share on LinkedIn" data-tippy-placement="top"><i class="icon-brand-linkedin-in"></i></a></li>
							</ul>
						</div>
					</div>
				</div>

			</div>
		</div>

	</div>
</div>



    <!-- Spacer -->
    <div class="margin-top-15"></div>
    <div class="my_modal"></div>
    <!-- Spacer / End-->

    <!-- Footer
                                ================================================== -->
    <div id="footer">
        <!-- Footer Copyrights -->
        <div class="footer-bottom-section">
            <div class="container">
                <div class="row">
                    <div class="col-xl-12">
                        © 2020 All Rights Reserved | Nurseify, LLC.&nbsp; by <a value="https://www.imc.consulting"
                            type="url" href="https://www.imc.consulting" target="_blank"
                            data-runtime-url="https://www.imc.consulting">IMC</a></div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer Copyrights / End -->
@endsection
@hasanyrole('Administrator|Facility|FacilityAdmin')
    @section('popup')
        <!-- Make an Offer Popup
                                                                ================================================== -->
        <v-make-offer current-user-name="{{ Auth()->user()->getFullNameAttribute() }}"
            user-name="{{ $user->getFullNameAttribute() }}" nurse="{{ $nurse->id }}"></v-make-offer>
        <!-- Make an Offer Popup / End -->
    @endsection
@endhasanyrole
