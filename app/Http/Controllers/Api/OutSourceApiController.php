<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use lluminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\DealerInformation;
use App\Models\DelarDistribution;
use App\Models\Retailer;
use App\Models\Zone;
use App\Models\User;
use App\Models\Rsm;
use App\Models\Products;
use App\Models\ProductMasterPrice;
use App\Models\Employee;
use App\Models\Incentive;
use App\Models\SpecialAward;
use App\Models\BrandPromoter;
use App\Models\Sale;
use App\Models\SaleProduct;
use App\Models\BpAttendance;
use App\Models\AuthorityMessage;
use App\Models\PreBookin;
use App\Models\PushNotification;
use App\Models\RetailerProductStock;
use App\Models\Category;
use Carbon\Carbon;
use DB;
use Response;
use Validator;
use Image;
use JWTAuth;
use Storage;
use Config;
use Tymon\JWTAuth\Exceptions\JWTException;
date_default_timezone_set('Asia/Dhaka');

class OutSourceApiController extends Controller
{
    public function verifyApiAuth($headerAuth,$token) {
        if ($headerAuth) {
            if ($tokenFetch = JWTAuth::parseToken()->authenticate()) {
                if ($token) {
                    return true;
                }
                return false;
            }
            return false;
        }
        return false;
    }

    public function getAuthenticatedUser() {
        $user = JWTAuth::toUser(JWTAuth::getToken());
        if ($user != null) {
            $id = $user['bp_id'];
            $login_field = 'bp_id';
            if ($user['retailer_id'] > 0) {
                $id = $user['retailer_id'];
                $login_field = 'retailer_id';
            }

            if ($user['employee_id'] > 0) {
                $id = $user['employee_id'];
                $login_field = 'employee_id';
            }
            return ['login_field'=>$login_field,'id'=>$id];
        }        
    }

    public function retailerProductStocks(Request $request) {
        $headerAuth = $request->header('Authorization'); 
        $token = $request->bearerToken();
        $verifyStatus = $this->verifyApiAuth($headerAuth,$token);
        if (isset($verifyStatus) && $verifyStatus === true) {
            $productId = $request->productId;
            $retailerId = $request->retailerId;
            $quantity = $request->quantity;
            if (!empty($productId) && $productId > 0 && !empty($retailerId) && $retailerId > 0) {
                $message = "";
                $logInfo = "";
                $isProductExists = RetailerProductStock::where('product_id','=',$productId)
                    ->where('retailer_id','=',$retailerId)
                    ->first();
                if ($isProductExists) {
                    $isProductExists->quantity += $quantity;
                    $isProductExists->updated_at = date('Y-m-d H:i:s');
                    $isUpdate = $isProductExists->update();

                    $logInfo = "Retailer's Product Stocks Updatedation Successful By Apps";
                    $message = "Retailer's Product Stocks Updatedation Successful";
                    if ($isUpdate == false) {
                        $logInfo = "Retailer's Product Stocks Updatedation Unsuccessful By Apps";
                        $message = "Retailer's Product Stocks Updatedation Unsuccessful";
                    }
                } else {
                    $stockInfo = RetailerProductStock::create([
                        "retailer_id"=>$retailerId,
                        "product_id"=>$productId,
                        "quantity"=>$quantity,
                        "created_at"=>date('Y-m-d H:i:s'),
                    ]);

                    $logInfo = "Retailer's Product Stocks Saved Successfully By Apps";
                    $message = "Retailer's Product Stock Save Successful";
                    if (empty($stockInfo)) {
                        $logInfo = "Retailer's Product Stocks Save Unsuccessful By Apps";
                        $message = "Retailer's Product Stocks Save Unsuccessful";
                    } 
                }
                Log::info($logInfo);
                return response()->json(['message'=>$message],200);
            } else {
                return response()->json(apiResponses(400),400);
            }
        } else {
            return response()->json(apiResponses(401),401);
        }        
    }

    public function getRetailerProductLists(Request $request) {
        $headerAuth = $request->header('Authorization'); 
        $token = $request->bearerToken();
        $verifyStatus = $this->verifyApiAuth($headerAuth,$token);
        if (isset($verifyStatus) && $verifyStatus === true) {
            $retailerId = $request->retailerId;
            if (!empty($retailerId) && $retailerId > 0) {
                $message = "";
                $logInfo = "";
                $productLists = RetailerProductStock::select('retailer_product_stocks.id as id','retailer_product_stocks.retailer_id as retailerId','retailer_product_stocks.product_id as productId','retailer_product_stocks.quantity as stock','product_masters.product_model as model')
                    ->leftJoin('product_masters','product_masters.product_master_id','=','retailer_product_stocks.product_id')
                    ->where('retailer_product_stocks.retailer_id','=',$retailerId)
                    ->get();
                if ($productLists) {
                    return response()->json($productLists,200);
                } else {
                    Log::info("Stock Not Found");
                    return response()->json("Retailer's Product Stock Save Successful",200);
                }
            } else {
                return response()->json(apiResponses(400),400);
            }
        } else {
            return response()->json(apiResponses(401),401);
        }
    }

    public function updateRetailerStockAfterSaleProduct($retailerId = 0,$productId = 0, $quantity = 0) {
        if ($retailerId > 0 && $productId > 0) {
            $stockInfo = RetailerProductStock::where('retailer_id','=',$retailerId)->where('product_id','=',$productId)->first();
            if ($stockInfo) {
                $stockInfo->quantity -= $quantity;
                $stockInfo->updated_at = date('Y-m-d H:i:s');
                $isUpdate = $stockInfo->update();
                if ($isUpdate) {
                    return true;
                }
                return false;
            }
            return false;
        }
        return false;
    }
    
    public function GetByInfoImeNumber(Request $request,$imeListArray) {
        $headerAuth = $request->header('Authorization'); 
        $token = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus = $this->verifyApiAuth($headerAuth,$token);
        
        if (isset($verifyStatus) && $verifyStatus === true) {
            $userId = auth('api')->user()->id;
            $userExists = DB::table('users')->where('id',$userId)->first();
            $loginUserId = 0;
			$userType = "";
			$userPhone = "";
            if ($userExists->bp_id > 0) {
                $loginUserId = $userExists->bp_id;
				$userType = "bp";
				$userPhone = BrandPromoter::select('bp_phone')->where('id','=',$loginUserId)->value('bp_phone');
            } else if($userExists->retailer_id > 0) {
                $loginUserId = $userExists->retailer_id;
				$userType = "retailer";
				$userPhone = Retailer::select('phone_number')->where('id','=',$loginUserId)->value('phone_number');
            }            
            
            $response = "";
            $imeResult = [];
            $imeNumberList = explode(",",$imeListArray);
            
            foreach($imeNumberList as $imeNumber) {
                $checkValidIMEI = strlen($imeNumber);
                if ($checkValidIMEI != 15) {
                    Log::error('Invalid IMEI Number ->OutSourceApiController->GetByInfoImeNumber');
                    return response()->json(apiResponses(404,"Invalid IMEI Number"),404);
                }                
                /////////////////////////////////
                $barcode = "";
                $barcode2 = "";
                $dealer_code = "";
                $distributor_name = "";
                $retailer_name = "";
                $retailer_phone = "";
                $retailder_address = "";
                $retailer_zone = "";
                $dealer_zone = "";
                $product_model = "";
                $product_color = "";
                $is_sold = false; // false=Available
                $product_id = "";
                $status = 1; // 1= Available
                $message = "";
                $product_code = "";
                $produc_type = "";
                $category = "";
                $mrp_price = "";
                $msdp_price = "";
                $msrp_price = "";
                $dealer_name = "";
                $color_id = "";
                
                $imeiSoldStatus = DB::table('view_sales_reports')
                    ->where('ime_number','=',trim($imeNumber))
                    ->orWhere('alternate_imei','=',trim($imeNumber))
                    ->first();

                if ($imeiSoldStatus) {
                    $getSaleDate = date("jS \of F Y",strtotime($imeiSoldStatus->sale_date));
                    $sellerName = ($imeiSoldStatus->bp_name) ? $imeiSoldStatus->bp_name:$imeiSoldStatus->retailer_name;
                    $barcode = $imeiSoldStatus->ime_number;
                    $barcode2 = $imeiSoldStatus->alternate_imei;
                    $dealer_code = $imeiSoldStatus->dealer_code;
                    $distributor_name = $imeiSoldStatus->dealer_name;
                    $retailer_name = $imeiSoldStatus->retailer_name;
                    $retailer_phone = $imeiSoldStatus->retailer_phone_number;
                    $retailder_address = $imeiSoldStatus->retailder_address;
                    $retailer_zone = $imeiSoldStatus->zone_id;
                    $dealer_zone = '';
                    $product_model = $imeiSoldStatus->product_model;
                    $product_color = $imeiSoldStatus->product_color;
                    $is_sold = true;
                    $product_id = $imeiSoldStatus->product_master_id;
                    $status = 0;
                    $message = "Not valid. This IMEI alreday sold By ".$sellerName.' '.$getSaleDate;
                    $product_code = $imeiSoldStatus->product_code;
                    $product_type = $imeiSoldStatus->product_type;
                    $category = $imeiSoldStatus->category;
                    $mrp_price = $imeiSoldStatus->mrp_price;
                    $msdp_price = $imeiSoldStatus->msdp_price;
                    $msrp_price = $imeiSoldStatus->msrp_price;
                    $dealer_name = $imeiSoldStatus->dealer_name;
                    $color_id = 0;
                } else {                    
                    $getCurlResponse = getData(sprintf(RequestApiUrl("ImeiCheckWithDealerCode"),$imeNumber,$userType,$userPhone),"GET");
                    $responseData = (array) json_decode($getCurlResponse['response_data'],true);

                    if (isset($responseData) && $responseData == "" || empty($responseData)) {
                        Log::error('IMEI Info Not Found ->OutSourceApiController->GetByInfoImeNumber');
                        return response()->json(apiResponses(404),404);
                    }

                    $barcode = $responseData[0]['ImeiOne'];
                    $barcode2 = $responseData[0]['ImeiTwo'];
                    $dealer_code = $responseData[0]['DealerCode'];
                    $distributor_name = $responseData[0]['DistributorNameCellCom'];
                    $retailer_name = $responseData[0]['RetailerName'];
                    $retailer_phone = $responseData[0]['RetailerPhone'];
                    $retailder_address = $responseData[0]['RetailerAddress'];
                    $retailer_zone = $responseData[0]['RetailerZone'];
                    $dealer_zone = $responseData[0]['DealerZone'];
                    $product_model = $responseData[0]['Model'];
                    $product_color = $responseData[0]['Color'];
                    $is_sold = ($responseData[0]['IsValid'] == true) ? false:true;
                    $product_id = $responseData[0]['ProductID'];
                    $status = ($responseData[0]['IsValid'] == true) ? "1":"0";
                    $message = $responseData[0]['Message'];

                    $imeProductResult = DB::table('view_product_master')->where('product_id','=',$product_id)->first();

                    if (isset($imeProductResult) && empty($imeProductResult) || $imeProductResult == "") {
                        Log::warning('Product Not Available By imei Number->OutSourceApiController->GetByInfoImeNumber');
                        return response()->json(apiResponses(404,"Product Not Available"),404);
                    }

                    $productColorId = DB::table('colors')->where('name','like','%'.$responseData[0]['Color'].'%')->value('color_id');

                    $dealerName    = DB::table('dealer_informations')
                        ->where('dealer_code',$responseData[0]['DealerCode'])
                        ->orWhere('alternate_code',$responseData[0]['DealerCode'])
                        ->value('dealer_name');

                    $product_code = $imeProductResult->product_code;
                    $product_type = $imeProductResult->product_type;
                    $category = $imeProductResult->category2;
                    $mrp_price = $imeProductResult->mrp_price;
                    $msdp_price = $imeProductResult->msdp_price;
                    $msrp_price = $imeProductResult->msrp_price;
                    $dealer_name = $dealerName;
                    $color_id = $productColorId ? $productColorId:0;
                }

                $imeInfo = [
                    'barcode'=>$barcode,
                    'barcode2'=>$barcode2,
                    'dealer_code'=>$dealer_code,
                    'distributor_name'=>$distributor_name,
                    'retailerName'=>$retailer_name,
                    'retailerPhone'=>$retailer_phone,
                    'retailerAddress'=>$retailder_address,
                    'retailerZone'=>$retailer_zone,
                    'dealerZone'=>$dealer_zone,
                    'productModel'=>$product_model,
                    'productColor'=>$product_color,
                    'is_sold'=>$is_sold,
                    'productId'=>$product_id,
                    'status'=>$status,
                    'message'=>$message,
                    'productCode'=>$product_code,
                    'productType'=>$product_type,
                    'category'=>$category,
                    'mrpPrice'=>$mrp_price,
                    'msdpPrice'=>$msdp_price,
                    'msrpPrice'=>$msrp_price,
                    'dealerName'=>$dealer_name,
                    'color_id'=>$color_id,
                ];
                return response()->json($imeInfo);
            }
        } else {
            return response()->json(apiResponses(401),401);
        }
    }

    public function getImeList(Request $request) {
        $headerAuth = $request->header('Authorization'); 
        $token = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus = $this->verifyApiAuth($headerAuth,$token);
        
        if (isset($verifyStatus) && $verifyStatus === true) {
            $notFoundIme = [];
            $imeResult = [];
            $imeStatus = [];
            $imeInfo = [];
            $imeList = $request->input('imeList');
            $DelarDistributionModel = new DelarDistribution;
            $DelarDistributionModel->setConnection('mysql2');
            $getAllImeNumber = $DelarDistributionModel::select('barcode','barcode2')
                ->get()
                ->pluck('barcode','barcode2')
                ->toArray();

            if (is_array($imeList)) {
                foreach($imeList as $imeNumber) {
                    if (!in_array($imeNumber, $getAllImeNumber)) {
                        $notFoundIme[] = $imeNumber;
                        $imeStatus[$imeNumber] = "Not Found";
                    } else {
                        $imeInfo = $DelarDistributionModel::select('barcode','barcode2','dealer_code')
                            ->where('barcode',$imeNumber)
                            ->orWhere('barcode2',$imeNumber)
                            ->first();
                        $productMasterId = $DelarDistributionModel::select('product_master_id')
                            ->where('barcode',$imeNumber)
                            ->orWhere('barcode2',$imeNumber)
                            ->value('product_master_id');
                        $imeProductResult = DB::table('view_product_master')
                            ->where('product_master_id',$productMasterId)
                            ->first();
                        $imeInfo->imeProductInfo = $imeProductResult;
                        array_push($imeResult,$imeInfo);
                        $imeStatus[$imeNumber] = "Match";
                    }
                }
            }

            if (!empty($notFoundIme)) {
                Log::info('Get IMEI List By Apps');
                return response()->json([$imeResult,$notFoundIme],200);
            } else {
                Log::info('Get IMEI List By Apps');
                return response()->json([$imeResult],200);
            }
        } else {
            return response()->json($response = apiResponses(401),401);
        }
    }
    
    public function SalesProduct(Request $request) {
        // return $request->all();
        $headerAuth = $request->header('Authorization'); 
        $token = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus = $this->verifyApiAuth($headerAuth,$token);

        if (isset($verifyStatus) && $verifyStatus === true) {
            $retailId = 0;
            $ZoneId = 0;

            if ($request->input('retailer_id') && $request->input('retailer_id') > 0) {
                $retailId = $request->input('retailer_id');
                $getRetailInfo = Retailer::select('id','retailer_id','phone_number','zone_id')->where('id','=',$retailId)->first();
                if ($getRetailInfo->zone_id != null || !empty($getRetailInfo->zone_id)) {
                    $ZoneId = $getRetailInfo->zone_id;
                }
            } else {
                return response()->json(apiResponses(400,'Retailer Not Found'),400);
            }

            $customer_name = $request->input('customer_name');
            $customer_phone = $request->input('customer_phone');
            $itemList = $request->input('list');
            $itemList = stripslashes(html_entity_decode($itemList));
            $itemList = (!empty($itemList)) ? json_decode($itemList,true) : [];
            if (empty($itemList) || !is_array($itemList)) {
                return response()->json(apiResponses(400,'Cart is empty'),400);
            }

            $porductInfo = "";
            $saleStatus = false;
            $sale_data = "";
            $saleId = "";
            $invoice_number = "";
            $dealerCode = 0;
            $productNotFoundStatus = true;
            $productNotFoundQtyCount = 0;
            $totalSaleQty = 0;
            $totalSaleItemAmount = 0;
            $totalSaleAmount = 0;

            if (count($itemList) > 0) {
                if ($saleStatus == false) {
                    $ClientPic = "";
                    $destinationPath = "";
                    if ($request->hasFile('photo')) {
                        $getPhoto = $request->file('photo');
                        $filename = time().'.'.$getPhoto->getClientOriginalExtension();
                        $destinationPath = public_path('/upload/client');
                        $success = $getPhoto->move($destinationPath, $filename);
                        $ClientPic = $filename;
                    }                
                    $baseUrl = URL::to('');
                    $storagePath = $baseUrl.'/storage/app/public/'.$ClientPic;
                    $unique_number = mt_rand(1,9);
                    $invoice_number = date('Ymds').$unique_number;
                    $saleInfo = Sale::create([
                        "invoice_number"=>$invoice_number,
                        "customer_name"=>$request->input('customer_name'),
                        "customer_phone"=>$request->input('customer_phone'),
                        "retailer_id"=> $retailId,
                        "dealer_code"=> $dealerCode,
                        "sale_date"=>date('Y-m-d H:i:s'),
                        "total_item"=>$totalSaleQty,
                        "total_qty"=>$totalSaleItemAmount,
                        "total_amount"=>$totalSaleAmount,
                        "photo"=>$ClientPic,
                        "status"=>0,
                        "walton_status"=>1,
                        "order_type"=>1,
                    ]);
                    $saleId = DB::getPdo()->lastInsertId();
                    $saleStatus = true;
                }

                foreach ($itemList as $lists) {
                    if (!empty($saleId) && $saleStatus == true) {
                        if (isset($lists['productId']) && $lists['productId'] > 0) {
                            $productId = $lists['productId'];
                            $porductInfo = DB::table('view_product_master')->where('product_master_id','=',$productId)->first();
                            if ($porductInfo) {
                                $productMasterId = $porductInfo->product_master_id;
                                if ($productMasterId > 0) {
                                    $salePrice = 0;
                                    if ($porductInfo->msrp_price) {
                                        $salePrice = $porductInfo->msrp_price;
                                    } else if (isset($lists['price'])) {
                                        $salePrice = $lists['price'];
                                    }
                                    $totalSaleQty++;
                                    $totalSaleItemAmount = $totalSaleItemAmount + $lists['qty'];
                                    $totalSaleAmount = $totalSaleAmount + $lists['qty'] * $salePrice;

                                    SaleProduct::create([
                                        "sales_id"=>$saleId,
                                        "product_master_id"=>$productMasterId,
                                        "dealer_code"=>$dealerCode,
                                        "product_id"=>$porductInfo->product_id,
                                        "product_code"=>$porductInfo->product_code,
                                        "product_type"=>$porductInfo->product_type,
                                        "product_model"=>$porductInfo->product_model,
                                        "category"=>$porductInfo->category2,
                                        "mrp_price"=>$porductInfo->mrp_price,
                                        "msdp_price"=>$porductInfo->msdp_price,
                                        "msrp_price"=>$porductInfo->msrp_price,
                                        "sale_price"=>$salePrice,
                                        "sale_qty"=>$lists['qty'],
                                        "retailer_id"=>$retailId,
                                        "product_status"=>0, //Sold Order
                                    ]);
                                    $isUpdateStock = $this->updateRetailerStockAfterSaleProduct($retailId,$productMasterId,$lists['qty']);
                                    // return $isUpdateStock;
                                } else {
                                    $productNotFoundQtyCount++;
                                    $productNotFoundAmount = $productNotFoundAmount + $lists['price'];
                                    $productNotFoundStatus = false;
                                }
                            } else {
                                $productNotFoundQtyCount++;
                                $productNotFoundStatus = false;
                            }
                        } else {
                            $productNotFoundQtyCount++;
                            $productNotFoundStatus = false;
                        }
                    }
                }
                $saleInfo->total_item = $totalSaleQty;
                $saleInfo->total_qty = $totalSaleItemAmount;
                $saleInfo->total_amount = $totalSaleAmount;
                $saleInfo->update();
            }

            $sale_data = [
                "saleStatus"=>$saleStatus,
                "sale_id"=>$saleId,
                "retailer_id"=> $retailId,
                "sale_date"=>date('Y-m-d'),
                "customer_name"=> $request->input('customer_name'),
                "customer_phone"=>  $request->input('customer_phone'),
                "invoiceImgPath"=>  $baseUrl.'/public/admin/invoices/1234.png',
                'productNotFoundStatus'=>$productNotFoundStatus,
                'productNotFoundMessage'=>"Total ".$productNotFoundQtyCount." products are not found you have added to the cart.",
            ];

            if ($sale_data['saleStatus'] == true) {
                Log::info('Product Sales Success By Apps');
                $sale_data['message'] = "Order successfully placed";
                return response()->json($sale_data,200);
            } else {
                Log::info('Product Sales Unsuccess By Apps');
                $sale_data['message'] = "Order placed Unsuccessful";
                return response()->json($sale_data,200);
            }
        } else {
            return response()->json(apiResponses(401),401);
        }
    }
    
    public function salesReport(Request $request) {
        // return $request->all();
        $authenticateUser = $this->getAuthenticatedUser();
        $headerAuth = $request->header('Authorization'); 
        $token = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus = $this->verifyApiAuth($headerAuth,$token);

        if (isset($verifyStatus) && $verifyStatus === true) {
            $userId = auth('api')->user()->id;
            $userExists = DB::table('users')->where('id',$userId)->first();
            $bpId = 0;
            $retailId = 0;
            $bpqueryStatus = 0;
            $requeryStatus = 0;
            if ($request->retailerId) {
                $retailId = $request->retailerId;
            } else if($userExists->retailer_id > 0) {
                $retailId = $userExists->retailer_id;
            }

            $current_month_first_date = date('Y-m-01');
            $current_month_last_date = date('Y-m-t');
            $fast_day_previous_one_month = date('Y-m-01', strtotime('-1 Months'));
            $last_day_previous_one_month = date('Y-m-t', strtotime('-1 Months'));
            $fast_day_previous_two_month = date('Y-m-01', strtotime('-2 Months'));
            $last_day_previous_two_month = date('Y-m-t', strtotime('-2 Months'));
            $compaireStartDate = strtotime($fast_day_previous_two_month);
            $compaireEndDate = strtotime($current_month_last_date);
            $searchStartDate = strtotime($request->startDate);
            $searchEndDate = strtotime($request->endDate);
            $reqSdate = $request->startDate;
            $reqEdate = $request->endDate;

            if ($searchStartDate >= $compaireStartDate  && $searchEndDate <= $compaireEndDate) {
                $saleList = DB::table('view_sales_reports')
                    ->where(function($sql_query) use($retailId){
                        if ($retailId > 0) {
                            $sql_query->where('retailer_id','=', $retailId);
                        }
                    })
                    ->whereBetween(DB::raw("DATE_FORMAT(sale_date,'%Y-%m-%d')"),[$reqSdate,$reqEdate])
                    ->where('status','=',0)
                    ->get();

                if (count($saleList) > 0) {
                    foreach($saleList as $sale) {                    
                        $saleProductList = DB::table('sale_products')
                            ->select('ime_number','product_code','product_type','product_model','product_color','category','mrp_price','msdp_price','msrp_price','sale_price','sale_qty')
                            ->where(function($sql_query) use($retailId){
                                if ($retailId > 0) {
                                    $sql_query->where('retailer_id','=', $retailId);
                                }
                            })
                            ->where('sales_id',$sale->id)
                            ->get();

                        $dealerInfo = DB::table('dealer_informations')
                            ->select('dealer_code as code','alternate_code as alternate_code','dealer_name as name','dealer_address as address','zone','dealer_phone_number as phone')
                            ->where('dealer_code',$sale->dealer_code)
                            ->orWhere('alternate_code',$sale->dealer_code)
                            ->first();

                        foreach ($saleProductList as $saleProduct) {
                            $saleProduct->dealer_name = "";
                            $saleProduct->dealer_code = "";
                            if (isset($dealerInfo)) {
                                $saleProduct->dealer_name = $dealerInfo->name;
                                $saleProduct->dealer_code = $dealerInfo->code ? $dealerInfo->code : $dealerInfo->alternate_code;
                            }
                        }
                        $sale->product_list = $saleProductList;
                        $sale->retailer_info = "";
                        if ($sale->retailer_id) {
                            $retailerInfo = DB::table('retailers')
                                ->select('retailer_name as name','retailder_address as address','phone_number as phone')
                                ->where('id',$sale->retailer_id)
                                ->first();
                            $sale->retailer_info = $retailerInfo;
                        }
                    }
                    Log::info('Get Sales List By Apps Request');
                    return response()->json($saleList,200);
                } else {
                    Log::warning('Sales List Not Found By Apps Request Date Range ->OutSourceApiController->salesReport');
                    return response()->json(apiResponses(404),404);
                }

            } else {
                Log::warning('Sales List Not Found By Apps Request Date Range ->OutSourceApiController->salesReport');
                return response()->json(["message"=> "Date Range Not Coverage.Please Try Again[".$reqSdate.'/'.$reqEdate."]","code"=>404],404);
            }
        } else {
            return response()->json(apiResponses(401),401);
        }
    }

    public function salesInvoice() {
        
        // $baseUrl = URL::to('');
        
       // return json_encode( 'https://manush.co.uk/syngenta_retailer/public/upload/invoices/1234.png') ;
        $html = view('api.sales.sales_invoice')->render();
        return $html;
    }
    
  
    

    public function singleSalesReport(Request $request) { 
        $authenticateUser = $this->getAuthenticatedUser();
        $headerAuth = $request->header('Authorization'); 
        $token = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus = $this->verifyApiAuth($headerAuth,$token);
        $bpId = 0;
        $retailId  = 0;
        if ($request->bpId) {
            $bpId = $request->bpId;
        } else {
            $retailId = $request->retailerId;
        }
        
        $sale_id = $request->salesId;

        if (isset($verifyStatus) && $verifyStatus === true && $sale_id > 0) {
            $salesLists = DB::table('sales')
                ->select('*')
                ->where(function($sql_query) use($bpId,$retailId){
                    if($bpId > 0){
                        $sql_query->where('bp_id','=', $bpId);
                    }
                    if($retailId > 0){
                        $sql_query->where('retailer_id','=', $retailId);
                    }
                })
                ->where('id',$sale_id)
                ->first();

            if (isset($salesLists) && !empty($salesLists)) {
                $saleProductList = DB::table('sale_products')
                    ->select('*')
                    ->where('bp_id',$bpId)
                    ->where('retailer_id',$retailId)
                    ->where('sales_id',$salesLists->id)
                    ->get();
                $salesLists->saleProductList = $saleProductList;                
                Log::info('Get Sales List');
                return response()->json($salesLists,201);
            } else {
                Log::info('Sales List Not Found');
                return response()->json(apiResponses(404),404);
            }
        } else  {
            return response()->json(apiResponses(401),401);
        }
    }

    public function salesIncentiveReport(Request $request) {
        $headerAuth = $request->header('Authorization'); 
        $token = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus = $this->verifyApiAuth($headerAuth,$token);
        
        if (isset($verifyStatus) && $verifyStatus === true) {
            $bpId = 0;
            $retailerId = 0;
            if ($request->bpId > 0) {
                $bpId = $request->bpId;
                $getBpInfo = BrandPromoter::select('bp_id','retailer_id')->where('id','=',$bpId)->first();                
                $retailerId = $getBpInfo->retailer_id;
            } else {
                $retailerId = $request->retailerId;
            }
            
            $salesIncentiveReportList = DB::table('view_sales_incentive_reports')
                ->select("category","ime_number as imei","incentive_title as title","zone","incentive_amount as amount","incentive_min_qty as min_qty","incentive_sale_qty as sale_qty","retailer_name","bp_name","product_model")
                ->where('bp_id',$bpId)
                ->where('retailer_id',$retailerId)
                ->get();

            $zone_name_list = [];
            if ($salesIncentiveReportList->isNotEmpty()) {
                foreach($salesIncentiveReportList as $incentiveList) {
                    $zoneIdList = json_decode($incentiveList->zone);
                    foreach($zoneIdList as $zone) {
                        $zone_name_list[] = DB::table('view_zone_list')
                        ->where('id',$zone)
                        ->where('status',1)
                        ->value('zone_name');
                    }
                    unset($incentiveList->zone);
                }
                $incentiveList->zone_name = $zone_name_list;                
                Log::info('Get Sales Incentive List');
                return response()->json($salesIncentiveReportList,200);
            } else {
                Log::warning('Sales Incentive Report Not Found');
                return response()->json(apiResponses(404),404);
            }
        } else {
            return response()->json(apiResponses(401),401);
        }
    }

    public function incentiveList(Request $request)
    {
        $authenticateUser = $this->getAuthenticatedUser();
        $headerAuth       = $request->header('Authorization'); 
        $token            = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus     = $this->verifyApiAuth($headerAuth,$token);

        if(isset($verifyStatus) && $verifyStatus === true) 
        {
            
            $bpId               = 0;
            $retailerId         = 0;
            $incentiveGroup     = 0;// 1=Bp or 2=Retailer
            $logedUserId        = 0;
            $currentDate        = date('Y-m-d');
            $startDate          = date('Y-m-01');
            $endDate            = date('Y-m-t');
            $groupCatId         = [];
            $zoneId             = ['all'];
            $userType           = "";

            $userId     = auth('api')->user()->id;
            $userExists = DB::table('users')
            ->where('id',$userId)
            ->first();


            if($userExists->bp_id > 0 && $userExists->bp_id != NULL) {
                $bpId               = $userExists->bp_id;
                $incentiveGroup     = 1; //Brand Promoter
                $logedUserId        = $bpId;
                $userType           = "bp";

                $getBpInfo          = BrandPromoter::select('retailer_id','category_id')
                ->where('id','=',$bpId)
                ->first();

                $groupCatId[]       = $getBpInfo->category_id;
                $getretailId        = $getBpInfo->retailer_id;

                $getRetailerInfo    = Retailer::select('retailer_id','category_id','zone_id')
                ->where('retailer_id','=',$getretailId)
                ->first();

                $zoneId[]           = $getRetailerInfo->zone_id;
            }
            elseif($userExists->retailer_id > 0 && $userExists->retailer_id != NULL) {
                $retailerId         = $userExists->retailer_id;
                $incentiveGroup     = 2; //Retailer
                $logedUserId        = $retailerId;
                $userType           = "retailer";

                $getRetailerInfo    = Retailer::select('retailer_id','category_id','zone_id')
                ->where('retailer_id','=',$retailerId)
                ->first();

                $groupCatId[]       = $getRetailerInfo->category_id;
                $zoneId[]           = $getRetailerInfo->zone_id;
            }

            $incentiveLists = DB::table('incentives')
            ->whereBetween(\DB::raw("DATE_FORMAT(end_date,'%Y-%m-%d')"),[$startDate,$endDate])
            ->where('status','=',1)
            //->where('incentive_amount','>',0)
            ->where(function($sql_query) use($userType,$bpId,$retailerId,$groupCatId) {
                if($userType == "bp") {
                    if($bpId > 0) {
                        $sql_query->where('incentive_group','=', 1);
                        $sql_query->where('group_category_id','=',$groupCatId);
                        //$sql_query->whereIn('group_category_id',$groupCatId);
                    }
                }
                if($userType == "retailer") {
                    if($retailerId > 0) {
                        $sql_query->where('incentive_group','=', 2);
                        $sql_query->where('group_category_id','=',$groupCatId);
                        //$sql_query->whereIn('group_category_id',$groupCatId);
                    }
                }
            })
            ->get();
            
            if(isset($incentiveLists) && !empty($incentiveLists)) 
            {
                $incentiveList = [];
                foreach($incentiveLists as $incentive)
                {
                    $getModelId         = json_decode($incentive->product_model);
                    $getIncentiveType   = json_decode($incentive->incentive_type);
                    $getZone            = json_decode($incentive->zone);

                    
                    $zoneNameArray = [];
                    foreach($getZone as $zoneId) {
                        if($zoneId == 'all') {
                            $zoneNameArray[]['zone_name'] = 'All';
                        } else {
                            $zoneNameArray[]['zone_name'] = DB::table('zones')
                            ->select('zone_name')
                            ->where('zone_id','=',$zoneId)
                            ->value('zone_name');
                        }
                    }
                    
                    $ProductModel = [];
                    if(in_array('all', (array)$getModelId)) {
                        $ProductModel[]['product_model'] = 'All';
                    } else {
                        $ProductModel = DB::table('view_product_master')
                        ->select('product_model')
                        ->whereIn('product_master_id', (array) $getModelId)
                        ->get();
                    }


                    $incentive->ProductModel   = $ProductModel;
                    $incentive->IncentiveType  = $getIncentiveType;
                    $incentive->ZoneName       = $zoneNameArray;//$ZoneName;

                    unset($incentive->incentive_group,$incentive->product_model,$incentive->incentive_type,$incentive->zone);

                    if(in_array('all', (array)$getZone) || in_array($zoneId, (array)$getZone) ){
                        $incentiveList[] = $incentive;
                    }
                }
                
                if(isset($incentiveList) && !empty($incentiveList)) {
                    Log::info('Get Incentive List By Apps Request');
                    return response()->json($incentiveList);
                } else {
                    Log::warning('Incentive List Not Found By Apps Request');
                    return response()->json(apiResponses(404),404);
                }
            }
            else 
            {
                return response()->json(apiResponses(404),404);
            }
        }
        else 
        {
            return response()->json(apiResponses(401),401);
        }
    }

    public function verifyBpAttendance($bpId=null,$requestInTime=null,$requestOutTime=null)
    {
        $officeInTime   = strtotime(date("10:00:00"));
        $officeOutTime  = strtotime(date("06:00:00"));
        $responseStatus = false;
        
        $checkBpRetailerId = 0;
        if(isset($bpId) && $bpId !=null || $bpId > 0) {
            $checkBpRetailerId = BrandPromoter::where('id','=',$bpId)->value('retailer_id');
            
            if($checkBpRetailerId !=null || $checkBpRetailerId > 0) {
                $response = Retailer::where('retailer_id','=',$checkBpRetailerId)
                ->select('shop_start_time','shop_end_time')
                ->first();
                
                if(isset($response) && !empty($response)) {
                    $responseStatus = true;
                    if($response['shop_start_time'] != null && $response['shop_end_time'] != null) {
                        
                        $officeInTime  = strtotime(date($response['shop_start_time']));
                        $officeOutTime = strtotime(date($response['shop_end_time']));
                    }
                }
            }
            
        }
        
        $getInTime  = round(abs($officeInTime - $requestInTime) / 60,2);
        $getOutTime = round(abs($officeOutTime - $requestOutTime) / 60,2);

        $inStatus   = "";
        $outStatus  = "";


        if($getInTime < 16) {
            $inStatus = "Late In";
        } else {
            $inStatus = "Present";//Ok
        }

        if($getOutTime >16) {
            $outStatus = "Early Out";
        } else {
            $outStatus = "On Time"; //Ok
        }
        
        
        /*DB::table('temporaryes')->insert([
            "request_data"=>$bpId.'~'.$inStatus.'~'.$getInTime.'~'.$officeInTime.'~'.$checkBpRetailerId,
            "date"=>date('d-m-Y'),
        ]);*/
                
        
        $status =["inStatus"=>$inStatus,'outStatus'=>$outStatus,'responseStatus'=>$responseStatus];

        return $status;
        
    }

    public function bpAttendance(Request $request)
    {
        $requestInTime  = strtotime(date("h:i:s"));
        $requestOutTime = strtotime(date("h:i:s"));

        $headerAuth     = $request->header('Authorization'); 
        $token          = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus   = $this->verifyApiAuth($headerAuth,$token);
        
        if(isset($verifyStatus) && $verifyStatus === true) {
            $SelfiPic = "";
            if($request->hasFile('photo')) {
                // $img = Image::make($image->path());
                // $img->resize(200, 200, function ($constraint) {
                // $constraint->aspectRatio();
                // })->save($destinationPath.'/'.$filename);
                $photo = $request->file('photo');
                $filename = time().'.'.$photo->getClientOriginalExtension();

                $SelfiPic = $filename;
                $destinationPath = public_path('/upload/bpattendance');
                $success = $photo->move($destinationPath, $filename);
            }

            $attendance_date_time = date('Y-m-d H:i:s');
            $location             = $request->input('location');
            $bpId                 = $request->input('bp_id');
            $currentDate          = date('Y-m-d');
            
            
            if(isset($bpId) && $bpId > 0) {
                $CheckAttendance = BpAttendance::
                where('bp_id',$bpId)
                //->where('date','like','%' . $currentDate . '%')
                ->where(\DB::raw("DATE_FORMAT(date,'%Y-%m-%d')"),$currentDate)
                ->orderBy('date', 'desc')
                ->first();
                
                
                $remarks            = 1;  //First In
                $inStatus           = "";
                $outStatus          = "";
                $responseMessage    = "";
                if($remarks == 1)
                {
                    $response = $this->verifyBpAttendance($bpId,$requestInTime);
                    
                    /*if($response == 0) {
                        return response()->json(apiResponses(422,'Retailer Not Found'),422);
                    }*/
                    if(!empty($response) && $response['responseStatus'] === true) {
                        $inStatus = $response['inStatus'];
                    } else {
                        return response()->json(apiResponses(422,'Retailer Not Found'),422);
                    }
                }

                if($CheckAttendance) {
                   $remarkStatus =  $CheckAttendance['remarks'];
                   if($remarkStatus == 1){
                        $remarks = 2; //First Out
    
                        $response = $this->verifyBpAttendance($bpId,0,$requestOutTime);
                        $outStatus = $response['outStatus'];
                        $responseMessage = "Check Out Has Been Successfully";
                   }
                   else if($remarkStatus == 2){
                        $remarks = 3; //Again In
    
                        $response = $this->verifyBpAttendance($bpId,$requestInTime,0);
                        $inStatus = $response['inStatus'];
                        $responseMessage = "Check In Has Been Successfully";
                   }
                   else if($remarkStatus == 3){
                        $remarks = 4; //Again Out
    
                        $response = $this->verifyBpAttendance($bpId,0,$requestOutTime);
                        $outStatus = $response['outStatus'];
                        $responseMessage = "Check Out Has Been Successfully";
                   }
                   else if($remarkStatus == 4){
                        $remarks = 3; //Again In
    
                        $response = $this->verifyBpAttendance($bpId,$requestInTime,0);
                        $inStatus = $response['inStatus'];
                        
                        $responseMessage = "Check In Has Been Successfully";
                   }
                }
                else
                {
                    $responseMessage = "Check In Has Been Successfully";
                }

                $locationDetails= $request->input('location_details');
                $locationDetails=(!empty( $locationDetails))? json_decode($locationDetails,true):[];
                $location='';
                if(!empty($locationDetails) && is_array($locationDetails)){
                    $firstLocation=$locationDetails[0];
                    $subThoroughfare=array_key_exists("subThoroughfare",$firstLocation)?$firstLocation['subThoroughfare']:"";
                    $thoroughfare=array_key_exists("thoroughfare",$firstLocation)?$firstLocation['thoroughfare']:"";
                    $subLocality=array_key_exists("subLocality",$firstLocation)?$firstLocation['subLocality']:"";
                    $locality=array_key_exists("locality",$firstLocation)?$firstLocation['locality']:"";
                    $subAdministrativeArea=array_key_exists("subAdministrativeArea",$firstLocation)?$firstLocation['subAdministrativeArea']:"";
                    $administrativeArea=array_key_exists("administrativeArea",$firstLocation)?$firstLocation['administrativeArea']:"";
                    $fullLocattions=[$subThoroughfare,$thoroughfare, $subLocality,$locality, $subAdministrativeArea,$administrativeArea, $administrativeArea];
                    $fullLocattions=array_filter($fullLocattions);
                    $fullLocattions=array_unique($fullLocattions);
                    $location=join(", ",$fullLocattions);
                    $location.='.';
                }

                $takeAttendance = BpAttendance::create([
                    "bp_id"=> $bpId ? $bpId:0,
                    "location"=> $location,
                    "location_details"=>json_encode($locationDetails, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE),
                    "selfi_pic"=> $SelfiPic,
                    "date"=> $attendance_date_time,
                    "remarks"=> $remarks ? $remarks : 1,
                    "in_status"=> $inStatus ? $inStatus : "-",
                    "out_status"=> $outStatus ? $outStatus : "-",
                    "status"=> $request->input('status') ? $request->input('status') : "P",
                    "comments"=> $request->input('comments') ? $request->input('comments') : "Good"
                ]);

                if($takeAttendance) {
                    Log::info('BP Attendance Got Taken');
                    return response()->json(["message"=>$responseMessage],200);
                } else {
                    Log::warning('BP Attendance Got Taken Failed->OutSourceApiController->bpAttendance');
                    return response()->json(apiResponses(400),400);
                }
            }
            Log::error('Request Invalid BP Id For Attendance ->OutSourceApiController->bpAttendance');
            return response()->json(apiResponses(406),406);
        }
        else 
        {
            return response()->json(apiResponses(401),401);
        }
    }

    public function bpAttendanceReport(Request $request)
    {
        $headerAuth     = $request->header('Authorization'); 
        $token          = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus   = $this->verifyApiAuth($headerAuth,$token);
        
        if(isset($verifyStatus) && $verifyStatus === true) {
            $bpId      = 0;
            if($request->bpId) {
                $bpId  = $request->bpId;
            }
            
            $reqSdate       = date($request->startDate);
            $reqEdate       = date($request->endDate);
            if(isset($bpId) && $bpId > 0) {
                $attendanceList = DB::table('view_bp_attendance_report')
                ->where('id',$bpId)
                ->whereBetween(DB::raw('DATE(date_time)'),[$reqSdate,$reqEdate])
                ->get();
    
                if(isset($attendanceList) && $attendanceList->isNotEmpty()) {
                    Log::info('Get Attendance List By Apps');
                    return response()->json($attendanceList,200);
                } else {
                    Log::warning('Attendance List Not Found By Apps Request ->OutSourceApiController->bpAttendanceReport');
                    return response()->json(apiResponses(404),404);
                }
            }
            Log::error('Request Invalid BP Id For Attendance ->OutSourceApiController->bpAttendanceReport');
            return response()->json(apiResponses(406),406);
        }
        else 
        {
            return response()->json(apiResponses(401),401);
        }

    }

    public function getLeaveType(Request $request)
    {
        $headerAuth     = $request->header('Authorization'); 
        $token          = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus   = $this->verifyApiAuth($headerAuth,$token);
        
        if(isset($verifyStatus) && $verifyStatus === true) {

            $getAll = DB::table('leave_types')
            ->select('id','name')
            ->where('status',1)
            ->get();

            if(isset($getAll) && !empty($getAll)) {
                return response()->json($getAll,200);
            }
            else {
                return response()->json(apiResponses(404),404);
            }
        }
        else 
        {
            return response()->json(apiResponses(401),401);
        }
    }

    public function getLeaveReason(Request $request)
    {
        $headerAuth     = $request->header('Authorization'); 
        $token          = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus   = $this->verifyApiAuth($headerAuth,$token);
        
        if(isset($verifyStatus) && $verifyStatus === true) {

            $getAll = DB::table('leave_categories')->get();

            if(isset($getAll) && !empty($getAll)) {
                return response()->json($getAll,200);
            }
            else {
                return response()->json(apiResponses(404),404);
            }
        }
        else 
        {
            return response()->json(apiResponses(401),401);
        }
    }

    public function bpApplyLeave(Request $request)
    {
        $jsonData = "";
        $requestInput = $request->input();
        foreach($requestInput as $key=>$allval)
        {
            foreach($allval as $k=>$val) {
                $jsonData = "[".$k."]"; //json_encode($k, JSON_FORCE_OBJECT);//
            }
        }

        $people= json_decode($jsonData, true);
        $inputValue = [];
        for($i=0;$i<count($people); $i++){
            $inputValue[] = $people[$i]["value"];
        }
        $headerAuth     = $request->header('Authorization'); 
        $token          = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus   = $this->verifyApiAuth($headerAuth,$token);
        
        if(isset($verifyStatus) && $verifyStatus === true) {
            
            $leaveDate = "";
            $leaveTime = "";
            if(isset($inputValue[4]) && !empty($inputValue[4]))
            {
                $str = $inputValue[4];
                $dateArray = explode("T",$str);
                $getDate   = $dateArray[0];
                $timeArray = explode(".",$dateArray[1]);
                $leaveTime   = date("h:i a",strtotime($timeArray[0]));
            }
            
            if(isset($inputValue[2]) && !empty($inputValue[2]))
            {
                $str = $inputValue[2];
                $dateArray = explode("T",$str);
                $getDate   = $dateArray[0];
                $leaveDate = date('Y-m-d',strtotime($getDate));
            }
            
            $apply_date = date('Y-m-d H:i:s');
            $start_date = date('Y-m-d');
            
            $bpId       =   $inputValue[0];
            $leaveType  =   $inputValue[1];
            $startDate  =   $leaveDate ? $leaveDate:date('Y-m-d');
            $totalDay   =   $inputValue[3];
            $startTime  =   $leaveTime ? $leaveTime:date('h:i a');
            $reason     =   $inputValue[5];

            if(isset($bpId) && $bpId > 0) {

                $checkLeave = DB::table('bp_leaves')
                ->where('bp_id',$bpId)
                ->where('leave_type',$leaveType)
                ->where('start_date',$startDate)
                ->where('total_day',$totalDay)
                ->first();

                if($checkLeave)
                {
                    $returnData = [
                        "apply_date"=>$startDate
                    ];
                    return response()->json(['message'=>'Leave All Ready Taken','code'=>203],203);
                }
                else
                {
                    $bpLeave = DB::table('bp_leaves')->insert([
                        "bp_id"=> $bpId ? $bpId:0,
                        "apply_date"=>$apply_date,
                        "leave_type"=> $leaveType ? $leaveType:0,
                        "start_date"=> $startDate,
                        "total_day"=> $totalDay ? $totalDay:0,
                        "start_time"=> $startTime,
                        "reason"=> $reason ? $reason:"",
                        "status"=>"Pending"
                    ]);

                    if(isset($bpLeave) && !empty($bpLeave)) {
                        Log::info('Got BP Leave By Apps Success');
                        return response()->json(['message'=>'Your Request Has Been Received Successfully','code'=>200],200);
                    }
                    else {
                        Log::warning('Got BP Leave By Apps Failed');
                        return response()->json(apiResponses(404),404);
                    }
                }
            }
            return response()->json(apiResponses(406),406);
        }
        else 
        {
            return response()->json(apiResponses(401),401);
        }
    }

    public function bpLeaveReport(Request $request)
    {
        $headerAuth     = $request->header('Authorization'); 
        $token          = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus   = $this->verifyApiAuth($headerAuth,$token);
        
        if(isset($verifyStatus) && $verifyStatus === true) {

            $bpId      = 0;
            if($request->bpId) {
                $bpId  = $request->bpId;
            } 

            $reqSdate       = $request->startDate;
            $reqEdate       = $request->endDate;

            if(isset($bpId) && $bpId > 0) {
                $leaveList = DB::table('view_bp_leave_report')
                ->orWhere(function($query) use($reqSdate, $reqEdate, $bpId){
                    /*if ($reqSdate && $reqEdate) {
                        if ($bpId > 0) {
                            $query->whereBetween('start_date',[$reqSdate,$reqEdate]);
                            $query->where('bp_id',$bpId);
                        } else {
                            $query->orWhereBetween('start_date',[$reqSdate,$reqEdate]);
                        }                    
                    }*/
                    
                    if ($bpId > 0) {
                        $query->where('bp_id',$bpId);
                    }
                })
                ->orderBy('id','desc')
                ->get();

                if(isset($leaveList)) {
                    Log::info('Get BP Leave List By Apps');
                    return response()->json($leaveList,200);
                }
                else {
                    Log::info('BP Leave List Not Found By Apps');
                    return response()->json(apiResponses(404),404);
                }
            }
            Log::error('Request Invalid BP Id For Leave Report ->OutSourceApiController->bpLeaveReport');
            return response()->json(apiResponses(406),406);
        }
        else 
        {
            return response()->json(apiResponses(401),401);
        }
    }

    public function GetByInfo($methodName,$terms,Request $request)
    {
        $headerAuth = $request->header('Authorization');
        if($headerAuth) {
            if ($tokenFetch = JWTAuth::parseToken()->authenticate()) {
                $token = str_replace("Bearer ", "", $request->header('Authorization'));
                if($token) 
                {
                    if(isset($methodName) && $methodName == 'GetUserById') {

                        $getResult = User::find($terms);

                        if($getResult){
                            return response()->json(['data'=>$getResult,'status'=>'Yes Found']);
                        } else {
                            return response()->json(['data'=>"",'status'=>'Not Found']);
                        }
                    }
                    else if(isset($methodName) && $methodName == 'GetRetailerById') {

                        $getResult = Retailer::find($terms);

                        if($getResult){
                            return response()->json(['data'=>$getResult,'status'=>'Yes Found']);
                        } else {
                            return response()->json(['data'=>"",'status'=>'Not Found']);
                        }
                    }
                    else if(isset($methodName) && $methodName == 'GetDealerById') {
                        
                        $getResult = DealerInformation::find($terms);

                        if($getResult){
                            return response()->json(['data'=>$getResult,'status'=>'Yes Found']);
                        } else {
                            return response()->json(['data'=>"",'status'=>'Not Found']);
                        }
                    }
                    else if(isset($methodName) && $methodName == 'GetEmployeeById') {
                        
                        $getResult = Employee::find($terms);

                        if($getResult){
                            return response()->json(['data'=>$getResult,'status'=>'Yes Found']);
                        } else {
                            return response()->json(['data'=>"",'status'=>'Not Found']);
                        }
                    }
                    else if(isset($methodName) && $methodName == 'GetProductById') {
                        
                        $getResult = Products::find($terms);

                        if($getResult){
                            return response()->json(['data'=>$getResult,'status'=>'Yes Found']);
                        } else {
                            return response()->json(['data'=>"",'status'=>'Not Found']);
                        }
                    }
                    else if(isset($methodName) && $methodName == 'GetProductByModel') {
                        
                        $getResult = Products::where('product_model','like','%'.$terms.'%');

                        if($getResult){
                            return response()->json(['data'=>$getResult,'status'=>'Yes Found']);
                        } else {
                            return response()->json(['data'=>"",'status'=>'Not Found']);
                        }
                    }
                    else if(isset($methodName) && $methodName == 'GetBrandPromoterById') {
                        
                        $getResult = BrandPromoter::find($terms);
                        
                        if($getResult){
                            return response()->json(['data'=>$getResult,'status'=>'Yes Found']);
                        } else {
                            return response()->json(['data'=>"",'status'=>'Not Found']);
                        }
                    }
                    else 
                    {
                        return response()->json(['error'=>'Data Not Found','status'=>'fail','code'=>404]);
                    }
                }
                else 
                {
                    return response()->json(['error'=>'Unauthorized access','status'=>'fail','code'=>401]);
                }             
            } 
            else 
            {
                return response()->json(['error'=>'token has been expired or revoked','status'=>'fail','code'=>401]);
            }
        } 
        else 
        {
            return response()->json(['error'=>'Unauthorized access','status'=>'fail','code'=>401]);
        }
        //return response()->json($responseArray);
    }
    
    public function GetSalesProduct(Request $request)
    {
        $headerAuth     = $request->header('Authorization'); 
        $token          = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus   = $this->verifyApiAuth($headerAuth,$token);
        
        if(isset($verifyStatus) && $verifyStatus === true){

            $DelarDistributionModel = new DelarDistribution;
            $DelarDistributionModel->setConnection('mysql2');

            $bpId      = 0;
            $retailId  = 0;
            $groupId   = 0;

            if($request->input('bp_id')) {
                $bpId           = $request->input('bp_id');
                $groupId        = 1;

            } else {
                $retailId       = $request->input('retailer_id');
                $groupId        = 2;
            }

            $customer_name  = $request->input('customer_name');
            $customer_phone = $request->input('customer_phone');
            $itemList       = $request->input('list');

            if(is_array($itemList) && !empty($itemList)) {

                $imeProductStatus  = [];
                $imeProductResult  = "";
                $saleStatus        = false;

                $sale_data = "";

                $saleId = "";
                foreach ($itemList as $lists) {
                    $getImeResult = $DelarDistributionModel::
                    where('barcode',$lists['ime_number'])
                    ->orWhere('barcode2',$lists['ime_number'])
                    ->first();

                    if(isset($getImeResult)) 
                    {
                        $productStatus   = $getImeResult['status'];
                        $productMasterId = $getImeResult['product_master_id'];

                        if($productStatus == 1 && $productMasterId > 0) {
                            $imeProductResult = DB::table('view_product_master')
                            ->where('product_master_id',$productMasterId)
                            ->first();
                            
                        

                            if($imeProductResult) {
                                //$imeProductStatus[] = true;
                                if($saleStatus === false) {
                                    
                                    $ClientPic = "";
                                    if($request->hasFile('photo')) {
                                        /*
                                        $image = $request->file('photo');
                                        $filename = time().'.'.$image->getClientOriginalExtension();
                                        $destinationPath = public_path('/upload/client');
                                        $ClientPic = $filename;
                                        /*
                                        $img = Image::make($image->path());
                                        $img->resize(100, 100, function ($constraint) {
                                        $constraint->aspectRatio();
                                        })->save($destinationPath.'/'.$filename);
                                        */
                                        
                                        $getPhoto = $request->file('photo');
                                        $filename = time().'.'.$getPhoto->getClientOriginalExtension();
                                        $destinationPath = public_path('/upload/client');
                                        $success = $getPhoto->move($destinationPath, $filename);
                                        
                                        $ClientPic = $filename;
            
            
                                    }
                                    

                                    Sale::create([
                                        "customer_name"=> $request->input('customer_name'),
                                        "customer_phone"=>  $request->input('customer_phone'),
                                        "bp_id"=> $bpId,
                                        "retailer_id"=> $retailId,
                                        "sale_date"=>date('Y-m-d'),
                                        "photo"=> $ClientPic,
                                        "status"=>0
                                    ]);

                                    $saleId = DB::getPdo()->lastInsertId();

                                    $saleStatus = true;

                                }

                                if(!empty($saleId)) {
                                    SaleProduct::create([
                                        "sales_id"=>$saleId,
                                        "ime_number"=> $lists['ime_number'],
                                        "product_master_id"=> $productMasterId,
                                        "product_id"=> $imeProductResult->product_id,
                                        "product_code"=>  $imeProductResult->product_code,
                                        "product_type"=> $imeProductResult->product_type,
                                        "product_model"=> $imeProductResult->product_model,
                                        "category"=> $imeProductResult->category2,
                                        "mrp_price"=> $imeProductResult->mrp_price,
                                        "msdp_price"=> $imeProductResult->msdp_price,
                                        "msrp_price"=> $imeProductResult->msrp_price,
                                        "sale_price"=> $lists['price'],
                                        "sale_qty"=> $lists['qty'],
                                        "bp_id"=> $bpId,
                                        "retailer_id"=> $retailId,
                                        "product_status"=>0 //Sold Order
                                    ]);
                                    //Ime Database Product Status Update Start
                                    $DelarDistributionModel::
                                    where('barcode',$lists['ime_number'])
                                    ->orWhere('barcode2',$lists['ime_number'])
                                    ->update([
                                        "status"=>0,
                                    ]);
                                }

                                $sale_data = [
                                    "sale_id"=>$saleId,
                                    "bp_id"=> $bpId,
                                    "retailer_id"=> $retailId,
                                    "sale_date"=>date('Y-m-d'),
                                    "customer_name"=> $request->input('customer_name'),
                                    "customer_phone"=>  $request->input('customer_phone')
                                ];

                                ///////////////// Incentive Calculation Start ////////////////
                                $saleQty        = $lists['qty'];
                                $saleId         = $saleId;
                                $sale_date      = date('d-m-Y');

                                $incentiveLists = DB::table('incentives')
                                ->where('incentive_group',$groupId)
                                ->get();

                                foreach($incentiveLists as $incentive)
                                {
                                    $insertStatus       = false;
                                    $getModelId         = json_decode($incentive->product_model,TRUE);
                                    $getIncentiveType   = json_decode($incentive->incentive_type,TRUE);
                                    $getZone            = json_decode($incentive->zone,TRUE);                                   
                                    if(in_array($productMasterId, $getModelId)) {

                                        $start_date = $incentive->start_date;
                                        $end_date   = $incentive->end_date;
                                        $minQty     = $incentive->min_qty;

                                        $totalSaleQty = DB::table('view_sales_reports')
                                        ->where('product_master_id',$productMasterId)
                                        ->whereBetween('sale_date',[$start_date,$end_date])
                                        ->sum('view_sales_reports.sale_qty');

                                        if($totalSaleQty >= $minQty)
                                        {
                                            $bpId = 0;
                                            $retailer_id = 0;
                                            
                                            if($groupId == 1 && in_array('bp', $getIncentiveType)){
                                                $bpId = $bpId;
                                                $retailer_id = 0;
                                                $insertStatus = true;
                                            }
                                            else if($groupId == 2 && in_array($retailer_id, $getIncentiveType))
                                            {
                                                $bpId = 0;
                                                $retailer_id = $retailId;
                                                $insertStatus = true;
                                            }
                                            

                                            if($insertStatus === true)
                                            {
                                                $insertData = array(
                                                    "ime_number"=>$lists['ime_number'],
                                                    "sale_id" =>$saleId, 
                                                    "bp_id" =>$bpId,
                                                    "retailer_id"=>$retailId,
                                                    "incentive_for"=>$retailId,
                                                    "incentive_title"=>$incentive->incentive_title,
                                                    "product_model"=>$imeProductResult->product_model,
                                                    "zone"=>$incentive->zone,
                                                    "incentive_amount"=>$incentive->incentive_amount,
                                                    "incentive_min_qty"=>$incentive->min_qty,
                                                    "incentive_sale_qty"=>$saleQty,
                                                    "start_date"=>$incentive->start_date,
                                                    "end_date"=>$incentive->end_date,
                                                    "incentive_status"=>$incentive->status,
                                                );

                                            }
                                        }

                                    }
                                }
                                //////////////////// Incentive Calculation End //////////////////
                            }
                            else
                            {

                                $saleRemove = DB::table('sales')
                                ->where('id',$saleId)
                                ->delete();

                                $saleItemsRemove = DB::table('sale_products')
                                ->where('sales_id',$saleId)
                                ->delete();

                                //Ime Database Product Status Update Start
                                $DelarDistributionModel::
                                where('barcode',$lists['ime_number'])
                                ->orWhere('barcode2',$lists['ime_number'])
                                ->update([
                                    "status"=>1,
                                ]);

                                $notFoundIme[] = $lists['ime_number'];
                                //$response = apiResponses(301,$notFoundIme);//Data Not Found

                                return response()->json(["message"=> "Ime Not Found.Please Contact Your Authority","not_found_ime"=>$notFoundIme,"code"=>404],404);
                            }
                        }
                        else 
                        {
                            return response()->json(apiResponses(422,'Product All Ready Sold'),422);
                        }
                    }
                    else
                    {
                        return response()->json(apiResponses(422,'Invalid Ime Number'),422);
                    }

                }

                if(isset($sale_data) && !empty($sale_data))
                {
                    return response()->json($sale_data,200);//Success
                }
                
            }
            else {
                Log::warning('Bad Request Get Sales Product ->OutSourceApiController->GetSalesProduct');
                return response()->json(apiResponses(400),400);//Bad Request
            }
         
        } else {
            return response()->json(apiResponses(401),401);
        }
    }

    public function getCategoryList(Request $request) {
        $headerAuth = $request->header('Authorization'); 
        $token = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus = $this->verifyApiAuth($headerAuth,$token);
        
        if (isset($verifyStatus) && $verifyStatus === true) {
            $results = Category::orderBy('name','asc')->get();

            if (isset($results) && !empty($results)) {
                $productList['msg'] = "Total ".count($results)." Category Given.";
                $productList['categoryList'] = $results;
                Log::info('Get Category List By Apps');
                return response()->json($productList,200);
            } else {
                Log::warning('Category List Not Found->OutSourceApiController->getCategoryList');
                return response()->json(apiResponses(404),404);
            }
        } else {
            return response()->json(apiResponses(401),401);
        }
    }

    public function getProductList(Request $request) {
        $headerAuth = $request->header('Authorization'); 
        $token = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus = $this->verifyApiAuth($headerAuth,$token);
        
        if (isset($verifyStatus) && $verifyStatus === true) {
            $results = DB::table('view_product_master')
                ->select('product_master_id as id','category_id','product_model as name','product_model as model','product_code as code','product_type as type','msrp_price as price','category2 as group','stock_qty')
                ->orderBy('name','asc')
                ->get();

            if (isset($results) && !empty($results)) {
                $productList['msg'] = "Total ".count($results)." Products Given.";
                $productList['productList'] = $results;
                Log::info('Get Product List By Apps');
                return response()->json($productList,200);
            } else {
                Log::warning('Product List Not Found->OutSourceApiController->getProductList');
                return response()->json(apiResponses(404),404);
            }
        } else {
            return response()->json(apiResponses(401),401);
        }
    }
    
    public function getPromoOffer(Request $request) {
        $headerAuth = $request->header('Authorization'); 
        $token = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus = $this->verifyApiAuth($headerAuth,$token);
        
        if (isset($verifyStatus) && $verifyStatus === true) {
            $baseUrl = Config::get('app.url');
            $month_Sdate = date('Y-m-01');
            $month_Edate = date('Y-m-t');
            $currentDate = date('Y-m-d');
            $userId = auth('api')->user()->id;
            $userExists = DB::table('users')->where('id',$userId)->first();
            $bpId = 0;
            $retailId = 0;
            
            if ($userExists->bp_id > 0 && $userExists->bp_id != NULL) {
                $bpId = $userExists->bp_id;
            } else if ($userExists->retailer_id > 0 && $userExists->retailer_id != NULL) {
                $retailId = $userExists->retailer_id;
            }

            if ($bpId && $bpId > 0) {
                $promoOfferList = DB::table('promo_offers')
                    ->select('title','offer_for','sdate','edate','offer_pic','photo')
                    ->where('status','=',1)
                    ->where(function($sql_query){
                        $sql_query->where('offer_for','=','all');
                        $sql_query->orWhere('offer_for','=','bp');
                    })
                    ->where('edate','>=',$currentDate)
                    ->get();
                
                $promoList = [];
                $baseUrl = Config::get('app.url');
                foreach($promoOfferList as $k=>$row) {
                    $promoList[$k]['title'] = $row->title;
                    $promoList[$k]['offer_for'] = $row->offer_for;
                    $promoList[$k]['sdate'] = $row->sdate;
                    $promoList[$k]['edate'] = $row->edate;
                    // $promoList[$k]['offer_pic'] = $baseUrl.'public/upload/'.$row->photo;
                    $promoList[$k]['offer_pic'] = asset('public/upload/'.$row->photo);
                }
                
                return response()->json($promoList,200);
            } else {                
                $promoOfferList = DB::table('promo_offers')
                    ->select('title','offer_for','sdate','edate','offer_pic','photo')
                    ->where('status','=',1)
                    ->where(function($sql_query){
                        $sql_query->where('offer_for','=','all');
                        $sql_query->orWhere('offer_for','=','retailer');
                    })
                    ->where('edate','>=',$currentDate)
                    ->get();
                
                $promoList = [];
                $baseUrl = Config::get('app.url');
                foreach ($promoOfferList as $k=>$row) {
                    $promoList[$k]['title'] = $row->title;
                    $promoList[$k]['offer_for'] = $row->offer_for;
                    $promoList[$k]['sdate'] = $row->sdate;
                    $promoList[$k]['edate'] = $row->edate;
                    // $promoList[$k]['offer_pic'] = $baseUrl.'public/upload/'.$row->photo;
                    $promoList[$k]['offer_pic'] = asset('public/upload/'.$row->photo);
                }                
                return response()->json($promoList,200);
            }
            return response()->json(apiResponses(404),404);
        } else {
            return response()->json(apiResponses(401),401);
        }
    }

    public function messageStore(Request $request)
    {
        $headerAuth     = $request->header('Authorization'); 
        $token          = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus   = $this->verifyApiAuth($headerAuth,$token);
        
        if(isset($verifyStatus) && $verifyStatus === true) {

            $userId     = auth('api')->user()->id;
            $userExists = DB::table('users')
            ->where('id',$userId)
            ->first();
    
            $phone = "";
            $zone  = "";
            
            if($userExists->bp_id > 0 && $userExists->bp_id != NULL) {
                $bpInfo = DB::table('brand_promoters')
                ->where('id',$userExists->bp_id)
                ->first();

                $dealerCode     = $bpInfo->distributor_code;
                $alternetCode   = $bpInfo->distributor_code2;

                $dealerZoneName = DB::table('dealer_informations')
                ->where('dealer_code',$dealerCode)
                ->where('alternate_code',$alternetCode)
                ->value('zone');

                $phone   = $bpInfo->bp_phone;
                $zone    = $dealerZoneName;
            }
            elseif($userExists->retailer_id > 0 && $userExists->retailer_id != NULL) {
                $retailerInfo = DB::table('retailers')
                ->where('id',$userExists->retailer_id)
                ->first();

                $RetailerZone= DB::table('zones')
                ->where('id','=',$retailerInfo->zone_id)
                ->value('zone_name');

                $phone   = $retailerInfo->phone_number;
                $zone    = $RetailerZone;
            }
            elseif($userExists->employee_id > 0 && $userExists->employee_id != NULL) {
                $employeeInfo = DB::table('employees')
                ->where('employee_id',$userExists->employee_id)
                ->first();

                $phone   = $employeeInfo->mobile_number;
                $zone    = "";
            }

            $messageId      = $request->input('message_id');
            $CheckStatus    = AuthorityMessage::where('id',$messageId)->first();

            //$groupId = $CheckStatus['message_group_id'] ? $CheckStatus['message_group_id']:0;
            $messageStatus  = 1;
            
            if(isset($CheckStatus) && $CheckStatus['who_reply'] == $userId && $CheckStatus['id'] == $messageId){
                $messageStatus = 0;
            }
            
            
            /*if(isset($CheckStatus) && $CheckStatus['id'] == $messageId) {
                $messageStatus = 1;
            }else {
                $messageStatus  = 0;
            }*/

            if(isset($CheckStatus) && !empty($CheckStatus)) {
                
                if($CheckStatus['bnm'] == 2) {
                    $updateBnmStatus = AuthorityMessage::where('id',$messageId)
                    ->update([
                        "bnm"=>1
                    ]); 
                }
                $AddMessage = AuthorityMessage::create([
                    "message_group_id"=>$CheckStatus['message_group_id'],
                    "message"=>$request->input('message'),
                    "date_time"=>date('Y-m-d H:i:s'),
                    "bnm"=>0,
                    "status"=>$messageStatus,
                    'reply_for'=> $CheckStatus['reply_for'], //$messageId ? $messageId:0,
                    'who_reply'=> $userId ? $userId:0,
                    "reply_user_name"=>$userExists->name,
                    "phone"=>$phone,
                    "zone"=>$zone
                ]);
                
                $updateBnmStatus = AuthorityMessage::where('id',$messageId)
                ->update([
                    'reply_for'=> $CheckStatus['message_group_id']
                ]);
                
                Log::info('Authority Message Save Success');
                return response()->json(['message'=>'success','code'=>200],200);
            } 
            else 
            {
                $AddMessage = AuthorityMessage::create([
                    "message_group_id"=>0,
                    "message"=>$request->input('message'),
                    "date_time"=>date('Y-m-d H:i:s'),
                    "bnm"=>2,
                    "status"=>0,//$messageStatus
                    'reply_for'=>0,
                    'who_reply'=>$userId ? $userId:0,
                    "reply_user_name"=>$userExists->name,
                    "phone"=>$phone,
                    "zone"=>$zone
                ]);
                
                $lastInsertId = DB::getPdo()->lastInsertId();
                $updateBnmStatus = AuthorityMessage::where('id',$lastInsertId)
                ->update([
                    "reply_for"=>$lastInsertId,
                    "message_group_id"=>$lastInsertId,
                ]);
                
                Log::info('Authority Message Save Success');
                return response()->json(['message'=>'success','code'=>200],200);
            }
        }
        return response()->json(apiResponses(404),404);
    }

    public function replyMessage(Request $request)
    {
        $headerAuth     = $request->header('Authorization'); 
        $token          = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus   = $this->verifyApiAuth($headerAuth,$token);
        
        if(isset($verifyStatus) && $verifyStatus === true) {
            /*
            $bpId      = 0;
            $retailId  = 0;

            if($request->bpId) {
                $bpId       = $request->bpId;
            } else {
                $retailId   = $request->retailId;
            }
            */
            //////////////////////////////////////
            $userId     = auth('api')->user()->id;
            $userExists = DB::table('users')
            ->where('id',$userId)
            ->first();

            
            $phone = "";
            $zone  = "";
            
            if($userExists->bp_id > 0 && $userExists->bp_id != NULL) {
                $bpInfo = DB::table('brand_promoters')
                ->where('id',$userExists->bp_id)
                ->first();

                $dealerCode     = $bpInfo->distributor_code;
                $alternetCode   = $bpInfo->distributor_code2;

                $dealerZoneName = DB::table('dealer_informations')
                ->where('dealer_code',$dealerCode)
                ->where('alternate_code',$alternetCode)
                ->value('zone');

                $phone   = $bpInfo->bp_phone;
                $zone    = $dealerZoneName;
            }
            elseif($userExists->retailer_id > 0 && $userExists->retailer_id != NULL) {
                $retailerInfo = DB::table('retailers')
                ->where('retailer_id',$userExists->retailer_id)
                ->first();

                $RetailerZone= DB::table('zones')
                ->where('id','=',$retailerInfo->zone_id)
                ->value('zone_name');

                $phone   = $retailerInfo->phone_number;
                $zone    = $RetailerZone;
            }
            elseif($userExists->employee_id > 0 && $userExists->employee_id != NULL) {
                $employeeInfo = DB::table('employees')
                ->where('id',$userExists->employee_id)
                ->first();

                $phone   = $employeeInfo->mobile_number;
                $zone    = "Others";
            }

            $messageId      = $request->input('message_id');
            $CheckStatus    = AuthorityMessage::where('id',$messageId)->first();

            if(isset($CheckStatus))
            {
                if($CheckStatus['bnm'] == 2) {
                    AuthorityMessage::where('id',$messageId)
                        ->update([
                            "bnm"=>1
                        ]);
                }
                
                $AddMessage = AuthorityMessage::create([
                    "message"=>$request->input('message'),
                    "date_time"=>date('Y-m-d H:i:s'),
                    "bnm"=>0,
                    "status"=>1,
                    'reply_for'=>$messageId,
                    //'who_reply'=>$request->input('retailer_id')
                    'who_reply'=>$userId ? $userId:0,
                    "reply_user_name"=>$userExists->name,
                    "phone"=>$phone ? $phone:0,
                    "zone"=>$zone ? $zone:0
                ]);
                
                $updateBnmStatus = AuthorityMessage::where('id',$messageId)
                ->update([
                    "reply_for"=>$CheckStatus['message_group_id']
                ]);
            
            
                Log::info('Authority Message Reply Success');
                return response()->json(['message'=>'success','code'=>200],200);
            }
            Log::warning('Invalid Authority Message');
            return response()->json(apiResponses(401),401);
        }
        else 
        {
            return response()->json(apiResponses(401),401);
        }
    }

    public function getMessageList(Request $request)
    {
        $headerAuth     = $request->header('Authorization'); 
        $token          = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus   = $this->verifyApiAuth($headerAuth,$token);
        
        if(isset($verifyStatus) && $verifyStatus === true) {

            $userId     = auth('api')->user()->id;
            $userExists = DB::table('users')
            ->where('id',$userId)
            ->first();

            $messageId   = $request->messageId;

            $responseMessage = [];

            /*$AuthorFirstMessage = [
                "id"=>3,
                "message"=>"Chcekc Message",
                "author"=>"Sayed",
            ];*/

            $FirstMessage = DB::table('authority_messages')
            ->select('id','message','date_time','reply_user_name','who_reply')
            ->where('id', $messageId)
            ->where('status', 0)
            ->orderBy('id','asc')
            ->first();

            $AuthorFirstMessage = [
                "id"=>$FirstMessage->id,
                "message"=>$FirstMessage->message,
                "dateTime"=>$FirstMessage->date_time,
                "isRead"=>true,
                "isSent"=>true,
                "author"=>[
                   "id"=>$FirstMessage->who_reply,
                   "name"=>$FirstMessage->reply_user_name
                ],
            ];

            $MessageList = DB::table('authority_messages')
            ->select('message','date_time','status','reply_user_name','who_reply')
            ->where('reply_for', $messageId)
            ->where('bnm','=',0)
            ->orderBy('id','asc')
            ->get();

            $replyMessage = [];
            foreach($MessageList as $row)
            {
                $newMessage=[
                    "message"=>$row->message,
                    "dateTime"=> $row->date_time,
                    "isRead"=>true,
                    "isSent"=>true,
                    "author"=>[
                    "id"=>$row->who_reply,
                    "name"=>$row->reply_user_name
                ]];
                array_push($replyMessage, $newMessage);
            }

            $responseMessage = $AuthorFirstMessage;
            $responseMessage['replies'] = $replyMessage;
            
            if($responseMessage) {
                Log::info('Get Authority Message List');
                return response()->json($responseMessage,200);
            } else {
                Log::warning('Authority Message List Not Found');
                return response()->json($responseMessage,200);
            }
        }
        else 
        {
            return response()->json(apiResponses(401),401);
        }
    }
    
    public function getMessageListByUserId(Request $request)
    {
        $headerAuth     = $request->header('Authorization'); 
        $token          = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus   = $this->verifyApiAuth($headerAuth,$token);
        
        if(isset($verifyStatus) && $verifyStatus === true) {

            $userId     = auth('api')->user()->id; //exit(); //1 
            $userExists = DB::table('users')
            ->where('id',$userId)
            ->first();

            $last_msg_list = DB::table('authority_messages as tab1')
                ->select('tab1.*')
                ->leftJoin('authority_messages as tab2',function($join_query) {
                    $join_query->on('tab2.reply_for','=','tab1.reply_for');
                    $join_query->on('tab2.id','>','tab1.id');
                })
                ->whereNull('tab2.id')
                ->where('tab1.bnm','=',0)
                ->where('tab1.reply_for','=',\DB::raw("(SELECT reply_for FROM authority_messages WHERE who_reply=".$userId." AND reply_for=tab1.reply_for ORDER BY id DESC LIMIT 1)"))
                ->groupBy('tab1.who_reply','tab1.id');
                //->orderBy('tab1.id','desc');
                
            

            $UserMessageList = DB::table('authority_messages')
                ->select('*')
                ->where('bnm','=',2)
                ->where('who_reply','=',$userId)
                ->union($last_msg_list)
                ->orderBy('id','desc')
                ->get();

            $responseMessage = [];
            foreach($UserMessageList as $message)
            {
                $responseMessage[] = [
                    "id"=>$message->reply_for,
                    "message"=>$message->message,
                    "dateTime"=>$message->date_time,
                    "author"=>[
                       "id"=>$message->who_reply,
                       "name"=>$message->reply_user_name,
                       "phone"=>$message->phone ? $message->phone:"",
                       "zone"=>$message->zone ? $message->zone:""
                    ],
                ];
            }

            if($responseMessage) {
                Log::info('Get Message By User Id');
                return response()->json($responseMessage,200);
            } else {
                Log::warning('Message Not Found By User Id');
                return response()->json(apiResponses(404),404);
            }

        }
        else 
        {
            return response()->json(apiResponses(401),401);
        }
    }
    
    public function userProfileUpdate(Request $request)
    {
        $headerAuth     = $request->header('Authorization'); 
        $token          = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus   = $this->verifyApiAuth($headerAuth,$token);
        
        if(isset($verifyStatus) && $verifyStatus === true) 
        {
            $userId             = auth('api')->user()->id;
            $password           = $request->input('password');
            $confirm_password   = $request->input('confirm_password');

            $userExists = DB::table('view_check_login_user')
            ->where('id',$userId)
            ->first();
            
            $name 		= $userExists->name;
			$phone 		= ($userExists->brand_promoter_phone) ? $userExists->brand_promoter_phone : $userExists->retailer_phone;
			$password 	= $password;

            $updatePassword = $userExists->password;
            if($password === $confirm_password) {
                $updatePassword = Hash::make($password);

                if ($userExists) {
                    $UpdateUser = DB::table('users')
                    ->where('id',$userExists->id)
                    ->update([
                        "password"=>$updatePassword,
                        "author"=>$name."-".$userId,
                        "updated_at"=>date('Y-m-d h:i:s')
                    ]);
                    
                    if($UpdateUser)
					{
						$messageBody = "HI, ".$name.". Your Password Has Reset.User Name=".$phone." Password=".$password." Thanks, Walton";
						$requestData = array(
							'mobileNumber' => $phone,
							'message' =>$messageBody,
						);
						$postRequestData    = json_encode($requestData);
						
						$this->sms_send($postRequestData);
					}
                    Log::info('User Password  Update Success By Apps-(User ID-'.$userId.')');
                    return response()->json(['message'=>'success','code'=>200],200);
                }
            }
            
            Log::warning('User Password  Update Failed By Apps-(User ID-'.$userId.')');
            return response()->json(apiResponses(401,'Password & Confirme Password Not Match'),401);
        }
        else 
        {
            return response()->json(apiResponses(401),401);
        }
    }
    
    public function getBannerList(Request $request) {
        $headerAuth = $request->header('Authorization'); 
        $token = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus = $this->verifyApiAuth($headerAuth,$token);
        
        if (isset($verifyStatus) && $verifyStatus === true) {
            $getBannerList = DB::table('banners')
                ->select('banner_pic as photo','image_path')
                ->where('status',1)
                ->orderBy('id','desc')
                ->get();
            
            $bannerList = [];
            $baseUrl = URL::to('');
            if (isset($getBannerList) && $getBannerList->isNotEmpty()) {
                foreach ($getBannerList as $k=>$row){
                    $bannerList[$k]['photo'] = $row->photo;
                    $bannerList[$k]['image_path'] = asset($row->image_path);
                }
            }

            if ($bannerList) {
                Log::info('Get Banner List By Apps');
                return response()->json($bannerList,200);
            } else {
                Log::warning('Banner List Not Found By Apps');
                return response()->json(apiResponses(404),404);
            }
        } else {
            return response()->json(apiResponses(401),401);
        }
    }

    public function ModelWaiseSalesReport(Request $request) {
        // return $request->all();
        $headerAuth = $request->header('Authorization'); 
        $token = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus = $this->verifyApiAuth($headerAuth,$token);
        
        if (isset($verifyStatus) && $verifyStatus === true) {
            $retailId = $request->retailId;
            $startDate = $request->startDate;
            $endDate = $request->endDate;
            $responseArray = [];

            $salerInfo = DB::table('retailers')
                ->select('retailer_name','retailder_address','phone_number')
                ->where('id',$retailId)
                ->first();

            if ($salerInfo) {
                $productModelSalesList = DB::table('view_sales_product_reports')
                    ->select('sale_id','sale_product_id','retailer_id','product_model','sale_price')
                    ->selectRaw('sum(sale_qty) as saleQty')
                    ->where('retailer_id','=', $retailId)
                    ->whereBetween(DB::raw("DATE_FORMAT(sale_date,'%Y-%m-%d')"),[$startDate,$endDate])
                    ->groupBy('product_model')
                    ->orderBy('sale_id','asc')
                    ->get();
                
                $salerInfo->salesModelInfo = $productModelSalesList;

                Log::info('Get Model Waise Sales List');
                return response()->json($salerInfo,200);
            } else {
                Log::warning('Model Waise Sales List Not Found');
                return response()->json(apiResponses(404),404);
            }
        } else {
            return response()->json(apiResponses(401),401);
        }
    }

    public function topTenSalesReport(Request $request) {
        // return $request->all();
        $headerAuth = $request->header('Authorization'); 
        $token = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus = $this->verifyApiAuth($headerAuth,$token);
        
        if (isset($verifyStatus) && $verifyStatus === true) {
            $retailId = $request->retailId;
            $startDate = $request->startDate;
            $endDate = $request->endDate;

            $salerInfo = DB::table('retailers')
                ->select('retailer_name','retailder_address','phone_number')
                ->where('id',$retailId)
                ->first();

            if ($salerInfo) {
                $salesReports = DB::table('view_sales_product_reports')
                    ->select('product_model','sale_price')
                    ->selectRaw('sum(sale_qty) as saleQty')
                    ->where('retailer_id','=', $retailId)
                    ->whereBetween(DB::raw("DATE_FORMAT(sale_date,'%Y-%m-%d')"),[$startDate,$endDate])
                    ->groupBy('product_model')
                    ->orderBy('saleQty','desc')
                    ->limit(5)
                    ->get();

                $productName = array();
                $productPrice = array();
                $productQty = array();

                foreach ($salesReports as $report) {
                    array_push($productName, strval($report->product_model));
                    array_push($productPrice, (float)$report->sale_price);
                    array_push($productQty, (int)$report->saleQty);
                }

                // $salerInfo->salesModelInfo = $salesReports;

                Log::info('Get Model Waise Sales List');
                return response()->json(['salerInfo'=>$salerInfo,'productName'=>json_encode($productName),'productPrice'=>json_encode($productPrice),'productQty'=>json_encode($productQty)],200);
            } else {
                Log::warning('Model Waise Sales List Not Found');
                return response()->json(apiResponses(404),404);
            }
        } else {
            return response()->json(apiResponses(401),401);
        }
    }
    
    public function getRetailerStock(Request $request) {
        $headerAuth = $request->header('Authorization'); 
        $token = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus = $this->verifyApiAuth($headerAuth,$token);
        
        if (isset($verifyStatus) && $verifyStatus === true) {
            $userId = auth('api')->user()->id;
            $userExists = DB::table('users')->where('id',$userId)->first();
            $bpId = 0;
            $retailId = 0;
            $clientType = "";
            $clientInfo = "";
            $searchId = 0;
            $FocusModel = "";
            $categoryId = 0;            

            if ($userExists->bp_id > 0 && $userExists->bp_id != NULL) {
                $bpId = $userExists->bp_id;
                $clientInfo = DB::table('brand_promoters')->where('id','=',$bpId)->first();                
                $clientType = "retailer";
                $getRetailerInfo = DB::table('retailers')->where('retailer_id','=',$clientInfo->retailer_id)->first();
                if (isset($getRetailerInfo) && !empty($getRetailerInfo)) {
                    $searchId   =  $getRetailerInfo->phone_number;
                }
                $FocusModel = DB::table('bp_model_stocks')->where('bp_category_id','=',$clientInfo->category_id)->get();
                $categoryId = $clientInfo->category_id;
            } else if ($userExists->retailer_id > 0 && $userExists->retailer_id != NULL) {
                $retailId = $userExists->retailer_id;
                $clientInfo = DB::table('retailers')->where('id','=',$retailId)->first();
                $clientType = "retailer";
                $searchId = $clientInfo->phone_number;
                $FocusModel = DB::table('bp_model_stocks')->where('bp_category_id','=',$clientInfo->category_id)->get();
                $categoryId = $clientInfo->category_id;
            }
            $responseData = "";
            if (isset($clientType) && !empty($clientType) && isset($searchId)  && $searchId > 0) {
                $getCurlResponse = getData(sprintf(RequestApiUrl("GetStock"),$searchId,$clientType),"GET");
                $responseData = json_decode($getCurlResponse['response_data'],true);
                if (empty($responseData)) {
                    return response()->json(apiResponses(404),404);
                }
            } else {
                return response()->json(apiResponses(422,'Retailer Not Found'),422);
            }
            
            $getStockList = [];
            $userInfo = "";

            if (isset($responseData) && !empty($responseData)) {
                $userInfo = [
                    "dealer_name"=>$responseData[0]['DealerName'],
                    "dealer_address"=>$responseData[0]['RetailerAddress'],
                    "zone"=>$responseData[0]['DealerZone'],
                    "dealer_phone_number"=>$responseData[0]['DealerPhone']
                ];
                
                foreach ($responseData as $k=>$responseRow) {
                    $modelList = DB::table('bp_model_stocks')
                        ->select('id','bp_category_id','model_name','green','yellow','red')
                        ->where('bp_category_id',$categoryId)
                        ->where('model_name','=',$responseRow['Model'])
                        ->get();

                    if ($modelList->isNotEmpty()) {
                        foreach ($modelList as $key=>$row) {
                            $statusColor    = "#ff0000";//"Red";
                            if ($responseRow['StockQuantity'] >= $row->green) {
                                $statusColor = "#008000";//"Green";
                            } elseif($responseRow['StockQuantity'] >= $row->yellow && $responseRow['StockQuantity'] < $row->green) {
                                $statusColor = "#FFFF00";//"Yellow";
                            } elseif($responseRow['StockQuantity'] >= $row->red) {
                                $statusColor = "#ff0000";//"Red";
                            }                            
                            $getStockList[$k]['Model'] = $row->model_name;
                            $getStockList[$k]['Color'] = "";
                            $getStockList[$k]['Stock'] = $responseRow['StockQuantity'];
                            $getStockList[$k]['ColorCode'] = $statusColor;
                        }
                    } else {
                        $statusColor = "#ff0000";//"Red";
                        $stock = 0;
                        if ($responseRow['StockQuantity'] >= 2) {
                            $statusColor = "#008000";//"Green";
                            $stock =$responseRow['StockQuantity'];//2
                        } else if($responseRow['StockQuantity'] >= 1 && $responseRow['StockQuantity'] < 2) {
                            $statusColor = "#FFFF00";//"Yellow";
                            $stock =$responseRow['StockQuantity'];//1
                        } else if($responseRow['StockQuantity'] >= 0) {
                            $statusColor = "#ff0000";//"Red";
                            $stock =$responseRow['StockQuantity'];//0
                        }                        
                        $getStockList[$k]['Model'] = $responseRow['Model'];
                        $getStockList[$k]['Color'] = "";
                        $getStockList[$k]['Stock'] = $stock;
                        $getStockList[$k]['ColorCode'] = $statusColor;
                    }
                }
            }
        
            if (isset($responseData)) {
                $dealerInfo['stockList'] = $getStockList;
                return response()->json($getStockList,200);
            } else {
                Log::warning('Stock Not Found');
                return response()->json(apiResponses(404),404);
            }
        } else {
            return response()->json(apiResponses(401),401);
        }
    }

    public function getTopSellerList(Request $request) {
        $headerAuth = $request->header('Authorization'); 
        $token = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus = $this->verifyApiAuth($headerAuth,$token);
        
        if (isset($verifyStatus) && $verifyStatus === true) {            
            //Current Month Top Seller Searching
            $start_date = date('Y-m-01');
            $current_date = date('Y-m-d');

            $bpTopList = DB::table('view_sales_reports')
                ->select('bp_name as name','bp_phone as phone','bp_distric as distric',DB::raw('SUM(sale_qty) AS totalQty'),DB::raw('SUM(sale_price) AS totalPrice'))
                ->whereBetween(\DB::raw("DATE_FORMAT(sale_date,'%Y-%m-%d')"),[$start_date,$current_date])
                ->where('bp_id','>',0)
                ->groupBy('bp_id')
                ->orderBy('totalQty','desc')
                ->orderBy('totalPrice','desc')
                ->get();

            $retailerTopList = DB::table('view_sales_reports')
                ->select('retailer_name as name','retailer_phone_number as phone','retailer_distric as distric',DB::raw('SUM(sale_qty) AS totalQty'),DB::raw('SUM(sale_price) AS totalPrice'))
                ->whereBetween(\DB::raw("DATE_FORMAT(sale_date,'%Y-%m-%d')"),[$start_date,$current_date])
                ->where('retailer_id','>',0)
                ->groupBy('retailer_id')
                ->orderBy('totalQty','desc')
                ->orderBy('totalPrice','desc')
                ->get();
            $responseArray['bpTopList'] = $bpTopList;
            $responseArray['retailerTopList'] =  $retailerTopList;
            
            if ($responseArray) {
                Log::info('Get Top Seller List By Apps');
                return response()->json($responseArray,200);
            } else {
                Log::warning('Top Seller List Not Found By Apps');
                return response()->json(apiResponses(404),404);
            }
        } else {
            return response()->json(apiResponses(401),401);
        }
    }
    
    public function postIMEIdisputeNumber(Request $request)
    {
        $headerAuth     = $request->header('Authorization'); 
        $token          = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus   = $this->verifyApiAuth($headerAuth,$token);
        
        if(isset($verifyStatus) && $verifyStatus === true) 
        {
            $bpId      = 0;
            $retailId  = 0;

            if($request->input('bp_id')) {
                $bpId       = $request->input('bp_id');
                $getBpInfo  = BrandPromoter::select('bp_id','retailer_id')
                ->where('id','=',$bpId)
                ->first();
                $retailId   = $getBpInfo->retailer_id;
            } else {
                $retailId   = $request->input('retailer_id');
                //$bpId       = BrandPromoter::where('retailer_id','=',$retailId)->value('bp_id');
            }
            $imeNumber          = $request->input('imei_number');
            $description        = $request->input('description');
            $customer_name      = $request->input('customer_name');
            $customer_phone     = $request->input('customer_phone');
            
            
            $ClientPic = "";
            $destinationPath = "";
            if($request->hasFile('customer_photo')) {
                $getPhoto = $request->file('customer_photo');
                $filename = time().'.'.$getPhoto->getClientOriginalExtension();
                $destinationPath = public_path('/upload/client');
                $success = $getPhoto->move($destinationPath, $filename);
            
                $ClientPic = $filename;
            }
            
            //$baseUrl        = URL::to('');
            //$storagePath    = $baseUrl.'/storage/app/public/'.$ClientPic;

            $CheckStatus    = DB::table('imei_disputes')
            ->where('bp_id',$bpId)
            ->where('retailer_id',$retailId)
            ->where('imei_number',$imeNumber)
            ->first();

            $message = "";

            if($CheckStatus) 
            {
                $message = "Request All Ready Send";
            }
            else
            {
                $AddInfo = DB::table('imei_disputes')
                ->insert([
                    "bp_id"=>$bpId,
                    "retailer_id"=>$retailId,
                    "customer_name"=>$customer_name,
                    "customer_phone"=>$customer_phone,
                    "customer_photo"=>$ClientPic,
                    "imei_number"=>$imeNumber,
                    "description"=>$description,
                    "date"=>date('Y-m-d'),
                    'status'=>0, //0 =no reply,1 reply
                ]);
                $message = "success";
            }
            Log::info('IMEI Dispute Request Success');
            return response()->json(['message'=>$message,'code'=>200],200);
        }
        else 
        {
            return response()->json(apiResponses(401),401);
        }
    }
    
    public function getIMEIdisputeList(Request $request)
    {
        $headerAuth     = $request->header('Authorization'); 
        $token          = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus   = $this->verifyApiAuth($headerAuth,$token);
        
        if(isset($verifyStatus) && $verifyStatus === true) 
        {
            $bpId      = 0;
            $retailId  = 0;

            if($request->bpId) {
                $bpId           = $request->bpId;
                $getBpInfo      = BrandPromoter::select('bp_id','retailer_id')
                ->where('id','=',$bpId)
                ->first();
                
                $waltonbpId    = $getBpInfo->bp_id;
                $retailId      = $getBpInfo->retailer_id;
            } else {
                $retailId   = $request->retailId;
            }

            $disputeList    = DB::table('imei_disputes')
            //->select('imei_number','description','date','status')
            ->where(function($sql_query) use($bpId,$retailId) {
        		if($bpId > 0){
        			$sql_query->where('bp_id','=', $bpId);
        		}
        		if($retailId > 0){
        			$sql_query->where('retailer_id','=', $retailId);
        		}
        	})
            //->where('bp_id',$bpId)
            //->where('retailer_id',$retailId)
            ->get();
            
            $baseUrl = URL::to('');
            
            if(isset($disputeList) && $disputeList->isNotEmpty()){
                $disputeListArray = [];
                foreach($disputeList as $dispute)
                {
                    $disputeStatus = "";
                    if($dispute->status == 0) {
                        $disputeStatus = "Pending";
                    } else if($dispute->status == 1) {
                        $disputeStatus = "Reported";
                    }else if($dispute->status == 2) {
                        $disputeStatus = "Decline";
                    }
                    
                    $pic = "no-image.png";
                    if(!empty($dispute->customer_photo)) {
                        $pic = $dispute->customer_photo;
                    }
                     
                    $disputeListArray[] = [
                        "customer_name"=>$dispute->customer_name,
                        "customer_phone"=>$dispute->customer_phone,
                        "customer_photo"=>$baseUrl.'/public/upload/client/'.$pic,
                        "imei_number"=> $dispute->imei_number,
                        "status"=>$disputeStatus,
                        "description"=> $dispute->description."(". $disputeStatus .")",
                        "date"=> $dispute->date,
                    ];
                }
                
                if($disputeListArray) {
                    Log::info('Get IMEI Dispute List By Apps');
                    return response()->json($disputeListArray,200);
                } else {
                    Log::warning('IMEI Dispute List Not Found By Apps');
                    return response()->json(apiResponses(404),404);
                }
            }
            else{
                Log::warning('IMEI Dispute List Not Found By Apps');
                return response()->json(apiResponses(404),404);
            }
        }
        else 
        {
            return response()->json(apiResponses(401),401);
        }
    }
    
    public function generalAndtargetIncentiveReport(Request $request)
    {
        $headerAuth     = $request->header('Authorization'); 
        $token          = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus   = $this->verifyApiAuth($headerAuth,$token);
        
        if(isset($verifyStatus) && $verifyStatus === true) 
        {
            $userId     = auth('api')->user()->id;
            $userExists = DB::table('users')
            ->where('id',$userId)
            ->first();

            $loginUserId      = 0;
            if($userExists->bp_id > 0) {
                $loginUserId = $userExists->bp_id;
            } else if($userExists->retailer_id > 0) {
                $loginUserId = $userExists->retailer_id;
            }
            
            DB::table('temporaryes')->insert([
                "bp_id"=>$loginUserId,
                "request_data"=>"Check BP Incentive",
                "date"=>date('d-m-Y'),
            ]);
            
            
            $startDate      = $request->startDate ? $request->startDate : date('Y-m-01');
            $endDate        = $request->endDate ? $request->endDate : date('Y-m-t');
            
            $bpId           = 0;
            $retailerId     = 0;
            $userType       = "";
            $incentiveUserName = "";
            
            if($request->bpId > 0) {
                $bpId       = $request->bpId;
                $getBpInfo  = BrandPromoter::select('bp_id','retailer_id','bp_name')
                ->where('id','=',$bpId)
                ->first();
                
                $retailerId         = $getBpInfo->retailer_id;
                $userType           = "bp";
                $incentiveUserName  = $getBpInfo->bp_name;
            } else {
                $retailerId         = $request->retailId;
                $userType           = "retailer";
                $getRetailerInfo    = Retailer::select('id','retailer_id','retailer_name')
                ->where('id','=',$retailerId)
                ->first();

                $incentiveUserName  = $getRetailerInfo->retailer_name;
            }
            

            $incentiveLists = DB::table('incentives')
            ->whereBetween(\DB::raw("DATE_FORMAT(end_date,'%Y-%m-%d')"),[$startDate,$endDate])
            ->where('status','=',1)
            ->where(function($sql_query) use($userType,$bpId,$retailerId) {
                if($userType == "bp") {
                    if($bpId > 0) {
                        $sql_query->where('incentive_group','=', 1);
                    }
                }
                if($userType == "retailer") {
                    if($retailerId > 0) {
                        $sql_query->where('incentive_group','=', 2);
                    }
                }
            })
            ->get();


            $generalIncentiveList   = [];
            $targetIncentiveList    = [];

            if($incentiveLists->isNotEmpty()) 
            {
                foreach($incentiveLists as $key=>$incentive) 
                {
                    $getModelId         = json_decode($incentive->product_model,TRUE);
                    $getIncentiveType   = json_decode($incentive->incentive_type,TRUE);
                    $getZone            = json_decode($incentive->zone,TRUE);
                    $groupCatIds        = explode(',', $incentive->group_category_id);
                    $incentiveCatName   = $incentive->incentive_category;

                    $incentiveType      = $incentive->incentive_group == 1 ? "bp":"retailer";
                    $incentiveSDate     = $incentive->start_date;
                    $incentiveEDate     = $incentive->end_date;
                    $targetQty          = $incentive->min_qty;
                    $incentiveAmount    = $incentive->incentive_amount;


                    $getSaleList = DB::table('view_sales_reports')
                    ->where('status','=',0)
                    ->where('bp_id',$bpId)
                    ->where('retailer_id',$retailerId)
                    ->whereBetween(DB::raw("DATE_FORMAT(sale_date,'%Y-%m-%d')"),[$startDate,$endDate])
                    ->where(function($sql_query) use($getModelId,$getIncentiveType,$getZone,$groupCatIds,$incentiveType) {
                        if($getModelId) {
                            if(in_array("all", $getModelId)) {
                                $sql_query->whereNotNull('product_master_id');
                            }
                            else {
                                $sql_query->whereIn('product_master_id',$getModelId);
                            }
                        }

                        if($getIncentiveType) {
                            if(in_array("all", $getIncentiveType)) {
                                $sql_query->whereNotNull('bp_id');
                                $sql_query->whereNotNull('retailer_id');
                            }
                            else {
                                if(in_array("bp", $getIncentiveType)){
                                    $sql_query->whereNotNull('bp_id');
                                } else if(in_array("retailer", $getIncentiveType)){
                                    $sql_query->whereNotNull('retailer_id');
                                }
                            }
                        }

                        if($getZone) {
                            if(in_array("all", $getZone)) {
                                $sql_query->whereNotNull('zone_id');
                            }
                            else {
                                $sql_query->whereIn('zone_id',$getZone);
                            }
                        }

                        if($groupCatIds) {
                            if($incentiveType == "bp"){
                                $sql_query->whereIn('bp_category_id',$groupCatIds);
                            } else if($incentiveType == "retailer") {
                                $sql_query->whereIn('retailer_category_id',$groupCatIds);
                            }
                        }
                    })
                    ->orderBy('id','DESC')
                    ->get();


                    foreach($getSaleList as $sale) 
                    {
                        if($incentive->incentive_amount > 0)
                        {
                            if($incentive->incentive_category == 'general')
                            {
                                $generalIncentiveList[] = [
                                    "title"=>$incentive->incentive_title,
                                    "sale_qty"=>$sale->sale_qty,
                                    "amount"=>$sale->sale_qty*$incentive->incentive_amount,
                                    "model"=>$sale->product_model,
                                    "name"=> $incentiveUserName ? $incentiveUserName:""
                                ];
                            }

                            if($incentive->incentive_category == 'target')
                            {
                                $targetIncentiveList[] = [
                                    "title"=>$incentive->incentive_title,
                                    "sale_qty"=>$sale->sale_qty,
                                    "amount"=>$sale->sale_qty*$incentive->incentive_amount,
                                    "model"=>$sale->product_model,
                                    "name"=> $incentiveUserName ? $incentiveUserName:""
                                ];
                            }
                        }
                    }
                }

                $liftingArray   = [];
                if($retailerId > 0)
                {
                    $retailerPhone  = DB::table('view_retailer_list')
                    ->where('id',$retailerId)
                    ->value('phone_number');
                    
                    $startDate  = date("Y-M-d",strtotime($startDate));
                    $endDate    = date("Y-M-d",strtotime($endDate));
                    
                    $getCurlResponse    = getData(sprintf(RequestApiUrl("GetRetailerLiftingIncentive"),$startDate,$endDate,$retailerPhone),"GET");
                    $responseData       = json_decode($getCurlResponse['response_data'],true);
                    
                    $totalAmount    = 0;
                    
                    foreach($responseData as $key=>$row) {
                        $totalAmount += $row['RetailerAmount'];
                        
                        $liftingArray[$key]['title'] = $row['Model'];
                        $liftingArray[$key]['sale_qty'] = 1;
                        $liftingArray[$key]['amount'] = $row['RetailerAmount'];
                        $liftingArray[$key]['model'] = $row['Model'];
                        $liftingArray[$key]['name'] = $row['RetailerName'];
                        
                    }
                    $liftingIncentive = "";
                    if(count($responseData) > 0) {
                        $liftingIncentive = $responseData;
                    }
                }

                return response()->json(["general"=>$generalIncentiveList,"target"=>$targetIncentiveList,"lifting"=>$liftingArray],200);
            }
            else
            {
                return response()->json(apiResponses(404),404);
            }
        }
        else {
            return response()->json(apiResponses(401),401);
        }
    }

    public function getSalesTarget(Request $request)
    {
        $headerAuth     = $request->header('Authorization'); 
        $token          = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus   = $this->verifyApiAuth($headerAuth,$token);
        
        if(isset($verifyStatus) && $verifyStatus === true) {
            
            $groupId     = 0;
            $groupName   = "";
            if($request->group == 1) {
                $groupId     = $request->group;
                $groupName   = "BP";
            } else {
                $groupId     = $request->group;
                $groupName   = "Retailer";
            }


            $sales_target = DB::table('incentives')
            ->select("incentive_title","product_model","incentive_type","zone","incentive_amount","min_qty","start_date","end_date")
            ->where('incentive_group',$groupId)
            ->where('incentive_category','like','%target%')
            ->where('status',1)
            ->get();
            

            $model_name_list    = [];
            $zone_name_list     = [];
            $getIncentiveType   = "";

            if($sales_target->isNotEmpty())
            {
                foreach($sales_target as $row)
                {
                    $getModelList       = json_decode($row->product_model);
                    foreach($getModelList as $modelId) {
                        $model_name_list[] = DB::table('product_masters')
                        ->where('product_master_id',$modelId)
                        ->where('status',1)
                        ->value('product_model');
                    }
                    unset($row->product_model);

                    $getIncentiveType   = json_decode($row->incentive_type);
                    unset($row->incentive_type);

                    $zoneIdList         = json_decode($row->zone);
                    foreach($zoneIdList as $zone) {
                        $zone_name_list[] = DB::table('view_zone_list')
                        ->where('id',$zone)
                        ->where('status',1)
                        ->value('zone_name');
                    }
                    unset($row->zone);
                }

                $row->salesModel       = $model_name_list ? $model_name_list:"";
                $row->salesZone        = !empty($zone_name_list) ? $zone_name_list : "";
                $row->incentiveType    = !empty($getIncentiveType) ? $getIncentiveType: "";

                //return response()->json([$groupName."List"=>$sales_target],200);
                return response()->json($sales_target,200);
            }
            return response()->json(apiResponses(404),404);
        }
        else {
            return response()->json(apiResponses(401),401);
        }
    }
    
    public function checkUserByPhone(Request $request)
    {
        $headerAuth     = $request->header('Authorization'); 
        $token          = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus   = $this->verifyApiAuth($headerAuth,$token);
        
        if(isset($verifyStatus) && $verifyStatus === true) {
            $user_phone = $request->input('phone');

            $userStatus = DB::table('view_check_login_user')
            ->where('employee_phone',$user_phone)
            ->orWhere('brand_promoter_phone',$user_phone)
            ->orWhere('retailer_phone',$user_phone)
            ->first();

            if($userStatus) {

                $otp = mt_rand(100000,999999);

                $addOtp = DB::table('users')
                ->where('id',$userStatus->id)
                ->update([
                    "otp_token"=>$otp
                ]);

                if($addOtp) {
                    $userCode = [
                        "user_id"=>$userStatus->id,
                        "code"=>$otp
                    ];
                    Log::info('User Otp Code Send Success');
                    return response()->json($userCode,200);
                }
            }
            Log::warning('User Not Available');
            return response()->json(apiResponses(401),401);
        } else {
            return response()->json(apiResponses(401),401);
        }
    }

    public function userOtpVerify(Request $request)
    {
        $headerAuth     = $request->header('Authorization'); 
        $token          = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus   = $this->verifyApiAuth($headerAuth,$token);
        
        if(isset($verifyStatus) && $verifyStatus === true) {
            $otp_token      = $request->input('otp_token');
            $user_id        = $request->input('user_id');

            $userStatus = DB::table('view_check_login_user')
            ->where('id',$user_id)
            ->where('otp_token',$otp_token)
            ->first();

            if($userStatus) {
                $verifyCode = [
                    "user_id"=>$userStatus->id,
                    "otp_token"=>$otp_token,
                    "status"=>"true"
                ];
                Log::info('otp Verify Code Send Success');
                return response()->json($verifyCode,200);
            }
            Log::warning('otp Verify Code Send Failed');
            return response()->json(apiResponses(401),401);
        } else {
            return response()->json(apiResponses(401),401);
        }
    }
    
    public function userPasswordUpdate(Request $request)
    {
        $headerAuth     = $request->header('Authorization'); 
        $token          = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus   = $this->verifyApiAuth($headerAuth,$token);
        
        if(isset($verifyStatus) && $verifyStatus === true) {
            
            $userId     = auth('api')->user()->id;
            $authUserName = DB::table('users')
			->select('name')
            ->where('id',$userId)
            ->value('name');

            $otp_token      = $request->input('otp_token');
            $user_id        = $request->input('user_id');
            $new_password   = $request->input('password');

            $userStatus = DB::table('view_check_login_user')
            ->where('id',$user_id)
            ->where('otp_token',$otp_token)
            ->first();

            if($userStatus && $otp_token > 0 && !empty($new_password)) {

                $req = Validator::make($request->all(), [
                    'password' => 'required|string|min:5',
                ]);

                if ($req->fails()) {
                    return response()->json($req->errors(), 422);
                }
				
				$name 		= $userStatus->name;
				$phone 		= ($userStatus->brand_promoter_phone) ? $userStatus->brand_promoter_phone : $userStatus->retailer_phone;
				$password 	= $new_password;

                $addOtp = DB::table('users')
                ->where('id',$userStatus->id)
                ->update([
                    "otp_token"=>"",
                    "password"=>Hash::make($new_password),
                    "author"=>$authUserName,
                    "updated_at"=>date('Y-m-d h:i:s')
                ]);
				
				
				$messageBody = "HI, ".$name.". Your Password Has Reset.User Name=".$phone." Password=".$password." Thanks, Walton";
				$requestData = array(
					'mobileNumber' => $phone,
					'message' =>$messageBody,
				);
				$postRequestData    = json_encode($requestData);
				
				if($addOtp) {
				    $this->sms_send($postRequestData);
				}
				
                Log::info('User Password Update Successfully-(User ID-'.$user_id.')');
                return response()->json(apiResponses(200),200);
            }
            Log::warning('User Password Update Failed-(User ID-'.$user_id.')');
            return response()->json(apiResponses(401),401);
        }
        else {
            return response()->json(apiResponses(401),401);
        }
    }
	
	public function sms_send($postRequestData)
    {
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
    }
    
    public function incentiveStatement(Request $request)
    {
        $headerAuth     = $request->header('Authorization'); 
        $token          = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus   = $this->verifyApiAuth($headerAuth,$token);
        
        if(isset($verifyStatus) && $verifyStatus === true) {

            $search_Sdate   = $request->startDate ? $request->startDate : date('Y-m-01');
            $search_Edate   = $request->endDate ? $request->endDate : date('Y-m-t');

            $bpId           = 0;
            $retailerId     = 0;
            $groupId        = 0;
            $incentiveType  = "";
            $groupCatId     = 1;//defaule 1 = A,Assigned
            $userType       = "";

            if($request->bpId > 0) {
                $bpId           = $request->bpId;
                $groupId        = 1;
                $incentiveType  = "bp";
                
                $getBpInfo     = BrandPromoter::select('bp_id','retailer_id','category_id')
                ->where('id','=',$bpId)
                ->first();

                if($getBpInfo) {
                    $retailerId     = ($getBpInfo->retailer_id) ? $getBpInfo->retailer_id:0;
                    $groupCatId     = ($getBpInfo->category_id) ? $getBpInfo->category_id:0;
                }
                
                $userType       = "bp";
            } else {
                $retailerId     = $request->retailId;
                $groupId        = 2;
                $incentiveType  = $request->retailId;

                $getRetailInfo  = Retailer::select('retailer_id','retailer_name','category_id')
                ->where('id','=',$retailerId)
                ->first();

                if($getRetailInfo) {
                    $groupCatId     = $getRetailInfo->category_id;
                }
                $userType       = "retailer";
            }

            $incentiveLists = DB::table('incentives')
            ->whereBetween(\DB::raw("DATE_FORMAT(end_date,'%Y-%m-%d')"),[$search_Sdate,$search_Edate])
            ->where('status','=',1)
            ->where('incentive_amount','>',0)
            ->where(function($sql_query) use($userType,$bpId,$retailerId) {
                if($userType == "bp") {
                    if($bpId > 0) {
                        $sql_query->where('incentive_group','=', 1);
                    }
                }
                if($userType == "retailer") {
                    if($retailerId > 0) {
                        $sql_query->where('incentive_group','=', 2);
                    }
                }
            })
            ->get();
            //echo "<pre>";print_r(incentiveLists);echo "</pre>";exit();

            $responseArray                  = [];
            $generalIncentiveAmount         = 0;
            $targetIncentiveAmount          = 0;
            $totalLiftingIncentiveAmount    = 0;
            $totalSaleAmount                = 0;


            if($incentiveLists->isNotEmpty()) {
                foreach($incentiveLists as $key=>$incentive) {

                    $getModelId         = json_decode($incentive->product_model,TRUE);
                    $getIncentiveType   = json_decode($incentive->incentive_type,TRUE);
                    $getZone            = json_decode($incentive->zone,TRUE);
                    $groupCatIds        = explode(',', $incentive->group_category_id);
                    $incentiveCatName   = $incentive->incentive_category;

                    $incentiveType      = $incentive->incentive_group == 1 ? "bp":"retailer";
                    $incentiveSDate     = $incentive->start_date;
                    $incentiveEDate     = $incentive->end_date;
                    $targetQty          = $incentive->min_qty;
                    $incentiveAmount    = $incentive->incentive_amount;


                    $totalSaleAmount = DB::table('view_sales_reports')
                    ->where('status','=',0)
                    //->where('bp_id',$bpId)
                    //->where('retailer_id',$retailerId)
                    ->whereBetween(DB::raw("DATE_FORMAT(sale_date,'%Y-%m-%d')"),[$search_Sdate,$search_Edate])
                    /*
                    ->where(function($sql_query) use($getModelId,$getIncentiveType,$getZone,$groupCatIds,$incentiveType) {
                        if($getModelId) {
                            if(in_array("all", $getModelId)) {
                                $sql_query->whereNotNull('product_master_id');
                            }
                            else {
                                $sql_query->whereIn('product_master_id',$getModelId);
                            }
                        }

                        if($getIncentiveType) {
                            if(in_array("all", $getIncentiveType)) {
                                $sql_query->whereNotNull('bp_id');
                                $sql_query->whereNotNull('retailer_id');
                            }
                            else {
                                if(in_array("bp", $getIncentiveType)){
                                    $sql_query->whereNotNull('bp_id');
                                } else if(in_array("retailer", $getIncentiveType)){
                                    $sql_query->whereNotNull('retailer_id');
                                }
                            }
                        }

                        if($getZone) {
                            if(in_array("all", $getZone)) {
                                $sql_query->whereNotNull('zone_id');
                            }
                            else {
                                $sql_query->whereIn('zone_id',$getZone);
                            }
                        }

                        if($groupCatIds) {
                            if($incentiveType == "bp"){
                                $sql_query->whereIn('bp_category_id',$groupCatIds);
                            } else if($incentiveType == "retailer") {
                                $sql_query->whereIn('retailer_category_id',$groupCatIds);
                            }
                        }
                    })
                    */
                    ->where(function($sql_query) use($bpId,$retailerId) {
                        if($bpId > 0){
                            $sql_query->where('bp_id','=',$bpId);
                        } else if($retailerId > 0) {
                            $sql_query->where('retailer_id','=',$retailerId);
                        }
                    })
                    ->sum('msrp_price');

                    $getItemSaleQty = DB::table('view_sales_reports')
                    ->where('status','=',0)
                    ->where('bp_id',$bpId)
                    ->where('retailer_id',$retailerId)
                    ->whereBetween(DB::raw("DATE_FORMAT(sale_date,'%Y-%m-%d')"),[$search_Sdate,$search_Edate])
                    ->where(function($sql_query) use($getModelId,$getIncentiveType,$getZone,$groupCatIds,$incentiveType) {
                        if($getModelId) {
                            if(in_array("all", $getModelId)) {
                                $sql_query->whereNotNull('product_master_id');
                            }
                            else {
                                $sql_query->whereIn('product_master_id',$getModelId);
                            }
                        }

                        if($getIncentiveType) {
                            if(in_array("all", $getIncentiveType)) {
                                $sql_query->whereNotNull('bp_id');
                                $sql_query->whereNotNull('retailer_id');
                            }
                            else {
                                if(in_array("bp", $getIncentiveType)){
                                    $sql_query->whereNotNull('bp_id');
                                } else if(in_array("retailer", $getIncentiveType)){
                                    $sql_query->whereNotNull('retailer_id');
                                }
                            }
                        }

                        if($getZone) {
                            if(in_array("all", $getZone)) {
                                $sql_query->whereNotNull('zone_id');
                            }
                            else {
                                $sql_query->whereIn('zone_id',$getZone);
                            }
                        }

                        if($groupCatIds) {
                            if($incentiveType == "bp"){
                                $sql_query->whereIn('bp_category_id',$groupCatIds);
                            } else if($incentiveType == "retailer") {
                                $sql_query->whereIn('retailer_category_id',$groupCatIds);
                            }
                        }
                    })
                    ->orderBy('id','DESC')
                    ->count();


                    $getSaleList = DB::table('view_sales_reports')
                    ->where('status','=',0)
                    ->where('bp_id',$bpId)
                    ->where('retailer_id',$retailerId)
                    ->whereBetween(DB::raw("DATE_FORMAT(sale_date,'%Y-%m-%d')"),[$search_Sdate,$search_Edate])
                    ->where(function($sql_query) use($getModelId,$getIncentiveType,$getZone,$groupCatIds,$incentiveType) {
                        if($getModelId) {
                            if(in_array("all", $getModelId)) {
                                $sql_query->whereNotNull('product_master_id');
                            }
                            else {
                                $sql_query->whereIn('product_master_id',$getModelId);
                            }
                        }

                        if($getIncentiveType) {
                            if(in_array("all", $getIncentiveType)) {
                                $sql_query->whereNotNull('bp_id');
                                $sql_query->whereNotNull('retailer_id');
                            }
                            else {
                                if(in_array("bp", $getIncentiveType)){
                                    $sql_query->whereNotNull('bp_id');
                                } else if(in_array("retailer", $getIncentiveType)){
                                    $sql_query->whereNotNull('retailer_id');
                                }
                            }
                        }

                        if($getZone) {
                            if(in_array("all", $getZone)) {
                                $sql_query->whereNotNull('zone_id');
                            }
                            else {
                                $sql_query->whereIn('zone_id',$getZone);
                            }
                        }

                        if($groupCatIds) {
                            if($incentiveType == "bp"){
                                $sql_query->whereIn('bp_category_id',$groupCatIds);
                            } else if($incentiveType == "retailer") {
                                $sql_query->whereIn('retailer_category_id',$groupCatIds);
                            }
                        }
                    })
                    ->orderBy('id','DESC')
                    ->get();


                    foreach($getSaleList as $sale)  {
                        if($incentive->incentive_amount > 0) {
                            if($incentive->incentive_category == 'general') {
                                $generalIncentiveAmount += $sale->sale_qty*$incentive->incentive_amount;
                            }

                            if($incentive->incentive_category == 'target') {
                                $targetIncentiveAmount += $sale->sale_qty*$incentive->incentive_amount;
                            }
                        }
                    }
                }
            }

            if($retailerId > 0) {
                $retailerPhone  = DB::table('view_retailer_list')
                ->where('id',$retailerId)
                ->value('phone_number');
                
                $startDate  = date("Y-M-d",strtotime($search_Sdate));
                $endDate    = date("Y-M-d",strtotime($search_Edate));
                
                $getCurlResponse    = getData(sprintf(RequestApiUrl("GetRetailerLiftingIncentive"),$startDate,$endDate,$retailerPhone),"GET");
                $responseData       = json_decode($getCurlResponse['response_data'],true);
    
                foreach($responseData as $row) {
                    $totalLiftingIncentiveAmount += $row['RetailerAmount'];
                }
            }

            $responseArray[] = [
                "title"=>"General Incentive",
                "amount"=>$generalIncentiveAmount,
                'key'=>'general_incentive',
            ];

            $responseArray[] = [
                "title"=>"Target Incentive",
                "amount"=>$targetIncentiveAmount,
                'key'=>'target_incentive',
            ];

            $responseArray[] = [
                "title"=>"Total Sale",
                "amount"=>$totalSaleAmount,
                'key'=>'total_sale',
            ];

            $responseArray[] = [
                "title"=>"Total Incentive",
                "amount"=>$generalIncentiveAmount+$targetIncentiveAmount,
                'key'=>'total_incentive',
            ];


            if(!empty($responseArray)) {
                return response()->json($responseArray,200);
            } else {
                return response()->json(apiResponses(401),401);
            }
        }
        else {
            return response()->json(apiResponses(401),401);
        }
    }

    public function RequestOffLineArray(Request $request)
    {
        $headerAuth     = $request->header('Authorization'); 
        $token          = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus   = $this->verifyApiAuth($headerAuth,$token);
        
        if(isset($verifyStatus) && $verifyStatus === true) {

            $SalesArray = array (
                array(
                    "customer_name"=> "Shimul Mondol",
                    "customer_phone"=> "01552669988",
                    "bp_id"=> "1",
                    "sale_id"=> "1",
                    "retailer_id"=> "0",
                    "address"=>"dhaka,mirpur 2",
                    "photo"=> "1621427130.jpg",
                    "date"=>"2021-05-19",
                    "list"=>array(
                        array(
                            "ime_number"=> "354066116773535",
                            "qty"=> "1",
                            "price"=> "950"
                        ),
                        array(
                            "ime_number"=> "354066116773536",
                            "qty"=> "1",
                            "price"=> "950"
                        ),
                    )
                ),
                array(
                    "customer_name"=> "Mr.Rony",
                    "customer_phone"=> "01552111222",
                    "bp_id"=> "1",
                    "sale_id"=> "1",
                    "retailer_id"=> "0",
                    "address"=>"dhaka,mirpur 2",
                    "photo"=> "1621427130.jpg",
                    "date"=>"2021-05-19",
                    "list"=>array(
                        array(
                            "ime_number"=> "354066116773537",
                            "qty"=> "1",
                            "price"=> "950"
                        )
                    )
                )
            );

            $jsonFeedArray = json_encode(['sales'=>$SalesArray]);

            $getJsonFeed  = json_decode($jsonFeedArray);

            //echo "<pre>";print_r(json_decode($jsonFeedArray));echo "</pre>";die();

            //$getSalesList = json_decode($SalesArray);
            //echo "<pre>";print_r($getSalesList);echo "</pre>";die();

            return response()->json(['sales'=>$SalesArray],200);
        }
        else {
            return response()->json(apiResponses(401),401);
        }
    }
    
    public function offLineSalesStore(Request $request)
    {
        $headerAuth     = $request->header('Authorization'); 
        $token          = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus   = $this->verifyApiAuth($headerAuth,$token);
        
        if(isset($verifyStatus) && $verifyStatus === true) 
        {
            $jsonFeedArray = $request->input('sales');
            $getJsonFeed   = json_decode($jsonFeedArray);
            $sale_data     = "";
            $status        = 0;
            
            $unique_number  = mt_rand(1,9);
            $invoice_number = date('Ymds').$unique_number;
            $orderFeeds     = "";
            
            $saleId         = 0;
            $baseUrl        = URL::to('');
            foreach($getJsonFeed as $row)
            {
                $bpId      = 0;
                $retailId  = 0;
                $groupId   = 0;
                $waltonbpId = 0;
                $waltonRetailId = 0;
    
                if($row->bp_id > 0) 
                {
                    $bpId           = $row->bp_id;
                    $groupId        = 1;
                    //$waltonbpId     = BrandPromoter::where('id','=',$bpId)->value('bp_id');
                    
                    $getBpInfo     = BrandPromoter::select('bp_id','retailer_id')
                    ->where('id','=',$bpId)
                    ->first();

                    $waltonbpId    = $getBpInfo->bp_id;
                    $retailId      = $getBpInfo->retailer_id;
                } 
                if($row->retailer_id > 0) 
                {
                    $retailId       = $row->retailer_id;
                    $groupId        = 2;
                    $waltonRetailId = Retailer::where('id','=',$retailId)->value('retailer_id');
                    //$bpId           = BrandPromoter::where('retailer_id','=',$retailId)->value('bp_id');
                }
                
                $customer_name  = $row->customer_name;
                $customer_phone = $row->customer_phone;
                $saleDate       = date('d-m-Y');//($row->date) ? $row->date:date('d/m/Y');
                $itemList       = json_decode($row->list);
                
                $imeProductStatus  = [];
                $getorderStatus    = [];
                $imeProductResult  = "";
                $saleStatus        = false;
                
                $dealerCode        = 0;
                $productMasterId   = 0;
                foreach($itemList as $lists)
                {
                    $getCurlResponse = getData(sprintf(RequestApiUrl("GetIMEIinfo"),$lists->ime_number),"GET");
                    $responseData    = (array)json_decode($getCurlResponse['response_data'],true);
                    
                    if(!empty($getCurlResponse['response_data']) || $getCurlResponse['response_data']!=null)
                    {
                        /*DB::table('temporaryes')->insert([
                            "request_data"=>$responseData[0]['DealerCode'],
                            "date"=>date('d-m-Y'),
                        ]);*/
                        
                        $dealerCode     = $responseData[0]['DealerCode'];
                        $productId      = $responseData[0]['ProductID'];
                        $productColor   = $responseData[0]['Color'];
                        $productModel   = $responseData[0]['Model'];
                        //$productImei1   = $responseData[0]['ImeiOne'];
                        //$productImei2   = $responseData[0]['ImeiTwo'];
                        $productType    = $responseData[0]['CellPhoneType'];
                        //$productStatus  = ($responseData[0]['IsSoldOut'] == true || $responseData[0]['IsSoldOut'] != null) ? "0":"1";
                        $productStatus  = ($responseData[0]['IsValid'] == true || $responseData[0]['IsValid'] != null) ? "1":"0";
                        
                        $imei1      = ($lists->ime_number) ? $lists->ime_number :$responseData[0]['ImeiOne'];
                        $imei2      = ($responseData[0]['ImeiTwo']) ? $responseData[0]['ImeiTwo']:"";

                        if($productStatus == 0) {
                            $getorderStatus[] = 1;
                            $status           = 3;
                        }
                        
                        $ZoneId    = 0;
                        if($groupId == 1)
                        {
                            $dealerZoneName = DB::table('dealer_informations')
                            ->where('dealer_code',$dealerCode)
                            ->where('alternate_code',$dealerCode)
                            ->value('zone');
    
                            if(!empty($dealerZoneName)) {
                                $ZoneId = DB::table('zones')
                                ->where('zone_name','like','%'.$dealerZoneName.'%')
                                ->value('id');
                            }
                        }
                        else
                        {
                            $getZoneId = DB::table('retailers')
                            ->where('retailer_id',$retailId)
                            ->value('zone_id');
    
                            if($getZoneId != null || !empty($getZoneId)) {
                                $ZoneId = $getZoneId;
                            }
                        }
                        
                        $imeProductResult = DB::table('view_product_master')
                        ->where('product_id','=',$productId)
                        ->first();
                        
                        if(!empty($imeProductResult))
                        {
                            $productMasterId    = $imeProductResult->product_master_id;
                            $image_64           = trim($row->photo); // image base64 encoded
                            $storagePath    = '';
                            $imageName      = "";
                            if(!empty($image_64 )) 
                            {
                                $ext                = explode(';base64',$row->photo);
                                $ext                = explode('/',$ext[0]);            
                                $extension          = ($ext[1]) ? $ext[1]:".jpg";
            
                                $replace            = substr($image_64, 0, strpos($image_64, ',')+1); 
                                $image              = str_replace($replace, '', $image_64); 
                                $image              = str_replace(' ', '+', $image);
                                $imageName          = time().'.'.$extension;
            
                                //Storage::disk('public')->put($imageName, base64_decode($image));
                                Storage::disk('public_uploads')->put($imageName, base64_decode($image));
            
                                $baseUrl        = URL::to('');
                                $storagePath    = $baseUrl.'/storage/app/public/'.$imageName;
                            }
                            
                            $orderStatus = 0;//order Pending 
                            if(in_array(1,$getorderStatus)) {
                                $orderStatus = 1; //order Sold
                            }

                            if($saleStatus === true) {
                                $getorderStatus[] = 1;
                            }

                            if($saleStatus === false) {
                                Sale::create([
                                    "invoice_number"=>$invoice_number,
                                    "customer_name"=>$customer_name,
                                    "customer_phone"=>$customer_phone,
                                    "bp_id"=> $bpId,
                                    "retailer_id"=> $retailId,
                                    "dealer_code"=> $dealerCode,
                                    "sale_date"=>date('Y-m-d H:i:s'),
                                    "photo"=> $imageName, //$storagePath,
                                    "status"=>$orderStatus,
                                    "walton_status"=>1,
                                    "order_type"=>2
                                ]);
                                $saleId = DB::getPdo()->lastInsertId();
                                
                                $request_saleData = [
                                    "invoice_number"=>$invoice_number,
                                    "sales_id"=>$saleId,
                                    "customer_name"=>$customer_name,
                                    "customer_phone"=>$customer_phone,
                                    "bp_id"=> $waltonbpId ? $waltonbpId:$bpId,
                                    "retailer_id"=> $waltonRetailId ? $waltonRetailId:$retailId,
                                    "dealer_code"=> $dealerCode,
                                    "sale_date"=>date('Y-m-d H:i:s'),
                                    "photo"=> $baseUrl."/public/upload/client/".$imageName,
                                    "status"=>$orderStatus,
                                    "order_type"=>"Offline"
                                ];
                                $orderFeeds = $request_saleData;

                                $saleStatus = true;
                                $getorderStatus[] = 0;
                            }

                            if(in_array(1,$getorderStatus)) {
                                Sale::where('id',$saleId)->update(["status"=>$orderStatus]);
                            }
                            
                            if(!empty($saleId)) {
                                SaleProduct::create([
                                    "sales_id"=>$saleId,
                                    "ime_number"=>$imei1,
                                    "alternate_imei"=>$imei2,
                                    "dealer_code"=> $dealerCode,
                                    "product_master_id"=> $productMasterId,
                                    "product_id"=> $imeProductResult->product_id,
                                    "product_code"=>  $imeProductResult->product_code,
                                    "product_type"=> $imeProductResult->product_type,
                                    "product_model"=> $imeProductResult->product_model,
                                    "product_color"=> $productColor ? $productColor:'Others',
                                    "category"=> $imeProductResult->category2,
                                    "mrp_price"=> $imeProductResult->mrp_price,
                                    "msdp_price"=> $imeProductResult->msdp_price,
                                    "msrp_price"=> $imeProductResult->msrp_price,
                                    "sale_price"=> $imeProductResult->msrp_price ? $imeProductResult->msrp_price : $lists->price,
                                    "sale_qty"=> $lists->qty,
                                    "bp_id"=> $bpId,
                                    "retailer_id"=> $retailId,
                                    "product_status"=>0, //0=sold order 1=pending order
                                    "update_sold_imei_status"=>0
                                ]);
                                $request_saleProductArray = [
                                    "sales_id"=>$saleId,
                                    "ime_number"=>$imei1,
                                    "alternate_imei"=>$imei2,
                                    "dealer_code"=> $dealerCode,
                                    "product_master_id"=> $productMasterId,
                                    "product_id"=> $imeProductResult->product_id,
                                    "product_code"=>  $imeProductResult->product_code,
                                    "product_type"=> $imeProductResult->product_type,
                                    "product_model"=> $imeProductResult->product_model,
                                    "product_color"=> $productColor ? $productColor:'Others',
                                    "category"=> $imeProductResult->category2,
                                    "mrp_price"=> $imeProductResult->mrp_price,
                                    "msdp_price"=> $imeProductResult->msdp_price,
                                    "msrp_price"=> $imeProductResult->msrp_price,
                                    "sale_price"=> $imeProductResult->msrp_price ? $imeProductResult->msrp_price : $lists->price,
                                    "sale_qty"=> $lists->qty,
                                    "bp_id"=> $waltonbpId ? $waltonbpId:0,
                                    "retailer_id"=> $waltonRetailId ? $waltonRetailId:0,
                                    "product_status"=>"Sold"
                                ];
                                $orderFeeds['itemLists'][] = $request_saleProductArray;
                                
                                //Ime Database Product Status Update Start
                                $getCurlResponse = getData(sprintf(RequestApiUrl("UpdateIMEIStatus"),$lists->ime_number),"GET");
                            }
    
                            $sale_data = [
                                "sale_id"=>$saleId,
                                "bp_id"=> $bpId,
                                "retailer_id"=> $retailId,
                                "sale_date"=>date('Y-m-d'),
                                "customer_name"=> $customer_name,
                                "customer_phone"=>  $customer_phone
                            ];
        
                            $saleQty        = $lists->qty;
                            $sale_date      = date('Y-m-d');
        
                            $incentiveType  = $groupId == 1 ? "bp":$retailId;
        
                            $incentiveLists = DB::table('incentives')
                            ->where('incentive_group',$groupId)
                            ->where('start_date','<=',$sale_date)
                            ->where('end_date','>=',$sale_date)
                            ->where('status',1)
                            ->get();

                            $status = 1;
                            //return response()->json(apiResponses(200),200);
                        }
                        else
                        {
                            $image_64       = trim($row->photo); // image base64 encoded
                            $storagePath    ='';
                            $imageName      = '';
                            if(!empty($image_64 )) 
                            {
                                $ext                = explode(';base64',$row->photo);
                                $ext                = explode('/',$ext[0]);            
                                $extension          = ($ext[1]) ? $ext[1]:".jpg";
            
                                $replace            = substr($image_64, 0, strpos($image_64, ',')+1); 
                                $image              = str_replace($replace, '', $image_64); 
                                $image              = str_replace(' ', '+', $image);
                                $imageName          = time().'.'.$extension;

                                //Storage::disk('public')->put($imageName, base64_decode($image));
                                Storage::disk('public_uploads')->put($imageName, base64_decode($image));
            
                                $baseUrl        = URL::to('');
                                $storagePath    = $baseUrl.'/storage/app/public/'.$imageName;
                            }
                            
                            $orderStatus = 0;//order Pending 
                            if(in_array(1,$getorderStatus)) {
                                $orderStatus = 1; //order Sold
                            }

                            if($saleStatus === true) {
                                $getorderStatus[] = 1;
                            }
                            
                            if($saleStatus === false) 
                            {
                                Sale::create([
                                    "invoice_number"=>$invoice_number,
                                    "customer_name"=>$customer_name,
                                    "customer_phone"=>$customer_phone,
                                    "bp_id"=> $bpId,
                                    "retailer_id"=> $retailId,
                                    "dealer_code"=> $dealerCode,
                                    "sale_date"=>date('Y-m-d'),
                                    "photo"=> $imageName, //$storagePath,
                                    "status"=>1,
                                    "walton_status"=>1,
                                    "order_type"=>2,//1=Online,2=Offline
                                ]);
                                $saleId = DB::getPdo()->lastInsertId();
                                
                                $request_saleData = [
                                    "invoice_number"=>$invoice_number,
                                    "sales_id"=>$saleId,
                                    "customer_name"=>$customer_name,
                                    "customer_phone"=>$customer_phone,
                                    "bp_id"=> $waltonbpId ? $waltonbpId:$bpId,
                                    "retailer_id"=> $waltonRetailId ? $waltonRetailId:$retailId,
                                    "dealer_code"=> $dealerCode,
                                    "sale_date"=>date('Y-m-d'),
                                    "photo"=> $baseUrl."/public/upload/client/".$imageName,
                                    "status"=>1,
                                    "order_type"=>"Offline"
                                ];
                                $orderFeeds = $request_saleData;
                                $saleStatus = true;
                                $getorderStatus[] = 0;
                            }

                            if(in_array(1,$getorderStatus)) {
                                Sale::where('id',$saleId)->update(["status"=>$orderStatus]);
                            }
                            
                            if(!empty($saleId)) 
                            {
                                SaleProduct::create([
                                    "sales_id"=>$saleId,
                                    "ime_number"=> $imei1,
                                    "alternate_imei"=> $imei2,
                                    "dealer_code"=> $dealerCode,
                                    "product_master_id"=> 0,
                                    "product_id"=> $productId,
                                    "product_code"=> 0,
                                    "product_type"=> $productType,
                                    "product_model"=> $productModel,
                                    "product_color"=> $productColor ? $productColor:'Others',
                                    "category"=>"Smart" ,
                                    "mrp_price"=> "00.00",
                                    "msdp_price"=> "00.00",
                                    "msrp_price"=> "00.00",
                                    "sale_price"=> $lists->price ? $lists->price:0,
                                    "sale_qty"=> $lists->qty,
                                    "bp_id"=> $bpId,
                                    "retailer_id"=> $retailId,
                                    "product_status"=>0, //0=Not Available 1=Available
                                    "update_sold_imei_status"=>0
                                ]);
                                
                                $request_saleProductArray = [
                                    "sales_id"=>$saleId,
                                    "ime_number"=> $imei1,
                                    "alternate_imei"=> $imei2,
                                    "dealer_code"=> $dealerCode,
                                    "product_master_id"=> 0,
                                    "product_id"=> $productId,
                                    "product_code"=> 0,
                                    "product_type"=> $productType,
                                    "product_model"=> $productModel,
                                    "product_color"=> $productColor ? $productColor:'Others',
                                    "category"=>"Smart" ,
                                    "mrp_price"=> "00.00",
                                    "msdp_price"=> "00.00",
                                    "msrp_price"=> "00.00",
                                    "sale_price"=> $lists->price ? $lists->price:0,
                                    "sale_qty"=> $lists->qty,
                                    "bp_id"=> $waltonbpId ? $waltonbpId:0,
                                    "retailer_id"=> $waltonRetailId ? $waltonRetailId:0,
                                    "product_status"=>"Not Available"
                                ];
                                $orderFeeds['itemLists'][] = $request_saleProductArray;
                                
                                //Ime Database Product Status Update Start
                                $getCurlResponse = getData(sprintf(RequestApiUrl("UpdateIMEIStatus"),$lists->ime_number),"GET");
                            }
    
                            $sale_data = [
                                "sale_id"=>$saleId,
                                "bp_id"=> $bpId,
                                "retailer_id"=> $retailId,
                                "sale_date"=>date('Y-m-d'),
                                "customer_name"=> $customer_name,
                                "customer_phone"=>  $customer_phone
                            ];
                            $saleQty   = $lists->qty;
                            $sale_date = date('Y-m-d H:i:s');
                            
                            $status = 1;
                            //return response()->json(apiResponses(200),200);
                        }
                    }
                    else
                    {
                        $image_64       = trim($row->photo); // image base64 encoded
                        $storagePath    ='';
                        $imageName      = "";
                        if(!empty($image_64 )) 
                        {
                            $ext                = explode(';base64',$row->photo);
                            $ext                = explode('/',$ext[0]);            
                            $extension          = ($ext[1]) ? $ext[1]:".jpg";
        
                            $replace            = substr($image_64, 0, strpos($image_64, ',')+1); 
                            $image              = str_replace($replace, '', $image_64); 
                            $image              = str_replace(' ', '+', $image);
                            $imageName          = time().'.'.$extension;
                            
                            //Storage::disk('public')->put($imageName, base64_decode($image));
                            Storage::disk('public_uploads')->put($imageName, base64_decode($image));
        
                            $baseUrl        = URL::to('');
                            $storagePath    = $baseUrl.'/storage/app/public/'.$imageName;
                        }
                        
                        $orderStatus = 0;//order Pending 
                        if(in_array(1,$getorderStatus)) {
                            $orderStatus = 1; //order Sold
                        }

                        if($saleStatus === true) {
                            $getorderStatus[] = 1;
                        }
                        
                        if($saleStatus === false) {
                            Sale::create([
                                "invoice_number"=>$invoice_number,
                                "customer_name"=>$customer_name,
                                "customer_phone"=>$customer_phone,
                                "bp_id"=> $bpId,
                                "retailer_id"=> $retailId,
                                "dealer_code"=>0,
                                "sale_date"=>date('Y-m-d H:i:s'),
                                "photo"=> $imageName, //$storagePath,
                                "status"=>1,
                                "walton_status"=>1,
                                "order_type"=>2,//1=Online,2=Offline
                            ]);
                            $saleId = DB::getPdo()->lastInsertId();
                            
                            $request_saleData = [
                                "invoice_number"=>$invoice_number,
                                "sales_id"=>$saleId,
                                "customer_name"=>$customer_name,
                                "customer_phone"=>$customer_phone,
                                "bp_id"=> $waltonbpId ? $waltonbpId:$bpId,
                                "retailer_id"=> $waltonRetailId ? $waltonRetailId:$retailId,
                                "dealer_code"=>0,
                                "sale_date"=>date('Y-m-d H:i:s'),
                                "photo"=> $baseUrl."/public/upload/client/".$imageName,
                                "status"=>1,
                                "order_type"=>"Offline"
                            ];
                            $orderFeeds = $request_saleData;
                            
                            $saleStatus = true;
                            $getorderStatus[] = 0;
                        }
                        
                        if(!empty($saleId)) {
                            SaleProduct::create([
                                "sales_id"=>$saleId,
                                "ime_number"=> $lists->ime_number,
                                "alternate_imei"=>"",
                                "dealer_code"=>0,
                                "product_master_id"=> 0,
                                "product_id"=> 0,
                                "product_code"=>  0,
                                "product_type"=> 0,
                                "product_model"=> 0,
                                "product_color"=> 'Others',
                                "category"=> 0,
                                "mrp_price"=> '0.00',
                                "msdp_price"=> '0.00',
                                "msrp_price"=> '0.00',
                                "sale_price"=> $lists->price ? $lists->price:0,
                                "sale_qty"=> $lists->qty,
                                "bp_id"=> $bpId ? $bpId:0,
                                "retailer_id"=> $retailId ? $retailId:0,
                                "product_status"=>1, //0=sold 1=pending
                                "update_sold_imei_status"=>0
                            ]);
                            $request_saleProductArray = [
                                "sales_id"=>$saleId,
                                "ime_number"=> $lists->ime_number,
                                "alternate_imei"=>"",
                                "dealer_code"=>0,
                                "product_master_id"=> 0,
                                "product_id"=> 0,
                                "product_code"=>  0,
                                "product_type"=> 0,
                                "product_model"=> 0,
                                "product_color"=> 'Others',
                                "category"=> 0,
                                "mrp_price"=> '0.00',
                                "msdp_price"=> '0.00',
                                "msrp_price"=> '0.00',
                                "sale_price"=> $lists->price ? $lists->price:0,
                                "sale_qty"=> $lists->qty,
                                "bp_id"=> $waltonbpId ? $waltonbpId:0,
                                "retailer_id"=> $waltonRetailId ? $waltonRetailId:0,
                                "product_status"=>"Pending"
                            ];
                            $orderFeeds['itemLists'][] = $request_saleProductArray;
                        }
                        
                        $sale_data = [
                            "sale_id"=>$saleId,
                            "bp_id"=> $bpId,
                            "retailer_id"=> $retailId,
                            "sale_date"=>date('Y-m-d H:i:s'),
                            "customer_name"=> $customer_name,
                            "customer_phone"=>  $customer_phone
                        ];
                        $saleQty   = $lists->qty;
                        $sale_date = date('Y-m-d H:i:s');
                        
                        $status = 2;
                        //return response()->json(apiResponses(200),200);
                    }
                }
            }
            
            if($status == 1)
            {
                $getPostCurlResponse = postData(sprintf(RequestApiUrl("SaveSale")),$orderFeeds);
                
                if(!empty($getPostCurlResponse) && $getPostCurlResponse=='success') {
                    DB::table('sales')
                    ->where('id','=',$saleId)
                    ->update([
                        'walton_status'=>0,
                        "request_data"=>json_encode($orderFeeds),
                    ]);
                }
                else
                {
                    DB::table('sales')
                    ->where('id','=',$saleId)
                    ->update([
                        "request_data"=>json_encode($orderFeeds),
                    ]);
                }
                return response()->json(apiResponses(200),200);
            }
            else if($status == 2)
            {
                $getPostCurlResponse = postData(sprintf(RequestApiUrl("SaveSale")),$orderFeeds);
                
                if(!empty($getPostCurlResponse) && $getPostCurlResponse=='success') {
                    DB::table('sales')
                    ->where('id','=',$saleId)
                    ->update([
                        'walton_status'=>0,
                        "request_data"=>json_encode($orderFeeds),
                    ]);
                }
                else
                {
                    DB::table('sales')
                    ->where('id','=',$saleId)
                    ->update([
                        "request_data"=>json_encode($orderFeeds),
                    ]);
                }
                //return response()->json(apiResponses(422,'Un-Register Product Sold Successfully'),422);
                return response()->json(apiResponses(200),200);
            }
            else if($status == 3)
            {
                $getPostCurlResponse = postData(sprintf(RequestApiUrl("SaveSale")),$orderFeeds);
                
                if(!empty($getPostCurlResponse) && $getPostCurlResponse=='success') {
                    DB::table('sales')
                    ->where('id','=',$saleId)
                    ->update([
                        'walton_status'=>0,
                        "request_data"=>json_encode($orderFeeds),
                    ]);
                }
                else
                {
                    DB::table('sales')
                    ->where('id','=',$saleId)
                    ->update([
                        "request_data"=>json_encode($orderFeeds),
                    ]);
                }
                //return response()->json(apiResponses(422,'Pending Order'),422);
                return response()->json(apiResponses(200),200);
            }
            else
            {
                return response()->json(apiResponses(401),401);
            }
        }
        else 
        {
            return response()->json(apiResponses(401),401);
        }
    }
    
    public function offLineSuccessStore()
    {
        foreach($getJsonFeed as $row) 
        {
            $DelarDistributionModel = new DelarDistribution;
            $DelarDistributionModel->setConnection('mysql2');

            $bpId      = 0;
            $retailId  = 0;
            $groupId   = 0;

            if($row->bp_id > 0) {
                $bpId           = $row->bp_id;
                $groupId        = 1;

            } else {
                $retailId       = $row->retailer_id;
                $groupId        = 2;
            }
            $customer_name  = $row->customer_name;
            $customer_phone = $row->customer_phone;
            $saleDate       = $row->date;
            $itemList       = json_decode($row->list);

            $imeProductStatus  = [];
            $imeProductResult  = "";
            $saleStatus        = false;
            $sale_data         = "";
            $saleId            = "";

            foreach ($itemList as $lists) {

                $getImeResult = $DelarDistributionModel::
                where('barcode',$lists->ime_number)
                ->orWhere('barcode2',$lists->ime_number)
                ->first();
                
                if(isset($getImeResult)) 
                {
                    $productStatus   = $getImeResult['status'];
                    $productMasterId = $getImeResult['product_master_id'];
                    $dealerCode      = $getImeResult['dealer_code'];

                    $productColor = DB::table('colors')
                    ->where('color_id',$getImeResult['color_id'])
                    ->value('name');

                    $ZoneId    = 0;
                    if($groupId == 1)
                    {
                        $dealerZoneName = DB::table('dealer_informations')
                        ->where('dealer_code',$dealerCode)
                        ->where('alternate_code',$dealerCode)
                        ->value('zone');

                        if(!empty($dealerZoneName)) {
                            $ZoneId = DB::table('zones')
                            ->where('zone_name','like','%'.$dealerZoneName.'%')
                            ->value('id');
                        }
                    }
                    else
                    {
                        $getZoneId = DB::table('retailers')
                        ->where('retailer_id',$retailId)
                        ->value('zone_id');

                        if($getZoneId != null || !empty($getZoneId)) {
                            $ZoneId = $getZoneId;
                        }
                    }

                    $imeProductResult = DB::table('view_product_master')
                    ->where('product_master_id',$productMasterId)
                    ->first();

                    $getorderStatus   = []; 
                    if($productStatus == 1 && $productMasterId > 0) {
                        $getorderStatus[]   = 0; //order Sold
                    }
                    else {
                        $getorderStatus[]   = 1; //order Pending
                    }

                    $orderStatus = 0;//order Pending 
                    if(in_array(1,$getorderStatus)) {
                        $orderStatus = 1; //order Sold
                    }

                    if($saleStatus === true) {
                        $getorderStatus[] = 1;
                    }
                    
                    $image_64           = trim($row->photo); // image base64 encoded
                    $storagePath='';
                    if(!empty($image_64 )) {
                    $ext                = explode(';base64',$row->photo);
                    $ext                = explode('/',$ext[0]);            
                    $extension          = ($ext[1]) ? $ext[1]:".jpg";

                    $replace            = substr($image_64, 0, strpos($image_64, ',')+1); 
                    $image              = str_replace($replace, '', $image_64); 
                    $image              = str_replace(' ', '+', $image);
                    $imageName          = time().'.'.$extension;

                    Storage::disk('public')->put($imageName, base64_decode($image));

                    $baseUrl        = URL::to('');
                    $storagePath    = $baseUrl.'/storage/app/public/'.$imageName;
                    }
                

                    if($saleStatus === false) {
                        Sale::create([
                            "customer_name"=>$customer_name,
                            "customer_phone"=>$customer_phone,
                            "bp_id"=> $bpId,
                            "retailer_id"=> $retailId,
                            "dealer_code"=> $dealerCode,
                            "sale_date"=>$saleDate,
                            "photo"=> $storagePath,
                            "status"=>0
                        ]);
                        $saleId = DB::getPdo()->lastInsertId();
                        $saleStatus = true;
                        $getorderStatus[] = 0;
                    }

                    if(in_array(1,$getorderStatus)) {
                        Sale::where('id',$saleId)
                        ->update(["status"=>$orderStatus]);
                    }

                    if(!empty($saleId)) {
                        SaleProduct::create([
                            "sales_id"=>$saleId,
                            "ime_number"=> $lists->ime_number,
                            "dealer_code"=> $dealerCode,
                            "product_master_id"=> $productMasterId,
                            "product_id"=> $imeProductResult->product_id,
                            "product_code"=>  $imeProductResult->product_code,
                            "product_type"=> $imeProductResult->product_type,
                            "product_model"=> $imeProductResult->product_model,
                            "product_color"=> $productColor ? $productColor:'Others',
                            "category"=> $imeProductResult->category2,
                            "mrp_price"=> $imeProductResult->mrp_price,
                            "msdp_price"=> $imeProductResult->msdp_price,
                            "msrp_price"=> $imeProductResult->msrp_price,
                            "sale_price"=> $lists->price,
                            "sale_qty"=> $lists->qty,
                            "bp_id"=> $bpId,
                            "retailer_id"=> $retailId,
                            "product_status"=>0 //Sold Order
                        ]);
                        //Ime Database Product Status Update Start
                        $DelarDistributionModel::
                        where('barcode',$lists->ime_number)
                        ->orWhere('barcode2',$lists->ime_number)
                        ->update([
                            "status"=>0,
                        ]);
                    }

                    $sale_data = [
                        "sale_id"=>$saleId,
                        "bp_id"=> $bpId,
                        "retailer_id"=> $retailId,
                        "sale_date"=>$saleDate,
                        "customer_name"=> $customer_name,
                        "customer_phone"=>  $customer_phone
                    ];

                    $saleQty        = $lists->qty;
                    $saleId         = $saleId;
                    $sale_date      = $saleDate;

                    $incentiveType  = $groupId == 1 ? "bp":$retailId;

                    $incentiveLists = DB::table('incentives')
                    ->where('incentive_group',$groupId)
                    ->where('start_date','<=',$sale_date)
                    ->where('end_date','>=',$sale_date)
                    ->where('status',1)
                    ->get();

                    if($incentiveLists->isNotEmpty()) {
                        foreach($incentiveLists as $incentive)
                        {
                            $getModelId         = json_decode($incentive->product_model,TRUE);
                            $getIncentiveType   = json_decode($incentive->incentive_type,TRUE);
                            $getZone            = json_decode($incentive->zone,TRUE);
                            $minQty             = $incentive->min_qty;

                            $totalSaleQty = DB::table('view_sales_reports')
                            ->where('product_master_id',$productMasterId)
                            //->whereBetween('sale_date',[$start_date,$end_date])
                            ->sum('view_sales_reports.sale_qty');
                            

                            if(in_array("all", $getModelId) || in_array($productMasterId, $getModelId)) {
                                if(in_array("all", $getIncentiveType) || in_array($incentiveType, $getIncentiveType)) {
                                    if(in_array("all", $getZone) || in_array($ZoneId, $getZone)) {
                                        //if($totalSaleQty >= $minQty) {

                                            DB::table('sale_incentives')
                                            ->insert([
                                                "incentive_category"=>$incentive->incentive_category,
                                                "ime_number"=>$lists->ime_number,
                                                "sale_id" =>$saleId, 
                                                "bp_id" =>$bpId,
                                                "retailer_id"=>$retailId,
                                                "incentive_title"=>$incentive->incentive_title,
                                                "product_model"=>$imeProductResult->product_model,
                                                "zone"=>$incentive->zone,
                                                "incentive_amount"=>$incentive->incentive_amount,
                                                "incentive_min_qty"=>$incentive->min_qty,
                                                "incentive_sale_qty"=>$saleQty,
                                                "total_amount"=>$saleQty*$incentive->incentive_amount,
                                                "start_date"=>$incentive->start_date,
                                                "end_date"=>$incentive->end_date,
                                                "incentive_date"=>date('Y-m-d'),
                                                "incentive_status"=>$incentive->status
                                            ]);

                                        //}
                                    }
                                }
                            }
                            
                        }
                    }
                }
                else
                {
                    if($saleStatus === false) {
                    	Sale::create([
                    		"customer_name"=>$customer_name,
                    		"customer_phone"=>$customer_phone,
                    		"bp_id"=> $bpId,
                    		"retailer_id"=> $retailId,
                    		"dealer_code"=> "",
                    		"sale_date"=>$saleDate,
                    		"photo"=> "",
                    		"status"=>0
                    	]);
                    	$saleId = DB::getPdo()->lastInsertId();
                    	$saleStatus = true;
                    	$getorderStatus[] = 0;
                    }

                    if(in_array(1,$getorderStatus)) {
                    	Sale::where('id',$saleId)
                    	->update(["status"=>$orderStatus]);
                    }

                    if(!empty($saleId)) {
                    	SaleProduct::create([
                    		"sales_id"=>$saleId,
                    		"ime_number"=> $lists->ime_number,
                    		"dealer_code"=> $dealerCode,
                    		"product_master_id"=> $productMasterId ? $productMasterId:0,
                    		"product_id"=> 0,
                    		"product_code"=>  0,
                    		"product_type"=> 0,
                    		"product_model"=> 0,
                    		"product_color"=> 'Others',
                    		"category"=> 0,
                    		"mrp_price"=> '0.00',
                    		"msdp_price"=> '0.00',
                    		"msrp_price"=> '0.00',
                    		"sale_price"=> $lists->price,
                    		"sale_qty"=> $lists->qty,
                    		"bp_id"=> $bpId,
                    		"retailer_id"=> $retailId,
                    		"product_status"=>0 //Sold Order
                    	]);
                    	//Ime Database Product Status Update Start
                    	$DelarDistributionModel::
                    	where('barcode',$lists->ime_number)
                    	->orWhere('barcode2',$lists->ime_number)
                    	->update([
                    		"status"=>0,
                    	]);
                    }

                    $sale_data = [
                    	"sale_id"=>$saleId,
                    	"bp_id"=> $bpId,
                    	"retailer_id"=> $retailId,
                    	"sale_date"=>$saleDate,
                    	"customer_name"=> $customer_name,
                    	"customer_phone"=>  $customer_phone
                    ];                    
                }
            }
                
        }

        if (isset($sale_data) && !empty($sale_data)) {
            return response()->json(apiResponses(200),200);
        } else {
            return response()->json(apiResponses(404),404);
        }
    }
    
    public function getRetailerLiftingIncentive(Request $request)
    {
        $headerAuth     = $request->header('Authorization'); 
        $token          = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus   = $this->verifyApiAuth($headerAuth,$token);
        
        if(isset($verifyStatus) && $verifyStatus === true) {
            $retailerId = 0;
            if($request->retailerId) {
                $retailerId = $request->retailerId;
            } else {
                $retailerId = auth('api')->user()->id;
            }

            $startDate = $request->startDate;
            $endDate   = $request->endDate;

            $retailerPhone = DB::table('view_retailer_list')
            ->where('id',$retailerId)
            ->value('phone_number');

            $getCurlResponse    = getData(sprintf(RequestApiUrl("GetRetailerLiftingIncentive"),$startDate,$endDate,$retailerPhone),"GET");
            $responseData       = json_decode($getCurlResponse['response_data'],true);
            
            $totalAmount = 0;
            foreach($responseData as $row) {
                $totalAmount += $row['RetailerAmount'];
            }
            
            if(isset($responseData)) {
                if(count($responseData) > 0) {
                    Log::info('Get Retailer Lifting Incentive');
                    return response()->json(["RetailerLiftingIncentive"=>$responseData,"liftingIncentiveAmount"=>$totalAmount],200);
                } else {
                    Log::info('Retailer Lifting Incentive Not Found');
                    return response()->json(apiResponses(404),404);
                }
            } else {
                return response()->json(apiResponses(404),404);exit();
            }
        } else {
            return response()->json(apiResponses(401),401);
        }
    }
    
    public function getPreBookingList(Request $request)
    {
        $headerAuth     = $request->header('Authorization'); 
        $token          = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus   = $this->verifyApiAuth($headerAuth,$token);

        if(isset($verifyStatus) && $verifyStatus === true) {
            
            $requestDate    = date('Y-m-d');

            $preBookingList = PreBookin::select('id','model as Model','color as Color','start_date as startDate','end_date as EndDate','minimum_advance_amount as MinimumAdvanceAmount','max_qty as MaxQty','price as Price')
            ->where('start_date', '<=', $requestDate)
            ->where('end_date', '>=', $requestDate)
            ->where('status','=',1)
            ->get();
            
            
            $bookingListItems = [];
            foreach($preBookingList as $key=>$row)
            {
                $bookingListItems[$key] = [
                    "id"=> $row->id,
                    "Model"=> $row->Model,
                    //"Color"=> $row->Color,
                    "startDate"=> $row->startDate,
                    "EndDate"=> $row->EndDate,
                    "MinimumAdvanceAmount"=> $row->MinimumAdvanceAmount,
                    "MaxQty"=> $row->MaxQty,
                    "Price"=> $row->Price
                ];
                $bookingListItems[$key]["Color"] = explode(',',$row->Color);
            }

            if(isset($preBookingList) && $preBookingList->isNotEmpty()) {
                Log::info('Get Pre-Booking List By Apps');
                //return response()->json($preBookingList,200);
                return response()->json($bookingListItems,200);
            } else {
                Log::warning('Pre-Booking List Not Found');
                return response()->json(apiResponses(404),404);
            }
        }
        else {
            return response()->json(apiResponses(401),401);
        }
    }

    public function OrderPreBooking(Request $request)
    {
        $headerAuth     = $request->header('Authorization'); 
        $token          = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus   = $this->verifyApiAuth($headerAuth,$token);
        
        if(isset($verifyStatus) && $verifyStatus === true) {

            $userId     = auth('api')->user()->id;
            $userExists = DB::table('users')
            ->where('id',$userId)
            ->first();

            $bpId      = 0;
            $retailId  = 0;
            
            if($userExists->bp_id > 0 && $userExists->bp_id != NULL) {
                $bpId = $userExists->bp_id;
                
                $getBpInfo     = BrandPromoter::select('bp_id','retailer_id')
                ->where('id','=',$bpId)
                ->first();
                $retailId      = $getBpInfo->retailer_id;
            }
            elseif($userExists->retailer_id > 0 && $userExists->retailer_id != NULL) {
                $retailId = $userExists->retailer_id;
                //$bpId       = BrandPromoter::where('retailer_id','=',$retailId)->value('bp_id');
            }
    
            $preBookingId       = $request->input('pre_booking_id');
            $customerName       = $request->input('customer_name');
            $customerPhone      = $request->input('customer_phone');
            $customerAddress    = $request->input('customer_address');
            $model              = $request->input('model');
            $color              = $request->input('color');
            $qty                = $request->input('qty');
            $advanced_payment   = $request->input('advanced_payment');
            $bookingDate        = date('Y-m-d');

            $checkCustomerBookingQty = DB::table('prebooking_orders')
            ->where('prebooking_id',$preBookingId)
            ->where('customer_phone','=',$customerPhone)
            ->where('model','=',$model)
            ->sum('qty');

            $totalBookingQty = $checkCustomerBookingQty + $qty;

            $checkStatus = PreBookin::where('model','=',$model)
            ->where('model','=',$model)
            ->where('max_qty','>=',$totalBookingQty)
            ->where('minimum_advance_amount','<=',$advanced_payment)
            ->where('start_date', '<=', $bookingDate)
            ->where('end_date', '>=', $bookingDate)
            ->first();

            if($checkStatus) {

                $orderStatus = DB::table('prebooking_orders')
                ->insert([
                    "prebooking_id" =>$checkStatus->id,
                    "bp_id"=>$bpId,
                    "retailer_id"=>$retailId,
                    "customer_name" =>$customerName,
                    "customer_phone" =>$customerPhone,
                    "customer_address" =>$customerAddress,
                    "model" => $model,
                    "color" => $color,
                    "qty" => $qty,
                    "advanced_payment" => $advanced_payment,
                    "booking_date" => date('Y-m-d H:i:s'),
                    "created_at"=>Carbon::now(),
                    "updated_at"=>Carbon::now()
                ]);

                if($orderStatus) {
                    Log::info('Order Pre-Booking Success');
                    return response()->json(apiResponses(200),200);
                } else {
                    Log::info('Order Pre-Booking Failed');
                    return response()->json(apiResponses(401),401);
                }
            } 
            else {
                Log::error('Invalid Pre-Booking Order');
                return response()->json(apiResponses(406),406);
            }
        } else {
            return response()->json(apiResponses(401),401);
        }
    }

    public function getPreBookingOrderList(Request $request)
    {
        $headerAuth     = $request->header('Authorization'); 
        $token          = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus   = $this->verifyApiAuth($headerAuth,$token);

        if(isset($verifyStatus) && $verifyStatus === true) {

            $userId     = auth('api')->user()->id;
            $userExists = DB::table('users')
            ->where('id',$userId)
            ->first();

            $startDate = $request->startDate;
            $endDate   = $request->endDate;

            $bpId      = 0;
            $retailId  = 0;
            
            if($userExists->bp_id > 0 && $userExists->bp_id != NULL) {
                $bpId = $userExists->bp_id;
                $getBpInfo     = BrandPromoter::select('bp_id','retailer_id')
                ->where('id','=',$bpId)
                ->first();
                $retailId      = $getBpInfo->retailer_id;
            }
            elseif($userExists->retailer_id > 0 && $userExists->retailer_id != NULL) {
                $retailId = $userExists->retailer_id;
            }

            $preBookingOrderList =  DB::table('prebooking_orders')
            ->select('customer_name as CustomerName','customer_phone as CustomerPhone','customer_address as CustomerAddress','model as Model','color as Color','qty as Qty','advanced_payment as AdvancedPayment','booking_date as BookingDate')
            ->where(function($sql_query) use($bpId,$retailId){
                if($bpId > 0){
                    $sql_query->where('bp_id','=', $bpId);
                }
                if($retailId > 0){
                    $sql_query->where('retailer_id','=', $retailId);
                }
            })
            //->where('bp_id',$bpId)
            //->where('retailer_id',$retailId)
            ->whereBetween('booking_date',[$startDate,$endDate])
            ->get();

            if(isset($preBookingOrderList) && $preBookingOrderList->isNotEmpty()) {
                Log::info('Get Pre-Booking List By Apps');
                return response()->json($preBookingOrderList,200);
            } else {
                Log::warning('Pre-Booking List Not Found By Apps');
                return response()->json(apiResponses(404),404);
            }
        }
        else {
            return response()->json(apiResponses(401),401);
        }
    }
    
    public function deviceRegistraction(Request $request)
    {
        $headerAuth     = $request->header('Authorization'); 
        $token          = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus   = $this->verifyApiAuth($headerAuth,$token);

        //dd($request->all());

        if(isset($verifyStatus) && $verifyStatus === true) {

            $userId     = auth('api')->user()->id;
            $userExists = DB::table('users')
            ->where('id',$userId)
            ->first();

            $bp_id              = $request->input('bp_id');
            $retailer_id        = $request->input('retailer_id');
            $registration_id    = $request->input('registration_id');
            $uuid               = $request->input('uuid');
            $platform           = $request->input('platform');
            $version            = $request->input('version');
            $intsallation_date  = $request->input('intsallation_date');

            $deviceRegistrationCheck = DB::table('device_registrations')
            ->where('registration_id','=',$registration_id)
            ->first();
            $status = 0;
            if($deviceRegistrationCheck) {
                $updateRegistraction = DB::table('device_registrations')
                ->where('registration_id','=',$registration_id)
                ->update([
                    "bp_id"=> $request->input('bp_id'),
                    "retailer_id"=> $request->input('retailer_id'),
                     "uuid"=>$uuid,
                    "platform"=> $request->input('platform'),
                    "version"=> $request->input('version'),
                    "intsallation_date"=> $request->input('intsallation_date'),
                    "updated_at"=>Carbon::now()
                ]);
                 $status = 1;
                if($updateRegistraction) {
              
                    Log::info('Device Registraction Success');
                }
            } else {

                $addRegistraction = DB::table('device_registrations')
                ->insert([
                    "bp_id"=> $request->input('bp_id'),
                    "retailer_id"=> $request->input('retailer_id'),
                    "registration_id"=> $request->input('registration_id'),
                    "uuid"=>$uuid,
                    "platform"=> $request->input('platform'),
                    "version"=> $request->input('version'),
                    "intsallation_date"=> $request->input('intsallation_date'),
                    "created_at"=>Carbon::now(),
                    "updated_at"=>Carbon::now()
                ]);
                if($addRegistraction) {
                    $status = 1;
                    Log::info('Device Registraction Success');
                }
            }

            if($status == 1) {
                return response()->json(apiResponses(200),200);
            } else {
                Log::warning('Device Registraction Failed ->OutSourceApiController->deviceRegistraction');
                return response()->json(apiResponses(200),200);
            }

        }
        else {
            return response()->json(apiResponses(401),401);
        }
    }
    
    public function SendPushNotification($id)
    {
        $PushNotificationInfo = PushNotification::where('id',$id)->first();
        return response()->json('success');
    }

    public function getMonthlySalesPercentage(Request $request)
    {
        $headerAuth     = $request->header('Authorization'); 
        $token          = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus   = $this->verifyApiAuth($headerAuth,$token);
        
        if(isset($verifyStatus) && $verifyStatus === true) 
        {
            $bpId           = 0;
            $retailerId     = 0;
            $incentiveGroup = 0;
            $incentiveCat   = "target";
            $zoneId         = 0;
            $month_Sdate    = date('Y-m-01');
            $month_Edate    = date('Y-m-t');
            $logedUserId    = 0;


            $userId     = auth('api')->user()->id;
            $userExists = DB::table('users')
            ->where('id',$userId)
            ->first();


            
            if($userExists->bp_id > 0 && $userExists->bp_id != NULL) 
            {
                $bpId = $userExists->bp_id;

                $incentiveGroup     = 1; //Brand Promoter

                $zoneName = DB::table('brand_promoters')
                ->where('id',$bpId)
                ->where('status',1)
                ->value('distributor_zone');

                $zoneId = DB::table('zones')
                ->where('zone_name',$zoneName)
                ->value('id');

                $logedUserId = $bpId;
            }
            elseif($userExists->retailer_id > 0 && $userExists->retailer_id != NULL) 
            {
                $retailerId = $userExists->retailer_id;
                $incentiveGroup  = 2; //Retailer

                $zoneId = DB::table('retailers')
                ->where('id',$retailerId)
                ->where('status',1)
                ->value('zone_id');

                $logedUserId = $retailerId;
            }

            $incentiveAvailabelOrNot = DB::table('incentives')
            ->where('incentive_group','=',$incentiveGroup)
            //->where('incentive_amount','>',0)
            //->where('start_date','<=',$month_Sdate)
            //->orWhere('start_date','>=',$month_Sdate)
            //->where('end_date','>=',$month_Edate)
            ->whereBetween(DB::raw("DATE_FORMAT(end_date,'%Y-%m-%d')"),[$month_Sdate,$month_Edate])
            ->where('status','=',1)
            ->get();

            //echo "<pre>";print_r($incentiveAvailabelOrNot);echo "</pre>";exit();


            $targetSaleQty  = 0;
            $totalSaleQty   = 0;
            $salePercent    = 0;

            $saleQtyArray   = [];

            if($incentiveAvailabelOrNot->isNotEmpty()) {
                foreach($incentiveAvailabelOrNot as $key=>$incentive) {
                    $getModelId         = json_decode($incentive->product_model,TRUE);//All Or Id
                    $getIncentiveType   = json_decode($incentive->incentive_type,TRUE);//BP Or Retailer
                    $getZone            = json_decode($incentive->zone,TRUE);//All Or Zone Id
                    $groupCatIds        = explode(',', $incentive->group_category_id);//A,B,C,D..etc
                    $incentiveCatName   = $incentive->incentive_category;//Target Or General

                    $incentiveType      = $incentive->incentive_group == 1 ? "bp":"retailer";
                    $incentiveSDate     = $incentive->start_date;
                    $incentiveEDate     = $incentive->end_date;
                    $targetQty          = $incentive->min_qty;
                    $targetSaleQty      = $incentive->min_qty;

                    if(in_array("all", $getModelId)) 
                    {

                        $totalSaleQty = DB::table('view_sales_reports')
                        ->where('status','=',0)
                        ->whereBetween(DB::raw("DATE_FORMAT(sale_date,'%Y-%m-%d')"),[$month_Sdate,$month_Edate])
                        ->where(function($sql_query) use($getModelId,$getIncentiveType,$getZone,$groupCatIds,$incentiveType,$bpId,$retailerId) {
                            if($getModelId) {
                                if(in_array("all", $getModelId)) {
                                    $sql_query->whereNotNull('product_master_id');
                                }
                                else {
                                    $sql_query->whereIn('product_master_id',$getModelId);
                                }
                            }

                            /*if($getIncentiveType) {
                                if(in_array("all", $getIncentiveType)) {
                                    $sql_query->whereNotNull('bp_id');
                                    $sql_query->whereNotNull('retailer_id');
                                }
                                else {
                                    if(in_array("bp", $getIncentiveType)){
                                        $sql_query->whereNotNull('bp_id');
                                    } else if(in_array("retailer", $getIncentiveType)){
                                        $sql_query->whereNotNull('retailer_id');
                                    }
                                }
                            }*/

                            if($getZone) {
                                if(in_array("all", $getZone)) {
                                    $sql_query->whereNotNull('zone_id');
                                }
                                else {
                                    $sql_query->whereIn('zone_id',$getZone);
                                }
                            }

                            if($groupCatIds) {
                                if($incentiveType == "bp"){
                                    $sql_query->whereIn('bp_category_id',$groupCatIds);
                                } else if($incentiveType == "retailer") {
                                    $sql_query->whereIn('retailer_category_id',$groupCatIds);
                                }
                            }

                            if($bpId) {
                                $sql_query->where('bp_id', '=', $bpId);
                            }

                            if($retailerId) {
                                $sql_query->where('retailer_id', '=', $retailerId);
                            }
                        })
                        ->orderBy('id','DESC')
                        ->count();

                        $saleQtyArray [$targetSaleQty] = $totalSaleQty;

                        if($totalSaleQty > 0)
                        {
                            $salePercent += round(($totalSaleQty * 100) / $targetSaleQty, 2);
                        }

                    }
                }
            }

            if($salePercent > 0 ) {
                return response()->json($salePercent,200);
            } else {
                return response()->json($salePercent,200);
            }
        }
        else 
        {
            return response()->json(apiResponses(401),401);
        }
    }
    
    public function getPushNotificationList(Request $request)
    {
        $headerAuth     = $request->header('Authorization'); 
        $token          = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus   = $this->verifyApiAuth($headerAuth,$token);

        if(isset($verifyStatus) && $verifyStatus === true)
        {
            $userId     = auth('api')->user()->id;
            $userExists = DB::table('users')
            ->where('id',$userId)
            ->first();

            $successNotificationList = PushNotification::select('title','message','date')
            ->where('send_status',1)
            ->get();

            if(isset($successNotificationList) && $successNotificationList->isNotEmpty()) {
                Log::info('Get Push Notification List By Apps');
                return response()->json($successNotificationList,200);
            } else {
                Log::warning('Pre-Booking List Not Found By Apps');
                return response()->json(apiResponses(404),404);
            }
        }
        else 
        {
            return response()->json(apiResponses(401),401);
        }
    }
    
    //Use For Cron Job EveryDay Mid Night Start
    public function storeDealerFromApi(Request $request) {
        $authenticateUser = $this->getAuthenticatedUser();
        $headerAuth = $request->header('Authorization'); 
        $token = str_replace("Bearer ", "", $request->header('Authorization'));
        $verifyStatus = $this->verifyApiAuth($headerAuth,$token);

        if (isset($verifyStatus) && $verifyStatus === true) {
            $userId = auth('api')->user()->id;
            $userExists = DB::table('users')->where('id',$userId)->first();

            $loginUserId = 0;
            if ($userExists->bp_id > 0) {
                $loginUserId = $userExists->bp_id;
            } else if($userExists->retailer_id > 0) {
                $loginUserId = $userExists->retailer_id;
            }

            $getCurlResponse = getData(RequestApiUrl("DealerAll"),"GET");
            $responseData = json_decode($getCurlResponse['response_data'],true);

            if (isset($getCurlResponse) && $getCurlResponse['status'] == 200) {
                $totalInsertRow = 0;
                foreach ($responseData as $row) {
                    $totalInsertRow += 1;
                    $DealerID = $row['Id'];
                    $status = ($row['IsActive'] == true)?1:0; 
                    $CheckDealer = DealerInformation::where('dealer_id',$DealerID)->first();
                    if ($CheckDealer) {
                        $UpdateDealerInfo = DealerInformation::where('dealer_id',$DealerID)->update([
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
            }
        }
    }
    //Use For Cron Job EveryDay Mid Night End
    
    public function getAppInfo() {
        $appInfo = [
            'ios_version_number' => 0.0,
            'android_version_number' => 0.0,
            'ios_update_message' => 'Update your ios app to get latest feature.',
            'android_update_message' => 'Update Your android app to get latest feature.',
            "release_date"=>date('01-02-2022'),
            "is_force_update"=>true,
            "is_off_android"=>true,
            "is_off_ios"=>true,
        ];
        if ($appInfo) {
            return response()->json($appInfo,200);
        } else {
            return response()->json(apiResponses(401),401);
        }
    }
}