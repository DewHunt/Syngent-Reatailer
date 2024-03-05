<form class="form-horizontal" method="POST" action="{{ route('promoOffer.update') }}" id="UpdateOffer" enctype="multipart/form-data">
    <input type="hidden" name="update_id" id="update_id" value="{{ $editOfferInfo->id }}" />
    @csrf
    <div class="row" style="display: none;">
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
            <div class="form-group">
	            <label>Offer For <span class="required">*</span></label>
	            <select class="form-control select2" style="width: 100%;" name="offer_for" required="">
	                <option value="">Select</option>
	                <option value="all" class="uall" selected>All</option>
	                <option value="retailer" class="uretailer">Retailer</option>
	            </select>
            </div>
        </div>

        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
            <div class="form-group">
	            <label>Zone</label>
	            <select class="form-control select2 uzone" multiple="multiple" data-placeholder="Select a Incentive Zone" data-dropdown-css-class="select2-purple" style="width: 100%;" id="update_zone" name="zone[]">
	                <option value="">Select Zone</option>
	                <option value="all" selected>All</option>
	                @if (isset($zoneList))
		                @foreach($zoneList as $row)
		                	<option value="{{ $row->zone_name }}">{{ $row->zone_name }}</option>
		                @endforeach
	                @endif
	            </select>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
            <div class="form-group">
	            <label>Start Date <span class="required">*</span></label>
	            <input type="text" name="sdate" class="form-control datepicker usdate" required="" value="{{ $editOfferInfo->sdate }}" />
            </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
            <div class="form-group">
	            <label>End Date <span class="required">*</span></label>
	            <input type="text" name="edate" class="form-control datepicker uedate" required="" value="{{ $editOfferInfo->edate }}" />
            </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
            <label>Status</label>
            <div class="form-group">
            	@php
            		$active = "";
            		$inActive = "";
            		if ($editOfferInfo->status == 1) {
            			$active = "checked";
            		} else {
            			$inActive = "checked";
            		}            		
            	@endphp
	            <label><input type="radio" id="option1" name="status" value="1" {{ $active }}> Active</label>&nbsp;&nbsp;&nbsp; 
	            <label><input type="radio" id="option2" name="status" value="0" {{ $inActive }}> In-Active</label>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="form-group">
	            <label>Offer Pic <span class="required">*</span></label><br/>
	            <span class="text-danger offer-pic-error"></span>
	            <input type="file" name="offer_pic" class="form-control"/>
	            <p>Offer Banner Size Should Be: 600px x 600px</p>
	            <span id="img-tag">
	            	<img src="{{ asset('/public/upload/offer-thumbnail/'.$editOfferInfo->photo) }}" class="img-thumbnail mx-auto d-block view-edit-photo"/>
	            </span>
	            {{-- <span id="img-tag"><img src="'+APP_URL+'/public/upload/offer-thumbnail/'+response.offerInfo.photo+'"/></span> --}}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="form-group">
			    <button type="button" class="btn btn-secondary pull-left" data-dismiss="modal">Close</button> 
			    <button type="submit" class="btn btn-primary pull-right">Update</button>
            </div>
        </div>
    </div>
</form>