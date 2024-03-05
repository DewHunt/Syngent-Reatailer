<form class="form-horizontal" method="POST" action="{{ route('employee.update') }}" id="UpdateEmployee" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="update_id" id="update_id" value="{{ $EmployeeInfo->id }}" />

    <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
            <div class="form-group">
                <label>Employee ID <span class="required">*</span></label>
                <input type="text" name="employee_id" class="form-control UpdateApiId" placeholder="Enter Employee ID" readonly="" value="{{ $EmployeeInfo->employee_id }}" />
            </div>
        </div>
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
            <div class="form-group">
                <label>Name <span class="required">*</span></label>
                <input type="text" name="name" class="form-control UpdateApiName" placeholder="Name"required="" value="{{ $EmployeeInfo->name }}" />
                <span class="text-danger"><strong id="update-name-error"></strong></span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
            <div class="form-group">
                <label>Designation</label>
                <input type="text" name="designation" class="form-control UpdateApiDesignation" placeholder="Designation" value="{{ $EmployeeInfo->designation }}" />
            </div>
        </div>
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
            <div class="form-group">
                <label>Mobile Number <span class="required">*</span></label>
                <input type="text" name="mobile_number" class="form-control UpdateApiMobileNumber Number" maxlength="11"  minlength="11" placeholder="Mobile Number" required="" value="{{ $EmployeeInfo->mobile_number }}" />
                <span class="text-danger"><strong id="update-phone-error"></strong></span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
            <div class="form-group">
                <label>Email <span class="required">*</span></label>
                <input type="text" name="email" class="form-control UpdateApiEmail" placeholder="Email Address" required="required" value="{{ $EmployeeInfo->email }}" />
                <span class="text-danger">{{ $errors->first('email') }}</span>
            </div>
        </div>
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
            <div class="form-group">
                <label>Operating Unit</label>
                <input type="text" name="operating_unit" class="form-control UpdateApiOperatingUnit" placeholder="Operating Unit" value="{{ $EmployeeInfo->operating_unit }}" />
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
            <div class="form-group">
                <label>Product</label>
                <input type="text" name="product" class="form-control UpdateApiProduct" placeholder="Product Name" value="{{ $EmployeeInfo->product }}" />
            </div>
        </div>
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
            <div class="form-group">
                <label>Department <span class="required">*</span></label>
                <input type="text" name="department" class="form-control UpdateApiDepartment" placeholder="Department" required="required" value="{{ $EmployeeInfo->department }}" />
                <span class="text-danger"><strong id="update-department-error"></strong></span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
            <div class="form-group">
                <label>Section</label>
                <input type="text" name="section" class="form-control UpdateApiSection" placeholder="Section" value="{{ $EmployeeInfo->section }}" />
            </div>
        </div>
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
            <div class="form-group">
                <label>Sub Section</label>
                <input type="text" name="sub_section" class="form-control UpdateApiSubSection" placeholder="Sub Section" value="{{ $EmployeeInfo->sub_section }}" />
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
            <div class="form-group">
                <label>Employee Pic</label><br/>
                <span class="text-danger employee-photo-error"></span>
                <input type="file" name="employee_pic" class="form-control"/>
                <p>Photo Size Should Be: 300px x 300px</p>
                <span id="img-tag">
                	<img src="{{ asset('/public/upload/employee/'.$EmployeeInfo->photo) }}" width="120" height="120"/>
                	{{-- <img src="'+APP_URL+'/public/upload/employee/'+response.photo+'" width="120" height="120"/> --}}
                </span>
            </div>
        </div>
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
        	<label>Status</label>
            <div class="form-group">
            	@php
            		$activeStatus = "";
            		$inActiveStatus = "";
            		if ($EmployeeInfo->status == 1) {
            			$activeStatus = "checked";
            		} else {
            			$inActiveStatus = "checked";
            		}
            	@endphp
                <label>
                	<input type="radio" id="option1" name="status" class="UpdateApiStatus" value="1" {{ $activeStatus  }}> Active
                </label>
                &nbsp;&nbsp; 
                <label>
                	<input type="radio" id="option2" name="status" class="UpdateApiStatus" value="0" {{ $inActiveStatus }}> In-Active
                </label>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 text-right">
            <div class="form-group">
			    <button type="button" class="btn btn-secondary btnCloseModal" data-dismiss="modal">Close</button> 
			    <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </div>
    </div>
</form>