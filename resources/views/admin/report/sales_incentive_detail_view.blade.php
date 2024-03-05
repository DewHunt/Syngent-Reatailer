@if($salesIncentiveReportDetails)
<div class="table-responsive">
	<table class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
        <thead>
            <tr>
				<th>Sl.</th>
				<th>IMEI Number</th>
				<th>BP Name</th>
				<th>Product Model</th>
				<th>Sale Qty</th>
				<th class='text-right'>Incentive Amount</th>
				<th>Incentive Date</th>
            </tr>
        </thead>
        <tbody>
        	@php
			$totalIncentiveAmount = 0;
        	$totalSaleQty         = 0;
        	@endphp
        	@foreach($salesIncentiveReportDetails as $row)
            	@php
				$totalIncentiveAmount += $row->incentive_amount;
            	$totalSaleQty += $row->incentive_sale_qty;
            	$Name = !empty($row->bp_name) ? $row->bp_name : $row->retailer_name;
            	@endphp

            	<tr>
            		<td>{{ ++$loop->index }}.</td>
            		<td>{{  $row->ime_number }}</td>
            		<td>{{  $Name }}</td>
            		<td>{{  $row->product_model }}</td>
            		<td>{{  $row->incentive_sale_qty }}</td>
            		<td class='text-right'>{{  number_format($row->incentive_amount,2) }}</td>
            		<td>{{  $row->incentive_date }}</td>
            	</tr>
        	@endforeach
        </tbody>
			<tr >
				<td colspan="4" class="text-right"><b>Total:</b></td>
				<td>{{ $totalSaleQty }}</td>
				<td class="text-right">{{ $totalIncentiveAmount }}</td>
				<td></td>
			</tr>
    </table>
</div>
@endif