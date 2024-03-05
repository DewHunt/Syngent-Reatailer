<div class="modal fade" id="AddOfferModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Promo Offer</h5>
                <span style="font-size:12px;margin-top:6px;margin-left:5px">[** All <span style="color:red;">Red</span> Start Sign Data Must Be Fillable.**]</span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>

            <div class="modal-body">
                <form class="form-horizontal" method="POST" action="" id="AddOffer" enctype="multipart/form-data">
                    @csrf
                    <div class="row" style="display: none;">
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="form-group">
                                <label>Offer For <span class="required">*</span></label>
                                <select class="form-control select2" style="width: 100%;" name="offer_for" required="">
                                    <option value="">Select</option>
                                    <option value="all" selected="selected">All</option>
                                    <option value="retailer">Retailer</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="form-group">
                                <label>Zone</label>
                                <select class="form-control select2" multiple="multiple" data-placeholder="Select a Incentive Zone" data-dropdown-css-class="select2-purple" style="width: 100%;" id="zone" name="zone[]">
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
                    </div>

                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
                            <div class="form-group">
                                <label>Start Date <span class="required">*</span></label>
                                <input type="text" name="sdate" class="form-control datepicker" required=""/>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
                            <div class="form-group">
                                <label>End Date <span class="required">*</span></label>
                                <input type="text" name="edate" class="form-control datepicker Number" required=""/>
                            </div>
                        </div>

                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
                            <label>Status</label>
                            <div class="form-group">
                                <label><input type="radio" name="status" checked="checked" value="1"> Active</label>&nbsp;&nbsp;&nbsp; 
                                <label><input type="radio" name="status" value="0"> In-Active</label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="form-group">
                                <label>Offer Pic <span class="required">*</span></label><br/>
                                <span class="text-danger offer-pic-error"></span>
                                <input type="file" name="offer_pic" class="form-control" required=""/>
                                <p style="color: red;">Offer Banner Size Should Be: 600px X 600px</p>
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