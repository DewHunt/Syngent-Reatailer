@extends('admin.master.master')
@section('content')
<style>
.cp {
    padding:5px 0px;
}
.csearch {
    width:208px;
}
.btn-margin-top {
    margin-top: 15px;
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
    }
    .beforeAddBtn{
        padding: 0px 7px;
    }
    .newAddBtn{
        margin-left: 5px;
        width: 170px;
        margin: 0px;
        padding-left: 0px !important;
        padding-right: 0px !important;
    }
    .eyeViewbtn {
        width: 90px;
    }
    /*.btn-sm {
        font-size: 1.7rem !important;
        padding: 0rem 1rem !important;
        margin: 5px 0 5px 0;
        width: 277px;
    }
    .dataTables_wrapper .dataTables_filter input {
        height: 45px;
    }
    .btn-group > .btn {
        padding: 10px 15px;
    }*/
    
}           
/* Portrait and Landscape */
@media only screen 
and (min-device-width: 320px) 
and (max-device-width: 568px)
and (-webkit-min-device-pixel-ratio: 2) {
    .cp {
        padding:5px
    }
    .csearch {
        width:300px;
    }
    .dataTables_wrapper .dataTables_filter input {
        height: 60px;
    }
    /*.btn-sm {*/
    /*    font-size: 2rem !important;*/
    /*    padding: 0.5rem 1.5em !important;*/
    /*    margin: 5px 0 5px 0;*/
    /*    width: 275px;*/
    /*}*/
    .btn-sm {
        font-size: 1.7rem !important;
        padding: 1rem 1rem !important;
        margin: 5px 15px 5px 0;
        width: 150px;
    }
    .dataTables_wrapper .dataTables_filter input {
        height: 45px;
    }
    .btn-group > .btn {
        padding: 10px 15px;
    }
@media only screen 
and (min-device-width: 375px) 
and (max-device-width: 812px) 
and (-webkit-min-device-pixel-ratio: 3){
    .cp {
        padding:5px
    }
    .csearch {
        width:300px;
    }
    .dataTables_wrapper .dataTables_filter input {
        height: 60px;
    }
    /*.btn-sm {*/
    /*    font-size: 2rem !important;*/
    /*    padding: 0.5rem 1.5em !important;*/
    /*    margin: 5px 0 15px 0;*/
    /*    width: 275px;*/
    /*}*/
    .btn-sm {
        font-size: 1.7rem !important;
        padding: 1rem 1rem !important;
        margin: 5px 15px 5px 0;
        width: 150px;
    }
    .dataTables_wrapper .dataTables_filter input {
        height: 45px;
    }
    .btn-group > .btn {
        padding: 10px 15px;
    }
}
@media (min-width: 768px) and (max-width: 1024px) {
    .cp {
        padding:5px
    }
    .csearch {
        width:300px;
    }
    .dataTables_wrapper .dataTables_filter input {
        height: 60px;
    }
    .btn-sm {
        font-size: 1.7rem !important;
        padding: 0.5rem 1rem !important;
        margin: 5px 15px 5px 0;
        width: 277px;
    }
    .dataTables_wrapper .dataTables_filter input {
        height: 45px;
    }
    .btn-group > .btn {
        padding: 10px 15px;
    }
}
</style>
<h4 class="c-grey-900">Dealer Information
    <button  type="button" class="btn btn-primary pull-right btn-sm newAddBtn" onclick="ClickAddToDealerFormApi()">
    <i class="fa fa-refresh" aria-hidden="true"></i> Refresh
    </button>
</h4>
<div class="col-md-12">
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-10 beforeAddBtn">
            {{-- 
            <button  type="button" class="btn btn-primary pull-right btn-sm newAddBtn" data-toggle="modal" data-target="#AddDeallerModal">Add New Dealer</button> 
            --}}

            {{-- 
            <button  type="button" class="btn btn-primary pull-right btn-sm newAddBtn" onclick="ClickAddToDealerFormApi()">
            <i class="fa fa-refresh" aria-hidden="true"></i> Get Dealer From Api
            </button>
             --}}
        </div>
    </div>
</div>
 
{{-- 
<div class="col-md-12 cp">
    <div class="row">
        <div class="col-md-6 btn-margin-top">
        <form action="{{ url('dealerExport') }}" method="POST">
            @csrf
            <input type="hidden" id="export_data" name="export_data" value="{{ $json_data_for_excel_and_pdf }}">
            <input type="hidden" id="data_type" name="data_type" value="excel">
            <button type="submit" class="btn btn-primary cur-p btn-xs exportbtn" id="exportExcel">Export to Excel</button>
            <button type="submit" class="btn btn-info cur-p btn-xs exportbtn" id="exportPdf">Export to Pdf</button>
        </form>
        </div>
        <div class="col-md-6">
            <div class="form-group top-margin">
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
                <th class="sorting" data-sorting_type="asc" data-column_name="id" style="cursor: pointer;width:5px">Sl. <span id="id_icon"></span></th>
                <th class="sorting" data-sorting_type="asc" data-column_name="dealer_code" style="cursor: pointer;width:10px">Dealer Code </th>
                <th class="sorting" data-sorting_type="asc" data-column_name="alternet_code" style="cursor: pointer;width:5px">Alternate Code </th>
                <th class="sorting" data-sorting_type="asc" data-column_name="dealer_name" style="cursor: pointer;width:15px">Dealer Name </th>
                <th class="sorting" data-sorting_type="asc" data-column_name="dealer_phone_number" style="cursor: pointer;width:5px">Phone </th>
                <th class="sorting" data-sorting_type="asc" data-column_name="dealer_address" style="cursor: pointer;width:15px">Address </th>
                <!--<th class="sorting" data-sorting_type="asc" data-column_name="status" style="cursor: pointer;width:5px">Status </th>-->
                <th style="width:50px !important">Action </th>
            </tr>
        </thead>
        <tbody>
            @include('admin.dealer.result_data')
        </tbody>
    </table>
</div>
{{-- <input type="hidden" name="hidden_page" id="hidden_page" value="1" />
<input type="hidden" name="hidden_column_name" id="hidden_column_name" value="id" />
<input type="hidden" name="hidden_sort_type" id="hidden_sort_type" value="asc" /> --}}

<!--Add New Dealer Modal Start -->
<div class="modal fade" id="AddDeallerModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Dealer</h5>

                <span style="font-size:12px;margin-top:6px;margin-left:5px">[** All <span style="color:red;">Red</span> Start Sign Data Must Be Fillable.**]</span>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form class="form-horizontal" method="POST" action="{{route('dealer.add')}}" id="AddDealer">
                @csrf
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group col-md-8">
                            <input type="text" class="form-control" id="search_dealer_code" placeholder="Search Dealer">
                        </div>
                        <div class="form-group col-md-4">
                            <button type="button" class="btn btn-primary btn-block" id="search_dealer_button">Search</button>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label>Dealer Code <span class="required">*</span></label>
                            <input type="text" name="dealer_code" class="form-control apidcode" placeholder="Dealer Code" required=""/>
                            <span class="text-danger">
                                <strong id="dealer-code-error"></strong>
                            </span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label>Alternate Code</label>
                            <input type="text" name="alternate_code" class="form-control apialtercode" placeholder="Alternate  Code"/>
                            <span class="text-danger">
                                <strong id="alternet-code-error"></strong>
                            </span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label>Dealer Name <span class="required">*</span></label>
                            <input type="text" name="dealer_name" class="form-control apidname" placeholder="Dealer Name" required=""/>
                            <span class="text-danger">
                                <strong id="name-error"></strong>
                            </span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label>Zone <span class="required">*</span></label>
                            <input type="text" name="zone" class="form-control apidzone" placeholder="Dealer Zone" required=""/>
                            <span class="text-danger">
                                <strong id="zone-error"></strong>
                            </span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label>City</label>
                            <input type="text" name="city" class="form-control" placeholder="Dealer City"/>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label>Division</label>
                            <input type="text" name="division" class="form-control" placeholder="Dealer Division"/>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label>Mobile <span class="required">*</span></label>
                            <input type="text" maxlength="11"  minlength="11" name="dealer_phone_number" class="form-control apidphone Number" placeholder="Mobile Number" required=""/>
                            <span class="text-danger">
                                <strong id="phone-error"></strong>
                            </span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label>Dealer Type</label>
                            <input type="text" name="dealer_type" class="form-control" placeholder="Dealer Type"/>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <label>Address</label>
                            <textarea class="form-control apidaddress" name="dealer_address"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="dealer_id" class="apidealerid"/>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> 
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--Add New Dealer Modal End -->

<!--Edit & Update Modal Start -->
<div class="modal fade" id="editDelarModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Information</h5>
                <span style="font-size:12px;margin-top:6px;margin-left:5px">[** All <span style="color:red;">Red</span> Start Sign Data Must Be Fillable.**]</span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form class="form-horizontal" method="POST" action="" id="UpdateDealer">
                @csrf
                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label>Dealer Code <span class="required">*</span></label>
                            <input type="text" name="dealer_code" class="form-control dealercode" placeholder="Dealer Code" required=""/>
                            <span class="text-danger">
                                <strong id="updatedealer-code-error"></strong>
                            </span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label>Alternate Code</label>
                            <input type="text" name="alternate_code" class="form-control alternetcode" placeholder="Alternate  Code"/>
                            <span class="text-danger">
                                <strong id="update-alternet-code-error"></strong>
                            </span>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label>Dealer Name <span class="required">*</span></label>
                            <input type="text" name="dealer_name" class="form-control dealername" placeholder="Dealer Name" required=""/>
                            <span class="text-danger">
                                <strong id="update-name-error"></strong>
                            </span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label>Zone <span class="required">*</span></label>
                            <input type="text" name="zone" class="form-control zone" placeholder="Dealer Zone" required=""/>
                            <span class="text-danger">
                                <strong id="update-zone-error"></strong>
                            </span>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label>City</label>
                            <input type="text" name="city" class="form-control city" placeholder="Dealer City"/>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label>Division</label>
                            <input type="text" name="division" class="form-control division" placeholder="Dealer Division"/>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label>Mobile <span class="required">*</span></label>
                            <input type="text" maxlength="11"  minlength="11" name="dealer_phone_number" class="form-control dealerphone Number" placeholder="Mobile Number" required=""/>
                            <span class="text-danger">
                                <strong id="update-phone-error"></strong>
                            </span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label>Dealer Type</label>
                            <input type="text" name="dealer_type" class="form-control dealertype" placeholder="Dealer Type"/>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <label>Address</label>
                            <textarea class="form-control dealeraddress" name="dealer_address"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="dealer_id" class="dealerid"/>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> 
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--Edit & Update Modal End -->

<div class="modal fade" id="viewDealerDetailsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Dealer Information</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                 <table class="table table-striped table-bordered table-sm" cellspacing="0" width="100%" id="dealerResultInfo">
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary pull-left" data-dismiss="modal">Close</button> 
            </div>
        </div>
    </div>
</div>

@section('page-scripts')
<script type="text/javascript">
jQuery(document).on("click","#exportExcel",function(e){
    var getAjaxVal = $('#ajax_export_data').val();
    if(!empty(getAjaxVal)){
        $('#export_data').val(getAjaxVal);
    }
    /*var searchVal       = $('#serach').val();
    var searchData      = "bpId="+bpId+'&getStartDate='+getStartDate+'&getEndDate='+getEndDate+'&searchVal='+searchVal+'&type=excel';

    var url = "dealer-export?"+searchData;
    $('#exportExcel').attr('href',url);*/
});
jQuery(document).on("click","#exportPdf",function(e){
    //e.preventDefault();
    var getAjaxVal = $('#ajax_export_data').val();
    if(!empty(getAjaxVal)){
        $('#export_data').val(getAjaxVal);
    }
    $('#data_type').val('pdf');
    return true;
    /*var searchVal      = $('#serach').val();
    var searchData     = "bpId="+bpId+'&getStartDate='+getStartDate+'&getEndDate='+getEndDate+'&searchVal='+searchVal+'&type=pdf';

    var url = "dealer-export?"+searchData;
    $('#exportPdf').attr('href',url);*/
});
//Dealer Information Modal Status Update Option 
jQuery('.dealer-toggle-class').change(function(e) {
    e.preventDefault();
    var status = jQuery(this).prop('checked') == true ? 1 : 0; 
    var DealerId = jQuery(this).data('id');
    var url = "dealer.status"+"/"+DealerId;
    jQuery.ajax({
        url:url,
        type:"GET",
        dataType:'JSON',
        cache: false,
        contentType: false,
        processData: false,
    
        success:function(response){
            if(response.success){
                Notiflix.Notify.Success('Status Update Successfull');
                Notiflix.Loading.Remove(600);
            }
            if(response.error){
                Notiflix.Notify.Failure('Status Update Failed');
                Notiflix.Loading.Remove(600);
            }
        }
    });
});
// Get Dealer Data AS MySql View Page   
function getDealerData() {
    var query       = $('#serach').val();
    var column_name = $('#hidden_column_name').val();
    var sort_type   = $('#hidden_sort_type').val();
    var page        = $('#hidden_page').val();
    //var url = "dealerinfo";
    jQuery.ajax({
    url:"?page="+page+"&sortby="+column_name+"&sorttype="+sort_type+"&query="+query,
    type:"GET",
    dataType:"HTMl",
        success:function(response){
            jQuery('.loading').hide();
            setTimeout(function(){// wait for 5 secs(2)
            window.location.reload(); // then reload the page.(3)
            }, 500);
        },
    });
}
// Add Dealer Data
jQuery('#AddDealer').submit(function(e){
    e.preventDefault();
    jQuery('#dealer-code-error').html("");
    jQuery('#alternet-code-error').html("");
    jQuery('#name-error').html("");
    jQuery('#zone-error').html("");
    jQuery('#phone-error').html("");
    var url = "dealer.add";
    jQuery.ajax({
        url: url,
        method:"POST",
        data:new FormData(this),
        dataType:'JSON',
        contentType: false,
        cache: false,
        processData: false,

        success:function(response) {
            if(response.errors) {
                if(response.errors.dealer_code){
                    jQuery( '#dealer-code-error' ).html( response.errors.dealer_code[0] );
                }
                if(response.errors.alternate_code){
                    jQuery( '#alternet-code-error' ).html( response.errors.alternate_code[0] );
                }
                if(response.errors.dealer_name){
                    jQuery( '#name-error' ).html( response.errors.dealer_name[0] );
                }
                if(response.errors.zone){
                    jQuery( '#zone-error' ).html( response.errors.zone[0] );
                }
                if(response.errors.dealer_phone_number){
                    jQuery( '#phone-error' ).html( response.errors.dealer_phone_number[0] );
                }
            }
            if(response == "error") {
                Notiflix.Notify.Failure( 'Dealer Save Failed' );
            }
            if(response == "success") {
                jQuery("#AddDealer")[0].reset();
                Notiflix.Notify.Success( 'Dealer Save Successfull' );
                return getDealerData();
            }

        },
        error:function(error) {
            jQuery("#AddDealer")[0].reset();
            Notiflix.Notify.Failure( 'Dealer Save Failed' );
        }
    });
});
// Edit Dealer Data
jQuery(document).on("click","#editDealerInfo",function(e){
  e.preventDefault();
  var DealerId = jQuery(this).data('id');
  //var url = "dealerinfo"+"/"+DealerId+"/edit";
  var url = "dealer.edit"+"/"+DealerId;
  jQuery.ajax({
    url:url,
    type:"GET",
    dataType:"JSON",

    success:function(response){
        if(response.id){
            jQuery('.dealerid').val(response.id);
        } else{
            jQuery('.dealerid').val(0);
        }
        
        if(response.dealer_code && response.alternate_code){
            jQuery('.dealercode').val(response.dealer_code).prop('readonly',true);
            jQuery('.alternetcode').val(response.alternate_code).prop('readonly',true);
        }else if(response.alternate_code) {
            jQuery('.dealercode').val(response.dealer_code);
            jQuery('.alternetcode').val(response.alternate_code).prop('readonly',true);
        }else if(response.dealer_code) {
            jQuery('.dealercode').val(response.dealer_code).prop('readonly',true);
            jQuery('.alternetcode').val(response.alternate_code);
        } else {
            jQuery('.dealercode').val(response.dealer_code);
            jQuery('.alternetcode').val(response.alternate_code);
        }

        //jQuery('.dealercode').val(response.dealer_code).prop('readonly',true);
        //jQuery('.alternetcode').val(response.alternate_code);
        jQuery('.dealername').val(response.dealer_name);
        jQuery('.zone').val(response.zone);
        jQuery('.city').val(response.city);
        jQuery('.division').val(response.division);
        jQuery('.dealerphone').val(response.dealer_phone_number);
        jQuery('.dealertype').val(response.dealer_type);
        jQuery('.dealeraddress').val(response.dealer_address);
    }
  });
});
// Update Dealer Data
jQuery('#UpdateDealerOld').on("submit", function(arg){
    jQuery.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
        }
    });
    arg.preventDefault();
    jQuery('#update-dealer-code-error').html("");
    jQuery('#update-alternet-code-error').html("");
    jQuery('#update-name-error').html("");
    jQuery('#update-zone-error').html("");
    jQuery('#update-phone-error').html("");


    var formData = new FormData(this);
    formData.append('_method', 'put');
  
    var dealerId    = jQuery('#dealer_id').val();
    var data        = jQuery("#UpdateDealer").serialize();
    
    jQuery.ajax({
        url:"dealer.update"+"/"+dealerId,
        type:"POST",
        data:formData,
        dataType:'JSON',
        cache: false,
        contentType: false,
        processData: false,
        success:function(response) {
            if(response.errors) {
                if(response.errors.dealer_code){
                    jQuery( '#update-dealer-code-error' ).html( response.errors.dealer_code[0] );
                }
                if(response.errors.alternate_code){
                    jQuery( '#update-alternet-code-error' ).html( response.errors.alternate_code[0] );
                }
                if(response.errors.dealer_name){
                    jQuery( '#update-name-error' ).html( response.errors.dealer_name[0] );
                }
                if(response.errors.zone){
                    jQuery( '#update-zone-error' ).html( response.errors.zone[0] );
                }
                if(response.errors.dealer_phone_number){
                    jQuery( '#update-phone-error' ).html( response.errors.dealer_phone_number[0] );
                }
            }

            if(response == "success"){
                Notiflix.Notify.Success( 'Dealer Update Successfull' );
                return getDealerData();
            }
            if(response == "error"){
                Notiflix.Notify.Failure( 'Dealer Update Failed' );
                //console.log(response);
            }
            
        }
    });
});
// Updat Dealer Data New
jQuery('#UpdateDealer').submit(function(e){
    e.preventDefault();
    jQuery('#dealer-code-error').html("");
    jQuery('#alternet-code-error').html("");
    jQuery('#name-error').html("");
    jQuery('#zone-error').html("");
    jQuery('#phone-error').html("");

    jQuery.ajax({
        url:"dealer.update",
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
            if(response.errors) {
                if(response.errors.dealer_code){
                    jQuery( '#dealer-code-error' ).html( response.errors.dealer_code[0] );
                }
                if(response.errors.alternate_code){
                    jQuery( '#alternet-code-error' ).html( response.errors.alternate_code[0] );
                }
                if(response.errors.dealer_name){
                    jQuery( '#name-error' ).html( response.errors.dealer_name[0] );
                }
                if(response.errors.zone){
                    jQuery( '#zone-error' ).html( response.errors.zone[0] );
                }
                if(response.errors.dealer_phone_number){
                    jQuery( '#phone-error' ).html( response.errors.dealer_phone_number[0] );
                }
            }
            if(response == "error") {
                Notiflix.Notify.Failure( 'Dealer Update Failed' );
            }
            if(response == "success") {
                Notiflix.Notify.Success( 'Dealer Update Successfull' );
                return getDealerData();
            }
        },
        error:function(error) {
            Notiflix.Notify.Failure( 'Dealer Update Failed' );
        }
    });
});
//API Search  By Dealer Code
jQuery(document).on("click","#search_dealer_button",function(e){
  e.preventDefault();
  var DealerCode = jQuery('#search_dealer_code').val();
  var url = "CheckDealerFromApi"+"/"+DealerCode;
  jQuery.ajax({
    url:url,
    type:"GET",
    dataType:"JSON",
    beforeSend: function() {
        Notiflix.Loading.Arrows('Data Processing');
        //Notiflix.Loading.Remove(400);
    },
    success:function(response){
        //console.log(response);
        if(response) {
            Notiflix.Loading.Remove(600);
            jQuery('.apidealerid').val(response.Id);
            jQuery('.apidcode').val(response.DealerCode).prop('readonly',true);
            jQuery('.apialtercode').val(response.ImportCode);
            jQuery('.apidname').val(response.DistributorNameCellCom);
            jQuery('.apidzone').val(response.Zone);
            jQuery('.apidphone').val(response.MobileNo);
            jQuery('.apidaddress').val(response.Address);

            if(response == 'success') {
                jQuery("#AddDealer")[0].reset();
                jQuery('.apidcode').prop('readonly',false);
                jQuery('#AddDealerModal').modal('hide');
                Notiflix.Notify.Success('Dealer Save Successfully');
            }  

        } 
        else {
            jQuery("#AddDealer")[0].reset();
            Notiflix.Notify.Failure( 'Dealer Not Found' );
            Notiflix.Loading.Remove(600);
        }

        if(response == 'empty' || response == 'error') {
            jQuery('.apidealerid').val(response.Id).prop('readonly',false);
            Notiflix.Notify.Info( 'Dealer Not Found! Please Try Another Dealer Id..' );
            Notiflix.Loading.Remove(600);
        }
        
    }
  });
});
//Add All Dealer By Api Calling
function ClickAddToDealerFormApi() {
    var url = "AddToDealerFormApi/";
    jQuery.ajax({
        url:url,
        type:"GET",
        dataType:"JSON",
        beforeSend: function() {
            Notiflix.Loading.Arrows('Data Processing');
            //Notiflix.Loading.Remove(400);
        },
        success:function(response)
        {
            console.log(response);
            if(response) 
            {
                Notiflix.Loading.Remove(400);
                if(response == 'success') {
                    Notiflix.Notify.Success('Dealer Save Successfully');
                    window.location.reload();
                    return getRetailerData();
                } 
                else 
                {
                    Notiflix.Notify.Warning( 'Dealer Not Found! Please Try Another Zone Id..' );
                }
            } 
            else 
            {
                Notiflix.Notify.Failure( 'Dealer Not Found' );
            }
            
        },
        complete:function(response)
        {
        // Hide image container
        Notiflix.Loading.Remove(600);
       }
    });
}
</script>
@endsection


@endsection

