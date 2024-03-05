<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use App\Models\DealerInformation;
use Carbon\Carbon;
use DB;
use Validator;
use Pagination;
use DataTables;
use Response;

class DealerInformationController extends Controller
{
    
    public function index(Request $request)
    {
        $DelarList = DB::table('view_dealer_information_list')
        ->orderBy('dealer_name','ASC')
        ->get();

        if(isset($DelarList) && $DelarList->isNotEmpty()) {
            Log::info('Load Dealer List');
        } else {
            Log::warning('Dealer List Not Found');
        }
        return view('admin.dealer.list',compact('DelarList'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $rules = [
            'dealer_code'=>'required',
            'dealer_name'=>'required',
            'zone'=>'required',
            'dealer_phone_number'=>'required|digits:11|numeric',
        ];

        $validator = Validator::make($request->all(), $rules);
        
        if($validator->fails()) {
            Log::error('Create Dealer Validation Failed');
            return Response::json(['errors' => $validator->errors()]);
        }


        $DelarCode      = $request->input('dealer_code');
        $AlternateCode  = $request->input('alternate_code');

        $CheckInfo = DealerInformation::where('dealer_code',$DelarCode)
        ->orWhere('alternate_code',$AlternateCode)
        ->first();

        $status = 0;
        if($CheckInfo) {
            $UpdateDealerInfo = DealerInformation::where('id',$CheckInfo['id'])
            ->update([
                "dealer_id"=>$request->input('dealer_id'),
                "dealer_code"=> $request->input('dealer_code'),
                "alternate_code"=>  $request->input('alternate_code') ? $request->input('alternate_code'):0,
                "dealer_name"=> $request->input('dealer_name'),
                "dealer_address"=> strip_tags($request->input('dealer_address')),
                "zone"=> $request->input('zone'),
                "city"=> $request->input('city'),
                "division"=> $request->input('division'),
                "dealer_phone_number"=> $request->input('dealer_phone_number'),
                "dealer_type"=> $request->input('dealer_type'),
                "updated_at"=> Carbon::now()
            ]);

            if($UpdateDealerInfo) {
                $status = 1;
                Log::info('Existing Dealer Update');
            }
        } else {
            $AddDealerInfo = DealerInformation::create([
                "dealer_id"=>$request->input('dealer_id'),
                "dealer_code"=> $request->input('dealer_code'),
                "alternate_code"=>  $request->input('alternate_code') ? $request->input('alternate_code'):0,
                "dealer_name"=> $request->input('dealer_name'),
                "dealer_address"=> strip_tags($request->input('dealer_address')),
                "zone"=> $request->input('zone'),
                "city"=> $request->input('city'),
                "division"=> $request->input('division'),
                "dealer_phone_number"=> $request->input('dealer_phone_number'),
                "dealer_type"=> $request->input('dealer_type'),
                "created_at"=> Carbon::now(),
                "updated_at"=> Carbon::now()
            ]);
            if($AddDealerInfo) {
                Log::info('Create Dealer');
                $status = 1;
            } 
        }

        if($status == 1) {
            return response()->json('success');
        } else {
            return response()->json('error');
        }
    }

    public function ApiStore(Request $request)
    {
        
        $get_dealer_information = ['{"dealerCode":"16418","alternateCode":"54321","dealerName":"Syngenta Plaza-Meherpur-CELLCOM","DealerAddress":"Hotel Bazar, Bus Stand Road, Meherpur Sadar,Meherpur","City":"Meherpur","Division":"Khulna","DealerPhoneNumber":"01869117052","dealerType":"English Teacher"}','{"dealerCode":"16419","alternateCode":"16247","dealerName":"Syngenta Plaza-Meherpur-CELLCOM","DealerAddress":"Hotel Bazar, Bus Stand Road, Meherpur Sadar,Meherpur","City":"Meherpur","Division":"Khulna","DealerPhoneNumber":"01317243494","dealerType":"Software Support"}','{"dealerCode":"16246","alternateCode":"","dealerName":"Syngenta Plaza-Mirpur-CELLCOM","DealerAddress":"Mirpur 2,Near By Stadium Market,Pollice Cafe","City":"Dhaka","Division":"Dhaka","DealerPhoneNumber":"015523874584","dealerType":"Software Engineer"}','{"dealerCode":"16245","alternateCode":"","dealerName":"Syngenta Plaza-Gulshan-CELLCOM","DealerAddress":"Gulshan 2,Near By DNCC Market,Pollice Cafe","City":"Dhaka","Division":"Dhaka","DealerPhoneNumber":"01715383665","dealerType":"Delar"}'];


        $JsonArray = [];
        foreach ($get_dealer_information as $row) 
        {
            $JsonArray[] = json_decode($row,true);
        }

        $Status = 0;
        $previous_taken_delarcode = [];

        foreach($JsonArray as $row)
        {

            $DelarCode = $row['dealerCode'];
            $AlternateCode = $row['alternateCode'];

            $CheckInfo = DealerInformation::where('dealer_code',$DelarCode)->orWhere('alternate_code',$AlternateCode)->first();
            if($CheckInfo) {

                $UpdateDealerInfo = DealerInformation::where('id',$CheckInfo['id'])
                ->update([
                    "dealer_code"=> $row['dealerCode'],
                    "alternate_code"=>  !empty($row['alternateCode']) ? $row['alternateCode']:0,
                    "dealer_name"=> $row['dealerName'],
                    "dealer_address"=> $row['DealerAddress'],
                    "city"=> $row['City'],
                    "division"=> $row['Division'],
                    "dealer_phone_number"=> $row['DealerPhoneNumber'],
                    "dealer_type"=> $row['dealerType'],
                    "updated_at"=> Carbon::now()
                ]);

            } else {

                $AddDealerInfo = DealerInformation::create([
                    "dealer_code"=> $row['dealerCode'],
                    "alternate_code"=>  !empty($row['alternateCode']) ? $row['alternateCode']:0,
                    "dealer_name"=> $row['dealerName'],
                    "dealer_address"=> $row['DealerAddress'],
                    "city"=> $row['City'],
                    "division"=> $row['Division'],
                    "dealer_phone_number"=> $row['DealerPhoneNumber'],
                    "dealer_type"=> $row['dealerType'],
                    "created_at"=> Carbon::now(),
                    "updated_at"=> Carbon::now()
                ]); 

            }

        }
        return response()->json(["success"=>"Delar Information Insert Successfully"]);
    }

    public function show($id)
    {
        
        $DealerInfo = DB::table('view_dealer_information_list')->where('id',$id)->first();


        $upazila = (!empty($DealerInfo->upazila_name) || $DealerInfo->upazila_name !=null) ? $DealerInfo->upazila_name:"null";
        $district = (!empty($DealerInfo->district_name) || $DealerInfo->district_name !=null) ? $DealerInfo->district_name:"null";
        $division = (!empty($DealerInfo->division) || $DealerInfo->division !=null) ? $DealerInfo->division:"null";
        $city = (!empty($DealerInfo->city) || $DealerInfo->city !=null) ? $DealerInfo->city:"null";


        $dealerInfo = "<tr><th>Name</th><td>".$DealerInfo->dealer_name."</td></tr><tr><th>Phone</th><td>".$DealerInfo->dealer_phone_number."</td></tr><tr><th>Address</th><td>".$DealerInfo->dealer_address."</td></tr><tr><th>Dealer Code</th><td>".$DealerInfo->dealer_code."</td></tr><tr><th>Alternate Code</th><td>".$DealerInfo->alternate_code."</td></tr><tr><th>Zone</th><td>".$DealerInfo->zone."</td></tr><tr><th>City</th><td>".$city."</td></tr><tr><th>Division</th><td>".$division."</td></tr><tr><th>Upazila Name</th><td>".$upazila."</td></tr><tr><th>Districts Name</th><td>".$district."</td></tr><tr><th>Distributor Type</th><td>".$DealerInfo->distributor_type."</td></tr><tr><th>Product Brand</th><td>".$DealerInfo->product_brand."</td></tr>";
        return response()->json(['status'=>'info','data'=>$dealerInfo]);
    }
    
    public function edit($id)
    {
        if(isset($id) && $id > 0) {
            $DealerInfo = DB::table('view_dealer_information_list')
            ->where('id',$id)
            ->first();

            if($DealerInfo) {
                Log::info('Get Dealer By Id');
                return response()->json($DealerInfo);
            } else {
                Log::warning('Dealer Not Found By Id');
                return response()->json('error');
            }
        } else {
            Log::warning('Dealer Not Found By Id');
            return response()->json('error'); 
        }
    }

    public function update(Request $request)
    {
        $id = $request->input('dealer_id');

        $rules = [
            'dealer_code'=>'required',
            'dealer_name'=>'required',
            'zone'=>'required',
            'dealer_phone_number'=>'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        
        if($validator->fails()) {
            Log::error('Update Dealer Validation Failed');
            return Response::json(['errors' => $validator->errors()]);
        }

        $CheckDealer = DealerInformation::where('dealer_code',$request->input('dealer_code'))
        ->whereNotIn( 'id', [$id])
        ->first();

        if(isset($CheckDealer)) {
            return response()->json('error');
            Log::alert('Dealer Code Exit');
        } else {

            $UpdateDealerInfo = DealerInformation::where('id',$id)
            ->update([
                "dealer_id"=>$request->input('dealer_id'),
                "dealer_code"=> $request->input('dealer_code'),
                "alternate_code"=>  $request->input('alternate_code') ? $request->input('alternate_code'):0,
                "dealer_name"=> $request->input('dealer_name'),
                "dealer_address"=> $request->input('dealer_address'),
                "zone"=> $request->input('zone'),
                "city"=> $request->input('city'),
                "division"=> $request->input('division'),
                "dealer_phone_number"=> $request->input('dealer_phone_number'),
                "dealer_type"=> $request->input('dealer_type'),
                "updated_at"=> Carbon::now()
            ]);

            if($UpdateDealerInfo) {
                Log::info('Existing Dealer Updated');
                return response()->json('success');
            } else {
                Log::warning('Existing Dealer Updation Failed');
                return response()->json('error');
            }
        }
    }

    public function CheckDealerFromApi($DealerCode)
    {
        $DealerCode = (int)$DealerCode;
        if(isset($DealerCode) && $DealerCode > 0 && is_int($DealerCode)) {

            $getCurlResponse = getData(sprintf(RequestApiUrl("DealerCode"),$DealerCode),"GET");
            $responseData    = json_decode($getCurlResponse['response_data'],true);

            if(isset($getCurlResponse) && $getCurlResponse['status'] == 200) {
                Log::info('Get Response By Dealer Code');
                return response()->json($responseData);
            } else {
                Log::warning('Not Response By Dealer Code');
                return response()->json($getCurlResponse['response_data']);
            }
        } else {
            return response()->json('Invalid Dealer Code');
        } 
    }

    public function ChangeStatus($id)
    {
        if(isset($id) && $id > 0) {
            $StatusInfo = DealerInformation::find($id);
            $old_status = $StatusInfo->status;
            $UpdateStatus = $old_status == 1 ? 0 : 1;

            $UpdateDealerStatus = DealerInformation::where('id',$id)
            ->update([
                "status"=> $UpdateStatus ? $UpdateStatus:0
            ]);

            if($UpdateDealerStatus) {
                Log::info('Existing Dealer Status Update Success');
                return response()->json(['success'=>'Status change successfully.']);
            } else {
                Log::error('Existing Dealer Status Update Failed');
                return response()->json(['error'=>'Status Update Failed.Please Try Again.']);
            }
        } else {
            Log::error('Existing Dealer Id Not Found');
            return response()->json('error');
        }
    }

    public function AddToDealerFormApi()
    {
        $getCurlResponse    = getData(RequestApiUrl("DealerAll"),"GET");
        $responseData       = json_decode($getCurlResponse['response_data'],true);

        if(isset($getCurlResponse) && $getCurlResponse['status'] == 200) {
            $totalInsertRow =0;
            foreach ($responseData as $row) {
                $totalInsertRow +=1;
                $DealerID = $row['Id'];
                $status   = ($row['IsActive'] == true)?1:0; 
                $CheckDealer = DealerInformation::where('dealer_id',$DealerID)->first();
                if($CheckDealer) {
                    $UpdateDealerInfo = DealerInformation::where('dealer_id',$DealerID)
                    ->update([
                        "dealer_id"=>$row['Id'],
                        "dealer_code"=> $row['DigitechCode'],
                        "alternate_code"=>  $row['ImportCode'],
                        "dealer_name"=> $row['DistributorNameCellCom'],
                        "dealer_address"=> $row['Address'],
                        "zone"=> $row['Zone'],
                        "dealer_phone_number"=> $row['MobileNo'],
                        "zone_id"=>$row['ZoneId'],
                        "upazila_id"=>$row['UpazilaId'],
                        "district_id"=>$row['DistrictId'],
                        "division_id"=>$row['DivisionId'],
                        "upazila_name"=>$row['UpazilaName'],
                        "district_name"=>$row['DistrictName'],
                        "division_name"=>$row['DivisionName'],
                        "distributor_type"=>$row['DistributorType'],
                        "product_brand"=>$row['ProductBrand'],
                        "distributor_type_id"=>$row['DistributorTypeId'],
                        "status"=>$status,
                        "updated_at"=>Carbon::now()
                    ]);

                } else {

                    $AddDealerInfo = DealerInformation::create([
                        "dealer_id"=>$row['Id'],
                        "dealer_code"=> $row['DigitechCode'],
                        "alternate_code"=>  $row['ImportCode'],
                        "dealer_name"=> $row['DistributorNameCellCom'],
                        "dealer_address"=> $row['Address'],
                        "zone"=> $row['Zone'],
                        "dealer_phone_number"=> $row['MobileNo'],
                        "zone_id"=>$row['ZoneId'],
                        "upazila_id"=>$row['UpazilaId'],
                        "district_id"=>$row['DistrictId'],
                        "division_id"=>$row['DivisionId'],
                        "upazila_name"=>$row['UpazilaName'],
                        "district_name"=>$row['DistrictName'],
                        "division_name"=>$row['DivisionName'],
                        "distributor_type"=>$row['DistributorType'],
                        "product_brand"=>$row['ProductBrand'],
                        "distributor_type_id"=>$row['DistributorTypeId'],
                        "status"=>$status,
                        "created_at"=>Carbon::now(),
                        "updated_at"=>Carbon::now()
                    ]);

                }

            }
            Log::info('Create Dealer Form Api');
            return response()->json('success');
        } else {
            Log::error('Dealer Create Failed Form Api');
            return response()->json('error');
        }
    }

    public function AddToDealerFormApi_old_28_10_2021()
    {
        $getCurlResponse    = getData(RequestApiUrl("DealerAll"),"GET");
        $responseData       = json_decode($getCurlResponse['response_data'],true);

        if(isset($getCurlResponse) && $getCurlResponse['status'] == 200) {
            $totalInsertRow =0;
            foreach ($responseData as $row) {
                $totalInsertRow +=1;
                $DealerID = $row['Id'];
                $CheckDealer = DealerInformation::where('dealer_id',$DealerID)->first();
                if($CheckDealer) {
                    $UpdateDealerInfo = DealerInformation::where('dealer_id',$DealerID)
                    ->update([
                        "dealer_code"=> $row['DigitechCode'],
                        "alternate_code"=>  $row['ImportCode'],
                        "dealer_name"=> $row['DistributorNameCellCom'],
                        "dealer_address"=> $row['Address'],
                        "zone"=> $row['Zone'],
                        "dealer_phone_number"=> $row['MobileNo']
                    ]);    

                } else {

                    $AddDealerInfo = DealerInformation::create([
                        "dealer_id"=>$DealerID,
                        "dealer_code"=> $row['DigitechCode'],
                        "alternate_code"=>  $row['ImportCode'],
                        "dealer_name"=> $row['DistributorNameCellCom'],
                        "dealer_address"=> $row['Address'],
                        "zone"=> $row['Zone'],
                        "dealer_phone_number"=> $row['MobileNo']
                    ]);

                }

            }
            Log::info('Create Dealer Form Api');
            return response()->json('success');
        } else {
            Log::error('Dealer Create Failed Form Api');
            return response()->json('error');
        }
    }

    public function destroy($id)
    {
        //
    }

}
