@extends('admin.master.master')
@section('css')
<style type="text/css">
.my-custom-scrollbar {
    position: relative;
    height: 53vh;
    overflow: auto;
}
.table-wrapper-scroll-y {
    display: block;
}
.subBtnheight {
    height:50px;
}
.cfmbpselectbox {
    width: 100% !important;
    height: 33px !important;
    font-size: 16px;
    /*padding: 1rem .75rem !important;*/
}
.select2-container .select2-selection--single {
    height: 32px;
}
.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 32px;
}
.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 30px;
}

@media only screen 
    and (min-device-width: 320px) 
    and (max-device-width: 568px)
    and (-webkit-min-device-pixel-ratio: 2) {
        .my-custom-scrollbar {
            position: relative;
            height: 1200px;
            overflow: auto;
        }
        .subBtnheight {
            height:70px;
        }
        .cfmbpselectbox {
            width: 60%;
            height: 58px !important;
            font-size: 24px;
        }
        .bgc-white {
            height: 150px !important;
        }
        .p-20 {
            padding: 20px 0px !important;
        }
        .select2-container .select2-selection--single {
            height: 67px;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 2;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 65px;
        }
    }
/* Portrait and Landscape */
@media only screen 
    and (min-device-width: 375px) 
    and (max-device-width: 812px) 
    and (-webkit-min-device-pixel-ratio: 3) {
        .my-custom-scrollbar {
            position: relative;
            height: 1200px;
            overflow: auto;
        }
        .subBtnheight {
            height:70px;
        }
        .cfmbpselectbox {
            width: 60%;
            height: 58px !important;
            font-size: 24px;
        }
        .bgc-white {
            height: 150px !important;
        }
        .cp-20 {
            padding: 20px 0px !important;
        }
        .col-md-12 {
            padding-right:  0px !important;
        }
        .select2-container .select2-selection--single {
            height: 67px;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 2;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 65px;
        }
    }
@media (min-width: 768px) and (max-width: 1024px) {
    .my-custom-scrollbar {
        position: relative;
        height: 1320px;
        overflow: auto;
    }
    .subBtnheight {
        height:70px;
    }
    .cfmbpselectbox {
        width: 60%;
        height: 58px !important;
        font-size: 24px;
    }
    .bgc-white {
        height: 150px !important;
    }
    .cp-20 {
        padding: 20px 0px !important;
    }
    .col-md-12 {
        padding-right:  0px !important;
    }
    .select2-container .select2-selection--single {
        height: 67px;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 2;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 65px;
    }
}
</style>
@endsection
@section('content')
<h4 class="c-grey-900">Focus Model To BP</h4>
@php $groupId = (Session::get('catId')) ? Session::get('catId'):1; @endphp
<div class="masonry-item col-md-12" style="padding-left:0px !important;padding-right: 0px !important;">
    <div class="bgc-white p-10 bd">
        <form method="post" action="{{ route('bpromoter.focus_model_to_bp_save') }}">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <label class="control-label">Select Category: <span class="required">*</span></label>
                    <select class="form-control cfmbpselectbox" name="category_id" id="group_category_id" style="width: 100%;" required="">
                        <option value="">Select Category</option>
                        @foreach($categoryLists as $cat)
                            <option value="{{ $cat->id }}" @if($cat->id == $groupId) selected="selected"@endif>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="control-label">Search Model:</label>
                    <select class="select2 form-control" data-placeholder="Select a Model" data-dropdown-css-class="select2-purple" style="width: 100%;" id="product_model" name="product_model">
                        <option value="">Select Model</option>
                        @if(isset($productModelLists))
                            @foreach($productModelLists as $row)
                                <option value="{{ $row->product_master_id }}">{{ $row->product_model }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>

            <br/>
            <input type="hidden" class="catId" value="{{ $catId }}">
            <div id="setModelList">
                @include('admin.bpromoter.product_model_list')
            </div>

            <div class="col-md-12 bgc-white cp-20 subBtnheight">
                <button type="submit" class="btn btn-primary btn-color pull-right mT-10">Submit</button>
            </div>

        </form>
    </div>
</div>

@section('page-scripts')
<script>
jQuery(document).on("change","#product_model",function(e) {
e.preventDefault();
var productId = $(this).val();
var url   = "bpromoter.focus_model_to_bp?modelId="+productId;
    jQuery.ajax({
    url:url,
    type:"GET",
    dataType:"JSON",
        beforeSend: function() {
            Notiflix.Loading.Arrows('Data Processing');
        },
        success:function(response) {
            console.log(response);
            Notiflix.Loading.Remove(100);
            $('#setModelList').html(response.modelLists);
        },
    });
});

jQuery(document).on("change","#group_category_id",function(e) {
e.preventDefault();
var catId = $(this).val();
var url   = "bpromoter.focus_model_to_bp?catId="+catId;
    jQuery.ajax({
    url:url,
    type:"GET",
    dataType:"JSON",
        beforeSend: function() {
            Notiflix.Loading.Arrows('Data Processing');
        },
        success:function(response) {
            console.log(response);
            Notiflix.Loading.Remove(100);
            $('#setModelList').html(response.modelLists);
            $('.catId').val(response.catId);
        },
    });
});
</script>
@endsection


@endsection