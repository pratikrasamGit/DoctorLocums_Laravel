@extends(
'nurses/profile/edit',
[
'nurse' => $nurse,
'subtitle' => 'Job Offers',
'activetab' => 'nurseOffers'
]
)
@section('inner-content')
<div class="row">
<!-- Dashboard Box -->
    <div class="col-xl-12">
        <div class="dashboard-box margin-top-0">
            <div class="content with-padding">
                @if(count($offers) > 0)
                <div class="row list-exp">
                    <div class="col-xl-12">
                        <table class="basic-table">
                            <tbody>
                                <tr>
                                <th width="30%">Specialty</th>
                                <th width="25%">Facility</th>
                                <th width="15%">Location</th>                                
                                <th width="10%">Status</th>
                                <th width="10%">View</th>
                                </tr>
                                @foreach($offers as $offer)
                                @if($offer->job)
                                <tr>
                                <td>{{\App\Providers\AppServiceProvider::keywordTitle($offer->job->preferred_specialty)}}</td>   
                                <td>{{$offer->job->facility->name}}</td>
                                <td>{{$offer->job->facility->city}}, {{$offer->job->facility->state}}</td>                                
                                <td>
                                    @if($offer->expiration >= date('Y-m-d H:i:s'))
                                    {{$offer->status}}
                                    @else
                                    Expired
                                    @endif
                                </td> 
                                <td><a href="/browse-jobs/{{$offer->job->id}}"><i class="icon-feather-eye"></i></a></td>
                                </tr>
                                @endif 
                                @endforeach
                            </tbody>
                        </table>
                        {{ $offers->appends(request()->except('page'))->links() }}
                    </div>
                </div>
                @else
                <p>No Offers found.</p>
                @endif                
            </div>
        </div>
    </div>
</div>
@endsection