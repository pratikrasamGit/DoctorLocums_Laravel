@extends('layouts.admin')
@section('title', 'Nurseify - Nurses')
@section('content')
<div class="dashboard-headline">
    <h3>Nurses</h3>
    <!-- Breadcrumbs -->
    <nav id="breadcrumbs" class="dark">
        <ul>
            <li><a href="/">Dashboard</a></li>
            <li>Nurses</li>
        </ul>
    </nav>
</div>
<div class="container search-panel">
{!! Form::open(['action' => 'NurseController@search', 'method' => 'POST']) !!}
    <div class="row">    
        <div class="col-md-6">
            @include('searchform.form')
        </div>
        <div class="col-md-3">
            {{Form::button('<i class="icon-material-outline-search" aria-hidden="true"></i> Search Nurses', ['name' => 'search', 'type' => 'submit','class'=>'button dark ripple-effect'])}}
        </div>
        <div class="col-md-3">
           @if( \Request::get('search_text') )
			<a class="button dark ripple-effect" href="{{route('nurses.index')}}"><i class="icon-line-awesome-times-circle-o" aria-hidden="true"></i>Reset</a>
			@endif
        </div>    
    </div>
{!! Form::close() !!}    
</div>
<div class="container margin-bottom-30">
    <div class="row">
    @if(count($nurses) > 0)
        <table class="table-one">
            <tr>
                <th width="20%">Name</th>
                <th width="20%">Speciality</th>
                <th width="20%">Email</th>
                <th width="10%">Phone</th>
                <th width="10%">Last Login</th>
                <th width="10%">Action</th>
                <th width="10%">Invite</th>
            </tr>
            @foreach($nurses as $nurse)
            <tr>
                <td>{{$nurse->user->getFullNameAttribute()}}</td>
                <td>{{\App\Providers\AppServiceProvider::keywordTitle($nurse->specialty)}}</td>
                <td>{{$nurse->user->email}}</td>
                <td>{{$nurse->user->mobile}}</td>
                <td>{{$nurse->user->last_login_at ? $nurse->user->last_login_at->diffForHumans() : 'N/A'}}</td>
                <td>
                    <a href="{{ route('nurses.edit',[$nurse->id]) }}" class="icon edit-icon" data-tippy-placement="top" title="Edit"><i class="icon-feather-edit"></i></a>
                    @role('Administrator')
                    <form onsubmit="return confirm('Do you really want to delete?');" action="{{ route('nurses.destroy',[$nurse->id]) }}" method="POST">
                        {{ csrf_field() }}
                        <input type="hidden" name="_method" value="DELETE" />
                        <button type="submit" class="icon delete-icon" data-tippy-placement="top" title="Delete"><i class="icon-material-outline-delete"></i></button>
                    </form>
                    @endrole
                </td>
                <td>
                 <a href="{{route('invite-users',[$nurse->user->id])}}" class="icon reset-icon" data-tippy-placement="top" title="Invite"><i class="icon-feather-send"></i></a>                    
                </td>
            </tr>
            @endforeach           
        </table>
        {{ $nurses->appends(request()->except('page'))->links() }}
        @else
        <p>No Nurse found.</p>
        @endif              
    </div>
    @role('Administrator')
    <div class="row">
        <div class="col-md-6">
        <p><a href="/admin/nurses/trashed">Trashed Nurses</a></p>
        </div>
        <div class="col-md-6">
        <p><a href="/admin/export-nurses">Export Nurses</a></p>
        </div>
    </div>  
    @endrole
</div>
@endsection