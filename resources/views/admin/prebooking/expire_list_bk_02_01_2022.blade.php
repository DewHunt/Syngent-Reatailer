@extends('admin.master.master')
@section('content')
<style>
/* 
  ##Device = Desktops
  ##Screen = 1281px to higher resolution desktops
*/
@media (min-width: 1281px) {
    .main-content .btn-group-sm>.btn, .btn-sm {
        font-size: 0.90rem !important;
        padding: 0.3rem 0rem !important;
        width: 135px;
        margin: 2px;
    }
    .beforeAddBtn{
        padding: 0px 7px;
    }
    .newAddBtn{
        margin-left: 5px;
        width: 100px;
        margin: 0px;
        padding-left: 0px !important;
        padding-right: 0px !important;
    } 
} 
/* Portrait and Landscape */
@media only screen 
and (min-device-width: 320px) 
and (max-device-width: 568px)
and (-webkit-min-device-pixel-ratio: 2) {
    .pre-booking-btn{
        font-size: 2rem !important;
        padding: 1rem 1rem !important;
        margin: 5px 0 5px 0;
        width: 310px;
    }
}
@media only screen 
and (min-device-width: 375px) 
and (max-device-width: 812px) 
and (-webkit-min-device-pixel-ratio: 3){
    .pre-booking-btn{
        font-size: 2rem !important;
        padding: 1rem 1rem !important;
        margin: 5px 0 5px 0;
        width: 310px;
    }
}
@media (min-width: 768px) and (max-width: 1024px) {
    .pre-booking-btn{
        font-size: 2rem !important;
        padding: 1rem 1rem !important;
        margin: 5px 0 5px 0;
        width: 310px;
    }
}
</style>

<div class="col-md-12">
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-10" style="margin-right: 0px !important;margin-left: 0px !important;padding-right: 0px !important;">
            <a href="{{ route('prebooking.index') }}">
                <button  type="button" class="btn btn-success pull-right btn-sm pre-booking-btn" style="margin-left:5px;">Current Pre-Booking</button></a>  

            <button  type="button" class="btn btn-primary pull-right btn-sm pre-booking-btn" data-toggle="modal" data-target="#AddPreBookingModal" style="margin-left:5px;">Add Pre-Booking</button>  
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8"><h4 class="c-grey-900 mB-20">Expire Pre-Booking List</h4></div>
    <div class="col-md-4">
        <div class="form-group" style="widht:200px">
            <input type="text" name="serach" id="serach" class="form-control" style="width: 280px;float: right;margin: 4px 0px;"/>
        </div>
    </div>
</div>


<div id="tag_container" class="table-responsive">
    <table class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th class="sorting" data-sorting_type="asc" data-column_name="id" style="cursor: pointer;">Sl.</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="model" style="cursor: pointer;">Model</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="color" style="cursor: pointer;">Color</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="start_date" style="cursor: pointer;">Start Date</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="end_date" style="cursor: pointer;">End Date</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="minimum_advance_amount" style="cursor: pointer;">Minimum Prebooking Amount</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="max_qty" style="cursor: pointer;">Max Qty</th>
                <th class="sorting text-right" data-sorting_type="asc" data-column_name="price" style="cursor: pointer;">Price</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="status" style="cursor: pointer;">Status</th>
                <th style="width:10px">Action</th>
            </tr>
        </thead>
        <tbody>
            @include('admin.prebooking.expire_result_data')
        </tbody>
    </table>
    <input type="hidden" name="hidden_page" id="hidden_page" value="1" />
    <input type="hidden" name="hidden_column_name" id="hidden_column_name" value="id" />
    <input type="hidden" name="hidden_sort_type" id="hidden_sort_type" value="asc" />
</div>

<!--Add New PreBooking Modal Start -->
<div class="modal fade" id="AddPreBookingModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Prebooking</h5>
                <span style="font-size:12px;margin-top:6px;margin-left:5px">[** All <span style="color:red;">Red</span> Start Sign Data Must Be Fillable.**]</span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form class="form-horizontal" method="POST" action="{{route('prebooking.add')}}" id="AddProductPreBooking">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        
                        {{-- 
                        <div class="col-md-6 mb-2">
                            <label>Model <span class="required">*</span></label>
                            <input type="text" class="form-control ui-autocomplete-input model_search" placeholder="Search By Model" required=""/>
                            <input type="hidden" name="model" class="form-control model_id">
                        </div> 
                        --}}

                        <div class="col-md-6 mb-2">
                            <label>Model <span class="required">*</span></label>
                            <select class="select2 form-control product_model" data-placeholder="Select  Model" style="width: 100%;" name="product_model" required="">
                                <option value="">Select Model</option>
                                @if(isset($modelList))
                                    @foreach($modelList as $row)
                                        <option value="{{ $row->product_master_id }}">{{ $row->product_model }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div> 

                        <div class="col-md-6 mb-2">
                            <label>Color</label>
                            <input type="text" name="color" class="form-control"/>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label>Start Date <span class="required">*</span></label>
                            <input type="text" name="start_date" class="form-control datepicker" required=""/>
                            <span class="text-danger">
                                <strong id="start-date-error"></strong>
                            </span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label>End Date <span class="required">*</span></label>
                            <input type="text" name="end_date" class="form-control datepicker" required=""/>
                            <span class="text-danger">
                                <strong id="end-date-error"></strong>
                            </span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label>Minimum Prebooking Amount <span class="required">*</span></label>
                            <input type="number" name="minimum_advance_amount" class="form-control" required=""/>
                            <span class="text-danger">
                                <strong id="minimum-amount-error"></strong>
                            </span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label>Maximum Qty <span class="required">*</span></label>
                            <input type="number" name="max_qty" class="form-control" required=""/>
                            <span class="text-danger">
                                <strong id="max-qty-error"></strong>
                            </span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label>MSRP <span class="required">*</span></label>
                            <input type="number" id="model_price" class="form-control"/>
                            <input type="hidden" name="price" id="setPrice">
                            <span class="text-danger">
                                <strong id="price-error"></strong>
                            </span>
                        </div>

                        <div class="col-md-6 mb-5" style="margin-top: 15px;">
                            <label>Status <span class="required">*</span></label><br/>
                            <label><input type="radio" name="status" checked="checked" value="1"> Active</label>  &nbsp;&nbsp; 
                            <label><input type="radio" name="status" value="0"> In-Active</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="product_id" class="ApiproductId"/>
                    <button type="button" class="btn btn-secondary btnCloseModal" data-dismiss="modal">Close</button> 
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--Add New Pre Booking Modal End -->

<!--Edit & Update Modal Start -->
<div class="modal fade" id="editPreBookingModal" role="dialog" aria-labelledby="editPreBookingModal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProductModal">Update Prebooking</h5>

                <span style="font-size:12px;margin-top:6px;margin-left:5px">[** All <span style="color:red;">Red</span> Start Sign Data Must Be Fillable.**]</span>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form class="form-horizontal" method="POST" action="" id="UpdateProductPreBooking" >
                <input type="hidden" name="_method" value="PUT"/>
                @csrf
                <div class="modal-body">
                    <div class="row">

                        <div class="col-md-6 mb-2">
                            <label>Model <span class="required">*</span></label>
                            <select class="select2 form-control getModel product_model" data-placeholder="Select  Model" style="width: 100%;" name="product_model">
                                <option value="">Select Model</option>
                                @if(isset($modelList))
                                    @foreach($modelList as $row)
                                        <option value="{{ $row->product_master_id }}" class="selectedId_{{ $row->product_master_id }}">{{ $row->product_model }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div> 

                        <div class="col-md-6 mb-2">
                            <label>Color</label>
                            <input type="text" name="color" class="form-control getColor"/>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label>Start Date <span class="required">*</span></label>
                            <input type="text" name="start_date" class="form-control datepicker getSdate"/>
                            <span class="text-danger">
                                <strong id="update-start-date-error"></strong>
                            </span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label>End Date <span class="required">*</span></label>
                            <input type="text" name="end_date" class="form-control datepicker getEdate" required=""/>
                            <span class="text-danger">
                                <strong id="update-end-date-error"></strong>
                            </span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label>Minimum Prebooking Amount <span class="required">*</span></label>
                            <input type="number" name="minimum_advance_amount" class="form-control getMiniMumAmount" required=""/>
                            <span class="text-danger">
                                <strong id="update-minimum-amount-error"></strong>
                            </span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label>Maximum Qty <span class="required">*</span></label>
                            <input type="number" name="max_qty" class="form-control getMaxQty" required=""/>
                            <span class="text-danger">
                                <strong id="update-max-qty-error"></strong>
                            </span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label>MSRP <span class="required">*</span></label>
                            <input type="number" min="0" name="model_price" class="form-control getPrice"/>
                            <input type="hidden" name="old_price" id="updateSetPrice">
                            <span class="text-danger">
                                <strong id="update-price-error"></strong>
                            </span>
                        </div>

                        <div class="col-md-6 mb-5" style="margin-top: 15px;">
                            <label>Status <span class="required">*</span></label><br/>
                            <label><input type="radio" id="option1" name="status" value="1"> Active</label>  &nbsp;&nbsp; 
                            <label><input type="radio" id="option2" name="status" value="0"> In-Active</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="update_id" id="updateId"/>
                    <button type="button" class="btn btn-secondary btnCloseModal" data-dismiss="modal">Close</button> 
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--Edit & Update Modal End -->



@section('page-scripts')
<script type="text/javascript">
jQuery(".select2").select2({ dropdownParent: ".modal-container" });
jQuery(document).ready(function(){
    jQuery('.pre-booking-toggle-class').change(function(e) {
        e.preventDefault();
        var status = jQuery(this).prop('checked') == true ? 1 : 0; 
        var getId = jQuery(this).data('id');
        var url = "prebooking.status"+"/"+getId;
        jQuery.ajax({
            url:url,
            type:"GET",
            dataType:'JSON',
            cache: false,
            contentType: false,
            processData: false,
            success:function(response){
                if(response.success){
                    Notiflix.Notify.Success( 'PreBooking Status Update Successfully' );
                }
            
                if(response.error){
                    Notiflix.Notify.Failure( 'PreBooking Status Update Failed' );
                }
            }
        });
    });
    // Get Product Data AS MySql View Page   
    function getPreBookingData() {
        var query       = $('#serach').val();
        var column_name = $('#hidden_column_name').val();
        var sort_type   = $('#hidden_sort_type').val();
        var page        = $('#hidden_page').val();
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
    // Add Product Data
    jQuery('#AddProductPreBooking').submit(function(e) {
      e.preventDefault();
      jQuery('#model-error').html("");
      jQuery('#color-error').html("");
      jQuery('#start-date-error').html("");
      jQuery('#end-date-error').html("");
      jQuery('#minimum-advance-amount-error').html("");
      jQuery('#maximum-qty-error').html("");
      jQuery('#price-error').html("");
      jQuery.ajax({
        url:"prebooking.add",
        method:"POST",
        data:new FormData(this),
        dataType:'JSON',
        contentType: false,
        cache: false,
        processData: false,
        beforeSend: function() {
            Notiflix.Loading.Arrows('Data Processing');
        },
        success:function(response)  {
            Notiflix.Loading.Remove(300);
            if(response.errors) {
                if(response.errors.model){
                    jQuery( '#model-error' ).html( response.errors.model[0] );
                }
                if(response.errors.color){
                    jQuery( '#color-error' ).html( response.errors.color[0] );
                }
                if(response.errors.start_date){
                    jQuery( '#start-date-error' ).html( response.errors.start_date[0] );
                }
                if(response.errors.end_date){
                    jQuery( '#end-date-error' ).html( response.errors.end_date[0] );
                }
                if(response.errors.minimum_advance_amount){
                    jQuery( '#minimum-advance-amount-error' ).html( response.errors.minimum_advance_amount[0] );
                }
                if(response.errors.max_qty){
                    jQuery( '#maximum-qty-error' ).html( response.errors.max_qty[0] );
                }
                if(response.errors.price){
                    jQuery( '#price-error' ).html( response.errors.price[0] );
                }
            }

            if(response == "error") {
                Notiflix.Notify.Failure('Data Insert Failed');
            }

            if(response == "success") {
                jQuery("#AddProductPreBooking")[0].reset();
                jQuery(".btnCloseModal").click();
                Notiflix.Notify.Success('Data Insert Successfull');
                return getPreBookingData();
            }
        },
        error:function(error)   {
            Notiflix.Notify.Failure('Data Insert Failed');
        }
      });
    });

    // Edit Product Data
    jQuery(document).on("click","#editPreBookingInfo",function(e){
        e.preventDefault();
        var getId   = jQuery(this).data('id');
        var url     = "prebooking.edit"+"/"+getId;
        jQuery.ajax({
            url:url,
            type:"GET",
            dataType:"JSON",
            beforeSend: function() {
                Notiflix.Loading.Arrows('Data Processing');
            },
            success:function(response)  {
                console.log(response);
                Notiflix.Loading.Remove(300);
                jQuery('#updateId').val(response.id);
                jQuery('.getModel').val(response.product_master_id).prop('selected',true);
                jQuery('.getColor').val(response.color);
                jQuery('.getSdate').val(response.start_date);
                jQuery('.getEdate').val(response.end_date);
                jQuery('.getMiniMumAmount').val(response.minimum_advance_amount);
                jQuery('.getMaxQty').val(response.max_qty);
                jQuery('.getPrice').val(response.price);
                //jQuery('.getPrice').val(response.price).prop('disabled',true);
                jQuery('.updateSetPrice').val(response.price);

                //jQuery(".selectedId_"+response.product_master_id).attr("selected", true);

                jQuery(".selectedId_"+response.product_master_id).prop("selected", true);
                jQuery(".product_model").val(response.product_master_id).trigger('change');

                if (response.status == 1){
                    jQuery("#option1").prop("checked", true);
                } else {
                    jQuery("#option2").prop("checked", true);
                }
            }
        });
    });
    // Update Product Data
    jQuery('#UpdateProductPreBooking').on("submit", function(arg){
        jQuery.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });
        arg.preventDefault();
        jQuery('#update-model-error').html("");
        jQuery('#update-color-error').html("");
        jQuery('#update-start-date-error').html("");
        jQuery('#update-end-date-error').html("");
        jQuery('#update-minimum-advance-amount-error').html("");
        jQuery('#update-maximum-qty-error').html("");
        jQuery('#update-price-error').html("");

        var formData = new FormData(this);
        formData.append('_method', 'post');
      
        var getId   = jQuery('#updateId').val();
        var data    = jQuery("#UpdateProductPreBooking").serialize();
        
        jQuery.ajax({
            url:"prebooking.update",
            type:"POST",
            data:formData,
            dataType:'JSON',
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function() {
                Notiflix.Loading.Arrows('Data Processing');
            },
            success:function(response)  {
                Notiflix.Loading.Remove(300);
                if(response.errors) {
                    if(response.errors.model){
                        jQuery( '#update-model-error' ).html( response.errors.model[0] );
                    }
                    if(response.errors.color){
                        jQuery( '#update-color-error' ).html( response.errors.color[0] );
                    }
                    if(response.errors.start_date){
                        jQuery( '#update-start-date-error' ).html( response.errors.start_date[0] );
                    }
                    if(response.errors.end_date){
                        jQuery( '#update-end-date-error' ).html( response.errors.end_date[0] );
                    }
                    if(response.errors.minimum_advance_amount){
                        jQuery( '#update-minimum-advance-amount-error' ).html( response.errors.minimum_advance_amount[0] );
                    }
                    if(response.errors.max_qty){
                        jQuery( '#update-maximum-qty-error' ).html( response.errors.max_qty[0] );
                    }
                    if(response.errors.price){
                        jQuery( '#update-price-error' ).html( response.errors.price[0] );
                    }
                }

                if(response == "success")   {
                    jQuery(".btnCloseModal").click();
                    Notiflix.Notify.Success( 'PreBooking Update Successfully' );
                    return getPreBookingData();
                    console.log(response);
                    Notiflix.Loading.Remove(600);
                }
            
                if(response == "error") {
                    Notiflix.Notify.Failure( 'PreBooking Update Failed' );
                    console.log(response);
                }
            }
        });
    });
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

