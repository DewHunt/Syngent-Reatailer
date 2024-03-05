@if(isset($IncentiveList))
    @foreach($IncentiveList as $key=>$row)
    @php
    $num_of_items   = count($productNameList); $num_count = 0; 
    $modelNames     = getIncentiveModels($row->product_model);
    $zoneNames      = getIncentiveZones($row->zone);
    $groupName      = getIncentiveGroups($row->group_category_id);
    @endphp
    <tr>
        <td>{{ ++$loop->index }}.</td>
        <td>{{ $row->incentive_title }}</td>
        <td>{{ ucfirst($row->incentive_category) }}</td>
        @if(!empty($modelNames))
        <td>{{ ucfirst($modelNames) }}</td>
        @endif
        @if(!empty($zoneNames))
        <td>{{ ucfirst($zoneNames) }}</td>
        @endif
        <td>{{ $groupName }}</td>
        <td>{{ number_format($row->incentive_amount,2) }}</td>
        <td>{{ $row->min_qty }}</td>
        <td>{{ $row->start_date }}</td>
        <td>{{ $row->end_date }}</td>
        <td>
            <input data-id="{{ $row->id }}" class="incentive-update-toggle" type="checkbox" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="Active" data-off="InActive" @if($row->status == 1 )checked @else {{ ' ' }}@endif>
        </td>
        <td>
            <!--
			<form action="{{ url('incentive.destroy',$row->id) }}" method="get">
            @csrf
			-->
                <a href="{{url('incentive.edit', \Crypt::encrypt($row->id))}}">
                    <button type="button"  class="btn btn-primary btn-sm editBtn">Edit</button>
                </a>
                {{-- 
                <button onclick="return confirm('Are you sure to delete?')" type="submit" class="btn btn-danger btn-sm">
                    Delete
                </button> 
                --}}<br/>
				 <!--<button onclick="payIncentive({{ $row->id }})" type="button" class="btn btn-info btn-sm editBtn mt5">Pay Incentive</button>-->
            <!--</form>-->
        </td>
    </tr>
    @endforeach
	<tr><td colspan="13" align="center">{!! $IncentiveList->links() !!}</td></tr>
@endif
    


                    