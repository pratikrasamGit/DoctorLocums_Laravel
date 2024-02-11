@extends('layouts.page')
@section('title', 'Nurseify - Facilities')
@section('content')
    <div class="full-page-container">
        @if (!Auth()->user()->hasRole('Facility'))
            @include('facilities.filter')
        @endif
        <!-- Full Page Content -->
        <div class="full-page-content-container" data-simplebar>
		<div class="full-page-content-inner">

			<h3 class="page-title">Search Results</h3>

			<div class="notify-box margin-top-15">
				<div class="switch-container">
					<label class="switch"><input type="checkbox"><span class="switch-button"></span><span class="switch-text">Turn on email alerts for this search</span></label>
				</div>

				<div class="sort-by">
					<span>Sort by:</span>
					<select class="selectpicker hide-tick">
						<option>Relevance</option>
						<option>Newest</option>
						<option>Oldest</option>
						<option>Random</option>
					</select>
				</div>
			</div>

			<!-- Freelancers List Container -->
			<div class="freelancers-container freelancers-grid-layout margin-top-35">
                @foreach ($facilities as $facility)
				<!--Freelancer -->
				<div class="freelancer">

					<!-- Overview -->
					<div class="freelancer-overview">
						<div class="freelancer-overview-inner">
							
							<!-- Bookmark Icon -->
							<span class="bookmark-icon"></span>
							
							<!-- Avatar -->
							<div class="freelancer-avatar">
                            @php
                                $profileFacility = \Illuminate\Support\Facades\Storage::get('assets/facilities/8810d9fb-c8f4-458c-85ef-d3674e2c540a');
                                if ($facility->facility_logo) {
                                    $t = \Illuminate\Support\Facades\Storage::exists('assets/facilities/facility_logo/' . $facility->facility_logo);
                                    if ($t) {
                                        $profileFacility = \Illuminate\Support\Facades\Storage::get('assets/facilities/facility_logo/' . $facility->facility_logo);
                                    }
                                }
                            @endphp
								<div class="verified-badge"></div>
								<a href="/browse-facilities/{{ $facility->id }}"><img src="data:image/jpeg;base64,{{ base64_encode($profileFacility) }}" alt="{{ $facility->name }}"></a>
							</div>

							<!-- Name -->
							<div class="freelancer-name">
								<h4><a href="/browse-facilities/{{ $facility->id }}">{{ $facility->name }} <img class="flag" src="{{asset('images/flags/'.strtolower($facility->state).'.svg')}}" alt="" title="United Kingdom" data-tippy-placement="top"></a></h4>
								<span>{{ \App\Providers\AppServiceProvider::keywordTitle($facility->type) }}</span>
							</div>

							<!-- Rating -->
							<div class="freelancer-rating">
								<div class="star-rating" data-rating="<?= isset($rating[$facility->id]['over_all']) && $rating[$facility->id]['over_all'] != '' ? $rating[$facility->id]['over_all'] : '0.0' ?>"></div>
							</div>
						</div>
					</div>
					
					<!-- Details -->
					<div class="freelancer-details">
						<div class="freelancer-details-list">
							<ul>
								<li>Location <strong><i class="icon-material-outline-location-on"></i> {{ $facility->city }}</strong></li>
								<li>Rate <strong>$60 / hr</strong></li>
								<li>Job Success <strong>95%</strong></li>
							</ul>
						</div>
						<a href="/browse-facilities/{{ $facility->id }}" class="button button-sliding-icon ripple-effect">View Profile <i class="icon-material-outline-arrow-right-alt"></i></a>
					</div>
				</div>
				<!-- Freelancer / End -->

				@endforeach
	
			</div>
			<!-- Freelancers Container / End -->

			<!-- Pagination -->
			<div class="clearfix"></div>
			<div class="pagination-container margin-top-20 margin-bottom-20">
				<!-- <nav class="pagination">
					<ul>
						<li class="pagination-arrow"><a href="#" class="ripple-effect"><i class="icon-material-outline-keyboard-arrow-left"></i></a></li>
						<li><a href="#" class="ripple-effect">1</a></li>
						<li><a href="#" class="ripple-effect current-page">2</a></li>
						<li><a href="#" class="ripple-effect">3</a></li>
						<li><a href="#" class="ripple-effect">4</a></li>
						<li class="pagination-arrow"><a href="#" class="ripple-effect"><i class="icon-material-outline-keyboard-arrow-right"></i></a></li>
					</ul>
				</nav> -->
                {{ $facilities->appends(request()->except('page'))->links() }}
			</div>
			<div class="clearfix"></div>
			<!-- Pagination / End -->
                <!-- Footer -->
                <div class="small-footer margin-top-15">
                    <div class="small-footer-copyrights">
                        © 2020 All Rights Reserved | Nurseify, LLC.&nbsp; by <a value="https://www.imc.consulting"
                            type="url" href="https://www.imc.consulting" target="_blank"
                            data-runtime-url="https://www.imc.consulting">IMC</a>
                    </div>
                </div>
                <!-- Footer / End -->
            </div>
        </div>
    </div>
    <div class="my_modal"></div>
@endsection
