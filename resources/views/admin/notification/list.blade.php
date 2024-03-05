@extends('admin.master.master')

@section('page-style')
    <style>
        .notification-response{ display: flex; flex-wrap: wrap; }
        .success { margin-left:10px; color: red; }
        .failure { margin-left:10px; color: red; }
        .cp { padding:5px }
        .csearch { width:285px; }
        .btnCommonWidth { width: 40% !important; font-size: 14px !important; }
        .btn-sm {
            padding: 4px 8px;
            padding: 0.25rem 0.5rem;
            font-size: 12.249px;
            font-size: .76562rem;
            line-height: 2;
            border-radius: 0.2rem;
        }
        /* Portrait and Landscape */
        @media only screen 
        and (min-device-width: 320px) 
        and (max-device-width: 568px)
        and (-webkit-min-device-pixel-ratio: 2) {
            .cp { padding:5px }
            .csearch { width:285px; margin-right: 10px; }
            .select2-container--default .select2-search--inline .select2-search__field{ width: auto !important; }
            .btn-sm {
                font-size: 1.7rem !important;
                padding: 0.5rem 1rem !important;
                margin: 5px 0 5px 0;
                width: 285px;
            }
            .dataTables_wrapper .dataTables_filter input { height: 45px; }
        }
        @media only screen 
        and (min-device-width: 375px) 
        and (max-device-width: 812px) 
        and (-webkit-min-device-pixel-ratio: 3){
            .cp { padding:5px }
            .csearch { width:285px; }
            .select2-container--default .select2-search--inline .select2-search__field{ width: auto !important; }
            .btn-sm {
                font-size: 1.7rem !important;
                padding: 0.5rem 1rem !important;
                margin: 5px 0 5px 0;
                width: 285px;
            }
            .dataTables_wrapper .dataTables_filter input { height: 45px; }
        }
        @media (min-width: 768px) and (max-width: 1024px) {
            .cp { padding:5px }
            .csearch { width:285px; margin-right: 10px; }
            .select2-container--default .select2-search--inline .select2-search__field{ width: auto !important; }
            .btnCommonWidth { width: 100% !important; }
            .btn-sm {
                font-size: 1.7rem !important;
                padding: 0.5rem 1rem !important;
                margin: 5px 0 5px 0;
                width: 285px;
            }
            .main-content .form-group .form-control { padding: 0.5rem 0.75rem !important; font-size: 2rem !important; }
        }
    </style>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                    <h4 class="c-grey-900 mB-20">Notification List</h4>
                </div>

                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                    <button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#AddPushNotificationModal">Add Notification</button>  
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row cp">
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12"></div>
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 text-right">
                    <label>Search:</label>&nbsp;&nbsp;
                    <input type="text" name="serach" id="serach" class="form-control pull-right csearch" style="width: 70%;" />
                </div>
            </div>
            
            <div id="tag_container" class="table-responsive">
                <table class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th width="50px">Sl.</th>
                            <th>Title</th>
                            <th>Message</th>
                            <th width="150px">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @include('admin.notification.result_data')
                    </tbody>
                </table>
                <input type="hidden" name="hidden_page" id="hidden_page" value="1" />
                <input type="hidden" name="hidden_column_name" id="hidden_column_name" value="id" />
                <input type="hidden" name="hidden_sort_type" id="hidden_sort_type" value="asc" />
            </div>
        </div>
    </div>

    <!--Add New PreBooking Modal Start -->
    @include('admin.notification.add')
    <!--Add New Pre Booking Modal End -->

    <!--Edit & Update Modal Start -->
    @include('admin.notification.edit')
    <!--Edit & Update Modal End -->

    <!--Send Modal Start -->
    <div class="modal fade" id="getPushNotificationModal" tabindex="-3" role="dialog" aria-labelledby="getPushNotificationModal" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProductModal">Send Notification</h5>
                    <span id="success"></h2></span>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <button onclick="startFCM()"
                        class="btn btn-danger btn-flat" style="display:none">Allow notification
                </button>

                <form class="form-horizontal" method="POST" action="" id="SendPushNotification">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-2 pushresponse">
                                <div class="notification-response">
                                    <h6>Success :</h6> <span class="success"></span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-2 pushresponse">
                                <div class="notification-response">
                                    <h6>Failure :</h6> <span class="failure"></span>
                                </div>
                            </div>


                            <div class="col-md-12 mb-2">
                                <label>Title <span class="required">*</span></label>
                                <input type="text" name="title" class="form-control sendTitle" required=""/>
                            </div>
                            <div class="col-md-12 mb-2">
                                <label>Message <span class="required">*</span></label>
                                <textarea name="body" class="form-control sendMessage" required="" cols="3" rows="2"></textarea>
                            </div>
                        </div>

                        <div class="row" style="display:none">
                            <div class="col-md-6 mb-2">
                                <label>Zone</label>
                                <select class="select2" multiple="multiple" data-placeholder="Select Zone" data-dropdown-css-class="select2-purple" style="width: 100%;" id="sendZone"  name="zone" required="">
                                    <option value="">Select Zone</option>
                                    <option value="all">All</option>
                                    @if(isset($zoneList))
                                    @foreach($zoneList as $row)
                                    <option value="{{ $row->zone_name }}">{{ $row->zone_name }}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label>Category</label>
                                <select class="select2" multiple="multiple" data-placeholder="Select Category" style="width: 100%;" id="sendCategory" name="category[]" required="">
                                    <option value="">Select Category</option>
                                    <option value="all">All</option>
                                    <option value="a">A</option>
                                    <option value="b">B</option>
                                    <option value="c">C</option>
                                    <option value="d">D</option>
                                </select>
                            </div>
                        </div>

                        <div class="row" style="display:none">
                            <div class="col-md-6 mb-2">
                                <label>Message Group</label>
                                <select class="select2" multiple="multiple" data-placeholder="Select Group" style="width: 100%;"  id="sendMessageGroup" name="message_group[]" required="">
                                    <option value="">Select Group</option>
                                    <option value="all">All</option>
                                    <option value="bp">BP</option>
                                    <option value="retailer">Retailer</option>
                                </select>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="send_id" id="sendId"/>
                        <button type="button" class="btn btn-secondary btnCloseModal" data-dismiss="modal">Close</button> 
                        <button type="submit" class="btn btn-primary">Send</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--Send Modal End -->
@endsection

@section('page-scripts')
    <script type="text/javascript">
        jQuery('.push-notification-toggle-class').change(function(e) {
            e.preventDefault();
            var status = jQuery(this).prop('checked') == true ? 1 : 0; 
            var getId = jQuery(this).data('id');
            var url = "pushNotification.status"+"/"+getId;
            jQuery.ajax({
                url:url,
                type:"GET",
                dataType:'JSON',
                cache: false,
                contentType: false,
                processData: false,
                success:function(response){
                    if(response.success){
                        Notiflix.Notify.Success( 'Notification Update Successfull' );
                    }
                
                    if(response.error){
                        Notiflix.Notify.Failure( 'Notification Update Failed' );
                    }
                }
            });
        });

        function getPushNotificationData() {
            var query = $('#serach').val();
            var column_name = $('#hidden_column_name').val();
            var sort_type = $('#hidden_sort_type').val();
            var page = $('#hidden_page').val();
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

        // Add Data
        $('#AddPushNotification').submit(function(e){
            e.preventDefault();
            jQuery('#title-error').html("");
            jQuery('#message-error').html("");
            jQuery.ajax({
                url:"pushNotification.add",
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
                    console.log(response);
                    if (response.errors) {
                        if (response.errors.title) {
                            jQuery( '#title-error' ).html( response.errors.title[0] );
                        }
                        if (response.errors.message) {
                            jQuery( '#message-error' ).html( response.errors.message[0] );
                        }
                    }

                    if (response == "error") {
                        Notiflix.Notify.Failure('Notification Save Failed');
                    }

                    if (response == "success") {
                        jQuery("#AddPushNotification")[0].reset();
                        jQuery(".btnCloseModal").click();
                        Notiflix.Notify.Success('Notification Save Successfull');
                        return getPushNotificationData();
                    }
                },
                error:function(error)   {
                    Notiflix.Notify.Failure('Notification Save Failed');
                }
            });
        });

        // Edit Data
        jQuery(document).on("click","#editPushNotificationInfo",function(e){
            e.preventDefault();
            var getId = jQuery(this).data('id');
            var url = "pushNotification.edit"+"/"+getId;
            jQuery.ajax({
                url:url,
                type:"GET",
                dataType:"JSON",
                beforeSend: function() {
                    Notiflix.Loading.Arrows('Data Processing');
                },
                success:function(response)  {
                    console.log(response);
                    jQuery('.edit-form-div').html(response);
                    jQuery('.select2').select2();
                    Notiflix.Loading.Remove(100);
                    // jQuery('#updateId').val(response.id);
                    // jQuery('.getTitle').val(response.title);
                    // jQuery('.getMessage').val(response.message);
                    // jQuery('#getZone').val(JSON.parse(response.zone)).change();
                    // jQuery('#getCategory').val(JSON.parse(response.category)).change();
                    // jQuery('#getMessageGroup').val(JSON.parse(response.message_group)).change();
                    // if (response.status == 1){
                    //     jQuery("#option1").prop("checked", true);
                    // } else {
                    //     jQuery("#option2").prop("checked", true);
                    // }
                }
            });
        });

        // Update  Data
        jQuery(document).on("submit",'#UpdatePushNotification', function(arg){
            arg.preventDefault();
            jQuery('#update-title-error').html("");
            jQuery('#update-message-error').html("");
            var formData = new FormData(this);
            
            jQuery.ajax({
                url:"pushNotification.update",
                type:"POST",
                data:formData,
                dataType:'JSON',
                cache: false,
                contentType: false,
                processData: false,
                success:function(response)  {
                    console.log(response);
                    if (response.errors) {
                        if (response.errors.title) {
                            jQuery( '#update-title-error' ).html( response.errors.title[0] );
                        }
                        if (response.errors.message) {
                            jQuery( '#update-message-error' ).html( response.errors.message[0] );
                        }
                    }

                    if (response == "success") {
                        jQuery(".btnCloseModal").click();
                        Notiflix.Notify.Success('Notification Update Successfull');
                        return getPushNotificationData();
                        console.log(response);
                        Notiflix.Loading.Remove(600);
                    }
                
                    if (response == "error") {
                        Notiflix.Notify.Failure( 'Notification Update Failed' );
                        console.log(response);
                    }
                }
            });
        });

        // Get Notification Data
        jQuery(document).on("click","#getPushNotificationInfo",function(e){
            e.preventDefault();
            $('.success').html("");
            $('.failure').html("");
            var getId = jQuery(this).data('id');
            var url = "pushNotification.show"+"/"+getId;
            jQuery.ajax({
                url:url,
                type:"GET",
                dataType:"JSON",
                success:function(response)  {
                    console.log(response);
                    jQuery('#sendId').val(response.id);
                    jQuery('.sendTitle').val(response.title);
                    jQuery('.sendMessage').val(response.message);
                    jQuery('#sendZone').val(JSON.parse(response.zone)).change();
                    jQuery('#sendCategory').val(JSON.parse(response.category)).change();
                    jQuery('#sendMessageGroup').val(JSON.parse(response.message_group)).change();
                }
            });
        });

        // Send Notification Data
        jQuery('#SendPushNotification').submit(function(e) {
            e.preventDefault();
            $('.success').html("");
            $('.failure').html("");
            jQuery.ajax({
                url:"sendWebNotification",
                method:"POST",
                data:new FormData(this),
                dataType:'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success:function(response)  {
                    console.log(response);
                    //console.log(response.failure);
                    jQuery('.success').html(response.success);
                    jQuery('.failure').html(response.failure);
                    if (response == "error") {
                        Notiflix.Notify.Failure( 'Notification Send Failed' );
                    }
                    if (response.success) {
                        Notiflix.Notify.Success( 'Notification Send Successfull' );
                        // jQuery(".btnCloseModal").click();
                        setTimeout(function(){
                            window.location.reload();
                            $(".btnCloseModal").click();
                        }, 3000);
                    }
                },
                error:function(error) {
                    Notiflix.Notify.Failure( 'Notification Send Failed' );
                }
            });
        });

        // Pagination Script Start
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
            });
        }

        jQuery(document).ready(function() {
            var searhText = document.getElementById('serach');
            searhText.onkeydown = function() {
                var key = event.keyCode || event.charCode;
                if (key == 8) {
                    var getSearchVal = $('#serach').val();
                    var length = getSearchVal.length;
                    if (length <= 1) {
                        var query = $('#serach').val();
                        var column_name = $('#hidden_column_name').val();
                        var sort_type = $('#hidden_sort_type').val();
                        var page = $('#hidden_page').val();
                        fetch_data(page, sort_type, column_name, query);
                    }
                }
            };

            jQuery(document).on('keyup', '#serach', function() {
                var getSearchVal = $('#serach').val();
                var length = getSearchVal.length;
                if (length >= 3) {
                    var query = $('#serach').val();
                    var column_name = $('#hidden_column_name').val();
                    var sort_type = $('#hidden_sort_type').val();
                    var page = $('#hidden_page').val();
                    fetch_data(page, sort_type, column_name, query);
                }
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
        });
    </script>
    <!-- The core Firebase JS SDK is always required and must be listed first -->
    <script src="https://www.gstatic.com/firebasejs/8.6.8/firebase-app.js"></script>
    <!-- TODO: Add SDKs for Firebase products that you want to use https://firebase.google.com/docs/web/setup#available-libraries -->
    <script src="https://www.gstatic.com/firebasejs/8.6.8/firebase-analytics.js"></script>

    <script>
        // Your web app's Firebase configuration
        // For Firebase JS SDK v7.20.0 and later, measurementId is optional
        var firebaseConfig = {
            apiKey: "AIzaSyBZJUHDGO9-680krYewIjRTeurrg66cSDI",
            authDomain: "retailgear-89ee0.firebaseapp.com",
            projectId: "retailgear-89ee0",
            storageBucket: "retailgear-89ee0.appspot.com",
            messagingSenderId: "198099682584",
            appId: "1:198099682584:web:4319de0585a25515b04a6d",
            measurementId: "G-6H4PX7NTRH"
        };
        // Initialize Firebase
        firebase.initializeApp(firebaseConfig);
        firebase.analytics();

        function startFCM() {
            messaging.requestPermission().then(function () { return messaging.getToken() }).then(function (response) {
                $.ajaxSetup({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
                });
                $.ajax({
                    url: '{{ url("storeToken") }}',
                    type: 'POST',
                    data: { token: response },
                    dataType: 'JSON',
                    success: function (response) {
                        alert('Token stored.');
                    },
                    error: function (error) {
                        console.log(error);
                        alert(error);
                    },
                });
            }).catch(function (error) {
                alert(error);
            });
        }
    </script>
@endsection