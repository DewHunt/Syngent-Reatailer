<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Dealer Lists</title>
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
		<h1>Dealer Lists</h1>
	</div>
	<table id="reports">
		<thead>
			<tr>
				<th>Sl.</th>
				<th>Dealer Code</th>
				<th>Alternate Code</th>
				<th>Dealer Name</th>
				<th>Phone</th>
				<th>Address</th>
			</tr>
		</thead>
		<tbody>
		@if(isset($exportData))
			@foreach($exportData as $row)
			<tr>
				<td>{{ ++$loop->index }}.</td>
				<td>{{ $row['dealer_code'] }}</td>
				<td>{{ $row['alternate_code'] }}</td>
				<td>{{ $row['dealer_name'] }}</td>
				<td>{{ $row['dealer_phone_number'] }}</td>
				<td>{{ $row['dealer_address'] }}</td>
			</tr>
			@endforeach
		@endif
		</tbody>
	</table>
</body>
</html>