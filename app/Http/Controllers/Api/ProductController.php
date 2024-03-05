<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Products;
use App\Models\ProductMasterPrice;
use App\Models\ProductChangeLog;
use App\Models\Category;
use DB;
use Validator;
use Carbon\Carbon;
use Response;

class ProductController extends Controller
{    
    public function index(Request $request) {
        $product_list = DB::table('view_product_master')->orderBy('product_master_id','desc')->get();
        $categories = Category::orderBy('name','asc')->get();

        if (isset($product_list) && $product_list->isNotEmpty()) {
            Log::info('Load Product List');
        } else {
            Log::warning('Product List Not Found');
        }
        return view('admin.product.list',compact('product_list','categories'));
    }

    public function create() {
        // 
    }
    
    public function store(Request $request) {
        // dd($request->all());
        $rules = ['product_model'=>'required','mrp_price'=>'required'];

        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            Log::error('Create Product Validation Failed');
            return response()->json(['fail'=>true,'errors'=>$validator->errors()]);
        }

        $product_id = generate_random_string(5,4,'num-low');

        $AddProductInfo = Products::create([
            "product_id"=> $product_id,
            "category_id"=> $request->categoryId,
            "product_code"=> $request->input('product_code'),
            "product_type"=> $request->input('product_type'),
            "product_model"=> $request->input('product_model'),
            "category2"=> $request->input('category'),
            "created_at"=> Carbon::now(),
            "updated_at"=> Carbon::now()
        ]);

        $AddProductPrice = ProductMasterPrice::create([
            "product_id"=>$AddProductInfo->id,
            "mrp_price"=>$request->input('mrp_price'),
            "msdp_price"=>$request->input('msdp_price'),
            "msrp_price"=>$request->input('msrp_price'),
            "created_at"=> Carbon::now(),
            "updated_at"=> Carbon::now()
        ]);

        $status = 1; 
        Log::info('Create Product Successfully');
        return response()->json('success');

        if ($status == 0) {
            return response()->json('error');
        } else {
            return response()->json('success');
        }
    }

    public function show($id) {
        $getInfo = DB::table('view_product_master')->where('product_master_id',$id)->first();
        $html = view('admin.product.product_details_table')->with(compact('getInfo'))->render();
        return response()->json($html);
    }
    
    public function edit($id) {
        if (isset($id) && $id > 0) {
            $ProductInfo = DB::table('view_product_master')->where('product_master_id',$id)->first();
            $categories = Category::orderBy('name','asc')->get();

            if ($ProductInfo) {
                $html = view('admin.product.edit_form')->with(compact('ProductInfo','categories'))->render();
                Log::info('Get Product By Id');
                return response()->json($html); 
            } else {
                Log::warning('Product Not Found By Id');
                return response()->json('error');
            }
        } else {
            Log::warning('Invalid Product Id');
            return response()->json('error');
        }
    }
    
    public function update(Request $request) {
        // dd($request->all());
        $id = $request->input('product_master_id');
        $rules = ['product_model'=>'required','mrp_price'=>'required'];

        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            Log::error('Product Validation Failed');
            return response()->json(['fail'=>true,'errors'=>$validator->errors()]);
        }

        $CheckProduct = Products::where('product_master_id',$id)->first();
        if (isset($CheckProduct) && !empty($CheckProduct)) {
            $status = 0;
            $UpdateProductInfo = Products::where('product_master_id',$id)->update([
                "product_id"=> $request->input('product_id'),
                "category_id"=> $request->categoryId,
                "product_code"=>  $request->input('product_code'),
                "product_type"=> $request->input('product_type'),
                "product_model"=> $request->input('product_model'),
                "category2"=> $request->input('category'),
                "updated_at"=> Carbon::now()
            ]);

            if ($UpdateProductInfo) {
                $CheckProductPrice = ProductMasterPrice::where('product_id',$id)->first();

                if (isset($CheckProductPrice) && !empty($CheckProductPrice)) {
                    $UpdateProductPrice = ProductMasterPrice::where('product_id',$id)->update([
                        "product_id"=> $id,
                        "mrp_price"=>  $request->input('mrp_price'),
                        "msdp_price"=>  $request->input('msdp_price'),
                        "msrp_price"=>  $request->input('msrp_price'),
                        "updated_at"=> Carbon::now()
                    ]);

                    $productChangeLog = ProductChangeLog::create([
                        "product_id"=>$id,
                        "old_mrp_price"=>$CheckProductPrice['mrp_price'],
                        "old_msdp_price"=>$CheckProductPrice['msdp_price'],
                        "old_msrp_price"=>$CheckProductPrice['msrp_price'],
                        "new_mrp_price"=>$request->input('mrp_price'),
                        "new_msdp_price"=>$request->input('msdp_price'),
                        "new_msrp_price"=>$request->input('msrp_price'),
                        "updated_at"=> Carbon::now()
                    ]);

                    Log::info('Existing Product Updated');
                    $status = 1; 
                } else {
                    $AddProductPrice = ProductMasterPrice::create([
                        "product_id"=>$id,
                        "mrp_price"=>$request->input('mrp_price'),
                        "msdp_price"=>$request->input('msdp_price'),
                        "msrp_price"=>$request->input('msrp_price'),
                        "created_at"=>Carbon::now(),
                        "updated_at"=>Carbon::now()
                    ]);

                    $productChangeLog = ProductChangeLog::create([
                        "product_id"=>$id,
                        "old_mrp_price"=>$request->input('mrp_price'),
                        "old_msdp_price"=>$request->input('msdp_price'),
                        "old_msrp_price"=>$request->input('msrp_price'),
                        "new_mrp_price"=>$request->input('mrp_price'),
                        "new_msdp_price"=>$request->input('msdp_price'),
                        "new_msrp_price"=>$request->input('msrp_price'),
                        "created_at"=>Carbon::now(),
                        "updated_at"=>Carbon::now()
                    ]);
                    Log::info('Create New Product');
                    $status = 1; 
                }
            }

            if ($status == 1) {
                Log::info('Existing Product Updated');
                return response()->json('success');
            } else {
                Log::error('Existing Product Updated Failed');
                return response()->json('error');
            }
        }
        else {
            Log::error('Existing Product Updated Failed');
            return response()->json('error');
        }
    }

    public function ChangeStatus($id) {
        if(isset($id) && $id > 0) {
            $StatusInfo = Products::where('product_master_id',$id)->first();
            $old_status = $StatusInfo->status;
            $UpdateStatus = $old_status == 1 ? 0 : 1;

            $UpdateDealerStatus = Products::where('product_master_id',$id)->update([
                "status"=> $UpdateStatus ? $UpdateStatus:0
            ]);

            if ($UpdateDealerStatus) {
                Log::info('Existing Product Status Change Success');
                return response()->json(['success'=>'Status change successfully.']);
            } else {
                Log::warning('Existing Product Status Change Failed');
                return response()->json(['error'=>'Status Update Failed.Please Try Again.']);
            }
        } else {
            Log::warning('Invalid Product Id');
            return response()->json('error');
        }
    }

    public function destroy($id) {
        //
    }

    public function productStockEdit($productId) {
        if (isset($productId) && $productId > 0) {
            $productInfo = Products::where('product_master_id',$productId)->select('product_master_id','default_qty','yeallow_qty','red_qty')->first();

            if ($productInfo) {
                $html = view('admin.product.product_stock_form')->with(compact('productInfo'))->render();
                return response()->json($html);
            }
            return response()->json('error');
        }
    }

    public function saveProductStockMaintain(Request $request) {
        $productId = $request->input('product_id');
        $default_qty = $request->input('default_qty');
        $yeallow_qty = $request->input('yeallow_qty');
        $red_qty = $request->input('red_qty');

        if (isset($productId) && $productId != null || $productId > 0) {
            $success = Products::where('product_master_id',$productId)->update([
                "default_qty"=>$default_qty,
                "yeallow_qty"=>$yeallow_qty,
                "red_qty"=>$red_qty,
                "updated_at"=>Carbon::now()
            ]);

            if ($success) {
                return response()->json('success');
            }
        }
        return response()->json('error');
    }
}