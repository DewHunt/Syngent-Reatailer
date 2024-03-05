<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Pending Order Reports</title>
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
		<h1>Pending Order Reports</h1>
	</div>
        <table id="reports">
			<thead>
				<tr>
					<th>Sl.</th>
					<th>Photo</th>
					<th>Customer Name</th>
					<th>Customer Phone</th>
					<th>Sale Date</th>
					<th>IMEI 1</th>
					<th>IMEI 2</th>
					<th>Model</th>
					<th>Dealer Name</th>
					<th>Dealer Phone</th>
					<th>Dealer Code</th>
					<th>Retailer Name</th>
					<th>Retailer Address</th>
					<th>Retailer Phone</th>
					<th>BP Name</th>
					<th>BP Phone</th>
					<th>Order Type</th>
					<th>Order Status</th>
				</tr>
			</thead>
			<tbody>
				@foreach($productSalesReport as $row)
				<tr>
					<td>{{ ++$loop->index }}.</td>
					@if(isset($row->photo) && !empty($row->photo))
					<td><img src="{{ asset('public/upload/client/'.$row->photo) }}" alt="photo" width="20" height="20"/></td>
					@else
					<td><img src="{{ asset('public/upload/client/no-image.png') }}" alt="photo" width="20" height="20"/></td>
					@endif
					<td>{{ $row->customer_name }}</td>
					<td>{{ $row->customer_phone }}</td>
					<td>{{ $row->sale_date }}</td>
					<td>{{ $row->ime_number }}</td>
					<td>{{ $row->alternate_imei }}</td>
					<td>{{ $row->product_model }}</td>
					<td>{{ $row->dealer_name }}</td>
					<td>{{ $row->dealer_phone_number }}</td>
					<td>{{ $row->dealer_code }}</td>
					<td>{{ $row->retailer_name }}</td>
					<td>{{ $row->retailer_phone_number }}</td>
					<td>{{ $row->retailder_address }}</td>
					<td>{{ $row->bp_name }}</td> 
					<td>{{ $row->bp_phone }}</td>
					<td>
						@if($row->order_type == 1)
						<span class="badge badge-info badge-sm statusLabel">{{ 'Online' }}</span>
						@elseif($row->order_type == 2)
						<span class="badge badge-success badge-sm statusLabel">{{ 'Offline' }}</span>
						@endif
					</td>
					<td>
						@if($row->status == 1)
						<span class="badge badge-warning badge-sm statusLabel">{{ 'Pending' }}</span>
						 @elseif($row->status == 2)
						<span class="badge badge-danger badge-sm statusLabel">{{ 'Decline' }}</span>
						@elseif($row->status == 0)
						<span class="badge badge-success badge-sm statusLabel">{{ 'Success' }}</span>
						@endif
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</body>
</html>