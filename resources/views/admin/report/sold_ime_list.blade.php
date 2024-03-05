@extends('admin.master.master')
@section('page-style')
<style>
    .select2-container .select2-selection--single {
      box-sizing: border-box;
      cursor: pointer;
      display: block;
      height: 35px;
      user-select: none;
      -webkit-user-select: none;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
      color: #444;
      line-height: 31px;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 35px;
        position: absolute;
        top: 1px;
        right: 1px;
        width: 20px;
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
            width: 280px;
            margin: 0px;
            padding-left: 0px !important;
            padding-right: 0px !important;
        }
        .eyeViewbtn {
            padding: 2px 6px;
            width: 100%;
            height: 33px;
        }
        .exportbtn {
            width: 20%;
        }
        .statusLabel {
            padding: 3px 5px !important;
            height: 30px;
            width: 100%;
        }
    }
     /* Portrait and Landscape */
    @media only screen 
    and (min-device-width: 320px) 
    and (max-device-width: 568px)
    and (-webkit-min-device-pixel-ratio: 2) {
            body {
            font-size: 23px !important;
            color: #000000 !important;
        }
        .new-bp-css .btn {
            padding: 0.7rem 0rem !important;
            font-size: 1.5rem !important;
            width: 210px !important;
            height: 60px !important;
        }
        .main-content .form-group .form-control {
            padding: 0.5rem .75rem !important;
            font-size: 1rem !important;
        }
        .main-content .form-group {
            margin-top: 0rem;
        }
        .bp-col-xs {
            -webkit-box-flex: 0;
            -ms-flex: 0 0 33.33333% !important;
            flex: 0 0 33.33333% !important;
            max-width: 33.33333% !important;
        }
        .bp-col-sm {
            -ms-flex: 0 0 50% !important;
            flex: 0 0 50% !important;
            max-width: 50% !important;
        }
        .btn-float {
            float: right;
        }
        .customHt {
            height: 60px;
        }
    }
    
    @media only screen and (min-device-width: 375px) 
    and (max-device-width: 812px) 
    and (-webkit-min-device-pixel-ratio: 3){
        body {
            font-size: 20px !important;
            color: #000000 !important;
        }
        .new-bp-css .btn {
            padding: 0.7rem 0rem !important;
            font-size: 1.5rem !important;
            width: 210px !important;
            height: 60px !important;
        }
        .main-content .form-group .form-control {
            padding: 0.5rem .75rem !important;
            font-size: 1rem !important;
        }
        .main-content .form-group {
            margin-top: 0rem;
        }
        .bp-col-xs {
            -webkit-box-flex: 0;
            -ms-flex: 0 0 33.33333% !important;
            flex: 0 0 33.33333% !important;
            max-width: 33.33333% !important;
        }
        .bp-col-sm {
            -ms-flex: 0 0 50% !important;
            flex: 0 0 50% !important;
            max-width: 50% !important;
        }
        .btn-float {
            float: right;
        }
        .customHt {
            height: 60px;
        }
    }
    
    @media (min-width: 768px) and (max-width: 1024px) {
        body {
            font-size: 20px !important;
            color: #000000 !important;
        }
        .new-bp-css .btn {
            padding: 0.7rem 0rem !important;
            font-size: 1.5rem !important;
            width: 210px !important;
            height: 60px !important;
        }
        .main-content .form-group .form-control {
            padding: 0.5rem .75rem !important;
            font-size: 1rem !important;
        }
        .main-content .form-group {
            margin-top: 0rem;
        }
        .bp-col-xs {
            -webkit-box-flex: 0;
            -ms-flex: 0 0 33.33333% !important;
            flex: 0 0 33.33333% !important;
            max-width: 33.33333% !important;
        }
        .bp-col-sm {
            -ms-flex: 0 0 50% !important;
            flex: 0 0 50% !important;
            max-width: 50% !important;
        }
        .btn-float {
            float: right;
        }
        .customHt {
            height: 60px;
        }
    }
</style>
@endsection

@section('content')
<h4 class="c-grey-900 mB-20">Report Dashboard</h4>
<div class="row">
    <div class="masonry-item col-md-3 mY-10">
        <div class="bd bgc-white">
            <div class="layers">
                <div class="layer w-100">
                    <div class="list-group">
                        @include('admin.report.report_menu')
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="masonry-item col-md-9 mY-10">
        <div class="bgc-white p-20 bd new-bp-css">
            <form method="post" action="{{ url('report.soldimei-search-report') }}">
                @csrf
                <div class="row">
                    <div class="form-group col-md-4 bp-col-sm">
                        <label>Dealer</label>
                        <input type="text" id="dealer_search_list" class="form-control ui-autocomplete-input" placeholder="Search By Name or Phone"/>
                        <input type="hidden" id='dealer_code' name="dealer_code" class="form-control"readonly>
                    </div>

                    <div class="form-group col-md-4 bp-col-sm">
                        <label>Retailer</label>
                        <input type="text" id="retailer_search" class="form-control ui-autocomplete-input" placeholder="Search By Name or Phone"/>
                        <input type="hidden" id='retailer_id' name="retailer_id" class="form-control"readonly>
                    </div>

                    <div class="form-group col-md-4 bp-col-sm">
                        <label>BP</label>
                        <input type="text" id="bp_search" class="form-control ui-autocomplete-input" placeholder="Search By Name or Phone"/>
                        <input type="hidden" id='bp_id' name="bp_id" class="form-control"readonly>
                    </div>

                    <div class="form-group col-md-4 bp-col-sm">
                        <label>Model</label>
                        <input type="text" id="model_search" class="form-control ui-autocomplete-input" placeholder="Search By Model"/>
                        <input type="hidden" id='product_id' name="product_id" class="form-control"readonly>
                    </div>

                    <div class="form-group col-md-3 bp-col-sm">
                        <label>Start Date</label>
                        <input type="text" class="form-control datepicker getStartDate" name="start_date" value="{{ (session()->get('search_sdate')) ? session()->get('search_sdate') : date('Y-m-01')  }}">
                    </div>

                    <div class="form-group col-md-3 bp-col-sm">
                        <label>End Date</label>
                        <input type="text" class="form-control datepicker getEndDate" name="end_date" value="{{ (session()->get('search_edate')) ? session()->get('search_edate') : date('Y-m-t')  }}">
                    </div>

                    <div class="col-md-2 mb-3 bp-col-xs" style="margin-top: 30px;">
                        <input type="hidden" id="searchBpId" value="{{ (session()->get('salesBPId')) ? session()->get('salesBPId') : ''  }}">
                        <input type="hidden" id="searchRetailerId" value="{{ (session()->get('salesRetailerId')) ? session()->get('salesRetailerId') : ''  }}">
                        <input type="hidden" id="searchDealerCode" value="{{ (session()->get('salesDealerCode')) ? session()->get('salesDealerCode') : ''  }}">
                        <input type="hidden" id="searchProductId" value="{{ (session()->get('salesProductId')) ? session()->get('salesProductId') : ''  }}">
                        <button type="submit" class="btn cur-p btn-primary btn-block mbtnSearch btn-float">Search</button>
                    </div>
                </div>
            </form>
            <!--
            <div class="row" style="padding-bottom:10px">
                <div class="col-md-8">
                    <a href="javascript:void(0)" class="btn btn-primary cur-p btn-xs exportbtn" id="exportExcel">Export to Excel</a>
                    <a href="javascript:void(0)" class="btn btn-info cur-p btn-xs exportbtn" id="exportPdf">Export to Pdf</a>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <input type="text" name="serach" id="serach" class="form-control customHt"/>
                    </div>
                </div>
            </div>
            -->
            
            <div class="row" style="padding-bottom:10px">
                <div class="col-md-2">
                    <a href="javascript:void(0)" class="btn btn-block btn-primary cur-p btn-xs exportbtn" id="exportExcel">Export to Excel</a>
                </div>
                <div class="col-md-2">
                    <a href="javascript:void(0)" class="btn btn-block btn-info cur-p btn-xs exportbtn" id="exportPdf">Export to Pdf</a>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <select class="select2" style="width: 100%;" id="search_field_name" name="search_field_name">
                            <option value="">Select</option>
                            <option value="ime_number" selected>IMEI Number</option>
                            <option value="product_model">Product Model</option>
                            <option value="dealer_name">Dealer Name</option>
                            <option value="dealer_phone_number">Dealer Phone</option>
                            <option value="dealer_code">Dealer Code</option>
                            <option value="retailer_name">Retailer Name</option>
                            <option value="retailer_phone_number">Retailer Phone</option>
                            <option value="bp_name">BP Name</option>
                            <option value="bp_phone">BP Phone</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <input type="text" name="serach" id="serach" class="form-control customHt"/>
                    </div>
                </div>

            </div>
            
            
            <div id="tag_container" class="table-responsive">
				<table id="dataExport" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th class="sorting" data-sorting_type="asc" data-column_name="id" style="cursor: pointer;">Sl.</th>
                            <th class="sorting" data-sorting_type="asc" data-column_name="imei_number" style="cursor: pointer;">IMEI 1</th>
                            <th class="sorting" data-sorting_type="asc" data-column_name="alternate_imei" style="cursor: pointer;">IMEI 2</th>
                            <th class="sorting" data-sorting_type="asc" data-column_name="sale_date" style="cursor: pointer;">Model</th>
                            <th class="sorting" data-sorting_type="asc" data-column_name="sale_date" style="cursor: pointer;">Sales Date</th>
                            <th class="sorting" data-sorting_type="asc" data-column_name="sale_date" style="cursor: pointer;">Sales Amount</th>

                            <th class="sorting" data-sorting_type="asc" data-column_name="dealer_name" style="cursor: pointer;">Dealer Name</th>
                            <th class="sorting" data-sorting_type="asc" data-column_name="dealer_phone" style="cursor: pointer;">Dealer Phone</th>
                            <th class="sorting" data-sorting_type="asc" data-column_name="dealer_code" style="cursor: pointer;">Dealer Code</th>

                            <th class="sorting" data-sorting_type="asc" data-column_name="retailer_name" style="cursor: pointer;">Retailer Name</th>
                            <th class="sorting" data-sorting_type="asc" data-column_name="retailer_phone" style="cursor: pointer;">Retailer Phone</th>

                            <th class="sorting" data-sorting_type="asc" data-column_name="bp_name" style="cursor: pointer;">BP Name</th>
                            <th class="sorting" data-sorting_type="asc" data-column_name="bp_phone" style="cursor: pointer;">BP Phone</th>

                            {{-- <th>Action</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @include('admin.report.sold_ime_result_data')
                    </tbody>
                </table>
                <input type="hidden" name="hidden_page" id="hidden_page" value="1" />
                <input type="hidden" name="hidden_column_name" id="hidden_column_name" value="id" />
                <input type="hidden" name="hidden_sort_type" id="hidden_sort_type" value="asc" />
            </div>
        </div>
    </div>
</div>

<!--View Product Modal Start -->
<div class="modal fade" id="viewProductModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Product Info</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
                            <tr>
                                <th>Model</th>
                                <td><span class="product_model"></span></td>
                            </tr>
                            <tr>
                                <th>Code</th>
                                <td><span class="product_code"></span></td>
                            </tr>
                            <tr>
                                <th>Type</th>
                                <td><span class="product_type"></span></td>
                            </tr>
                            <tr>
                                <th>Mrp Price</th>
                                <td><span class="mrp_price"></span></td>
                            </tr>
                            <tr>
                                <th>Msdp Price</th>
                                <td><span class="msdp_price"></span></td>
                            </tr>
                            <tr>
                                <th>Msrp Price</th>
                                <td><span class="msrp_price"></span></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btnCloseModal" data-dismiss="modal">Close</button>  
            </div>
        </div>
    </div>
</div>
<!--View Product Modal End -->

@section('page-scripts')
<script type="text/javascript">
jQuery(document).on("click","#exportExcel",function(e) {
    var bpId                = $('#searchBpId').val();
    var retailerId          = $('#searchRetailerId').val();
    var dealerId            = $('#searchDealerCode').val();
    var searchProductId     = $('#searchProductId').val();
    var getStartDate        = $('.getStartDate').val();
    var getEndDate          = $('.getEndDate').val();
    var searchVal           = $('#serach').val();
    var field               = $('#search_field_name').val();

    var searchData          = 'bpId='+bpId+'&retailerId='+retailerId+'&getStartDate='+getStartDate+'&getEndDate='+getEndDate+'&dealerId='+dealerId+'&searchProductId='+searchProductId+'&field='+field+'&searchVal='+searchVal+'&type=excel';
    var url = "sold-imei-export?"+searchData;
    $('#exportExcel').attr('href',url);
});
jQuery(document).on("click","#exportPdf",function(e) {
    var bpId                = $('#searchBpId').val();
    var retailerId          = $('#searchRetailerId').val();
    var dealerId            = $('#searchDealerCode').val();
    var searchProductId     = $('#searchProductId').val();
    var getStartDate        = $('.getStartDate').val();
    var getEndDate          = $('.getEndDate').val();
    var searchVal           = $('#serach').val();
    var field               = $('#search_field_name').val();
    
    var searchData     = 'bpId='+bpId+'&retailerId='+retailerId+'&getStartDate='+getStartDate+'&getEndDate='+getEndDate+'&dealerId='+dealerId+'&searchProductId='+searchProductId+'&field='+field+'&searchVal='+searchVal+'&type=pdf';

    var url = "sold-imei-export?"+searchData;
    $('#exportPdf').attr('href',url);
});
</script>

<!--Pagination Script Start-->
<script>
function clear_icon() {
    $('#id_icon').html('');
    $('#post_title_icon').html('');
}

function fetch_data(page, sort_type, sort_by, field, query) {
    $.ajax({
        //url:"?page="+page+"&sortby="+sort_by+"&sorttype="+sort_type+"&query="+query,
        url:"?page="+page+"&sortby="+sort_by+"&sorttype="+sort_type+"&field="+field+"&query="+query,
        type:"get",
        success:function(data) {
            console.log(data);
            $('tbody').html('');
            $('tbody').html(data);

            /*$(this).data('toggle-on', true);
            jQuery('.toggle').each(function() {
                $(this).toggles({
                    on: $(this).data('toggle-on')
                });
            });
            jQuery('input[type=checkbox][data-toggle^=toggle]').bootstrapToggle();
            var toggleJs  = APP_URL+"/public/admin/js/custom-js/toggle-information.js";
            jQuery.getScript(toggleJs);*/
            
        }
    })
}

jQuery(document).ready(function() {

    var searhText = document.getElementById('serach');
    searhText.onkeydown = function() {
        var key = event.keyCode || event.charCode;
        if( key == 8 ) {
            var getSearchVal = $('#serach').val();
            var length = getSearchVal.length;
            if(length <= 1) {
                var query       = $('#serach').val();
                var column_name = $('#hidden_column_name').val();
                var sort_type   = $('#hidden_sort_type').val();
                var page        = $('#hidden_page').val();
                fetch_data(page, sort_type, column_name, query);
            }
        }
    };

    //jQuery(document).on('keyup', '#serach', function() {
    $("#serach").blur(function() {
        var getSearchVal    = $('#serach').val();
        var length          = getSearchVal.length;
        if(length >=3) {
            var query       = $('#serach').val();
            var column_name = $('#hidden_column_name').val();
            var sort_type   = $('#hidden_sort_type').val();
            var page        = $('#hidden_page').val();
            var field       = $('#search_field_name').val();
            fetch_data(page, sort_type, column_name, field, query);
        }
        else
        {
            if(length >= 1 && length <= 2) {
                alert('Enter Minimum 3 or above alphabet');
            }

            if(length == 0) {
                var query       = $('#serach').val();
                var column_name = $('#hidden_column_name').val();
                var sort_type   = $('#hidden_sort_type').val();
                var page        = $('#hidden_page').val();
                var field       = $('#search_field_name').val();
                fetch_data(page, sort_type, column_name, field, query);
            }
        }
    });

    jQuery(document).on('click', '.sorting', function(){
        var column_name     = $(this).data('column_name');
        var order_type      = $(this).data('sorting_type');
        var reverse_order   = '';
        if(order_type == 'asc') {
            $(this).data('sorting_type', 'desc');
            reverse_order = 'desc';
            clear_icon();
            $('#'+column_name+'_icon').html('<span class="glyphicon glyphicon-triangle-bottom"></span>');
        }
        if(order_type == 'desc') {
            $(this).data('sorting_type', 'asc');
            reverse_order = 'asc';
            clear_icon
            $('#'+column_name+'_icon').html('<span class="glyphicon glyphicon-triangle-top"></span>');
        }
        $('#hidden_column_name').val(column_name);
        $('#hidden_sort_type').val(reverse_order);
        var page    = $('#hidden_page').val();
        var query   = $('#serach').val();
        fetch_data(page, reverse_order, column_name, query);
    });

    jQuery(document).on('click', '.pagination a', function(event){
        event.preventDefault();
        var page = $(this).attr('href').split('page=')[1];
        $('#hidden_page').val(page);
        var column_name = $('#hidden_column_name').val();
        var sort_type   = $('#hidden_sort_type').val();
        var query       = $('#serach').val();
        var field       = $('#search_field_name').val();
        $('li').removeClass('active');
        $(this).parent().addClass('active');
        fetch_data(page, sort_type, column_name, field, query);
    });
});
</script>
@endsection


@endsection

