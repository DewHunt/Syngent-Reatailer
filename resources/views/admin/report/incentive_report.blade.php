@extends('admin.master.master')
@section('content')
<style>
    .mbtnSearch {
        margin-top: 30px;
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
            padding: 0rem !important;
            font-size: 1.5rem !important;
            width: 210px !important;
            height: 45px !important;
            float: left;
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
            margin-top: 53px;
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
        select.form-control:not([size]):not([multiple]) {
            height: 40px !important;
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
            padding: 0rem !important;
            font-size: 1.5rem !important;
            width: 210px !important;
            height: 45px !important;
            float: left;
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
            margin-top: 53px;
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
        select.form-control:not([size]):not([multiple]) {
            height: 40px !important;
        }
        
    }

    @media (min-width: 768px) and (max-width: 1024px) {
        body {
            font-size: 20px !important;
            color: #000000 !important;
        }
        .new-bp-css .btn {
            padding: 0rem !important;
            font-size: 1.5rem !important;
            width: 210px !important;
            height: 45px !important;
            float: left;
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
            margin-top: 53px;
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
        select.form-control:not([size]):not([multiple]) {
            height: 40px !important;
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
    
    @php 
    $active_status = 0;
    if(session()->get('search_catId') == 'general') {
        $active_status = 1;
    } else if (session()->get('search_catId') == 'target'){
        $active_status = 2;
    } 
    @endphp

    <div class="masonry-item col-md-9 mY-10">
        <div class="bgc-white p-20 bd new-bp-css">
            <form method="post" action="{{ url('incentive_report') }}">
                @csrf
                <div class="row">
                    <div class="form-group col-md-4 bp-col-sm">
                        <label>Group</label><span class="required">*</span>
                        <select class="form-control" name="incentive_category" required>
                            <option value="" selected="{{ ($active_status == 0)? 'selected':'' }}">Select Category</option>
                            <option value="general" selected="{{ ($active_status == 1)? 'selected':'' }}">General</option>
                            <option value="target" selected="{{ ($active_status == 2)? 'selected':'' }}">Target</option>
                        </select>
                    </div>

                    <div class="form-group col-md-4 bp-col-sm">
                        <label>BP</label>
                        <input type="text" id="bp_search" class="form-control ui-autocomplete-input" placeholder="Search By Name or Phone"/>
                        <input type="hidden" id='bp_id' name="bp_id" class="form-control"readonly>
                    </div>

                    <div class="form-group col-md-4 bp-col-sm">
                        <label>Retailer</label>
                        <input type="text" id="retailer_search" class="form-control ui-autocomplete-input" placeholder="Search By Name or Phone"/>
                        <input type="hidden" id='retailer_id' name="retailer_id" class="form-control"readonly>
                    </div>

                     <div class="form-group col-md-4 bp-col-sm">
                        <label>Start Date</label>
                        <input type="text" class="form-control datepicker getStartDate" name="start_date" value="{{ (session()->get('search_sdate')) ? session()->get('search_sdate') : date('Y-m-01')  }}">
                    </div>

                    <div class="form-group col-md-4 bp-col-sm">
                        <label>End Date</label>
                        <input type="text" class="form-control datepicker getEndDate" name="end_date" value="{{ (session()->get('search_edate')) ? session()->get('search_edate') : date('Y-m-t')  }}">
                    </div>

                    <div class="col-md-4 mb-3 bp-col-sm">
                        <input type="hidden" id="searchCat" value="{{ (session()->get('search_catId')) ? session()->get('search_catId') : ''  }}">
                        <input type="hidden" id="searchBpId" value="{{ (session()->get('search_bpId')) ? session()->get('search_bpId') : ''  }}">
                        <input type="hidden" id="searchRetailerId" value="{{ (session()->get('search_retailerId')) ? session()->get('search_retailerId') : ''  }}">
                        <button type="submit" class="btn cur-p btn-primary btn-block mbtnSearch btn-float">Search</button>
                    </div>
                </div>
            </form>
            @if(isset($salesIncentiveReportList))
            <div class="row" style="display:none">
                <div class="col-md-9">
                    {{-- 
                    <button id="btnPdf" class="btn btn-primary cur-p btn-xs">TO PDF</button>
                    <button class="btn btn-info cur-p btn-xs" onclick="ExportToExcel('xlsx')">TO Excel</button> 
                    --}}
                    <!--
                    <a href="javascript:void(0)" class="btn btn-primary cur-p btn-xs exportbtn" id="exportExcel">Export to Excel</a>
                    <a href="javascript:void(0)" class="btn btn-info cur-p btn-xs exportbtn" id="exportPdf">Export to Pdf</a>
                    -->
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <input type="text" name="serach" id="serach" class="form-control" />
                    </div>
                </div>
            </div>
            <div id="tag_container" class="table-responsive">
                <table id="example3" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Sl.</th>
                            <th>Incentive Type</th>
                            <th>IMEI 1</th>
                            <th>IMEI 2</th>
                            <th>Model</th>
                            <th>Dealer Name</th>
                            <th>Dealer Phone</th>
                            <th>Retailer Name</th>
                            <th>Retailer Phone</th>
                            <th>BP Name</th>
                            <th>BP Phone</th>
                            <th>Sales Qty</th>
                            <th>Incentive Amount</th>
                            <th>Sale Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @include('admin.report.incentive_report_result_data')
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>
</div>

<!--View Product Modal Start -->
<div class="modal fade" id="viewSaleIncentiveDetailsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Incentive Report Details <a href="javascript:void(0)" id="excelIncentiveDownload" class='btn btn-primary'>Download Excel</a></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-body">
                        <div id="incentiveReportList"></div>
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

@section('page-scripts')
<script type="text/javascript">
jQuery(document).on("click","#exportExcel",function(e){
    var cat             = $('#searchCat').val();
    var bpId            = $('#searchBpId').val();
    var retailerId      = $('#searchRetailerId').val();
    var getStartDate    = $('.getStartDate').val();
    var getEndDate      = $('.getEndDate').val();
    var searchVal       = $('#serach').val();
    
    var searchData      = "cat="+cat+'&bpId='+bpId+'&retailerId='+retailerId+'&getStartDate='+getStartDate+'&getEndDate='+getEndDate+'&searchVal='+searchVal+'&type=excel';

    var url = "sales-incentive-export?"+searchData;
    $('#exportExcel').attr('href',url);
});

jQuery(document).on("click","#exportPdf",function(e){
    var cat             = $('#searchCat').val();
    var bpId            = $('#searchBpId').val();
    var retailerId      = $('#searchRetailerId').val();
    var getStartDate    = $('.getStartDate').val();
    var getEndDate      = $('.getEndDate').val();
    var searchVal       = $('#serach').val();
    
    var searchData      = "cat="+cat+'&bpId='+bpId+'&retailerId='+retailerId+'&getStartDate='+getStartDate+'&getEndDate='+getEndDate+'&searchVal='+searchVal+'&type=pdf';

    var url = "sales-incentive-export?"+searchData;
    $('#exportPdf').attr('href',url);
});

jQuery(document).on("click","#bpSaleIncentiveDetails",function(e){
  e.preventDefault();
  $('#incentiveReportList').html('');
  $('#totalIncentiveAmount').html('');
  $('#totalQty').html('');
  var incentiveId = jQuery(this).data('id');
  var url = "incentiveDetailsView"+"/"+incentiveId+"/"+0;
  jQuery.ajax({
    url:url,
    type:"GET",
    dataType:"JSON",
    beforeSend: function() {
        Notiflix.Loading.Arrows('Data Processing');
    },
    success:function(response){
        console.log(response);
        if(response) 
        {
            Notiflix.Loading.Remove(300);
            jQuery('#incentiveReportList').append(response.incentiveReportList);
            jQuery('#totalIncentiveAmount').html(response.totalIncentiveAmount);
            jQuery('#totalQty').html(response.totalSaleQty);
            jQuery('#excelIncentiveDownload').attr('data-id',response.getId);
            
            var url = "bp-incentive-download?bpId="+response.getId;
            jQuery('#excelIncentiveDownload').attr('href',url);
        }
        if(response == "error")
        {
            Notiflix.Notify.Failure( 'Data Not Found' );
            setTimeout(function(){// wait for 5 secs(2)
                window.location.reload(); // then reload the page.(3)
                $(".btnCloseModal").click();
            }, 1000);
        }
    }
  });
});

jQuery(document).on("click","#retailSaleIncentiveDetails",function(e){
  e.preventDefault();
  $('#incentiveReportList').html('');
  jQuery('#totalIncentiveAmount').html('');
  jQuery('#totalQty').html('');
  var incentiveId = jQuery(this).data('id');
  var url = "incentiveDetailsView"+"/"+0+"/"+incentiveId;
  jQuery.ajax({
    url:url,
    type:"GET",
    dataType:"JSON",
    beforeSend: function() {
        Notiflix.Loading.Arrows('Data Processing');
    },
    success:function(response){
        console.log(response);
        if(response == "error"){
            Notiflix.Notify.Failure( 'Data Not Found' );
            setTimeout(function(){// wait for 5 secs(2)
                window.location.reload(); // then reload the page.(3)
                $(".btnCloseModal").click();
            }, 1000);
        }

        if(response) {
            Notiflix.Loading.Remove(300);
            console.log(response.type);
            jQuery('#incentiveReportList').append(response.incentiveReportList);
            jQuery('#totalIncentiveAmount').html(response.totalIncentiveAmount);
            jQuery('#totalQty').html(response.totalSaleQty);
            
            var url = "retailer-incentive-download?retailerId="+response.getId;
            jQuery('#excelIncentiveDownload').attr('href',url);
        }
    }
  });
});

function viewLargePhoto(photoID)
{
    var photoUrl = APP_URL+'/public/upload/client/'+photoID;
    $('#photoId').attr("src", photoUrl ); 
}
</script>


@endsection

@endsection