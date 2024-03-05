<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Sales Return Report</title>
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
            <h1>Sales Return Invoice Report</h1>
        </div>
        <table id="reports">
			<thead>
				<tr>
					<tr>
						<th>Sl.</th>
						<th>Photo</th>
						<th>Customer Name</th>
						<th>Customer Phone</th>
						<th>Dealer Name</th>
						<th>Dealer Code</th>
						<th>Retailer Name</th>
						<th>Retailer Phone</th>
						<th>BP Name</th>
						<th>BP Phone</th>
						<th>Sale Date</th>
						<th>Sales Qty</th>
						<th>Sales Amount</th>
						<th>Order Type</th>
					</tr>
				</tr>
			</thead>
			<tbody>
			@if(isset($saleList))
				@php $saleQty=0;$saleAmount=0; @endphp
					@foreach($saleList as $row)
					@php $saleQty+=$row->sale_qty;$saleAmount +=$row->sale_price; @endphp
					<tr>
						<td>{{ ++$loop->index }}.</td>
						<td><img src="{{ asset('public/upload/client/'.$row->photo) }}" alt="photo" width="20" height="20"/></td>
						<td>{{ $row->customer_name }}</td>
						<td>{{ $row->customer_phone }}</td>
						<td>{{ $row->dealer_name }}</td>
						<td>{{ $row->dealer_code }}</td>
						<td>{{ $row->retailer_name }}</td>
						<td>{{ $row->retailer_phone_number }}</td>
						<td>{{ $row->bp_name }}</td>
						<td>{{ $row->bp_phone }}</td>
						<td>{{ $row->sale_date }}</td>
						<td>{{ $row->sale_qty }}</td>
						<td>{{ number_format($row->sale_price,2) }}</td>
						<td>
							@if($row->order_type == 1)
							<span class="badge badge-info badge-sm">{{ 'Online' }}</span>
							@elseif($row->order_type == 2)
							<span class="badge badge-success badge-sm">{{ 'Offline' }}</span>
							@endif
						</td>
					</tr>
					@endforeach
					<tr>
						<td colspan="10"></td>
						<td><b>Total:</b></td>
						<td>{{ $saleQty }}</td>
						<td class="text-right">{{ number_format($saleAmount,2) }}</td>
						<td></td>
					</tr>
				@endif
			</tbody>
		</table>
	</body>
</html>