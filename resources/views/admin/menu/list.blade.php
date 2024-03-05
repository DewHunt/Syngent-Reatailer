@extends('admin.master.master')

@section('page-style')
    <style>
        .cp { padding:5px }
        .csearch { width:225px; }
        .cbtnwh { width: 100px; height: 35px; }
        .top-margin { margin-top: 0.5rem; }
        /* Portrait and Landscape */
        @media only screen 
        and (min-device-width: 320px) 
        and (max-device-width: 568px)
        and (-webkit-min-device-pixel-ratio: 2) {
            .modal-body select.form-control:not([size]):not([multiple]) { height: calc(3.0625rem + 2px) !important; }
            .cp { padding:5px }
            .csearch { width:300px; }
            .cbtnwh { width: 300px; height: 80px; }
            .top-margin { margin-top: 0.5rem; }
        }
        /* Portrait and Landscape */
        @media only screen 
        and (min-device-width: 375px) 
        and (max-device-width: 812px) 
        and (-webkit-min-device-pixel-ratio: 3) { 
            .modal-body select.form-control:not([size]):not([multiple]) { height: calc(3.0625rem + 2px) !important; }
            .cp { padding:5px }
            .csearch { width:300px; }
            .cbtnwh { width: 300px; height: 80px; }
            .top-margin { margin-top: 0.5rem; }
        }
        @media (min-width: 768px) and (max-width: 1024px) {
            .modal-body select.form-control:not([size]):not([multiple]) { height: calc(3.0625rem + 2px) !important; }
            .cp { padding:5px }
            .csearch { width:300px; }
            .top-margin { margin-top: 0.5rem; }
        }
    </style>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12"><h4 class="c-grey-900 mB-20">Menu List</h4></div>
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                    <button  type="button" class="btn btn-primary pull-right btn-sm cbtnwh" data-toggle="modal" data-target="#AddMenuModal" style="margin-left: 5px">Add Menu</button>
                </div>
            </div>
        </div>

        <div class="card-body">
            <!--Menu List Start -->
            @include('admin.menu.result_data')
            <!--Menu List End -->

            <!--Add New Menu Modal Start -->
            @include('admin.menu.add_menu')
            <!--Add New Menu Modal End -->

            <!--Edit & Update Modal Start -->
            @include('admin.menu.edit_menu')
            <!--Edit & Update Modal End -->
        </div>
    </div>
@endsection

@section('page-scripts')
    <script type="text/javascript">
        //Menu Module Start
        $(document).ready(function() {
            jQuery('#parentMenuId').select2({dropdownParent: jQuery('#AddMenuModal')});
        });

        jQuery('#AddMenu').submit(function(e){
            e.preventDefault();
            jQuery.ajax({
                url: "{{ route('menu.save') }}",
                method:"POST",
                data:new FormData(this),
                dataType:'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success:function(response){
                    if (response == 'success') {
                        jQuery("#AddMenu")[0].reset();
                        Notiflix.Notify.Success('Menu Save Successfully');
                        setTimeout(function(){
                            window.location.reload();
                            $(".btnCloseModal").click();
                        }, 200);
                    }

                    if(response == 'error') {
                        Notiflix.Notify.Warning('Menu Save Failed');
                    }
                },
                error:function(error){
                    Notiflix.Notify.Failure('Something Went Wrong.Please Try Again');
                }
            });
        });

        jQuery('.menu-toggle-class').change(function(e) {
            e.preventDefault();
            var status = jQuery(this).prop('checked') == true ? 1 : 0; 
            var menuId = jQuery(this).data('id');
            var url = "changeStatus"+"/"+menuId;
            jQuery.ajax({
                url:url,
                type:"GET",
                dataType:'JSON',
                cache: false,
                contentType: false,
                processData: false,
                success:function(response) {
                    if (response == 'success') {
                        Notiflix.Notify.Success('Menu Update Successfully' );
                    }
                
                    if (response == 'error') {
                        Notiflix.Notify.Failure('Menu Update Failed.Please Try Again');
                    }
                }
            });
        });

        // Edit Data
        jQuery(document).on("click","#editmenu",function(e){
            e.preventDefault();
            var menuId = jQuery(this).data('id');
            var url = "editMenu"+"/"+menuId;
            jQuery.ajax({
                url:url,
                type:"GET",
                dataType:"JSON",
                beforeSend: function() {
                    Notiflix.Loading.Arrows('Data Processing');
                },
                success:function(response) {
                    console.log(response);
                    jQuery('.edit-form-div').html(response);
                    jQuery('.select2').select2();
                    Notiflix.Loading.Remove(300);
                }
            });
        });

        // Update Data
        jQuery(document).on("submit",'#UpdateMenu', function(arg){
            arg.preventDefault();
            jQuery.ajaxSetup({
                headers: {
                  'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                }
            });
            var formData = new FormData(this);
            jQuery.ajax({
                url:"updateMenu",
                type:"POST",
                data:formData,
                dataType:'JSON',
                cache: false,
                contentType: false,
                processData: false,
                success:function(response){
                    if (response == "success") {
                        jQuery("#UpdateMenu")[0].reset();
                        Notiflix.Notify.Success('Menu Update Successfully');
                        setTimeout(function(){// wait for 5 secs(2)
                            window.location.reload(); // then reload the page.(3)
                            $(".btnCloseModal").click();
                        }, 200);
                    }
                
                    if (response == "error") {
                        Notiflix.Notify.Failure( 'Data Update Failed' );
                    }
                },
                error:function(error) {
                  jQuery("#UpdateMenu")[0].reset();
                  Notiflix.Notify.Failure( 'Data Update Failed' );
                }
            });
        });

        jQuery(document).on('click','#deletemenu',function(){
            let id = jQuery(this).attr('data-id');

            jQuery.ajax({
                type: "POST",
                url: "{{ route('deleteMenu') }}",
                data:{_token:'{{ csrf_token() }}',id},
                beforeSend: function() {
                    Notiflix.Loading.Arrows('Data Processing');
                },
                success: function(response) {
                    if (response == "success") {
                        $('.row_'+id).remove();
                        Notiflix.Notify.Success('Menu Deleted Successfully');
                    } else if (response == "error") {
                        Notiflix.Notify.Failure('Menu Deleyion Failed');
                    }
                    Notiflix.Loading.Remove(300);
                },
            });
        });
        // Menu Module End
    </script>
@endsection