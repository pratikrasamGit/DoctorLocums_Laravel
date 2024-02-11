@if(isset($facility->departments) && count($facility->departments) > 0)
<table class="table-one">
    <tr>
        <th width="55%">Department Name</th>
        <th width="15%">Phone</th>
        <th width="15%">Department No.</th>
        <th width="15%">Action</th>
    </tr>
    @foreach($facility->departments as $department)
    <tr>
        <td>{{$department->department_name}}</td>
        <td>{{$department->department_phone}}</td>
        <td>{{$department->department_numbers}}</td>
        <td>
            <a href="{{ route('departments.edit',[$department->id]) }}" class="icon edit-icon" data-tippy-placement="top" title="Edit"><i class="icon-feather-edit"></i></a>
        </td>
    </tr>
    @endforeach           
</table>
@endif