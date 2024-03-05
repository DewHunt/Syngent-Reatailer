@if($attendanceList)
<div class="table-responsive">
	<table class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
        <thead>
            <tr>
				<th>Sl.</th>
				<th>BP Name</th>
				<th>In Status</th>
				<th>Out Status</th>
				<th>Location</th>
				<th>Status & Time</th>
            </tr>
        </thead>
        <tbody>
        	@foreach($attendanceList as $row)
	        	@php
					$imagePath = "no-image.png";
					$baseUrl = URL::to('');
					$pathUrl = $baseUrl.'/public/upload/bpattendance/';

	        		$remarkStatus = "";
	            	$remarksDate  = "";
		            if($row->remarks == 1) {
		                $remarkStatus = "First In";
		                $remarksDate  = $row->date;
		            }
		            elseif($row->remarks == 2) {
		                $remarkStatus = "First Out";
		                $remarksDate  = $row->date;
		            }
		            elseif($row->remarks == 3) {
		                $remarkStatus = "Again In";
		                $remarksDate  = $row->date;
		            }
		            elseif($row->remarks == 4) {
		                $remarkStatus = "Again Out";
		                $remarksDate  = $row->date;
		            }

		            if(!empty($row->selfi_pic) && $row->selfi_pic !=null) {
		                $imagePath = $row->selfi_pic;
		            }
	        	@endphp
            	<tr>
            		<td>{{ ++$loop->index }}.</td>
            		<td>{{  $row->bp_name }}</td>
            		<td>{{  ($row->in_status == 'Ok') ? 'Present': $row->in_status }}</td>
            		<td>{{  $row->out_status }}</td>
            		<td>{{  $row->location }}</td>
            		<td><b>{{  $remarkStatus }}</b> <br/> {{ $remarksDate }}</td>
            	</tr>
        	@endforeach
        </tbody>
        <tr>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
    </table>
</div>
@endif