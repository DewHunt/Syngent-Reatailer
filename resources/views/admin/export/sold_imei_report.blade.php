<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Sold IMEI List Reports</title>
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
            <h1>Sold IMEI List Reports</h1>
        </div>
        <table id="reports">
			<thead>
				<tr>
					<th>Sl.</th>
					<th>IMEI 1</th>
					<th>IMEI 2</th>
					<th>Model</th>
					<th>Sales Date</th>
					<th>Sales Amount</th>
					<th>Dealer Name</th>
					<th>Dealer Phone</th>
					<th>Dealer Code</th>
					<th>Retailer Name</th>
					<th>Retailer Phone</th>
					<th>BP Name</th>
					<th>BP Phone</th>
				</tr>
			</thead>
			<tbody>
			@if(isset($saleList))
				@php $totalSalesAmount = 0; @endphp
				@foreach($saleList as $row)
				@php $totalSalesAmount +=$row->msrp_price; @endphp
				<tr>
					<td>{{ ++$loop->index }}.</td>
					<td>{{ $row->ime_number }}</td>
					<td>{{ $row->alternate_imei }}</td>
					<td>{{ $row->product_model }}</td>
					<td>{{ $row->sale_date }}</td>
					<td class="text-right">{{ number_format($row->msrp_price,2) }}</td>
					<td>{{ $row->dealer_name }}</td>
					<td>{{ $row->dealer_phone_number }}</td>
					<td>{{ $row->dealer_code }}</td>
					<td>{{ $row->retailer_name }}</td>
					<td>{{ $row->retailer_phone_number }}</td>
					<td>{{ $row->bp_name }}</td> 
					<td>{{ $row->bp_phone }}</td>
				</tr>
				@endforeach
				<tr>
					<td colspan="4"></td>
					<td><b>Total:</b></td>
					<td>{{ number_format($totalSalesAmount) }}</td>
					<td colspan="7"></td>
				</tr>
			@endif
			</tbody>
		</table>
	</body>
</html>