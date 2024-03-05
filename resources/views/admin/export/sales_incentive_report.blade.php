<!DOCTYPE html>
<html lang="en">
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
            <h1>Sales Incentive Report</h1>
        </div>
        <table id="reports">
			<thead>
				<tr>
					<th>Sl.</th>
					<th>Photo</th>
					<th>Incentive Type</th>
					<th>Dealer Name</th>
					<th>Dealer Phone</th>
					<th>Retailer Name</th>
					<th>Retailer Phone</th>
					<th>BP Name</th>
					<th>BP Phone</th>
					<th>Sales Qty</th>
					<th>Incentive Amount</th>
				</tr>
			</thead>
			<tbody>
			@php 
				$totalIncentiveQty = 0;
				$totalIncentiveAmount = 0;
			@endphp
			@if(isset($salesIncentiveReportList))
				@foreach($salesIncentiveReportList as $row)
					@if($row->total_qty > 0 && $row->total_incentive > 0)
						<tr>
							<td>{{ ++$loop->index }}.</td>
							@if(isset($row->photo) && !empty($row->photo))
							<td><img src="{{ asset('public/upload/client/'.$row->photo) }}" alt="photo" width="20" height="20"/></td>
							@else
							<td><img src="{{ asset('public/upload/client/no-image.png') }}" alt="photo" width="20" height="20"/></td>
							@endif
							<td>{{ ucfirst($row->category) }}</td>
							<td>{{ $row->dealer_name }}</td>
							<td>{{ $row->dealer_phone_number }}</td>
							<td>{{ $row->retailer_name }}</td>
							<td>{{ $row->retailer_phone_number }}</td>
							<td>{{ $row->bp_name }}</td>
							<td>{{ $row->bp_phone }}</td>
							@php 
								$totalIncentiveQty += $row->total_qty;
								$totalIncentiveAmount += $row->total_incentive;
							@endphp
							<td>{{ $row->total_qty }}</td>
							<td class='text-right'>{{ number_format($row->total_incentive,2) }}</td>
						</tr>
					@endif
				@endforeach
				<tr>
					<td colspan="8"></td>
					<td class="text-right"><b>Total:</b></td>
					<td>{{ $totalIncentiveQty }}</td>
					<td class="text-right">{{ number_format($totalIncentiveAmount,2) }}</td>
				</tr>
			@endif
			</tbody>
		</table>
	</body>
</html>