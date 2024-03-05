@if(isset($BrandPromoterList))
    @foreach($BrandPromoterList as $row)
    @php 
	$passwordStatus = getPasswordStatus('bp',$row->id);
	if(isset($passwordStatus) && $passwordStatus == 1){
		$passwordLabel = "Changed Password";
	} else {
		$passwordLabel = "Set Password";
	}
	@endphp
    <tr>
        <td>{{ ++$loop->index }}.</td>
        <td>{{ $row->category_name }}</td>
        <td>{{ $row->bp_name }}</td>
        <td>{{ $row->bp_phone }}</td>
        <td>{{ $row->distributor_name }}</td>
        <td>{{ $row->distributor_code }}</td>
        <td>{{ $row->retailer_name }}</td>
        <td>{{ $row->retailer_phone_number }}</td>
        <td>
			@php
			$status = "Active";
			$btnClass = "btn-success";
			if($row->status != 1){
				$status = "InActive";
				$btnClass = "btn-danger";
			}
			@endphp
			
			<button id="promoterStatus_{{ $row->id}}" class="btn {{$btnClass}} btnPromoterStatus" promoter-id="{{ $row->id}}" promoter-status="{{$row->status}}">{{$status}}</button>
        </td>
        <td class="text-right">
            <button type="button" data-id="{{ $row->id }}" id="editBPromoterInfo" class="btn btn-primary btn-sm commonbtnwidth" data-toggle="modal" data-target="#editBPromoterModal">Edit</button>

            <button type="button" data-id="{{ $row->id }}" id="bpPasswordSet" class="btn btn-info btn-sm commonbtnwidth">{{ $passwordLabel }}</button>

        </td>
    </tr>
    @endforeach
@endif
    


                    