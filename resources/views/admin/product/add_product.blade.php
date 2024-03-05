<div class="modal fade" id="AddProductModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Product</h5>
                <span style="font-size:12px;margin-top:6px;margin-left:5px">[** All <span style="color:red;">Red</span> Start Sign Data Must Be Fillable.**]</span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form class="form-horizontal" method="POST" action="{{route('product.add')}}" id="AddProduct">
                    @csrf
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="form-group">
                                <label>Category <span class="required">*</span></label>
                                <select class="form-control select2" data-placeholder="Select" style="width: 100%;" name="categoryId">
                                    <option value="">Select A Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="form-group">
                                <label>Product Brand <span class="required">*</span></label>
                                <input type="text" name="product_model" class="form-control ApiproductModel" placeholder="Product Brand" required=""/>
                                <span class="text-danger"><strong id="model-number-error"></strong></span>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="form-group">
                                <label>Product Code</label>
                                <input type="text" name="product_code" class="form-control ApiproductCode"/>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="form-group">
                                <label>Product Type</label>
                                <input type="text" name="product_type" class="form-control ApiproductType" placeholder="Product Type Ex:Cell Phone"/>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="form-group">
                                <label>MRP Price <span class="required">*</span></label>
                                <input type="text" name="mrp_price" class="form-control ApiproductPrice" placeholder="MRP Price" required=""/>
                                <span class="text-danger"><strong id="mrp-error"></strong></span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="form-group">
                                <label>MSDP Price <span class="required">*</span></label>
                                <input type="text" name="msdp_price" class="form-control ApiproductMsdp" placeholder="MSDP Price" required=""/>
                                <span class="text-danger"><strong id="msdp-error"></strong></span>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="form-group">
                                <label>MSRP Price <span class="required">*</span></label>
                                <input type="text" name="msrp_price" class="form-control ApiproductMsrp" placeholder="MSRP Price" required=""/>
                                <span class="text-danger"><strong id="msrp-error"></strong></span>
                            </div>
                        </div>
                    </div>

                    <div class="row" style="display: none;">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="form-group">
                                <label>Category</label>
                                <input type="text" name="category" class="form-control ApiproductCategory" placeholder="Category Name"/>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="form-group">
                                <button type="button" class="btn btn-secondary btnCloseModal pull-left" data-dismiss="modal">Close</button> 
                                <button type="submit" class="btn btn-primary pull-right">Submit</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>