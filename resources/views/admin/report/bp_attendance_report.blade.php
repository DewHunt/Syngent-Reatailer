@extends('admin.master.master')
@section('content')
<style>
    .mbtnSearch {
        margin-top: 30px;
    }
    .badge{
        height: auto;
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
            width: 100px;
            height: 33px;
        }
        .exportbtn {
            width: 17%;
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
            padding: 0rem 0rem !important;
            font-size: 1.5rem !important;
            width: 210px !important;
            height: 60px !important;
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
            float: left;
        }
        .badge {
            font-size: 100%;
            width: 130px;
            height: 60px;
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
            padding: 0rem 0rem !important;
            font-size: 1.5rem !important;
            width: 210px !important;
            height: 60px !important;
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
            float: left;
        }
        .badge {
            font-size: 100%;
            width: 130px;
            height: 60px;
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
            padding: 0rem 0rem !important;
            font-size: 1.5rem !important;
            width: 210px !important;
            height: 60px !important;
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
            float: left;
        }
        .badge {
            font-size: 100%;
            width: 130px;
            height: 60px;
        }
        select.form-control:not([size]):not([multiple]) {
            height: 40px !important;
        }
    }
</style>
@php 
$getOrderBy = Session::get('attendance_orderby');
$getDate    = Session::get('search_sdate');
@endphp
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
            <form method="post" action="{{ url('bp_attendance_report') }}">
                @csrf
                <div class="row">
                    <div class="form-group col-md-4 bp-col-sm">
                        <label>BP</label>
                        <input type="text" id="bp_search" class="form-control ui-autocomplete-input" placeholder="Search By Name or Phone"/>
                        <input type="hidden" id='bp_id' name="bp_id" class="form-control"readonly>
                    </div>

                    <div class="form-group col-md-2 bp-col-sm">
                        <label>Start Date</label>
                        <input type="text" class="form-control datepicker getStartDate" name="start_date" value="{{ (session()->get('search_sdate')) ? session()->get('search_sdate') : date('Y-m-d')  }}">
                    </div>

                    <div class="form-group col-md-2 bp-col-sm">
                        <label>End Date</label>
                        <input type="text" class="form-control datepicker getEndDate" name="end_date" value="{{ (session()->get('search_edate')) ? session()->get('search_edate') : date('Y-m-d')  }}">
                    </div>

                    @php
                    $orderBy = Request::segment(2);
                    @endphp

                    <div class="form-group col-md-2 bp-col-sm bp-select">
                        <label>Data Ordering</label>
                        <select class="form-control" style="width: 100%;" name="order_by">
                            <option value="all" @if($getOrderBy == 'all') selected @endif>All</option>
                            <option value="present" @if($getOrderBy == 'present') selected @endif>Present</option>
                            <option value="absent" @if($getOrderBy == 'absent') selected @endif>Absent</option>
                        </select>

                        {{-- 
                        <a href="javascript:void(0)" id="dataViewOrderBy">
                            <select class="form-control" style="width: 100%;" name="order_by" id="attendance_order_by">
                                <option value="all" selected>All</option>
                                <option value="present" @if($orderBy == 'present') selected @endif>Present</option>
                                <option value="absent" @if($orderBy == 'absent') selected @endif>Absent</option>
                            </select>
                        </a> 
                        --}}
                    </div>


                    <div class="col-md-2 mb-3 bp-col-sm">
                        <input type="hidden" id="searchBpId" value="{{ (session()->get('search_bpid')) ? session()->get('search_bpid') : ''  }}">
                        <button type="submit" class="btn cur-p btn-primary btn-block mbtnSearch btn-float">Search</button>
                    </div>


                </div>
            </form>


            <div class="row" style="display:none">
                <div class="col-md-9">
                    {{-- 
                    <button id="btnPdf" class="btn btn-primary cur-p btn-xs exportbtn">TO PDF</button>
                    <button class="btn btn-info cur-p btn-xs exportbtn" onclick="ExportToExcel('xlsx')">TO Excel</button> 
                    --}}

                    <a href="javascript:void(0)" class="btn btn-primary cur-p btn-xs exportbtn" id="exportExcel">Export to Excel</a>
                    <a href="javascript:void(0)" class="btn btn-info cur-p btn-xs exportbtn" id="exportPdf">Export to Pdf</a>
                </div>
                <div class="col-md-3">
                    {{-- <div class="form-group">
                        <input type="text" name="serach" id="serach" class="form-control" />
                    </div> --}}
                </div>
            </div>
            <div id="tag_container" class="table-responsive">
                <table id="attendanceData" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th class="sorting" data-sorting_type="asc" data-column_name="id" style="cursor: pointer;">Sl.</th>
                            <th>Selfi Photo</th>
                            <th class="sorting" data-sorting_type="asc" data-column_name="bp_name" style="cursor: pointer;">BP Name</th>
                            <th class="sorting" data-sorting_type="asc" data-column_name="bp_phone" style="cursor: pointer;">BP Phone</th>
							<th class="sorting" data-sorting_type="asc" data-column_name="distributor_name" style="cursor: pointer;">Dealer Name</th>
							<th class="sorting" data-sorting_type="asc" data-column_name="distributor_code" style="cursor: pointer;">Dealer Code</th>
                            <th class="sorting" data-sorting_type="asc" data-column_name="in_status" style="cursor: pointer;">In Status</th>
                            <th class="sorting" data-sorting_type="asc" data-column_name="id" style="cursor: pointer;">Out Status</th>
                            <th class="sorting" data-sorting_type="asc" data-column_name="id" style="cursor: pointer;">Location</th>
                            <th class="sorting" data-sorting_type="asc" data-column_name="id" style="cursor: pointer;">Attendance Date</th>
                            <th class="sorting" data-sorting_type="asc" data-column_name="id" style="cursor: pointer;">In Time & Location</th>
                            <th class="sorting" data-sorting_type="asc" data-column_name="id" style="cursor: pointer;">Out Time & Location</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        @include('admin.report.bp_attendance_report_result_data')
                    </tbody>
                </table>
                <input type="hidden" name="hidden_page" id="hidden_page" value="1" />
                <input type="hidden" name="hidden_column_name" id="hidden_column_name" value="id" />
                <input type="hidden" name="hidden_sort_type" id="hidden_sort_type" value="asc" />
            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="viewAttendanceDetailsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Attendance Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-body">
                        <div id="attendanceView"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btnCloseModal" data-dismiss="modal">Close</button>  
            </div>
        </div>
    </div>
</div>


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
    var bpId            = $('#searchBpId').val();
    var getStartDate    = $('.getStartDate').val();
    var getEndDate      = $('.getEndDate').val();
    var searchVal       = $('#serach').val();


    var searchData      = "bpId="+bpId+'&getStartDate='+getStartDate+'&getEndDate='+getEndDate+'&searchVal='+searchVal+'&type=excel';
    var url = "bp-attendance-export?"+searchData;
    $('#exportExcel').attr('href',url);
});

jQuery(document).on("click","#exportPdf",function(e){
    var bpId           = $('#searchBpId').val();
    var getStartDate   = $('.getStartDate').val();
    var getEndDate     = $('.getEndDate').val();
    var searchVal      = $('#serach').val();


    var searchData     = "bpId="+bpId+'&getStartDate='+getStartDate+'&getEndDate='+getEndDate+'&searchVal='+searchVal+'&type=pdf';
    var url = "bp-attendance-export?"+searchData;
    $('#exportPdf').attr('href',url);
});

jQuery(document).on('change','#attendance_order_by',function(e){
    //e.preventDefault();
    var orderBy = $(this).val();
    var url =  "{{url('getOrderByAttendance')}}"+"/"+orderBy;
    $('#dataViewOrderBy').attr('href',url);
});

//Bp Attendance Modal
jQuery(document).on("click","#bpAttendanceDetails",function(e){
  e.preventDefault();
  jQuery('#attendanceView').html("");
  var attendanceId = jQuery(this).data('id');
  var url = "attendanceDetailsView"+"/"+attendanceId;
  jQuery.ajax({
    url:url,
    type:"GET",
    dataType:"JSON",
    beforeSend: function() {
        Notiflix.Loading.Arrows('Data Processing');
    },
    success:function(response){
        console.log(response);
        Notiflix.Loading.Remove(100);

        if(response == "error"){
            Notiflix.Notify.Failure( 'Data Not Found' );
            setTimeout(function(){// wait for 5 secs(2)
                window.location.reload(); // then reload the page.(3)
                $(".btnCloseModal").click();
            }, 1000);
        }

        if(response) {
            Notiflix.Loading.Remove(800);
            jQuery('#attendanceView').append(response.attendanceView);
        }
    }
  });
});
</script>
@endsection
@endsection