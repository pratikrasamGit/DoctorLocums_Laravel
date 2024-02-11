@extends('layouts.page')
@section('title', 'Nurseify - ' . $job->facility->name)
@section('content')
    @php
    $profileFacility = \Illuminate\Support\Facades\Storage::get('assets/facilities/8810d9fb-c8f4-458c-85ef-d3674e2c540a');
    if ($job->facility->facility_logo) {
        $t = \Illuminate\Support\Facades\Storage::exists('assets/facilities/facility_logo/' . $job->facility->facility_logo);
        if ($t) {
            $profileFacility = \Illuminate\Support\Facades\Storage::get('assets/facilities/facility_logo/' . $job->facility->facility_logo);
        }
    }
    @endphp
    <!-- Titlebar
                                                                                                                                                                                                                                                                                                            ================================================== -->
                                                                                                                                                                                                                                                                                                            <div class="single-page-header" data-background-image="images/single-job.jpg">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="single-page-header-inner">
					<div class="left-side">
						<div class="header-image"><a href="/browse-facilities/{{ $job->facility->slug }}"><img src="data:image/jpeg;base64,{{ base64_encode($profileFacility) }}" alt="{{ $job->facility->name }}"></a></div>
						<div class="header-details">
							<h3>{{ \App\Providers\AppServiceProvider::keywordTitle($job->preferred_specialty) }}</h3></h3>
							<h5>About the Facility</h5>
							<ul>
								<li><a href="/browse-facilities/{{ $job->facility->id }}"><i class="icon-material-outline-business"></i> {{ $job->facility->name }}</a></li>
								<li><div class="star-rating" data-rating="<?= isset($rating[$job->facility->id]['over_all']) && $rating[$job->facility->id]['over_all'] != '' ? $rating[$job->facility->id]['over_all'] : '0.0' ?>"></div></li>
								<li><img class="flag" src="{{asset('images/flags/'.strtolower($job->facility->state).'.svg')}}" alt=""> {{ $job->facility->city }}</li>
								<li><div class="verified-badge-with-title">Verified</div></li>
							</ul>
						</div>
					</div>
					<div class="right-side">
						<div class="salary-box">
							<div class="salary-type">Hourly Pay Rate</div>
							<div class="salary-amount">${{ $job->preferred_hourly_pay_rate }}</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="container">
	<div class="row">
		
		<!-- Content -->
		<div class="col-xl-8 col-lg-8 content-right-offset">

			<div class="single-page-section">
				<h3 class="margin-bottom-25">Job Description</h3>
				{!! $job->description ? $job->description : 'N/A' !!}
                    @if ($job->video_embed_url)
                        <iframe width="100%" height="315" class="margin-top-50"
                            src="{{ $job->video_embed_url }}"></iframe>
                    @endif
            </div>

			<div class="single-page-section">
				<h3 class="margin-bottom-30">Location</h3>
				<div id="single-job-map-container">
					<div id="singleListingMap" data-latitude="{{ $job->facility->f_lat ?: 29.73583720373403 }}"
                            data-longitude="{{ $job->facility->f_lang ?: -95.37205826309389 }}" data-map-icon="im im-icon-Hamburger"></div>
					<a href="#" id="streetView">Street View</a>
				</div>
			</div>

			<div class="single-page-section">
				<h3 class="margin-bottom-25">Similar Jobs</h3>

				<!-- Listings Container -->
				<div class="listings-container grid-layout">

						<!-- Job Listing -->
						<a href="#" class="job-listing">

							<!-- Job Listing Details -->
							<div class="job-listing-details">
								<!-- Logo -->
								<div class="job-listing-company-logo">
									<img src="images/company-logo-02.png" alt="">
								</div>

								<!-- Details -->
								<div class="job-listing-description">
									<h4 class="job-listing-company">Coffee</h4>
									<h3 class="job-listing-title">Barista and Cashier</h3>
								</div>
							</div>

							<!-- Job Listing Footer -->
							<div class="job-listing-footer">
								<ul>
									<li><i class="icon-material-outline-location-on"></i> San Francisco</li>
									<li><i class="icon-material-outline-business-center"></i> Full Time</li>
									<li><i class="icon-material-outline-account-balance-wallet"></i> $35000-$38000</li>
									<li><i class="icon-material-outline-access-time"></i> 2 days ago</li>
								</ul>
							</div>
						</a>

						<!-- Job Listing -->
						<a href="#" class="job-listing">

							<!-- Job Listing Details -->
							<div class="job-listing-details">
								<!-- Logo -->
								<div class="job-listing-company-logo">
									<img src="images/company-logo-03.png" alt="">
								</div>

								<!-- Details -->
								<div class="job-listing-description">
									<h4 class="job-listing-company">King <span class="verified-badge" title="Verified Employer" data-tippy-placement="top"></span></h4>
									<h3 class="job-listing-title">Restaurant Manager</h3>
								</div>
							</div>

							<!-- Job Listing Footer -->
							<div class="job-listing-footer">
								<ul>
									<li><i class="icon-material-outline-location-on"></i> San Francisco</li>
									<li><i class="icon-material-outline-business-center"></i> Full Time</li>
									<li><i class="icon-material-outline-account-balance-wallet"></i> $35000-$38000</li>
									<li><i class="icon-material-outline-access-time"></i> 2 days ago</li>
								</ul>
							</div>
						</a>
					</div>
					<!-- Listings Container / End -->

				</div>
		</div>
		

		<!-- Sidebar -->
		<div class="col-xl-4 col-lg-4">
			<div class="sidebar-container">

				<a href="#small-dialog" class="apply-now-button popup-with-zoom-anim">Apply Now <i class="icon-material-outline-arrow-right-alt"></i></a>
					
				<!-- Sidebar Widget -->
				<div class="sidebar-widget">
					<div class="job-overview">
						<div class="job-overview-headline">Job Summary</div>
						<div class="job-overview-inner">
							<ul>
								<li>
									<i class="icon-material-outline-location-on"></i>
									<span>Location</span>
									<h5>{{ $job->facility->name }}, {{ $job->facility->city }},
                                            {{ $job->facility->state }}</h5>
								</li>
								<li>
									<i class="icon-material-outline-business-center"></i>
									<span>Job Type</span>
									<h5>{{ $job->preferred_shift_duration ? \App\Providers\AppServiceProvider::keywordTitle($job->preferred_shift_duration) : 'N/A' }} Shift</h5>
								</li>
								<li>
									<i class="icon-material-outline-local-atm"></i>
									<span>Salary</span>
									<h5>${{ $job->preferred_hourly_pay_rate }}</h5>
								</li>
								<li>
									<i class="icon-material-outline-access-time"></i>
									<span>Date Posted</span>
									<h5>{{ $job->created_at ? date('jS F Y', strtotime($job->created_at)) : 'N/A' }}</h5>
								</li>
							</ul>
						</div>
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
    <div class="margin-top-50"></div>
    <!-- Footer -->
    <div id="footer">
        <!-- Footer Copyrights -->
        <div class="footer-bottom-section">
            <div class="container">
                <div class="row">
                    <div class="col-xl-12">
                        © 2020 All Rights Reserved | Nurseify, LLC.&nbsp; by <a value="https://www.imc.consulting"
                            type="url" href="https://www.imc.consulting" target="_blank"
                            data-runtime-url="https://www.imc.consulting">IMC</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer Copyrights / End -->
    </div>
    <!-- Footer / End -->


<!-- Apply for a job popup
================================================== -->
<div id="small-dialog" class="zoom-anim-dialog mfp-hide dialog-with-tabs">

	<!--Tabs -->
	<div class="sign-in-form">

		<ul class="popup-tabs-nav">
			<li><a href="#tab">Apply Now</a></li>
		</ul>

		<div class="popup-tabs-container">

			<!-- Tab -->
			<div class="popup-tab-content" id="tab">
				
				<!-- Welcome Text -->
				<div class="welcome-text">
					<h3>Attach File With CV</h3>
				</div>
					
				<!-- Form -->
				<form method="post" id="apply-now-form">

					<div class="input-with-icon-left">
						<i class="icon-material-outline-account-circle"></i>
						<input type="text" class="input-text with-border" name="name" id="name" placeholder="First and Last Name" required/>
					</div>

					<div class="input-with-icon-left">
						<i class="icon-material-baseline-mail-outline"></i>
						<input type="text" class="input-text with-border" name="emailaddress" id="emailaddress" placeholder="Email Address" required/>
					</div>

					<div class="uploadButton">
						<input class="uploadButton-input" type="file" accept="image/*, application/pdf" id="upload-cv" />
						<label class="uploadButton-button ripple-effect" for="upload-cv">Select File</label>
						<span class="uploadButton-file-name">Upload your CV / resume relevant file. <br> Max. file size: 50 MB.</span>
					</div>

				</form>
				
				<!-- Button -->
				<button class="button margin-top-35 full-width button-sliding-icon ripple-effect" type="submit" form="apply-now-form">Apply Now <i class="icon-material-outline-arrow-right-alt"></i></button>

			</div>

		</div>
	</div>
</div>
<!-- Apply for a job popup / End -->

@endsection
