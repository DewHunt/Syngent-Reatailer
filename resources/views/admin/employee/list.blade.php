@extends('admin.master.master')

@section('page-style')
    <style>
        .cp { padding: }
        .csearch { width:30 }
        .hide-table-col {
            display: none !important;
        }
        /* 
        * Device = Desktops
        * Screen = 1281px to higher resolution desktops
        */
        @media (min-width: 1281px) {
            .main-content .btn-group-sm>.btn, .btn-sm {
                font-size: 0.90rem !important;
                padding: 0.3rem 1rem !important;
                width: 100%;
                margin: 2px;
            }
            .beforeAddBtn { padding: 0px 7px; }
            .newAddBtn {
                margin-left: 5px;
                width: 155px;
                margin: 0px;
                padding-left: 0px !important;
                padding-right: 0px !important;
                margin-left:10px;
            }
            .dataTables_wrapper .dataTables_filter input { padding: 0px !important; }
            .form-row > .col, .form-row > [class*="col-"] { padding-right: 20px; padding-left: 20px; }
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
                padding: 0.3rem 1rem !important;
                margin: 5px 0 5px 0;
                width: 275px;
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
                padding: 0.3rem 1rem !important;
                margin: 5px 0 5px 0;
                width: 275px;
            }
            .dataTables_wrapper .dataTables_filter input { height: 45px; }
            .btn-group > .btn { padding: 7px 25px; font-size: 20px; }
        }
        @media (min-width: 768px) and (max-width: 1024px) {
            .cp { padding:5px }
            .csearch { width:300px; }
            .main-content .btn-group-sm>.btn, .btn-sm {
                font-size: 2rem !important;
                padding: 0.3rem 1rem !important;
                margin: 5px 0 5px 0;
                width: 275px;
            }
            .btn-sm { font-size: 1.7rem !important; padding: 0.5rem 1rem !important; margin: 5px 10px 5px 0; width: 277px; }
        }
    </style>
@endsection

@section('content')    
    {{-- <h4 class="c-grey-900">Employee List</h4>
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-10 beforeAddBtn">
                <button  type="button" class="btn btn-primary pull-right btn-sm newAddBtn" data-toggle="modal" data-target="#AddEmployeeModal">Add Employee</button>
                <input type="hidden" class="EmpPassword" readonly="">
            </div>
        </div>
    </div> --}}
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12"><h4 class="c-grey-900">Employee List</h4></div>

                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">                    
                    <button  type="button" class="btn btn-primary pull-right btn-sm newAddBtn" data-toggle="modal" data-target="#AddEmployeeModal">Add Employee</button>
                    <input type="hidden" class="EmpPassword" readonly="">
                </div>
            </div>
        </div>

        <div class="card-body">
            <div id="tag_container" class="table-responsive">
                <table id="example3" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Sl.</th>
                            <th>Employee ID</th>
                            <th>Name</th>
                            <th>Designation</th>
                            <th>Department</th>
                            <th>Mobile</th>
                            <th class="noExport">Status</th>
                            <th class="hide-table-col">Status</th>
                            <th class="noExport">Action</th>
                        </tr>
                    </thead>
                    <tbody>@include('admin.employee.result_data')</tbody>
                </table>
                <input type="hidden" name="hidden_page" id="hidden_page" value="1" />
                <input type="hidden" name="hidden_column_name" id="hidden_column_name" value="id" />
                <input type="hidden" name="hidden_sort_type" id="hidden_sort_type" value="asc" />
            </div>
        </div>
    </div>


    <!--Add New Employee Modal Start -->
    @include('admin.employee.add')
    <!--Add New Employee Modal End -->

    <!--Edit & Update Modal Start -->
    @include('admin.employee.edit')
    <!--Edit & Update Modal End -->
@endsection

@section('page-scripts')
    <script type="text/javascript">
        //Employee Information Modal Status Update Option 
        jQuery('.employee-toggle-class').change(function(e) {
            e.preventDefault();
            var status = jQuery(this).prop('checked') == true ? 1 : 0; 
            var EmpId = jQuery(this).data('id');
            var url = "employee.status"+"/"+EmpId;
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
                        Notiflix.Loading.Remove(600);
                    }
                
                    if (response.error) {
                        Notiflix.Notify.Failure( 'Data Update Failed' );
                        Notiflix.Loading.Remove(600);
                    }
                }
            });
        });

        // Get Employee Data AS MySql View Page   
        function getEmployeeData() {
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

        // Add Employee Data
        jQuery('#AddEmployee').submit(function(e){
            e.preventDefault();
            jQuery('#search_employee_id').html("");
            jQuery('#employee-id-error').html("");
            jQuery('#name-error').html("");
            jQuery('#phone-error').html("");
            jQuery.ajax({
                url:"employee.add",
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
                        if (response.errors.employee_id) {
                            jQuery( '#employee-id-error' ).html( response.errors.employee_id );
                        }
                        if (response.errors.EmployeeName) {
                            jQuery( '#name-error' ).html( response.errors.name );
                        }
                        if (response.errors.MobileNumber) {
                            jQuery( '#phone-error' ).html( response.errors.mobile_number );
                        }
                        if (response.errors.name) {
                            jQuery( '#name-error' ).html( response.errors.name );
                        }
                        if (response.errors.mobile_number) {
                            jQuery( '#phone-error' ).html( response.errors.mobile_number );
                        }
                        if (response.errors.email) {
                            jQuery( '#email-error' ).html( response.errors.email );
                        }
                        if (response.errors.department) {
                            jQuery( '#department-error' ).html( response.errors.department );
                        }
                    }

                    if (response == "success") {
                        jQuery("#AddEmployee")[0].reset();
                        Notiflix.Notify.Success('Employee Add Successfull');
                        Notiflix.Loading.Remove(200);
                        setTimeout(function() {
                            window.location.reload();
                            $(".btnCloseModal").click();
                            return getEmployeeData();
                        }, 1000);
                    }

                    if (response == "update-success") {
                        jQuery("#AddEmployee")[0].reset();
                        Notiflix.Notify.Success('Employee Update Successfull');
                        Notiflix.Loading.Remove(200);
                        setTimeout(function() {
                            window.location.reload();
                            $(".btnCloseModal").click();
                            return getEmployeeData();
                        }, 1000);
                    }

                    if (response.fail) {
                        if (response.errors.name) {
                            jQuery('#error_field').addClass('has-error');
                            jQuery('#error-name').html( response.errors.name[0] );
                            Notiflix.Notify.Failure('Employee Add Failed');
                            Notiflix.Loading.Remove(200);
                        }
                    }
                },
                error:function(error) {
                    Notiflix.Notify.Failure('Employee Add Failed');
                    Notiflix.Loading.Remove(200);
                }
            });
        });

        // Edit Employee Data
        jQuery(document).on("click","#editEmployeeInfo",function(e) {
            e.preventDefault();
            var EmployeeId = jQuery(this).data('id');
            var url = "employee.edit"+"/"+EmployeeId;
                jQuery.ajax({
                url:url,
                type:"GET",
                dataType:"JSON",
                beforeSend: function() {
                    Notiflix.Loading.Arrows('Data Processing');
                },
                success:function(response) {
                    console.log(response);
                    $('.edit-form-div').html(response);
                    Notiflix.Loading.Remove(600);
                }
            });
        });

        // Update Employee Data
        jQuery(document).on("submit",'#UpdateEmployee', function(arg){
            arg.preventDefault();
            jQuery('#update-name-error').html("");
            jQuery('#update-phone-error').html("");
            var formData = new FormData(this);

            jQuery.ajax({
                url:"employee.update",
                type:"POST",
                data:formData,
                dataType:'JSON',
                cache: false,
                contentType: false,
                processData: false,
                success:function(response) {
                    if (response == "success") {
                        //jQuery("#editDelarModal").modal("hide");
                        Notiflix.Notify.Success('Employee Info Update Successfull');
                        return getEmployeeData();
                        Notiflix.Loading.Remove(600);
                    }
                    if (response.errors) {
                        if (response.errors.name) {
                            jQuery('#update-name-error').html(response.errors.name[0]);
                        }
                        if (response.errors.mobile_number) {
                            jQuery('#update-phone-error').html(response.errors.mobile_number[0]);
                        }
                        if (response.errors.department) {
                            jQuery('#update-department-error').html(response.errors.department[0]);
                        }
                    }
                    if (response == "empExit") {
                        Notiflix.Notify.Warning( 'Employee Info Update Failed.Please Try Again.' );
                        Notiflix.Loading.Remove(600);
                    }
                },
                error:function(error) {
                    Notiflix.Notify.Failure('Employee Info Update Failed');
                }
            });
        });

        //API Search Employee By Id
        jQuery(document).on("click","#search_employee_button",function(e) {
            e.preventDefault();
            var EmployeeId = jQuery('#search_employee_id').val();
            var url = "employee.searchApi"+"/"+EmployeeId;
            jQuery.ajax({
                url:url,
                type:"GET",
                dataType:"JSON",
                beforeSend: function() {
                    Notiflix.Loading.Arrows('Data Processing');
                },
                success:function(response) {
                    //console.log(response);
                    Notiflix.Loading.Remove(500);
                    if (response.error) {
                        jQuery("#AddEmployee")[0].reset();
                        Notiflix.Notify.Failure( 'NO Data Found' );
                        Notiflix.Loading.Remove(600);
                    }

                    if (response.success) {
                        //jQuery('#employee_id').val(response.EmployeeId).prop('readonly',true);
                        jQuery('.ApiId').val(response.success.EmployeeId).prop('readonly',true);
                        jQuery('.ApiName').val(response.success.EmployeeName);
                        jQuery('.ApiDesignation').val(response.success.Designation);
                        jQuery('.ApiMobileNumber').val(response.success.MobileNumber);
                        jQuery('.ApiEmail').val(response.success.Email);
                        jQuery('.ApiOperatingUnit').val(response.success.OperatingUnit);
                        jQuery('.ApiProduct').val(response.success.Product);
                        jQuery('.ApiDepartment').val(response.success.Department);
                        jQuery('.ApiSection').val(response.success.Section);
                        jQuery('.ApiSubSection').val(response.success.SubSection);
                        jQuery('.ApiStatus').val(response.success.status);
                        jQuery('.EmpPassword').val(response.success.password).show();
                    } 

                    if (response == 'empty' || response == 'error') {
                        jQuery('.ApiId').val(response.EmployeeId).prop('readonly',false);
                        Notiflix.Notify.Info( 'Data Not Found! Please Try Another Employee Id..' );
                    }
                }
            });
        });
    </script>
@endsection