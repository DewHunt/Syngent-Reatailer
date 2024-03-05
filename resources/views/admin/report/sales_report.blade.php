@php
    $search_sdate = session()->get('salesSdate');
    if (empty($search_sdate)) {
        $search_sdate = date('Y-m-01');
    }
    $search_edate = session()->get('salesEdate');
    if (empty($search_edate)) {
        $search_edate = date('Y-m-t');
    }
@endphp

@extends('admin.master.master')

@section('page-style')
    <style type="text/css">
        .table-bordered th { border: 1px solid #e9ecef; font-size: 12px !important; }
        .mbtnSearch { margin-top: 30px; }
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
            .beforeAddBtn{ padding: 0px 7px; }
            .newAddBtn{
                margin-left: 5px;
                width: 280px;
                margin: 0px;
                padding-left: 0px !important;
                padding-right: 0px !important;
            }
            .eyeViewbtn { padding: 2px 6px; width: 100px; height: 33px; }
            .exportbtn { width: 20%; }
            .statusLabel { padding: 3px 5px !important; height: 30px; width: 100%; }
            .badge { width: 100px; height: 35px; font-size: 12px; }
        }
        /* Portrait and Landscape */
        @media only screen 
        and (min-device-width: 320px) 
        and (max-device-width: 568px)
        and (-webkit-min-device-pixel-ratio: 2) {
            body { font-size: 23px !important; color: #000000 !important; }
            .new-bp-css .btn {
                padding: 1rem !important;
                font-size: 1.5rem !important;
                width: 210px !important;
                height: 65px !important;
            }
            .table-bordered th { font-size: 23px !important; line-height: 1.3; }
            .main-content .form-group .form-control { padding: 0.5rem .75rem !important; font-size: 1rem !important; }
            .mbtnSearch { margin-top: 53px; }
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
            .btn-float { float: right; }
            .badge { width: 210px; height: 65px; font-size: 20px; }
            .customHt { height: 60px; }
        }
        /* Portrait and Landscape */
        @media only screen 
        and (min-device-width: 375px) 
        and (max-device-width: 812px) 
        and (-webkit-min-device-pixel-ratio: 3){
            body { font-size: 20px !important; color: #000000 !important; }
            .new-bp-css .btn {
                padding: 1rem !important;
                font-size: 1.5rem !important;
                width: 210px !important;
                height: 65px !important;
            }
            .table-bordered th { font-size: 20px !important; line-height: 1.3; }
            .main-content .form-group .form-control { padding: 0.5rem .75rem !important; font-size: 1rem !important; }
            .mbtnSearch { margin-top: 53px; }
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
            .btn-float { float: right; }
            .badge { width: 210px; height: 65px; font-size: 20px; }
            .customHt { height: 60px; }
        }
        @media (min-width: 768px) and (max-width: 1024px) {
            body { font-size: 20px !important; color: #000000 !important; }
            .new-bp-css .btn {
                padding: 0.9rem !important;
                font-size: 1.5rem !important;
                width: 210px !important;
                height: 65px !important;
            }
            .table-bordered th { font-size: 20px !important; line-height: 1.3; }
            .main-content .form-group .form-control { padding: 0.5rem .75rem !important; font-size: 1rem !important; }
            .mbtnSearch { margin-top: 53px; }
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
            .btn-float { float: right; }
            .badge { width: 210px; height: 65px; font-size: 20px; }
            .customHt { height: 60px; }
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
                <form method="post" action="{{ route('report.sales-invoice') }}">
                    @csrf
                    <div class="row" style="display: none;">
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="form-group">
                                <label>BP</label>
                                <input type="text" id="bp_search" class="form-control ui-autocomplete-input" placeholder="Search By Name or Phone"/>
                                <input type="hidden" id='bp_id' name="bp_id" class="form-control"readonly>
                            </div>
                        </div>
                        
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="form-group">
                                <label>Retailer</label>
                                <input type="text" id="retailer_search" class="form-control ui-autocomplete-input" placeholder="Search By Name or Phone"/>
                                <input type="hidden" id='retailer_id' name="retailer_id" class="form-control"readonly>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 col-12">
                            <div class="form-group">
                                <label class="form-label">Date</label>
                                <div id="daterange-div" class="form-control daterange-style">
                                    <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                                    <span id="changed_date" class="changed_date"></span> <b class="caret"></b>
                                    <div class="date-input-div">
                                        <input class="from-date getStartDate" type="hidden" name="from_date" value="">
                                        <input class="to-date getEndDate" type="hidden" name="to_date" value="">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
                            <div class="form-group">
                                <label>Start Date</label>
                                <input type="text" class="form-control datepicker getStartDate" name="start_date" value="{{ $search_sdate  }}">
                            </div>
                        </div>

                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
                            <div class="form-group">
                                <label>End Date</label>
                                <input type="text" class="form-control datepicker getEndDate" name="end_date" value="{{ $search_edate }}">
                            </div>
                        </div> --}}
                        
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
                            <div class="form-group">
                                <button type="submit" class="btn cur-p btn-primary btn-block mbtnSearch btn-float">Search</button>
                            </div>
                        </div>

                        <input type="hidden" id="searchBpId" value="{{ (session()->get('salesBPId')) ? session()->get('salesBPId') : ''  }}">
                        <input type="hidden" id="searchRetailerId" value="{{ (session()->get('salesRetailerId')) ? session()->get('salesRetailerId') : ''  }}">
                    </div>
                </form>

                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <a href="javascript:void(0)" class="btn btn-primary btn-xs" id="exportExcel">Export to Excel</a>
                            <a href="javascript:void(0)" class="btn btn-info btn-xs" id="exportPdf">Export to Pdf</a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <input type="text" name="serach" id="serach" class="form-control customHt"/>
                        </div>
                    </div>
                </div>

                <div class="result-data-div">@include('admin.report.sales_report_result_data')</div>                
            </div>
        </div>
    </div>

    <!--View Product Modal Start -->
    <div class="modal fade" id="viewOrderDetailsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Order Invoice</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="salesInfo"></div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="sale_id" id="saleId">
                    <button type="button" class="btn btn-primary" id="salesReturn">Sales Return</button>
                    <button type="button" class="btn btn-secondary btnCloseModal" data-dismiss="modal">Close</button>  
                </div>
            </div>
        </div>
    </div>
    <!--View Product Modal End -->

    <!--Photo View Modal Start -->
    <div class="modal fade" id="viewPhotoModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-body">
                            <img id="photoId" src="" width="425" height="350" alt="photo"/>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btnCloseModal" data-dismiss="modal">Close</button>  
                </div>
            </div>
        </div>
    </div>
    <!--Photo View Modal End -->
@endsection

@section('page-scripts')
    <script type="text/javascript">
        var start = "<?= $search_sdate ?>";
        var end = "<?= $search_edate ?>";
        console.log('Start',start);
        if (start) {
            start = moment(start);
        } else {
            var start = moment().clone().startOf('month');
        }

        if (end) {
            end = moment(end);
        } else {
            var end = moment().clone().endOf('month');
        }
        show_daterange(start,end);

        jQuery(document).on("click","#exportExcel",function(e){
            var searchVal = $('#serach').val();
            var bpId = $('#searchBpId').val();
            var retailerId = $('#searchRetailerId').val();
            var getStartDate = $('.getStartDate').val();
            var getEndDate = $('.getEndDate').val();
            var searchData = "bpId="+bpId+"&retailerId="+retailerId+'&getStartDate='+getStartDate+'&getEndDate='+getEndDate+'&searchVal='+searchVal+'&type=excel';
            var url = "sales-invoice-export?"+searchData;
            $('#exportExcel').attr('href',url);
        });

        jQuery(document).on("click","#exportPdf",function(e){
            var searchVal = $('#serach').val();
            var bpId = $('#searchBpId').val();
            var retailerId = $('#searchRetailerId').val();
            var getStartDate = $('.getStartDate').val();
            var getEndDate = $('.getEndDate').val();
            var searchData = "bpId="+bpId+"&retailerId="+retailerId+'&getStartDate='+getStartDate+'&getEndDate='+getEndDate+'&searchVal='+searchVal+'&type=pdf';

            var url = "sales-invoice-export?"+searchData;
            $('#exportPdf').attr('href',url);
        });

        // Order Details Data View
        jQuery(document).on("click","#orderDetailsView",function(e){
            e.preventDefault();
            $('#salesInfo').html("");
            var saleId = jQuery(this).data('id');
            jQuery.ajax({
                type: "POST",
                url:"{{ route('OrderDetailsView') }}",
                data:{_token:'{{ csrf_token() }}',saleId},
                beforeSend: function() {
                    Notiflix.Loading.Arrows('Data Processing');
                },
                success:function(response) {
                    console.log(response);
                    if (response) {
                        Notiflix.Loading.Remove(100);
                        jQuery('#salesInfo').html(response.orderDetailsInfo);
                        jQuery('#saleId').val(response.saleId);
                    }
                    Notiflix.Loading.Remove(300);
                    if (response == "error") {
                        Notiflix.Notify.Failure( 'Data Not Found' );
                        // setTimeout(function() {
                        //     window.location.reload();
                        //     $(".btnCloseModal").click();
                        // }, 1000);
                    }
                }
            });
        });

        // Sales Return
        jQuery(document).on("click","#salesReturn",function(e) {
            e.preventDefault();
            var orderId = $('#saleId').val();
            var url = "salesReturn"+"/"+orderId;
            jQuery.ajax({
                url:url,
                type:"GET",
                dataType:"JSON",
                success:function(response) {
                    console.log(response);
                    Notiflix.Loading.Remove(300);
                    if (response == "success") {
                        Notiflix.Notify.Success('Sales Return Successfully');
                        setTimeout(function() {// wait for 5 secs(2)
                            window.location.reload(); // then reload the page.(3)
                            $(".btnCloseModal").click();
                        }, 1000);
                    }
                    if (response == "error") {
                        Notiflix.Notify.Failure('Sales Return Failed');
                        setTimeout(function() {// wait for 5 secs(2)
                            window.location.reload(); // then reload the page.(3)
                            $(".btnCloseModal").click();
                        }, 1000);
                    }
                },
                error:function(error){
                  Notiflix.Notify.Failure('Sales Return Failed');
                }
            });
        });

        function viewLargePhoto(photoID) {
            var photoUrl = APP_URL+'/'+photoID;
            $('#photoId').attr("src", photoUrl ); 
        }

        // Pagination Script Start
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
                    $('.result-data-div').html('');
                    $('.result-data-div').html(data);
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
        });

        jQuery(document).on('keyup', '#serach', function() {
            var getSearchVal = $('#serach').val();
            var length = getSearchVal.length;
            if (length >=3) {
                var query = $('#serach').val();
                var column_name = $('#hidden_column_name').val();
                var sort_type = $('#hidden_sort_type').val();
                var page = $('#hidden_page').val();
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
            if(order_type == 'desc') {
                $(this).data('sorting_type', 'asc');
                reverse_order = 'asc';
                clear_icon();
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
    </script>
@endsection