<form class="form-horizontal" method="POST" action="{{ route('saveProductStockMaintain') }}" id="saveProductStockMaintain">
    @csrf
    <input type="hidden" name="product_id" id="productId" value="{{ $productInfo->product_master_id }}" />
    <div class="row">
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
        	<div class="form-group">
	            <label>Default<span class="required">*</span></label>
	            <input type="text" class="form-control" name="default_qty" id="default_qty" required="" value="{{ $productInfo->default_qty }}" />
        	</div>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
        	<div class="form-group">
	            <label>Yellow<span class="required">*</span></label>
	            <input type="text" class="form-control" name="yeallow_qty" id="yeallow_qty" required="" value="{{ $productInfo->yeallow_qty }}"/>
        	</div>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
        	<div class="form-group">
	            <label>Red<span class="required">*</span></label>
	            <input type="text" class="form-control" name="red_qty" id="red_qty" required="" value="{{ $productInfo->red_qty }}"/>
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