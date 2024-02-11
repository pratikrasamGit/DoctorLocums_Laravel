@extends('layouts.page')
@section('title', 'Nurseify - Jobs')
@section('content')
    <div class="full-page-container">
        @include('jobs.filter')
        <!-- Full Page Content -->
        <div class="full-page-content-container" data-simplebar>
            <div class="full-page-content-inner">
                @include('inc.messages')
                <h3 class="page-title">Jobs</h3>
                <div class="notify-box margin-top-15">
                    <div class="switch-container">
                        <label class="switch"><input type="checkbox"><span class="switch-button"></span><span
                                class="switch-text">Turn on email alerts for this search</span></label>
                    </div>
                    <div class="sort-by">
                        <span>Sort by:</span>
                        <select class="selectpicker hide-tick">
                            <option>Newest</option>
                            <option>Oldest</option>
                            <option>Bill Rate (Low to High)</option>
                            <option>Bill Rate (High to Low)</option>
                        </select>
                    </div>
                </div>
                @if (count($jobs) > 0)
                    <!-- Freelancers List Container -->
                    <div class="listings-container grid-layout margin-top-35">
                        @foreach ($jobs as $job)
                            @php
                                $profileFacility = \Illuminate\Support\Facades\Storage::get('assets/facilities/8810d9fb-c8f4-458c-85ef-d3674e2c540a');
                                if ($job->facility->facility_logo) {
                                    $t = \Illuminate\Support\Facades\Storage::exists('assets/facilities/facility_logo/' . $job->facility->facility_logo);
                                    if ($t) {
                                        $profileFacility = \Illuminate\Support\Facades\Storage::get('assets/facilities/facility_logo/' . $job->facility->facility_logo);
                                    }
                                }
                            @endphp
                            <a href="/browse-jobs/{{ $job->id }}" class="job-listing">
                                <!-- Job Listing Details -->
                                <div class="job-listing-details">
                                    <!-- Logo -->
                                    <div class="job-listing-company-logo">
                                        <img src="data:image/jpeg;base64,{{ base64_encode($profileFacility) }}"
                                            alt="{{ $job->facility->name }}">
                                    </div>

                                    <!-- Details -->
                                    <div class="job-listing-description">
                                        <h4 class="job-listing-company">{{ $job->facility->name }} <span
                                                class="verified-badge" title="Verified Employer"
                                                data-tippy-placement="top"></span></h4>
                                        <h3 class="job-listing-title">
                                            {{ \App\Providers\AppServiceProvider::keywordTitle($job->preferred_specialty) }}
                                        </h3>
                                    </div>
                                </div>

                                <!-- Job Listing Footer -->
                                <div class="job-listing-footer">
                                    <span class="bookmark-icon"></span>
                                    <ul>
                                        <li><i class="icon-material-outline-location-on"></i> {{ $job->facility->city }},
                                            {{ $job->facility->state }}</li>
                                        <li><i class="icon-material-outline-business-center"></i> Full Time</li>
                                        <li><i class="icon-material-outline-account-balance-wallet"></i>
                                            ${{ $job->preferred_hourly_pay_rate }}</li>
                                        <li><i class="icon-material-outline-access-time"></i>
                                            {{ $job->created_at ? date('jS F Y', strtotime($job->created_at)) : 'N/A' }}
                                        </li>
                                    </ul>
                                </div>
                            </a>
                        @endforeach
                    </div>
                    <!-- Freelancers Container / End -->

                    <!-- Pagination -->
                    <div class="clearfix"></div>
                    <div class="pagination-container margin-top-20 margin-bottom-20">
                        {{ $jobs->appends(request()->except('page'))->links() }}
                    </div>
                    <!-- Pagination / End -->
                @else
                    <p>No Job found.</p>
                @endif
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
    <!-- Full Page Content / End -->
@endsection
