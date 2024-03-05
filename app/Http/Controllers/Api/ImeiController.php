<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\DealerInformation;
use App\Models\DelarDistribution;
use App\Models\Imei;
use Carbon\Carbon;
use Validator;
use DB;

class ImeiController extends Controller
{    
    public function index() {
        return view('admin.ime.new_list');
    }
   
    public function create() {
        //
    }
   
    public function store(Request $request) {
        //
    }

    public function show($id) {
        //
    }
   
    public function edit($id) {
        //
    }

    public function update(Request $request, $id) {
        //
    }
    
    public function destroy($id) {
        //
    }
    
    public function checkImei($imeNumber) {
        $checkValidIMEI = strlen($imeNumber);
        if ($checkValidIMEI != 15)  {
            Log::error('IMEI Not Available');
            return response()->json(['status'=>'error']);
        }

        $getCurlResponse = getData(sprintf(RequestApiUrl("GetIMEIinfo"),$imeNumber),"GET");
        $responseData = (array) json_decode($getCurlResponse['response_data'],true);

        if (isset($responseData) && $responseData == "" || empty($responseData)) {
            Log::error('IMEI Info Not Found');
            return response()->json(['status'=>'error']);
        }
        
        $soldStatus = ($responseData[0]['IsValid'] == true) ? "Available":"Sold";
        $badgeStatus = ($responseData[0]['IsValid'] == true) ? "badge-success":"badge-danger";

        $imeiInfo = "<tr><th>Status</th><td><span class='peer'><span style='padding: 14px!important;width:20% !important' class='badge badge-pill fl-l ".$badgeStatus." lh-0 p-10 all-view-notification'>".$soldStatus."</span></span></td></tr><tr><th>Model</th><td>".$responseData[0]['Model']."</td></tr><tr><th>Color</th><td>".$responseData[0]['Color']."</td></tr><tr><th>IMEI 1</th><td>".$responseData[0]['ImeiOne']."</td></tr><tr><th>IMEI 2</th><td>".$responseData[0]['ImeiTwo']."</td></tr><tr><th>Dealer Code</th><td>".$responseData[0]['DealerCode']."</td></tr><tr><th>Distributor Name</th><td>".$responseData[0]['DistributorNameCellCom']."</td></tr><tr><th>Retailer Name</th><td>".$responseData[0]['RetailerName']."</td></tr><tr><th>Retailer Phone</th><td>".$responseData[0]['RetailerPhone']."</td></tr><tr><th>Retailer Address</th><td>".$responseData[0]['RetailerAddress']."</td></tr><tr><th>Retailer Zone</th><td>".$responseData[0]['RetailerZone']."</td></tr><tr><th>Dealer Zone</th><td>".$responseData[0]['DealerZone']."</td></tr>";

        Log::info('Get Product Info By IMEI');
        return response()->json(['status'=>'success','data'=>$imeiInfo]);
    }
}
