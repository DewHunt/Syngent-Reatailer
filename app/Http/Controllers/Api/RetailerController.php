<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DealerInformation;
use App\Models\Retailer;
use App\Models\Zone;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use DB;
use Validator;
use DataTables;
use Response;

class RetailerController extends Controller
{    
    public function index(Request $request) {
        $CategoryList = DB::table('bp_retailer_categories')->where('status','=',1)->get();
        $RetailerList = "";
        if ($request->ajax()) {
            $sort_by = $request->get('sortby');
            $sort_type = $request->get('sorttype');
            $query = $request->get('query');
            $query = str_replace(" ", "%", $query);
            $searchVal = str_replace(" ", "%", $query);

             $RetailerList = DB::table('view_retailer_list')
                ->select('id','retailer_name','retailder_address','owner_name','phone_number','dealer_name','distributor_code','distributor_code2','shop_start_time','start_time_ampm','shop_end_time','end_time_ampm','status')
                ->where(function($sql_query) use($searchVal) {
                    if(!empty($searchVal) && $searchVal !=null) {
                        $sql_query->where('retailer_name','like', '%'.$searchVal.'%')
                        ->orWhere('owner_name', 'like', '%'.$searchVal.'%')
                        ->orWhere('phone_number', 'like', '%'.$searchVal.'%')
                        ->orWhere('retailder_address', 'like', '%'.$searchVal.'%')
                        ->orWhere('dealer_name', 'like', '%'.$searchVal.'%')
                        ->orWhere('distributor_code', 'like', '%'.$searchVal.'%');
                    }
                 })
                //->orderBy($sort_by, $sort_type)
                ->orderBy('owner_name','ASC')
                ->paginate(100);            
            return view('admin.retailer.result_data', compact('RetailerList','CategoryList'))->render();
        } else {
            $RetailerList = DB::table('view_retailer_list')
                ->select('id','retailer_name','retailder_address','owner_name','phone_number','dealer_name','distributor_code','distributor_code2','shop_start_time','start_time_ampm','shop_end_time','end_time_ampm','status')
                ->orderBy('owner_name','ASC')
                ->paginate(100);
        }

        if (isset($RetailerList) && $RetailerList->isNotEmpty()) {
            Log::info('Load Retailer List');
        } else {
            Log::warning('Retailer List Not Found');
        }
        return view('admin.retailer.list',compact('RetailerList','CategoryList'));
    }
    
    public function create() {
    }

    public function store(Request $request) {        
        $rules = [
            'retailer_name'=>'required',
			'owner_name'=>'required',
			'phone_number'=>'required',
            'retailder_address'=>'required'
        ];
        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            Log::error('Retailer Validation Failed');
            return Response::json(['errors' => $validator->errors()]);
        }

        $retailer_phone = $request->input('phone_number');
        $CheckRetailer = retailer::where('phone_number',$retailer_phone)->first();
        

        $paymentType = $request->input('payment_type') ? $request->input('payment_type') : 1;
        $paymentNumber = $request->input('payment_number') ? $request->input('payment_number'):$request->input('api_payment_number');
        $paymentAgent = "";
        if ($request->input('agent_name') != null) {
            $paymentAgent  = $request->input('agent_name');
        } else {
            $paymentAgent  = $request->input('api_payment_type');
        }

        $paymentBankName = "";
        if ($request->input('bank_name') != null) {
            $paymentBankName  = $request->input('bank_name');
        } else {
            $paymentBankName  = "";
        }

        if ($CheckRetailer) {
            $CheckUserTable = User::where('retailer_id',$CheckRetailer['id'])->first();
            $UpdateRetailer = retailer::where('id',$CheckRetailer['id'])->update([
                "category_id"=>$request->input('category_id'),
            	"retailer_id"=>$request->input('retailer_id'),
            	"retailer_name"=>$request->input('retailer_name'),
            	"retailder_address"=>$request->input('retailder_address'),
				"owner_name"=>$request->input('owner_name'),
				"phone_number"=>$request->input('phone_number'),
                "bank_name"=>$paymentBankName,
                "agent_name"=>$paymentAgent,
				"payment_type"=>$paymentType,
				"payment_number"=>$paymentNumber,
				"zone_id"=>$request->input('zone_id'),
				"division_id"=>$request->input('division_id'),
				"division_name"=>$request->input('division_name'),
				"distric_id"=>$request->input('distric_id'),
				"distric_name"=>$request->input('distric_name'),
				"police_station"=>$request->input('police_station'),
				"thana_id"=>$request->input('thana_id'),
				"distributor_code"=>$request->input('distributor_code'),
				"distributor_code2"=>$request->input('distributor_code2'),
				"status"=>$request->input('status')
            ]);

            if ($UpdateRetailer) {
                if (isset($CheckUserTable) && !empty($CheckUserTable)) {
                    $UpdateUser = User::where('retailer_id',$CheckRetailer['id'])->update([
                        "name"=>$request->input('retailer_name'),
                    ]);
                }
            }
            Log::info('Existing Retailer Updated');
            return response()->json('success');
        } else {
            $AddRetailer = retailer::create([
                "category_id"=>$request->input('category_id'),
                "retailer_id"=>$request->input('retailer_id'),
            	"retailer_name"=>$request->input('retailer_name'),
            	"retailder_address"=>$request->input('retailder_address'),
				"owner_name"=>$request->input('owner_name'),
				"phone_number"=>$request->input('phone_number'),
                "bank_name"=>$paymentBankName,
                "agent_name"=>$paymentAgent,
				"payment_type"=>$paymentType,
				"payment_number"=>$paymentNumber,
				"zone_id"=>$request->input('zone_id'),
				"division_id"=>$request->input('division_id'),
				"division_name"=>$request->input('division_name'),
				"distric_id"=>$request->input('distric_id'),
				"distric_name"=>$request->input('distric_name'),
				"police_station"=>$request->input('police_station'),
				"thana_id"=>$request->input('thana_id'),
				"distributor_code"=>$request->input('distributor_code'),
				"distributor_code2"=>$request->input('distributor_code2'),
				"status"=>$request->input('status')
            ]);
            Log::info('Create Retailer');
            return response()->json('success');
        }
    }

    public function show($id) {
        $RetailerInfo = DB::table('view_retailer_list')->where('id',$id)->first();
        $html = view('admin.retailer.view_retailer')->with(compact('RetailerInfo'))->render();
        return response()->json($html);
        // return response()->json(['status'=>'info','data'=>$dealerInfo]);
    }

    public function edit($id) {
        if (isset($id) && $id > 0) {
            $CategoryList = DB::table('bp_retailer_categories')->where('status','=',1)->get();
            $RetailerInfo = DB::table('view_retailer_list')->where('id',$id)->first();

            if ($RetailerInfo) {
                $html = view('admin.retailer.edit_form')->with(compact('CategoryList','RetailerInfo'))->render();
                Log::info('Get Retailer By Id');
                return response()->json($html);
            } else {
                Log::warning('Retailer Not Found By Id');
                return response()->json('error');
            }
        } else {
            Log::warning('Retailer Not Found By Id');
            return response()->json('error'); 
        }
    }

    public function update(Request $request) {
        // dd($request->all());
        $rules = [
            'retailer_name'=>'required',
            'owner_name'=>'required',
            'phone_number'=>'required', 
            'retailder_address'=>'required'
        ];
        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            Log::error('Update Retailer Validation Failed');
            return Response::json(['errors' => $validator->errors()]);
        }

        $RetailerUpdateID = $request->input('update_id');
        $retailer_id = $request->input('retailer_id');
        $retailer_phone = $request->input('phone_number');
        $category_id = $request->input('category_id');
        $CheckRetailer = retailer::where('phone_number',$retailer_phone)->where('id','<>',$RetailerUpdateID)->first();
        $CheckUserTable = User::where('retailer_id',$RetailerUpdateID)->first();

        if (!$CheckRetailer) {
            $UpdateRetailer = retailer::where('id',$RetailerUpdateID)->update([
                "category_id"=>$category_id,
                "retailer_id"=>$request->input('retailer_id'),
                "retailer_name"=>$request->input('retailer_name'),
                "retailder_address"=>$request->input('retailder_address'),
                "owner_name"=>$request->input('owner_name'),
                "phone_number"=>$request->input('phone_number'),
                "bank_name"=>$request->input('bank_name'),
                "agent_name"=>$request->input('agent_name'),
                "payment_type"=>$request->input('payment_type'),
                "payment_number"=>$request->input('payment_number'),
                "zone_id"=>$request->input('zone_id'),
                "division_id"=>$request->input('division_id'),
                "division_name"=>$request->input('division_name'),
                "distric_id"=>$request->input('distric_id'),
                "distric_name"=>$request->input('distric_name'),
                "police_station"=>$request->input('police_station'),
                "thana_id"=>$request->input('thana_id'),
                "distributor_code"=>$request->input('distributor_code'),
                "distributor_code2"=>$request->input('distributor_code2'),
                "status"=>$request->input('status')
            ]);

            if ($UpdateRetailer) {
                if (isset($CheckUserTable) && !empty($CheckUserTable)) {
                    $UpdateUser = User::where('retailer_id',$RetailerUpdateID)->update([
                        "name"=>$request->input('retailer_name'),
                    ]);
                }
            }
            Log::info('Existing Retailer Updated');
            return response()->json('success');
        } else {
            Log::error('Existing Retailer Updation Failed');
            return response()->json('error');
        }
    }

    public function CheckRetailer($mobile=null) {
        $getCurlResponse = "";

        if (isset($mobile) && $mobile !=0) {
            $getCurlResponse = getData(sprintf(RequestApiUrl("RetailerPhone"),$mobile),"GET");
        }

        $responseData = json_decode($getCurlResponse['response_data'],true);

        if (isset($getCurlResponse) && $getCurlResponse['status'] == 200) {
            return response()->json($responseData);
        } else {
            return response()->json($getCurlResponse['response_data']);
        }        
    }

    public function ChangeStatus($id) {
        if (isset($id) && $id > 0) {
            $StatusInfo = retailer::find($id);
            $old_status = $StatusInfo->status;
            $UpdateStatus = $old_status == 1 ? 0 : 1;
            $UpdateRetailerStatus = retailer::where('id',$id)->update([
                "status"=> $UpdateStatus ? $UpdateStatus:0
            ]);

            if ($UpdateRetailerStatus) {
                Log::info('Existing Retailer Status Changed');
                return response()->json(['success'=>'Status change successfully.']);
            } else {
                Log::error('Existing Retailer Status Changed Failed');
                return response()->json(['error'=>'Status Update Failed.Please Try Again.']);
            }
        } else {
            Log::error('Existing Retailer Id Not Found');
            return response()->json('error');
        }
    }

    public function destroy(Zone $zone) {
        //
    }

    public function retailerShopTimeEdit($retailId) {
        if (isset($retailId) && $retailId > 0) {
            $response = Retailer::where('id','=',$retailId)
                ->select('id','shop_start_time','start_time_ampm','shop_end_time','end_time_ampm')
                ->first();
            $html = view('admin.retailer.retailer_shop_time_form')->with(compact('response'))->render();
            return response()->json($html);
        }
        Log::error('Retailer Shop Time Edit Failed');
        return response()->json('error');    
    }

    public function saveShopWorkingTime(Request $request) {
        $retailId = $request->input('retailer_id');
        $startTime = $request->input('start_time');
        $endTime = $request->input('end_time');
        $startTimeAmPm = $request->input('start_time_ampm');
        $endTimeAmPm = $request->input('end_time_ampm');

        if (isset($retailId) && $retailId > 0) {
            $success = Retailer::where('id','=',$retailId)->update([
                "shop_start_time"=>$startTime,
                "start_time_ampm"=>$startTimeAmPm,
                "shop_end_time"=>$endTime,
                "end_time_ampm"=>$endTimeAmPm,
            ]);

            if ($success) {
                Log::info('Retailer Shop Time Set Successfully');
                return response()->json('success');
            }
            Log::error('Retailer Shop Time Set Faield');
            return response()->json('error');
        } else {
            Log::error('Retailer Shop Time Set Faield');
            return response()->json('error');
        }
    }
    
    public function passwordUpdate($id) {
        $user = Auth::user();
		$authUserName = $user->name;
        $dataFormat = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz';
        $uniquePassword =  substr(str_shuffle($dataFormat), 0, 8);
        //echo $uniquePassword = rand(111111,999999);
        $retailerId = $id;
        $getInfo = Retailer::select("retailer_name","phone_number")->where('id','=',$retailerId)->first();
        $retailerName = $getInfo['retailer_name'];
        $retailerPhone = $getInfo['phone_number'];
        $retailerEmail = str_replace(' ', '',strtolower($retailerName.'@example.com'));
        $retailerPassword = strtolower($uniquePassword);        
        // $messageBody = "Dear,".$retailerName."Your Login Credential Goes To Here.User ID=".$retailerId." Password=".$retailerPassword;        
        $messageBody = "HI, ".$retailerName.". Your Password Has Reset.User Name=".$retailerPhone." Password=".$retailerPassword." Thanks, Syngenta";        
        $requestData = array('mobileNumber' => $retailerPhone,'message' =>$messageBody,);
        $postRequestData = json_encode($requestData);
        $checkRetailerbyUserTable = User::where('retailer_id','=',$retailerId)->first();
        $status = 0;
        if ($checkRetailerbyUserTable) {
            $passwordUpdateStatus = User::where('retailer_id','=',$retailerId)->update([
                'name'=>$retailerName,
                'email'=>$retailerEmail,
                'password' => Hash::make($retailerPassword),
                'status'=>1,
                'author'=>$authUserName,
                "updated_at"=>Carbon::now()
            ]);
            $status = 1;
        } else {
            $passwordUpdateStatus = User::create([
                'retailer_id'=>$retailerId,
                'name'=>$retailerName,
                'email'=>$retailerEmail,
                'password' => Hash::make($retailerPassword),
                'status'=>1,
                'author'=>$authUserName,
                "updated_at"=>Carbon::now()
            ]);
            $status = 1;
        }
        
        if ($status == 1) {
            // $cURLConnection = curl_init('#');
            //     curl_setopt($cURLConnection, CURLOPT_POSTFIELDS, $postRequestData);
            //     curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
            //     curl_setopt($cURLConnection, CURLOPT_HTTPHEADER, array(
            //     // Set Here Your Requesred Headers
            //     'Content-Type: application/json',
            //     'AppApiKey:18197:mostafiz',
            // ));
            // $apiResponse = curl_exec($cURLConnection);
            // curl_close($cURLConnection);
            // // $apiResponse - available data from the API request
            // $jsonArrayResponse = json_decode($apiResponse);

            // if ($jsonArrayResponse) {
            //     Log::info('Retailer Password Set Successfully-(Retailer ID-'.$retailerId.')');
            //     return response()->json($jsonArrayResponse);
            // }
        }
        Log::info('Retailer Password Set Failed-(Retailer ID-'.$retailerId.')');
        return response()->json('error');
    }
    
    public function getRetailerSearch(Request $request) {
        $search = $request->search;
        $retailList = "";
        if($search == '') {
            $retailList = Retailer::orderby('retailer_name','asc')
                ->select('id','retailer_name','phone_number','retailder_address')
                ->get();
        } else  {
            $retailList = Retailer::orderby('retailer_name','asc')
                ->select('id','retailer_name','phone_number','retailder_address')
                ->where('retailer_name', 'like', '%' .$search . '%')
                ->orWhere('phone_number','like', '%' .$search . '%')
                ->get();
        }

        $dataArray = array();
        foreach($retailList as $row) {
            $dataArray[] = '<option value="'.$row->id.'">'.$row->retailer_name.'-'.$row->phone_number.'-'.$row->retailder_address.'</option>';
        }
        return response()->json($dataArray);
    }
}