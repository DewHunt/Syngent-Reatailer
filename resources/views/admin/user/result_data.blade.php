@if(isset($GetUser))
    @foreach($GetUser as $row)
    <tr>
        <td>{{ ++$loop->index }}.</td>
        <td>{{ $row->name }}</td>
        @php 
            $empId = getSyngentaEmpId($row->employee_id); 
        @endphp
        <td>
            @if($empId)
            {{ $empId }}
            @endif
        </td>
        <td>{{ $row->designation }}</td>
        <td>{{ $row->department }}</td>
        <td>{{ $row->email }}</td>
        <td>
            <input data-id="{{ $row->id }}" class="user-toggle-class" type="checkbox" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="Active" data-off="InActive" @if($row->status == 1 )checked @else {{ ' ' }}@endif>
        </td>
        <td>
            <a href="{{ url('user.menu_permission_list'.'/'.$row->id) }}" class="btn btn-info btn-sm">Menu Permission</a>

            <button type="button" data-id="{{ $row->id }}" id="editUserInfo" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editUserModal">Edit</button>
        </td>
    </tr>
    @endforeach
	{{-- <tr><td colspan="6" align="center">{!! $userLists->links() !!}</td></tr> --}}
@endif
    


                    