<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Retailer Lists</title>
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
		<h1>Retailer Lists</h1>
	</div>
	<table id="reports">
		<thead>
			<tr>
				<th>Sl.</th>
				<th>Retailer Name</th>
				<th>Dealer Name</th>
				<th>Dealer Code</th>
				<th>Phone</th>
				<th>Address</th>
				<th>Owner Name</th>
			</tr>
		</thead>
		<tbody>
		@foreach($retailerList as $row)
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
		</tr>
		@endforeach
		</tbody>
	</table>
</body>
</html>