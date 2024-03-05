@php
	$amPmArray = array('am' => 'AM','pm'=>'PM');
@endphp

<form class="form-horizontal" method="POST" action="{{ route('retailer.save_working_time') }}" id="saveShopWorkingTime">
    @csrf
	<input type="hidden" name="retailer_id" id="retailerId" value="{{ $response->id }}">
    <div class="row">
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
        	<div class="form-group">
	            <label>Start Time<span class="required">*</span></label>
	            <input type="text" class="form-control time startTime" name="start_time" placeholder="hh:mm:ss" required="" value="{{ $response->shop_start_time }}">
        	</div>
        </div>
        <div class="col-xl-2 col-lg-2 col-md-2 col-sm-12 col-12">
        	<div class="form-group">
	            <label>Time Format<span class="required">*</span></label>
	            <select class="form-control" name="start_time_ampm" id="start_time_ampm">
	            	@foreach ($amPmArray as $key => $value)
	            		@php
	            			$select = "";
	            			if ($key == $response->start_time_ampm) {
	            				$select = "selected";
	            			}
	            		@endphp
	            		<option value="{{ $key }}" {{ $select }}>{{ $value }}</option>
	            	@endforeach
	            </select>
        	</div>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
        	<div class="form-group">
	            <label>End Time<span class="required">*</span></label>
	            <input type="text" class="form-control time endTime" name="end_time" placeholder="hh:mm:ss" required="" value="{{ $response->shop_end_time }}">
        	</div>
        </div>
         <div class="col-xl-2 col-lg-2 col-md-2 col-sm-12 col-12">
         	<div class="form-group">
	            <label>Time Format<span class="required">*</span></label>
	            <select class="form-control" name="end_time_ampm" id="end_time_ampm">
	            	@foreach ($amPmArray as $key => $value)
	            		@php
	            			$select = "";
	            			if ($key == $response->end_time_ampm) {
	            				$select = "selected";
	            			}
	            		@endphp
	            		<option value="{{ $key }}" {{ $select }}>{{ $value }}</option>
	            	@endforeach
	            </select>
         	</div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 text-right">
        	<div class="form-group">
				<button type="button" class="btn btn-secondary btnCloseModal" data-dismiss="modal">Close</button> 
				<button type="submit" class="btn btn-primary">Save</button>
        	</div>
        </div>
    </div>
</form>