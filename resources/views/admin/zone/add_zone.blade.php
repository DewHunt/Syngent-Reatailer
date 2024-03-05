<div class="modal fade" id="AddZoneModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Zone</h5>

                <span style="font-size:12px;margin-top:6px;margin-left:5px">[** All <span style="color:red;">Red</span> Start Sign Data Must Be Fillable.**]</span>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form class="form-horizontal" method="POST" action="" id="AddZone">
                    @csrf
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="form-group">
                                <label>Zone Name <span class="required">*</span></label>
                                <input type="text" name="zone_name" class="form-control ApiZoneName" placeholder="Zone Name"required=""/>
                                <span class="text-danger"><strong id="name-error"></strong></span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <label>Status</label>
                            <div class="form-group">
                                <label><input type="radio" name="status" checked="checked" value="1">&nbsp;Active</label>&nbsp;&nbsp;&nbsp; 
                                <label><input type="radio" name="status" value="0">&nbsp;In-Active</label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="form-group">
                                <button type="button" class="btn btn-secondary pull-left" data-dismiss="modal">Close</button> 
                                <button type="submit" class="btn btn-primary pull-right">Submit</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>