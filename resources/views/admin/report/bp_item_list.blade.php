@if($salesInfo)
	<table class='table table-striped table-bordered table-sm' cellspacing='0' width='100%'>
		<tbody>
			<tr>
				<th scope='row'>BP Name:</th>
				<td>{{  $salesInfo->bp_name  }}</td>
			</tr>
			<tr>
				<th scope='row'>BP Phone:</th>
				<td>{{ $salesInfo->bp_phone }}</td>
			</tr>
		</tbody>
	</table>
@endif

@if($saleProductList)
	<div class="table-responsive">
	    <table class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
	        <thead>
	            <tr>
	                <th>Sl.</th>
	                <th>Type</th>
	                <th>Model</th>
	                <th>Color</th>
	                <th>Total Sale Qty</th>
	                <th class="text-right">Total Sale Price</th>
	            </tr>
	        </thead>
	        <tbody>
	        	@php
	            $totalMSRP =0;
	            $totalSaleQty=0;
	            $totalSalePrice=0;
	        	@endphp
	        	@foreach($saleProductList as $row)
	        	@php
	            $totalMSRP +=$row->msrp_price;
	            $totalSaleQty+=$row->total_qty;
	            $totalSalePrice+=$row->total_sale_amount;
	        	@endphp
	        	<tr>
	        		<td>{{ ++$loop->index }}</td>
	        		<td>{{ $row->product_type }}</td>
	        		<td>{{ $row->product_model }}</td>
	        		<td>{{ $row->product_color }}</td>
	        		<td>{{ $row->total_qty }}</td>
	        		<td class="text-right">{{ number_format($row->total_sale_amount,2) }}</td>
	        	</tr>
	        	@endforeach
	        </tbody>
	        <tr>
	            <th></th>
	            <th></th>
	            <th></th>
	            <th class="text-right">Total:</th>
	            <th class="text-bold">{{ $totalSaleQty }}</th>
	            <th class="text-right text-bold"> {{ $totalSalePrice }}</th>
	        </tr>
	    </table>
	</div>
@endif