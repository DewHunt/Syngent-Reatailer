@php 
    $totalIncentiveQty = 0;
    $totalIncentiveAmount = 0;
@endphp
@if(isset($salesIncentiveReportList))
    @foreach($salesIncentiveReportList as $key=>$row)
    <tr>
        <td>{{ ++$loop->index }}.</td>
        <td>{{ ucfirst($row['category']) }}</td>
        <td>{{ $row['imei1'] }}</td>
        <td>{{ $row['imei2'] }}</td>
        <td>{{ $row['model'] }}</td>
        <td>{{ $row['dealer_name'] }}</td>
        <td>{{ $row['dealer_phone_number'] }}</td>
        <td>{{ $row['retailer_name'] }}</td>
        <td>{{ $row['retailer_phone_number'] }}</td>
        <td>{{ $row['bp_name'] }}</td>
        <td>{{ $row['bp_phone'] }}</td>
        @php 
        $totalIncentiveQty += $row['total_qty'];
        $totalIncentiveAmount += $row['total_incentive'];
        @endphp
        <td align="right">{{ $row['total_qty'] }}</td>
        <td class='text-right'>{{ number_format($row['total_incentive'],2) }}</td>
        <td>{{ $row['sale_date'] }}</td>
    </tr>
    @endforeach
@endif