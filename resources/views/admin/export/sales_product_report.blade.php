<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Sales Product Reports</title>
	<style type="text/css">
		html, body, div {
	      font-family: bangla;
	    }
		table, td, th { border: 1px solid black; font-size:10px; padding:0px 6px; }
		table { width: 100%; border-collapse: collapse; }
		.center{ text-align:center; }
		.text-right{ text-align:right }
		.text-left{ text-align:left }
	</style>
</head>
    <body>
        <div class="center">
            <h1>Sales Product Reports</h1>
        </div>
        <table id="reports">
			<thead>
				<tr>
					<th width="30px">Sl.</th>
					<th>Brand</th>
					<th>Retailer Name</th>
					<th width="100px">Retailer Phone</th>
					<th width="40px">Qty</th>
					<th width="80px">Amount</th>
				</tr>
			</thead>
			<tbody>
				@if (isset($productSalesReport) && !$productSalesReport->isEmpty())
					@php $totalQty = 0; $totalAmount = 0; @endphp
					@foreach($productSalesReport as $row)
						@php
							$totalQty += $row->saleQty;
							$totalAmount += $row->saleAmount;
						@endphp
						<tr>
							<td>{{ ++$loop->index }}.</td>
							<td>{{ $row->product_model }}</td>
							<td>{{ $row->retailer_name }}</td>
							<td>{{ $row->retailer_phone_number }}</td>
							<td class="text-right">{{ $row->saleQty }}</td>
							<td class="text-right"><span style="float:right">{{ number_format($row->saleAmount,2) }}</span></td>
						</tr>
					@endforeach
					<tr>
						<td colspan="3"></td>
						<td class="text-right"><b>Total</b></td>
						<td class="text-right"><b>{{ $totalQty }}</b></td>
						<td class="text-right"><b>{{ number_format($totalAmount,2) }}</b></td>
					</tr>
				@endif
			</tbody>
		</table>
	</body>
</html>