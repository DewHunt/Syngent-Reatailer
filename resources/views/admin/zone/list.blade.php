@extends('admin.master.master')

@section('page-style')
    <style>
        .cp { padding:5px }
        .csearch { width:285px; }
        /* 
          ##Device = Desktops
          ##Screen = 1281px to higher resolution desktops
        */
        @media (min-width: 1281px) {
            .main-content .btn-group-sm>.btn, .btn-sm {
                font-size: 0.90rem !important;
                padding: 0.3rem 0rem !important;
                width: 155px;
                margin: 5px 0px;
            }
            .beforeAddBtn { padding: 0px 0px; }
            .newAddBtn{
                margin-left: 5px;
                width: 155px;
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
            .cp { padding:5px }
            .csearch { width:300px; }
            .btn-sm {
                font-size: 1.7rem !important;
                padding: 0rem 1rem !important;
                margin: 5px 15px 5px 0;
                width: 285px;
            }
            .dataTables_wrapper .dataTables_filter input { height: 45px; }
            .btn-group > .btn { padding: 7px 25px; font-size: 20px; }
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
                margin: 5px 15px 5px 0;
                width: 285px;
            }
            .dataTables_wrapper .dataTables_filter input { height: 45px; }
            .btn-group > .btn { padding: 7px 25px; font-size: 20px; }
        }
        @media (min-width: 768px) and (max-width: 1024px) {
            .cp { padding:5px }
            .csearch { width:300px; }
            .btn-sm {
                font-size: 1.7rem !important;
                padding: 0rem 1rem !important;
                margin: 5px 15px 5px 0;
                width: 277px;
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
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12"><h4 class="c-grey-900">Zone List</h4></div>

                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                    <button  type="button" class="btn btn-primary pull-right newAddBtn" data-toggle="modal" data-target="#AddZoneModal">Add Zone</button>
                </div>
            </div>
        </div>

        <div class="card-body">
            <!-- Zone Modal Start -->
            @include('admin.zone.result_data')
            <!-- Add New Zone Modal End -->

            <!-- Add New Zone Modal Start -->
            @include('admin.zone.add_zone')
            <!-- Add New Zone Modal End -->

            <!-- Edit & Update Modal Start -->
            @include('admin.zone.edit_zone')
            <!-- Edit & Update Modal End -->
        </div>
    </div>
@endsection

@section('page-scripts')
    <script type="text/javascript">
        //Employee Information Modal Status Update Option 
        jQuery('.zone-toggle-class').change(function(e) {
            e.preventDefault();
            var status = jQuery(this).prop('checked') == true ? 1 : 0; 
            var ZoneId = jQuery(this).data('id');
            var url = "zone.status"+"/"+ZoneId;
            jQuery.ajax({
                url:url,
                type:"GET",
                dataType:'JSON',
                cache: false,
                contentType: false,
                processData: false,
                success:function(response){
                    if(response.success) {
                        Notiflix.Notify.Success( 'Data Update Successfull' );
                    }
                
                    if(response.error) {
                        Notiflix.Notify.Failure( 'Data Update Failed' );
                    }
                }
            });
        });

        // Get  Data AS MySql View Page   
        function getZoneData(){
            var query = $('#serach').val();
            var column_name = $('#hidden_column_name').val();
            var sort_type = $('#hidden_sort_type').val();
            var page = $('#hidden_page').val();
            //var url = "zone";
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

        // Add New Data
        jQuery('#AddZone').submit(function(e) {
            e.preventDefault();
            jQuery('#name-error').html("");
            jQuery.ajax({
                url:"zone.add",
                method:"POST",
                data:new FormData(this),
                dataType:'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success:function(response) {
                    if (response.errors) {
                        if (response.errors.zone_name) {
                            jQuery( '#name-error' ).html( response.errors.zone_name[0] );
                        }
                    }
                    if (response == "success") {
                        jQuery("#AddZone")[0].reset();
                        Notiflix.Notify.Success( 'Data Insert Successfull' );
                        return getZoneData();
                    }
                },
                error:function(error) {
                    Notiflix.Notify.Failure( 'Data Insert Failed' );
                }
            });
        });

        // Edit  Data
        jQuery(document).on("click","#editZoneInfo",function(e){
            e.preventDefault();
            var ZoneId = jQuery(this).data('id');
            var url = "zone.edit"+"/"+ZoneId;
            jQuery.ajax({
                url:url,
                type:"GET",
                dataType:"JSON",
                beforeSend: function() {
                    Notiflix.Loading.Arrows('Data Processing');
                },
                success:function(response){
                    console.log(response);
                    jQuery('.edit-form-div').html(response);
                    Notiflix.Loading.Remove(300);
                }
            });
        });

        // Update Data
        jQuery(document).on("submit",'#UpdateZone', function(arg){
            arg.preventDefault();
            var formData = new FormData(this);            
            jQuery.ajax({
                url:"zone.update",
                type:"POST",
                data:formData,
                dataType:'JSON',
                cache: false,
                contentType: false,
                processData: false,
                success:function(response){
                    if (response == "success") {
                        Notiflix.Notify.Success( 'Data Update Successfull' );
                        return getZoneData();
                    }                
                    if (response == "error") {
                        jQuery("#UpdateZone")[0].reset();
                        Notiflix.Notify.Failure( 'Data Update Failed' );
                    }
                },
                error:function(error) {
                  Notiflix.Notify.Failure( 'Data Update Failed' );
                }
            });
        });

        jQuery(document).on('click','#deleteZone',function(){
            let id = jQuery(this).attr('data-id');

            jQuery.ajax({
                type: "POST",
                url: "{{ route('zone.delete') }}",
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
    </script>
@endsection