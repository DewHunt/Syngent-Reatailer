@extends('admin.master.master')
@section('content')
@if(isset($groupId) && !empty($groupId) && $groupId == 1)
<h4 class="c-grey-900 mB-20">Brand Promoter Incentive List</h4>
@else
<h4 class="c-grey-900 mB-20">Retailer Incentive List</h4>
@endif

@include('admin.incentive.menu')
<style>
	.editBtn {
		width:100%;
		height: 35px;
	}
	.mt5 {
		margin-top:5px;
	}
    @media only screen 
    and (min-device-width: 320px) 
    and (max-device-width: 568px)
    and (-webkit-min-device-pixel-ratio: 2) {
        .main-content .form-group .form-control {
            font-size: 2rem !important;
        }
        .bgc-white .btn {
            padding: 1rem 1rem !important;
            font-size: 2rem !important;
            width: 300px;
            height: 80px;
        }
        .commonBtnWidth {
            width:100px !important;
        }
        .editBtn {
            width: 250px;
            height: auto;
        }
    }
    @media only screen 
    and (min-device-width: 375px) 
    and (max-device-width: 812px) 
    and (-webkit-min-device-pixel-ratio: 3) { 
        .main-content .form-group .form-control {
            font-size: 2rem !important;
        }
        .bgc-white .btn {
            padding: 1rem 1rem !important;
            font-size: 2rem !important;
            width: 300px;
            height: 80px;
        }
        .commonBtnWidth {
            width:100% !important;
        }
        .editBtn {
            width: 250px;
            height: auto;
        }
    }

    @media (min-width: 768px) and (max-width: 1024px) {
        .main-content .form-group .form-control {
            font-size: 2rem !important;
        }
        .bgc-white .btn {
            padding: 1rem 1rem !important;
            font-size: 2rem !important;
            width: 300px;
            height: 80px;
        }
        .commonBtnWidth {
            width:100% !important;
        }
        .editBtn {
            width: 250px;
            height: auto;
        }
    }
</style>

<div class="row">
@if(isset($groupId) && !empty($groupId) && $groupId == 1)
    <div class="col-md-9">
		<h6>Brand Promoter Incentive List</h6>
	</div>
@else
    <div class="col-md-9">
		<h6>Retailer Incentive List</h6>
	</div>
@endif

    <div class="col-md-3">
        <div class="form-group">
            <input type="text" name="serach" id="serach" class="form-control"/>
        </div>
    </div>
</div>


<div id="tag_container" class="table-responsive">
    <table class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th class="sorting" data-sorting_type="asc" data-column_name="id" style="cursor: pointer;">Sl.</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="incentive_title" style="cursor: pointer;">Title</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="incentive_category" style="cursor: pointer;">Category</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="product_model" style="cursor: pointer;">Model</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="zone" style="cursor: pointer;">Zone</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="incentive_group" style="cursor: pointer;">Group</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="incentive_amount" style="cursor: pointer;">Amount</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="min_qty" style="cursor: pointer;">Min.Qty</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="start_date" style="cursor: pointer;">Start Date</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="end_date" style="cursor: pointer;">End Date</th>
                <th class="sorting" data-sorting_type="asc" data-column_name="status" style="cursor: pointer;">Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @include('admin.incentive.incentive_result_data')
        </tbody>
    </table>
    <input type="hidden" name="hidden_page" id="hidden_page" value="1" />
    <input type="hidden" name="hidden_column_name" id="hidden_column_name" value="id" />
    <input type="hidden" name="hidden_sort_type" id="hidden_sort_type" value="asc" />
</div>

@section('page-scripts')
<script type="text/javascript">
jQuery(document).ready(function(){
    jQuery('.incentive-update-toggle').change(function(e) {
        e.preventDefault();
        var status      = jQuery(this).prop('checked') == true ? 1 : 0; 
        var IncentiveId = jQuery(this).data('id');
        var url         = APP_URL+"/incentive.status"+"/"+IncentiveId;
        jQuery.ajax({
            url:url,
            type:"GET",
            dataType:'JSON',
            cache: false,
            contentType: false,
            processData: false,
            success:function(response){
                if(response.success) {
                    Notiflix.Notify.Success( 'incentive Update Successfully' );
                    Notiflix.Loading.Remove(600);
                }
            
                if(response.error){
                    Notiflix.Notify.Failure( 'incentive Update Failed' );
                    Notiflix.Loading.Remove(600);
                }
            }
        });
    });
});
/*
function payIncentive(incentiveId) {
	var url    = APP_URL+"/payIncentiveBySalesOrder"+"/"+incentiveId;
	jQuery.ajax({
		url:url,
		type:"GET",
		dataType:'JSON',
		cache: false,
		contentType: false,
		processData: false,
		beforeSend: function() {
            Notiflix.Loading.Arrows('Data Processing');
        },
		success:function(response){
			console.log(response);
			if(response) {
				Notiflix.Loading.Remove(300);
			}
			
			if(response == "success") {
				Notiflix.Notify.Success('Order Incentive Pay Successfully');
				Notiflix.Loading.Remove(600);
			}
		
			if(response == "error"){
				Notiflix.Notify.Failure('Order Incentive Pay Failed');
				Notiflix.Loading.Remove(600);
			}
			
			if(response == "warning") {
				Notiflix.Notify.Warning('Sales Order Not Found');
				Notiflix.Loading.Remove(600);
			}
		}
	});
}
*/

function payIncentive() {
	var url    = APP_URL+"/payIncentiveBySalesOrder";
	jQuery.ajax({
		url:url,
		type:"GET",
		dataType:'JSON',
		cache: false,
		contentType: false,
		processData: false,
		beforeSend: function() {
            Notiflix.Loading.Arrows('Data Processing');
        },
		success:function(response){
			console.log(response);
			if(response) {
				Notiflix.Loading.Remove(300);
			}
			
			if(response == "success") {
				Notiflix.Notify.Success('Order Incentive Pay Successfully');
				Notiflix.Loading.Remove(600);
			}
		
			if(response == "error"){
				Notiflix.Notify.Failure('Order Incentive Pay Failed');
				Notiflix.Loading.Remove(600);
			}
			
			if(response == "warning") {
				Notiflix.Notify.Warning('Sales Order Not Found');
				Notiflix.Loading.Remove(600);
			}
		}
	});
}
</script>
<!--Pagination New Script Start-->
<script>
function clear_icon() {
    $('#id_icon').html('');
    $('#post_title_icon').html('');
}

function fetch_data(page, sort_type, sort_by, query) {
    $.ajax({
        url:"?page="+page+"&sortby="+sort_by+"&sorttype="+sort_type+"&query="+query,
        type:"get",
        success:function(data) {
            console.log(data);
            $('tbody').html('');
            $('tbody').html(data);
            $(this).data('toggle-on', true);
            jQuery('.toggle').each(function() {
                $(this).toggles({
                    on: $(this).data('toggle-on')
                });
            });
            if(!empty(data.json_data_for_excel_and_pdf))
            {
                $('#export_data').val(data.json_data_for_excel_and_pdf);
            }
            jQuery('input[type=checkbox][data-toggle^=toggle]').bootstrapToggle();
            var toggleJs  = APP_URL+"/public/admin/js/custom-js/toggle-information.js";
            jQuery.getScript(toggleJs);
        }
    })
}

jQuery(document).ready(function() {
    var searhText = document.getElementById('serach');
    searhText.onkeydown = function() {
        var key = event.keyCode || event.charCode;
        if( key == 8 ) {
            var getSearchVal = $('#serach').val();
            var length = getSearchVal.length;
            if(length <= 1) {
                var query       = $('#serach').val();
                var column_name = $('#hidden_column_name').val();
                var sort_type   = $('#hidden_sort_type').val();
                var page        = $('#hidden_page').val();
                fetch_data(page, sort_type, column_name, query);
            }
        }
    };

    jQuery(document).on('keyup', '#serach', function() {
        var getSearchVal = $('#serach').val();
        var length = getSearchVal.length;
        if(length >=3) {
            var query       = $('#serach').val();
            var column_name = $('#hidden_column_name').val();
            var sort_type   = $('#hidden_sort_type').val();
            var page        = $('#hidden_page').val();
            fetch_data(page, sort_type, column_name, query);
        }
    });

    jQuery(document).on('click', '.sorting', function(){
        var column_name     = $(this).data('column_name');
        var order_type      = $(this).data('sorting_type');
        var reverse_order   = '';
        if(order_type == 'asc') {
            $(this).data('sorting_type', 'desc');
            reverse_order = 'desc';
            clear_icon();
            $('#'+column_name+'_icon').html('<span class="glyphicon glyphicon-triangle-bottom"></span>');
        }
        if(order_type == 'desc') {
            $(this).data('sorting_type', 'asc');
            reverse_order = 'asc';
            clear_icon
            $('#'+column_name+'_icon').html('<span class="glyphicon glyphicon-triangle-top"></span>');
        }
        $('#hidden_column_name').val(column_name);
        $('#hidden_sort_type').val(reverse_order);
        var page    = $('#hidden_page').val();
        var query   = $('#serach').val();
        fetch_data(page, reverse_order, column_name, query);
    });

    
    jQuery(document).on('click', '.pagination a', function(event){
        event.preventDefault();
        var page = $(this).attr('href').split('page=')[1];
        $('#hidden_page').val(page);
        var column_name = $('#hidden_column_name').val();
        var sort_type   = $('#hidden_sort_type').val();
        var query       = $('#serach').val();
        $('li').removeClass('active');
        $(this).parent().addClass('active');
        fetch_data(page, sort_type, column_name, query);
    });
    
    // jQuery('.btnCloseModal').trigger('click');
    // jQuery('.btnCloseModal').mousedown();
    // jQuery('.close').click();
});
</script>
<!--Pagination New Script Start-->
@endsection
@endsection