@extends('layouts.admin')
@section('title', 'Nurseify - Jobs')
@section('content')
    <div class="dashboard-headline">
        <h3>Email Template Content Management</h3>
        <!-- Breadcrumbs -->
        <nav id="breadcrumbs" class="dark">
            <ul>
                <li><a href="/">Dashboard</a></li>
                <li>Email Templates</li>
            </ul>
        </nav>
    </div>

    <div class="container margin-bottom-30">
        <div class="row">
            @if (count($email_template) > 0)
                <table class="table-one">
                    <tr>
                        <th width="20%">S.No</th>
                        <th width="20%">Subject</th>
                        <th width="10%">Status</th>
                        <th width="15%">Action</th>
                    </tr>
                    <?php $i = 1; ?>
                    @foreach ($email_template as $et)
                        <tr>
                            <td>{{ $i++ }}</td>
                            <td>{{ $et->label ? $et->label : '' }}</td>
                            <td>{{ $et->active ? 'Inactive' : 'Active' }}</td>
                            <td>
                                <a href="{{ route('email-template.edit', [$et->id]) }}" class="icon edit-icon"
                                    data-tippy-placement="top" title="Edit"><i class="icon-feather-edit"></i></a>
                                <a href="email-template/view/{{ $et->id }}" target="_blank" class="icon view-icon"
                                    data-tippy-placement="top" title="View"><i class="icon-feather-eye"></i></a>
                                {{-- @role('Administrator|Admin')
                                    <form onsubmit="return confirm('Do you really want to delete?');"
                                        action="{{ route('jobs.destroy', [$et->id]) }}" method="POST">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="_method" value="DELETE" />
                                        <button type="submit" class="icon delete-icon" data-tippy-placement="top"
                                            title="Delete"><i class="icon-material-outline-delete"></i></button>
                                    </form>
                                @endrole --}}
                            </td>
                        </tr>
                    @endforeach
                </table>
                {{-- {{ $emailtemplate->appends(request()->except('page'))->links() }} --}}
            @else
                <p>No Job found.</p>
            @endif
        </div>
    </div>
@endsection
