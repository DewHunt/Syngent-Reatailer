<table class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
	<thead>
		<tr>
			<th>Leave Type</th>
			<th>Start Date</th>
			<th>Start Time</th>
			<th>Total Day</th>
			<th>Reason</th>
		</tr>
	</thead>
	@php
	$totalLeaveQty = 0;
	@endphp
	<tbody id="currentMonthBPLeave">
		@foreach($leaveList as $row)
		@php $totalLeaveQty +=$row->total_day; @endphp
		<tr>
			<td>{{ $row->leave_type }}</td>
			<td>{{ date('d-m-Y', strtotime($row->start_date)) }}</td>
			<td>{{ $row->start_time }}</td>
			<td>{{ $row->total_day }}</td>
			<td>{{ $row->reason }}</td>
		</tr>
		@endforeach
	</tbody>
	<tr>
		<th></th>
		<th></th>
		<th class="text-right">Total:</th>
		<th> {{ $totalLeaveQty }}</th>
		<th></th>
	</tr>    
</table>