@extends('admin.master.master')

@section('page-style')
    <style>
        .cp { padding:5px }
        .csearch { width:285px; }
        .bannerPhoto { width: 30%; height: 10%; }
        .view-edit-photo { width: 50%; }
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
            .beforeAddBtn{ padding: 0px 7px; }
            .newAddBtn{
                margin-left: 5px;
                width: 100px;
                margin: 0px;
                padding-left: 0px !important;
                padding-right: 0px !important;
            }
            .bannerPhoto { width: 100%; height: 100%; }  
        }          
        /* Portrait and Landscape */
        @media only screen 
        and (min-device-width: 320px) 
        and (max-device-width: 568px)
        and (-webkit-min-device-pixel-ratio: 2) {
            .cp { padding:5px }
            .csearch { width:285px; margin-right: 10px; }
            .bannerPhoto { width: 100%; height: 100%; }
        }
        @media only screen 
        and (min-device-width: 375px) 
        and (max-device-width: 812px) 
        and (-webkit-min-device-pixel-ratio: 3){
            .cp { padding:5px }
            .csearch { width:285px; margin-right: 10px; }
            .bannerPhoto { width: 100%; height: 100%; }
        }
        @media (min-width: 768px) and (max-width: 1024px) {
            .cp { padding:5px }
            .csearch { width: 285px; margin-right: 10px; }
            .bannerPhoto { width: 100%; height: 100% !important; }
            .card { border: none; }
        }
    </style>
@endsection

@section('content')
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-6"><h4 class="c-grey-900 mB-20">Promo Offer List</h4></div>
            <div class="col-md-6 beforeAddBtn">
                <button  type="button" class="btn btn-primary pull-right btn-sm newAddBtn" data-toggle="modal" data-target="#AddOfferModal">Add Offer</button>
            </div>
        </div>
    </div>

    @include('admin.offer.result_data')

    <!--Add Modal Start -->
    @include('admin.offer.add_offer')
    <!--Add Modal End -->


    <!--Edit & Update Modal Start -->
    @include('admin.offer.edit_offer')
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
                            <img id="photoId" class="bannerPhoto" src="" width="425" height="250" alt="offer"/>
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
        var APP_URL = {!! json_encode(url('/')) !!};
        //Get Offer
        function getOfferData() {
            var query       = $('#serach').val();
            var column_name = $('#hidden_column_name').val();
            var sort_type   = $('#hidden_sort_type').val();
            var page        = $('#hidden_page').val();
            //var url = "employee";
            jQuery.ajax({
            //url:url,
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

        // Add Offer Data
        jQuery('#AddOffer').submit(function(e) {
            e.preventDefault();
            jQuery.ajax({
                url:"promoOffer.add",
                method:"POST",
                data:new FormData(this),
                dataType:'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success:function(response) {
                    if (response.errors) {
                        if (response.errors.title) {
                            jQuery( '#title-error' ).html( response.errors.title );
                        }
                        if (response.errors.sdate) {
                            jQuery( '#sdate-error' ).html( response.errors.sdate );
                        }
                        if (response.errors.edate) {
                            jQuery('#edate-error').html( response.errors.edate );
                        }
                        if (response.errors.offer_pic) {
                            jQuery( '.offer-pic-error' ).html( response.errors.offer_pic );
                        }
                    }
                    if (response == "error") {
                        Notiflix.Notify.Failure('Offer Insert Failed' );
                        Notiflix.Loading.Remove(600);
                    }
                    if (response == "success") {
                        jQuery("#AddOffer")[0].reset();
                        Notiflix.Notify.Success('Offer Insert Successfull');
                        Notiflix.Loading.Remove(600);
                        return getOfferData();
                    }
                    if (response.fail) {
                        if (response.errors.name) {
                            jQuery("#AddOffer")[0].reset();
                            jQuery('#error_field').addClass('has-error');
                            jQuery('#error-name').html( response.errors.name[0] );
                            Notiflix.Notify.Failure('Offer Insert Failed');
                            Notiflix.Loading.Remove(600);
                        }
                    }
                },
                error:function(error) {
                    jQuery("#AddOffer")[0].reset();
                    Notiflix.Notify.Failure('Offer Insert Failed');
                    Notiflix.Loading.Remove(600);
                }
            });
        });

        // Edit Offer
        jQuery(document).on("click","#editOfferInfo",function(e){
            e.preventDefault();
            var offerId = jQuery(this).data('id');
            var url = "promoOffer.edit"+"/"+offerId;
            jQuery.ajax({
                url:url,
                type:"GET",
                dataType:"JSON",
                success:function(response) {
                    console.log(response);
                    jQuery('.edit-form-div').html(response);
                    jQuery('.select2').select2()
                    Notiflix.Loading.Remove(600);
                }
            });
        });

        // Update Offer
        jQuery(document).on("submit",'#UpdateOffer', function(arg){
            arg.preventDefault();
            jQuery.ajaxSetup({
                headers: {'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')}
            });
            var formData = new FormData(this);
            jQuery.ajax({
                url:"promoOffer.update",
                type:"POST",
                data:formData,
                dataType:'JSON',
                cache: false,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response == "success") {
                        Notiflix.Notify.Success('Offer Update Successfull');
                        return getOfferData();
                        Notiflix.Loading.Remove(600);
                    }
                },
                error:function(error) {
                    jQuery("#updateOffer")[0].reset();
                    Notiflix.Notify.Failure('Offer Update Failed');
                }
            });
        });

        // Offer Modal Status
        jQuery('.offer-toggle-class').change(function(e) {
            e.preventDefault();
            var status = jQuery(this).prop('checked') == true ? 1 : 0; 
            var updateId = jQuery(this).data('id');
            var url = "promoOffer.status"+"/"+updateId;
            jQuery.ajax({
                url:url,
                type:"GET",
                dataType:'JSON',
                cache: false,
                contentType: false,
                processData: false,
                success:function(response){
                    if (response.success) {
                        Notiflix.Notify.Success('Offer Update Successfull');
                        Notiflix.Loading.Remove(600);
                    }                
                    if (response.error) {
                        Notiflix.Notify.Failure('Offer Update Failed');
                        Notiflix.Loading.Remove(600);
                    }
                }
            });
        });

        $('.offerphotoIdModal').click(function(event) {   
            event.preventDefault();
            var getSrc = jQuery(this).data('id');
            var photoUrl = APP_URL+'/public/upload/'+getSrc;
            $('#photoId').attr("src", photoUrl ); 
        });

        // Pagination New Script Start
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
                var query = $('#serach').val();
                var column_name = $('#hidden_column_name').val();
                var sort_type = $('#hidden_sort_type').val();
                var page = $('#hidden_page').val();
                fetch_data(page, sort_type, column_name, query);
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
                if (order_type == 'desc') {
                    $(this).data('sorting_type', 'asc');
                    reverse_order = 'asc';
                    clear_icon
                    $('#'+column_name+'_icon').html('<span class="glyphicon glyphicon-triangle-top"></span>');
                }
                $('#hidden_column_name').val(column_name);
                $('#hidden_sort_type').val(reverse_order);
                var page = $('#hidden_page').val();
                var query = $('#serach').val();
                fetch_data(page, reverse_order, column_name, query);
            });
            
            jQuery(document).on('click','.pagination a', function(event){
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
            
            // jQuery('.btnCloseModal').trigger('click');
            // jQuery('.btnCloseModal').mousedown();
            // jQuery('.close').click();
        });
    </script>
    <!--Pagination New Script Start-->
@endsection