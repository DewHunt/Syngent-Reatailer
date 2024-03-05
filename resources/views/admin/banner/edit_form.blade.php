<form class="form-horizontal" method="POST" action="{{ route('banner.update') }}" id="UpdateBanner" enctype="multipart/form-data">
    <input type="hidden" name="update_id" id="update_id" value="{{ $editBannerInfo->id }}" />
    @csrf
    <div class="row" style="display: none;">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="form-group">
	            <label>Group<span class="required">*</span></label>
	            <select class="form-control" style="width: 100%;" name="banner_for" required="">
	                <option value="">Select</option>
	                <option value="all" selected>All</option>
	                <option value="retailer" class="uretailer">Retailer</option>
	            </select>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <label>Status <span class="required">*</span></label>
            <div class="form-group">
            	@php
            		$active = "";
            		$inActive = "";
            		if ($editBannerInfo->status == 1) {
            			$active = "checked";
            		} else {
            			$inActive = "checked";
            		}
            	@endphp
	            <label><input type="radio" id="option1" name="status" value="1" {{ $active }}> Active</label>&nbsp; &nbsp;&nbsp; 
	            <label><input type="radio" id="option2" name="status" value="0" {{ $inActive }}> In-Active</label>
	            <span class="text-danger status-error"></span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="form-group">
	            <label>Banner Pic <span class="required">*</span></label><br/>
	            <span class="text-danger banner-error"></span>
	            <input type="file" name="banner_pic" class="form-control"/>
	            <p style="color: red;">Banner Size Should Be: 380px x 150px</p>
	            <span id="img-tag">
                    <img src="{{ asset($editBannerInfo->image_path) }}" class="img-thumbnail mx-auto d-block view-edit-photo"/>
                </span>
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