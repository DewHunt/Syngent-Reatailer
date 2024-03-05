@if(isset($RetailerList))
    @foreach($RetailerList as $row)
        @php 
        	$passwordStatus = getPasswordStatus('retailer',$row->id);
        	if (isset($passwordStatus) && $passwordStatus == 1) {
        		$passwordLabel = "Changed Password";
        	} else {
        		$passwordLabel = "Set Password";
        	}
    	@endphp

        <tr>
            <td>{{ ++$loop->index }}.</td>
            <td>
                {{ $row->retailer_name }} <br/>
                @if ($row->shop_start_time != null && $row->shop_end_time != null)
                    <i class="fa fa-clock-o" aria-hidden="true"></i>
                    {{ date('h:i',strtotime($row->shop_start_time)) }} {{ $row->start_time_ampm }} {{'-'}} 
                    <i class="fa fa-clock-o" aria-hidden="true"></i>
                    {{ date('h:i',strtotime($row->shop_end_time)) }} {{ $row->end_time_ampm }}
                @endif
            </td>
            <td>{{ $row->phone_number }}</td>
            <td>{{ $row->retailder_address }}</td>
            <td>{{ $row->owner_name }}</td>
            {{-- <td>{{ $row->dealer_name }} </td> --}}
            {{-- <td>{{ $row->distributor_code }}</td>         --}}
            <td>
                <button type="button" data-id="{{ $row->id }}" id="editInfo" class="btn btn-primary btn-sm" style="margin-bottom: 5px;" data-toggle="modal" data-target="#editModal">Edit</button>

                <button type="button" data-id="{{ $row->id }}" id="viewRetailerDetails" class="btn cur-p btn-info btn-sm eyeViewbtn" data-toggle="modal" data-target="#viewRetailerDetailsModal">View</button>

                @if($row->status == 1)
                    @php
                        $btnClass = "btn-info";
                        $btnText = "Set Working Hour";
                        if ($row->shop_start_time > 0) {
                            $btnClass = "btn-success";
                            $btnText = "Edit Working Hour";
                        }
                    @endphp
                    <button type="button" data-id="{{ $row->id }}" id="setRetailerWorkingHour" class="btn {{ $btnClass }} btn-sm setworkingbtn" data-toggle="modal" data-target="#setWorkingHourModal">{{ $btnText }}</button>
                @endif
                {{-- <button type="button" data-id="{{ $row->id }}" id="retailerPasswordSet" class="btn btn-primary btn-sm">{{ $passwordLabel }}</button> --}}
            </td>
        </tr>
    @endforeach
    <tr><td colspan="9" align="center">{!! $RetailerList->links() !!}</td></tr>
@endif