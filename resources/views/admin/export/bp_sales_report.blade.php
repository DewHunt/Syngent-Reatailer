<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>BP Sales Report</title>
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
            <h1>BP Sales Report</h1>
        </div>
        <table id="reports">
			<thead>
				<tr>
					<th>Sl.</th>
					<!--<th>Photo</th>-->
					<th>BP Name</th>
					<th>BP Phone</th>
					<th>Retailer Name</th>
					<th>Retailer Phone</th>
					<th>Dealer Name</th>
					<th>Dealer Phone</th>
					<th>Dealer Code</th>
					<th>BP Sales Qty</th>
					<th>BP Sales Amount</th>
				</tr>
			</thead>
			<tbody>
			@if(isset($bpSalesList))
				@php $totalQty = 0; $totalAmount=0; @endphp
				@foreach($bpSalesList as $row)
					@php 
						$totalQty +=$row->total_qty;
						$totalAmount+=$row->total_sale_amount;
					@endphp
				<tr>
					<td>{{ ++$loop->index }}.</td>
					<!--
					@if(isset($row->photo) && !empty($row->photo))
					<td><img src="{{ asset('public/upload/client/'.$row->photo) }}" alt="photo" width="20" height="20"/></td>
					@else
					<td><img src="{{ asset('public/upload/client/no-image.png') }}" alt="photo" width="20" height="20"/></td>
					@endif
					-->
					<td>{{ $row->bp_name }}</td>
					<td>{{ $row->bp_phone }}</td>
					<td>{{ $row->retailer_name }}</td>
					<td>{{ $row->retailer_phone_number }}</td>
					<td>{{ $row->dealer_name }}</td>
					<td>{{ $row->dealer_phone_number }}</td>
					<td>{{ $row->dealer_code }}</td>
					<td>{{ $row->total_qty }}</td>
					<td class="text-right">{{ number_format($row->total_sale_amount,2) }}</td>
				</tr>
				@endforeach
				<tr>
					<td colspan="7"></td>
					<td class="text-right"><b>Total</b></td>
					<td><b>{{ $totalQty }}</b></td>
					<td class="text-right"><b>{{ number_format($totalAmount,2) }}</b></td>
				</tr>
			@endif
			</tbody>
		</table>
    </body>
</html>




                    