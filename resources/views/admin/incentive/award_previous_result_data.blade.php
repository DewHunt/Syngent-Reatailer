@if(isset($previousAwardList))
    @foreach($previousAwardList as $key=>$row)
    @php $num_of_items = count($productNameList); $num_count = 0; @endphp
    <tr>
        <td>{{ ++$loop->index }}.</td>
        <td>{{ $row->award_title }}</td>
        <td>{{ ucfirst($row->award_type) }}</td>
        <td>{{ $row->min_qty }}</td>
        <td>{{ $row->start_date }}</td>
        <td>{{ $row->end_date }}</td>
    </tr>
    @endforeach
	<tr><td colspan="7" align="center">{!! $previousAwardList->links() !!}</td></tr>
@endif
    


                    