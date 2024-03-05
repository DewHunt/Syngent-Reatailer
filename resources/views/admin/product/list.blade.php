@extends('admin.master.master')

@section('page-style')
    <style>
        .cp { padding:5px }
        .csearch { width:285px; }
        /* 
        * Device = Desktops
        * Screen = 1281px to higher resolution desktops
        */
        @media (min-width: 1281px) {
            .main-content .btn-group-sm>.btn, .btn-sm { font-size: 0.90rem !important; padding: 0.3rem 0rem !important; width: 125px; }
            .beforeAddBtn { padding: 0px 7px; }
            .newAddBtn {
                margin-left: 5px;
                width: 168px;
                margin: 0px -6px 5px 9px;
                padding-left: 0px !important;
                padding-right: 0px !important;
            }
            .eyeViewbtn { padding: 0.3rem 0rem !important; width: 125px; margin: 5px 0px; }            
        }        
        /* Portrait and Landscape */
        @media only screen 
        and (min-device-width: 320px) 
        and (max-device-width: 568px)
        and (-webkit-min-device-pixel-ratio: 2) {
            .cp { padding:5px }
            .csearch { width:300px; }
            .btn-sm {
                font-size: 1.7rem !important;
                padding: 0rem 1rem !important;
                margin: 5px 0 5px 0;
                width: 280px;
            }
            .dataTables_wrapper .dataTables_filter input { height: 45px; }
            .btn-group > .btn { padding: 7px 25px; font-size: 20px; }
            div.table-responsive>div.dataTables_wrapper>div.row>div[class^=col-]:last-child {
            padding-right: 15px;
        }
        }
        @media only screen 
        and (min-device-width: 375px) 
        and (max-device-width: 812px) 
        and (-webkit-min-device-pixel-ratio: 3){
            .cp { padding:5px }
            .csearch { width:300px; }
            .btn-sm {
                font-size: 1.7rem !important;
                padding: 0rem 1rem !important;
                margin: 5px 0 5px 0;
                width: 280px;
            }
            .dataTables_wrapper .dataTables_filter input { height: 45px; }
            .btn-group > .btn { padding: 7px 25px; font-size: 20px; }
            div.table-responsive>div.dataTables_wrapper>div.row>div[class^=col-]:last-child { padding-right: 15px; }
        }

        @media (min-width: 768px) and (max-width: 1024px) {
            .cp { padding:5px }
            .csearch { width:300px; }
            .btn-sm {
                font-size: 1.7rem !important;
                padding: 0rem 1rem !important;
                margin: 5px 0 5px 0;
                width: 280px;
            }
            .dataTables_wrapper .dataTables_filter input { height: 45px; }
            .btn-group > .btn { padding: 7px 25px; font-size: 20px; }
        }
    </style>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12"><h4 class="c-grey-900 mB-5">Brand List</h4></div>

                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                    <button  type="button" class="btn btn-primary pull-right btn-sm newAddBtn" data-toggle="modal" data-target="#AddProductModal">Add Brand</button>
                </div>
            </div>
        </div>

        <div class="card-body">
            <!--Add New Product Modal Start -->
            @include('admin.product.result_data')
            <!--Add New Product Modal End -->

            <!--Add New Product Modal Start -->
            @include('admin.product.add_product')
            <!--Add New Product Modal End -->

            <!--Edit & Update Modal Start -->
            @include('admin.product.edit_product')
            <!--Edit & Update Modal End -->

            <!--Stock Maintaince Modal Start -->
            @include('admin.product.product_stock')
            <!--Stock Maintaince Modal End -->

            <!--Product View Start -->
            @include('admin.product.view_product_details')
            <!--Product View End -->
        </div>
    </div>

@endsection

@section('page-scripts')
    <script type="text/javascript">
        //Product Information Modal Status Update Option 
        jQuery('.product-toggle-class').change(function(e) {
            e.preventDefault();
            var status = jQuery(this).prop('checked') == true ? 1 : 0; 
            var ProductId = jQuery(this).data('id');
            var url = "productStatus"+"/"+ProductId;
            jQuery.ajax({
                url:url,
                type:"GET",
                dataType:'JSON',
                cache: false,
                contentType: false,
                processData: false,
                success:function(response){
                    if (response.success) {
                        Notiflix.Notify.Success( 'Data Update Successfull' );
                    }                
                    if (response.error) {
                        Notiflix.Notify.Failure( 'Data Update Failed' );
                    }
                }
            });
        });

        // Get Product Data AS MySql View Page   
        function getProductData() {
            var query = $('#serach').val();
            var column_name = $('#hidden_column_name').val();
            var sort_type = $('#hidden_sort_type').val();
            var page = $('#hidden_page').val();
            //var url = "product";
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
        jQuery('#AddProduct').submit(function(e) {
            e.preventDefault();
            jQuery('#model-number-error').html("");
            // jQuery('#code-error').html("");
            jQuery('#mrp-error').html("");
            jQuery('#msdp-error').html("");
            jQuery('#msrp-error').html("");
            jQuery.ajax({
                url:"product.add",
                method:"POST",
                data:new FormData(this),
                dataType:'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success:function(response) {
                    console.log(response);
                    if (response.errors) {
                        if (response.errors.product_model) {
                            jQuery( '#model-number-error' ).html(response.errors.product_model[0]);
                        }
                        // if (response.errors.product_code) {
                        //     jQuery('#code-error').html(response.errors.product_code[0]);
                        // }
                        if (response.errors.mrp_price) {
                            jQuery( '#mrp-error' ).html(response.errors.mrp_price[0]);
                        }
                        if (response.errors.msdp_price) {
                            jQuery('#msdp-error').html(response.errors.msdp_price[0]);
                        }
                        if (response.errors.msrp_price) {
                            jQuery('#msrp-error').html(response.errors.msrp_price[0]);
                        }
                        if (response.errors.product_id) {
                            Notiflix.Notify.Failure('Invalid Product.Please Contact Your Higher Authorized');
                        }
                    }
                    if (response == "error") {
                        Notiflix.Notify.Failure('Prduct Add Failed');
                    }
                    if (response == "success") {
                        jQuery("#AddProduct")[0].reset();
                        jQuery(".btnCloseModal").click();
                        Notiflix.Notify.Success('Prduct Add Successfull');
                        return getProductData();
                    }
                },
                error:function(error) {
                    Notiflix.Notify.Failure('Prduct Add Failed');
                }
            });
        });

        // Edit Product Data
        jQuery(document).on("click","#editProductInfo",function(e){
            e.preventDefault();
            var ProductId = jQuery(this).data('id');
            var url = "product.edit"+"/"+ProductId;
            jQuery.ajax({
                url:url,
                type:"GET",
                dataType:"JSON",
                success: function(response) {
                    console.log(response);
                    jQuery('.edit-form-div').html(response);
                    jQuery('.select2').select2();
                }
            });
        });

        // Update Product Data
        jQuery(document).on("submit",'#UpdateProduct', function(arg){
            arg.preventDefault();
            jQuery.ajaxSetup({
                headers: {'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')}
            });

            jQuery('#update-model-number-error').html("");
            jQuery('#update-code-error').html("");
            jQuery('#update-mrp-error').html("");
            jQuery('#update-msdp-error').html("");
            jQuery('#update-msrp-error').html("");

            var formData = new FormData(this);

            jQuery.ajax({
                url:"product.update",
                type:"POST",
                data:formData,
                dataType:'JSON',
                cache: false,
                contentType: false,
                processData: false,
                success:function(response) {
                    if (response.errors) {
                        if (response.errors.product_model) {
                            jQuery( '#update-model-number-error' ).html( response.errors.product_model[0] );
                        }
                        if (response.errors.product_code) {
                            jQuery( '#update-code-error' ).html( response.errors.product_code[0] );
                        }
                        if (response.errors.mrp_price) {
                            jQuery( '#update-mrp-error' ).html( response.errors.mrp_price[0] );
                        }
                        if (response.errors.msdp_price) {
                            jQuery( '#update-msdp-error' ).html( response.errors.msdp_price[0] );
                        }
                        if (response.errors.msrp_price) {
                            jQuery( '#update-msrp-error' ).html( response.errors.msrp_price[0] );
                        }
                    }
                    if (response == "success") {
                        jQuery(".btnCloseModal").click();
                        Notiflix.Notify.Success( 'Data Update Successfull' );
                        return getProductData();
                        console.log(response);
                        Notiflix.Loading.Remove(600);
                    }                
                    if (response == "error") {
                        Notiflix.Notify.Failure( 'Data Update Failed' );
                        console.log(response);
                    }
                }
            });
        });

        // Product Stock Maintain Modal Start
        jQuery(document).on("click","#productStock",function(e){
            e.preventDefault();
            $('#default_qty').val();
            $('#yeallow_qty').val();
            $('#red_qty').val();
            $('#productId').val();
            var productId = jQuery(this).data('id');
            var url = "productStockEdit"+"/"+productId;
            jQuery.ajax({
                url:url,
                type:"GET",
                dataType:"JSON",
                async:false,
                cache: false,
                contentType: false,
                processData: false,
                success:function(response) {
                    console.log(response);
                    jQuery('.product-stock-div').html(response);
                }
            });
        });

        jQuery(document).on('submit','#saveProductStockMaintain', function(e){
          e.preventDefault();
          jQuery.ajax({
            url:"saveProductStockMaintain",
            method:"POST",
            data:new FormData(this),
            dataType:'JSON',
            contentType: false,
            cache: false,
            processData: false,
            success:function(response){
                if(response == 'success') {
                    Notiflix.Notify.Success('Save Successfully');
                    // setTimeout(function(){// wait for 5 secs(2)
                    //     window.location.reload(); // then reload the page.(3)
                    //     $(".btnCloseModal").click();
                    // }, 200);
                    $(".btnCloseModal").click();
                }

                if(response == 'error') {
                    Notiflix.Notify.Warning( 'Save Failed' );
                }
            },
            error:function(error){
              Notiflix.Notify.Failure( 'Something Went Wrong.Please Try Again' );
            }
          });
        });

        // View Product Details
        jQuery(document).on("click","#viewProductDetails",function(e){
            e.preventDefault();
            $('#productResultInfo').html('');
            var productId = jQuery(this).data('id');
            var url = "product.show"+"/"+productId;
            jQuery.ajax({
                url:url,
                type:"GET",
                dataType:"JSON",
                beforeSend: function() {
                    Notiflix.Loading.Arrows('Data Processing');
                    Notiflix.Loading.Remove(300);
                },
                success:function(response) {
                    console.log(response);
                    jQuery('.product-details-table').html(response);
                }
            });
        });
    </script>
@endsection
