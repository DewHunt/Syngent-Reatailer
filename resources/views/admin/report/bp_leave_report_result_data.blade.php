@foreach($leaveList as $row)
<tr>
    <td>{{ ++$loop->index }}.</td>
    <td>{{ $row->bp_name }}</td> 
    <td>{{ $row->bp_phone }}</td>
    <td>{{ $row->retailer_name }}</td>
    <td>{{ $row->retailer_phone_number }}</td>
    <td>{{ $row->dealer_name }}</td>
    <td>{{ $row->distributor_code }}</td>
    <td>{{ $row->leave_type }}</td>
    <td>{{ date('d-m-Y', strtotime($row->apply_date)) }}</td>
    <td>{{ date('d-m-Y', strtotime($row->start_date)) }}</td>
    <td>{{ $row->total_day }}</td>
    <td>{{ $row->start_time }}</td>
    <td>{{ $row->reason }}</td>
    <td>
        @if($row->status == 'Pending')
        <span class="badge badge-warning badge-sm">{{ 'Pending' }}</span>
        @elseif($row->status == 'Approved')
        <span class="badge badge-success badge-sm">{{ 'Approved' }}</span>
        @else
        <span class="badge badge-danger badge-sm">{{ 'Cancel' }}</span>
        @endif
    </td>
</tr>
@endforeach
<tr><td colspan="14" align="center">{!! $leaveList->links() !!}</td></tr>
    


                    