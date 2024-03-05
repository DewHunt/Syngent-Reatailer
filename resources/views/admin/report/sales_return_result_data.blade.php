@if(isset($saleList))
@php $saleQty=0;$saleAmount=0; @endphp
    @foreach($saleList as $row)
    @php $saleQty+=$row->sale_qty;$saleAmount +=$row->sale_price; @endphp
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
                <img src="{{ asset('public/upload/client/no-image.png') }}" alt="photo" width="50" height="50"/>
            </td>
        @endif
        <td>{{ $row->customer_name }}</td>
        <td>{{ $row->customer_phone }}</td>
        <td>{{ $row->dealer_name }}</td>
        <td>{{ $row->dealer_code }}</td>
        <td>{{ $row->retailer_name }}</td>
        <td>{{ $row->retailer_phone_number }}</td>
        <td>{{ $row->bp_name }}</td>
        <td>{{ $row->bp_phone }}</td>
        <td>{{ $row->sale_date }}</td>
        <td>{{ $row->sale_qty }}</td>
        <td>{{ number_format($row->sale_price,2) }}</td>
        <td>
            @if($row->order_type == 1)
            <span class="badge badge-info badge-sm">{{ 'Online' }}</span>
            @elseif($row->order_type == 2)
            <span class="badge badge-success badge-sm">{{ 'Offline' }}</span>
            @endif
        </td>
        <td>
            @if($row->status == 1)
            <span class="badge badge-warning badge-sm">{{ 'Pending' }}</span>
            @elseif($row->status == 0)
            <span class="badge badge-success badge-sm">{{ 'Success' }}</span>
            @endif
        </td>
        <td style="text-align: center;">
           <button type="button" data-id="{{ $row->id }}" id="orderDetailsView" class="btn cur-p btn-info btn-xs eyeViewbtn" data-toggle="modal" data-target="#viewOrderDetailsModal"><i class="fa fa-eye" aria-hidden="true"></i></button>
        </td>
    </tr>
    @endforeach
    <tr>
        <td colspan="11" class="text-right"><b>Total:</b></td>
        <td>{{ $saleQty }}</td>
        <td class="text-right">{{ number_format($saleAmount,2) }}</td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr><td colspan="16" align="center">{!! $saleList->links() !!}</td></tr>
@endif
    


                    