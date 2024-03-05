<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Pre-Booking Order Reports</title>
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
            <h1>Pre-Booking Order Reports</h1>
        </div>
        <table id="reports">
			<thead>
				<tr>
					<th>Sl.</th>
					<th>Model</th>
					<th>Color</th>
					<th>Dealer Name</th>
					<th>Dealer Phone</th>
					<th>Dealer Code</th>
					<th>Alternate Code</th>
					<th>Retailer Name</th>
					<th>Retailer Phone</th>
					<th>BP Name</th>
					<th>BP Phone</th>
					<th>Pre-Order Qty</th>
					<th>Pre-Order Amount</th>
				</tr>
			</thead>
			<tbody>
			@if(isset($preBookingOrderList) && !$preBookingOrderList->isEmpty())
				@php $preBookingTotQty =0; $preBookingTotAmount =0;  @endphp
					@foreach($preBookingOrderList as $row)
					<tr>
						<td>{{ ++$loop->index }}.</td>
						<td>{{ $row->model }}</td>
						<td>{{ $row->color }}</td>
						<td>{{ $row->dealer_name }}</td>
						<td>{{ $row->dealer_phone_number }}</td>
						<td>{{ $row->distributor_code }}</td>
						<td>{{ $row->distributor_code2 }}</td>
						<td>{{ $row->retailer_name }}</td>
						<td>{{ $row->retailer_phone_number }}</td>
						<td>{{ $row->bp_name }}</td> 
						<td>{{ $row->bp_phone }}</td>
						<th>{{ $row->booking_date }}</th>
						@php $preBookingTotQty +=$row->bookingQty; $preBookingTotAmount +=$row->advanced_payment  @endphp
						<td class="text-left">{{ $row->bookingQty }}</td>
						<td class="text-right">{{ number_format($row->advanced_payment,2) }}</td>
					</tr>
					@endforeach
					@if($preBookingTotQty > 0 && $preBookingTotAmount > 0)
					<tr>
						<td colspan="11"></td>
						<td class="text-right"><b>{{ 'Total:' }}</b></td>
						<td class="text-left"><b>{{ $preBookingTotQty }}</b></td>
						<td class="text-right"><b>{{ number_format($preBookingTotAmount,2) }}</b></td>
					</tr>
					@endif
				@else
				@endif
			</tbody>
		</table>
	</body>
</html>