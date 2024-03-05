@if(isset($oldIncentiveList))
    @foreach($oldIncentiveList as $key=>$row)
    @php $num_of_items = count($productNameList); $num_count = 0; @endphp
    <tr>
        <td>{{ ++$loop->index }}.</td>
        <td>{{ $row->incentive_title }}</td>
        <td>{{ ucfirst($row->incentive_category) }}</td>
        <td>{{ number_format($row->incentive_amount,2) }}</td>
        <td>{{ $row->min_qty }}</td>
        <td>{{ $row->start_date }}</td>
        <td>{{ $row->end_date }}</td>
    </tr>
    @endforeach
	<tr><td colspan="7" align="center">{!! $oldIncentiveList->links() !!}</td></tr>
@endif
    


                    