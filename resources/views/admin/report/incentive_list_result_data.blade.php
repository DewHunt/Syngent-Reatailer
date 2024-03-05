@if(isset($IncentiveList))
@php $totalQty = 0; $totalAmount = 0;@endphp
    @foreach($IncentiveList as $key=>$row)
    @php 
    $num_of_items = count($productNameList); 
    $num_count = 0;
    $totalQty += $row->min_qty;
    $totalAmount += $row->incentive_amount;
    @endphp
    <tr>
        <td>{{ ++$loop->index }}.</td>
        <td>{{ ($row->incentive_group == 1) ? 'BP':'Retailer' }}</td>
        <td>{{ $row->incentive_title }}</td>
        <td>{{ ucfirst($row->incentive_category) }}</td>
        <td>{{ $row->start_date }}</td>
        <td>{{ $row->end_date }}</td>
        <td>{{ $row->min_qty }}</td>
        <td class="text-right">{{ number_format($row->incentive_amount,2) }}</td>
        <td><span class="badge badge-{{ (date('Y-m-d') > $row->end_date) ? 'danger':'primary' }}">{{ (date('Y-m-d') > $row->end_date) ? 'Expire':'Active' }}</span></td>
    </tr>
    @endforeach
    <tr>
        <td colspan="6" class="text-right"><b>Total:</b></td>
        <td><b>{{ $totalQty }}</b></td>
        <td class="text-right"><b>{{ number_format($totalAmount,2) }}</b></td>
        <td></td>
    </tr>
    <tr><td colspan="9" align="center">{!! $IncentiveList->links() !!}</td></tr>
@endif