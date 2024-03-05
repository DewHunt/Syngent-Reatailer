@extends('admin.master.master')
@section('content')
<style>
.webbtn {
    float: left;
    margin-left: 5px;
}
.textSize {
    font-size: 16px;
    font-weight: bold;
}
.totqty {
    color: green;
}
.totalBg{
    background-color: green !important;
    color: #ffffff;
}
.table-striped tbody tr:nth-of-type(2n+1) {
    background-color: unset;
}
/* 
##Device = Desktops
##Screen = 1281px to higher resolution desktops
*/
@media (min-width: 1281px) {
    .main-content .btn-group-sm>.btn, .btn-sm {
        font-size: 0.90rem !important;
        padding: 0.3rem 1rem !important;
        width: 100%;
        margin: 2px;
    }
    .beforeAddBtn{
        padding: 0px 7px;
    }
    .newAddBtn{
        margin-left: 5px;
        width: 280px;
        margin: 0px;
        padding-left: 0px !important;
        padding-right: 0px !important;
    }
    .eyeViewbtn {
        padding: 2px 6px;
        width: 100%;
        height: 33px;
    }
    .exportbtn {
        width: 10%;
        margin-left: 0px;
        margin-bottom: 5px;
        float: right;
        margin-right: 3px;
    }
    .statusLabel {
        padding: 3px 5px !important;
        height: 30px;
        width: 100%;
    }
}
@media only screen 
    and (min-device-width: 320px) 
    and (max-device-width: 568px)
    and (-webkit-min-device-pixel-ratio: 2) {
        
    .select-h select.form-control:not([size]):not([multiple]) {
        height: 82px !important;
        padding: 0rem .75rem !important;
        font-size: 1.5rem !important;
    }
    .bp-col-sm {
       -ms-flex: 0 0 100% !important;
        flex: 0 0 100% !important;
        max-width: 100% !important;
    }
    .bp-btn-sm {
        -ms-flex: 0 0 50% !important;
        flex: 0 0 50% !important;
        max-width: 50% !important;
    }
    .btn-float {
        float: right;
    }
    .btncw{
        width: 350px !important;
    }
    .textSize {
        font-size: 26px;
        font-weight: bold;
    }
    .getStockDownload {
        height: 60px;
        width: 250px;
        font-size: 30px;
        margin-bottom: 16px;
    }
    .textCenterMobile{
        margin-left: 100px;
    }
}
@media only screen 
    and (min-device-width: 375px) 
    and (max-device-width: 812px) 
    and (-webkit-min-device-pixel-ratio: 3){

    .select-h select.form-control:not([size]):not([multiple]) {
        height: 82px !important;
        padding: 0rem .75rem !important;
        font-size: 1.5rem !important;
    }
    .bp-col-sm {
       -ms-flex: 0 0 100% !important;
        flex: 0 0 100% !important;
        max-width: 100% !important;
    }
    .bp-btn-sm {
        -ms-flex: 0 0 50% !important;
        flex: 0 0 50% !important;
        max-width: 50% !important;
    }
    .btn-float {
        float: right;
    }
    .btncw{
        width: 350px !important;
    }
    .textSize {
        font-size: 26px;
        font-weight: bold;
    }
    .getStockDownload {
        height: 60px;
        width: 250px;
        font-size: 30px;
        margin-bottom: 16px;
    }
    .textCenterMobile{
        margin-left: 100px;
    }
}
@media (min-width: 768px) and (max-width: 1024px) {
    .select-h select.form-control:not([size]):not([multiple]) {
        height: 82px !important;
        padding: 0rem .75rem !important;
        font-size: 1.5rem !important;
    }
    .bp-col-sm {
        -ms-flex: 0 0 100% !important;
        flex: 0 0 100% !important;
        max-width: 100% !important;
    }
    .bp-btn-sm {
        -ms-flex: 0 0 50% !important;
        flex: 0 0 50% !important;
        max-width: 50% !important;
    }
    .btn-float {
        float: right;
    }
    .btncw{
        width: 350px !important;
    }
}
</style>
<h4 class="c-grey-900 mB-20">Stock List</h4>
<div class="row">
    <div class="masonry-item col-md-7 mY-10 masonry-col bp-col-sm">
        <div class="bgc-white p-20 bd">
            <div class="peer">
                <form method="post" action="{{ route('retailer.search-stock') }}" id="stockSearch">
                    @csrf
                    <div class="form-row">
                        <div class="form-group col-md-3 select-h bp-col-sm">
                            <label>Client Type <span class="required">*</span></label>
                            <select class="form-control" name="client_type" id="clientType" required="">
                                <option value="">Select</option>
                                <option value="emp" @if($clientType == 'emp') selected="selected" @endif>Employee</option>
                                <option value="dealer" @if($clientType == 'dealer') selected="selected" @endif>Dealer</option>
                                <option value="retailer" @if($clientType == 'retailer') selected="selected" @endif>Retailer</option>
                            </select>
                        </div>

                        <div class="form-group col-md-5  bp-col-sm">
                            <span id="setlabel"><label>Phone / Dealer Code / Employee ID</label></span>&nbsp;<span class="required">*</span><input type="text" name="search_id" id="searchId" value="{{ $searchId }}" class="form-control" required>
                        </div>
                        @php 
                        if(!empty(Session::get('searchModel'))){
                            $getModel = Session::get('searchModel');
                        }
                        else
                        {
                            $getModel = [];
                        }
                        
                        @endphp

                        <div class="form-group col-md-4  bp-col-sm">
                            <span id="setlabel"><label>Model</label></span>
                            <select class="select2" multiple="multiple" data-placeholder="Select a Model" data-dropdown-css-class="select2-purple" style="width: 100%;" id="product_model" name="model[]">
                                <option value="">Select Model</option>
                                @if(isset($modelList))
                                    @foreach($modelList as $row)
                                    <option value="{{ $row->product_model }}" @if(in_array($row['product_model'],$getModel)) selected="selected" @endif>{{ $row->product_model }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="form-group col-md-12">
                            <input type="hidden" class="resultType" name="result_type" value="2">
                            <div class="bp-btn-sm">
                            <button type="submit" class="btn btn-info cur-p btn-secondary pull-left mb-2 btncw" onclick="getStockType(1)">Details</button> </div>&nbsp;&nbsp;&nbsp;
                            <div class="bp-btn-sm btn-float webbtn">
                            <button type="submit" class="btn btn-success cur-p btn-secondary btncw"  onclick="getStockType(2)">Summary</button>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
    @php $dealerStockQty = []; @endphp
    <div class="masonry-item col-md-5 mY-10 bp-col-sm">
        {{-- <div class="bgc-white p-5 bd"> --}}
        <div class="peer">
            <div class="form-row">
                @if(isset($responseData))
                <table class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
                    <tr>
                        <th class="text-center" colspan="2">
                            @if($clientType =='retailer')
                                {{ $User = "Retailer" }} Information
                            @elseif($clientType =='dealer')
                                {{ $User = "Dealer" }} Information
                            @elseif($clientType =='emp')
                                {{ $User = "Employee" }} Information
                            @endif
                        </th>
                    </tr>
                    
                    @if($clientType =='emp')
                    <tr>
                        <th>Employee ID </th>
                        <td>
                            @if(!empty($clientInfo['employee_id']))
                                {{ $clientInfo['employee_id'] }}
                            @else
                                {{ $clientInfo['EmployeeId'] }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Name </th>
                        <td>
                            @if(!empty($clientInfo['name']))
                                {{ $clientInfo['name'] }}
                            @else
                                {{ $clientInfo['EmployeeName'] }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Phone </th>
                        <td>
                            @if(!empty($clientInfo['mobile_number']))
                                {{ $clientInfo['mobile_number'] }}
                            @else
                                {{ $clientInfo['MobileNumber'] }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Email </th>
                        <td>

                            @if(!empty($clientInfo['email']))
                                {{ $clientInfo['email'] }}
                            @else
                                {{ $clientInfo['Email'] }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Designation </th>
                        <td>
                            @if(!empty($clientInfo['designation']))
                                {{ $clientInfo['designation'] }}
                            @else
                                {{ $clientInfo['Designation'] }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Total Stock Qty </th>
                        <td><span class="totqty textSize">{{ ($employeeTotQty) ? number_format($employeeTotQty):0 }}</span></td>
                    </tr>
                    @endif

                    @if($clientType =='dealer')
                    <tr>
                        <th>Dealer Code</th>
                        <td>{{ $clientInfo['dealer_code'] }},{{ $clientInfo['alternate_code'] }}</td>
                    </tr>
                    <tr>
                        <th>Name</th>
                        <td>{{ $clientInfo['dealer_name'] }}</td>
                    </tr>
                    <tr>
                        <th>Phone</th>
                        <td>{{ $clientInfo['dealer_phone_number'] }}</td>
                    </tr>
                    <tr>
                        <th>Address</th>
                        <td>{{ $clientInfo['dealer_address'] }}</td>
                    </tr>
                    <tr>
                        <th>Zone</th>
                        <td>{{ $clientInfo['zone'] }}</td>
                    </tr>
                    <tr>
                        <th>Total Stock Qty</th>
                        <td><span class="totqty textSize">{{ ($dealerStockTotQty) ? number_format($dealerStockTotQty):0  }}</span></td>
                    </tr>
                    @endif

                    @if($clientType =='retailer')
                        <tr>
                            <th>Retailer Name</th>
                            <td>{{ $retailerInfo['RetailerName'] }}</td>
                        </tr>
                        <tr>
                            <th>Retailer Phone</th>
                            <td>{{ $retailerInfo['RetailerPhone'] }}</td>
                        </tr>
                        <tr>
                            <th>Address</th>
                            <td>{{ $retailerInfo['RetailerAddress'] }}</td>
                        </tr>
                        <tr>
                            <th>Zone</th>
                            <td>{{ $retailerInfo['RetailerZone'] }}</td>
                        </tr>
                        <tr>
                            <th>Dealer Name</th>
                            <td>{{ $retailerInfo['DealerName'] }} ( {{ $retailerInfo['DealerCode'] }} )</td>
                        </tr>
                        <tr>
                            <th>Dealer Zone</th>
                            <td>{{ $retailerInfo['DealerZone'] }}</td>
                        </tr>
                        <tr>
                            <th>Total Stock Qty</th>
                            <td><span class="totqty textSize">{{ ($retailerStockTotQty) ? number_format($retailerStockTotQty):0 }}</span></td>
                        </tr>
                    @endif
                </table>
                @endif
            </div>
        </div>
        {{-- </div> --}}
    </div>
</div>

@if(isset($resultType) && $resultType == 100)
    <style type="text/css">
    .stock-download-div .row{
        margin-right:0px !important;
    }
    .well {
        min-height: 20px;
        padding: 19px;
        margin-bottom: 20px;
        background-color: #f5f5f5;
        border: 1px solid #e3e3e3;
        border-radius: 4px;
        -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
        box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
        color: #000;
        font-size: 15px;
    }
    .well-sm {
        padding: 9px;
        border-radius: 3px;
    }
    </style>

    @if($clientType == 'emp')
        @if(isset($dataArray) && !empty($dataArray))
        <div id="accordion">
            @foreach($dataArray as $keys=>$retailers)
            <div class="well well-sm">{{ 'Dealer :' }} - {{ $keys }},   @if(isset($userInfo)) {{ $userInfo['DistributorNameCellCom'] }}, {{ $userInfo['DealerPhone'] }}, {{ $userInfo['DealerZone'] }}, {{ $userInfo['District'] }} @endif
            </div>
            <div class="card">
                @php $i = 1; @endphp
                @foreach($retailers as $key=>$items)
                    <div class="card-header">
                        <a class="card-link" data-toggle="collapse" href="#collapse{{$i}}"><i class="c-light-blue-500 fa fa-server"></i> {{ $key }}, @if(isset($userInfo)) {{ $userInfo['RetailerPhone'] }}, {{ $userInfo['RetailerAddress'] }}, {{ $userInfo['RetailerZone'] }}@endif</a>
                    </div>
                    <div id="collapse{{$i}}" class="collapse @if($i==1) show @endif" data-parent="#accordion">
                        <div class="card-body">
                        <table class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>Model Name</th>
                                    <th>StockQty</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($items as $k=>$val)
                                @php 
                                $getRow = explode('~',$val); 
                                $modelName = $getRow[0];
                                $stockQty  = $getRow[1];
                                $getStockInfo = checkModelStock($modelName)
                                @endphp
                                <tr>
                                    <td>{{ $modelName }}</td>
                                    <td>
                                    
                                    @if(isset($getStockInfo) && !empty($getStockInfo))
                                        @if($getStockInfo->default_qty !=null && $getStockInfo->yeallow_qty !=null && $getStockInfo->red_qty !=null )
                                            @if($stockQty >= $getStockInfo->yeallow_qty && $stockQty < $getStockInfo->default_qty)
                                            <button type="button" class="btn btn-warning btn-sm blink_me">{{ $stockQty }}</button>
                                            @elseif($stockQty < $getStockInfo->yeallow_qty && $stockQty >= $getStockInfo->red_qty)
                                            <button type="button" class="btn btn-danger btn-sm blink_me">{{ $stockQty }}</button>
                                            @else
                                            <button type="button" class="btn btn-sm blink_me">{{ $stockQty }}</button>
                                            @endif
                                        @else
                                            @if($stockQty >= 1 && $stockQty < 2)
                                            <button type="button" class="btn btn-warning btn-sm blink_me">{{ $stockQty }}</button>

                                            @elseif($stockQty < 1 && $stockQty >= 0)
                                            <button type="button" class="btn btn-danger btn-sm blink_me">{{ $stockQty }}</button>
                                            @else
                                            <button type="button" class="btn btn-sm blink_me">{{ $stockQty }}</button>
                                            @endif
                                        @endif
                                    @else
                                        @if($stockQty >= 1 && $stockQty < 2)
                                        <button type="button" class="btn btn-warning btn-sm blink_me">{{ $stockQty }}</button>

                                        @elseif($stockQty < 1 && $stockQty >= 0)
                                        <button type="button" class="btn btn-danger btn-sm blink_me">{{ $stockQty }}</button>
                                        @else
                                            <button type="button" class="btn btn-sm blink_me">{{ $stockQty }}</button>
                                        @endif
                                    @endif

                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        </div>
                    </div>

                    @php $i++; @endphp
                @endforeach
            </div>
            @endforeach
        </div>
        @endif
    @elseif($clientType == 'dealer')
        @if(isset($dataArray) && !empty($dataArray))
        <div id="accordion">
            <div class="card">
                @php $i = 1; @endphp
                @foreach($dataArray as $key=>$items)
                    <div class="card-header">
                        <a class="card-link" data-toggle="collapse" href="#collapse{{$i}}"><i class="c-light-blue-500 fa fa-server"></i> {{ $key }} , @if(isset($userInfo)) {{ $userInfo['RetailerPhone'] }}, {{ $userInfo['RetailerAddress'] }}, {{ $userInfo['RetailerZone'] }}@endif</a>
                    </div>
                    <div id="collapse{{$i}}" class="collapse @if($i==1) show @endif" data-parent="#accordion">
                        <div class="card-body">
                        <table class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>Model Name</th>
                                    <th>StockQty</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($items as $k=>$val)
                                @php 
                                $getRow = explode('~',$val); 
                                $modelName = $getRow[0];
                                $stockQty  = $getRow[1];
                                $getStockInfo = checkModelStock($modelName)
                                @endphp
                                <tr>
                                    <td>{{ $modelName }}</td>
                                    <td>
                                    
                                    @if(isset($getStockInfo) && !empty($getStockInfo))
                                        @if($getStockInfo->default_qty !=null && $getStockInfo->yeallow_qty !=null && $getStockInfo->red_qty !=null )
                                            @if($stockQty >= $getStockInfo->yeallow_qty && $stockQty < $getStockInfo->default_qty)
                                            <button type="button" class="btn btn-warning btn-sm blink_me">{{ $stockQty }}</button>
                                            @elseif($stockQty < $getStockInfo->yeallow_qty && $stockQty >= $getStockInfo->red_qty)
                                            <button type="button" class="btn btn-danger btn-sm blink_me">{{ $stockQty }}</button>
                                            @endif
                                        @else
                                            @if($stockQty >= 1 && $stockQty < 2)
                                            <button type="button" class="btn btn-warning btn-sm blink_me">{{ $stockQty }}</button>

                                            @elseif($stockQty < 1 && $stockQty >= 0)
                                            <button type="button" class="btn btn-danger btn-sm blink_me">{{ $stockQty }}</button>
                                            @endif
                                        @endif
                                    @else
                                        @if($stockQty >= 1 && $stockQty < 2)
                                        <button type="button" class="btn btn-warning btn-sm blink_me">{{ $stockQty }}</button>

                                        @elseif($stockQty < 1 && $stockQty >= 0)
                                        <button type="button" class="btn btn-danger btn-sm blink_me">{{ $stockQty }}</button>
                                        @endif
                                    @endif

                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        </div>
                    </div>

                    @php $i++; @endphp
                @endforeach
            </div>
        </div>
        @endif
    @elseif($clientType == 'retailer')
        @if(isset($dataArray) && !empty($dataArray))
        <div id="accordion">
            @foreach($dataArray as $keys=>$retailers)
            <div class="well well-sm">{{ $keys }}</div>
            <div class="card">
                <table class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Model Name</th>
                            <th>StockQty</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i = 1; @endphp
                        @foreach($retailers as $key=>$val)
                            @php 
                            $getRow = explode('~',$val); 
                            $modelName = $getRow[0];
                            $stockQty  = $getRow[1];
                            $getStockInfo = checkModelStock($modelName)
                            @endphp
                        <tr>
                            <td>{{ $modelName }}</td>
                            <td>
                            
                            @if(isset($getStockInfo) && !empty($getStockInfo))
                                @if($getStockInfo->default_qty !=null && $getStockInfo->yeallow_qty !=null && $getStockInfo->red_qty !=null )
                                    @if($stockQty >= $getStockInfo->yeallow_qty && $stockQty < $getStockInfo->default_qty)
                                    <button type="button" class="btn btn-warning btn-sm blink_me">{{ $stockQty }}</button>
                                    @elseif($stockQty < $getStockInfo->yeallow_qty && $stockQty >= $getStockInfo->red_qty)
                                    <button type="button" class="btn btn-danger btn-sm blink_me">{{ $stockQty }}</button>
                                    @endif
                                @else
                                    @if($stockQty >= 1 && $stockQty < 2)
                                    <button type="button" class="btn btn-warning btn-sm blink_me">{{ $stockQty }}</button>

                                    @elseif($stockQty < 1 && $stockQty >= 0)
                                    <button type="button" class="btn btn-danger btn-sm blink_me">{{ $stockQty }}</button>
                                    @endif
                                @endif
                            @else
                                @if($stockQty >= 1 && $stockQty < 2)
                                <button type="button" class="btn btn-warning btn-sm blink_me">{{ $stockQty }}</button>

                                @elseif($stockQty < 1 && $stockQty >= 0)
                                <button type="button" class="btn btn-danger btn-sm blink_me">{{ $stockQty }}</button>
                                @endif
                            @endif

                            </td>
                        </tr>
                        @php $i++; @endphp
                        @endforeach
                    </tbody>
                </table> 
            </div>
            @endforeach
        </div>
        @endif
    @endif
@endif
@if(isset($resultType) && $resultType == 200)
<div id="tag_container" class="table-responsive">
    <table class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Sl.</th>
                <th>Model</th>
                {{-- <th>Color</th> --}}
                <th>Total Stock</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($responseData))
                @foreach($responseData as $row)
                @php $getStockInfo = checkModelStock($row['Model']) @endphp
                <tr>
                    <td>{{ ++$loop->index }}.</td>
                    <td>{{ $row['Model'] }}</td>
                   {{--  <td>{{ $row['Color'] }}</td> --}}
                    <td>
                        @if(isset($getStockInfo) && !empty($getStockInfo))
                            @if($getStockInfo->default_qty !=null && $getStockInfo->yeallow_qty !=null && $getStockInfo->red_qty !=null )
                                @if($row['StockQuantity'] >= $getStockInfo->yeallow_qty && $row['StockQuantity'] < $getStockInfo->default_qty)
                                <button type="button" class="btn btn-warning btn-sm blink_me">{{ $row['StockQuantity'] }}</button>
                                @elseif($row['StockQuantity'] < $getStockInfo->yeallow_qty && $row['StockQuantity'] >= $getStockInfo->red_qty)
                                <button type="button" class="btn btn-danger btn-sm blink_me">{{ $row['StockQuantity'] }}</button>
                                @endif
                            @else
                                @if($row['StockQuantity'] >= 1 && $row['StockQuantity'] < 2)
                                <button type="button" class="btn btn-warning btn-sm blink_me">{{ $row['StockQuantity'] }}</button>

                                @elseif($row['StockQuantity'] < 1 && $row['StockQuantity'] >= 0)
                                <button type="button" class="btn btn-danger btn-sm blink_me">{{ $row['StockQuantity'] }}</button>
                                @endif
                            @endif
                        @else
                            @if($row['StockQuantity'] >= 1 && $row['StockQuantity'] < 2)
                            <button type="button" class="btn btn-warning btn-sm blink_me">{{ $row['StockQuantity'] }}</button>

                            @elseif($row['StockQuantity'] < 1 && $row['StockQuantity'] >= 0)
                            <button type="button" class="btn btn-danger btn-sm blink_me">{{ $row['StockQuantity'] }}</button>
                            @endif
                        @endif
                    </td>
                </tr>
                @endforeach
            @else
            <tr>
                <td colspan="4" class="text-center" style="color:red"> {{ 'Product Not Available' }}</td>
            </tr>
            @endif
        </tbody>
    </table>
</div>
@endif

{{-- Details Results --}}
@if(isset($resultType) && $resultType == 1)
    @if($clientType == 'emp')
        @if(isset($dataArray) && !empty($dataArray))
            <div class="row" style=" margin-right:0px !important;">
                <div class="col-md-12">
                    <a href="javascript:void(0)">
                        <button class="btn btn-primary cur-p btn-xs exportbtn getStockDownload">Download Excel</button>
                    </a>
                </div>
            </div>
            @foreach($dataArray as $keys=>$retailers)
            <div class="col-md-12">
                <table class="table table-striped table-bordered table-sm dataExport" cellspacing="0" width="100%">
                    <tbody>
                        <tr>
                            <th style="width:100%;">
                                {{ 'Dealer :' }} @if(isset($userInfo)) {{ $userInfo[$keys]['DealerName'] }} ({{ $keys }}), {{ $userInfo[$keys]['DistributorNameCellCom'] }}, {{ $userInfo[$keys]['DealerPhone'] }}, {{ $userInfo[$keys]['DealerZone'] }}, {{ $userInfo[$keys]['District'] }} @endif <span class="totqty">{{ ' ,Total Stock Qty - '.number_format(array_sum($dealerTotQty[$keys])) }} </span>
                            </th>
                            <td style="padding:.3rem 8.3em !important;"></td>
                        </tr>
                        @php $i = 1; @endphp
                        @foreach($retailers as $key=>$items)
                        {{-- <tr>
                            <th style="width:100%"><span style="margin-left:20px">{{$i}}.Retailer : {{ $key }}</span>, @if(isset($userInfo)) {{ $userInfo[$keys]['RetailerPhone'] }}, {{ $userInfo[$keys]['RetailerAddress'] }}, {{ $userInfo[$keys]['RetailerZone'] }}@endif</th>
                            <td></td>
                        </tr> --}}

                        <tr>
                            <th style="width:100%"><span style="margin-left:20px">{{$i}}.Retailer : </span>@if(isset($dealerWaiseRetailerInfo)) {{ $dealerWaiseRetailerInfo[$key]['RetailerName'] }}, {{ $dealerWaiseRetailerInfo[$key]['RetailerPhone'] }}, {{ $dealerWaiseRetailerInfo[$key]['RetailerAddress'] }}, {{ $dealerWaiseRetailerInfo[$key]['ThanaName'] }}, {{ $dealerWaiseRetailerInfo[$key]['RetailerZone'] }}, {{ $dealerWaiseRetailerInfo[$key]['Division'] }}@endif</th>
                            <td></td>
                        </tr>
                        <tr>
                            <th><span style="margin-left:100px">Model</span></th>
                            <th class="text-center"><span>StockQty</span></th>
                        </tr>
                        @php $totalQty = 0;  @endphp
                        @foreach($items as $k=>$val)
                            @php 
                            $getRow = explode('~',$val); 
                            $modelName = $getRow[0];
                            $stockQty  = $getRow[1];
                            $totalQty  += $getRow[1];
                            $totalDealerQty[]= $totalQty;
                            $getStockInfo = checkModelStock($modelName)
                            @endphp
                            <tr>
                                <td> <span style="margin-left:100px"> {{ $modelName }}</span></td>
                                <td> <span class="text-center textCenterMobile">
                                
                                @if(isset($getStockInfo) && !empty($getStockInfo))
                                    @if($getStockInfo->default_qty !=null && $getStockInfo->yeallow_qty !=null && $getStockInfo->red_qty !=null )
                                        @if($stockQty >= $getStockInfo->yeallow_qty && $stockQty < $getStockInfo->default_qty)
                                        <button type="button" class="btn btn-warning btn-sm blink_me">{{ $stockQty }}</button>
                                        @elseif($stockQty < $getStockInfo->yeallow_qty && $stockQty >= $getStockInfo->red_qty)
                                        <button type="button" class="btn btn-danger btn-sm blink_me">{{ $stockQty }}</button>
                                        @else
                                        <button type="button" class="btn btn-sm blink_me">{{ $stockQty }}</button>
                                        @endif
                                    @else
                                        @if($stockQty >= 1 && $stockQty < 2)
                                        <button type="button" class="btn btn-warning btn-sm blink_me">{{ $stockQty }}</button>

                                        @elseif($stockQty < 1 && $stockQty >= 0)
                                        <button type="button" class="btn btn-danger btn-sm blink_me">{{ $stockQty }}</button>
                                        @else
                                        <button type="button" class="btn btn-sm blink_me">{{ $stockQty }}</button>
                                        @endif
                                    @endif
                                @else
                                    @if($stockQty >= 1 && $stockQty < 2)
                                    <button type="button" class="btn btn-warning btn-sm blink_me">{{ $stockQty }}</button>

                                    @elseif($stockQty < 1 && $stockQty >= 0)
                                    <button type="button" class="btn btn-danger btn-sm blink_me">{{ $stockQty }}</button>
                                    @else
                                        <button type="button" class="btn btn-sm blink_me">{{ $stockQty }}</button>
                                    @endif
                                @endif
                                </span>
                                </td>
                            </tr>
                        @endforeach
                        <tr class="totalBg">
                            <th><span style="margin-left:100px">Total</span></th>
                            <th class="text-center"><span>{{ number_format($totalQty) }}</span></th>
                        </tr>
                        @php $i++; @endphp
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endforeach
        @endif
    @elseif($clientType == 'dealer')
        @if(isset($dataArray) && !empty($dataArray))

            <div class="row" style=" margin-right:0px !important;">
                <div class="col-md-12">
                    <a href="javascript:void(0)">
                        <button class="btn btn-primary cur-p btn-xs exportbtn getStockDownload">Download Excel</button>
                    </a>
                </div>
            </div>
            <div class="col-md-12">
                <table class="table table-striped table-bordered table-sm dataExport" cellspacing="0" width="100%">
                    <tbody>
                        @php $i = 1; @endphp
                        @foreach($dataArray as $key=>$items)
                            <tr>
                                <th style="width:70% !important"><span style="margin-left:20px">{{$i}}.Retailer : @if(isset($userInfo)) {{ $userInfo[$key]['RetailerName'] }}, {{ $userInfo[$key]['RetailerPhone'] }}, {{ $userInfo[$key]['RetailerAddress'] }}, , {{ $userInfo[$key]['ThanaName'] }}, {{ $userInfo[$key]['RetailerZone'] }} , {{ $userInfo[$key]['Division'] }}@endif<span></th>
                            </tr>
                            <tr>
                                <th><span style="margin-left:100px">Model</span></th>
                                <th class="text-center">StockQty</th>
                            </tr>
                            @php $totalQty = 0; @endphp
                            @foreach($items as $k=>$val)
                                @php 
                                $getRow = explode('~',$val); 
                                $modelName = $getRow[0];
                                $stockQty  = $getRow[1];
                                $totalQty  += $getRow[1];
                                $dealerStockQty[] = $getRow[1];
                                $getStockInfo = checkModelStock($modelName)
                                @endphp
                                <tr>
                                    <td>
                                        <span style="margin-left:100px">{{ $modelName }}</span>
                                    </td>
                                    <td class="text-center">
                                    
                                    @if(isset($getStockInfo) && !empty($getStockInfo))
                                        @if($getStockInfo->default_qty !=null && $getStockInfo->yeallow_qty !=null && $getStockInfo->red_qty !=null )
                                            @if($stockQty >= $getStockInfo->yeallow_qty && $stockQty < $getStockInfo->default_qty)
                                            <button type="button" class="btn btn-warning btn-sm blink_me">{{ $stockQty }}</button>
                                            @elseif($stockQty < $getStockInfo->yeallow_qty && $stockQty >= $getStockInfo->red_qty)
                                            <button type="button" class="btn btn-danger btn-sm blink_me">{{ $stockQty }}</button>
                                            @else
                                            <button type="button" class="btn btn-sm blink_me">{{ $stockQty }}</button>
                                            @endif
                                        @else
                                            @if($stockQty >= 1 && $stockQty < 2)
                                            <button type="button" class="btn btn-warning btn-sm blink_me">{{ $stockQty }}</button>

                                            @elseif($stockQty < 1 && $stockQty >= 0)
                                            <button type="button" class="btn btn-danger btn-sm blink_me">{{ $stockQty }}</button>
                                            @else
                                            <button type="button" class="btn btn-sm blink_me">{{ $stockQty }}</button>
                                            @endif
                                        @endif
                                    @else
                                        @if($stockQty >= 1 && $stockQty < 2)
                                        <button type="button" class="btn btn-warning btn-sm blink_me">{{ $stockQty }}</button>

                                        @elseif($stockQty < 1 && $stockQty >= 0)
                                        <button type="button" class="btn btn-danger btn-sm blink_me">{{ $stockQty }}</button>
                                        @else
                                            <button type="button" class="btn btn-sm blink_me">{{ $stockQty }}</button>
                                        @endif
                                    @endif

                                    </td>
                                </tr>
                            @endforeach
                            <tr class="totalBg">
                                <th><span style="margin-left:100px">Total</span></th>
                                <th class="text-center">{{ number_format($totalQty) }}</th>
                            </tr>
                        @php $i++; @endphp
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    @elseif($clientType == 'retailer')
        @if(isset($dataArray) && !empty($dataArray))
            <div class="row" style=" margin-right:0px !important;">
                <div class="col-md-12">
                    <a href="javascript:void(0)">
                        <button class="btn btn-primary cur-p btn-xs exportbtn getStockDownload">Download Excel</button>
                    </a>
                </div>
            </div>
            <div class="col-md-12">
                <table id="dataExport" class="table table-striped table-bordered table-sm dataExport" cellspacing="0" width="100%">
                    <tbody>
                        @php $i = 1; @endphp
                        @foreach($dataArray as $key=>$items)
                            <tr>
                                <th><span style="margin-left:100px">Model</span></th>
                                <th class="text-center">StockQty</th>
                            </tr>
                            @php $totalQty = 0; @endphp
                            @foreach($items as $k=>$val)
                                @php 
                                $getRow = explode('~',$val); 
                                $modelName = $getRow[0];
                                $stockQty  = $getRow[1];
                                $totalQty  += $getRow[1];
                                $getStockInfo = checkModelStock($modelName)
                                @endphp
                                <tr>
                                    <td>
                                        <span style="margin-left:100px">{{ $modelName }}</span>
                                    </td>
                                    <td class="text-center textCenterMobile">
                                    
                                    @if(isset($getStockInfo) && !empty($getStockInfo))
                                        @if($getStockInfo->default_qty !=null && $getStockInfo->yeallow_qty !=null && $getStockInfo->red_qty !=null )
                                            @if($stockQty >= $getStockInfo->yeallow_qty && $stockQty < $getStockInfo->default_qty)
                                            <button type="button" class="btn btn-warning btn-sm blink_me">{{ $stockQty }}</button>
                                            @elseif($stockQty < $getStockInfo->yeallow_qty && $stockQty >= $getStockInfo->red_qty)
                                            <button type="button" class="btn btn-danger btn-sm blink_me">{{ $stockQty }}</button>
                                            @else
                                            <button type="button" class="btn btn-sm blink_me">{{ $stockQty }}</button>
                                            @endif
                                        @else
                                            @if($stockQty >= 1 && $stockQty < 2)
                                            <button type="button" class="btn btn-warning btn-sm blink_me">{{ $stockQty }}</button>

                                            @elseif($stockQty < 1 && $stockQty >= 0)
                                            <button type="button" class="btn btn-danger btn-sm blink_me">{{ $stockQty }}</button>
                                            @else
                                            <button type="button" class="btn btn-sm blink_me">{{ $stockQty }}</button>
                                            @endif
                                        @endif
                                    @else
                                        @if($stockQty >= 1 && $stockQty < 2)
                                        <button type="button" class="btn btn-warning btn-sm blink_me">{{ $stockQty }}</button>

                                        @elseif($stockQty < 1 && $stockQty >= 0)
                                        <button type="button" class="btn btn-danger btn-sm blink_me">{{ $stockQty }}</button>
                                        @else
                                            <button type="button" class="btn btn-sm blink_me">{{ $stockQty }}</button>
                                        @endif
                                    @endif

                                    </td>
                                </tr>
                            @endforeach
                            <tr class="totalBg">
                                <th><span style="margin-left:100px">Total</span></th>
                                <th class="text-center">{{ number_format($totalQty) }}</th>
                            </tr>
                        @php $i++; @endphp
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    @endif
@endif

{{-- Summery Results --}}
@if(isset($resultType) && $resultType == 2)
    @if($clientType == 'emp')
        @if(isset($dataArray) && !empty($dataArray))
            <div class="row" style=" margin-right:0px !important;">
                <div class="col-md-12">
                    <a href="javascript:void(0)">
                        <button class="btn btn-primary cur-p btn-xs exportbtn getStockDownload">Download Excel</button>
                    </a>
                </div>
            </div>
            @foreach($dataArray as $keys=>$retailers)
            <div class="col-md-12">
                <table class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
                    <tbody>
                        <tr>
                            <th style="width:100%;">
                                {{ 'Dealer :' }} @if(isset($userInfo)) {{ $userInfo[$keys]['DealerName'] }} ({{ $keys }}), {{ $userInfo[$keys]['DistributorNameCellCom'] }}, {{ $userInfo[$keys]['DealerPhone'] }}, {{ $userInfo[$keys]['DealerZone'] }}, {{ $userInfo[$keys]['District'] }} @endif <span class="totqty">{{ ' ,Total Stock Qty - '.number_format(array_sum($dealerTotQty[$keys])) }} </span>
                            </th>
                            <td style="padding:.3rem 8.3em !important;"></td>
                        </tr>
                        @php $i = 1; @endphp
                        <tr>
                            <th><span style="margin-left:100px">Model</span></th>
                            <th class="text-center">StockQty</th>
                        </tr>
                        @foreach($retailers as $key=>$items)
                        @php $toalQty = 0; @endphp
                        @foreach($items as $k=>$val)
                            @php 
                            $getRow = explode('~',$val); 
                            $modelName = $getRow[0];
                            $stockQty  = $getRow[1];
                            $toalQty += $getRow[1];
                            $getStockInfo = checkModelStock($modelName)
                            @endphp
                            <tr>
                                <td><span style="margin-left:100px">{{ $modelName }}</span></td>
                                <td class="text-center">
                                
                                @if(isset($getStockInfo) && !empty($getStockInfo))
                                    @if($getStockInfo->default_qty !=null && $getStockInfo->yeallow_qty !=null && $getStockInfo->red_qty !=null )
                                        @if($stockQty >= $getStockInfo->yeallow_qty && $stockQty < $getStockInfo->default_qty)
                                        <button type="button" class="btn btn-warning btn-sm blink_me">{{ $stockQty }}</button>
                                        @elseif($stockQty < $getStockInfo->yeallow_qty && $stockQty >= $getStockInfo->red_qty)
                                        <button type="button" class="btn btn-danger btn-sm blink_me">{{ $stockQty }}</button>
                                        @else
                                        <button type="button" class="btn btn-sm blink_me">{{ $stockQty }}</button>
                                        @endif
                                    @else
                                        @if($stockQty >= 1 && $stockQty < 2)
                                        <button type="button" class="btn btn-warning btn-sm blink_me">{{ $stockQty }}</button>

                                        @elseif($stockQty < 1 && $stockQty >= 0)
                                        <button type="button" class="btn btn-danger btn-sm blink_me">{{ $stockQty }}</button>
                                        @else
                                        <button type="button" class="btn btn-sm blink_me">{{ $stockQty }}</button>
                                        @endif
                                    @endif
                                @else
                                    @if($stockQty >= 1 && $stockQty < 2)
                                    <button type="button" class="btn btn-warning btn-sm blink_me">{{ $stockQty }}</button>

                                    @elseif($stockQty < 1 && $stockQty >= 0)
                                    <button type="button" class="btn btn-danger btn-sm blink_me">{{ $stockQty }}</button>
                                    @else
                                        <button type="button" class="btn btn-sm blink_me">{{ $stockQty }}</button>
                                    @endif
                                @endif

                                </td>
                            </tr>
                        @endforeach
                        {{-- 
                        <tr>
                            <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Total</th>
                            <th class="text-center">{{ $toalQty }}</th>
                        </tr> 
                        --}}
                        @php $i++; @endphp
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endforeach
        @endif
    @elseif($clientType == 'dealer')
        @if(isset($dataArray) && !empty($dataArray))
            <div class="row" style=" margin-right:0px !important;">
                <div class="col-md-12">
                    <a href="javascript:void(0)">
                        <button class="btn btn-primary cur-p btn-xs exportbtn getStockDownload">Download Excel</button>
                    </a>
                </div>
            </div>
            <div class="col-md-12">
                <table class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
                    <tbody>
                        @php $i = 1; @endphp
                        <tr>
                            <th><span style="margin-left:100px">Model</span></th>
                            <th class="text-center">StockQty</th>
                        </tr>
                        @php $toalQty = 0; @endphp
                        @foreach($dataArray as $key=>$items)
                            @foreach($items as $k=>$val)
                                @php 
                                $getRow = explode('~',$val); 
                                $modelName = $getRow[0];
                                $stockQty  = $getRow[1];
                                $toalQty  += $getRow[1];
                                $getStockInfo = checkModelStock($modelName)
                                @endphp
                                <tr>
                                    <td>
                                        <span style="margin-left:100px">{{ $modelName }}</span>
                                    </td>
                                    <td class="text-center">
                                    
                                    @if(isset($getStockInfo) && !empty($getStockInfo))
                                        @if($getStockInfo->default_qty !=null && $getStockInfo->yeallow_qty !=null && $getStockInfo->red_qty !=null )
                                            @if($stockQty >= $getStockInfo->yeallow_qty && $stockQty < $getStockInfo->default_qty)
                                            <button type="button" class="btn btn-warning btn-sm blink_me">{{ $stockQty }}</button>
                                            @elseif($stockQty < $getStockInfo->yeallow_qty && $stockQty >= $getStockInfo->red_qty)
                                            <button type="button" class="btn btn-danger btn-sm blink_me">{{ $stockQty }}</button>
                                            @else
                                            <button type="button" class="btn btn-sm blink_me">{{ $stockQty }}</button>
                                            @endif
                                        @else
                                            @if($stockQty >= 1 && $stockQty < 2)
                                            <button type="button" class="btn btn-warning btn-sm blink_me">{{ $stockQty }}</button>

                                            @elseif($stockQty < 1 && $stockQty >= 0)
                                            <button type="button" class="btn btn-danger btn-sm blink_me">{{ $stockQty }}</button>
                                            @else
                                            <button type="button" class="btn btn-sm blink_me">{{ $stockQty }}</button>
                                            @endif
                                        @endif
                                    @else
                                        @if($stockQty >= 1 && $stockQty < 2)
                                        <button type="button" class="btn btn-warning btn-sm blink_me">{{ $stockQty }}</button>

                                        @elseif($stockQty < 1 && $stockQty >= 0)
                                        <button type="button" class="btn btn-danger btn-sm blink_me">{{ $stockQty }}</button>
                                        @else
                                            <button type="button" class="btn btn-sm blink_me">{{ $stockQty }}</button>
                                        @endif
                                    @endif

                                    </td>
                                </tr>
                            @endforeach
                        @php $i++; @endphp
                        @endforeach
                        <tr class="totalBg">
                            <th><span style="margin-left:100px">Total</span></th>
                            <th class="text-center">{{ number_format($toalQty) }}</th>
                        </tr>
                    </tbody>
                </table>
            </div>
        @endif
    @elseif($clientType == 'retailer')
        @if(isset($dataArray) && !empty($dataArray))
        <div class="row" style=" margin-right:0px !important;">
            <div class="col-md-12">
                <a href="javascript:void(0)">
                    <button class="btn btn-primary cur-p btn-xs exportbtn getStockDownload">Download Excel</button>
                </a>
            </div>
        </div>
         @foreach($dataArray as $keys=>$retailers)
            <div class="col-md-12">
                <table class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th class="text-center">Model Name</th>
                            <th class="text-center">StockQty</th>
                        </tr>
                    </thead>
                    <tbody>
                            @php $i = 1; $toalQty=0; @endphp
                            @foreach($retailers as $key=>$val)
                                @php 
                                $getRow = explode('~',$val); 
                                $modelName = $getRow[0];
                                $stockQty  = $getRow[1];
                                $toalQty  += $getRow[1];
                                $getStockInfo = checkModelStock($modelName)
                                @endphp
                            <tr>
                                <td class="text-center">{{ $modelName }}</td>
                                <td class="text-center">
                                
                                @if(isset($getStockInfo) && !empty($getStockInfo))
                                    @if($getStockInfo->default_qty !=null && $getStockInfo->yeallow_qty !=null && $getStockInfo->red_qty !=null )
                                        @if($stockQty >= $getStockInfo->yeallow_qty && $stockQty < $getStockInfo->default_qty)
                                        <button type="button" class="btn btn-warning btn-sm blink_me">{{ $stockQty }}</button>
                                        @elseif($stockQty < $getStockInfo->yeallow_qty && $stockQty >= $getStockInfo->red_qty)
                                        <button type="button" class="btn btn-danger btn-sm blink_me">{{ $stockQty }}</button>
                                        @endif
                                    @else
                                        @if($stockQty >= 1 && $stockQty < 2)
                                        <button type="button" class="btn btn-warning btn-sm blink_me">{{ $stockQty }}</button>

                                        @elseif($stockQty < 1 && $stockQty >= 0)
                                        <button type="button" class="btn btn-danger btn-sm blink_me">{{ $stockQty }}</button>
                                        @else
                                         <button type="button" class="btn btn-sm blink_me">{{ $stockQty }}</button>
                                        @endif
                                    @endif
                                @else
                                    @if($stockQty >= 1 && $stockQty < 2)
                                    <button type="button" class="btn btn-warning btn-sm blink_me">{{ $stockQty }}</button>

                                    @elseif($stockQty < 1 && $stockQty >= 0)
                                    <button type="button" class="btn btn-danger btn-sm blink_me">{{ $stockQty }}</button>
                                    @else
                                         <button type="button" class="btn btn-sm blink_me">{{ $stockQty }}</button>
                                    @endif
                                @endif

                                </td>
                            </tr>
                            @php $i++; @endphp
                            @endforeach
                            <tr>
                                <th class="text-center">Total</th>
                                <th class="text-center">{{ number_format($toalQty) }}</th>
                            </tr>
                        </tbody>
                </table>
            </div>
            @endforeach
        @endif
    @endif
@endif

@endsection