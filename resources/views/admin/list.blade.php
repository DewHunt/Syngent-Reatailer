@extends('admin.master.master')
@section('page-style')
<style type="text/css">
/* 
##Device = Desktops
##Screen = 1281px to higher resolution desktops
*/
@media (min-width: 1281px) {
    .mbtnSearch {
        margin-top: 30px;
    }
    .exportbtnmt {
        margin-top: 30px;
    }
    .searchbtn {
        margin-top: 9px;
        height: 35px;
        font-size: 23px;
    }
    .customSearchBtn {
        margin-top:10px;
        margin-bottom: 5px;
    }
}
/* Portrait and Landscape */
@media only screen 
and (min-device-width: 320px) 
and (max-device-width: 568px)
and (-webkit-min-device-pixel-ratio: 2) {
    .main-content .form-group .form-control {
        padding: 0rem 0rem !important;
        font-size: 0rem !important;
    }
    .exportbtn {
        height: 45px;
        font-size: 22px !important;
    }
    .exportbtnmt {
        margin-top: 55px;
    }
    .searchbtn {
        margin-top: 28px;
        height: 45px;
        font-size: 23px;
    }
    .customSearchBtn {
        margin-top:28px;
        margin-bottom: 5px;
    }
}

/* Portrait and Landscape */
@media only screen 
and (min-device-width: 375px) 
and (max-device-width: 812px) 
and (-webkit-min-device-pixel-ratio: 3){
    .mbtnSearch {
        margin-top: 53px;
    }
    .main-content .form-group .form-control {
        padding: 0rem 0.75rem !important;
        font-size: 2rem !important;
    }
    .exportbtn {
        height: 45px;
        font-size: 22px !important;
    }
    .exportbtnmt {
        margin-top: 55px;
    }
    .searchbtn {
        margin-top: 28px;
        height: 45px;
        font-size: 23px;
    }
    .customSearchBtn {
        margin-top:28px;
        margin-bottom: 5px;
    }
}
@media (min-width: 768px) and (max-width: 1024px) {
    .mbtnSearch {
        margin-top: 53px;
    }
    /*.main-content .form-group .form-control {
        padding: 1rem 0.75rem !important;
        font-size: 2rem !important;
    }*/
    .exportbtn {
        height: 45px;
        font-size: 22px !important;
    }
    .exportbtnmt {
        margin-top: 55px;
    }
    .searchbtn {
        margin-top: 28px;
        height: 45px;
        font-size: 23px !important;
    }
    .customSearchBtn {
        margin-top:28px;
        margin-bottom: 5px;
    }
}
</style>
@endsection
@section('content')
<div class="row">
    <div class="col-md-6">
        <h4 class="c-grey-900 mB-20">User Log List</h4>
    </div>

    {{-- <div class="form-group col-md-3 bp-col-sm">
        <label>Start Date</label>
        <input type="text" class="form-control datepicker getStartDate" name="start_date" value="{{ (session()->get('searchSdate')) ? session()->get('searchSdate') : date('Y-m-01')  }}">
    </div>
    <div class="form-group col-md-3 bp-col-sm">
        <label>End Date</label>
        <input type="text" class="form-control datepicker getEndDate" name="end_date" value="{{ (session()->get('searchEdate')) ? session()->get('searchEdate') : date('Y-m-d')  }}">
    </div>

    <div class="col-md-2 bp-col-sm">
        <button type="submit" class="btn cur-p btn-primary btn-block mbtnSearch btn-float">Search</button>
    </div> --}}


    {{-- <div class="col-md-3">
        <div class="form-group">
            <input type="text" name="serach" id="serach" class="form-control" />
        </div>
    </div> --}}
</div>
<form method="post" action="{{ url('userlog.date-search') }}">
@csrf
<div class="row">
    <div class="form-group col-md-6 bp-col-sm">
        <a href="javascript:void(0)" class="btn btn-primary cur-p btn-xs exportbtnmt exportbtn" id="exportExcel">Export to Excel</a>

        <a href="javascript:void(0)" class="btn btn-info cur-p btn-xs exportbtnmt exportbtn" id="exportPdf">Export to Pdf</a>
    </div>
    
    <div class="form-group col-md-2 bp-col-sm">
        <label>Start Date</label>
        <input type="text" class="form-control datepicker getStartDate" name="start_date" value="{{ (session()->get('searchSdate')) ? session()->get('searchSdate') : date('Y-m-01')  }}">
    </div>
    <div class="form-group col-md-2 bp-col-sm">
        <label>End Date</label>
        <input type="text" class="form-control datepicker getEndDate" name="end_date" value="{{ (session()->get('searchEdate')) ? session()->get('searchEdate') : date('Y-m-d')  }}">
    </div>

    <div class="col-md-2 bp-col-sm">
        <label for=""></label>
        <button type="submit" class="btn cur-p btn-primary btn-block searchbtn btn-float">Search</button>
    </div>
</div>
</form>

<div class="row">
    <div class="col-md-9">
    </div>
    <div class="col-md-3">
       <input type="text" name="serach" id="serach" class="form-control customSearchBtn"/>
    </div>
</div>

<div id="tag_container" class="table-responsive bgc-white">
    <table class="table table-striped table-bordered" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th class="sorting" data-sorting_type="desc" data-column_name="id" style="cursor: pointer;">Sl.</th>
            <th class="sorting" data-sorting_type="desc" data-column_name="name" style="cursor: pointer;">Name</th>
            <th class="sorting" data-sorting_type="desc" data-column_name="type" style="cursor: pointer;">Type</th>
            <th class="sorting" data-sorting_type="desc" data-column_name="user_agent" style="cursor: pointer;">User Agent</th>
            <th class="sorting" data-sorting_type="desc" data-column_name="ip_address" style="cursor: pointer;">IP Address</th>
            <th class="sorting" data-sorting_type="desc" data-column_name="created_at" style="cursor: pointer;">Login Time</th>
        </tr>
    </thead>
    <tbody>
        @include('admin.log.result_data')
    </tbody>
</table>
<input type="hidden" name="hidden_page" id="hidden_page" value="1" />
<input type="hidden" name="hidden_column_name" id="hidden_column_name" value="id" />
<input type="hidden" name="hidden_sort_type" id="hidden_sort_type" value="asc" />
</div>



@section('page-scripts')
<script type="text/javascript">
jQuery(document).on("click","#exportExcel",function(e){
    var getStartDate    = $('.getStartDate').val();
    var getEndDate      = $('.getEndDate').val();
    var searchData      = 'getStartDate='+getStartDate+'&getEndDate='+getEndDate+'&type=excel';

    var url = "user-log-export?"+searchData;
    $('#exportExcel').attr('href',url);
});

jQuery(document).on("click","#exportPdf",function(e){
    var getStartDate   = $('.getStartDate').val();
    var getEndDate     = $('.getEndDate').val();
    var searchData     = 'getStartDate='+getStartDate+'&getEndDate='+getEndDate+'&type=pdf';

    var url = "user-log-export?"+searchData;
    $('#exportPdf').attr('href',url);
});
</script>

<!--Pagination New Script Start-->
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
            if(!empty(data.json_data_for_excel_and_pdf))
            {
                $('#export_data').val(data.json_data_for_excel_and_pdf);
            }
            jQuery('input[type=checkbox][data-toggle^=toggle]').bootstrapToggle();
            var toggleJs  = APP_URL+"/public/admin/js/custom-js/toggle-information.js";
            jQuery.getScript(toggleJs);
        }
    })
}

jQuery(document).ready(function() {
    jQuery(document).on('keyup', '#serach', function() {
        var query       = $('#serach').val();
        var column_name = $('#hidden_column_name').val();
        var sort_type   = $('#hidden_sort_type').val();
        var page        = $('#hidden_page').val();
        fetch_data(page, sort_type, column_name, query);
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
<!--Pagination New Script Start-->
@endsection
@endsection