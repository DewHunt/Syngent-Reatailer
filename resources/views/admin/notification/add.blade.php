<div class="modal fade" id="AddPushNotificationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Notification</h5>
                <span style="font-size:12px;margin-top:6px;margin-left:5px">[** All <span style="color:red;">Red</span> Start Sign Data Must Be Fillable.**]</span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form class="form-horizontal" method="POST" action="" id="AddPushNotification">
                    @csrf
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="form-group">
                                <label>Title <span class="required">*</span></label>
                                <input type="text" name="title" class="form-control" required=""/>
                                <span class="text-danger"><strong id="title-error"></strong></span>
                            </div>
                        </div>
                    </div>

                    <div class="row" style="display: none;">
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="form-group">
                                <label>Zone <span class="required">*</span></label>
                                <select class="form-control select2" multiple="multiple" data-placeholder="Select Zone" data-dropdown-css-class="select2-purple" name="zone[]">
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
                                <select class="form-control select2" multiple="multiple" data-placeholder="Select Group" id="message_group" name="message_group[]">
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
                                <label>Message <span class="required">*</span></label>
                                <textarea name="message" class="form-control" required="" cols="3" rows="2"></textarea>
                                <span class="text-danger"><strong id="message-error"></strong></span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="form-group">
                                <button type="button" class="btn btn-secondary btnCloseModal" data-dismiss="modal">Close</button> 
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>