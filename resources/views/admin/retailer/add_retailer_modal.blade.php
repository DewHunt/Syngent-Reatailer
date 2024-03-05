<div class="modal fade" id="AddRetailerModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Retailer</h5>
                <span style="font-size:12px;margin-top:6px;margin-left:5px">
                    [** All <span style="color:red;">Red</span> Start Sign Data Must Be Fillable.**]
                </span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form class="form-horizontal" method="POST" action="" id="AddRetailer">
                @csrf
                <input type="hidden" name="api_payment_type" class="ApiRetailerPaymentType"/>
                <input type="hidden" name="api_payment_number" class="ApiRetailerPaymentNumber"/>
                <input type="hidden" name="retailer_id" class="ApiRetailerId"/>
                <input type="hidden" name="zone_id" class="ApiRetailerZoneId"/>
                <input type="hidden" name="division_id" class="ApiRetailerDivisionId"/>
                <input type="hidden" name="distric_id" class="ApiRetailerDistricId"/>
                <input type="hidden" name="thana_id" class=" ApiRetailerThanaID"/>
                <div class="modal-body">
                    <div class="row" style="display: none;">
                        <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-12">
                            <div class="form-group">
                                <input type="text" class="form-control" id="search_retailer_id" placeholder="Search By Retailer ID" oninput="SearchRetailerDisable()">
                            </div>
                        </div>

                        <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-12">
                            <div class="form-group">
                                <input type="text" class="form-control Number" id="search_retailer_mobile" placeholder="Search By Phone" maxlength="11" minlength="11" oninput="SearchRetailerMobileDisable()">
                            </div>
                        </div>

                        <div class="col-xl-2 col-lg-2 col-md-2 col-sm-12 col-12">
                            <div class="form-group">
                                <button type="button" class="btn btn-primary btn-block" id="search_retailer_button">Search</button>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12" style="display: none;">
                            <div class="form-group">
                                <label>Select Category <span class="required">*</span></label>
                                <select class="form-control" data-placeholder="Select Category" style="width: 100%;" name="category_id" required="">
                                    <option value="">Select Category</option>
                                    @if(isset($CategoryList))
                                        @foreach($CategoryList as $row)
                                            @php
                                                $select = "";
                                                if ($row->id == 1) {
                                                    $select = "selected";
                                                }
                                            @endphp
                                            <option value="{{ $row->id }}" {{ $select }}>{{ $row->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="form-group">
                                <label>Retailer Name <span class="required">*</span></label>
                                <input type="text" name="retailer_name" class="form-control ApiRetailerName" placeholder="Name"required=""/>
                                <span class="text-danger"><strong id="name-error"></strong></span>
                            </div>
                        </div>

                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="form-group">
                                <label>Owner Name <span class="required">*</span></label>
                                <input type="text" name="owner_name" class="form-control ApiRetailerOwnerName" placeholder="Owner Name"required=""/>
                                <span class="text-danger"><strong id="owner-name-error"></strong></span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="form-group">
                                <label>Police Station</label>
                                <input type="text" name="police_station" class="form-control ApiRetailerPoliceStation" placeholder="Police Station" />
                            </div>
                        </div>
                        
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="form-group">
                                <label>Phone Number <span class="required">*</span></label>
                                <input type="text" name="phone_number" maxlength="11"  minlength="11" class="form-control ApiRetailerPhone Number" placeholder="Phone Number" required=""/>
                                <span class="text-danger"><strong id="phone-error"></strong></span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="form-group">
                                <label>Retailer Address <span class="required">*</span></label>
                                <textarea name="retailder_address" class="form-control ApiRetailerAddress" required="" cols="3" rows="2"></textarea>
                                <span class="text-danger"><strong id="address-error"></strong></span>
                            </div>
                        </div>
                    </div>

                    <div class="row" style="display: none;">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="form-group">
                                <label>Distributor Code <span class="required">*</span></label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="search_retailer_dealer_code" placeholder="Search Dealer By Code" required="">
                                    <div class="input-group-append">
                                        <button class="btn  btn-primary btn-block" type="button" id="search_retailer_dealer_button">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row" style="display: none;">
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="form-group">
                                <label>Distributor Code</label>
                                <input type="text" class="form-control ApiRetailerDistributorCode" placeholder="Distributor Code" disabled=""/>
                                <input type="hidden" class="dcode" name="distributor_code"/>
                            </div>
                        </div>
                        
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="form-group">
                                <label>Distributor Alternet Code</label>
                                <input type="text" class="form-control ApiRetailerDistributorCode2" placeholder="Alternet Code" disabled="" />
                                <input type="hidden" class="dacode" name="distributor_code2"/>
                            </div>
                        </div>
                    </div>

                    <div class="row" style="display: none;">
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="form-group">
                                <label>Distributor Name</label>
                                <input type="text" name="distributor_name" class="form-control dealerName" placeholder="Name"/>
                            </div>
                        </div>
                        
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="form-group">
                                <label>Distributor Zone</label>
                                <input type="text" name="distributor_zone" class="form-control dealerZone" placeholder="Zone"/>
                            </div>
                        </div>
                    </div>

                    <div class="row" style="display: none;">
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="form-group">
                                <label>Division Name</label>
                                <input type="text" name="division_name" class="form-control ApiRetailerDivisionName" placeholder="Division Name"/>
                            </div>
                        </div>
                        
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="form-group">
                                <label>Distric Name</label>
                                <input type="text" name="distric_name" class="form-control ApiRetailerDistric" placeholder="Distric Name"/>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12" style="display: none;">
                            <label>Payment Type <span class="required">*</span></label>
                            <div class="form-group">
                                <label><input type="radio" id="mfc" class="payment_type" name="payment_type" value="1"> MFC</label>
                                &nbsp;&nbsp;
                                <label><input type="radio" id="bank" class="payment_type" name="payment_type" value="2"> Bank Account</label>
                                <span class="paymentNumber"></span>
                            </div>
                        </div>
                        
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="form-group">
                                <label>Status</label>
                                <div class="col-sm-6" style="padding-left:0px !important">
                                    <label><input type="radio" name="status" checked="checked" value="1"> Active</label>
                                    &nbsp;&nbsp; 
                                    <label><input type="radio" name="status" value="0"> In-Active</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <span class="paymentNumber" style="display: none;">
                        <div class="agentDiv">
                            <div class="row">
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                                    <div class="form-group">
                                        <input type="text" name="agent_name" class="form-control mfc_name" placeholder="Enter Agent Name Ex:Bkash,Nogod,Rocket" value=""/>
                                    </div>
                                </div>

                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                                    <div class="form-group">
                                        <input type="text" name="payment_number" class="form-control UpdateApiRetailerPaymentNumber mfc_field" onkeypress="return (event.charCode != 8 && event.charCode == 0 || (event.charCode >= 48 && event.charCode <= 57))" maxlength="11" minlength="11" required="" value=""/>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bankDiv">
                            <div class="row">
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                                    <div class="form-group">
                                        <input type="text" name="bank_name" class="form-control UpdateBankName" placeholder="Enter Bank Name Ex:DBBL,Jamuna Bank" value=""/>
                                    </div>
                                </div>

                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                                    <div class="form-group">
                                        <input type="text" name="payment_number" class="form-control UpdateApiRetailerPaymentNumber bank_field" placeholder="Bank Payment Number"  minlength="11" required="" value="" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </span>

                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="form-group">
                                <button type="button" class="btn btn-secondary btnCloseModal" data-dismiss="modal">Close</button> 
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>