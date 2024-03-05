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
        width: 100%;
        height: 33px;
    }
    .exportbtn {
        width: 20%;
    }
    .statusLabel {
        /*
        padding: 10px 5px !important;
        height: 30px;
        */
        width: 100%;
    }
	.p-10{
		padding:10px;
	}
	.csearch-input {
        margin-top:30px;
    }
    .badge {
      height: auto;
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
        padding: 0rem !important;
        font-size: 1.5rem !important;
        width: 210px !important;
        height: 65px !important;
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
    .badge {
        font-size: 100%;
        width: 130px;
        height: 65px;
    }
    .csearch-input {
        margin-top:22px;
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
        height: 65px !important;
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
    .badge {
        font-size: 100%;
        width: 130px;
        height: 65px;
    }
    .csearch-input {
        margin-top:22px;
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
        height: 65px !important;
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
    .badge {
        font-size: 100%;
        width: 130px;
        height: 65px;
    }
    .csearch-input {
        margin-top:50px;
    }
    #photoId{
        width:100%;
    }
}
</style>
<h4 class="c-grey-900 mB-20">Pending Order Lists</h4>
<div class="row">
    <div class="masonry-item col-md-12 mY-10">
        <div class="bgc-white p-10 bd new-bp-css">
            @if(isset($saleList))
            <div class="row" style="margin-left: -5px;">
                <div class="col-md-9">
					<form method="post" action="{{ url('report.pending-search-order-report') }}">
						@csrf
						<div class="row">
                            <div class="form-group col-md-3 bp-col-sm" style="padding: 0px 5px;">
                                <label>Dealer</label>
                                <input type="text" id="dealer_search_list" class="form-control ui-autocomplete-input" placeholder="Search By Name or Phone"/>
                                <input type="hidden" id='dealer_code' name="dealer_code" class="form-control"readonly>
                            </div>

							<div class="form-group col-md-3 bp-col-sm" style="padding: 0px 5px;">
								<label>Retailer</label>
								<input type="text" id="retailer_search" class="form-control ui-autocomplete-input" placeholder="Search By Name or Phone"/>
								<input type="hidden" id='retailer_id' name="retailer_id" class="form-control"readonly>
							</div>

                            <div class="form-group col-md-3 bp-col-sm" style="padding: 0px 5px;">
                                <label>BP</label>
                                <input type="text" id="bp_search" class="form-control ui-autocomplete-input" placeholder="Search By Name or Phone"/>
                                <input type="hidden" id='bp_id' name="bp_id" class="form-control"readonly>
                            </div>

                            <div class="form-group col-md-2 bp-col-sm" style="padding: 0px 5px;">
                                <label>Model</label>
                                <input type="text" id="model_search" class="form-control ui-autocomplete-input" placeholder="Search By Model"/>
                                <input type="hidden" id='product_id' name="product_id" class="form-control"readonly>
                            </div>

							<div class="col-md-1 mb-3 bp-col-xs" style="padding: 0px 5px;">
								<button type="submit" class="btn cur-p btn-primary btn-block mbtnSearch btn-float">Search</button>
							</div>
						</div>
					</form>
                    @if(!empty($sellerName))
                    <div style="padding:10px">
                        <i class="c-light-blue-500 fa fa-user"></i> {{ $sellerName }}
                    </div>
                    @endif
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <input type="text" name="serach" id="serach" class="form-control csearch-input"/>
                    </div>
                </div>
            </div>
            <div id="tag_container" class="table-responsive">
                <table class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th class="sorting" data-sorting_type="asc" data-column_name="id" style="cursor: pointer;">Sl.</th>
                            <th class="sorting" data-sorting_type="asc" data-column_name="photo" style="cursor: pointer;">Photo</th>
                            <th class="sorting" data-sorting_type="asc" data-column_name="customer_name" style="cursor: pointer;">Customer Name</th>
                            <th class="sorting" data-sorting_type="asc" data-column_name="customer_phone" style="cursor: pointer;">Customer Phone</th>
                            <th class="sorting" data-sorting_type="asc" data-column_name="sale_date" style="cursor: pointer;">Sale Date</th>

                            <th class="sorting" data-sorting_type="asc" data-column_name="ime_number" style="cursor: pointer;">IMEI 1</th>
                            <th class="sorting" data-sorting_type="asc" data-column_name="alternate_imei" style="cursor: pointer;">IMEI 2</th>
                            <th class="sorting" data-sorting_type="asc" data-column_name="model" style="cursor: pointer;">MODEL</th>
                            <th class="sorting" data-sorting_type="asc" data-column_name="dealer_name" style="cursor: pointer;">Dealer Name</th>
                            <th class="sorting" data-sorting_type="asc" data-column_name="dealer_code" style="cursor: pointer;">Dealer Code</th>

                            <th class="sorting" data-sorting_type="asc" data-column_name="retailer_name" style="cursor: pointer;">Retailer Name</th>
                            <th class="sorting" data-sorting_type="asc" data-column_name="retailer_phone_number" style="cursor: pointer;">Retailer Phone</th>
                            <th class="sorting" data-sorting_type="asc" data-column_name="bp_name" style="cursor: pointer;">BP Name</th>
                            <th class="sorting" data-sorting_type="asc" data-column_name="bp_phone" style="cursor: pointer;">BP Phone</th>

                            <th class="sorting" data-sorting_type="asc" data-column_name="order_type" style="cursor: pointer;">Order Type</th>
                            <th class="sorting" data-sorting_type="asc" data-column_name="status" style="cursor: pointer;">Status</th>
                            {{-- <th>Action</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @include('admin.order.pending_sales_list_result_data')
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
                <div class="card">
                    <div class="card-body">
                        <table class="table table-striped table-bordered table-sm" cellspacing="0" width="100%" id="salesInfo">
                        </table>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>Sl.</th>
                                        <th>Photo</th>
                                        <th>IMEI Number</th>
                                        <th>Code</th>
                                        <th>Type</th>
                                        <th>Model</th>
                                        <th>Color</th>
                                        <th>Msrp Price</th>
                                        <th>Sale Price</th>
                                        <th>Sale Qty</th>
                                    </tr>
                                </thead>
                                <tbody id="itemList">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
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

<!--Edit & Update Modal Start -->
<div class="modal fade" id="updateOrderStatusModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Order Status</h5>
                <span style="font-size:12px;margin-top:6px;margin-left:5px">[** All <span style="color:red;">Red</span> Start Sign Data Must Be Fillable.**]</span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form class="form-horizontal" method="POST" action="" id="UpdatePendingOrder">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <label>Status<span class="required">*</span></label>
                            <select class="form-control" data-placeholder="Select" style="width: 100%;" name="status" id="pendingOrderStatus" required="">
                                <option value="0" selected="selected">Accepted</option>
                                <option value="2">Declined</option>
                            </select>
                        </div>
                        <div class="col-md-12 mb-2 commentsBox" style="display:none">
                            <label>Comments</label>
                            <textarea class="form-control description" name="comments"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="pending_order_id" id="orderId">
                    <button type="button" class="btn btn-secondary btnCloseModal" data-dismiss="modal">Close</button> 
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
                        <img id="photoId" src="" width="430" height="400" alt="photo"/>
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
//Pending or Bounce Order Status Change
jQuery(document).on("click","#updateOrderStatus",function(e){
    e.preventDefault();
    $('#orderId').val(0);
    var orderId = jQuery(this).data('id');
    $('#orderId').val(orderId);
});
jQuery(document).on('change','#pendingOrderStatus',function(){
    var status = $(this).val();
    $('.commentsBox').fadeOut('200');
    if(status == 2) {
        $('.commentsBox').fadeIn('200');
    }
});
jQuery('#UpdatePendingOrder').submit(function(e){
  e.preventDefault();
  jQuery.ajax({
    url:"pendingOrderStatusUpdate",
    method:"POST",
    data:new FormData(this),
    dataType:'JSON',
    contentType: false,
    cache: false,
    processData: false,
    beforeSend: function() {
        Notiflix.Loading.Arrows('Order Processing');
    },
    success:function(response){
        Notiflix.Loading.Remove(300);
        if(response == 'success') {
            Notiflix.Notify.Success('Order Status Changed Successfully');
            setTimeout(function(){// wait for 5 secs(2)
                window.location.reload(); // then reload the page.(3)
                $(".btnCloseModal").click();
            }, 200);
        }
        
        if(response == 'warning') {
            Notiflix.Notify.Success('Sorry Product All Ready Sold');
        }

        if(response == 'error') {
            Notiflix.Notify.Warning( 'Order Status Changed Failed' );
        }
    },
    error:function(error){
      Notiflix.Notify.Failure( 'Something Went Wrong.Please Try Again' );
    }
  });
});

function viewLargePhoto(photoID)
{
    var photoUrl = APP_URL+'/public/upload/client/'+photoID;
    $('#photoId').attr("src", photoUrl ); 
}
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