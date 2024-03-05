<div class="modal fade" id="AddMenuModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Menu</h5>
                <span style="font-size:12px;margin-top:6px;margin-left:5px">[** All <span style="color:red;">Red</span> Start Sign Field Must Be Fillable.**]</span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form class="form-horizontal" method="POST" action="" id="AddMenu">
                    @csrf
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="form-group">
                                <label>Parent Menu</label>
                                <select class="form-control select2" data-placeholder="Select" style="width: 100%;" name="parentMenuId" id="parentMenuId">
                                    <option value="">Select Parent Menu</option>
                                    @if(isset($menuList))
                                        @foreach ($menuList as $menu)
                                            <option value="{{ $menu->id }}">{{ $menu->menu_name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>

                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="form-group">
                                <label>Menu Name <span class="required">*</span></label>
                                <input type="text" name="menuName" class="form-control" required=""/>
                                <span class="text-danger"><strong id="menu-name-error"></strong></span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="form-group">
                                <label>Menu Link</label>
                                <input type="text" name="menuLink" class="form-control"/>
                                <span class="text-danger"><strong id="menu-link-error"></strong></span>
                            </div>
                        </div>

                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="form-group">
                                <label>Order By</label>
                                <input type="number" name="orderBy" class="form-control" min="1"/>
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
            </div>
        </div>
    </div>
</div>