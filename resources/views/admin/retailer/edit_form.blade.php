<form class="form-horizontal" method="POST" action="{{ route('retailer.update') }}" id="UpdateRetailer">
    @csrf
    <input type="hidden" name="update_id" id="update_id" value="{{ $RetailerInfo->id }}"/>
    <input type="hidden" name="retailer_id" class="UpdateApiRetailerId" value="{{ $RetailerInfo->retailer_id }}"/>
    <input type="hidden" name="zone_id" class="UpdateApiRetailerZoneId" value="{{ $RetailerInfo->zone_id }}"/>
    <input type="hidden" name="division_id" class="UpdateApiRetailerDivisionId" value="{{ $RetailerInfo->division_id }}"/>
    <input type="hidden" name="distric_id" class="UpdateApiRetailerDistricId" value="{{ $RetailerInfo->distric_id }}"/>
    <input type="hidden" name="thana_id" class=" UpdateApiRetailerThanaID" value="{{ $RetailerInfo->thana_id }}"/>
    <div class="row">
    	<div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12" style="display: none;">
    		<div class="form-group">
                <label>Select Category <span class="required">*</span></label>
                <select class="form-control" data-placeholder="Select Category" style="width: 100%;" name="category_id" required="">
                    <option value="">Select Category</option>
                    @if(isset($CategoryList))
                        @foreach($CategoryList as $row)
                        	@php
                        		$select = '';
                        		if ($row->id == $RetailerInfo->category_id) {
                        			$select = "selected";
                        		}
                        	@endphp
                            <option value="{{ $row->id }}" class="UpdateApiCategoryId{{ $row->name }}" {{ $select }}>{{ $row->name }}</option>
                        @endforeach
                    @endif
                </select>
    		</div>
    	</div>

    	<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
    		<div class="form-group">
                <label>Retailer Name <span class="required">*</span></label>
                <input type="text" name="retailer_name" class="form-control UpdateApiRetailerName" placeholder="Name" required="" value="{{ $RetailerInfo->retailer_name }}"/>
                <span class="text-danger"><strong id="update-name-error"></strong></span>
    		</div>
    	</div>

    	<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
    		<div class="form-group">
                <label>Owner Name <span class="required">*</span></label>
                <input type="text" name="owner_name" class="form-control UpdateApiRetailerOwnerName" placeholder="Owner Name" required="" value="{{ $RetailerInfo->owner_name }}"/>
                <span class="text-danger"><strong id="update-owner-name-error"></strong></span>
    		</div>
    	</div>
    </div>

    <div class="row">
    	<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
    		<div class="form-group">
                <label>Police Station</label>
                <input type="text" name="police_station" class="form-control UpdateApiRetailerPoliceStation" placeholder="Police Station" value="{{ $RetailerInfo->police_station }}"/>
    		</div>
    	</div>
    	
    	<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
    		<div class="form-group">
                <label>Phone Number <span class="required">*</span></label>
                <input type="text" name="phone_number" maxlength="11"  minlength="11" class="form-control UpdateApiRetailerPhone Number" placeholder="Phone Number" required="" value="{{ $RetailerInfo->phone_number }}"/>
                <span class="text-danger"><strong id="update-phone-error"></strong></span>
    		</div>
    	</div>
    </div>

    <div class="row">
    	<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
    		<div class="form-group">
                <label>Retailer Address <span class="required">*</span></label>
                <textarea name="retailder_address" class="form-control UpdateApiRetailerAddress" required="" cols="3" rows="2">{{ $RetailerInfo->retailder_address }}</textarea>
                <span class="text-danger"><strong id="update-address-error"></strong></span>
    		</div>
    	</div>
    </div>

    <div class="row" style="display: none;">
    	<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
    		<div class="form-group">
                <label>Distributor Code <span class="required">*</span></label>
                <div class="input-group">
                    <input type="text" name="distributor_code" class="form-control UpdateApiRetailerDistributorCode" id="usearch_retailer_dealer_code" placeholder="Search Dealer By Code" value="{{ $RetailerInfo->distributor_code }}">
                    <div class="input-group-append">
                        <button class="btn  btn-primary" type="button" id="usearch_retailer_dealer_button">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </div>
    		</div>
    	</div>
    </div>

    <!--------- Start  ---->
    <div class="row" style="display: none;">
    	<div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-12">
    		<div class="form-group">
                <label>Alternet Code</label>
                <input type="text" name="distributor_code2" class="form-control UpdateApiRetailerDistributorCode2 udealerAlternetCode" placeholder="Alternet Distributor Code" value="{{ $RetailerInfo->distributor_code2 }}"/>
    		</div>
    	</div>
    	
    	<div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-12">
    		<div class="form-group">
                <label>Distributor Zone</label>
                <input type="text" name="distributor_zone" class="form-control udealerZone" placeholder="Zone" value="{{ $RetailerInfo->zone_name }}"/>
    		</div>
    	</div>
    	
    	<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
    		<div class="form-group">
                <label>Distributor Name</label>
                <input type="text" name="distributor_name" class="form-control udealerName" placeholder="Name" value="{{ $RetailerInfo->dealer_name }}"/>
    		</div>
    	</div>
    </div>
    <!--------- End  ---->

    <div class="row" style="display: none;">
    	<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
    		<div class="form-group">
                <label>Division Name</label>
                <input type="text" name="division_name" class="form-control UpdateApiRetailerDivisionName" placeholder="Division Name" value="{{ $RetailerInfo->division_name }}"/>
    		</div>
    	</div>
    	
    	<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
    		<div class="form-group">
                <label>Distric Name</label>
                <input type="text" name="distric_name" class="form-control UpdateApiRetailerDistric" placeholder="Distric Name" value="{{ $RetailerInfo->distric_name }}"/>
    		</div>
    	</div>
    </div>

    <div class="row">
    	<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12" style="display: none;">
    		<label>Payment Type <span class="required">*</span></label>
    		<div class="form-group">
				<label><input type="radio" id="umfc" class="payment_type" name="payment_type" value="1"> MFC</label>
				&nbsp;&nbsp;
				<label><input type="radio" id="ubank" class="payment_type" name="payment_type" value="2"> Bank Account</label>
    		</div>
    	</div>
    	
    	<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
    		<div class="form-group">
                <label>Status</label>
                <div class="col-sm-6" style="padding-left:0px !important">
                    <label><input type="radio" id="option1" name="status" value="1" checked> Active</label>&nbsp;&nbsp;
                    <label><input type="radio" id="option2" name="status" value="0"> In-Active</label>
                </div>
    		</div>
    	</div>
    </div>

    <!--------- Start  -------->
    <span class="paymentNumber" style="display: none;">
        <div class="agentDiv">
		    <div class="row">
		    	<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
		            <div class="form-group">
		                <input type="text" name="agent_name" class="form-control mfc_name" placeholder="Enter Agent Name Ex:Bkash,Nogod,Rocket" value="{{ $RetailerInfo->agent_name }}"/>
		            </div>
		    	</div>

		    	<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
		            <div class="form-group">
		                <input type="text" name="payment_number" class="form-control UpdateApiRetailerPaymentNumber mfc_field" onkeypress="return (event.charCode != 8 && event.charCode == 0 || (event.charCode >= 48 && event.charCode <= 57))" maxlength="11" minlength="11" required="" value="{{ $RetailerInfo->payment_number }}"/>
		            </div>
		    	</div>
		    </div>
        </div>

        <div class="bankDiv">
		    <div class="row">
		    	<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
		            <div class="form-group">
		                <input type="text" name="bank_name" class="form-control UpdateBankName" placeholder="Enter Bank Name Ex:DBBL,Jamuna Bank" value="{{ $RetailerInfo->bank_name }}"/>
		            </div>
		    	</div>

		    	<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
		            <div class="form-group">
		                <input type="text" name="payment_number" class="form-control UpdateApiRetailerPaymentNumber bank_field" placeholder="Bank Payment Number"  minlength="11" required="" value="{{ $RetailerInfo->payment_number }}" />
		            </div>
		    	</div>
		    </div>
        </div>
    </span>
    <!--------- End  -------->

    <div class="row">
    	<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 text-right">
    		<div class="form-group">
			    <button type="button" class="btn btn-secondary btnCloseModal" data-dismiss="modal">Close</button> 
			    <button type="submit" class="btn btn-primary">Update</button>
    		</div>
    	</div>
    </div>
</form>