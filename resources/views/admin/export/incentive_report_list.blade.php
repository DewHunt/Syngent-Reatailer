<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Incentive  Reports</title>
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
		<h1>Incentive  Reports</h1>
	</div>
	<table id="reports">
		<thead>
			<tr>
				<th>Sl.</th>
				<th>Group</th>
				<th>Title</th>
				<th>Category</th>
				<th>Start Date</th>
				<th>End Date</th>
				<th>Min.Qty</th>
				<th>Amount</th>
			</tr>
		</thead>
		<tbody>
		@php 
		$totalQty = 0; 
		$totalAmount = 0;
		$num_count = 0;
		
		@endphp
		@if(isset($IncentiveList))
			@foreach($IncentiveList as $key=>$row)
			@php
			$totalQty += $row->min_qty;
			$totalAmount += $row->incentive_amount;
			@endphp
			<tr>
				<td>{{ ++$loop->index }}.</td>
				<td>{{ ($row->incentive_group == 1) ? 'BP':'Retailer' }}</td>
				<td>{{ $row->incentive_title }}</td>
				<td>{{ ucfirst($row->incentive_category) }}</td>
				<td>{{ $row->start_date }}</td>
				<td>{{ $row->end_date }}</td>
				<td class="text-left">{{ $row->min_qty }}</td>
				<td class="text-right">{{ number_format($row->incentive_amount,2) }}</td>
			</tr>
			@endforeach
			<tr>
				<td colspan="6" class="text-right"><b>Total:</b></td>
				<td class="text-left"><b>{{ $totalQty }}</b></td>
				<td class="text-right"><b>{{ number_format($totalAmount,2) }}</b></td>
			</tr>
		@endif
		</tbody>
	</table>
</body>
</html>