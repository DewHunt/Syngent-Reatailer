<div class="modal fade" id="AddEmployeeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Employee</h5>
                <span style="font-size:12px;margin-top:6px;margin-left:5px">[** All <span style="color:red;">Red</span> Start Sign Data Must Be Fillable.**]</span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>

            <div class="modal-body">
                <form class="form-horizontal" method="POST" action="" id="AddEmployee" enctype="multipart/form-data">
                    @csrf
                    <div class="row" style="display: none;">
                        <div class="col-xl-10 col-lg-10 col-md-10 col-sm-12 col-12">
                            <div class="form-group">
                                <input type="text" class="form-control" id="search_employee_id" placeholder="Employee Search">
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-2 col-md-2 col-sm-12 col-12">
                            <div class="form-group">
                                <button type="button" class="btn btn-primary btn-block" id="search_employee_button">Search</button>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="form-group">
                                <label>Employee ID <span class="required">*</span></label>
                                <input type="text" name="employee_id" id="employee_id" class="form-control ApiId" placeholder="Enter Employee ID" required/>
                                <span class="text-danger"><strong id="employee-id-error"></strong></span>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="form-group">
                                <label>Name <span class="required">*</span></label>
                                <input type="text" name="name" class="form-control ApiName" placeholder="Name"required=""/>
                                <span class="text-danger"><strong id="name-error"></strong></span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="form-group">
                                <label>Designation</label>
                                <input type="text" name="designation" class="form-control ApiDesignation" placeholder="Designation"/>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="form-group">
                                <label>Mobile Number <span class="required">*</span></label>
                                <input type="text" name="mobile_number" class="form-control ApiMobileNumber Number" placeholder="Mobile Number" maxlength="11"  minlength="11" required="" />
                                <span class="text-danger"><strong id="phone-error"></strong></span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="form-group">
                                <label>Email <span class="required">*</span></label>
                                <input type="text" name="email" class="form-control ApiEmail" placeholder="Email Address" required="required"/>
                                <span class="text-danger"><strong id="email-error"></strong></span>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="form-group">
                                <label>Operating Unit</label>
                                <input type="text" name="operating_unit" class="form-control ApiOperatingUnit" placeholder="Operating Unit" />
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="form-group">
                                <label>Product</label>
                                <input type="text" name="product" class="form-control ApiProduct" placeholder="Product Name" />
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="form-group">
                                <label>Department <span class="required">*</span></label>
                                <input type="text" name="department" class="form-control ApiDepartment" placeholder="Department" required="required"/>
                                <span class="text-danger"><strong id="department-error"></strong></span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="form-group">
                                <label>Section</label>
                                <input type="text" name="section" class="form-control ApiSection" placeholder="Section"/>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="form-group">
                                <label>Sub Section</label>
                                <input type="text" name="sub_section" class="form-control ApiSubSection" placeholder="Sub Section"/>
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
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                            <label>Status</label>
                            <div class="form-group">
                                <label><input type="radio" name="status" checked="checked" value="1"> Active</label>  &nbsp;&nbsp; 
                                <label><input type="radio" name="status" value="0"> In-Active</label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 text-right">
                            <div class="form-group">
                                <button type="button" class="btn btn-secondary btnCloseModal" data-dismiss="modal">Close</button> 
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>