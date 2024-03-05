<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Sales Report</title>
<style type="text/css">
	html, body, div { font-family: bangla; }
	table, td, th { border: 1px solid black; font-size: 10px; padding: 0px 6px; }
	table { width: 100%; border-collapse: collapse; }
	.center { text-align:center; }
	.text-right { text-align:right }
	.text-left { text-align:left }
</style>

</head>
    <body>
        <div class="center">
            <h1>Sales Invoice Report</h1>
        </div>
        <table id="reports">
            <thead>
                <tr>
                    <th rowspan="2" class="text-center" width="30px">Sl.</th>
                    <th colspan="2" class="text-center">Customer</th>
                    <th colspan="2" class="text-center">Retailer</th>
                    <th colspan="3" class="text-center">Sale</th>
                </tr>
                <tr>
                    <th>Name</th>
                    <th width="80px">Phone</th>
                    <th>Name</th>
                    <th width="80px">Phone</th>
                    <th width="60px">Date</th>
                    <th width="40px">Qty</th>
                    <th width="80px">Amount</th>
                </tr>
            </thead>
			<tbody>
				@if (isset($saleList))
					@php
						$totalSaleQty = 0;
						$totalSaleAmount = 0;
					@endphp
					@foreach($saleList as $row)
						@php
							$totalSaleQty += $row->total_qty;
							$totalSaleAmount += $row->total_amount;
						@endphp
						<tr>
							<td>{{ ++$loop->index }}.</td>
							<td>{{ $row->customer_name == 'null' ? '' : $row->customer_name }}</td>
							<td class="center">{{ $row->customer_phone == '01null' ? '' : $row->customer_phone }}</td>
							<td>{{ $row->retailer_name }}</td>
							<td class="center">{{ $row->retailer_phone_number }}</td>
							<td class="cneter">{{ date_format(date_create($row->sale_date),"Y-m-d") }}</td>
							<td class="text-right">{{ $row->total_qty }}</td>
							<td class="text-right">{{ number_format($row->total_amount,2) }}</td>
						</tr>
					@endforeach
				@endif
			</tbody>
			<tfoot>				
				<tr>
					<td colspan="5"></td>
					<td><b>Total:</b></td>
					<td class="text-right">{{ $totalSaleQty }}</td>
					<td class="text-right">{{ number_format($totalSaleAmount,2) }}</td>
				</tr>
			</tfoot>
		</table>
	</body>
</html>