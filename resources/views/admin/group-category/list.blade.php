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
        padding: 0.3rem 0rem !important;
        width: 155px;
        margin: 5px 0px;
    }
    .beforeAddBtn{
        padding: 0px 0px;
    }
    .newAddBtn{
        margin-left: 5px;
        width: 155px;
        margin: 0px;
        padding-left: 0px !important;
        padding-right: 0px !important;
    }
    /*.btn-sm {
        font-size: 1.7rem !important;
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
    .btn-sm {
        font-size: 1.7rem !important;
        padding: 0rem 1rem !important;
        margin: 5px 15px 5px 0;
        width: 285px;
    }
    .dataTables_wrapper .dataTables_filter input {
        height: 45px;
    }
    .btn-group > .btn {
        padding: 7px 25px;
        font-size: 20px;
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
    .btn-sm {
        font-size: 1.7rem !important;
        padding: 0rem 1rem !important;
        margin: 5px 15px 5px 0;
        width: 285px;
    }
    .dataTables_wrapper .dataTables_filter input {
        height: 45px;
    }
    .btn-group > .btn {
        padding: 7px 25px;
        font-size: 20px;
    }
}
@media (min-width: 768px) and (max-width: 1024px) {
    .cp {
        padding:5px
    }
    .csearch {
        width:300px;
    }
    .btn-sm {
        font-size: 1.7rem !important;
        padding: 0rem 1rem !important;
        margin: 5px 15px 5px 0;
        width: 277px;
    }
    .dataTables_wrapper .dataTables_filter input {
        height: 45px;
    }
    .btn-group > .btn {
        padding: 7px 25px;
        font-size: 20px;
    }
}
</style>

<h4 class="c-grey-900">Group Name List</h4>
<div class="col-md-12">
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-10 beforeAddBtn" style="padding-right:0px">
            <button  type="button" class="btn btn-primary pull-right btn-sm newAddBtn" data-toggle="modal" data-target="#AddGroupNameModal">Add Group Name</button>
        </div>
    </div>
</div>


<div id="tag_container" class="table-responsive">
    <table id="example3" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Sl.</th>
                <th>Group Name</th>
                <th>Sorting Order</th>
                <th width="100px">Status</th>
                <th width="100px">Action</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($getBpRetailerCatList))
				@foreach($getBpRetailerCatList as $row)
				<tr>
					<td>{{ ++$loop->index }}.</td>
					<td>{{ $row->name }}</td>
                    <td>{{ $row->sorting_number }}</td>
                    <td class="text-center">
                        @php
                        if($row->status == 1 ) {
                        @endphp
                        <button class="btn btn-info btn-sm">Active</button>
                        @php
                        } else {
                        @endphp
                        <button class="btn btn-danger btn-sm">InActive</button>
                        @php } @endphp
                    </td>
					<td class="text-center">
						<button type="button" data-id="{{ $row->id }}" id="editGroupInfo" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editModal">Edit</button>
					</td>
				</tr>
				@endforeach
			@endif
        </tbody>
    </table>
</div>

<!--Add New Modal Start -->
<div class="modal fade" id="AddGroupNameModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Group Name</h5>

                <span style="font-size:12px;margin-top:6px;margin-left:5px">[** All <span style="color:red;">Red</span> Start Sign Data Must Be Fillable.**]</span>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form class="form-horizontal" method="POST" action="" id="AddGroupName">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Group Name <span class="required">*</span></label>
                        <input type="text" name="group_name" class="form-control" placeholder="Enter Your Group Name"required=""/>
                        <span class="text-danger">
                            <strong id="name-error"></strong>
                        </span>
                    </div>
                    <div class="form-group">
                        <label>Sorting Order <span class="required">*</span></label>
                        <input type="number" name="sorting_number" class="form-control" placeholder="Enter Your Sorting Number"required="" min="1"/>
                        <span class="text-danger">
                            <strong id="sorting-number-error"></strong>
                        </span>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <div class="col-sm-5">
                            <div>
                                <label>
                                    <input type="radio" name="status" checked="checked" value="1"> Active
                                </label>  &nbsp;&nbsp; 
                                <label><input type="radio" name="status" value="0"> In-Active</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary pull-left" data-dismiss="modal">Close</button> 
                    <button type="submit" class="btn btn-primary pull-right">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--Add New Modal End -->

<!--Edit & Update Modal Start -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Group Name</h5>
                <span style="font-size:12px;margin-top:6px;margin-left:5px">[** All <span style="color:red;">Red</span> Start Sign Data Must Be Fillable.**]</span>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form class="form-horizontal" method="POST" action="" id="UpdateGroup">
                <input type="hidden" class="UpdateGroupId" name="update_id" id="update_id"/>
                <input type="hidden" name="_method" value="PUT"/>
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Group Name <span class="required">*</span></label>
                        <input type="text" name="group_name" class="form-control UpdateGroupName" required=""/>
                        <span class="text-danger">
                            <strong id="update-name-error"></strong>
                        </span>
                    </div>

                    <div class="form-group">
                        <label>Sorting Order <span class="required">*</span></label>
                        <input type="number" name="sorting_number" class="form-control UpdateSortingNumber" placeholder="Enter Your Sorting Number"required="" min="1"/>
                        <span class="text-danger">
                            <strong id="update-sorting-number-error"></strong>
                        </span>
                    </div>

                    <div class="form-group">
                        <label>Status</label>
                        <div class="col-sm-5">
                            <div>
                                <label>
                                    <input type="radio" id="option1" name="status" value="1"> Active
                                </label>  &nbsp;&nbsp; 
                                <label><input type="radio" id="option2" name="status" value="0"> In-Active</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> 
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--Edit & Update Modal End -->

@section('page-scripts')
<script type="text/javascript">
//Employee Information Modal Status Update Option 
jQuery('.active-toggle-class').change(function(e) {
    e.preventDefault();
    var status = jQuery(this).prop('checked') == true ? 1 : 0; 
    var getId = jQuery(this).data('id');
    var url = "GroupCategoryController.status"+"/"+getId;
    jQuery.ajax({
        url:url,
        type:"GET",
        dataType:'JSON',
        cache: false,
        contentType: false,
        processData: false,
        success:function(response){
            if(response.success) {
                Notiflix.Notify.Success( 'Data Update Successfull' );
            }
        
            if(response.error) {
                Notiflix.Notify.Failure( 'Data Update Failed' );
            }
        }
    });
});
// Get  Data AS MySql View Page   
function getData(){
    var query       = $('#serach').val();
    var column_name = $('#hidden_column_name').val();
    var sort_type   = $('#hidden_sort_type').val();
    var page        = $('#hidden_page').val();
    //var url = "zone";
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
jQuery('#AddGroupName').submit(function(e){
  e.preventDefault();
  jQuery('#name-error').html("");
  jQuery.ajax({
    url:"GroupCategoryController.add",
    method:"POST",
    data:new FormData(this),
    dataType:'JSON',
    contentType: false,
    cache: false,
    processData: false,

    success:function(response) {
        if(response.errors) {
            if(response.errors.name){
                jQuery( '#name-error' ).html( response.errors.name[0] );
            }
            if(response.errors.sorting_number){
                jQuery( '#sorting-number-error' ).html( response.errors.sorting_number[0] );
            }
        }
        if(response == "success"){
            jQuery("#AddGroupName")[0].reset();
            Notiflix.Notify.Success( 'Group Name Insert Successfull' );
            return getData();
        }

        if(response == "warning") {
            jQuery("#AddGroupName")[0].reset();
            Notiflix.Notify.Failure('Group Name All Ready Exit');
        }
    },
    error:function(error){
      Notiflix.Notify.Failure( 'Data Insert Failed' );
    }
  });
});
// Edit  Data
jQuery(document).on("click","#editGroupInfo",function(e){
  e.preventDefault();
  var getId = jQuery(this).data('id');
  var url   = "GroupCategoryController.edit"+"/"+getId;
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
        jQuery('.UpdateGroupId').val(response.id);
        jQuery('.UpdateGroupName').val(response.name);
        jQuery('.UpdateSortingNumber').val(response.sorting_number);
        
        if (response.status == 1){
            jQuery("#option1").prop("checked", true);
        } else {
            jQuery("#option2").prop("checked", true);
        }
    }
  });
});
// Update Data
jQuery('#UpdateGroup').on("submit", function(arg){
    jQuery.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
        }
    });
    arg.preventDefault();
    var formData = new FormData(this);
    formData.append('_method', 'post');
  
    var groupId  = jQuery('#update_id').val();
    var data     = jQuery("#UpdateGroup").serialize();
    
    jQuery.ajax({
        url:"GroupCategoryController.add",
        type:"POST",
        data:formData,
        dataType:'JSON',
        cache: false,
        contentType: false,
        processData: false,
        success:function(response){
            if(response == "success"){
                Notiflix.Notify.Success( 'Data Update Successfull' );
                return getData();
            }

            if(response == "warning") {
                jQuery("#UpdateGroup")[0].reset();
                Notiflix.Notify.Failure('Group Name All Ready Exit');
            }
        
            if(response == "error") {
                jQuery("#UpdateGroup")[0].reset();
                Notiflix.Notify.Failure( 'Data Update Failed' );
            }
        },
        error:function(error) {
          Notiflix.Notify.Failure( 'Data Update Failed' );
        }
    });
});
</script>
@endsection


@endsection