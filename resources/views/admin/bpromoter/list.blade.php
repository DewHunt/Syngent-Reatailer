@extends('admin.master.master')
@section('content')
<style>
    .cp {
        padding:5px
    }
    .csearch {
        width:285px;
    }
    /* 
      ##Device = Desktops
      ##Screen = 1281px to higher resolution desktops
    */
    @media (min-width: 1281px) {
        .main-content .btn-group-sm>.btn, .btn-sm {
            font-size: 0.90rem !important;
            padding: 0.3rem 1rem !important;
            width: 100%;
            margin: 2px;
        }
        .beforeAddBtn{
            padding: 0px 7px;
        }
        .newAddBtn{
            margin-left: 5px;
            width: 28%;
            margin: 0px;
            padding-left: 0px !important;
            padding-right: 0px !important;
            margin-left: 10px;
        }
        .csearch {
            width: 30%;
        }
        .common-row-padding {
            padding-right: 10px;
        }
        .commonbtnwidth {
            width:100%;
        }
        /*.btn-sm {
            font-size: 1rem !important;
            padding: 0rem 1rem !important;
            margin: 5px 0 5px 0;
            width: 170px;
        }*/
    }           
    /* Portrait and Landscape */
    @media only screen 
    and (min-device-width: 320px) 
    and (max-device-width: 568px)
    and (-webkit-min-device-pixel-ratio: 2) {
        .retailer-select-h select.form-control:not([size]):not([multiple]) {
            height: calc(3.4rem + 2px) !important;
        }
        .input-group-append .btn {
            padding: 1rem 0.75rem !important;
        }
        .cp {
            padding:5px
        }
        .csearch {
            width:300px;
        }
        .btn-sm {
            font-size: 1.7rem !important;
            padding: 0rem 1rem !important;
            margin: 5px 10px 5px 0;
            width: 285px;
        }
        .dataTables_wrapper .dataTables_filter input {
            height: 45px;
        }
        .btn-group > .btn {
            padding: 7px 25px;
            font-size: 20px;
        }
    }
    @media only screen 
    and (min-device-width: 375px) 
    and (max-device-width: 812px) 
    and (-webkit-min-device-pixel-ratio: 3){
        .retailer-select-h select.form-control:not([size]):not([multiple]) {
            height: calc(3.4rem + 2px) !important;
        }
        .input-group-append .btn {
            padding: 1rem 0.75rem !important;
        }
        .cp {
            padding:5px
        }
        .csearch {
            width:300px;
        }
        .btn-sm {
            font-size: 1.7rem !important;
            padding: 0rem 1rem !important;
            margin: 5px 10px 5px 0;
            width: 285px;
        }
        .dataTables_wrapper .dataTables_filter input {
            height: 45px;
        }
        .btn-group > .btn {
            padding: 7px 25px;
            font-size: 20px;
        }
    }
    @media (min-width: 768px) and (max-width: 1024px) {
        .retailer-select-h select.form-control:not([size]):not([multiple]) {
                height: calc(3.4rem + 2px) !important;
            }
            .input-group-append .btn {
                padding: 1rem 0.75rem !important;
            }
        .cp {
            padding:5px
        }
        .csearch {
            width:300px;
        }
        .input-group-append .btn {
            padding: 1rem 0.75rem !important;
        }
        .commonbtnwidth {
            width: 200px;
            height: 60px;
        }
        .btn-sm {
            font-size: 1.7rem !important;
            padding: 0rem 1rem !important;
            margin: 5px 10px 5px 0;
            width: 277px;
        }
        /*
        .dataTables_wrapper .dataTables_filter input {
            height: 45px;
        }
        .btn-group > .btn {
            padding: 7px 25px;
            font-size: 20px;
        }*/
    }
</style>
{{-- 
<div class="col-md-12">
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-10 beforeAddBtn">
            <!--<button  type="button" class="btn btn-primary pull-right btn-sm newAddBtn" onclick="AddAllPromoterByApi()">Refresh</button>
-->
            <button  type="button" class="btn btn-primary pull-right btn-sm newAddBtn" data-toggle="modal" data-target="#AddBPromoterModal">Add BP</button>
            <br/>
        </div>
    </div>
</div> 
--}}

<div class="col-md-12 cp">
    <div class="row">
        <div class="col-md-6">
            <h4 class="c-grey-900">Brand Promoter</h4>

            {{-- <button id="btnPdf" class="btn btn-primary cur-p btn-xs exportbtn">TO PDF</button>
            <button class="btn btn-info cur-p btn-xs exportbtn" onclick="ExportToExcel('xlsx')">TO Excel</button> --}}
        </div>
        <div class="col-md-6 common-row-padding">
            <button  type="button" class="btn btn-primary pull-right btn-sm newAddBtn" onclick="AddAllPromoterByApi()">Refresh</button>

            <button  type="button" class="btn btn-primary pull-right btn-sm newAddBtn" data-toggle="modal" data-target="#AddBPromoterModal">Add BP</button>
            <br/>
            {{-- <div class="form-group top-margin">
                <input type="text" name="serach" id="serach" class="form-control pull-right csearch"/>
            </div> --}}
        </div>
    </div>
</div>

{{-- 
<div class="col-md-12 cp">
    <div class="row">
        <div class="col-md-6">
            <h4 class="c-grey-900 mB-20">Brand Promoter</h4>
        </div>
        <div class="col-md-6 common-row-padding">
            <div class="form-group top-margin">

                <button  type="button" class="btn btn-primary pull-right btn-sm newAddBtn" data-toggle="modal" data-target="#AddBPromoterModal">Add BP</button> 
            <button  type="button" class="btn btn-info pull-right btn-sm" onclick="AddAllPromoterByApi()" disabled="" style="display: none">Add to BP By Api</button>


                <input type="text" name="serach" id="serach" class="form-control pull-right csearch"/>
            </div>
        </div>
    </div>
</div> 
--}}


<div id="tag_container" class="table-responsive">
    <table id="example3" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th class="sorting" data-sorting_type="asc" data-column_name="id" style="cursor: pointer;width:5%;">Sl.</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="bp_name" style="cursor: pointer;width:15%;">Category</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="bp_name" style="cursor: pointer;width:15%;">Promoter Name</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="bp_phone" style="cursor: pointer;width:10%;">Promoter Phone</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="distributor_name" style="cursor: pointer;width:30%;">Dealer Name</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="distributor_code" style="cursor: pointer;width:30%;">Dealer Code</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="retailer_name" style="cursor: pointer;width:30%;">Retailer Name</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="retailer_phone" style="cursor: pointer;width:30%;">Retailer Phone</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="status" style="cursor: pointer;">Status</th>
                <th style="width:10%;">Action</th>
            </tr>
        </thead>
        <tbody>
            @include('admin.bpromoter.result_data')
        </tbody>
    </table>
    {{-- <input type="hidden" name="hidden_page" id="hidden_page" value="1" />
    <input type="hidden" name="hidden_column_name" id="hidden_column_name" value="id" />
    <input type="hidden" name="hidden_sort_type" id="hidden_sort_type" value="asc" /> --}}
</div>

<!--Add New Modal Start -->
<div class="modal fade" id="AddBPromoterModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Brand Promoter</h5>
                <span style="font-size:12px;margin-top:6px;margin-left:5px">
                    [** All <span style="color:red;">Red</span> Start Sign Data Must Be Fillable.**]
                </span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form class="form-horizontal" method="POST" action="" id="AddBPromoter">
                @csrf
                <div class="modal-body">
                    {{-- <div class="form-row" id="ApiSearchDiv">
                        <div class="form-group col-md-10">
                            <input type="text" class="form-control Number" id="search_bpromoter_phone" maxlength="11" placeholder="Search BP By Phone">
                        </div>

                        <div class="form-group col-md-2">
                            <button type="button" class="btn btn-primary btn-block" id="search_bpromoter_button">Search</button>
                        </div>
                    </div> --}}

                    <div class="form-group"  style="display: none;">
                        <label>Brand Promoter ID</label>
                        <input type="text" name="bp_id" class="form-control ApiBPromoterId" placeholder="Enter Brand Promoter ID"/>
                    </div>

                    <div class="form-group"  style="display: none;">
                        <label>Retailer ID</label>
                        <input type="text" name="retailer_id" class="form-control ApiBPromoterRetailerId" placeholder="Enter Retailer ID"/>
                    </div>

                    <div class="col-md-12 retailer-select-h">
                        <div class="row">
                            <div class="col-md-4 mb-2" style="padding: 0px 7px 0px 15px;">
                                <label>Select Category <span class="required">*</span></label>
                                <select class="form-control" data-placeholder="Select Category" style="width: 100%;" name="bp_category" required="">
                                    <option value="">Select Category</option>
                                    @if(isset($CategoryList))
                                        @foreach($CategoryList as $row)
                                            <option value="{{ $row->id }}">{{ $row->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <div class="col-md-4 mb-2" style="padding:0px">
                                <label>BP Name <span class="required">*</span></label>
                                <input type="text" name="bp_name" class="form-control ApiBPromoterName" placeholder="Name"required=""/>
                                <span class="text-danger">
                                    <strong id="name-error"></strong>
                                </span>
                            </div>
                            
                            <div class="col-md-4 mb-2" style="padding: 0px 15px 0px 2px;">
                                <label>Phone Number <span class="required">*</span></label>
                                <input type="text" name="bp_phone" maxlength="11"  minlength="11" class="form-control ApiBPromoterPhone Number" placeholder="Phone Number" required=""/>
                                <span class="text-danger">
                                    <strong id="phone-error"></strong>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12 mb-2" style="margin-top:15px">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="search_retailer_mobile" placeholder="Search Retailer By Phone">
                                    <div class="input-group-append">
                                        <button class="btn  btn-primary btn-block" type="button" id="search_retailer_button">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label>Retailer Name <span class="required">*</span></label>
                                <input type="text" name="retailer_name" class="form-control ApiRetailerName" placeholder="Name"required=""/>
                                <span class="text-danger">
                                    <strong id="name-error"></strong>
                                </span>
                            </div>

                            <div class="col-md-6 mb-2">
                                <label>Owner Name </label>
                                <input type="text" name="owner_name" class="form-control ApiRetailerOwnerName" placeholder="Owner Name"/>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label>Police Station</label>
                                <input type="text" name="police_station" class="form-control ApiRetailerPoliceStation" placeholder="Police Station"/>
                            </div>

                            <div class="col-md-6 mb-2">
                                <label>Phone Number <span class="required">*</span></label>
                                <input type="text" name="retailer_phone_number" maxlength="11"  minlength="11" class="form-control ApiRetailerPhone Number" placeholder="Phone Number" required=""/>
                                <span class="text-danger">
                                    <strong id="phone-error"></strong>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <label>Retailer Address <span class="required">*</span></label>
                                <textarea name="retailder_address" class="form-control ApiRetailerAddress" required="" cols="3" rows="2"></textarea>
                                <span class="text-danger">
                                    <strong id="address-error"></strong>
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- 
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12 mb-2">
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
                    --}}

                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6 mb-2"  id="dCode">
                                <label>Distributor Code <span class="required">*</span></label>
                                <input type="text" name="distributor_code" class="form-control dealerCode ApiRetailerDistributorCode" placeholder="Distributor Code" required=""/>
                            </div>
                            <div class="col-md-6 mb-2" id="dAlternetCode">
                                <label>Distributor Alternate Code</label>
                                <input type="text" name="distributor_code2" class="form-control dealerAlternetCode ApiRetailerDistributorCode2" placeholder="Alternate Code"/>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6 mb-2" id="dName">
                                <label>Distributor Name <span class="required">*</span></label>
                                <input type="text" name="distributor_name" class="form-control dealerName" placeholder="Name" required=""/>
                            </div>
                            <div class="col-md-6 mb-2"  id="dZone">
                                <label>Distributor Zone <span class="required">*</span></label>
                                <input type="text" name="distributor_zone" class="form-control dealerZone" placeholder="Zone" required=""/>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label>Division Name</label>
                                <input type="text" name="division_name" class="form-control ApiRetailerDivisionName" placeholder="Division Name"/>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label>District Name</label>
                                <input type="text" name="distric_name" class="form-control ApiRetailerDistric" placeholder="District Name"/>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label>Payment Type</label>
                                <div class="col-sm-6" style="padding-left:0px !important">
                                    <label>
                                        <input type="radio" id="mfc" name="payment_type" value="1" onclick="checkPaymentType(1)"> MFC
                                    </label> &nbsp;&nbsp;
                                    <label>
                                        <input type="radio" id="bank" name="payment_type" value="2" onclick="checkPaymentType(2)"> Bank Account
                                    </label>
                                </div>
                                <span class="paymentNumber"></span>
                            </div>

                            <div class="col-md-6 mb-2">
                                <label>Status</label>
                                <div class="col-sm-6" style="padding-left:0px !important">
                                    <label>
                                        <input type="radio" name="status" checked="checked" value="1"> Active
                                    </label>  &nbsp;&nbsp; 
                                    <label>
                                        <input type="radio" name="status" value="0"> In-Active
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="api_payment_type" class="ApiRetailerPaymentType"/>
                <input type="hidden" name="api_payment_number" class="ApiRetailerPaymentNumber"/>
                <input type="hidden" name="retailer_id" class="ApiRetailerId"/>
                <input type="hidden" name="zone_id" class="ApiRetailerZoneId"/>
                <input type="hidden" name="division_id" class="ApiRetailerDivisionId"/>
                <input type="hidden" name="distric_id" class="ApiRetailerDistricId"/>
                <input type="hidden" name="thana_id" class=" ApiRetailerThanaID"/>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btnCloseModal" data-dismiss="modal">Close</button> 
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--Add New Modal End -->

<!--Edit & Update Modal Start -->
<div class="modal fade" id="editBPromoterModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Brand Promoter Information</h5>

                <span style="font-size:12px;margin-top:6px;margin-left:5px">[** All <span style="color:red;">red</span> starred fields are required.**]</span>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form class="form-horizontal" method="POST" action="" id="UpdateBPromoter">
                <input type="hidden" name="update_id" id="update_id"/>
                <input type="hidden" name="_method" value="PUT"/>
                @csrf
                <div class="modal-body">
                    <div class="col-md-12 retailer-select-h">
                        <div class="row">
                            <div class="col-md-4 mb-2" style="padding: 0px 7px 0px 15px;">
                                <label>Select Category <span class="required">*</span></label>
                                <select class="form-control" data-placeholder="Select Category" style="width: 100%;" name="bp_category" required="">
                                    <option value="">Select Category</option>
                                    @if(isset($CategoryList))
                                    @foreach($CategoryList as $row)
                                    <option value="{{ $row->id }}" class="categoryName_{{ $row->name }}">{{ $row->name }}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-4 mb-2" style="padding:0px">
                                <label>BP Name <span class="required">*</span></label>
                                <input type="text" name="bp_name" class="form-control  UpdateApiBPromoterName" placeholder="Enter Brand Promoter Name" required=""/>
                                <span class="text-danger">
                                    <strong id="update-name-error"></strong>
                                </span>
                            </div>
                            <div class="col-md-4 mb-2" style="padding: 0px 15px 0px 2px;">
                                <label>BP Phone <span class="required">*</span></label>
                                <input type="text" name="bp_phone" class="form-control  UpdateApiBPromoterPhone Number" placeholder="Enter Brand Promoter Phone Number" maxlength="11"  minlength="11" required=""/>
                                <span class="text-danger">
                                    <strong id="update-phone-error"></strong>
                                </span>
                            </div>
                        </div>
                    </div>


                    <div class="col-md-12 missingRetailerSearchButton" style="display:block">
                        <div class="row">
                            <div class="col-md-12 mb-2" style="margin-top:15px">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="update_search_retailer_mobile" placeholder="Search Retailer By Phone">
                                    <div class="input-group-append">
                                        <button class="btn  btn-primary btn-block" type="button" id="update_search_retailer_button">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label>Retailer Name <span class="required">*</span></label>
                                <input type="text" name="retailer_name" class="form-control ApiRetailerName UpdateApiRetailerName" placeholder="Name"required=""/>
                                <span class="text-danger">
                                    <strong id="update-name-error"></strong>
                                </span>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label>Owner Name</label>
                                <input type="text" name="owner_name" class="form-control ApiRetailerOwnerName UpdateApiRetailerOwnerName" placeholder="Owner Name"/>

                                <span class="text-danger">
                                    <strong id="update-owner-name-error"></strong>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label>Police Station</label>
                                <input type="text" name="police_station" class="form-control ApiRetailerPoliceStation UpdateApiRetailerPoliceStation" placeholder="Police Station"/>
                            </div>

                            <div class="col-md-6 mb-2">
                                <label>Retailer Phone Number <span class="required">*</span></label>
                                <input type="text" name="retailer_phone_number" maxlength="11"  minlength="11" class="form-control ApiRetailerPhone UpdateApiRetailerPhone Number" placeholder="Phone Number" required=""/>
                                <span class="text-danger">
                                    <strong id="update-phone-error"></strong>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <label>Retailer Address <span class="required">*</span></label>
                                <textarea name="retailder_address" class="form-control ApiRetailerAddress UpdateApiRetailerAddress" required="" cols="3" rows="2"></textarea>
                                <span class="text-danger">
                                    <strong id="update-address-error"></strong>
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- 
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <label>Distributor Code <span class="required">*</span></label>
                                <div class="input-group">
                                    <input type="text" name="distributor_code" class="form-control UpdateApiRetailerDistributorCode" id="usearch_retailer_dealer_code" placeholder="Search Dealer By Code" required="">
                                    <div class="input-group-append">
                                        <button class="btn  btn-primary" type="button" id="usearch_retailer_dealer_button"><i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> 
                    --}}
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6 mb-2" id="udAlternetCode">
                            <label>Distributor Code <span class="required">*</span></label>
                                <input type="text" name="distributor_code" class="form-control UpdateApiRetailerDistributorCode" placeholder="Distributor Code" required=""/>
                            </div>

                            <div class="col-md-6 mb-2" id="udAlternetCode">
                                <label>Alternate Code</label>
                                <input type="text" name="distributor_code2" class="form-control UpdateApiRetailerDistributorCode2 udealerAlternetCode" placeholder="Alternet Code"/>
                            </div>

                            <div class="col-md-6 mb-2" id="udZone">
                                <label>Distributor Zone <span class="required">*</span></label>
                                <input type="text" name="distributor_zone" class="form-control udealerZone" placeholder="Zone" required=""/>
                            </div>

                            <div class="col-md-6 mb-2" id="udName">
                                <label>Distributor Name <span class="required">*</span></label>
                                <input type="text" name="distributor_name" class="form-control udealerName" placeholder="Name" required=""/>
                            </div>

                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label>Division Name</label>
                                <input type="text" name="division_name" class="form-control UpdateApiRetailerDivisionName" placeholder="Division Name"/>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label>District Name</label>
                                <input type="text" name="distric_name" class="form-control UpdateApiRetailerDistric" placeholder="District Name"/>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label>Payment Type </label>
                                <div class="col-sm-6" style="padding-left:0px !important">
                                    <label>
                                        <input type="radio" id="umfc" name="payment_type" value="1" onclick="checkPaymentType(1)"> MFC
                                    </label> &nbsp;&nbsp;
                                    <label>
                                        <input type="radio" id="ubank" name="payment_type" value="2" onclick="checkPaymentType(2)"> Bank Account
                                    </label>
                                </div>
                                <span class="paymentNumber">
                                    <div class="agentDiv">
                                        <div class="form-group">
                                            <input type="text" name="agent_name" class="form-control mfc_name" placeholder="Enter Agent Name Ex:Bkash,Nogod,Rocket"/>
                                        </div>

                                        <div class="form-group">
                                            <input type="text" name="payment_number" class="form-control UpdateApiRetailerPaymentNumber mfc_field" onkeypress="return (event.charCode != 8 && event.charCode == 0 || (event.charCode >= 48 && event.charCode <= 57))"  maxlength="11"  minlength="11"/>
                                        </div>
                                    </div>

                                    <div class="bankDiv">
                                        <div class="form-group">
                                            <input type="text" name="bank_name" class="form-control UpdateBankName" placeholder="Enter Bank Name Ex:DBBL,Jamuna Bank"/>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" name="payment_number" class="form-control UpdateApiRetailerPaymentNumber bank_field" placeholder="Bank Payment Number"  minlength="11"/>
                                        </div>
                                    </div>
                                </span>
                            </div>

                            <div class="col-md-6 mb-2">
                                <label>Status</label>
                                <div class="col-sm-6" style="padding-left:0px !important">
                                    <label>
                                        <input type="radio" id="option1" name="status" value="1"> Active
                                    </label>  &nbsp;&nbsp; 
                                    <label>
                                        <input type="radio" id="option2" name="status" value="0"> In-Active
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" id="UpdateApiRetailerPaymentType" disabled="disabled"/>
                <input type="hidden" id="UpdateApiRetailerPaymentNumber" disabled="disabled"/>
                <input type="hidden" name="retailer_id" class="UpdateApiRetailerId"/>
                <input type="hidden" name="zone_id" class="UpdateApiRetailerZoneId"/>
                <input type="hidden" name="division_id" class="UpdateApiRetailerDivisionId"/>
                <input type="hidden" name="distric_id" class="UpdateApiRetailerDistricId"/>
                <input type="hidden" name="thana_id" class=" UpdateApiRetailerThanaID"/>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btnCloseModal" data-dismiss="modal">Close</button> 
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--Edit & Update Modal End -->

<!--Password Modal Start -->
<div class="modal fade" id="bpPasswordSetModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Brand Promoter New Password Set</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form class="form-horizontal" method="POST" action="" id="bpPasswordUpdate">
                <input type="text" name="password_update_bp_id" id="password_update_bp_id"/>
                @csrf
                <div class="modal-body">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label>New Password <span class="required">*</span></label>
                                <input type="password" name="password" class="form-control"required autocomplete="new-password"/>
                                <span class="text-danger" id="error_field">
                                    <strong id="update-password-error"></strong>
                                </span>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label>Confirm Password <span class="required">*</span></label>
                                <input type="password" id="password-confirm" name="confirm_password" required autocomplete="new-password" class="form-control"/>
                                <span class="text-danger" id="error_field">
                                    <strong id="update-confirm-password-error"></strong>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btnCloseModal" data-dismiss="modal">Close</button> 
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--Password Modal End -->

@section('page-scripts')
<script type="text/javascript">
//Brand Promoter Information Modal Status Update Option 
jQuery('.promoter-toggle-class').change(function(e) {
    e.preventDefault();
    var status = jQuery(this).prop('checked') == true ? 1 : 0; 
    var PromoterId = jQuery(this).data('id');
    var url = "bpromoter.status"+"/"+PromoterId;
    jQuery.ajax({
        url:url,
        type:"GET",
        dataType:'JSON',
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function() {
            Notiflix.Loading.Arrows('Data Processing');
        },
        success:function(response) {
            Notiflix.Loading.Remove(300);
            if(response.success) {
                Notiflix.Notify.Success( 'Data Update Successfull' );
            }
        
            if(response.error) {
                Notiflix.Notify.Failure( 'Data Update Failed' );
            }
        }
    });
});
// Get  Data AS MySql View Page   
function getPromoterData(){
    var query       = $('#serach').val();
    var column_name = $('#hidden_column_name').val();
    var sort_type   = $('#hidden_sort_type').val();
    var page        = $('#hidden_page').val();
    //var url = "bpromoter";
    jQuery.ajax({
    //url:url,
    url:"?page="+page+"&sortby="+column_name+"&sorttype="+sort_type+"&query="+query,
    type:"GET",
    dataType:"HTMl",
        success:function(response) {
            jQuery('.loading').hide();
            setTimeout(function(){// wait for 5 secs(2)
            window.location.reload(); // then reload the page.(3)
            }, 500);
        },
    });
}
// Add New Data
jQuery('#AddBPromoter').submit(function(e){
  e.preventDefault();
  jQuery('#name-error').html("");
  jQuery('#phone-error').html("");
  jQuery('#search_bpromoter_phone').html("");
  jQuery.ajax({
    url:"bpromoter.add",
    method:"POST",
    data:new FormData(this),
    dataType:'JSON',
    contentType: false,
    cache: false,
    processData: false,
    beforeSend: function() {
        Notiflix.Loading.Arrows('Data Processing');
    },
    success:function(response) {
        console.log(response);
        Notiflix.Loading.Remove(300);   
        if(response.errors) {
            if(response.errors.bp_name){
                jQuery( '#name-error' ).html( response.errors.bp_name[0] );
            }
            if(response.errors.bp_phone){
                jQuery( '#phone-error' ).html( response.errors.bp_phone[0] );
            }
        }
        if(response == "success") {
            jQuery("#AddBPromoter")[0].reset();
            Notiflix.Notify.Success('BP Info Save Successfull');
            return getPromoterData();
        }
        if(response.fail) {
            if(response.errors.name) {
                jQuery('#error_field').addClass('has-error');
                jQuery('#error-name').html( response.errors.name[0] );
                Notiflix.Notify.Failure('BP Info Save Failed');
                setTimeout(function(){// wait for 5 secs(2)
                    window.location.reload(); // then reload the page.(3)
                    $(".btnCloseModal").click();
                }, 2000);
            }
        }
    },
    error:function(error) {
        Notiflix.Notify.Failure('BP Info Save Failed');
        setTimeout(function(){// wait for 5 secs(2)
            window.location.reload(); // then reload the page.(3)
            $(".btnCloseModal").click();
        }, 2000);
    }
  });
});
// Edit  Data
jQuery(document).on("click","#editBPromoterInfo",function(e){
  e.preventDefault();
  var PromoterId = jQuery(this).data('id');
  var url = "bpromoter.edit"+"/"+PromoterId;
  jQuery.ajax({
    url:url,
    type:"GET",
    dataType:"JSON",
    beforeSend: function() {
        Notiflix.Loading.Arrows('Data Processing');
    },
    success:function(response){
        console.log(response);
        Notiflix.Loading.Remove(300);           
        jQuery('#update_id').val(response.id);
        jQuery('.UpdateApiBPromoterId').val(response.bp_id);
        jQuery('.UpdateApiBPromoterRetailerId').val(response.retailer_id);
        jQuery('.UpdateApiBPromoterName').val(response.bp_name);
        jQuery('.UpdateApiBPromoterPhone').val(response.bp_phone);
        jQuery('.UpdateApiRetailerId').val(response.retailer_id).prop('readonly',true);
        jQuery('.UpdateApiRetailerName').val(response.retailer_name);
        jQuery('.UpdateApiRetailerAddress').val(response.retailder_address);
        jQuery('.UpdateApiRetailerOwnerName').val(response.owner_name);
        jQuery('.UpdateApiRetailerPhone').val(response.retailer_phone_number);
        jQuery('#UpdateApiRetailerPaymentType').val(response.payment_number_type);
        jQuery('#UpdateApiRetailerPaymentNumber').val(response.payment_number);
        //jQuery('.UpdateApiRetailerZoneId').val(response.zone_id);
        jQuery('.UpdateApiRetailerDivisionId').val(response.division_id);
        jQuery('.UpdateApiRetailerDivisionName').val(response.division_name);
        jQuery('.UpdateApiRetailerDistricId').val(response.distric_id);
        jQuery('.UpdateApiRetailerDistric').val(response.distric_name);
        jQuery('.UpdateApiRetailerPoliceStation').val(response.police_station);
        //jQuery('.UpdateApiRetailerThanaID').val(response.thana_id);
        jQuery('.UpdateApiRetailerDistributorCode').val(response.distributor_code).prop('readonly',true);;
        jQuery('.UpdateApiRetailerDistributorCode2').val(response.distributor_code2);
        jQuery('.UpdateApiCategoryId'+response.category_id).val(response.category_id).prop('selected',true);
        jQuery('.dealerCode').val(response.DealerCode).prop('readonly',true);
        jQuery('.udealerAlternetCode').val(response.distributor_code2).prop('readonly',true);
        jQuery('.udealerName').val(response.distributor_name);
        jQuery('.udealerZone').val(response.distributor_zone);

        $("#udCode").css("display", "block");
        $("#udAlternetCode").css("display", "block");
        $("#udName").css("display", "block");
        $("#udZone").css("display", "block");

        jQuery('.categoryName_'+response.category_name).prop('selected',true);

        if(response.payment_type == null) {
            jQuery("#umfc").prop("checked", false);
            jQuery("#ubank").prop("checked", false);
            jQuery('.agentDiv').hide();
            jQuery('.bankDiv').hide();

            jQuery('.mfc_field').val();
            jQuery('.mfc_name').val();
            jQuery('.bank_field').val();
            jQuery('.UpdateBankName').val();

            jQuery('.mfc_field').hide();
            jQuery('.mfc_name').hide();
            jQuery('.bank_field').hide();
            jQuery('.UpdateBankName').hide();
        }
        else if (response.payment_type == 1) {
            jQuery("#umfc").prop("checked", true);
            jQuery('.agentDiv').show();
            jQuery('.bankDiv').hide();
            jQuery('.mfc_field').val(response.payment_number);
            jQuery('.mfc_name').val(response.agent_name);
            $('.bank_field').remove();
            $('.UpdateBankName').remove();
        } else {
            jQuery("#ubank").prop("checked", true);
            jQuery('.agentDiv').hide();
            jQuery('.bankDiv').show();
            $('.mfc_field').remove();
            $('.mfc_name').remove();
            jQuery('.bank_field').val(response.payment_number);
            jQuery('.UpdateBankName').val(response.bank_name);
        }

        if (response.status == 1) {
            jQuery("#option1").prop("checked", true);
        } else {
            jQuery("#option2").prop("checked", true);
        }


        if(!(response.distributor_code) && !(response.distributor_code2))
        {
            Notiflix.Notify.Warning('Sorry You Are Not Update.Because Distributor Code Not Found');
            /*setTimeout(function(){// wait for 5 secs(2)
                window.location.reload(); // then reload the page.(3)
                $(".btnCloseModal").click();
            }, 2000);*/

            jQuery('.missingRetailerSearchButton').show();
            
        }
    }
  });
});
// Update Data
jQuery('#UpdateBPromoter').on("submit", function(arg){
    jQuery.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
        }
    });
    arg.preventDefault();
    var formData = new FormData(this);
    formData.append('_method', 'post');
  
    var promoterId   = jQuery('#update_id').val();
    var data         = jQuery("#UpdateBPromoter").serialize();
    jQuery('#update-name-error').html("");
    jQuery('#update-phone-error').html("");
    
    jQuery.ajax({
        url:"bpromoter.update",
        type:"POST",
        data:formData,
        dataType:'JSON',
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function() {
            Notiflix.Loading.Arrows('Data Processing');
        },
        success:function(response) {
            Notiflix.Loading.Remove(300);
            if(response == "success") {
                jQuery("#UpdateBPromoter")[0].reset();
                Notiflix.Notify.Success('BP Info Update Successfull');
                return getPromoterData();
            }
            if(response.errors) {
                if(response.errors.bp_name) {
                    jQuery( '#update-name-error' ).html( response.errors.bp_name[0] );
                }
                if(response.errors.bp_phone){
                    jQuery( '#update-phone-error' ).html( response.errors.bp_phone[0] );
                }
                Notiflix.Loading.Remove(300); 
            }
            if(response.fail) {
                if(response.errors.name) {
                    jQuery('#error_field').addClass('has-error');
                    jQuery('#error-name').html( response.errors.name[0] );
                    Notiflix.Notify.Failure('BP Info Update Failed');
                    Notiflix.Loading.Remove(300); 
                }
            }
            if(response=="error") {
                Notiflix.Notify.Failure('BP Info Update Failed');
                Notiflix.Loading.Remove(300); 
            }
        },
        error:function(error) {
          Notiflix.Notify.Failure('BP Info Update Failed');
          Notiflix.Loading.Remove(300); 
        }
    });
});
//API Search Promoter By Id
jQuery(document).on("click","#search_bpromoter_button",function(e){
  e.preventDefault();
  var PromoterPhone = jQuery('#search_bpromoter_phone').val();
  var url = "bpromoter.search_by_api"+"/"+PromoterPhone;
  jQuery.ajax({
    url:url,
    type:"GET",
    dataType:"JSON",
    beforeSend: function() {
        Notiflix.Loading.Arrows('Data Processing');
    },
    success:function(response){
        console.log(response);
        if(response !=null)
        {
            Notiflix.Loading.Remove(600);
            jQuery('.ApiBPromoterId').val(response.Id).prop('readonly',true);
            jQuery('.ApiBPromoterRetailerId').val(response.bpRetailId);
            jQuery('.ApiBPromoterName').val(response.bpName);
            jQuery('.ApiBPromoterPhone').val(response.bpPhone);
            if(response == 'success')
            {
                jQuery("#AddBPromoter")[0].reset();
                jQuery('#AddBPromoterModal').modal('hide');
                Notiflix.Notify.Success('Data Insert Successfully');
            }  
        }
        else
        {
            Notiflix.Notify.Failure( 'Data Not Found' );
            Notiflix.Loading.Remove(200);
        }

        if(response == 'empty' || response == 'error')
        {
            jQuery('.ApiBPromoterId').val(response.Id).prop('readonly',false);
            Notiflix.Notify.Failure( 'Data Not Found! Please Try Another Phone Number..' );
            Notiflix.Loading.Remove(600);
        }
        
    }
  });
});
//Add All Brand Promoter By Api Calling
function AddAllPromoterByApi() {
    var url = "AddBPromoterFromApi/";
    jQuery.ajax({
    url:url,
    type:"GET",
    dataType:"JSON",
        beforeSend: function() {
            Notiflix.Loading.Arrows('Data Processing');
        },
        success:function(response){
            console.log(response);
            if(response) {
                if(response == 'success') {
                    Notiflix.Loading.Remove(600);
                    Notiflix.Notify.Success('Data Insert Successfully');
                    window.location.reload();
                    return getPromoterData();
                } else {
                    Notiflix.Loading.Remove(600);
                    Notiflix.Notify.Warning( 'Data Not Found! Please Try Another Id..' );
                }
            } else {
                Notiflix.Loading.Remove(600);
                Notiflix.Notify.Failure( 'Data Not Found' );
            }
            
        },
       complete:function(response){
        // Hide image container
        Notiflix.Loading.Remove(600);
       }
    });
}
//API Search Retailer By Id / Mobile
jQuery(document).on("click","#search_retailer_button",function(e){
  e.preventDefault();
  //$('#AddRetailer').[0]   = html("");
  var getRetailerId         = jQuery('#search_retailer_id').val();
  var getRetailerMobile     = jQuery('#search_retailer_mobile').val();

  RetailerId        = "";
  RetailerMobile    = "";

  if(getRetailerId !=''){
    RetailerId      = getRetailerId;
    RetailerMobile  = 0;
  } else {
    RetailerMobile  = getRetailerMobile;
    RetailerId      = 0;
  }

  //var url = "apiretailer"+"/"+RetailerId+"/"+RetailerMobile;
  var url = "retailer.search_by_api"+"/"+getRetailerMobile;
  jQuery.ajax({
    url:url,
    type:"GET",
    dataType:"JSON",
    beforeSend: function() {
        Notiflix.Loading.Arrows('Data Processing');
        Notiflix.Loading.Remove(1000);
    },
    success:function(response){
        console.log(response);
        //if(response)
        if(!$.trim(response))
        {
            Notiflix.Notify.Failure('Data Not Found');
            Notiflix.Loading.Remove(600);
            jQuery('.ApiRetailerId').val(response.Id).prop('readonly',true);
            jQuery('.ApiRetailerName').val(response.RetailerName);
            jQuery('.ApiRetailerAddress').val(response.RetailerAddress);
            jQuery('.ApiRetailerOwnerName').val(response.OwnerName);
            jQuery('.ApiRetailerPhone').val(response.PhoneNumber);
            jQuery('.ApiRetailerPaymentType').val(response.PaymentNumberType);
            jQuery('.ApiRetailerPaymentNumber').val(response.PaymentNumber);
            jQuery('.ApiRetailerZoneId').val(response.ZoneId);
            jQuery('.ApiRetailerDivisionId').val(response.DivisionId);
            jQuery('.ApiRetailerDivisionName').val(response.Division);
            jQuery('.ApiRetailerDistricId').val(response.DistrictId);
            jQuery('.ApiRetailerDistric').val(response.District);
            jQuery('.ApiRetailerPoliceStation').val(response.PoliceStation);
            jQuery('.ApiRetailerThanaID').val(response.ThanaId);
            jQuery('.ApiRetailerDistributorCode').val(response.DistributorCode).prop('readonly',true);
            jQuery('.ApiRetailerDistributorCode2').val(response.DistributorCode2).prop('readonly',true);
            jQuery('.dealerZone').val(response.Zone);
            jQuery('.dealerName').val(response.DistributorName);
            jQuery('.dcode').val(response.DistributorCode);
            jQuery('.dacode').val(response.DistributorCode2);

            if(response == 'success') {
                jQuery("#AddRetailer")[0].reset();
                jQuery('#AddRetailerModal').modal('hide');
                Notiflix.Notify.Success('Data Insert Successfully');
            }  

        } else {
            //jQuery("#AddRetailer")[0].reset();
            //Notiflix.Notify.Failure( 'Data Not Found' );
            //Notiflix.Loading.Remove(600);

            Notiflix.Loading.Remove(600);
            jQuery('.ApiRetailerId').val(response.Id).prop('readonly',true);
            jQuery('.ApiRetailerName').val(response.RetailerName);
            jQuery('.ApiRetailerAddress').val(response.RetailerAddress);
            jQuery('.ApiRetailerOwnerName').val(response.OwnerName);
            jQuery('.ApiRetailerPhone').val(response.PhoneNumber);
            jQuery('.ApiRetailerPaymentType').val(response.PaymentNumberType);
            jQuery('.ApiRetailerPaymentNumber').val(response.PaymentNumber);
            jQuery('.ApiRetailerZoneId').val(response.ZoneId);
            jQuery('.ApiRetailerDivisionId').val(response.DivisionId);
            jQuery('.ApiRetailerDivisionName').val(response.Division);
            jQuery('.ApiRetailerDistricId').val(response.DistrictId);
            jQuery('.ApiRetailerDistric').val(response.District);
            jQuery('.ApiRetailerPoliceStation').val(response.PoliceStation);
            jQuery('.ApiRetailerThanaID').val(response.ThanaId);
            jQuery('.ApiRetailerDistributorCode').val(response.DistributorCode);
            jQuery('.ApiRetailerDistributorCode2').val(response.DistributorCode2);
            jQuery('.ApiRetailerDistributorCode').val(response.DistributorCode).prop('readonly',true);
            jQuery('.ApiRetailerDistributorCode2').val(response.DistributorCode2).prop('readonly',true);
            jQuery('.dealerZone').val(response.Zone);
            jQuery('.dealerName').val(response.DistributorName);
            jQuery('.dcode').val(response.DistributorCode);
            jQuery('.dacode').val(response.DistributorCode2);
        }

        /////////////////////////////////
        /*
        jQuery('.dealerCode').val(response.DistributorCode).prop('readonly',true);
        jQuery('.dealerAlternetCode').val(response.DistributorCode2).prop('readonly',true);
        jQuery('.dealerName').val(response.DistributorName);
        jQuery('.dealerZone').val(response.Zone);
        */

        jQuery("#dCode").css("display", "block");
        jQuery("#dAlternetCode").css("display", "block");
        jQuery("#dName").css("display", "block");
        jQuery("#dZone").css("display", "block");
        /////////////////////////////////

        if(response == 'empty' || response == 'error') {
            jQuery('.ApiRetailId').val(response.RetailId).prop('readonly',false);
            Notiflix.Notify.Failure( 'Data Not Found! Please Try Another Retailer Id..' );
            Notiflix.Loading.Remove(600);
        }
        
    }
  });
});
//BP Password Set
jQuery(document).on("click","#bpPasswordSet",function(e){
    e.preventDefault();
    var PromoterId = jQuery(this).data('id');
    var url = "bpromoter.passwordUpdate"+"/"+PromoterId;
    jQuery.ajax({
        url:url,
        type:"GET",
        dataType:"JSON",
        beforeSend: function() {
            Notiflix.Loading.Arrows('Data Processing');
        },
        success:function(response) {
            console.log(response);
            Notiflix.Loading.Remove(300);   
            if(response == "success") {
                Notiflix.Notify.Success('Password Save Successfull');
            }
            if(response == "update") {
                Notiflix.Notify.Success('Password Update Successfull');
            }
            if(response.fail) {
                if(response.errors.name) {
                    Notiflix.Loading.Remove(300);
                    Notiflix.Notify.Failure('Password Save Failed');
                    setTimeout(function(){// wait for 5 secs(2)
                        window.location.reload(); // then reload the page.(3)
                        $(".btnCloseModal").click();
                    }, 2000);
                }
            }
        },
        error:function(error) {
            Notiflix.Loading.Remove(300);
            Notiflix.Notify.Failure('Password Save Failed.Please Try Again');
            setTimeout(function(){// wait for 5 secs(2)
                window.location.reload(); // then reload the page.(3)
                $(".btnCloseModal").click();
            }, 2000);
        }
    })
});
//API Search Retailer By Id / Mobile For Update
jQuery(document).on("click","#update_search_retailer_button",function(e){
  e.preventDefault();
  var getRetailerId         = jQuery('#update_search_retailer_id').val();
  var getRetailerMobile     = jQuery('#update_search_retailer_mobile').val();

  RetailerId        = "";
  RetailerMobile    = "";

  if(getRetailerId !='') {
    RetailerId      = getRetailerId;
    RetailerMobile  = 0;
  } else {
    RetailerMobile  = getRetailerMobile;
    RetailerId      = 0;
  }
  var url = "retailer.search_by_api"+"/"+getRetailerMobile;
  jQuery.ajax({
    url:url,
    type:"GET",
    dataType:"JSON",
    beforeSend: function() {
        Notiflix.Loading.Arrows('Data Processing');
    },
    success:function(response) {
        console.log(response);
        if(response)
        {
            Notiflix.Loading.Remove(100);
            jQuery('.UpdateApiRetailerId').val(response.Id).prop('readonly',true);
            jQuery('.UpdateApiRetailerName').val(response.RetailerName);
            jQuery('.UpdateApiRetailerAddress').val(response.RetailerAddress);
            jQuery('.UpdateApiRetailerOwnerName').val(response.OwnerName);
            jQuery('.UpdateApiRetailerPhone').val(response.PhoneNumber);
            jQuery('.UpdateApiRetailerPaymentType').val(response.PaymentNumberType);
            jQuery('.UpdateApiRetailerPaymentNumber').val(response.PaymentNumber);
            jQuery('.UpdateApiRetailerZoneId').val(response.ZoneId);
            jQuery('.UpdateApiRetailerDivisionId').val(response.DivisionId);
            jQuery('.UpdateApiRetailerDivisionName').val(response.Division);
            jQuery('.UpdateApiRetailerDistricId').val(response.DistrictId);
            jQuery('.UpdateApiRetailerDistric').val(response.District);
            jQuery('.UpdateApiRetailerPoliceStation').val(response.PoliceStation);
            jQuery('.UpdateApiRetailerThanaID').val(response.ThanaId);
            jQuery('.UpdateApiRetailerDistributorCode').val(response.DistributorCode).prop('readonly',true);
            jQuery('.UpdateApiRetailerDistributorCode2').val(response.DistributorCode2).prop('readonly',true);
            jQuery('.udealerZone').val(response.Zone);
            jQuery('.udealerName').val(response.DistributorName);
            jQuery('.UpdateApiRetailerDistributorCode').val(response.DistributorCode);
            jQuery('.UpdateApiRetailerDistributorCode2').val(response.DistributorCode2);
        } 
        else
        {
            Notiflix.Notify.Failure('Data Not Found');
            Notiflix.Loading.Remove(100);
        }
    }
  });
});

$(document).on("click",".btnPromoterStatus",function(){
	var promoterId 		= $(this).attr('promoter-id');
	var promoterStatus 	= $(this).attr('promoter-status');
	
	var url = "bpromoter.status"+"/"+promoterId;
    jQuery.ajax({
        url:url,
        type:"GET",
        dataType:'JSON',
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function() {
            Notiflix.Loading.Arrows('Data Processing');
        },
        success:function(response) {
            Notiflix.Loading.Remove(300);
            if(response.success) {
                Notiflix.Notify.Success( 'Data Update Successfull' );
				if(response.status == 1){
					$("#promoterStatus_"+promoterId).removeClass("btn-danger");
					$("#promoterStatus_"+promoterId).addClass("btn-success");
					$("#promoterStatus_"+promoterId).html("Active");
				}
				else
				{
					$("#promoterStatus_"+promoterId).removeClass("btn-success");
					$("#promoterStatus_"+promoterId).addClass("btn-danger");
					$("#promoterStatus_"+promoterId).html("InActive");
				}
				
            }
        
            if(response.error) {
                Notiflix.Notify.Failure( 'Data Update Failed' );
				$(".inactive_"+bpId).css("display", "none");
				$(".active_"+bpId).css("display", "block");
            }
        }
    });
});
</script>
@endsection


@endsection