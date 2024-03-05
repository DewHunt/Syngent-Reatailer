@extends('admin.master.master')

@section('page-style')
    <style>
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
            .beforeAddBtn { padding: 0px 7px; }
            .newAddBtn {
                margin-left: 5px;
                width: 100px;
                margin: 0px;
                padding-left: 0px !important;
                padding-right: 0px !important;
            }
            .bannerPhoto { width: 100%;height: 100%; } 
        }
        /* Portrait and Landscape */
        @media only screen 
        and (min-device-width: 320px) 
        and (max-device-width: 568px)
        and (-webkit-min-device-pixel-ratio: 2) {
            .cebtn { width: 200px; text-align: center; }
            .bannerPhoto { width: 100%; height: 100%; }
        }
        @media only screen 
            and (min-device-width: 375px) 
            and (max-device-width: 812px) 
            and (-webkit-min-device-pixel-ratio: 3) {
                .cebtn { width: 200px; text-align: center; }
                .bannerPhoto { width: 100%; height: 100%; }
            }
        @media (min-width: 768px) and (max-width: 1024px) {
            .cebtn { width: 200px; text-align: center; }
            .bannerPhoto { width: 100%; height: 100% !important; }
            .card { border: none; }
        }
    </style>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12"><h4 class="c-grey-900 mB-20">Banner List</h4></div>

                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                    <button type="button" class="btn btn-primary pull-right btn-sm newAddBtn" data-toggle="modal" data-target="#AddBannerModal">Add Banner</button>
                </div>
            </div>
        </div>

        <div class="card-body">
            <!--Add Modal Start -->
            @include('admin.banner.result_data')
            <!--Add Modal End -->

            <!--Add Modal Start -->
            @include('admin.banner.add_banner')
            <!--Add Modal End -->

            <!--Edit & Update Modal Start -->
            @include('admin.banner.edit_banner')
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
                                    <img id="photoId" width="450px" src=""  alt="photo" class="bannerPhoto" />
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
        </div>
    </div>
@endsection

@section('page-scripts')
    <script type="text/javascript">
        //Get Banner Data
        function getBannerData() {
            var url = "banner";
            jQuery('.loading').show();
            jQuery.ajax({
                url:url,
                type:"GET",
                dataType:"HTMl",
                success:function(response) {
                    setTimeout(function() {
                        window.location.reload(true);
                    }, 200);
                },
            });
        }

        // Add Banner Data
        jQuery('#AddBanner').submit(function(e){
            e.preventDefault();
            jQuery.ajax({
                url:"banner.add",
                method:"POST",
                data:new FormData(this),
                dataType:'JSON',
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function() {
                    Notiflix.Loading.Arrows('Data Processing');
                },
                success:function(response) {
                    Notiflix.Loading.Remove(300);
                    if (response.errors) {
                        if (response.errors.banner_pic) {
                            jQuery('.banner-error').html( response.errors.banner_pic );
                        }
                        if (response.errors.status) {
                            jQuery('.status-error').html( response.errors.status );
                        }
                    }
                    if (response == "error") {
                        Notiflix.Notify.Failure('Banner Save Failed');
                        Notiflix.Loading.Remove(600);
                    }
                    if (response == "success") {
                        Notiflix.Notify.Success('Banner Save Successfull');
                        Notiflix.Loading.Remove();
                        setTimeout(function() {
                            window.location.reload(true);
                        }, 500);
                    }
                    if (response.fail) {
                        if(response.errors.name){
                            jQuery('#error_field').addClass('has-error');
                            jQuery('#error-name').html( response.errors.name[0] );
                            Notiflix.Notify.Failure( 'Banner Save Failed' );
                            Notiflix.Loading.Remove(600);
                        }
                    }
                },
                error:function(error) {
                    Notiflix.Notify.Failure('Banner Save Failed');
                    Notiflix.Loading.Remove(600);
                }
            });
        });

        //Edit Banner
        jQuery(document).on("click","#editBannerInfo",function(e) {
            e.preventDefault();
            var bannerId = jQuery(this).data('id');
            var url = "banner.edit"+"/"+bannerId;
            jQuery.ajax({
                url:url,
                type:"GET",
                dataType:"JSON",
                success:function(response) {
                    console.log(response);
                    jQuery('.edit-form-div').html(response);
                }
            });
        });

        //Update Banner
        jQuery(document).on("submit",'#UpdateBanner', function(arg){
            arg.preventDefault();
            jQuery.ajaxSetup({
                headers: {'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')}
            });
            var formData = new FormData(this);
            jQuery.ajax({
                url:"banner.update",
                type:"POST",
                data:formData,
                dataType:'JSON',
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    Notiflix.Loading.Arrows('Data Processing');
                },
                success:function(response) {
                    Notiflix.Loading.Remove(300);
                    if (response == "success") {
                        Notiflix.Notify.Success('Banner Update Successfull');
                        Notiflix.Loading.Remove();
                        setTimeout(function() {
                            window.location.reload(true);
                        }, 500);
                        return getBannerData();
                    }
                    if (response == "warning") {
                        Notiflix.Notify.Warning( 'Banner Update Failed Maximum 4 Banner Activation Allowed' );
                        Notiflix.Loading.Remove(600);
                        return getBannerData();
                    }
                },
                error:function(error) {
                    Notiflix.Notify.Failure('Banner Update Failed');
                }
            });
        });

        //Banner Modal Status
        jQuery('.banner-toggle-class').change(function(e) {
            e.preventDefault();
            var status = jQuery(this).prop('checked') == true ? 1 : 0;
            var updateId = jQuery(this).data('id');
            var url = "banner.status"+"/"+updateId;
            jQuery.ajax({
                url:url,
                type:"GET",
                dataType:'JSON',
                cache: false,
                contentType: false,
                processData: false,
                success:function(response) {
                    if (response.success) {
                        Notiflix.Notify.Success( 'Banner Status Update Successfull' );
                        Notiflix.Loading.Remove(600);
                        return getBannerData();
                    }                
                    if (response.error) {
                        Notiflix.Notify.Failure( 'Banner Status Update Failed' );
                        Notiflix.Loading.Remove(600);
                    }                    
                    if (response.warning) {
                        Notiflix.Notify.Warning( 'Banner Status Update Failed Maximum 4 Banner Activation Allowed' );
                        setTimeout(function(){
                            window.location.reload(true); 
                        },5000);
                        //window.location.reload(true);
                    }
                }
            });
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
            
            jQuery(document).on('click', '.pagination a', function(event){
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

        jQuery(document).on('click','.delete-img',function() {
            let id = jQuery(this).attr('data-id');

            jQuery.ajax({
                type: "POST",
                url: "{{ route('banner.destroy') }}",
                data:{_token:'{{ csrf_token() }}',id},
                beforeSend: function() {
                    Notiflix.Loading.Arrows('Data Processing');
                },
                success: function(response) {
                    if (response == "success") {
                        $('.row_'+id).remove();
                        Notiflix.Notify.Success('Image Deleted Successfully');
                    } else if (response == "error") {
                        Notiflix.Notify.Failure('Image Deletion Failed');
                    }
                    Notiflix.Loading.Remove(300);
                },
            });
        });
    </script>
    <!--Pagination New Script Start-->
@endsection