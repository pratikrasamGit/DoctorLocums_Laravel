@if(isset($department->users) && count($department->users) > 0)
<table class="table-one">
    <tr>
        <th width="30%">Name</th>
        <th width="30%">Email</th>
        <th width="20%">Mobile</th>
        <th width="20%">Action</th>
    </tr>
    @foreach($department->users as $user)
    <tr>
        <td>{{$user->getFullNameAttribute()}}</td>
        <td>{{$user->email}}</td>
        <td>{{$user->mobile}}</td>
        <td>
            <a href="{{ route('departmentusers.edit',[$user->id]) }}" class="icon edit-icon" data-tippy-placement="top" title="Edit"><i class="icon-feather-edit"></i></a>
            <a onclick="return confirm('Do you really want to delete?');" href="/admin/users/{{$user->id}}/departments/{{$department->id}}/detach" class="icon delete-icon" data-tippy-placement="top" title="Delete">
            <i class="icon-material-outline-delete"></i></a>
        </td>
    </tr>
    @endforeach           
</table>
@endif