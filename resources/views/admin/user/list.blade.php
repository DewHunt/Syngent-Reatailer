@extends('admin.master.master')
@section('content')
<style>
.cp {
    padding:5px
}
.csearch {
    width:285px;
}
/* 
  ##Device = Desktops
  ##Screen = 1281px to higher resolution desktops
*/
@media (min-width: 1281px) {
    .main-content .btn-group-sm>.btn, .btn-sm {
        font-size: 0.90rem !important;
        padding: 0.3rem 1rem !important;
        width: 150px;
        margin: 2px;
    }
    .beforeAddBtn{
        padding: 0px 7px;
    }
    .newAddBtn{
        margin-left: 5px;
        width: 165px;
        margin: 0px;
        padding-left: 0px !important;
        padding-right: 0px !important;
    }
    .dataTables_wrapper .dataTables_filter input {
        height: 30px;
    }
    /*.btn-sm {
        font-size: 2rem !important;
        padding: 0rem 1rem !important;
        margin: 5px 15px 5px 0;
        width: 277px;
    }*/
}            
/* Portrait and Landscape */
@media only screen 
and (min-device-width: 320px) 
and (max-device-width: 568px)
and (-webkit-min-device-pixel-ratio: 2) {
    .cp {
        padding:5px
    }
    .csearch {
        width:300px;
    }
    .dataTables_wrapper .dataTables_filter input {
        height: 55px;
    }
    .btn-group > .btn {
        padding: 7px 25px;
        font-size: 20px;
    }
    .btn-sm {
        font-size: 2rem !important;
        padding: 0rem 1rem !important;
        margin: 5px 15px 5px 0;
        width: 277px;
    }
}
@media only screen 
and (min-device-width: 375px) 
and (max-device-width: 812px) 
and (-webkit-min-device-pixel-ratio: 3){
    .cp {
        padding:5px
    }
    .csearch {
        width:300px;
    }
    .dataTables_wrapper .dataTables_filter input {
        height: 55px;
    }
    .btn-group > .btn {
        padding: 7px 25px;
        font-size: 20px;
    }
    .btn-sm {
        font-size: 2rem !important;
        padding: 0rem 1rem !important;
        margin: 5px 15px 5px 0;
        width: 277px;
    }
}
@media (min-width: 768px) and (max-width: 1024px) {
    .cp {
        padding:5px
    }
    .csearch {
        width:300px;
    }
    .main-content .btn-group-sm>.btn, .btn-sm {
        font-size: 2rem !important;
        padding: 0rem 1rem !important;
        margin: 5px 0 5px 0;
        width: 280px;
    }
    .dataTables_wrapper .dataTables_filter input {
        height: 45px;
    }
    .btn-group > .btn {
        padding: 7px 25px;
        font-size: 20px;
    }
    .btn-sm {
        font-size: 2rem !important;
        padding: 0rem 1rem !important;
        margin: 5px 15px 5px 0;
        width: 277px;
        height:60px;
    }
}
</style>
<h4 class="c-grey-900">User List <button  type="button" class="btn btn-primary pull-right btn-sm newAddBtn" data-toggle="modal" data-target="#AddUserModal">Add User</button></h4>
{{-- 
<div class="col-md-12">
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-10 beforeAddBtn">
            <button  type="button" class="btn btn-primary pull-right btn-sm newAddBtn" data-toggle="modal" data-target="#AddUserModal">Add New User</button>
        </div>
    </div>
</div> 
--}}
{{-- 
<div class="col-md-12 cp">
    <div class="row">
        <div class="col-md-6"></div>
        <div class="col-md-6">
            <div class="form-group top-margin">
                <input type="text" name="serach" id="serach" class="form-control pull-right csearch"/>
            </div>
        </div>
    </div>
</div> 
--}}
<div id="tag_container" class="table-responsive">
    <table id="example2" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th class="sorting" data-sorting_type="asc" data-column_name="id" style="cursor: pointer;width:10%">Sl.</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="name" style="cursor: pointer;width:30%">Name</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="employee_id" style="cursor: pointer;width:20%">Employee ID</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="designation" style="cursor: pointer;width:20%">Designation</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="department" style="cursor: pointer;width:20%">Department</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="email" style="cursor: pointer;width:15%">Email</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="status" style="cursor: pointer;width:15%">Status</th>
                <th style="width:10%">Action</th>
            </tr>
        </thead>
        <tbody>
            @include('admin.user.result_data')
        </tbody>
    </table>
    <input type="hidden" name="hidden_page" id="hidden_page" value="1" />
    <input type="hidden" name="hidden_column_name" id="hidden_column_name" value="id" />
    <input type="hidden" name="hidden_sort_type" id="hidden_sort_type" value="asc" />
</div>


<!--Add New User Modal Start -->
<div class="modal fade" id="AddUserModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New User</h5>

                <span style="font-size:12px;margin-top:6px;margin-left:5px">[** All <span style="color:red;">Red</span> Start Sign Data Must Be Fillable.**]</span>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form class="form-horizontal" method="POST"  id="AddUser">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <label>Select Employee <span class="required">*</span></label>
                            <select class="form-control select2" data-placeholder="Select" style="width: 100%;" name="employee_id" id="employeeId" required="">
                                <option value="">Select Employee</option>
                                @if(isset($empList) && !empty($empList))
                                    @foreach($empList as $row)
                                        <option value="{{ $row->id }}">{{ $row->name }}-[{{ $row->employee_id }}]</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label>User Name</label>
                            <input type="text" name="name" class="form-control uname" value="{{ old('name') }}"/>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <label>Email Address</label>
                            <input type="email" name="email" class="form-control uemail"/>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label>Password <span class="required">*</span></label>
                            <input id="password" type="password" class="form-control" name="password" placeholder="Enter User Password Minimum 5 Digit" required autocomplete="new-password">

                            <span class="text-danger">
                                <strong id="user-password-error"></strong>
                            </span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <label>Confirm Password <span class="required">*</span></label>
                            <input id="password_confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">

                            <span class="text-danger">
                                <strong id="user-confirm-password-error"></strong>
                            </span>
                        </div>
                        <div class="col-md-12 mb-2">
                            <div class="form-group">
                                <label>Status</label> &nbsp;&nbsp;&nbsp;&nbsp;
                                <label><input type="radio" name="status" checked="checked" value="1"> Active</label>  &nbsp;&nbsp; 
                                <label><input type="radio" name="status" value="0"> In-Active</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="dealer_id" class="apidealerid"/>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> 
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--Add New User Modal End -->


<!--Update New User Modal Start -->
<div class="modal fade" id="editUserModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update User</h5>
                <span style="font-size:12px;margin-top:6px;margin-left:5px">[** All <span style="color:red;">Red</span> Start Sign Data Must Be Fillable.**]</span>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form class="form-horizontal updateValidatedForm" method="POST"  id="UpdateUser">
                <input type="hidden" name="update_id" id="update_id"/>
                <input type="hidden" name="_method" value="PUT"/>
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <label>Select Employee <span class="required">*</span></label>
                            <select class="form-control empId" data-placeholder="Select2" style="width: 100%;" name="employee_id" required="">
                            <option value="">Select Employee</option>
                            @if(isset($empList) && !empty($empList))
                                @foreach($empList as $row)
                                <option value="{{ $row->id }}" class="vEmpId{{ $row->id }}">{{ $row->name }}</option>
                                @endforeach
                            @endif
                        </select>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label>User Name</label>
                            <input type="text" name="name" class="form-control userName" value="{{ old('name') }}"/>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <label>Email Address</label>
                            <input type="email" name="email" class="form-control userEmail"/>
                            <span class="text-danger">
                                <strong id="uuser-email-error"></strong>
                            </span>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label>Password</label>
                            <input id="password" type="password" class="form-control userPassword" name="password" placeholder="Enter User Password Minimum 5 Digit"autocomplete="new-password">

                            <span class="text-danger">
                                <strong id="uuser-password-error"></strong>
                            </span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <label>Confirm Password</label>
                            <input id="password_confirm" type="password" class="form-control" name="password_confirmation" autocomplete="new-password">

                            <span class="text-danger">
                                <strong id="uuser-confirm-password-error"></strong>
                            </span>
                        </div>
                        <div class="col-md-12 mb-2">
                            <div class="form-group">
                                <label><input type="radio" id="option1" name="status" class="UpdateUserStatus" value="1"> Active</label>  &nbsp;&nbsp; 
                                <label><input type="radio" id="option2" name="status" class="UpdateUserStatus" value="0"> In-Active</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="update_employee_id" id="update_employee_id">
                    <input type="hidden" name="old_password" id="old_password">
                    <button type="button" class="btn btn-secondary btnCloseModal" data-dismiss="modal">Close</button> 
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--Update New User Modal End -->

@section('page-scripts')
<script type="text/javascript">
jQuery(".select2").select2({ dropdownParent: ".modal-container" });
//User Status Active and InActive
jQuery('.user-toggle-class').change(function(e) {
    e.preventDefault();
    var status      = jQuery(this).prop('checked') == true ? 1 : 0; 
    var userId      = jQuery(this).data('id');
    var url         = "user.status"+"/"+userId;
    jQuery.ajax({
        url:url,
        type:"GET",
        dataType:'JSON',
        cache: false,
        contentType: false,
        processData: false,
        success:function(response){
            console.log(response);
            if(response.success) {
                Notiflix.Notify.Success('User Update Successfully');
            }
            if(response.error) {
                Notiflix.Notify.Failure('User Update Failed');
            }

        }
    });
});
// Get User Data AS MySql View Page   
function getUserData(){
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
// Add New Data
jQuery('#AddUser').submit(function(e){
  e.preventDefault();
  jQuery('#user-email-error').html("");
  jQuery('#user-password-error').html("");
  jQuery('#user-confirm-password-error').html("");
  jQuery.ajax({
    url:"user.add",
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
    Notiflix.Loading.Remove(300);
    if(response.errors) {
        if(response.errors.email){
            jQuery( '#user-email-error' ).html( response.errors.email[0] );
        }
        if(response.errors.password){
            jQuery( '#user-password-error' ).html( response.errors.password[0] );
        }
        if(response.errors.confirm-password){
            jQuery( '#user-confirm-password-error' ).html( response.errors.confirm-password[0] );
        }
    }


    if(response == "error"){
        Notiflix.Notify.Failure('User Save Failed');
    }

    if(response == "success"){
        jQuery("#AddUser")[0].reset();
        $(".btnCloseModal").click();
        Notiflix.Notify.Success('User Save Successfull');
        return getUserData();
    }

      if(response.fail) {
        if(response.errors.name) {
          $(".btnCloseModal").click();
          jQuery('#error_field').addClass('has-error');
          jQuery('#error-name').html( response.errors.name[0] );
          Notiflix.Notify.Failure('User Save Failed');
        }
      }

    },
    error:function(error){
      jQuery("#AddUser")[0].reset();
      Notiflix.Notify.Failure('User Save Failed');
    }
  });
});
// Edit  Data
jQuery(document).on("click","#editUserInfo",function(e){
  e.preventDefault();
  var UserId = jQuery(this).data('id');
  var url = "user.edit"+"/"+UserId;
  jQuery.ajax({
    url:url,
    type:"GET",
    dataType:"JSON",
    beforeSend: function() {
        Notiflix.Loading.Arrows('Data Processing');
    },
    success:function(response){
        console.log(response);
        Notiflix.Loading.Remove(300);
        jQuery('#update_id').val(response.id);
        jQuery('.userName').val(response.name);
        jQuery('.userEmail').val(response.email);
        jQuery('#old_password').val(response.password);

        if(response.employee_id > 0){
            jQuery(".vEmpId"+response.employee_id).prop("selected", true);
            jQuery(".empId").prop("disabled", true);
            jQuery('.userName').prop("readonly", true);
            jQuery('#update_employee_id').val(response.employee_id);

        }
        else {
            jQuery(".empId").prop("disabled", false);
            jQuery('.userName').prop("readonly", true);
            jQuery('#update_employee_id').val(0);
        }

        if (response.status == 1){
            jQuery("#option1").prop("checked", true);
        } else {
            jQuery("#option2").prop("checked", true);
        }
    }
  });
});
// Update Data
jQuery('#UpdateUser').on("submit", function(arg){
    jQuery.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
        }
    });
    arg.preventDefault();
    jQuery('#uuser-email-error').html("");
    jQuery('#uuser-password-error').html("");
    jQuery('#uuser-confirm-password-error').html("");

    var formData = new FormData(this);
    formData.append('_method', 'post');
  
    var userId   = jQuery('#update_id').val();
    var data     = jQuery("#UpdateUser").serialize();
    
    jQuery.ajax({
        url:"user.update",
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
            if(response.errors) {
                if(response.errors.email){
                    jQuery( '#uuser-email-error' ).html( response.errors.email[0] );
                }
                if(response.errors.password){
                    jQuery( '#uuser-password-error' ).html( response.errors.password[0] );
                }
                if(response.errors.confirm-password){
                    jQuery( '#uuser-confirm-password-error' ).html( response.errors.confirm-password[0] );
                }
            }


            if(response == "user-success") {
                Notiflix.Notify.Success('User Info Update Successfull');
                return getUserData();
            }
        
            if(response == "error"){
                Notiflix.Notify.Failure('User Inf Update Failed');
            }
        
            if(response.fail) {
                if(response.errors.name){
                    jQuery('#error_field').addClass('has-error');
                    jQuery('#error-name').html( response.errors.name[0] );
                    Notiflix.Notify.Failure('User Info Update Failed');
                }
            }
        }
    });
});
/*
jQuery('.updateValidatedForm').validate({
    rules : {
        password : {
            minlength : 5
        },
        password_confirm : {
            minlength : 5,
            equalTo : "#password"
        }
    }
});
*/

jQuery(document).on('change','#employeeId',function(){
    var empId = jQuery(this).val();
    var url   = "getEmployeeInfo"+"/"+empId
    jQuery.ajax({
    url:url,
    type:"GET",
    dataType:"JSON",
    beforeSend: function() {
        Notiflix.Loading.Arrows('Data Processing');
    },
    success:function(response) {
        Notiflix.Loading.Remove(300);
        console.log(response);
        if(response.name) {
            jQuery('.uname').val(response.name);
        }
        if(response.email) {
            jQuery('.uemail').val(response.email);
        }
    }
  });
});
</script>
@endsection



@endsection