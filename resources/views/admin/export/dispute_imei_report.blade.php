<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Dispute IMEI List Reports</title>
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
		<h1>Dispute IMEI List Reports</h1>
	</div>
        <table id="reports">
			<thead>
				<tr>
					<th>Sl.</th>
					<th>Dealer Name</th>
					<th>Dealer Phone</th>
					<th>Dealer Code</th>
					<th>Alternate Code</th>
					<th>Retailer Name</th>
					<th>Retailer Phone</th>
					<th>BP Name</th>
					<th>BP Phone</th>
					<th>IMEI</th>
					<th>Description</th>
					<th>Comments</th>
					<th>Date</th>
					<th>Status</th>
				</tr>
			</thead>
			<tbody>
			@if(isset($imeiDisputeList))
				@foreach($imeiDisputeList as $row)
				<tr>
					<td>{{ ++$loop->index }}.</td>
					<td>{{ $row->dealer_name }}</td>
					<td>{{ $row->dealer_phone_number }}</td>
					<td>{{ $row->distributor_code }}</td>
					<td>{{ $row->distributor_code2 }}</td>
					<td>{{ $row->retailer_name }}</td>
					<td>{{ $row->retailer_phone }}</td>
					<td>{{ $row->bp_name }}</td> 
					<td>{{ $row->bp_phone }}</td>
					<td>{{ $row->imei_number }}</td>
					<td>{{ $row->description }}</td>
					<td>{{ $row->comments }}</td>
					<td>{{ $row->date }}</td>
					<td>
						@if($row->status == 0) 
						<span class="badge  badge-info badge-sm">{{ 'Pending' }}</span>
						@elseif($row->status == 1)
						<span class="badge badge-info badge-sm">{{ 'Reported' }}</span>
						@elseif($row->status == 2)
						<span class="badge badge-info badge-sm">{{ 'Decline' }}</span>
						@endif
					</td>
				</tr>
				@endforeach
			@endif
			</tbody>
		</table>
	</body>
</html>