@if(isset($bpSalesList))
    @php $totalQty = 0; $totalAmount=0; @endphp
    @foreach($bpSalesList as $row)
        @php 
        $totalQty +=$row->total_qty;
        $totalAmount+=$row->total_sale_amount;
        @endphp
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
        <td>{{ $row->bp_name }}</td>
        <td>{{ $row->bp_phone }}</td>
        <td>{{ $row->retailer_name }}</td>
        <td>{{ $row->retailer_phone_number }}</td>
        <td>{{ $row->dealer_name }}</td>
        <td>{{ $row->dealer_phone_number }}</td>
        <td>{{ $row->dealer_code }}</td>
        <td>{{ $row->total_qty }}</td>
        <td class="text-right">{{ number_format($row->total_sale_amount,2) }}</td>
        <td style="text-align: center;">
            <button type="button" data-id="{{ $row->bp_id }}" id="viewBpOrderDetails" class="btn cur-p btn-info btn-xs eyeViewbtn" data-toggle="modal" data-target="#viewOrderDetailsModal"><i class="fa fa-eye" aria-hidden="true"></i></button>
        </td>
    </tr>
    @endforeach
    <tr>
        <td colspan="9" class="text-right"><b>Total</b></td>
        <td><b>{{ $totalQty }}</b></td>
        <td class="text-right"><b>{{ number_format($totalAmount,2) }}</b></td>
        <td></td>
    </tr>
    <tr><td colspan="12" align="center">{!! $bpSalesList->links() !!}</td></tr>
@endif