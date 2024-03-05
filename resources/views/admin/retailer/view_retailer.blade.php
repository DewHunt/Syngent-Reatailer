<table class="table table-striped table-borderless table-sm" cellspacing="0" width="100%">
	<tbody>
		@if ($RetailerInfo)
			<tr>
				<th width="150px">Retailer Name</th>
				<td>{{ $RetailerInfo->retailer_name }}</td>
			</tr>
			<tr>
				<th width="150px">Owner Name</th>
				<td>{{ $RetailerInfo->owner_name }}</td>
			</tr>
			<tr>
				<th width="150px">Phone</th>
				<td>{{ $RetailerInfo->phone_number }}</td>
			</tr>
			<tr>
				<th width="150px">Police Station</th>
				<td>{{ $RetailerInfo->police_station }}</td>
			</tr>
			<tr>
				<th width="150px">Address</th>
				<td>{{ $RetailerInfo->retailder_address }}</td>
			</tr>
			<tr>
				<th width="150px">Shop Opening Time</th>
				<td>{{ $RetailerInfo->shop_start_time.'-'.strtoupper($RetailerInfo->start_time_ampm) }}</td>
			</tr>
			<tr>
				<th width="150px">Shop Closing Time</th>
				<td>{{ $RetailerInfo->shop_end_time.'-'.strtoupper($RetailerInfo->end_time_ampm) }}</td>
			</tr>
			{{-- <tr>
				<th width="150px">Agent Name</th>
				<td>{{ $RetailerInfo->agent_name }}</td>
			</tr> --}}
			{{-- <tr>
				<th width="150px">Bank Name</th>
				<td>{{ $RetailerInfo->bank_name }}</td>
			</tr> --}}
			{{-- <tr>
				<th width="150px">Payment Number</th>
				<td>{{ $RetailerInfo->payment_number }}</td>
			</tr> --}}
			{{-- <tr>
				<th width="150px">Division</th>
				<td>{{ $RetailerInfo->division_name }}</td>
			</tr> --}}
			{{-- <tr>
				<th width="150px">Distric Name</th>
				<td>{{ $RetailerInfo->distric_name }}</td>
			</tr> --}}
			{{-- <tr>
				<th width="150px">Distributor Code</th>
				<td>{{ $RetailerInfo->distributor_code }}</td>
			</tr> --}}
			{{-- <tr>
				<th width="150px">Alternate Code</th>
				<td>{{ $RetailerInfo->distributor_code2 }}</td>
			</tr> --}}
			{{-- <tr>
				<th width="150px">Dealer Name</th>
				<td>{{ $RetailerInfo->dealer_name }}</td>
			</tr> --}}
			{{-- <tr>
				<th width="150px">Zone</th>
				<td>{{ $RetailerInfo->zone_name }}</td>
			</tr> --}}
			{{-- <tr>
				<th width="150px">Category</th>
				<td>{{ $RetailerInfo->category_name }}</td>
			</tr> --}}
		@else
			<tr>
				<th>Retailer Not Found! Please Try Another Dealer</th>
			</tr>
		@endif
	</tbody>
</table>