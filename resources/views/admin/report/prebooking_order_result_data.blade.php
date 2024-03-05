@if(isset($preBookingOrderList) && !$preBookingOrderList->isEmpty())
@php $preBookingTotQty =0; $preBookingTotAmount =0;  @endphp
    @foreach($preBookingOrderList as $row)
    <tr>
        <td>{{ ++$loop->index }}.</td>
        <td>{{ $row->customer_name }}</td>
        <td>{{ $row->customer_phone }}</td>
        <td>{{ $row->model }}</td>
        <td>{{ $row->color }}</td>
        <td>{{ $row->dealer_name }}</td>
        <td>{{ $row->dealer_phone_number }}</td>
        <td>{{ $row->distributor_code }}</td>
        <td>{{ $row->retailer_name }}</td>
        <td>{{ $row->retailer_phone_number }}</td>
        <td>{{ $row->bp_name }}</td> 
        <td>{{ $row->bp_phone }}</td>
        <td>{{ $row->booking_date }}</td>
        @php $preBookingTotQty +=$row->bookingQty; $preBookingTotAmount +=$row->advanced_payment  @endphp
        <td>{{ $row->bookingQty }}</td>
        <td class="text-right">{{ number_format($row->advanced_payment,2) }}</td>
        {{-- 
        <td style="text-align: center;">
        <button type="button" data-id="{{ $row->model }}" id="viewOrderSalesDetails" class="btn cur-p btn-info btn-xs eyeViewbtn" data-toggle="modal" data-target="#viewOrderSalesDetailsModal"><i class="fa fa-eye" aria-hidden="true"></i></button>
        </td>
         --}}
    </tr>
    @endforeach
    @if($preBookingTotQty > 0 && $preBookingTotAmount > 0)
    <tr>
        <td colspan="13" class="text-right"><b>{{ 'Total:' }}</b></td>
        <td class="text-left"><b>{{ $preBookingTotQty }}</b></td>
        <td class="text-right"><b>{{ number_format($preBookingTotAmount,2) }}</b></td>
    </tr>
    @endif
@else

<tr class="text-center text-danger"><td colspan="15">Result Not Found</td></tr>    
<tr><td colspan="16" align="center">{!! $preBookingOrderList->links() !!}</td></tr>
@endif

    


                    