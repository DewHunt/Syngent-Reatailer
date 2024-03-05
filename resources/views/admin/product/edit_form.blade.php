<form class="form-horizontal" method="POST" action="{{ route('product.update') }}" id="UpdateProduct" >
    @csrf
    <input type="hidden" name="product_master_id" id="product_master_id" value="{{ $ProductInfo->product_master_id }}" />
    <input type="hidden" name="product_id" class="productId" value="{{ $ProductInfo->product_id }}" />

    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="form-group">
                <label>Category <span class="required">*</span></label>
                <select class="form-control select2" data-placeholder="Select" style="width: 100%;" name="categoryId">
                    <option value="">Select A Category</option>
                    @foreach ($categories as $category)
                        @php
                            $select = '';
                            if ($category->id == $ProductInfo->category_id) {
                                $select = 'selected';
                            }
                        @endphp
                        <option value="{{ $category->id }}" {{ $select }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
            <div class="form-group">
                <label>Product Model <span class="required">*</span></label>
                <input type="text" name="product_model" class="form-control productModel" placeholder="Product Model" required="" value="{{ $ProductInfo->product_model }}" />
                <span class="text-danger"><strong id="update-model-number-error"></strong></span>
            </div>
        </div>
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
            <div class="form-group">
                <label>Product Code</label>
                <input type="text" name="product_code" class="form-control productCode" value="{{ $ProductInfo->product_code }}" />
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
            <div class="form-group">
                <label>Product Type</label>
                <input type="text" name="product_type" class="form-control productType" placeholder="Product Type" value="{{ $ProductInfo->product_type }}" />
            </div>
        </div>
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
            <div class="form-group">
                <label>Price <span class="required">*</span></label>
                <input type="text" name="mrp_price" class="form-control productPrice" placeholder="MRP Price" required="" value="{{ $ProductInfo->mrp_price }}" />
                <span class="text-danger"><strong id="update-mrp-error"></strong></span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
            <div class="form-group">
                <label>MSDP Price <span class="required">*</span></label>
                <input type="text" name="msdp_price" class="form-control productMsdp" placeholder="MSDP Price" required="" value="{{ $ProductInfo->msdp_price }}" />
                <span class="text-danger"><strong id="update-msdp-error"></strong></span>
            </div>
        </div>
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
            <div class="form-group">
                <label>MSRP Price <span class="required">*</span></label>
                <input type="text" name="msrp_price" class="form-control productMsrp" placeholder="MSRP Price" required="" value="{{ $ProductInfo->msrp_price }}" />
                <span class="text-danger"><strong id="update-msrp-error"></strong></span>
            </div>
        </div>
    </div>

    <div class="row" style="display: none;">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="form-group">
                <label>Category</label>
                <input type="text" name="category" class="form-control productCategory" placeholder="Category Name" value="{{ $ProductInfo->category2 }}" />
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="form-group">
                <button type="button" class="btn btn-secondary btnCloseModal pull-left" data-dismiss="modal">Close</button> 
                <button type="submit" class="btn btn-primary pull-right">Update</button>
            </div>
        </div>
    </div>
</form>