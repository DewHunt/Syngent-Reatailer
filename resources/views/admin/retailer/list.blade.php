@extends('admin.master.master')

@section('page-style')
    <style>
        .cp { padding:5px }
        .csearch { width:285px; }
        /* 
          ##Device = Desktops
          ##Screen = 1281px to higher resolution desktops
        */
        @media (min-width: 1281px) {
            .main-content .btn-group-sm>.btn, .btn-sm {
                font-size: 0.90rem !important;
                padding: 0.3rem 1rem !important;
                width: 100%;
            }
            .beforeAddBtn{ padding: 0px 7px; }
            .newAddBtn{
                width: 155px;
                padding-left: 0px !important;
                padding-right: 0px !important;
                margin-left: 10px;
                margin-right: 0px;
            }
            .eyeViewbtn { width: 150px; }
            .setworkingbtn { width: 150px; margin: 5px 0px; }
            .searchFrom { width: 155px !important; }
            .common-row-padding { padding-right: 10px; }
        }   
        /* Portrait and Landscape */
        @media only screen 
        and (min-device-width: 320px) 
        and (max-device-width: 568px)
        and (-webkit-min-device-pixel-ratio: 2) {
            .retailer-select-h select.form-control:not([size]):not([multiple]) { height: calc(3.4rem + 2px) !important; }
            .input-group-append .btn { padding: 1rem 0.75rem !important; }
            .cp { padding:5px }
            .csearch { width:285px; }        
        }
        /* Portrait and Landscape */
        @media only screen 
        and (min-device-width: 375px) 
        and (max-device-width: 812px) 
        and (-webkit-min-device-pixel-ratio: 3){
            .retailer-select-h select.form-control:not([size]):not([multiple]) { height: calc(3.4rem + 2px) !important; }
            .input-group-append .btn { padding: 1rem 0.75rem !important; }
            .cp { padding:5px }
            .csearch { width:285px; }
        }
        @media (min-width: 768px) and (max-width: 1024px) {
            .retailer-select-h select.form-control:not([size]):not([multiple]) { height: calc(3.4rem + 2px) !important; }
            .input-group-append .btn { padding: 1rem 0.75rem !important; }
            .cp { padding:5px }
            .csearch { width:285px; }        
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12"><h4 class="c-grey-900 mB-20">Retailer List</h4></div>
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 common-row-padding">
            <div style="display: none;">
                <button type="button" class="btn btn-primary pull-right btn-sm newAddBtn" data-toggle="modal" data-target="#AddRetailerModal">Add Retailer</button><br/>
            </div>
            <div class="form-group top-margin" style="display: none;">
                <input type="text" name="serach" id="serach" class="form-control pull-right csearch searchFrom"/>
            </div>
        </div>
    </div>

    <div id="tag_container" class="table-responsive">
        <table class="table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th class="sorting" data-sorting_type="asc" data-column_name="id" style="cursor: pointer; width:5%">Sl.</th>
                    <th class="sorting" data-sorting_type="asc" data-column_name="retailer_name" style="cursor: pointer; width:30%">Retailer Name</th>
                    <th class="sorting" data-sorting_type="asc" data-column_name="phone_number" style="cursor: pointer; width:15%">Retailer Phone</th>
                    <th class="sorting" data-sorting_type="asc" data-column_name="retailder_address" style="cursor: pointer; width:25%">Address</th>
                    <th class="sorting" data-sorting_type="asc" data-column_name="owner_name" style="cursor: pointer; width:10%">Owner Name</th>
                    {{-- <th class="sorting" data-sorting_type="asc" data-column_name="dealer" style="cursor: pointer; width:30%">Dealer Name</th> --}}
                    {{-- <th class="sorting" data-sorting_type="asc" data-column_name="dealer" style="cursor: pointer; width:30%">Dealer Code</th> --}}
                    <!--<th class="sorting" data-sorting_type="asc" data-column_name="status" style="cursor: pointer;">Status</th>-->
                    <th style="width:10%">Action</th>
                </tr>
            </thead>
            <tbody>
                @include('admin.retailer.result_data')
            </tbody>
        </table>
        <input type="hidden" name="hidden_page" id="hidden_page" value="1" />
        <input type="hidden" name="hidden_column_name" id="hidden_column_name" value="id" />
        <input type="hidden" name="hidden_sort_type" id="hidden_sort_type" value="asc" />
    </div>

    <!--Add New Data Modal Start -->
    @include('admin.retailer.add_retailer_modal')
    <!--Add New Modal End -->

    <!--Edit & Update Modal Start -->
    {{-- @include('admin.retailer.edit_retailer_modal') --}}
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

                <div class="modal-body"><div class="edit-from-div"></div></div>
            </div>
        </div>
    </div>
    <!--Edit & Update Modal End -->

    <!--Set Working Hour Modal Start -->
    <div class="modal fade" id="setWorkingHourModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Set Working Hour</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body"><div class="working_hour_div"></div></div>
            </div>
        </div>
    </div>
    <!--Set Working Hour Modal Start -->

    <div class="modal fade" id="viewRetailerDetailsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Retailer Information</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                     <div id="retailerResultInfo"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary pull-left" data-dismiss="modal">Close</button> 
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-scripts')
    <script type="text/javascript">
        jQuery(document).ready(function() {
            jQuery('.agentDiv').css('display','none');
            jQuery('.bankDiv').css('display','none');
        });

        jQuery(document).on('change','.payment_type',function() {
            let paymentValue = $(this).val();
            if (paymentValue == '1') {
            console.log(paymentValue);
                jQuery('.agentDiv').css('display','block');
                jQuery('.bankDiv').css('display','none');
            } else if (paymentValue == '2') {
                jQuery('.agentDiv').css('display','none');
                jQuery('.bankDiv').css('display','block');
            }
        });

        //Modal Status Update Option 
        jQuery('.retailer-toggle-class').change(function(e) {
            e.preventDefault();
            var status = jQuery(this).prop('checked') == true ? 1 : 0; 
            var RetailerId = jQuery(this).data('id');
            var url = "retailer.status"+"/"+RetailerId;
            jQuery.ajax({
                url:url,
                type:"GET",
                dataType:'JSON',
                cache: false,
                contentType: false,
                processData: false,
                success:function(response){
                    if (response.success) {
                        Notiflix.Notify.Success( 'Data Update Successfull' );
                    }                
                    if (response.error) {
                        Notiflix.Notify.Failure( 'Data Update Failed' );
                    }
                }
            });
        });

        // Get Data AS MySql View Page   
        function getRetailerData() {
            var query = $('#serach').val();
            var column_name = $('#hidden_column_name').val();
            var sort_type = $('#hidden_sort_type').val();
            var page = $('#hidden_page').val();
            //var url = "retailer";
            jQuery.ajax({
                url:"?page="+page+"&sortby="+column_name+"&sorttype="+sort_type+"&query="+query,
                type:"GET",
                dataType:"HTMl",
                success:function(response){
                    jQuery('.loading').hide();
                    setTimeout(function() { window.location.reload(); }, 500);
                },
            });
        }

        // Add New Data
        jQuery('#AddRetailer').submit(function(e){
            e.preventDefault();
            jQuery('#name-error').html("");
            jQuery('#address-error').html("");
            jQuery('#owner-name-error').html("");
            jQuery('#phone-error').html("");
            jQuery.ajax({
                url:"retailer.add",
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
                    Notiflix.Loading.Remove(300);
                    if (response.errors)  {
                        if (response.errors.retailer_name) {
                            jQuery( '#name-error' ).html( response.errors.retailer_name[0] );
                        }
                        if (response.errors.retailder_address) {
                            jQuery( '#address-error' ).html( response.errors.retailder_address[0] );
                        }
                        if (response.errors.owner_name) {
                            jQuery( '#owner-name-error' ).html( response.errors.owner_name[0] );
                        }
                        if (response.errors.phone_number) {
                            jQuery( '#phone-error' ).html( response.errors.phone_number[0] );
                        }
                    }

                    if (response == "success") {
                        jQuery("#AddRetailer")[0].reset();
                        Notiflix.Notify.Success('Retailer Save Successfull');
                        return getRetailerData();
                    }

                    if (response.fail) {
                        if(response.errors.name){
                            jQuery('#error_field').addClass('has-error');
                            jQuery('#error-name').html( response.errors.name[0] );
                            Notiflix.Notify.Failure('Retailer Save Failed');
                        }
                    }
                },
                error:function(error){
                    Notiflix.Notify.Failure('Retailer Save Failed');
                }
            });
        });

        // Edit  Data
        jQuery(document).on("click","#editInfo",function(e){
            e.preventDefault();
            var RetailId = jQuery(this).data('id');
            var url = "{{ url('/') }}"+"/retailer.edit"+"/"+RetailId;
            jQuery.ajax({
                url:url,
                type:"GET",
                dataType:"JSON",
                beforeSend: function() {
                    Notiflix.Loading.Arrows('Data Processing');
                },
                success:function(response){
                    // console.log(response);
                    jQuery('.edit-from-div').html(response);
                    jQuery('.agentDiv').css('display','none');
                    jQuery('.bankDiv').css('display','none');
                    Notiflix.Loading.Remove(300);
                }
            });
        });

        // Update Data
        jQuery(document).on('submit','#UpdateRetailer',function(arg) {
            arg.preventDefault();
            var formData = new FormData(this);
            
            jQuery.ajax({
                url:"retailer.update",
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
                    if (response.errors) {
                        if (response.errors.retailer_name) {
                            jQuery( '#update-name-error' ).html( response.errors.retailer_name[0] );
                        }
                        if (response.errors.retailder_address) {
                            jQuery( '#update-address-error' ).html( response.errors.retailder_address[0] );
                        }
                        if (response.errors.owner_name) {
                            jQuery( '#update-owner-name-error' ).html( response.errors.owner_name[0] );
                        }
                        if (response.errors.phone_number) {
                            jQuery( '#update-phone-error' ).html( response.errors.phone_number[0] );
                        }
                    }
                    if (response == "success") {
                        Notiflix.Notify.Success('Retailer Info Update Successfull' );
                        return getRetailerData();
                    }                
                    if (response == "error") {
                        Notiflix.Notify.Failure('Retailer Info Update Failed' );
                    }                
                    if (response.fail) {
                        if (response.errors.name) {
                            jQuery('#error_field').addClass('has-error');
                            jQuery('#error-name').html( response.errors.name[0] );
                            Notiflix.Notify.Failure('Retailer Info Update Failed' );
                        }
                    }
                },
                error:function(error) {
                    Notiflix.Notify.Failure( 'Retailer Info Update Failed' );
                }
            });
        });

        //API Search Retailer By Id / Mobile
        jQuery(document).on("click","#search_retailer_button",function(e){
            e.preventDefault();
            var getRetailerId = jQuery('#search_retailer_id').val();
            var getRetailerMobile = jQuery('#search_retailer_mobile').val();
            RetailerId = "";
            RetailerMobile = "";
            if (getRetailerId != '') {
                RetailerId = getRetailerId;
                RetailerMobile = 0;
            } else {
                RetailerMobile = getRetailerMobile;
                RetailerId = 0;
            }

            // var url = "apiretailer"+"/"+RetailerId+"/"+RetailerMobile;
            var url = "retailer.search_by_api"+"/"+getRetailerMobile;
            jQuery.ajax({
                url:url,
                type:"GET",
                dataType:"JSON",
                beforeSend: function() {
                    Notiflix.Loading.Arrows('Data Processing');
                    Notiflix.Loading.Remove(1000);
                },
                success:function(response) {
                    console.log(response);
                    if (!$.trim(response)) {
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

                        if (response == 'success') {
                            jQuery("#AddRetailer")[0].reset();
                            jQuery('#AddRetailerModal').modal('hide');
                            Notiflix.Notify.Success('Data Insert Successfully');
                        }  
                    } else {
                        // Notiflix.Notify.Failure( 'Data Not Found' );
                        // Notiflix.Loading.Remove(600);
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
                    jQuery("#dCode").css("display", "block");
                    jQuery("#dAlternetCode").css("display", "block");
                    jQuery("#dName").css("display", "block");
                    jQuery("#dZone").css("display", "block");

                    if (response == 'empty' || response == 'error') {
                        jQuery('.ApiRetailId').val(response.RetailId).prop('readonly',false);
                        Notiflix.Notify.Failure( 'Data Not Found! Please Try Another Retailer Id..' );
                        Notiflix.Loading.Remove(600);
                    }
                }
            });
        });

        function SearchRetailerDisable() {
            var RetailerId = jQuery('#search_retailer_id').val();
            if (RetailerId != '') {
                jQuery('#search_retailer_id').prop('disabled',false);
                jQuery('#search_retailer_mobile').prop('disabled',true);
            } else {
                jQuery('#search_retailer_id').prop('disabled',false);
                jQuery('#search_retailer_mobile').prop('disabled',false);
            }
        }

        function SearchRetailerMobileDisable() {
            var RetailerMobile  = jQuery('#search_retailer_mobile').val();
            if (RetailerMobile != '') {
                jQuery('#search_retailer_mobile').prop('disabled',false);
                jQuery('#search_retailer_id').prop('disabled',true);
            } else {
                jQuery('#search_retailer_mobile').prop('disabled',false);
                jQuery('#search_retailer_id').prop('disabled',false);
            }
        }

        //API Search  By Dealer Code
        jQuery(document).on("click","#search_retailer_dealer_button",function(e) {
            e.preventDefault();
            var DealerCode = jQuery('#search_retailer_dealer_code').val();
            var url = "CheckDealerFromApi"+"/"+DealerCode;
            jQuery.ajax({
                url:url,
                type:"GET",
                dataType:"JSON",
                beforeSend: function() {
                    Notiflix.Loading.Arrows('Data Processing');
                    Notiflix.Loading.Remove(300);
                },
                success:function(response) {
                    console.log(response);
                    if (!$.trim(response)) {
                        Notiflix.Notify.Warning('Invalid Distributor Code');
                        Notiflix.Loading.Remove(600);
                        jQuery('.dealerCode').val(response.DealerCode).prop('readonly',true);
                        jQuery('.dealerAlternetCode').val(response.ImportCode).prop('readonly',true);
                        jQuery('.dealerName').val(response.DistributorNameCellCom);
                        jQuery('.dealerZone').val(response.Zone);
                        jQuery("#dCode").css("display", "block");
                        jQuery("#dAlternetCode").css("display", "block");
                        jQuery("#dName").css("display", "block");
                        jQuery("#dZone").css("display", "block");

                        // setTimeout(function(){// wait for 5 secs(2)
                        //     window.location.reload(); // then reload the page.(3)
                        //     $(".btnCloseModal").click();
                        // }, 500);
                    } else {                        
                        Notiflix.Loading.Remove(600);
                        jQuery('.dealerCode').val(response.DealerCode).prop('readonly',true);
                        jQuery('.dealerAlternetCode').val(response.ImportCode).prop('readonly',true);
                        jQuery('.dealerName').val(response.DistributorNameCellCom);
                        jQuery('.dealerZone').val(response.Zone);
                        jQuery("#dCode").css("display", "block");
                        jQuery("#dAlternetCode").css("display", "block");
                        jQuery("#dName").css("display", "block");
                        jQuery("#dZone").css("display", "block");
                    }
                }
            });
        });

        jQuery(document).on("click","#usearch_retailer_dealer_button",function(e){
            e.preventDefault();
            var DealerCode = jQuery('#usearch_retailer_dealer_code').val();
            var url = "CheckDealerFromApi"+"/"+DealerCode;
            jQuery.ajax({
                url:url,
                type:"GET",
                dataType:"JSON",
                beforeSend: function() {
                    Notiflix.Loading.Arrows('Data Processing');
                    Notiflix.Loading.Remove(300);
                },
                success:function(response) {
                    console.log(response);
                    if (response) {
                        Notiflix.Loading.Remove(600);
                        jQuery('.udealerCode').val(response.DealerCode).prop('readonly',true);
                        jQuery('.udealerAlternetCode').val(response.ImportCode).prop('readonly',true);
                        jQuery('.udealerName').val(response.DistributorNameCellCom);
                        jQuery('.udealerZone').val(response.Zone);

                        jQuery("#udCode").css("display", "block");
                        jQuery("#udAlternetCode").css("display", "block");
                        jQuery("#udName").css("display", "block");
                        jQuery("#udZone").css("display", "block");

                    } else {
                        Notiflix.Notify.Failure( 'Data Not Found' );
                        Notiflix.Loading.Remove(600);
                    }                    
                }
            });
        });

        jQuery(document).on("click","#setRetailerWorkingHour",function(e){
            e.preventDefault();
            $('#retailerId').val(0);
            var retailId = jQuery(this).data('id');
            var url = "retailer.open_working_time_modal"+"/"+retailId;
            jQuery.ajax({
                url:url,
                type:"GET",
                dataType:"JSON",
                success:function(response) {
                    console.log(response);
                    jQuery('.working_hour_div').html(response);
                }
            });
        });

        jQuery(document).on('submit','#saveShopWorkingTime',function(e) {
            e.preventDefault();
            jQuery.ajax({
                url:"retailer.save_working_time",
                method:"POST",
                data:new FormData(this),
                dataType:'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success:function(response) {
                    if (response == 'success') {
                        Notiflix.Notify.Success('Working Time Save Successfully');
                    }
                    if (response == 'error') {
                        Notiflix.Notify.Warning( 'Working Time Save Failed' );
                    }
                    setTimeout(function() {
                        window.location.reload();
                        $(".btnCloseModal").click();
                    }, 500);
                },
                error:function(error){
                    Notiflix.Notify.Failure( 'Something Went Wrong.Please Try Again' );
                }
            });
        });

        jQuery(document).on("click","#retailerPasswordSet",function(e){
            e.preventDefault();
            var retailerId = jQuery(this).data('id');
            var url = "retaile.passwordUpdate"+"/"+retailerId;
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
                    if (response == "success") {
                        Notiflix.Notify.Success('Password Save Successfull');
                    }
                    if (response == "update") {
                        Notiflix.Notify.Success('Password Update Successfull');
                    }
                    if (response.fail) {
                        if(response.errors.name) {
                            Notiflix.Notify.Failure('Password Save Failed');
                            setTimeout(function(){// wait for 5 secs(2)
                                window.location.reload(); // then reload the page.(3)
                                $(".btnCloseModal").click();
                            }, 2000);
                        }
                    }
                },
                error:function(error) {
                    Notiflix.Notify.Failure('Password Save Failed');
                    setTimeout(function(){// wait for 5 secs(2)
                        window.location.reload(); // then reload the page.(3)
                        $(".btnCloseModal").click();
                    }, 2000);
                }
            });
        });

        jQuery(document).on("click","#viewRetailerDetails",function(e){
            e.preventDefault();
            var retailerId = jQuery(this).data('id');
            var url = "retailer.show"+"/"+retailerId;
            jQuery.ajax({
                url:url,
                type:"GET",
                dataType:"JSON",
                beforeSend: function() {
                    Notiflix.Loading.Arrows('Data Processing');
                },
                success:function(response) {
                    console.log(response);
                    $('#retailerResultInfo').html(response);
                    Notiflix.Loading.Remove(300);
                }
            });
        });

        // Pagination Script Start
        function clear_icon() {
            $('#id_icon').html('');
            $('#post_title_icon').html('');
        }

        function fetch_data(page, sort_type, sort_by, query) {
            $.ajax({
                url:"?page="+page+"&sortby="+sort_by+"&sorttype="+sort_type+"&query="+query,
                type:"get",
                async:false,
                cache:false,
                processData:false,
                success:function(data) {
                    console.log(data);
                    $('tbody').html('');
                    $('tbody').html(data);
                    $(this).data('toggle-on', true);
                    jQuery('.toggle').each(function() {
                        $(this).toggles({
                            on: $(this).data('toggle-on')
                        });
                    });
                    if (!data.json_data_for_excel_and_pdf != "") {
                        $('#export_data').val(data.json_data_for_excel_and_pdf);
                    }
                    jQuery('input[type=checkbox][data-toggle^=toggle]').bootstrapToggle();
                    var toggleJs  = APP_URL+"/public/admin/js/custom-js/toggle-information.js";
                    jQuery.getScript(toggleJs);
                }
            })
        }

        jQuery(document).ready(function() {
            var searhText = document.getElementById('serach');
            searhText.onkeydown = function() {
                var key = event.keyCode || event.charCode;
                if (key == 8) {
                    var getSearchVal = $('#serach').val();
                    var length = getSearchVal.length;
                    if(length <= 1) {
                        var query = $('#serach').val();
                        var column_name = $('#hidden_column_name').val();
                        var sort_type = $('#hidden_sort_type').val();
                        var page = $('#hidden_page').val();
                        fetch_data(page, sort_type, column_name, query);
                    }
                }
            };
            
            jQuery(document).on('keyup', '#serach', function() {
                var getSearchVal = $('#serach').val();
                var length = getSearchVal.length;
                if (length >= 3) {
                    var query       = $('#serach').val();
                    var column_name = $('#hidden_column_name').val();
                    var sort_type   = $('#hidden_sort_type').val();
                    var page        = $('#hidden_page').val();
                    fetch_data(page, sort_type, column_name, query);
                }
            });

            jQuery(document).on('click', '.sorting', function(){
                var column_name = $(this).data('column_name');
                var order_type = $(this).data('sorting_type');
                var reverse_order = '';
                if (order_type == 'asc') {
                    $(this).data('sorting_type', 'desc');
                    reverse_order = 'desc';
                    clear_icon();
                    $('#'+column_name+'_icon').html('<span class="glyphicon glyphicon-triangle-bottom"></span>');
                }
                if (order_type == 'desc') {
                    $(this).data('sorting_type', 'asc');
                    reverse_order = 'asc';
                    clear_icon
                    $('#'+column_name+'_icon').html('<span class="glyphicon glyphicon-triangle-top"></span>');
                }
                $('#hidden_column_name').val(column_name);
                $('#hidden_sort_type').val(reverse_order);
                var page = $('#hidden_page').val();
                var query = $('#serach').val();
                fetch_data(page, reverse_order, column_name, query);
            });

            jQuery(document).on('click', '.pagination a', function(event) {
                event.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                $('#hidden_page').val(page);
                var column_name = $('#hidden_column_name').val();
                var sort_type = $('#hidden_sort_type').val();
                var query = $('#serach').val();
                $('li').removeClass('active');
                $(this).parent().addClass('active');
                fetch_data(page, sort_type, column_name, query);
            });
        });
    </script>
@endsection