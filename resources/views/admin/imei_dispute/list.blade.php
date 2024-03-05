@extends('admin.master.master')
@section('content')
<style>
    .cp {
        padding:5px;
    }
    .csearch {
        width:285px;
    }
    .top-margin {
      margin-top: 3rem;
    }
    .buttons-excel {
        width: 125px;
    }
    /* 
    ##Device = Desktops
    ##Screen = 1281px to higher resolution desktops
    */
    @media (min-width: 1281px) {
        .main-content .btn-group-sm>.btn, .btn-sm {
            font-size: 0.90rem !important;
            padding: 0.3rem 1rem !important;
            width: 100px;
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
        .top-margin {
          margin-top: 3rem;
        }
    }         
    /* Portrait and Landscape */
    @media only screen 
    and (min-device-width: 320px) 
    and (max-device-width: 568px)
    and (-webkit-min-device-pixel-ratio: 2) {
        .cp {
            padding:5px;
        }
        .csearch {
            width:300px;
        }
        .main-content .btn {
            padding: 1rem !important;
            font-size: 1.5rem !important;
            width: 210px !important;
            height: 65px !important;
        }
    }
    @media only screen 
    and (min-device-width: 375px) 
    and (max-device-width: 812px) 
    and (-webkit-min-device-pixel-ratio: 3){
        .cp {
            padding:5px;
        }
        .csearch {
            width:300px;
        }
        .main-content .btn {
            padding: 1rem !important;
            font-size: 1.5rem !important;
            width: 210px !important;
            height: 65px !important;
        }
    }
    @media (min-width: 768px) and (max-width: 1024px) {
        .cp {
            padding:5px;
        }
        .csearch {
            width:300px;
        }
        .main-content .btn {
            padding: 1rem !important;
            font-size: 1.5rem !important;
            width: 210px !important;
            height: 65px !important;
        }
    }
</style>

{{-- 
<div class="col-md-12 cp">
    <div class="row">
        <div class="col-md-6">
            <h4 class="c-grey-900 mB-20">IMEI Dispute List</h4>
            <a href="javascript:void(0)" class="btn btn-primary cur-p btn-xs exportbtn" id="exportExcel">Export to Excel</a>
            <a href="javascript:void(0)" class="btn btn-info cur-p btn-xs exportbtn" id="exportPdf">Export to Pdf</a>
        </div>
        <div class="col-md-6">
            <div class="form-group top-margin">
                <input type="text" name="serach" id="serach" class="form-control pull-right csearch"/>
            </div>
        </div>
    </div>
</div> 
--}}

<div class="col-md-12 cp">
    <div class="row">
        <div class="col-md-6">
            <h4 class="c-grey-900 mB-20">IMEI Dispute List</h4>
        </div>
        <div class="col-md-6"></div>
    </div>
</div>

<div id="tag_container" class="table-responsive">
    <table id="example4" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th class="sorting" data-sorting_type="asc" data-column_name="id" style="cursor: pointer;">Sl.</th>
                <th>Photo</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="customer_name" style="cursor: pointer;">Customer Name</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="customer_phone" style="cursor: pointer;">Customer Phone</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="dealer_name" style="cursor: pointer;">Dealer Name</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="dealer_phone_number" style="cursor: pointer;">Dealer Phone</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="distributor_code" style="cursor: pointer;">Dealer Code</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="reetailer_name" style="cursor: pointer;">Retailer Name</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="retailer_phone" style="cursor: pointer;">Retailer Phone</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="bp_name" style="cursor: pointer;">BP Name</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="bp_phone" style="cursor: pointer;">BP Phone</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="imei_number" style="cursor: pointer;">IMEI</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="description" style="cursor: pointer;">Description</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="comments" style="cursor: pointer;">Comments</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="date" style="cursor: pointer;">Date</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="status" style="cursor: pointer;">Status</th>
            </tr>
        </thead>
        <tbody>
            @include('admin.imei_dispute.result_data')
        </tbody>
    </table>

    <input type="hidden" name="hidden_page" id="hidden_page" value="1" />
    <input type="hidden" name="hidden_column_name" id="hidden_column_name" value="id" />
    <input type="hidden" name="hidden_sort_type" id="hidden_sort_type" value="asc" />
</div>

<!--Edit & Update Modal Start -->
<div class="modal fade" id="editIMEIModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update IMEI Dispute</h5>
                <span style="font-size:12px;margin-top:6px;margin-left:5px">[** All <span style="color:red;">Red</span> Start Sign Data Must Be Fillable.**]</span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form class="form-horizontal" method="POST" action="" id="UpdateIMEIDispute">
                @csrf
                <div class="modal-body">
                    <div class="col-md-12 select-h">
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <label>Status<span class="required">*</span></label>
                                <select class="form-control" data-placeholder="Select" style="width: 100%;" name="status" id="disputeStatus" required="">
                                    <option value="">Select</option>
                                    {{-- <option value="0">Pending</option> --}}
                                    <option value="1">Reported</option>
                                    <option value="2">Decline</option>
                                </select>
                            </div>
                            <div class="col-md-12 mb-2 commentsBox" style="display:none">
                                <label>Comments</label>
                                <textarea class="form-control description" name="comments"></textarea>
                                <input type="hidden" name="imei_number" class="imeiNumber">
                                <input type="hidden" name="imei_id" class="imeidisputeId">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> 
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--Edit & Update Modal End -->

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
jQuery(document).on('change','#disputeStatus',function(){
    var status = $(this).val();
    $('.commentsBox').fadeOut('200');
    if(status == 2) {
        $('.commentsBox').fadeIn('200');
    }
});
//Edit imei dispute number
jQuery(document).on("click","#editIMEIinfo",function(e){
  e.preventDefault();
  jQuery('#UpdateIMEIDispute').html();
  var imeiDisputeId  = jQuery(this).data('id');
  var url            = "imei.dispute-edit"+"/"+imeiDisputeId;
  jQuery.ajax({
    url:url,
    type:"GET",
    dataType:"JSON",
    success:function(response) {
        console.log(response.imeidisputeInfo.imei_number);
        if(response.status == 'success')
        {
            jQuery('.imeidisputeId').val(response.imeidisputeInfo.id);
            jQuery('.imeiNumber').val(response.imeidisputeInfo.imei_number);
        }
         
    }
  });
});
//Update imei dispute number
jQuery('#UpdateIMEIDispute').submit(function(e){
  e.preventDefault();
  jQuery.ajax({
    url:"imei.dispute-reply",
    method:"POST",
    data:new FormData(this),
    dataType:'JSON',
    contentType: false,
    cache: false,
    processData: false,
    success:function(response){
        if(response == 'success') {
            Notiflix.Notify.Success('IMEI Update Successfull');
            Notiflix.Loading.Remove(300);
            setTimeout(function(){// wait for 5 secs(2)
                window.location.reload(); // then reload the page.(3)
                $(".btnCloseModal").click();
            }, 1000);
        }

        if(response == 'error') {
            Notiflix.Notify.Warning('IMEI Update Failed.Please Try Again');
            Notiflix.Loading.Remove(300);
        }
    },
    error:function(error){
      Notiflix.Notify.Failure('IMEI Insert Failed');
      Notiflix.Loading.Remove(300);
    }
  });
});
function viewLargePhoto(photoID) {
    var photoUrl = APP_URL+'/public/upload/client/'+photoID;
    $('#photoId').attr("src", photoUrl ); 
}
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
        if(length >=2) {
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

