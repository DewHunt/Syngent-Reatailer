<div class="modal fade" id="AddBannerModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Banner</h5>
                <span style="font-size:12px;margin-top:6px;margin-left:5px">[** All <span style="color:red;">Red</span> Start Sign Data Must Be Fillable.**]</span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>

            <div class="modal-body">
                <form class="form-horizontal" method="POST" action="" id="AddBanner" enctype="multipart/form-data">
                    @csrf
                    <div class="row" style="display: none;">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="form-group">
                                <label>Group <span class="required">*</span></label>
                                <select class="form-control"  style="width: 100%;" name="banner_for" required="">
                                    <option value="">Select</option>
                                    <option value="all" selected="selected">All</option>
                                    <option value="retailer">Retailer</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <label>Status <span class="required">*</span></label>
                            <div class="form-group">
                                <label><input type="radio" name="status" checked="checked" value="1"> Active</label>  &nbsp;&nbsp; 
                                <label><input type="radio" name="status" value="0"> In-Active</label>
                                <span class="text-danger status-error"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="form-group">
                                <label>Banner Pic <span class="required">*</span></label><br/>
                                <span class="text-danger banner-error"></span>
                                <input type="file" name="banner_pic" class="form-control" required=""/>
                                <p style="color: red;">Banner Size Should Be: 380px x 150px</p>
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