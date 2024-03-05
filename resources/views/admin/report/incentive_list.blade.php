@extends('admin.master.master')
@section('content')
<style>
.mbtnSearch {
    margin-top: 30px;
}
.badge{
    height: auto;
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
        padding: 0.4rem 0rem !important;
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
        float: left;
    }
    .customHt {
        height: 50px;
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
        padding: 0.4rem 0rem !important;
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
        float: left;
    }
    .badge {
        font-size: 100%;
        width: 130px;
        height: 60px;
    }
    .customHt {
        height: 50px;
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
        padding: 0.4rem 0rem !important;
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
        float: left;
    }
    .badge {
        font-size: 100%;
        width: 130px;
        height: 60px;
    }
    .customHt {
        height: 50px;
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

    <div class="masonry-item col-md-9 mY-10">
        <div class="bgc-white p-20 bd new-bp-css">
            <form method="post" action="{{ url('report.search_incentive') }}" id="exportDataForm">
                @csrf
                <div class="row">
                    <div class="form-group col-md-3 bp-col-sm" style="padding: 0px 5px;">
                        <label>Category</label>
                        <select class="form-control getCategory" name="incentive_category">
                            <option value="">Select Category</option>
                            <option value="general" {{ (session()->get('SearchIncentiveCategory') == 'general' )? 'selected':'' }} >General</option>
                            <option value="target" {{ (session()->get('SearchIncentiveCategory') == 'target' )? 'selected':'' }}>Target</option>
                        </select>
                    </div>

                    <div class="form-group col-md-3 bp-col-sm" style="padding: 0px 5px;">
                        <label>Group</label>
                        <select class="form-control getGroup" name="incentive_group">
                            <option value="">Select Group</option>
                            <option value="1" {{ (session()->get('SearchIncentiveGroup') == '1' )? 'selected':'' }}>BP</option>
                            <option value="2" {{ (session()->get('SearchIncentiveGroup') == '2' )? 'selected':'' }}>Retailer</option>
                        </select>
                    </div>
                    

                    <div class="form-group col-md-2 bp-col-xs" style="padding: 0px 5px;">
                        <label>Start Date</label>
                        <input type="text" class="form-control datepicker getStartDate" name="start_date" value="{{ (session()->get('SearchIncentiveFromDate')) ? session()->get('SearchIncentiveFromDate') : date('Y-m-d')  }}">
                    </div>


                    <div class="form-group col-md-2 bp-col-xs" style="padding: 0px 5px;">
                        <label>End Date</label>
                        <input type="text" class="form-control datepicker getEndDate" name="end_date" value="{{ (session()->get('SearchIncentiveToDate')) ? session()->get('SearchIncentiveToDate') : date('Y-m-t') }}">
                    </div>

                    <div class="col-md-2 mb-3 bp-col-sm">
                        <button type="submit" class="btn cur-p btn-primary btn-block mbtnSearch btn-float" id="exportBtnSubmit">Search</button>
                    </div>
                </div>
            </form>
            @if(isset($IncentiveList))
            <div class="row" style="padding-bottom:5px">
                <div class="col-md-8">
                    <a href="javascript:void(0)" class="btn btn-primary cur-p btn-xs exportbtn" id="exportExcel">Export to Excel</a>
                    <a href="javascript:void(0)" class="btn btn-info cur-p btn-xs exportbtn" id="exportPdf">Export to Pdf</a> 
                   
                </div>
                <div class="col-md-4">
                    <div class="">
                        <input type="text" name="serach" id="serach" class="form-control customHt"/>
                    </div>
                </div>
            </div>
            @php @endphp
            <div id="tag_container" class="table-responsive">
                <table id="dataExport" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th class="sorting" data-sorting_type="asc" data-column_name="id" style="cursor: pointer;">Sl.</th>
                            <th class="sorting" data-sorting_type="asc" data-column_name="incentive_title" style="cursor: pointer;">Group</th>
                            <th class="sorting" data-sorting_type="asc" data-column_name="incentive_title" style="cursor: pointer;">Title</th>
                            <th class="sorting" data-sorting_type="asc" data-column_name="incentive_category" style="cursor: pointer;">Category</th>
                            <th class="sorting" data-sorting_type="asc" data-column_name="start_date" style="cursor: pointer;">Start Date</th>
                            <th class="sorting" data-sorting_type="asc" data-column_name="end_date" style="cursor: pointer;">End Date</th>
                            <th class="sorting" data-sorting_type="asc" data-column_name="min_qty" style="cursor: pointer;">Min.Qty</th>
                            <th class="sorting" data-sorting_type="asc" data-column_name="incentive_amount" style="cursor: pointer;">Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @include('admin.report.incentive_list_result_data')
                    </tbody>
                </table>
                <input type="hidden" name="hidden_page" id="hidden_page" value="1" />
                <input type="hidden" name="hidden_column_name" id="hidden_column_name" value="id" />
                <input type="hidden" name="hidden_sort_type" id="hidden_sort_type" value="asc" />
            </div>
            @endif
        </div>
    </div>
</div>

@section('page-scripts')
<script type="text/javascript">
jQuery(document).on("click","#exportExcel",function(e){
    var getCategory     = $('.getCategory').val();
    var getGroup        = $('.getGroup').val();
    var getStartDate    = $('.getStartDate').val();
    var getEndDate      = $('.getEndDate').val();
    var searchVal       = $('#serach').val();


    var searchData      = "getCategory="+getCategory+'&getGroup='+getGroup+'&getStartDate='+getStartDate+'&getEndDate='+getEndDate+'&searchVal='+searchVal+'&type=excel';

    var url = "exportIncentiveReport?"+searchData;
    $('#exportExcel').attr('href',url);
});

jQuery(document).on("click","#exportPdf",function(e){
    var getCategory     = $('.getCategory').val();
    var getGroup        = $('.getGroup').val();
    var getStartDate    = $('.getStartDate').val();
    var getEndDate      = $('.getEndDate').val();
    var searchVal       = $('#serach').val();
    
    var searchData      = "getCategory="+getCategory+'&getGroup='+getGroup+'&getStartDate='+getStartDate+'&getEndDate='+getEndDate+'&searchVal='+searchVal+'&type=pdf';

    var url = "exportIncentiveReport?"+searchData;
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
});
</script>
@endsection


@endsection