<form class="form-horizontal" method="POST" action="{{ route('pushNotification.update') }}" id="UpdatePushNotification" >
    @csrf
    <input type="hidden" name="update_id" id="updateId" value="{{ $PushNotificationInfo->id }}" />
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        	<div class="form-group">
	            <label>Title <span class="required">*</span></label>
	            <input type="text" name="title" class="form-control getTitle" required="" value="{{ $PushNotificationInfo->title }}" />
	            <span class="text-danger"><strong id="update-title-error"></strong></span>
        	</div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        	<div class="form-group">
	            <label>Message <span class="required">*</span></label>
	            <textarea name="message" class="form-control getMessage" required="" cols="3" rows="2">{{ $PushNotificationInfo->message }}</textarea>
	            <span class="text-danger"><strong id="update-message-error"></strong></span>
        	</div>
        </div>
    </div>

    <div class="row" style="display: none;">
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
        	<div class="form-group">
	            <label>Zone <span class="required">*</span></label>
	            <select class="form-control select2" multiple="multiple" data-placeholder="Select Zone" data-dropdown-css-class="select2-purple" id="getZone" name="zone">
	                <option value="">Select Zone</option>
	                <option value="all" selected>All</option>
	                @if(isset($zoneList))
		                @foreach($zoneList as $row)
		                	<option value="{{ $row->zone_name }}">{{ $row->zone_name }}</option>
		                @endforeach
	                @endif
	            </select>
        	</div>
        </div>
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
        	<div class="form-group">
	            <label>Message Group <span class="required">*</span></label>
	            <select class="form-control select2" multiple="multiple" data-placeholder="Select Group" id="getMessageGroup" name="message_group[]">
	                <option value="">Select Group</option>
	                <option value="all" selected>All</option>
	                <option value="retailer">Retailer</option>
	            </select>
        	</div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        	<div class="form-group">
			    <button type="button" class="btn btn-secondary btnCloseModal" data-dismiss="modal">Close</button> 
			    <button type="submit" class="btn btn-primary">Update</button>
        	</div>
        </div>
    </div>
</form>