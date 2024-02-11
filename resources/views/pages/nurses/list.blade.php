@extends('layouts.page')
@section('title', 'Nurseify - Nurses')
@section('content')
    <div class="full-page-container">
        @if (!Auth()->user()->hasRole('Nurse'))
            @include('nurses.filter')
        @endif
        <!-- Full Page Content -->
        <div class="full-page-content-container" data-simplebar>
            <div class="full-page-content-inner">
                @include('inc.messages')
                <h3 class="page-title">Nurses</h3>
                <div class="notify-box margin-top-15">
                    <div class="switch-container">
                    </div>
                    <div class="sort-by">
                        <span>Sort by:</span> 
                        <select class="selectpicker hide-tick"
                            onchange="window.location.replace('browse-nurses?view='+this.value)">
                            <option value="newest" @if (isset($_GET['view']) && $_GET['view'] == 'newest') {{ 'selected' }} @endif>Newest
                            </option>
                            <option value="oldest" @if (isset($_GET['view']) && $_GET['view'] == 'oldest') {{ 'selected' }} @endif>Oldest
                            </option>
                            <option value="low-to-high" @if (isset($_GET['view']) && $_GET['view'] == 'low-to-high') {{ 'selected' }} @endif>Bill
                                Rate (Low to High)</option>
                            <option value="high-to-low" @if (isset($_GET['view']) && $_GET['view'] == 'high-to-low') {{ 'selected' }} @endif>Bill
                                Rate (High to Low)</option>
                        </select>
                    </div>
                </div>
                @if (count($nurses) > 0)
                    <!-- Freelancers List Container -->
                    <div class="freelancers-container freelancers-grid-layout margin-top-35">
                        @foreach ($nurses as $nurse)
                            <!--Freelancer -->
                            <div class="freelancer">

                                <!-- Overview -->
                                <div class="freelancer-overview">
                                    <div class="freelancer-overview-inner">
                                        <!-- Bookmark Icon -->
                                        <span class="bookmark-icon"></span>
                                        
                                        <!-- Avatar -->
                                        @php
                                            $profileNurse = \Illuminate\Support\Facades\Storage::get('assets/nurses/8810d9fb-c8f4-458c-85ef-d3674e2c540a');
                                            if ($nurse->user->image) {
                                                $t = \Illuminate\Support\Facades\Storage::exists('assets/nurses/profile/' . $nurse->user->image);
                                                if ($t) {
                                                    $profileNurse = \Illuminate\Support\Facades\Storage::get('assets/nurses/profile/' . $nurse->user->image);
                                                }
                                            }
                                            $final_bill_rate = $nurse->facility_hourly_pay_rate;
                                        @endphp
                                        <div class="freelancer-avatar">
                                            <a href="/browse-nurses/{{ $nurse->slug }}"><img src="data:image/jpeg;base64,{{ base64_encode($profileNurse) }}"
                                                    alt="{{ $nurse->user->getFullNameAttribute() }}"></a>
                                        </div>

                                        <!-- Name -->
                                        <div class="freelancer-name">
                                            <h4><a href="/browse-nurses/{{ $nurse->slug }}">{{ $nurse->user->getFullNameAttribute() }} <img class="flag" src="{{asset('images/flags/'.strtolower($nurse->state).'.svg')}}" alt="" title="" data-tippy-placement="top"></a></h4>
                                            <!-- <span>Front-End Developer</span> -->
                                        </div>

                                        <!-- Rating -->
                                        <div class="freelancer-rating">
                                            <div class="star-rating" data-rating="<?= isset($rating[$nurse->id]['over_all']) && $rating[$nurse->id]['over_all'] != '' ? $rating[$nurse->id]['over_all'] : '0.0' ?>"></div>
                                        </div>

                                    </div>
                                </div>
                                
                                <!-- Details -->
                                <div class="freelancer-details">
                                    <div class="freelancer-details-list">
                                        <ul>
                                            <li>Location <strong><i class="icon-material-outline-location-on"></i> {{ $nurse->city ? $nurse->city . ',' : '' }} </strong></li>
                                            <li>Rate <strong>${{ $final_bill_rate }} / hr</strong></li>
                                            <!-- <li>Job Success <strong>100%</strong></li> -->
                                        </ul>
                                    </div>
                                    <a href="/browse-nurses/{{ $nurse->slug }}" class="button button-sliding-icon ripple-effect">View Profile <i class="icon-material-outline-arrow-right-alt"></i></a>
                                </div>
                            </div>
                            <!-- Freelancer / End -->
                        @endforeach
                    </div>
                    <!-- Freelancers Container / End -->

                    <!-- Pagination -->
                    <div class="clearfix"></div>
                    <div class="pagination-container margin-top-20 margin-bottom-20">
                        {{ $nurses->appends(request()->except('page'))->links() }}
                    </div>
                    <div class="clearfix"></div>
                    <!-- Pagination / End -->
                @else
                    <p>No Nurse found.</p>
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
    <div class="my_modal"></div>
@endsection
