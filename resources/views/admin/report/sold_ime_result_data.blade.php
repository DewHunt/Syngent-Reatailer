@if(isset($soldImeList))
    @php $totalSalesAmount = 0; @endphp
    @foreach($soldImeList as $row)
    @php $totalSalesAmount +=$row->msrp_price; @endphp
    <tr>
        <td>{{ ++$loop->index }}.</td>
        <td>{{ $row->ime_number }}</td>
        <td>{{ $row->alternate_imei }}</td>
        <td>{{ $row->product_model }}</td>
        <td>{{ $row->sale_date }}</td>
        <td class="text-right">{{ number_format($row->msrp_price,2) }}</td>
        <td>{{ $row->dealer_name }}</td>
        <td>{{ $row->dealer_phone_number }}</td>
        <td>{{ $row->dealer_code }}</td>
        <td>{{ $row->retailer_name }}</td>
        <td>{{ $row->retailer_phone_number }}</td>
        <td>{{ $row->bp_name }}</td> 
        <td>{{ $row->bp_phone }}</td>
        {{-- td style="text-align: center;">
            <button type="button" data-id="{{ $row->product_master_id }}" id="viewProductInfo" class="btn cur-p btn-info btn-xs eyeViewbtn" data-toggle="modal" data-target="#viewProductModal"><i class="fa fa-eye" aria-hidden="true"></i></button>
        </td> --}}
    </tr>
    @endforeach
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td class="text-right"><b>Total:</b></td>
        <td class="text-right">{{ number_format($totalSalesAmount) }}</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td> 
        <td></td>
    </tr>
    <tr><td colspan="13" align="center">{!! $soldImeList->links() !!}</td></tr>
@endif


                    