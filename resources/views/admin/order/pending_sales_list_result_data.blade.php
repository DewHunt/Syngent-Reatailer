@foreach($saleList as $row)
<tr>
<td>{{ ++$loop->index }}.</td>
@if(isset($row->photo) && !empty($row->photo))
<td>
    <a href="javascript:void(0)" onclick="viewLargePhoto('{{ $row->photo }}')" data-toggle="modal" data-target="#viewPhotoModal">
        <img src="{{ asset('public/upload/client/'.$row->photo) }}" alt="photo" width="50" height="50"/>
    </a>
</td>
@else
    <td>
        <img src="{{ asset('public/upload/no-image.png') }}" alt="photo" width="50" height="50"/>
    </td>
@endif
<td>{{ $row->customer_name }}</td>
<td>{{ $row->customer_phone }}</td>
<td>{{ $row->sale_date }}</td>
<td>{{ $row->ime_number }}</td>
<td>{{ $row->alternate_imei }}</td>
<td>{{ $row->product_model }}</td>
<td>{{ $row->dealer_name }}</td>
<td>{{ $row->dealer_code }}</td>
<td>{{ $row->retailer_name }}</td>
<td>{{ $row->retailer_phone_number }}</td>
<td>{{ $row->bp_name }}</td>
<td>{{ $row->bp_phone }}</td>
<td>
    @if($row->order_type == 1)
    <span class="badge badge-info badge-sm statusLabel">{{ 'Online' }}</span>
    @elseif($row->order_type == 2)
    <span class="badge badge-success badge-sm statusLabel">{{ 'Offline' }}</span>
    @endif
</td>
{{-- <td>
    @if($row->status == 1)
    <span class="badge badge-warning badge-sm statusLabel">{{ 'Pending' }}</span>
     @elseif($row->status == 2)
    <span class="badge badge-danger badge-sm statusLabel">{{ 'Decline' }}</span>
    @elseif($row->status == 0)
    <span class="badge badge-success badge-sm statusLabel">{{ 'Success' }}</span>
    @endif
</td> --}}
<td>
@if($row->status == 1)
<button type="button" data-id="{{ $row->id }}" id="updateOrderStatus" class="btn btn-primary btn-sm eyeViewbtn" data-toggle="modal" data-target="#updateOrderStatusModal">Pending</button>
@elseif($row->status == 0 )
<button type="button" class="btn btn-success btn-sm eyeViewbtn">Success</button>
@endif
</td>
</tr>
@endforeach
<tr><td colspan="16" align="center">{!! $saleList->links() !!}</td></tr>
    


                    