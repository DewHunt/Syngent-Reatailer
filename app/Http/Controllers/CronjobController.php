<?php

namespace App\Http\Controllers;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Employee;
use Carbon\Carbon;
use DB;
use Response;
use Mail;
use File;
class CronjobController extends Controller
{
    
    public function index(Request $request)
    {
        $clientType = "emp";
        $empIdList  = Employee::select('*')
        ->where('status',1)
        ->whereNotNull('email')
        ->whereNotNull('employee_id')
        ->get();
        
        if(isset($empIdList) && $empIdList->isNotEmpty()) 
        {
            foreach($empIdList as $erow) 
            {
                $empId             = $erow->employee_id;
                $empEmail          = $erow->email;
                $getCurlResponse   = getData(sprintf(RequestApiUrl("GetStock"),$empId,$clientType),"GET");
                $responseData      = json_decode($getCurlResponse['response_data'],true);

                $dataArray    = [];
                $userInfo     = [];
                $dealerTotQty   = [];
                $retailerTotQty = 0;
                $employeeTotQty = 0;
                $dealerStockTotQty = 0;
                $retailerInfo     ="";
                $retailerStockTotQty = 0;
                $dealerWaiseRetailerInfo = [];

                $clientInfo         = Employee::where('employee_id','=',$empId)->first();

                if(isset($responseData) && !empty($responseData))
                {
                    foreach($responseData as $key=>$row) 
                    {
                        $dataArray[$row['DealerCode']][$row['RetailerName']][] = $row['Model'].'~'.$row['StockQuantity'];

                        $employeeTotQty += $row['StockQuantity'];
                        $dealerTotQty[$row['DealerCode']][] = $row['StockQuantity'];

                        $userInfo[$row['DealerCode']] = [
                            "DealerName"=>$row['DealerName'],
                            "DistributorNameCellCom"=>$row['DistributorNameCellCom'],
                            "DealerPhone"=>$row['DealerPhone'],
                            "DealerZone"=>$row['DealerZone'],
                            "DealerCode"=>$row['DealerCode'],
                            "District"=>$row['District'],
                            "RetailerName"=>$row['RetailerName'],
                            "RetailerPhone"=>$row['RetailerPhone'],
                            "RetailerAddress"=>$row['RetailerAddress'],
                            "RetailerZone"=>$row['RetailerZone'],
                        ];
                        
                        $dealerWaiseRetailerInfo[$row['RetailerPhone']] = [
                            "RetailerName"=>$row['RetailerName'],
                            "RetailerPhone"=>$row['RetailerPhone'],
                            "RetailerAddress"=>$row['RetailerAddress'],
                            "RetailerZone"=>$row['RetailerZone'],
                            "OwnerName"=>$row['OwnerName'],
                            "ThanaName"=>$row['ThanaName'],
                            "Division"=>$row['Division']
                        ];
                        
                        
                    }
                }

                $htmlResponse = "";
                if(isset($dataArray) && !empty($dataArray)) {
                    $i = 1;
                    foreach($dataArray as $keys=>$retailers) {
                     $htmlResponse = "<table style='font-size: 12px; font-family: 'Helvetica Neue', Helvetica, Arial, Tahoma, sans-serif; color:#ffffff'><tbody><tr>Dealer: ".$userInfo[$keys]['DealerName']." ,DistributorNameCellCom: ".$userInfo[$keys]['DistributorNameCellCom']." ,Phone: ".$userInfo[$keys]['DealerPhone']." ,Zone: ".$userInfo[$keys]['DealerZone']." ,District: ".$userInfo[$keys]['District']."-".number_format(array_sum($dealerTotQty[$keys]))."</tr>";
                     
                     //$htmlResponse .="<tr style='background:#92D050'><th>".$i.".Retailer :".$dealerWaiseRetailerInfo[$key]['RetailerName']." ,Phone: ".$dealerWaiseRetailerInfo[$key]['RetailerPhone']." ,Retailer Address".$dealerWaiseRetailerInfo[$key]['RetailerAddress']." ,Retailer Thana:".$dealerWaiseRetailerInfo[$key]['ThanaName']." ,Retailer Zone:".$dealerWaiseRetailerInfo[$key]['RetailerZone']." ,Division:".$dealerWaiseRetailerInfo[$key]['Division']."</th></tr>";
                        
                        foreach($retailers as $key=>$items) {
                            $htmlResponse .="<tr style='background:#92D050'><th>".$i.".Retailer :".$key." ,Phone: ".$userInfo[$keys]['RetailerPhone']." ,Retailer Address".$userInfo[$keys]['RetailerAddress']." ,Retailer Zone:".$userInfo[$keys]['RetailerZone']."</th></tr>";

                            $htmlResponse .="<tr style='text-align:center'><th>Model</th><th>Qty</th></tr>";
                            $j=0; $totalQty = 0;
                            foreach($items as $k=>$val) {

                                $getRow = explode('~',$val); 
                                $modelName = $getRow[0];
                                $stockQty  = $getRow[1];
                                $totalQty  += $getRow[1];
                                $totalDealerQty[]= $totalQty;
                                $getStockInfo = checkModelStock($modelName);

                                $getbgColor = ($j % 2 == 0) ?'eeeeee':'ffffff';

                                $stockStatusColor = "";
                                if(isset($getStockInfo) && !empty($getStockInfo)) 
                                {
                                    if($getStockInfo->default_qty !=null && $getStockInfo->yeallow_qty !=null && $getStockInfo->red_qty !=null ) 
                                    {
                                        if($stockQty >= $getStockInfo->yeallow_qty && $stockQty < $getStockInfo->default_qty) 
                                        {
                                            $stockStatusColor = 'FFFF00';
                                        }
                                        elseif($stockQty < $getStockInfo->yeallow_qty && $stockQty >= $getStockInfo->red_qty) 
                                        {
                                            $stockStatusColor = 'FF0000';
                                        }
                                        else
                                        {
                                            $stockStatusColor = '7FFFD4';
                                        }
                                    }
                                    else
                                    {
                                        if($stockQty >= 1 && $stockQty < 2)
                                        {
                                            $stockStatusColor = 'FFFF00';
                                        }
                                        elseif($stockQty < 1 && $stockQty >= 0) {
                                            $stockStatusColor = 'FF0000';
                                        }
                                        else
                                        {
                                            $stockStatusColor = '7FFFD4';
                                        }
                                    }
                                }
                                else
                                {
                                    if($stockQty >= 1 && $stockQty < 2){
                                        $stockStatusColor = 'FFFF00';
                                    }
                                    elseif($stockQty < 1 && $stockQty >= 0){
                                        $stockStatusColor = 'FF0000';
                                    }
                                    else{
                                        $stockStatusColor = '7FFFD4';
                                    }
                                }
                                $htmlResponse .="<tr style='text-align:center; color:#000000;'><td>".$modelName."</td><td style='background:#".$stockStatusColor."'>".$stockQty."</td></tr>";
                            $j++;
                            }
                            $htmlResponse .='<tr style="background:#919292;color:white">
                                <th><span style="margin-left:0px">Total</span></th>
                                <th style="text-align: center;padding-left: 30px;">
                                    <span>'.number_format($totalQty).'</span>
                                </th>
                            </tr>';
                             $i++;
                        }
                        
                    }
                }

                $data = $htmlResponse;
                $fileName = $clientInfo['employee_id'].'.xls';
                File::put(public_path('/upload/employee_stock/'.$fileName),$data);
                
                if(isset($dataArray) && !empty($dataArray)) 
                {
                    Mail::send('admin.mail_confirmation.stock_alert_mail', ['responseData' => $responseData,'empEmail' => $empEmail, 'clientInfo'=>$clientInfo,'dataArray'=>$dataArray,'userInfo'=>$userInfo,'dealerTotQty'=>$dealerTotQty,'employeeTotQty'=>$employeeTotQty,'dealerStockTotQty'=>$dealerStockTotQty,'dealerWaiseRetailerInfo'=>$dealerWaiseRetailerInfo], function($message) use ($responseData,$empEmail,$clientInfo,$dataArray,$userInfo,$dealerTotQty,$employeeTotQty,$dealerStockTotQty,$dealerWaiseRetailerInfo) {
                        //$message->to('sayed.giantssoft@gmail.com');
                        $message->to($clientInfo['email']);
                        $message->subject('Current Stock Qty Status');
                        $message->from('info@example.com','Syngenta Retail Manager');
                        $message->attach(public_path('/upload/employee_stock/'.$clientInfo['employee_id'].'.xls'), [
                            'as' => $clientInfo['employee_id'].'.xls',
                            'mime' => 'application/xlsx',
                        ]);
                    });
                }
            }
        }
    }
    
    
    public function index_old()
    {
        $clientType = "emp";
        $empIdList  = Employee::select('employee_id')
        ->where('status',1)
        ->whereNotNull('email')
        ->whereNotNull('employee_id')
        ->get();

        $ProductInfo = [];
        if(isset($empIdList) && $empIdList->isNotEmpty()) 
        {
            foreach($empIdList as $erow) 
            {
                $empId             = $erow->employee_id;
                $getCurlResponse   = getData(sprintf(RequestApiUrl("GetStock"),$empId,$clientType),"GET");
                $responseData      = json_decode($getCurlResponse['response_data'],true);

                
                if(isset($responseData) && !empty($responseData)) 
                {
                    foreach($responseData as $key=>$row) 
                    {
                        $getStockInfo = checkBPFocusModelStock($row['Model']);

                        if(isset($getStockInfo) && !empty($getStockInfo)) {
                            if($getStockInfo->green !=null && $getStockInfo->yellow !=null && $getStockInfo->red !=null ) {
                                if($row['StockQuantity'] >= $getStockInfo->yellow && $row['StockQuantity'] < $getStockInfo->green) {
                                    //echo "Mail Send";
                                    $ProductInfo[$row['EmpId']][] = [
                                        'empEmail'=>$erow->email,
                                        'DealerName' =>$row['DealerName'],
                                        "DealerCode"=>$row['DealerCode'],
                                        'DealerPhone'=>$row['DealerPhone'],
                                        'RetailerName'=>$row['RetailerName'],
                                        'RetailerPhone'=>$row['RetailerPhone'],
                                        'Model'=>$row['Model'],
                                        'AvailableQty'=>$row['StockQuantity'],
                                        'Status'=>'yellow'
                                    ];
                                    //$this->stockReportSendMail($erow->email,$data);
                                }
                                elseif($row['StockQuantity'] < $getStockInfo->yellow && $row['StockQuantity'] >= $getStockInfo->red) {
                                    //echo "Mail Send";

                                    $ProductInfo[$row['EmpId']][] = [
                                        'empEmail'=>$erow->email,
                                        'DealerName' =>$row['DealerName'],
                                        "DealerCode"=>$row['DealerCode'],
                                        'DealerPhone'=>$row['DealerPhone'],
                                        'RetailerName'=>$row['RetailerName'],
                                        'RetailerPhone'=>$row['RetailerPhone'],
                                        'Model'=>$row['Model'],
                                        'AvailableQty'=>$row['StockQuantity'],
                                        'Status'=>'red'
                                    ];

                                    //$this->stockReportSendMail($erow->email,$data);
                                }
                            } 
                            else {
                                if($row['StockQuantity'] >= 1 && $row['StockQuantity'] < 2) {
                                    //echo "Mail Send";

                                    $ProductInfo[$row['EmpId']][] = [
                                        'empEmail'=>$erow->email,
                                        'DealerName' =>$row['DealerName'],
                                        "DealerCode"=>$row['DealerCode'],
                                        'DealerPhone'=>$row['DealerPhone'],
                                        'RetailerName'=>$row['RetailerName'],
                                        'RetailerPhone'=>$row['RetailerPhone'],
                                        'Model'=>$row['Model'],
                                        'AvailableQty'=>$row['StockQuantity'],
                                        'Status'=>'yellow'
                                    ];


                                    //$this->stockReportSendMail($erow->email,$data);
                                }
                                elseif($row['StockQuantity'] < 1 && $row['StockQuantity'] >= 0) {
                                    //echo "Mail Send";

                                    $ProductInfo[$row['EmpId']][] = [
                                        'empEmail'=>$erow->email,
                                        'DealerName' =>$row['DealerName'],
                                        "DealerCode"=>$row['DealerCode'],
                                        'DealerPhone'=>$row['DealerPhone'],
                                        'RetailerName'=>$row['RetailerName'],
                                        'RetailerPhone'=>$row['RetailerPhone'],
                                        'Model'=>$row['Model'],
                                        'AvailableQty'=>$row['StockQuantity'],
                                        'Status'=>'red'
                                    ];

                                    //$this->stockReportSendMail($erow->email,$data);
                                }
                            }
                        }
                        else 
                        {
                            if($row['StockQuantity'] >= 1 && $row['StockQuantity'] < 2) {

                                $ProductInfo[$row['EmpId']][] = [
                                    'empEmail'=>$erow->email,
                                    'DealerName' =>$row['DealerName'],
                                    "DealerCode"=>$row['DealerCode'],
                                    'DealerPhone'=>$row['DealerPhone'],
                                    'RetailerName'=>$row['RetailerName'],
                                    'RetailerPhone'=>$row['RetailerPhone'],
                                    'Model'=>$row['Model'],
                                    'AvailableQty'=>$row['StockQuantity'],
                                    'Status'=>'yellow'
                                ];


                                //$this->stockReportSendMail($erow->email,$data);
                            }
                            elseif($row['StockQuantity'] < 1 && $row['StockQuantity'] >= 0) {

                                $ProductInfo[$row['EmpId']][] = [
                                    'empEmail'=>$erow->email,
                                    'DealerName' =>$row['DealerName'],
                                    "DealerCode"=>$row['DealerCode'],
                                    'DealerPhone'=>$row['DealerPhone'],
                                    'RetailerName'=>$row['RetailerName'],
                                    'RetailerPhone'=>$row['RetailerPhone'],
                                    'Model'=>$row['Model'],
                                    'AvailableQty'=>$row['StockQuantity'],
                                    'Status'=>'red'
                                ];

                                //$this->stockReportSendMail($erow->email,$data);
                            }
                        }
                    }
                }
            }
        }

        if(isset($ProductInfo) && !empty($ProductInfo)) {
            //echo "<pre>";print_r($ProductInfo);echo "</pre>"; exit();
            foreach($ProductInfo as $k=>$rowDataList) {
    
                $getEmail  = Employee::where('employee_id',$k)->first();
                $sendEmail = $getEmail['email']; //'sayed.giantssoft@gmail.com';
                Mail::send('admin.mail_confirmation.stock_alert_mail', ['rowDataList' => $rowDataList,'getEmail' => $getEmail], function($message) use ($rowDataList,$getEmail) {
                    //$message->to('sayed.giantssoft@gmail.com');
                    $message->to($getEmail['email']);
                    $message->subject('Current Stock Qty Status');
                    $message->from('info@example.com','Syngenta Retail Manager');
                });
            }
        }
    }
}
