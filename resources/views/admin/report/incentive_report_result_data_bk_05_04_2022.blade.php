@php 
    $totalIncentiveQty = 0;
    $totalIncentiveAmount = 0;
@endphp
@if(isset($salesIncentiveReportList))
    @foreach($salesIncentiveReportList as $row)
        @if($row->total_qty > 0 && $row->total_incentive > 0)
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
                <td>{{ ucfirst($row->category) }}</td>
                <td>{{ $row->dealer_name }}</td>
                <td>{{ $row->dealer_phone_number }}</td>
                <td>{{ $row->retailer_name }}</td>
                <td>{{ $row->retailer_phone_number }}</td>
                <td>{{ $row->bp_name }}</td>
                <td>{{ $row->bp_phone }}</td>
                @php 
                    $totalIncentiveQty += $row->total_qty;
                    $totalIncentiveAmount += $row->total_incentive;
                @endphp
                <td align="right">{{ $row->total_qty }}</td>
                <td class='text-right'>{{ number_format($row->total_incentive,2) }}</td>
                <td style="text-align: center;">
                    @if($row->bp_id > 0)
                    <a href="javascript:void(0)">
                    <button type="button" data-id="{{ $row->bp_id }}" id="bpSaleIncentiveDetails" class="btn cur-p btn-info btn-xs" data-toggle="modal" data-target="#viewSaleIncentiveDetailsModal" ><i class="fa fa-eye" aria-hidden="true"></i></button>
                    </a>
                    @elseif($row->retailer_id > 0)
                    <a href="javascript:void(0)">
                    <button type="button" data-id="{{ $row->retailer_id }}" id="retailSaleIncentiveDetails" class="btn cur-p btn-info btn-xs" data-toggle="modal" data-target="#viewSaleIncentiveDetailsModal"><i class="fa fa-eye" aria-hidden="true"></i></button>
                    </a>
                    @endif
                </td>
            </tr>
        @endif
    @endforeach
    <tr>
        <td colspan="8"></td>
        <td align="right"><b>Total:</b></td>
        <td align="right">{{ $totalIncentiveQty }}</td>
        <td align="right">{{ number_format($totalIncentiveAmount,2) }}</td>
        <td></td>
    </tr>
    <tr><td colspan="12" align="center">{!! $salesIncentiveReportList->links() !!}</td></tr>
@endif