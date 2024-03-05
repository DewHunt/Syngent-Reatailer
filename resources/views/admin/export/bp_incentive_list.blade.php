<!DOCTYPE html>
<html lang="en">
<head>
	<title>SOLD IMEI List Report</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
<div class="container" style="margin-top:30px">
	<div class="row">
		<div class="col-sm-12">
			<table class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
				<thead>
					<tr>
						<th>Sl.</th>
						<th>IMEI</th>
						<th>BP Name</th>
						<th>Product Model</th>
						<th>Sales Qty</th>
						<th>Incentive Amount</th>
						<th>Date</th>
					</tr>
				</thead>
				<tbody>
				@if(isset($salesIncentiveDetails))
					@foreach($salesIncentiveDetails as $row)
					<tr class="text-center">
						<td>{{ ++$loop->index }}.</td>
						<td>{{ $row->ime_number }}</td>
						<td>{{ $row->bp_name }}</td>
						 <td>{{ $row->product_model }}</td>
						<td>{{ $row->incentive_sale_qty }}</td>
						<td>{{ number_format($row->incentive_amount,2) }}</td>
						<td>{{ $row->start_date }}</td>
					</tr>
					@endforeach
				@endif
				</tbody>
			</table>
		</div>
	</div>
</div>
</body>
</html>