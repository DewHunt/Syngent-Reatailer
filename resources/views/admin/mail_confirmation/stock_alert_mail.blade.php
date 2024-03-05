Dear User
<h3>You'r Stock List Goes To Here</h3>
@php $dealerStockQty = []; @endphp
@if(isset($responseData))
<table width="100%" border="0" cellspacing="0" cellpadding="6" align="left"  style="font-size: 12px; font-family: 'Helvetica Neue', Helvetica, Arial, Tahoma, sans-serif; color:#ffffff">
    <tr style="background:#919292;width:100%">
        <th class="text-center" colspan="2">Employee Information</th>
    </tr>
    <tr style="color:black">
        <th style="float: right">Employee ID </th>
        <td style="text-align:center;">
            @if(!empty($clientInfo['employee_id']))
                {{ $clientInfo['employee_id'] }}
            @else
                {{ $clientInfo['EmployeeId'] }}
            @endif
        </td>
    </tr>
    <tr style="background-color:gray;color:white">
        <th style="float: right">Name </th>
        <td style="text-align:center;">
            @if(!empty($clientInfo['name']))
                {{ $clientInfo['name'] }}
            @else
                {{ $clientInfo['EmployeeName'] }}
            @endif
        </td>
    </tr>
    <tr style="color:black">
        <th style="float: right">Phone </th>
        <td style="text-align:center;">
            @if(!empty($clientInfo['mobile_number']))
                {{ $clientInfo['mobile_number'] }}
            @else
                {{ $clientInfo['MobileNumber'] }}
            @endif
        </td>
    </tr>
    <tr style="background-color:gray;color:white">
        <th style="float: right">Email </th>
        <td style="color:white;text-align:center;">

            @if(!empty($clientInfo['email']))
                {{ $clientInfo['email'] }}
            @else
                {{ $clientInfo['Email'] }}
            @endif
        </td>
    </tr>
    <tr style="color:black">
        <th style="float: right">Designation </th>
        <td style="text-align:center;">
            @if(!empty($clientInfo['designation']))
                {{ $clientInfo['designation'] }}
            @else
                {{ $clientInfo['Designation'] }}
            @endif
        </td>
    </tr>
    <tr style="background-color:gray;color:white">
        <th style="float: right">Total Stock Qty </th>
        <td style="text-align:center;"><span class="totqty textSize">{{ ($employeeTotQty) ? number_format($employeeTotQty):0 }}</span></td>
    </tr>
</table>
@endif

@if(isset($dataArray) && !empty($dataArray))
    @foreach($dataArray as $keys=>$retailers)
    <table id="dataExport" width="100%" border="0" cellspacing="0" cellpadding="6" align="left"  style="font-size: 12px; font-family: 'Helvetica Neue', Helvetica, Arial, Tahoma, sans-serif; color:#ffffff">
        <tbody>
            <tr>
                <th style="width:100%;background:#919292" colspan="2">
                    {{ 'Dealer :' }} @if(isset($userInfo)) {{ $userInfo[$keys]['DealerName'] }} ({{ $keys }}), {{ $userInfo[$keys]['DistributorNameCellCom'] }}, {{ $userInfo[$keys]['DealerPhone'] }}, {{ $userInfo[$keys]['DealerZone'] }}, {{ $userInfo[$keys]['District'] }} @endif <span class="totqty">{{ ' ,Total Stock Qty - '.number_format(array_sum($dealerTotQty[$keys])) }} </span>
                </th>
                {{-- <td style="padding:.3rem 8.3em !important;"></td> --}}
            </tr>
            @php $i = 1; @endphp
            @foreach($retailers as $key=>$items)
            <tr>
                <th colspan="2" style="width:100%;background-color:black;"><span style="margin-left:0px">{{$i}}.Retailer : </span>@if(isset($dealerWaiseRetailerInfo)) {{ $dealerWaiseRetailerInfo[$key]['RetailerName'] }}, {{ $dealerWaiseRetailerInfo[$key]['RetailerPhone'] }}, {{ $dealerWaiseRetailerInfo[$key]['RetailerAddress'] }}, {{ $dealerWaiseRetailerInfo[$key]['ThanaName'] }}, {{ $dealerWaiseRetailerInfo[$key]['RetailerZone'] }}, {{ $dealerWaiseRetailerInfo[$key]['Division'] }}@endif</th>
            </tr>
            <tr style="background-color:gray;">
                <th><span style="margin-left:0px">Model</span></th>
                <th style="float: left;">StockQty</th>
            </tr>
            @php $j=0; $totalQty = 0;  @endphp
            @foreach($items as $k=>$val)
                @php 
                $getRow = explode('~',$val); 
                $modelName = $getRow[0];
                $stockQty  = $getRow[1];
                $totalQty  += $getRow[1];
                $totalDealerQty[]= $totalQty;
                $getStockInfo = checkModelStock($modelName)
                @endphp
                <tr style="color:#000000;background:#{{ ($j % 2 == 0) ?'eeeeee':'ffffff'}}">
                    <td><span style="margin-left:550px;text-align: center;"> {{ $modelName }}</span></td>
                    <td><span style="text-center">
                    @if(isset($getStockInfo) && !empty($getStockInfo))
                        @if($getStockInfo->default_qty !=null && $getStockInfo->yeallow_qty !=null && $getStockInfo->red_qty !=null )
                            @if($stockQty >= $getStockInfo->yeallow_qty && $stockQty < $getStockInfo->default_qty)
                            <span style="background-color:yellow;width: 98px;padding: 5px 30px;">{{ $stockQty }}</span>
                            @elseif($stockQty < $getStockInfo->yeallow_qty && $stockQty >= $getStockInfo->red_qty)
                            <span style="background-color:red;width: 98px;padding: 5px 30px;">{{ $stockQty }}</span>
                            @else
                            <span style="background-color:aquamarine;width: 98px;padding: 5px 30px;">{{ $stockQty }}</span>
                            @endif
                        @else
                            @if($stockQty >= 1 && $stockQty < 2)
                            <span style="background-color:yellow;width: 98px;padding: 5px 30px;">{{ $stockQty }}</span>
                            @elseif($stockQty < 1 && $stockQty >= 0)
                            <span style="background-color:red;width: 98px;padding: 5px 30px;">{{ $stockQty }}</span>
                            @else
                            <span style="background-color:aquamarine;width: 98px;padding: 5px 30px;">{{ $stockQty }}</span>
                            @endif
                        @endif
                    @else
                        @if($stockQty >= 1 && $stockQty < 2)
                        <span style="background-color:yellow;width: 98px;padding: 5px 30px;">{{ $stockQty }}</span>
                        @elseif($stockQty < 1 && $stockQty >= 0)
                            <span style="background-color:red;width: 98px;padding: 5px 30px;">{{ $stockQty }}</span>
                        @else
                            <span style="background-color:aquamarine;width: 98px;padding: 5px 30px;">{{ $stockQty }}</span>
                        @endif
                    @endif
                    </span>
                    </td>
                </tr>
            @php $j++; @endphp
            @endforeach
            <tr style="background:#919292;color:white">
                <th><span style="margin-left:0px">Total</span></th>
                <th style="text-align: left;padding-left: 30px;">
                    <span>{{ number_format($totalQty) }}</span>
                </th>
            </tr>
            @php $i++; @endphp
            @endforeach
        </tbody>
    </table>
    @endforeach
@endif
<p>Please Confirm To Retailer Update To Stock</p>

Best Regards<hr><br/>

Syngenta Retail Management System
