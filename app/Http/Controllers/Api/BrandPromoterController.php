<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\BrandPromoter;
use App\Models\Retailer;
use App\Models\Products;
use App\Models\User;
use Carbon\Carbon;
use DB;
use Validator;
use Response;
use Session;

class BrandPromoterController extends Controller
{
    
    public function index(Request $request)
    {
        $CategoryList      = DB::table('bp_retailer_categories')
        ->where('status','=',1)
        ->orderBy('sorting_number','ASC')
        ->get();
        $BrandPromoterList = DB::table('view_brand_promoter_list')
        ->orderBy('bp_name','ASC')
        ->get();

        if(isset($BrandPromoterList) && $BrandPromoterList->isNotEmpty()) {
            Log::info('Load Brand Promoter List');
        } else {
            Log::warning('Brand Promoter List Not Found');
        }
        return view('admin.bpromoter.list',compact('BrandPromoterList','CategoryList'));
    }


    public function create()
    {
        //
    }

    
    public function store(Request $request)
    {        
        $rules = [
            'bp_name'=>'required',
            'bp_phone'=>'required|digits:11|numeric|unique:brand_promoters',
            'retailer_name'=>'required',
            //'owner_name'=>'required',
            'retailer_phone_number'=>'required|digits:11|numeric',
            'retailder_address'=>'required',
            'distributor_code'=>'required',
            //'distributor_code2'=>'required',
            'distributor_name'=>'required',
            'distributor_zone'=>'required',
            //'payment_type'=>'required',
            //'payment_number'=>'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        
        if($validator->fails()) {
            Log::error('Brand Promoter Validation Failed');
            return Response::json(['errors' => $validator->errors()]);
        }

        $bp_id              = $request->input('bp_id');
        $bp_name            = $request->input('bp_name');
        $bp_phone           = $request->input('bp_phone');
        $categoryId         = $request->input('bp_category');
        $CheckPromoter      = BrandPromoter::where('bp_phone',$bp_phone)->first();
        $bpFeeds            = "";
    
        if($CheckPromoter)
        {
            $CheckUserTable = User::where('bp_id',$CheckPromoter['id'])->first();
            $UpdatePromoter = BrandPromoter::where('id',$CheckPromoter['id'])
            ->update([
                "category_id"=>$categoryId ? $categoryId:1,
                "bp_id"=>$request->input('bp_id'),
                "retailer_id"=>$request->input('retailer_id'),
                "bp_name"=>$request->input('bp_name'),
                "bp_phone"=>$request->input('bp_phone'),
                "retailer_name"=>$request->input('retailer_name'),
                "owner_name"=>$request->input('owner_name'),
                "police_station"=>$request->input('police_station'),
                "retailer_phone_number"=>$request->input('retailer_phone_number'),
                "retailder_address"=>$request->input('retailder_address'),
                "distributor_code"=>$request->input('distributor_code'),
                "distributor_code2"=>$request->input('distributor_code2'),
                "distributor_name"=>$request->input('distributor_name'),
                "distributor_zone"=>$request->input('distributor_zone'),
                "division_name"=>$request->input('division_name'),
                "distric_name"=>$request->input('distric_name'),
                "bank_name"=>$request->input('bank_name'),
                "agent_name"=>$request->input('agent_name'),
                "payment_type"=>$request->input('payment_type'),
                "payment_number"=>$request->input('payment_number'),
                "status"=>$request->input('status')
            ]);

            if($UpdatePromoter) {
                if(isset($CheckUserTable) && !empty($CheckUserTable)){
                    $UpdateUser = User::where('bp_id',$CheckPromoter['id'])
                    ->update([
                        "name"=>$request->input('bp_name'),
                    ]);
                }
            }
            Log::info('Existing Brand Promoter Updated SuccessFully');
            return response()->json('success');
        } 
        else 
        {
            $AddPromoter = BrandPromoter::create([
                "category_id"=>$categoryId ? $categoryId:1,
                "bp_id"=>$request->input('bp_id'),
                "retailer_id"=>$request->input('retailer_id'),
                "bp_name"=>$request->input('bp_name'),
                "bp_phone"=>$request->input('bp_phone'),
                "retailer_name"=>$request->input('retailer_name'),
                "owner_name"=>$request->input('owner_name'),
                "police_station"=>$request->input('police_station'),
                "retailer_phone_number"=>$request->input('retailer_phone_number'),
                "retailder_address"=>$request->input('retailder_address'),
                "distributor_code"=>$request->input('distributor_code'),
                "distributor_code2"=>$request->input('distributor_code2'),
                "distributor_name"=>$request->input('distributor_name'),
                "distributor_zone"=>$request->input('distributor_zone'),
                "division_name"=>$request->input('division_name'),
                "distric_name"=>$request->input('distric_name'),
                "bank_name"=>$request->input('bank_name'),
                "agent_name"=>$request->input('agent_name'),
                "payment_type"=>$request->input('payment_type'),
                "payment_number"=>$request->input('payment_number'),
                "status"=>$request->input('status'),
                "is_send"=>0,//1=Send Success,0=Send Pending
            ]);
            
            $getInsertBpId = DB::getPdo()->lastInsertId();
            
            if($AddPromoter) 
            {
                $bpFeeds = [
                    "id"=>$getInsertBpId,
                    "category_id"=>$categoryId ? $categoryId:1,
                    "bp_id"=>$request->input('bp_id') ? $request->input('bp_id'):0,
                    "retailer_id"=>$request->input('retailer_id'),
                    "bp_name"=>$request->input('bp_name'),
                    "bp_phone"=>$request->input('bp_phone'),
                    "retailer_name"=>$request->input('retailer_name'),
                    "owner_name"=>$request->input('owner_name'),
                    "police_station"=>$request->input('police_station') ?$request->input('police_station'):'NULL',
                    "retailer_phone_number"=>$request->input('retailer_phone_number'),
                    "retailder_address"=>$request->input('retailder_address'),
                    "distributor_code"=>$request->input('distributor_code'),
                    "distributor_code2"=>$request->input('distributor_code2'),
                    "distributor_name"=>$request->input('distributor_name'),
                    "distributor_zone"=>$request->input('distributor_zone'),
                    "division_name"=>$request->input('division_name'),
                    "distric_name"=>$request->input('distric_name'),
                    "bank_name"=>$request->input('bank_name') ? $request->input('bank_name'):'NULL',
                    "agent_name"=>$request->input('agent_name') ? $request->input('agent_name'):'NULL',
                    "status"=>$request->input('status'),
                    "monthly_average_sale"=>0,
                    "monthly_average_sale_of_walton"=>0
                ];
                
                $getPostCurlResponse = postBPData(sprintf(RequestApiUrl("addBP")),$bpFeeds);

                if($getPostCurlResponse) 
                {
                    $responseModify = json_decode($getPostCurlResponse);
                    if($responseModify->bp_id > 0) {
                        $UpdatePromoter = BrandPromoter::where('id','=',$getInsertBpId)
                        ->update([
                            "bp_id"=>$responseModify->bp_id,
                            "is_send"=>1
                        ]);
                    }
                }
            }
            
            Log::info('Create Brand Promoter SuccessFully');
            return response()->json('success');
        }
    }

    public function show()
    {
        //
    }

    public function edit($id)
    {
        if(isset($id) && $id>0) {
            $BPromoterInfo = DB::table('view_brand_promoter_list')
            ->where('id',$id)
            ->first();

            if($BPromoterInfo) {
                Log::info('Get Brand Promoter By Id');
                return response()->json($BPromoterInfo); 
            } else {
                Log::warning('Brand Promoter Not Found By Id');
                return response()->json('error');
            }
        } else {
            Log::warning('Invalid Brand Promoter Id');
            return response()->json('error');
        }
    }

    public function update(Request $request)
    {
        $rules = [
            //'bp_id'=>'required',
            //'retailer_id'=>'required',
            'bp_name'=>'required',
            'bp_phone'=>'required|digits:11|numeric',
        ];

        $validator = Validator::make($request->all(), $rules);
        
        if($validator->fails()) {
            Log::error('Brand Promoter Update Validation Failed');
            return Response::json(['errors' => $validator->errors()]);
        }

        $BPromoterUpdateID      = $request->input('update_id');
        $bp_id                  = $request->input('bp_id');
        $retailer_id            = $request->input('retailer_id');
        $bp_name                = $request->input('bp_name');
        $bp_phone               = $request->input('bp_phone');
        $category_id            = $request->input('bp_category');
        $CheckBPromoter         = BrandPromoter::where('bp_phone',$bp_phone)
        ->where('id','<>',$BPromoterUpdateID)
        ->first();
        

        if(!$CheckBPromoter) {
            $CheckUserTable  = User::where('bp_id',$BPromoterUpdateID)->first();
            
            $UpdateBPromoter = BrandPromoter::where('id',$BPromoterUpdateID)
            ->update([
                "category_id"=>$category_id ? $category_id:1,
                "bp_id"=>$bp_id ? $bp_id:0,
                "retailer_id"=>$retailer_id ? $retailer_id:0,
                "bp_name"=>$bp_name,
                "bp_phone"=>$bp_phone,
                "retailer_name"=>$request->input('retailer_name'),
                "owner_name"=>$request->input('owner_name'),
                "police_station"=>$request->input('police_station'),
                "retailer_phone_number"=>$request->input('retailer_phone_number'),
                "retailder_address"=>$request->input('retailder_address'),
                "distributor_code"=>$request->input('distributor_code'),
                "distributor_code2"=>$request->input('distributor_code2'),
                "distributor_name"=>$request->input('distributor_name'),
                "distributor_zone"=>$request->input('distributor_zone'),
                "division_name"=>$request->input('division_name'),
                "distric_name"=>$request->input('distric_name'),
                "bank_name"=>$request->input('bank_name'),
                "agent_name"=>$request->input('agent_name'),
                "payment_type"=>$request->input('payment_type'),
                "payment_number"=>$request->input('payment_number'),
                "status"=>$request->input('status')
            ]);

            if($UpdateBPromoter) {
                if(isset($CheckUserTable) && !empty($CheckUserTable)){
                    $UpdateUser = User::where('bp_id',$BPromoterUpdateID)
                    ->update([
                        "name"=>$request->input('bp_name'),
                    ]);
                }
            }
            Log::info('Existing Brand Promoter Update SuccessFully');
            return response()->json('success');
        } else {
            Log::error('Existing Brand Promoter Updated Failed');
            return response()->json('error');
        }     
    }

    public function CheckBPromoterFromApi($phone)
    {
        $getCurlResponse    = getData(sprintf(RequestApiUrl("BPromoterPhone"),$phone),"GET");
        $responseData       = json_decode($getCurlResponse['response_data'],true);

        if(isset($getCurlResponse) && $getCurlResponse['status'] == 200) {
            return response()->json($responseData);
        } else {
            //return response()->json($getCurlResponse['response_data']);
            return response()->json('error');
        }
    }

    public function ChangeStatus($id) 
    {
        if(isset($id) && $id > 0) {
            $StatusInfo = BrandPromoter::find($id);
            $old_status = $StatusInfo->status;

            $UpdateStatus = $old_status == 1 ? 0 : 1;

            $UpdateBrandPromoterStatus = BrandPromoter::where('id',$id)
            ->update([
                "status"=> $UpdateStatus ? $UpdateStatus:0
            ]);
            
            if($UpdateBrandPromoterStatus)
			{
				DB::table('users')
				->where('bp_id','=',$id)
				->update([
					"status"=> $UpdateStatus ? $UpdateStatus:0
				]);
			}

            if($UpdateBrandPromoterStatus) {
                Log::info('Brand Promoter Status Changed Success');
                return response()->json(['success'=>'Status change success.','status'=>$UpdateStatus]);
            } else {
                Log::error('Brand Promoter Status Changed Failed');
                return response()->json(['error'=>'Status Update Failed.Please Try Again.']);
            }
        } else {
            Log::warning('Invalid Brand Promoter Id');
            return response()->json('error');
        }
    }

    public function AddBPromoterFromApi()
    {
        $getCurlResponse    = getData(RequestApiUrl("GetBrandPromoter"),"GET");
        $responseData       = json_decode($getCurlResponse['response_data'],true);

        $insertStatus    = 0;

        if(isset($getCurlResponse) && $getCurlResponse['status'] == 200) {
            foreach ($responseData as $row) 
            {
                $bpId                           = $row['Id'];
                $bpName                         = $row['BPName'];
                $bpPhone                        = $row['BPPhoneNumber'];
                $bpDistrict                     = $row['District'];
                $bpCategoryId                   = 1;

                $retailerID                     = $row['RetailerID'];
                $retailerName                   = $row['RetailerName'];
                $retailerAddress                = $row['RetailerAddress'];
                $retailerZone                   = $row['RetailerZone'];
                $retailerPhoneNumber            = $row['RetailerPhoneNumber'];
                $retailerZoneId                 = $row['RetailerZoneId'];

                $distributorName                = $row['DistributorName'];
                $distributorCode                = $row['DistributorCode'];
                $alternateCode                  = $row['AlternateDistributorCode'];

                $monthlyAverageSale             = $row['MonthlyAverageSale'];
                $monthlyAverageSaleOfWalton     = $row['MonthlyAverageSaleOfWalton'];

                $status                         = $row['Active'] == true ? 1 :0;
                
                ///////////////////////////////////////
				if($retailerID > 0) {
					$checkStatus = retailer::where('retailer_id','=',$retailerID)->first();
					if(!$checkStatus) {
						$getCurlResponse    = getData(sprintf(RequestApiUrl("RetailerId"),$retailerID),"GET");
						$responseData       = json_decode($getCurlResponse['response_data'],true);
						
						if($responseData) {
							$AddRetailerInfo = retailer::create([
								"retailer_id"=>$retailerID,
								"retailer_name"=>$responseData['RetailerName'],
								"retailder_address"=>$responseData['RetailerAddress'],
								"owner_name"=>$responseData['OwnerName'],
								"phone_number"=>$responseData['PhoneNumber'],
								"payment_type"=>$responseData['PaymentNumberType'],
								"payment_number"=>$responseData['PaymentNumber'],
								"zone_id"=>$responseData['ZoneId'],
								"division_id"=>$responseData['DivisionId'],
								"division_name"=>$responseData['Division'],
								"distric_id"=>$responseData['DistrictId'],
								"distric_name"=>$responseData['District'],
								"police_station"=>$responseData['PoliceStation'],
								"thana_id"=>$responseData['ThanaId'],
								"distributor_code"=>$responseData['DistributorCode'],
								"distributor_code2"=>$responseData['DistributorCode2'],
								"status"=>$responseData['IsActive'],
							]);
						}
					}
				}
				//////////////////////////////////////
                
                $CheckPromoter = BrandPromoter::where('bp_phone','=',$bpPhone)->first();
                if($CheckPromoter)
                {
                    $UpdatePromoter = BrandPromoter::where('id',$CheckPromoter['id'])
                    ->update([
                        "category_id"=>$bpCategoryId ? $bpCategoryId:1,
                        "bp_id"=>$bpId,
                        "retailer_id"=>$retailerID,
                        "bp_name"=>$bpName,
                        "bp_phone"=>$bpPhone,
                        "retailer_name"=>$retailerName,
                        "retailer_phone_number"=>$retailerPhoneNumber,
                        "retailder_address"=>$retailerAddress,
                        "distributor_code"=>$distributorCode,
                        "distributor_code2"=>$alternateCode,
                        "distributor_name"=>$distributorName,
                        "distributor_zone"=>$retailerZone,
                        "division_name"=>$bpDistrict,
                        "distric_name"=>$bpDistrict,
                        "status"=>$status,
                        "monthly_average_sale"=>$monthlyAverageSale,
                        "monthly_average_sale_of_walton"=>$monthlyAverageSaleOfWalton,
                    ]);
                    
                    
                    if($UpdatePromoter) {
                        $CheckUserTable = User::where('bp_id',$CheckPromoter['id'])->first();
                        if(isset($CheckUserTable) && !empty($CheckUserTable)) {
                            $UpdateUser = User::where('bp_id',$CheckPromoter['id'])
                            ->update([
                                "name"=>$bpName,
                                //"email"=>$bpPhone.'@waltonbd.com'
                            ]);
                        }
                    }
                    $insertStatus = 1;
                } 
                else 
                {
                    $AddPromoter = BrandPromoter::create([
                        "category_id"=>$bpCategoryId ? $bpCategoryId:1,
                        "bp_id"=>$bpId,
                        "retailer_id"=>$retailerID,
                        "bp_name"=>$bpName,
                        "bp_phone"=>$bpPhone,
                        "retailer_name"=>$retailerName,
                        "retailer_phone_number"=>$retailerPhoneNumber,
                        "retailder_address"=>$retailerAddress,
                        "distributor_code"=>$distributorCode,
                        "distributor_code2"=>$alternateCode,
                        "distributor_name"=>$distributorName,
                        "distributor_zone"=>$retailerZone,
                        "division_name"=>$bpDistrict,
                        "distric_name"=>$bpDistrict,
                        "status"=>$status,
                        "monthly_average_sale"=>$monthlyAverageSale,
                        "monthly_average_sale_of_walton"=>$monthlyAverageSaleOfWalton,
                    ]);
                    $insertStatus = 1;
                }
            }
        } 
        else 
        {
            Log::error('Brand Promoter Add Failed From Api');
            return response()->json('error');
        }

        if($insertStatus == 1) {
            Log::info('Brand Promoter Add SuccessFully From Api');
            return response()->json('success');
        }
        else {
            Log::error('Brand Promoter Add Failed From Api');
            return response()->json('error');
        }
    }

    public function destroy($id)
    {
        //
    }

    public function focus_model_to_bp(Request $request)
    {
        if($request->ajax()) {
            $modelId            = $request->get('modelId');
            $catId              = ($request->get('catId')) ? $request->get('catId'):1;

            Session::put('catId', $catId);


            $searchPhoneType    = 'smart';
            $productModelLists  = "";

            if($modelId > 0) {
                $catProductId   = DB::table('bp_model_stocks')->select('product_master_id')->where('bp_category_id','=',$catId)->get();

                $getProductIdLists = [];
                foreach($catProductId as $row)
                {
                    $getProductIdLists[] = $row->product_master_id;
                }

                $productModelLists  = Products::select('product_master_id','product_id','product_model','product_type','category2')
                ->where('product_master_id','=',$modelId)
                ->orWhereIn('product_master_id',$getProductIdLists)
                ->get();
            }
            else
            {
                $productModelLists  = Products::select('product_master_id','product_id','product_model','product_type','category2')
                ->where('status',1)
                ->where('category2','like','%'.$searchPhoneType.'%')
                ->orWhereNull('category2')
                ->whereNotNull('product_id')
                ->groupBy('product_model')
                ->get();
            }
            

            $catId          = Session::get('catId') ? Session::get('catId') : 1;
            $getModelList   = View('admin.bpromoter.product_model_list',compact('productModelLists'))->render();

            return response()->json(['modelLists'=>$getModelList,'catId'=>$catId]);
        }
        else
        {
            $searchPhoneType    = 'smart';
            $productModelLists  = Products::select('product_master_id','product_id','product_model','product_type','category2')
            ->where('status',1)
            ->where('category2','like','%'.$searchPhoneType.'%')
            ->orWhereNull('category2')
            ->whereNotNull('product_id')
            ->groupBy('product_model')
            ->get();

            $catId = 1;
            return view('admin.bpromoter.focus_model_to_bp',compact('productModelLists','catId'));
        }
    }

    public function focus_model_to_bp_by_cat($catId)
    {
        //Session::forget('catId');
        //$catId = 2;
        Session::put('catId', $catId);
        return response()->json(['catId'=>$catId]);
    }

    public function focus_model_to_bp_by_modelId($modelId) 
    {
        $searchPhoneType = 'smart';
        $productModelLists = Products::select('product_master_id','product_id','product_model','product_type','category2')
        ->where('product_master_id','=',$modelId)
        ->get();

        $catId = Session::get('catId') ? Session::get('catId') : 1;
        $getModelList = View('admin.bpromoter.product_model_list',compact('productModelLists'))->render();
        return response()->json(['modelLists'=>$getModelList,'catId'=>$catId]);
    }

    public function focus_model_to_bp_save(Request $request)
    {
        Session::forget('catId');

        $category_id         = $request->input('category_id');
        $select_model        = $request->input('select_model');

        $saveProductIdLists  = DB::table('bp_model_stocks')
        ->select('product_master_id')
        ->where('bp_category_id',$category_id)
        ->get();

        $productIdLists = [];
        if($saveProductIdLists->isNotEmpty()){
            foreach($saveProductIdLists as $val) {
                $productIdLists[] = $val->product_master_id;
            }
        }

        $getDiffVal = array_diff($productIdLists, $select_model);

        $status = 0;
        if(is_array($select_model) && $category_id > 0) 
        {
            foreach($select_model as $key=>$model) 
            {
                $checkStatus = DB::table('bp_model_stocks')
                ->where('bp_category_id',$category_id)
                ->where('product_master_id',$request->input('product_master_id')[$model])
                ->where('product_id',$request->input('product_id')[$model])
                ->where('model_name','like','%'.$request->input('model_name')[$model].'%')
                ->first();

                if($checkStatus) 
                {
                    $modelStockSaveByBp = DB::table('bp_model_stocks')
                    ->where('bp_category_id',$category_id)
                    ->where('product_master_id',$request->input('product_master_id')[$model])
                    ->where('model_name','like','%'.$request->input('model_name')[$model].'%')
                    ->update([
                        "bp_category_id"=>$category_id,
                        "product_master_id"=> $request->input('product_master_id')[$model],
                        "product_id"=> $request->input('product_id')[$model],
                        "model_name"=> $request->input('model_name')[$model],
                        "green"=> $request->input('green')[$model],
                        "yellow"=> $request->input('yellow')[$model],
                        "red"=> $request->input('red')[$model],
                        "updated_at"=>Carbon::now()
                    ]);

                    if($modelStockSaveByBp) {
                        $status = 1;
                    }
                } 
                else 
                {
                    $modelStockSaveByBp = DB::table('bp_model_stocks')
                    ->insert([
                        "bp_category_id"=>$category_id,
                        "product_master_id"=> $request->input('product_master_id')[$model],
                        "product_id"=> $request->input('product_id')[$model],
                        "model_name"=> $request->input('model_name')[$model],
                        "green"=> $request->input('green')[$model],
                        "yellow"=> $request->input('yellow')[$model],
                        "red"=> $request->input('red')[$model],
                        "created_at"=>Carbon::now(),
                        "updated_at"=>Carbon::now()
                    ]);

                    if($modelStockSaveByBp) {
                        $status = 1;
                    }
                }
            }
        }

        if(!empty($getDiffVal)) {
            foreach($getDiffVal as $diffId) {
                $modelRemoveByBp = DB::table('bp_model_stocks')
                ->where('bp_category_id',$category_id)
                ->where('product_master_id',$diffId)
                ->delete();
                if($modelRemoveByBp) {
                    $status = 1;
                }
            }
        }

        if($status == 1) {
            Session::put('catId', $category_id);
            return redirect()->back()->with('success','Stock Assigned Successfully');
        }
        return redirect()->back()->with('error','Stock Assigned Faield');
    }
    
    public function passwordUpdate_1($id)
    {
        $dataFormat     = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz';
        $uniquePassword =  substr(str_shuffle($dataFormat), 0, 6);
        //echo $uniquePassword = rand(111111,999999);
        $bpId       = $id;
        $bpName     = BrandPromoter::where('id','=',$bpId)->value('bp_name');
        $bpEmail    = strtolower($bpName.'@example.com');
        $bpPassword = strtolower($uniquePassword);

        $checkBpbyUserTable = User::where('bp_id','=',$bpId)->first();

        if($checkBpbyUserTable) 
        {
            $passwordUpdateStatus = User::where('bp_id','=',$bpId)
            ->update([
                'name'=>$bpName,
                'email'=>str_replace(' ', '', $bpEmail),
                'password' => Hash::make($bpPassword),
                'status'=>1,
                "updated_at"=>Carbon::now()
            ]);

            return response()->json('update');
        }
        else
        {
            $passwordUpdateStatus = User::create([
                'bp_id'=>$bpId,
                'name'=>$bpName,
                'email'=>str_replace(' ', '', $bpEmail),
                'password' => Hash::make($bpPassword),
                'status'=>1,
                "updated_at"=>Carbon::now()
            ]);
            return response()->json('success');
        }
        return Response::json('error');
    }
    
    public function passwordUpdate($id)
    {
        $user           = Auth::user();
		$authUserName   = $user->name;
		
        $dataFormat     = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz';
        $uniquePassword =  substr(str_shuffle($dataFormat), 0, 8);
        //echo $uniquePassword = rand(111111,999999);
        $bpId       = $id;
        $getbpInfo  = BrandPromoter::select("bp_name","bp_phone")
        ->where('id','=',$bpId)
        ->first();
        
        $bpName     = $getbpInfo['bp_name'];
        //$bpName     = 'bpmail_'.$bpId;
        $bpPhone    = $getbpInfo['bp_phone'];
        $bpEmail    = str_replace(' ', '',strtolower($bpName.'_'.$bpId.'@waltonbd.com'));
        $bpPassword = strtolower($uniquePassword);
        $messageBody = "HI, ".$getbpInfo['bp_name'].". Your Password Has Reset.User Name=".$bpPhone." Password=".$bpPassword." Thanks, Syngenta";
        $requestData = array(
            'mobileNumber' => $bpPhone,
            'message' =>$messageBody,
        );
        $postRequestData = json_encode($requestData);

        $checkBpbyUserTable = User::where('bp_id','=',$bpId)->first();
        $status = 0;

        if($checkBpbyUserTable) 
        {
            $passwordUpdateStatus = User::where('bp_id','=',$bpId)
            ->update([
                //'name'=>$bpName,
                //'email'=>$bpEmail,
                'password' => Hash::make($bpPassword),
                'status'=>1,
                'author'=>$authUserName,
                "updated_at"=>Carbon::now()
            ]);
            $status = 1;
        }
        else
        {
            $passwordUpdateStatus = User::create([
                'bp_id'=>$bpId,
                'name'=>$bpName,
                'email'=>$bpEmail,
                'password' => Hash::make($bpPassword),
                'status'=>1,
                'author'=>$authUserName,
                "updated_at"=>Carbon::now()
            ]);
            $status = 1;
        }
    
        if($status == 1) {
            $cURLConnection = curl_init('#');
                curl_setopt($cURLConnection, CURLOPT_POSTFIELDS, $postRequestData);
                curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($cURLConnection, CURLOPT_HTTPHEADER, array(
                // Set Here Your Requesred Headers
                'Content-Type: application/json',
                'AppApiKey:18197:mostafiz',
            ));
            $apiResponse = curl_exec($cURLConnection);
            curl_close($cURLConnection);

            // $apiResponse - available data from the API request
            $jsonArrayResponse = json_decode($apiResponse);

            if($jsonArrayResponse)
            {
                //return response()->json($jsonArrayResponse);
                Log::info('Brand Promoter Password Set Successfully-(BP ID-'.$bpId.')');
                return response()->json('success');
            }
        }
        Log::error('Brand Promoter Password Set Failed-(BP ID-'.$bpId.')');
        return response()->json('error');
    }
    
    public function bpLeaveManagement(Request $request)
    {
        Session::forget('leaveBPId');
        Session::forget('leaveSdate');
        Session::forget('leaveEdate');

        //$month_Sdate       =  date('Y-m-01');
        $month_Sdate       =  date('Y-m-d');
        $month_Edate       =  date('Y-m-t');

        if($request->ajax()) 
        {
            $sort_by      = $request->get('sortby');
            $sort_type    = $request->get('sorttype');
            $query        = $request->get('query');
            $query        = str_replace(" ", "%", $query);
            $searchVal    = str_replace(" ", "%", $query);

            $leaveList = DB::table('view_bp_leave_report')
            ->whereBetween('start_date',[$month_Sdate,$month_Edate])
            ->where('status','=','Pending')
            ->where(function($sql_query) use($searchVal)
            {
                $sql_query->where('bp_name','like', '%'.$searchVal.'%')
                ->orWhere('bp_phone','like', '%'.$searchVal.'%')
                ->orWhere('retailer_name', 'like', '%'.$searchVal.'%')
                ->orWhere('retailer_phone_number','like', '%'.$searchVal.'%')
                ->orWhere('dealer_name', 'like', '%'.$searchVal.'%')
                ->orWhere('distributor_code','like', '%'.$searchVal.'%')
                ->orWhere('leave_type','like', '%'.$searchVal.'%')
                ->orWhere('status','like', '%'.$searchVal.'%');
            })
            ->orderBy($sort_by, $sort_type)
            ->paginate(100);

            return view('admin.bpromoter.leave_list_result_data', compact('leaveList'))->render();
        }
        else
        {
            $leaveList = DB::table('view_bp_leave_report')
            ->whereBetween('start_date',[$month_Sdate,$month_Edate])
            ->where('status','=','Pending')
            ->paginate(100);
        }

        if(isset($leaveList) && $leaveList->isNotEmpty()) {
            Log::info('Load BP Leave List');
        } else {
            Log::warning('BP Leave List Not Found');
        }
        return view('admin.bpromoter.leave_list',compact('leaveList'));
    }

    public function bpLeaveSearch(Request $request)
    {
        $leaveBPId  = $request->input('bp_id');
        $leaveSdate = $request->input('start_date');
        $leaveEdate = $request->input('end_date');

        Session::put('leaveBPId',$leaveBPId);
        Session::put('leaveSdate',$leaveSdate);
        Session::put('leaveEdate',$leaveEdate);

        $bpId       = 0;
        if($request->input('bp_id')) {
            $bpId   = $request->input('bp_id');
        }


        $formDate   = Session::get('leaveSdate') ? Session::get('leaveSdate') : date('Y-m-01');
        $toDate     = Session::get('leaveEdate') ? Session::get('leaveEdate') : date('Y-m-t');

        $leaveList = "";

        if($request->ajax()) 
        {
           $sort_by      = $request->get('sortby');
            $sort_type    = $request->get('sorttype');
            $query        = $request->get('query');
            $query        = str_replace(" ", "%", $query);
            $searchVal    = str_replace(" ", "%", $query);

            $leaveList = DB::table('view_bp_leave_report')
            ->whereBetween('start_date',[$formDate,$toDate])
            ->where('status','=','Pending')
            ->where(function($sql_query) use($searchVal)
            {
                $sql_query->where('bp_name','like', '%'.$searchVal.'%')
                ->orWhere('bp_phone','like', '%'.$searchVal.'%')
                ->orWhere('retailer_name', 'like', '%'.$searchVal.'%')
                ->orWhere('retailer_phone_number','like', '%'.$searchVal.'%')
                ->orWhere('dealer_name', 'like', '%'.$searchVal.'%')
                ->orWhere('distributor_code','like', '%'.$searchVal.'%')
                ->orWhere('leave_type','like', '%'.$searchVal.'%')
                ->orWhere('status','like', '%'.$searchVal.'%');
            })
            ->orderBy($sort_by, $sort_type)
            ->paginate(100);

            return view('admin.bpromoter.leave_list_result_data', compact('leaveList'))->render();
        }
        else
        {
            $leaveList = DB::table('view_bp_leave_report')
            ->where('status','=','Pending')
            ->whereBetween('start_date',[$formDate,$toDate])
            ->where(function($sql_query) use($bpId) {
                if(!empty($bpId) && $bpId > 0)
                {
                    $sql_query->where('bp_id','=',$bpId);
                }
            })
            ->when($bpId, function ($query, $bpId) {
                return $query->where('bp_id','=',$bpId);
            })
            ->paginate(100);
        }

        if(isset($leaveList) && !empty($leaveList)) {
            Log::info('BP Leave List Not Found');
            return view('admin.bpromoter.leave_list',compact('leaveList'))->with('success','Data Found');
        } else {
           Log::warning('BP Leave List Not Found');
           //return redirect()->action([ReportController::class, 'bpLeaveReportForm'])->with('error','Data Not Found.Please Try Again');
           return redirect()->back()->with('error','Data Not Found');
        }
    }
    
    public function pending_bp_update()
    {
        $getBpLists = BrandPromoter::where('bp_id','=',0)
        ->where('id','=',720)
        //->where('status','=',1)
        //->where('is_send','=',0)
        ->get();

        if($getBpLists->isNotEmpty()){
            foreach($getBpLists as $row) {
                $bpFeeds = [
                    "id"=>$row->id,
                    "category_id"=>$row->category_id,
                    "bp_id"=>$row->bp_id,
                    "retailer_id"=>$row->retailer_id,
                    "bp_name"=>$row->bp_name,
                    "bp_phone"=>$row->bp_phone,
                    "retailer_name"=>$row->retailer_name,
                    "owner_name"=>$row->owner_name,
                    "police_station"=>$row->police_station,
                    "retailer_phone_number"=>$row->retailer_phone_number,
                    "retailder_address"=>$row->retailder_address,
                    "distributor_code"=>$row->distributor_code,
                    "distributor_code2"=>$row->distributor_code2,
                    "distributor_name"=>$row->distributor_name,
                    "distributor_zone"=>$row->distributor_zone,
                    "division_name"=>$row->division_name,
                    "distric_name"=>$row->distric_name,
                    "bank_name"=>$row->bank_name,
                    "agent_name"=>$row->agent_name,
                    "status"=>$row->status,
                    "monthly_average_sale"=>$row->monthly_average_sale,
                    "monthly_average_sale_of_walton"=>$row->monthly_average_sale_of_walton
                ];

                $getPostCurlResponse = postBPData(sprintf(RequestApiUrl("addBP")),$bpFeeds);

                if($getPostCurlResponse) {
                    $responseModify = json_decode($getPostCurlResponse);
                    if($responseModify->bp_id > 0) {
                        $UpdatePromoter = BrandPromoter::where('id','=',$getInsertBpId)
                        ->update([
                            "bp_id"=>$responseModify->bp_id,
                            "is_send"=>1
                        ]);
                    }
                }
            }
        }
    }
    
    public function update_missing_bp_id()
	{
		$getCurlResponse    = getData(RequestApiUrl("GetBrandPromoter"),"GET");
        $responseData       = json_decode($getCurlResponse['response_data'],true);
		if(isset($getCurlResponse) && $getCurlResponse['status'] == 200) 
		{
            foreach ($responseData as $row) 
            {
                $bpId                           = $row['Id'];
                $bpName                         = $row['BPName'];
                $bpPhone                        = $row['BPPhoneNumber'];
                $bpDistrict                     = $row['District'];
                $bpCategoryId                   = 1;

                $retailerID                     = $row['RetailerID'];
                $retailerName                   = $row['RetailerName'];
                $retailerAddress                = $row['RetailerAddress'];
                $retailerZone                   = $row['RetailerZone'];
                $retailerPhoneNumber            = $row['RetailerPhoneNumber'];
                $retailerZoneId                 = $row['RetailerZoneId'];

                $distributorName                = $row['DistributorName'];
                $distributorCode                = $row['DistributorCode'];
                $alternateCode                  = $row['AlternateDistributorCode'];

                $monthlyAverageSale             = $row['MonthlyAverageSale'];
                $monthlyAverageSaleOfWalton     = $row['MonthlyAverageSaleOfWalton'];

                $status                         = $row['Active'] == true ? 1 :0;
				
				$CheckPromoter = BrandPromoter::where('bp_phone','=',$bpPhone)->first();
				
				
				if($CheckPromoter && $CheckPromoter['bp_id'] == 0 || $CheckPromoter['bp_id'] == null)
                {
                    BrandPromoter::where('id',$CheckPromoter['id'])
                    ->update([
                        "category_id"=>$bpCategoryId ? $bpCategoryId:1,
                        "bp_id"=>$bpId,
                        "retailer_id"=>$retailerID,
                        "bp_name"=>$bpName,
                        "bp_phone"=>$bpPhone,
                        "retailer_name"=>$retailerName,
                        "retailer_phone_number"=>$retailerPhoneNumber,
                        "retailder_address"=>$retailerAddress,
                        "distributor_code"=>$distributorCode,
                        "distributor_code2"=>$alternateCode,
                        "distributor_name"=>$distributorName,
                        "distributor_zone"=>$retailerZone,
                        "division_name"=>$bpDistrict,
                        "distric_name"=>$bpDistrict,
                        "status"=>$status,
                        "monthly_average_sale"=>$monthlyAverageSale,
                        "monthly_average_sale_of_walton"=>$monthlyAverageSaleOfWalton,
                    ]);
                }
			}
		}
	}
}
