@extends('admin.master.master')
@section('content')
<style type="text/css">
    .table-bordered th {
        border: 1px solid #e9ecef;
        font-size: 12px !important;
    }
    .mbtnSearch {
        margin-top: 30px;
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
            width: 130px;
            height: 33px;
        }
        .exportbtn {
            width: 130px;
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
            padding: 0.5rem 0rem !important;
            font-size: 1.5rem !important;
            width: 210px !important;
            height: 50px !important;
        }
        .table-bordered th {
            font-size: 23px !important;
            line-height: 1.3;
        }
        .main-content .form-group .form-control {
            padding: 0.5rem .75rem !important;
            font-size: 1rem !important;
        }
        .mbtnSearch {
            margin-top: 0px;
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
            height: 50px;
        }
    }
    /* Portrait and Landscape */
    @media only screen 
    and (min-device-width: 375px) 
    and (max-device-width: 812px) 
    and (-webkit-min-device-pixel-ratio: 3){
        body {
            font-size: 20px !important;
            color: #000000 !important;
        }
        .new-bp-css .btn {
            padding: 0.5rem 0rem !important;
            font-size: 1.5rem !important;
            width: 210px !important;
            height: 50px !important;
        }
        .table-bordered th {
            font-size: 20px !important;
            line-height: 1.3;
        }
        .main-content .form-group .form-control {
            padding: 0.5rem .75rem !important;
            font-size: 1rem !important;
        }
        .mbtnSearch {
            margin-top: 0px;
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
            height: 50px;
        }
    }
    @media (min-width: 768px) and (max-width: 1024px) {
        body {
            font-size: 20px !important;
            color: #000000 !important;
        }
        .new-bp-css .btn {
            padding: 0.5rem 0rem !important;
            font-size: 1.5rem !important;
            width: 210px !important;
            height: 50px !important;
        }
        .table-bordered th {
            font-size: 20px !important;
            line-height: 1.3;
        }
        .main-content .form-group .form-control {
            padding: 0.5rem .75rem !important;
            font-size: 1rem !important;
        }
        .mbtnSearch {
            margin-top: 0px;
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
            height: 50px;
        }
    }
</style>
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
            <form method="post" action="{{ url('preBookingReport') }}">
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

                    <div class="col-md-2 mb-3 bp-col-xs">
                        <input type="hidden" id="searchBpId" value="{{ (session()->get('search_bpid')) ? session()->get('search_bpid') : ''  }}">
                        <input type="hidden" id="searchRetailerId" value="{{ (session()->get('search_retailerid')) ? session()->get('search_retailerid') : ''  }}">
                        <input type="hidden" id="searchDealerId" value="{{ (session()->get('search_dealerid')) ? session()->get('search_dealerid') : ''  }}">
                        <input type="hidden" id="searchProductId" value="{{ (session()->get('search_productid')) ? session()->get('search_productid') : ''  }}">
                        <button type="submit" class="btn cur-p btn-primary btn-block mbtnSearch btn-float">Search</button>
                    </div>
                </div>
            </form>

            <div class="row" style="padding-bottom: 10px;">
                <div class="col-md-8">
                    <a href="javascript:void(0)" class="btn btn-primary cur-p btn-xs exportbtn" id="exportExcel">Export to Excel</a>
                    <a href="javascript:void(0)" class="btn btn-info cur-p btn-xs exportbtn" id="exportPdf">Export to Pdf</a>
                    
                    <br/>
                    @if(!empty($sellerName))
                    <div style="padding:10px">
                        <i class="c-light-blue-500 fa fa-user"></i> {{ $sellerName }}
                    </div>
                    @endif
                </div>
                <div class="col-md-4">
                    <div class="">
                        <input type="text" name="serach" id="serach" class="form-control customHt"/>
                    </div>
                </div>
            </div>
            <div id="tag_container" class="table-responsive">
                <table id="dataExport" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th class="sorting" data-sorting_type="asc" data-column_name="id" style="cursor: pointer;">Sl.</th>
                            <th class="sorting" data-sorting_type="asc" data-column_name="customer_name" style="cursor: pointer;">Customer Name</th>
                            <th class="sorting" data-sorting_type="asc" data-column_name="customer_phone" style="cursor: pointer;">Customer Phone</th>
                            <th class="sorting" data-sorting_type="asc" data-column_name="model" style="cursor: pointer;">Model</th>
                            <th class="sorting" data-sorting_type="asc" data-column_name="color" style="cursor: pointer;">Color</th>
                            <th class="sorting" data-sorting_type="asc" data-column_name="dealer_name" style="cursor: pointer;">Dealer Name</th>
                            <th class="sorting" data-sorting_type="asc" data-column_name="dealer_phone" style="cursor: pointer;">Dealer Phone</th>
                            <th class="sorting" data-sorting_type="asc" data-column_name="dealer_code" style="cursor: pointer;">Dealer Code</th>
                            <th class="sorting" data-sorting_type="asc" data-column_name="retailer_name" style="cursor: pointer;">Retailer Name</th>
                            <th class="sorting" data-sorting_type="asc" data-column_name="retailer_phone" style="cursor: pointer;">Retailer Phone</th>
                            <th class="sorting" data-sorting_type="asc" data-column_name="bp_name" style="cursor: pointer;">BP Name</th>
                            <th class="sorting" data-sorting_type="asc" data-column_name="bp_phone" style="cursor: pointer;">BP Phone</th>
                            <th class="sorting" data-sorting_type="asc" data-column_name="booking_date" style="cursor: pointer;">Order Date</th>
                            <th class="sorting" data-sorting_type="asc" data-column_name="order_qty" style="cursor: pointer;">Pre-Order Qty</th>
                            <th class="sorting" data-sorting_type="asc" data-column_name="amount" style="cursor: pointer;">Pre-Order Amount</th>
                            {{-- <th>Order Details</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @include('admin.report.prebooking_order_result_data')
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
<div class="modal fade" id="viewOrderSalesDetailsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Order Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>Customer Name</th>
                                        <th>Customer Phone</th>
                                        <th>Address</th>
                                        <th>Model</th>
                                        <th>Color</th>
                                        <th>Qty</th>
                                        <th>Advanced Payment</th>
                                        <th>Booking Date</th>
                                        <th>BP Name</th>
                                        <th>Retailer Name</th>
                                    </tr>
                                </thead>
                                <tbody id="orderList">

                                </tbody>
                            </table>
                        </div>

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
    var dealerId            = $('#searchDealerId').val();
    var searchProductId     = $('#searchProductId').val();
    var getStartDate        = $('.getStartDate').val();
    var getEndDate          = $('.getEndDate').val();
    var searchVal           = $('#serach').val();

    var searchData          = 'bpId='+bpId+'&retailerId='+retailerId+'&getStartDate='+getStartDate+'&getEndDate='+getEndDate+'&dealerId='+dealerId+'&searchProductId='+searchProductId+'&searchVal='+searchVal+'&type=excel';

    var url = "pre-booking-order-export?"+searchData;
    $('#exportExcel').attr('href',url);
});

jQuery(document).on("click","#exportPdf",function(e) {
    var bpId                = $('#searchBpId').val();
    var retailerId          = $('#searchRetailerId').val();
    var dealerId            = $('#searchDealerId').val();
    var searchProductId     = $('#searchProductId').val();
    var getStartDate        = $('.getStartDate').val();
    var getEndDate          = $('.getEndDate').val();
    var searchVal           = $('#serach').val();

    var searchData          = 'bpId='+bpId+'&retailerId='+retailerId+'&getStartDate='+getStartDate+'&getEndDate='+getEndDate+'&dealerId='+dealerId+'&searchProductId='+searchProductId+'&searchVal='+searchVal+'&type=pdf';

    var url = "pre-booking-order-export?"+searchData;
    $('#exportPdf').attr('href',url);
});
</script>

<!--Pagination Script Start-->
<script>
function clear_icon() {
    $('#id_icon').html('');
    $('#post_title_icon').html('');
}

function fetch_data(page, sort_type, sort_by, query) {
    $.ajax({
        url:"?page="+page+"&sortby="+sort_by+"&sorttype="+sort_type+"&query="+query,
        type:"get",
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

    jQuery(document).on('keyup', '#serach', function() {
        var getSearchVal = $('#serach').val();
        var length = getSearchVal.length;
        if(length >=3) {
            var query       = $('#serach').val();
            var column_name = $('#hidden_column_name').val();
            var sort_type   = $('#hidden_sort_type').val();
            var page        = $('#hidden_page').val();
            fetch_data(page, sort_type, column_name, query);
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
        $('li').removeClass('active');
        $(this).parent().addClass('active');
        fetch_data(page, sort_type, column_name, query);
    });
    // jQuery('.btnCloseModal').trigger('click');
    // jQuery('.btnCloseModal').mousedown();
    // jQuery('.close').click();
});
</script>
@endsection


@endsection