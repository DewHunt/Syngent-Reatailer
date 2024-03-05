<form class="form-horizontal" method="POST" action="{{ route('zone.update') }}" id="UpdateZone">
    @csrf
    <input type="hidden" name="update_id" id="update_id" value="{{ $ZoneInfo->id }}" />
    <div class="row">
    	<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
		    <div class="form-group">
		        <label>Zone Name <span class="required">*</span></label>
		        <input type="text" name="zone_name" class="form-control UpdateApiZoneName" placeholder="Zone Name" required="" value="{{ $ZoneInfo->zone_name }}" />
		        <span class="text-danger"><strong id="update-name-error"></strong></span>
		    </div>
    	</div>
    </div>
    <div class="row">
    	<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
		    <label>Status</label>
		    <div class="form-group">
		    	@php
		    		$active = "";
		    		$inActive = "";
		    		if ($ZoneInfo->status == 1) {
		    			$active = "checked";
		    		} else {
		    			$inActive = "checked";
		    		}		    		
		    	@endphp
		        <label><input type="radio" id="option1" name="status" value="1" {{ $active }}>&nbsp;Active</label>&nbsp;&nbsp;&nbsp; 
		        <label><input type="radio" id="option2" name="status" value="0" {{ $inActive }}>&nbsp;In-Active</label>
		    </div>
    	</div>
    </div>

    <div class="row">
    	<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
		    <button type="button" class="btn btn-secondary pull-left" data-dismiss="modal">Close</button> 
		    <button type="submit" class="btn btn-primary pull-right">Update</button>
    	</div>
    </div>
</form>