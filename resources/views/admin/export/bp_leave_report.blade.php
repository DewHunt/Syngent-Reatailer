<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>BP Leave Reports</title>
	<style type="text/css">
		.center{
			text-align:center;
		}
		table, td, th {
			border: 1px solid black;
			font-size:10px;
			padding:0px 6px;
		}
		.text-right{
			text-align:right
		}
		.text-left{
			text-align:left
		}

		table {
			width: 100%;
			border-collapse: collapse;
		}
	</style>
</head>
    <body>
        <div class="center">
            <h1>BP Leave Reports</h1>
        </div>
        <table id="reports">
			<thead>
				<tr>
					<th>Sl.</th>
					<th>BP Name</th>
					<th>BP Phone</th>
					<th>Retailer Name</th>
					<th>Retailer Phone</th>
					<th>Dealer Name</th>
					<th>Dealer Phone</th>
					<th>Leave Type</th>
					<th>Apply Date</th>
					<th>Start Date</th>
					<th>Total Day</th>
					<th>Start Time</th>
					<th>Reason</th>
					<th>Status</th>
				</tr>
			</thead>
			<tbody>
			@foreach($leaveList as $row)
				<tr>
					<td>{{ ++$loop->index }}.</td>
					<td>{{ $row->bp_name }}</td> 
					<td>{{ $row->bp_phone }}</td>
					<td>{{ $row->retailer_name }}</td>
					<td>{{ $row->retailer_phone_number }}</td>
					<td>{{ $row->dealer_name }}</td>
					<td>{{ $row->dealer_phone_number }}</td>
					<td>{{ $row->leave_type }}</td>
					<td>{{ date('d-m-Y', strtotime($row->apply_date)) }}</td>
					<td>{{ date('d-m-Y', strtotime($row->start_date)) }}</td>
					<td>{{ $row->total_day }}</td>
					<td>{{ $row->start_time }}</td>
					<td>{{ $row->reason }}</td>
					<td>
						@if($row->status == 'Pending')
						<span class="badge badge-warning badge-sm">{{ 'Pending' }}</span>
						@elseif($row->status == 'Approved')
						<span class="badge badge-success badge-sm">{{ 'Approved' }}</span>
						@else
						<span class="badge badge-danger badge-sm">{{ 'Cancel' }}</span>
						@endif
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</body>
</html>