<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Information</h5>
                <span style="font-size:12px;margin-top:6px;margin-left:5px">[** All <span style="color:red;">Red</span> Start Sign Data Must Be Fillable.**]</span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form class="form-horizontal" method="POST" action="" id="UpdateRetailer">
                <input type="text" name="update_id" id="update_id"/>
                <input type="text" name="_method" value="PUT"/>
                @csrf
                <div class="modal-body">
                    <div class="col-md-12 retailer-select-h">
                        <div class="row">
                            <div class="col-md-4 mb-2" style="padding: 0px 7px 0px 15px;">
                                <label>Select Category <span class="required">*</span></label>
                                <select class="form-control" data-placeholder="Select Category" style="width: 100%;" name="category_id" required="">
                                    <option value="">Select Category</option>
                                    @if(isset($CategoryList))
                                        @foreach($CategoryList as $row)
                                            <option value="{{ $row->id }}" class="UpdateApiCategoryId{{ $row->name }}">{{ $row->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <div class="col-md-4 mb-2" style="padding:0px">
                                <label>Retailer Name <span class="required">*</span></label>
                                <input type="text" name="retailer_name" class="form-control UpdateApiRetailerName" placeholder="Name"required=""/>
                                <span class="text-danger"><strong id="update-name-error"></strong></span>
                            </div>
                            <div class="col-md-4 mb-2" style="padding:0px 5px">
                                <label>Owner Name <span class="required">*</span></label>
                                <input type="text" name="owner_name" class="form-control UpdateApiRetailerOwnerName" placeholder="Owner Name"required=""/>
                                <span class="text-danger"><strong id="update-owner-name-error"></strong></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label>Police Station</label>
                                <input type="text" name="police_station" class="form-control UpdateApiRetailerPoliceStation" placeholder="Police Station"/>
                            </div>

                            <div class="col-md-6 mb-2">
                                <label>Phone Number <span class="required">*</span></label>
                                <input type="text" name="phone_number" maxlength="11"  minlength="11" class="form-control UpdateApiRetailerPhone Number" placeholder="Phone Number" required=""/>
                                <span class="text-danger"><strong id="update-phone-error"></strong></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <label>Retailer Address <span class="required">*</span></label>
                                <textarea name="retailder_address" class="form-control UpdateApiRetailerAddress" required="" cols="3" rows="2"></textarea>
                                <span class="text-danger"><strong id="update-address-error"></strong></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <label>Distributor Code <span class="required">*</span></label>
                                <div class="input-group">
                                    <input type="text" name="distributor_code" class="form-control UpdateApiRetailerDistributorCode" id="usearch_retailer_dealer_code" placeholder="Search Dealer By Code" required="">
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
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-3 mb-2" style="display: none" id="udAlternetCode">
                                <label>Alternet Code</label>
                                <input type="text" name="distributor_code2" class="form-control UpdateApiRetailerDistributorCode2 udealerAlternetCode" placeholder="Alternet Distributor Code"/>
                            </div>

                            <div class="col-md-3 mb-2" style="display: none" id="udZone">
                                <label>Distributor Zone</label>
                                <input type="text" name="distributor_zone" class="form-control udealerZone" placeholder="Zone"/>
                            </div>

                            <div class="col-md-6 mb-2" style="display: none" id="udName">
                                <label>Distributor Name</label>
                                <input type="text" name="distributor_name" class="form-control udealerName" placeholder="Name"/>
                            </div>
                        </div>
                    </div>
                    <!--------- End  ---->

                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label>Division Name</label>
                                <input type="text" name="division_name" class="form-control UpdateApiRetailerDivisionName" placeholder="Division Name"/>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label>Distric Name</label>
                                <input type="text" name="distric_name" class="form-control UpdateApiRetailerDistric" placeholder="Distric Name"/>
                            </div>
                        </div>
                    </div>

                    <!--------- Start  -------->
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label>Payment Type <span class="required">*</span></label>
                                <div class="col-sm-6" style="padding-left:0px !important">
                                    <label>
                                        <input type="radio" id="umfc" name="payment_type" value="1" onclick="checkPaymentType(1)"> MFC
                                    </label> &nbsp;&nbsp;
                                    <label>
                                        <input type="radio" id="ubank" name="payment_type" value="2" onclick="checkPaymentType(2)"> Bank Account
                                    </label>
                                </div>
                               
                            </div>

                            <div class="col-md-6 mb-2">
                                <label>Status</label>
                                <div class="col-sm-6" style="padding-left:0px !important">
                                    <label><input type="radio" id="option1" name="status" value="1"> Active</label>&nbsp;&nbsp;
                                    <label><input type="radio" id="option2" name="status" value="0"> In-Active</label>
                                </div>
                            </div>
                            
                            <div class="col-md-12">
                                 <span class="paymentNumber">
                                    <div class="agentDiv">
                                        <div class="form-group">
                                            <input type="text" name="agent_name" class="form-control mfc_name" placeholder="Enter Agent Name Ex:Bkash,Nogod,Rocket" required=""/>
                                        </div>

                                        <div class="form-group">
                                            <input type="text" name="payment_number" class="form-control UpdateApiRetailerPaymentNumber mfc_field" onkeypress="return (event.charCode != 8 && event.charCode == 0 || (event.charCode >= 48 && event.charCode <= 57))"  maxlength="11"  minlength="11" required=""/>
                                        </div>
                                    </div>

                                    <div class="bankDiv">
                                        <div class="form-group">
                                            <input type="text" name="bank_name" class="form-control UpdateBankName" placeholder="Enter Bank Name Ex:DBBL,Jamuna Bank" required=""/>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" name="payment_number" class="form-control UpdateApiRetailerPaymentNumber bank_field" placeholder="Bank Payment Number"  minlength="11" required=""/>
                                        </div>
                                    </div>
                                </span>
                            </div>
                            
                        </div>
                    </div>
                    <!--------- End  -------->
                </div>
                <input type="text" id="UpdateApiRetailerPaymentType" disabled="disabled"/>
                <input type="text" id="UpdateApiRetailerPaymentNumber" disabled="disabled"/>
                <input type="text" name="retailer_id" class="UpdateApiRetailerId"/>
                <input type="text" name="zone_id" class="UpdateApiRetailerZoneId"/>
                <input type="text" name="division_id" class="UpdateApiRetailerDivisionId"/>
                <input type="text" name="distric_id" class="UpdateApiRetailerDistricId"/>
                <input type="text" name="thana_id" class=" UpdateApiRetailerThanaID"/>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btnCloseModal" data-dismiss="modal">Close</button> 
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>