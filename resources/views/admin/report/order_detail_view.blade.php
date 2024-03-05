@if($salesInfo)
	<div class="table-responsive">
		<table class='table table-striped table-borderless table-sm' cellspacing='0' width='100%'>
			<tbody>
				<tr>
					<th width="130px">Sale Date : </th>
					<td colspan="3">{{ $salesInfo->sale_date }}</td>
				</tr>
				<tr>
					<th width="130px">Customer Name : </th>
					<td>{{ $salesInfo->customer_name }}</td>
					<th width="130px">Customer Phone : </th>
					<td>{{ $salesInfo->customer_phone }}</td>
				</tr>
				<tr>
					<th width="130px">Retailer Name : </th>
					<td>{{ $salesInfo->retailer_name }}</td>
					<th width="130px">Retailer Phone : </th>
					<td>{{ $salesInfo->retailer_phone_number }}</td>
				</tr>
				<tr>
					<th width="130px">Retailer Address : </th>
					<td colspan="3">{{ $salesInfo->retailder_address }}</td>
				</tr>
				<tr>
					<th width="130px">Comments : </th>
					<td colspan='3'>{{ $salesInfo->note }}</td>
				</tr>
			</tbody>
		</table>
	</div>
@endif

@if($saleProductList)
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
	        <thead>
	            <tr>
	                <th width="30px">Sl.</th>
	                <th>Product</th>
	                <th width="100px" class="text-center">Price</th>
	                <th width="60px" class="text-center">Qty</th>
	                <th width="100px" class="text-center">Amount</th>
	            </tr>
	        </thead>
	        <tbody>
	        	@php
					$totalSaleQty = 0;
					$saleAmount = 0;
					$totalSaleAmount = 0;
	        	@endphp
	        	@foreach($saleProductList as $row)
	            	@php
						$totalSaleQty += $row->sale_qty;
						$saleAmount += $row->sale_qty * $row->sale_price;
						$totalSaleAmount += $saleAmount;
	            	@endphp

	            	<tr>
	            		<td>{{ ++$loop->index }}.</td>
	            		<td>{{  $row->product_model }}</td>
	            		<td class='text-right'>{{ number_format($row->sale_price,2) }}</td>
	            		<td class="text-right">{{  $row->sale_qty }}</td>
	            		<td class='text-right'>{{ number_format($saleAmount,2) }}</td>
	            	</tr>
	        	@endforeach
	        </tbody>

	        <tfoot>
		        <tr>
		            <th colspan="3" class="text-right">Total:</th>
		            <th class="text-right">{{ $totalSaleQty }}</th>
		            <th class="text-right">{{ number_format($totalSaleAmount,2) }}</th>
		        </tr>
	        </tfoot>
	    </table>
	</div>
@endif