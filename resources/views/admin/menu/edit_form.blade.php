<form class="form-horizontal" method="POST" action="{{ route('updateMenu') }}" id="UpdateMenu">
    @csrf
    <input type="hidden" name="update_id" id="update_id" value="{{ $menuInfo->id }}" />
    <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
        	<div class="form-group">
	            <label>Parent Menu</label>
	            <select class="form-control select2" style="width: 100%;" name="parentMenuId">
	                <option value="">Select Parent Menu</option>
	                @if(isset($menuList))
	                    @foreach ($menuList as $menu)
	                    	@php
	                    		$select = "";
	                    		if ($menu->id == $menuInfo->parent_menu) {
	                    			$select = "selected";
	                    		}
	                    	@endphp
	                        <option value="{{ $menu->id }}" class="parentMenuId{{ $menu->id }}" {{ $select }}>{{ $menu->menu_name }}</option>
	                    @endforeach
	                @endif
	            </select>
        	</div>
        </div>

        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
        	<div class="form-group">
	            <label>Menu Name <span class="required">*</span></label>
	            <input type="text" name="menuName" class="form-control menuName" required="" value="{{ $menuInfo->menu_name }}" />
	            <span class="text-danger"><strong id="menu-name-error"></strong></span>
        	</div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
        	<div class="form-group">
	            <label>Menu Link <span class="required">*</span></label>
	            <input type="text" name="menuLink" class="form-control menuLink" value="{{ $menuInfo->menu_link }}" />
	            <span class="text-danger"><strong id="menu-link-error"></strong></span>
        	</div>
        </div>

        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
        	<div class="form-group">
	            <label>Order By</label>
	            <input type="number" name="orderBy" class="form-control orderBy" min="1" value="{{ $menuInfo->order_by }}" />
        	</div>
        </div>
    </div>

    <div class="row">
    	<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        	<div class="form-group">
			    <button type="button" class="btn btn-secondary pull-left" data-dismiss="modal">Close</button> 
			    <button type="submit" class="btn btn-primary pull-right">Submit</button>
        	</div>
    	</div>
	</div>
</form>