@if(isset($imeiDisputeList))
    @foreach($imeiDisputeList as $row)
    <tr>
        <td>{{ ++$loop->index }}.</td>
        @if(isset($row->customer_photo) && !empty($row->customer_photo))
        <td>
            <a href="javascript:void(0)" onclick="viewLargePhoto('{{ $row->customer_photo }}')" data-toggle="modal" data-target="#viewPhotoModal">
                <img src="{{ asset('public/upload/client/'.$row->customer_photo) }}" alt="photo" width="50" height="50"/>
            </a>
        </td>
        @else
            <td>
                <img src="{{ asset('public/upload/client/no-image.png') }}" alt="photo" width="50" height="50"/>
            </td>
        @endif
        <td>{{ $row->customer_name }}</td>
        <td>{{ $row->customer_phone }}</td>
        <td>{{ $row->dealer_name }}</td>
        <td>{{ $row->dealer_phone_number }}</td>
        <td>{{ $row->distributor_code }}</td>
        <td>{{ $row->retailer_name }}</td>
        <td>{{ $row->retailer_phone }}</td>
        <td>{{ $row->bp_name }}</td> 
        <td>{{ $row->bp_phone }}</td>
        <td>{{ $row->imei_number }}</td>
        <td>{{ $row->description }}</td>
        <td>{{ $row->comments }}</td>
        <td>{{ $row->date }}</td>
        <td>
            @if($row->status == 0) 
            <span class="badge  badge-info badge-sm">{{ 'Pending' }}</span>
            @elseif($row->status == 1)
            <span class="badge badge-info badge-sm">{{ 'Reported' }}</span>
            @elseif($row->status == 2)
            <span class="badge badge-info badge-sm">{{ 'Decline' }}</span>
            @endif
        </td>
    </tr>
    @endforeach
	<tr><td colspan="16" align="center">{!! $imeiDisputeList->links() !!}</td></tr>
@endif
    


                    