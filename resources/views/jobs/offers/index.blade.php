@extends('layouts.admin')
@section('title', 'Nurseify - Job Offers')
@section('content')
    <div class="dashboard-headline">
        <h3>Job Offers</h3>
        <!-- Breadcrumbs -->
        <nav id="breadcrumbs" class="dark">
            <ul>
                <li><a href="/">Dashboard</a></li>
                <li>Job Offers</li>
            </ul>
        </nav>
    </div>
    <!-- <div class="container search-panel">
            <div class="row">
                <div class="col-md-3">
                    <input class="with-border" placeholder="Placeholder">
                </div>
                <div class="col-md-3">
                    <select class="selectpicker" multiple>
                        <option>Mustard</option>
                        <option>Ketchup</option>
                        <option>Relish</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="selectpicker" multiple>
                        <option>Mustard</option>
                        <option>Ketchup</option>
                        <option>Relish</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="submit" class="button dark ripple-effect" name="">
                </div>
            </div>
        </div> -->
    <div class="container margin-bottom-30">
        <div class="row">
            @if (count($offers) > 0)
                <table class="table-one">
                    <tr>
                        <th width="25%">Nurse</th>
                        <th width="25%">Facility</th>
                        <th width="20%">Location</th>
                        <th width="15%">Specialty</th>
                        <th width="15%">Status</th>
                    </tr>
                    @foreach ($offers as $offer)
                        <tr>
                            <td>{{ $offer->nurse->user->getFullNameAttribute() }}</td>
                            <td>{{ $offer->job()->first()->facility->name }}</td>
                            <td>{{ $offer->job()->first()->facility->city }},
                                {{ $offer->job()->first()->facility->state }}
                            </td>
                            <td>{{ \App\Providers\AppServiceProvider::keywordTitle($offer->job()->first()->preferred_specialty) }}
                            </td>
                            <td>{{ $offer->status }}</td>
                        </tr>
                    @endforeach
                </table>
                {{ $offers->appends(request()->except('page'))->links() }}
            @else
                <p>No Job offer found.</p>
            @endif
        </div>
    </div>
@endsection
