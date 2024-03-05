@if(isset($RetailerList))
    @foreach($RetailerList as $row)
    <tr>
        <td>{{ ++$loop->index }}.</td>
        <td>
            {{ $row->retailer_name }} <br/>
            @if($row->shop_start_time !=null && $row->shop_end_time !=null)
            <i class="fa fa-clock-o" aria-hidden="true"></i>
            {{date('h:i',strtotime($row->shop_start_time))}} {{'-'}} 
            <i class="fa fa-clock-o" aria-hidden="true"></i>
            {{date('h:i',strtotime($row->shop_end_time))}}
            @endif
        </td>
        <td>{{ $row->dealer_name }} </td>
        <td>{{ $row->distributor_code }}</td>
        <td>{{ $row->phone_number }}</td>
        <td>{{ $row->retailder_address }}</td>
        <td>{{ $row->owner_name }}</td>
        <!--
        <td>
            <input data-id="{{ $row->id }}" class="retailer-toggle-class" type="checkbox" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="Active" data-off="InActive" @if($row->status == 1 )checked @else {{ ' ' }}@endif>
        </td>
        -->
        <td>
            {{-- <button type="button" data-id="{{ $row->id }}" id="editInfo" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editModal">Edit</button> --}}

            <button type="button" data-id="{{ $row->id }}" id="viewRetailerDetails" class="btn cur-p btn-info btn-sm eyeViewbtn" data-toggle="modal" data-target="#viewRetailerDetailsModal"><i class="fa fa-eye" aria-hidden="true"></i></button>

            @if($row->status == 1) 
            <button type="button" data-id="{{ $row->id }}" id="setRetailerWorkingHour" class="btn btn-@if($row->shop_start_time > 0){{'success'}}@else{{'info'}}@endif btn-sm setworkingbtn" data-toggle="modal" data-target="#setWorkingHourModal">@if($row->shop_start_time > 0){{'Edit'}}@else{{'Set'}}@endif Working Hour</button>
            @endif

            <button type="button" data-id="{{ $row->id }}" id="retailerPasswordSet" class="btn btn-primary btn-sm">Password Set</button>
        </td>
    </tr>
    @endforeach
    <tr><td colspan="9" align="center">{!! $RetailerList->links() !!}</td></tr>
@endif



                    