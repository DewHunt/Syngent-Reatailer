<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\AuthorityMessage;
use App\Models\DelarDistribution;
use App\Models\DealerInformation;
use App\Models\Employee;
use App\Models\BpAttendance;
use App\Models\BrandPromoter;
use App\Models\Retailer;
use App\Models\Zone;
use App\Models\User;
use App\Models\Products;
use App\Models\PushNotification;
use App\Models\Incentive;
use App\Models\SpecialAward;
use App\Models\RetailerProductStock;
use Carbon\Carbon;
use DB;
use Response;
use Validator;
use Pagination;
use DataTables;
use Storage;
use Mail;
use Session;
use File;
date_default_timezone_set('Asia/Dhaka');
class ReportController extends Controller
{    
    public function report_dashboard() {
    	Log::info('Load Report Dashboard');
        return view('admin.report.dashboard');
    }

    public function bpSearch(Request $request) {
    	$search = $request->search;
        $bpList = "";

		if ($search == '')  {
			$bpList = BrandPromoter::orderby('bp_name','asc')->select('id','bp_name','bp_phone')->get();
		} else  {
			$bpList = BrandPromoter::orderby('bp_name','asc')
    			->select('id','bp_name','bp_phone')
    			->where('bp_name', 'like', '%' .$search . '%')
    			->orWhere('bp_phone','like', '%' .$search . '%')
    			->orWhere('bp_id', $search)
    			->get();
		}

        $response = array();
        foreach ($bpList as $row) {
            $label = $row->bp_name." (Mo:".$row->bp_phone.")";
            $response[] = array("value"=>$row->id,"label"=>$label);
        }
        return response()->json($response);
    }

    public function retailerSearch(Request $request) {
    	$search = $request->search;
        $retailList = "";
		if ($search == '')  {
			$retailList = Retailer::orderby('retailer_name','asc')->select('id','retailer_name','phone_number')->get();
		} else  {
			$retailList = Retailer::orderby('retailer_name','asc')
    			->select('id','retailer_name','phone_number')
    			->where('retailer_name', 'like', '%' .$search . '%')
    			->orWhere('phone_number','like', '%' .$search . '%')
    			->orWhere('retailer_id', $search)
    			->get();
		}

        $response = array();
        foreach ($retailList as $row) {
            $label = $row->retailer_name." (Mo:".$row->phone_number.")";
            $response[] = array("value"=>$row->id,"label"=>$label);
        }
        return response()->json($response);
    }

    public function salesReportForm(Request $request) {
    	Session::forget('salesBPId');
        Session::forget('salesRetailerId');

        if ($request->ajax()) {
            $sort_by = $request->get('sortby');
            $sort_type = $request->get('sorttype');
            $query = $request->get('query');
            $query = str_replace(" ", "%", $query);

            $month_Sdate = Session()->get('salesSdate');
            $month_Edate = Session()->get('salesEdate');
            
            $saleList = DB::table('view_sales_reports')
                ->whereBetween(\DB::raw("DATE_FORMAT(sale_date, '%Y-%m-%d')"),[$month_Sdate,$month_Edate])
                ->where('status','=',0)
                ->where(function($sql_query) use($query){
                    if ($query !=null || !empty($query)) {
                        $sql_query->where('retailer_name','like', '%'.$query.'%')
                            ->orWhere('retailer_phone_number','like','%'.$query.'%')
                            ->orWhere('customer_name','like', '%'.$query.'%')
                            ->orWhere('customer_phone','like', '%'.$query.'%');
                    }  
                })
                ->orderBy($sort_by, $sort_type)
                ->groupBy('id')
                ->paginate(100);

            return view('admin.report.sales_report_result_data', compact('saleList'))->render();
        } else {        
            // $bpList = BrandPromoter::get(['bp_id','bp_name']);
            // $retailerList = Retailer::get(['retailer_id','retailer_name']);
            $month_Sdate = $request->input('from_date');
            if (empty($month_Sdate)) {
                $month_Sdate = date('Y-m-01');
            }
            $month_Edate = $request->input('to_date');
            if (empty($month_Edate)) {
                $month_Edate = date('Y-m-t');
            }
            Session::put('salesSdate',$month_Sdate);
            Session::put('salesEdate',$month_Edate);
            $saleList = "";

            $saleList = DB::table('view_sales_reports')
                ->where('status','=',0)
                ->whereBetween(DB::raw("DATE_FORMAT(sale_date,'%Y-%m-%d')"),[$month_Sdate,$month_Edate])
                ->orderBy('id','DESC')
                ->paginate(100);
        }

        if (isset($saleList) && $saleList->isNotEmpty()) {
            Log::info('Load Product Sale List');
        } else {
            Log::warning('Product Sale List Not Found');
        }
    	return view('admin.report.sales_report',compact('saleList'));
    }
    
    public function OrderDetailsView(Request $request) {
        $saleId = $request->saleId;
        $salesInfo = DB::table('view_sales_reports')->where('id',$saleId)->first();
        $saleProductList = DB::table('view_sales_product_reports')->select('*')->where('sale_id',$saleId)->get();
        $orderDetailsView = View('admin.report.order_detail_view',compact('salesInfo','saleProductList'))->render();
        if ($orderDetailsView) {
            Log::info('Load Sales Info');
            return response()->json(['orderDetailsInfo'=>$orderDetailsView,'saleId'=>$saleId]);
        } else {
            Log::warning('Sales Info Not Found');
             return response()->json('error');
        }
    }

    public function dateRangesalesReport(Request $request) {
        $getBPId = $request->input('bp_id');
        $getRetailerId = $request->input('retailer_id');
        $salesSdate = $request->input('start_date');
        $salesEdate = $request->input('end_date');

        Session::put('salesBPId',$getBPId);
        Session::put('salesRetailerId',$getRetailerId);
        Session::put('salesSdate',$salesSdate);
        Session::put('salesEdate',$salesEdate);

        $salesBPId = Session::get('salesBPId');
        $salesRetailerId = Session::get('salesRetailerId');
        
        $sellerName = "";
        if ($salesBPId != null || !empty($salesBPId)) {
            $sellerName = BrandPromoter::where('status','=',1)->where('id','=',$salesBPId)->value('bp_name');
        } else if ($salesRetailerId != null || !empty($salesRetailerId)) {
            $sellerName = Retailer::where('status','=',1)->where('id','=',$salesRetailerId)->value('retailer_name');
        }

        $startDate = Session::get('salesSdate') ? Session::get('salesSdate'):date('Y-m-01');
        $endDate = Session::get('salesEdate') ? Session::get('salesEdate'):date('Y-m-t');
        
        if ($request->ajax()) {
            $sort_by = $request->get('sortby');
            $sort_type = $request->get('sorttype');
            $query = $request->get('query');
            $query = str_replace(" ", "%", $query);

            $saleList = DB::table('view_sales_reports')
                ->where('status','=',0)
                ->whereBetween(DB::raw("DATE_FORMAT(sale_date,'%Y-%m-%d')"),[$startDate,$endDate])
                ->where(function($sql_query) use($query,$salesBPId,$salesRetailerId) {
                    if ($query != null || !empty($query)) {
                        $sql_query->where('bp_name','like', '%'.$query.'%')
                            ->orWhere('bp_phone','like', '%'.$query.'%')
                            ->orWhere('retailer_name','like', '%'.$query.'%')
                            ->orWhere('retailer_phone_number','like','%'.$query.'%')
                            ->orWhere('dealer_name','like', '%'.$query.'%')
                            ->orWhere('dealer_phone_number','like', '%'.$query.'%')
                            ->orWhere('dealer_code','like','%'.$query.'%')
                            ->orWhere('customer_name','like', '%'.$query.'%')
                            ->orWhere('customer_phone','like', '%'.$query.'%');
                    } 

                    if ($salesBPId > 0) {
                        $query->where('bp_id','=',$salesBPId);
                    } 
                    if ($salesRetailerId > 0) {
                        $query->where('retailer_id','=',$salesRetailerId);
                    }
                })
                ->orderBy($sort_by, $sort_type)
                ->paginate(100);
        
            return view('admin.report.sales_report_result_data', compact('saleList'))->render();
        } else {
            $saleList = DB::table('view_sales_reports')
                ->whereBetween(\DB::raw("DATE_FORMAT(sale_date, '%Y-%m-%d')"),[$startDate,$endDate])
                ->where('status','=',0)
                ->where(function($query) use($salesBPId,$salesRetailerId){
                    if ($salesBPId > 0) {
                        $query->where('bp_id',$salesBPId);
                    } 
                    if ($salesRetailerId > 0) {
                        $query->where('retailer_id',$salesRetailerId);
                    }
                })
                ->paginate(100);
            
    		foreach ($saleList as $sale) {
                $saleProductList = DB::table('sale_products')
                    ->select('*')
                    ->where('bp_id',$salesBPId)
                    ->where('retailer_id',$salesRetailerId)
                    ->where('sales_id',$sale->id)
                    ->get();
        
                $dealerInfo = DB::table('dealer_informations')
                    ->select('dealer_code as code','alternate_code as alternate_code','dealer_name as name','dealer_address as address','zone','dealer_phone_number as phone')
                    ->where('dealer_code',$sale->dealer_code)
                    ->orWhere('alternate_code',$sale->dealer_code)
                    ->first();

                $sale->product_list = $saleProductList;

                $retailerInfo = "";
                if ($sale->retailer_id) {
                    $retailerInfo = DB::table('retailers')
                        ->select('retailer_name as name','retailder_address as address','phone_number as phone')
                        ->where('retailer_id',$sale->retailer_id)
                        ->first();
                }
    
                $brandPromoterInfo = "";
                if (isset($bpId) && $bpId > 0) {
                    $brandPromoterInfo = DB::table('brand_promoters')
                        ->select('bp_name as name','bp_phone as phone')
                        ->where('bp_id',$bpId)
                        ->first();
                }                
            }

            if (count($saleList) > 0) {
                return view('admin.report.sales_report',compact('saleList','retailerInfo','brandPromoterInfo','sellerName'))->with('success','Sales Data Found');
            } else {
                Log::warning(' Date Range Sales List Data Not Found');
                //return redirect()->action([ReportController::class, 'salesReportForm'])->with('error','Data Not Found.Please Try Again');
                return view('admin.report.sales_report',compact('saleList'));
            }
        }
    }

    public function SaleOrderDetails($saleId) {
    	$salesInfo = DB::table('view_sales_reports')->where('id',$saleId)->first();
        $saleProductList = DB::table('view_sales_reports')->select('*')->where('id',$saleId)->get();
        
    	return view('admin.report.sales_item_report',compact('salesInfo','saleProductList'))->with('success','Sales Data Found');
    }    
    
    public function incentiveReportFrom(Request $request) {
        Session::forget('search_catId');
        Session::forget('search_bpId');
        Session::forget('search_retailerId');
        Session::forget('search_sdate');
        Session::forget('search_edate');        
        
        $month_Sdate = Session::get('search_sdate') ? Session::get('search_sdate'):date('Y-m-01');
        $month_Edate = Session::get('search_edate') ? Session::get('search_edate'):date('Y-m-t');
        
        $salesIncentiveReportList = "";
        if ($request->ajax()) {
            $sort_by = $request->get('sortby');
            $sort_type = $request->get('sorttype');
            $query = $request->get('query');
            $query = str_replace(" ", "%", $query);
            
            $salesIncentiveReportList = DB::table('view_sales_incentive_reports')
                ->select('id','bp_id','category','retailer_id','bp_name','bp_phone','retailer_name','retailer_phone_number','dealer_name','dealer_phone_number','distributor_code','distributor_code2',\DB::raw('SUM(incentive_amount) AS total_incentive'),\DB::raw('SUM(incentive_sale_qty) AS total_qty'))
                ->where('sale_id','>',0)
                ->whereBetween(DB::raw("DATE_FORMAT(incentive_date,'%Y-%m-%d')"),[$month_Sdate,$month_Edate])
                ->where(function($sql_query) use($query){
                    if ($query !=null || !empty($query)) {
                        $sql_query->where('category','like', '%'.$query.'%')
                        ->orWhere('dealer_name','like', '%'.$query.'%')
                        ->orWhere('bp_name','like', '%'.$query.'%')
                        ->orWhere('retailer_name','like', '%'.$query.'%')
                        ->orWhere('incentive_amount','like', '%'.$query.'%');
                    }
                })
                ->groupBy('bp_id')
                ->groupBy('retailer_id')
                ->orderBy($sort_by, $sort_type)
                ->paginate(100);

            return view('admin.report.incentive_report_result_data', compact('salesIncentiveReportList'))->render();
        } else {
            //////////////////////////////////////////////////
            DB::table('sale_incentives')->truncate();
            //////////////////////////////////////////////////////
            $salesIncentiveReportList = DB::table('view_sales_incentive_reports')
                ->select('id','bp_id','category','retailer_id','bp_name','bp_phone','retailer_name','retailer_phone_number','dealer_name','dealer_phone_number','distributor_code','distributor_code2',\DB::raw('SUM(incentive_amount) AS total_incentive'),\DB::raw('SUM(incentive_sale_qty) AS total_qty'))
                ->whereBetween('incentive_date',[$month_Sdate,$month_Edate])
                ->where('sale_id','>',0)
                ->groupBy('bp_id')
                ->groupBy('retailer_id')
                ->paginate(100);
        }

        if (isset($salesIncentiveReportList) && $salesIncentiveReportList->isNotEmpty()) {
            Log::info('Load Sales Incentive List');
            return view('admin.report.incentive_report',compact('salesIncentiveReportList'));
        } else {
            Log::warning('Sales Incentive List Not Found');
            return view('admin.report.incentive_report');
        }
    }
    
    public function incentiveReport(Request $request) {
        $scatId = $request->input('incentive_category');
        $sbpId = $request->input('bp_id');
        $sretailerId = $request->input('retailer_id');
        $ssdate = $request->input('start_date');
        $sedate = $request->input('end_date');

        Session::put('search_catId',$scatId);
        Session::put('search_bpId',$sbpId);
        Session::put('search_retailerId',$sretailerId);
        Session::put('search_sdate',$ssdate);
        Session::put('search_edate',$sedate);
        
        $from_date = Session::get('search_sdate') ? Session::get('search_sdate') : date('Y-m-01');
        $to_date = Session::get('search_edate') ? Session::get('search_edate') : date('Y-m-t');
        $bpId = 0;
        $retailerId = 0;

        if ($request->input('bp_id')) {
            $bpId = $request->input('bp_id');
        } 
        if ($request->input('retailer_id')) {
            $retailerId = $request->input('retailer_id');
        }
        $incentiveCat = $request->input('incentive_category');        
        $salesIncentiveReportList = "";        
        $search_Sdate = $request->input('start_date');
        $search_Edate = $request->input('end_date');
        $incentiveLists = DB::table('incentives')
            ->where('end_date','>=',$search_Edate)
            ->where('status','=',1)
            ->where(function($sql_query) use($incentiveCat,$bpId,$retailerId) {
                if (!empty($incentiveCat)) {
                    $sql_query->where('incentive_category', '=', $incentiveCat);
                }
                if ($bpId > 0) {
                    $sql_query->where('incentive_group','=', 1);
                }
                if ($retailerId > 0) {
                    $sql_query->where('incentive_group','=', 2);
                }
            })
            ->get();

        $salesIncentiveReportList = [];
        if ($incentiveLists->isNotEmpty()) {
            foreach ($incentiveLists as $key=>$incentive) {
                $getModelId = json_decode($incentive->product_model,TRUE);//All Or Id
                $getIncentiveType = json_decode($incentive->incentive_type,TRUE);//BP Or Retailer
                $getZone = json_decode($incentive->zone,TRUE);//All Or Zone Id
                $groupCatIds = explode(',', $incentive->group_category_id);//A,B,C,D..etc
                $incentiveCatName = $incentive->incentive_category;//Target Or General
                $incentiveType = $incentive->incentive_group == 1 ? "bp":"retailer";
                $incentiveSDate = $incentive->start_date;
                $incentiveEDate = $incentive->end_date;
                $targetQty = $incentive->min_qty;

                $getSaleList = DB::table('view_sales_reports')
                    ->where('status','=',0)
                    ->whereBetween(DB::raw("DATE_FORMAT(sale_date,'%Y-%m-%d')"),[$search_Sdate,$search_Edate])
                    ->where(function($sql_query) use($getModelId,$getIncentiveType,$getZone,$groupCatIds,$incentiveType) {
                        if ($getModelId) {
                            if (in_array("all", $getModelId)) {
                                $sql_query->whereNotNull('product_master_id');
                            } else {
                                $sql_query->whereIn('product_master_id',$getModelId);
                            }
                        }
                        if ($getIncentiveType) {
                            if (in_array("all", $getIncentiveType)) {
                                $sql_query->whereNotNull('bp_id');
                                $sql_query->whereNotNull('retailer_id');
                            } else {
                                if (in_array("bp", $getIncentiveType)) {
                                    $sql_query->whereNotNull('bp_id');
                                } else if(in_array("retailer", $getIncentiveType)){
                                    $sql_query->whereNotNull('retailer_id');
                                }
                            }
                        }
                        if ($getZone) {
                            if (in_array("all", $getZone)) {
                                $sql_query->whereNotNull('zone_id');
                            } else {
                                $sql_query->whereIn('zone_id',$getZone);
                            }
                        }
                        if ($groupCatIds) {
                            if ($incentiveType == "bp") {
                                $sql_query->whereIn('bp_category_id',$groupCatIds);
                            } else if ($incentiveType == "retailer") {
                                $sql_query->whereIn('retailer_category_id',$groupCatIds);
                            }
                        }
                    })
                    ->orderBy('id','DESC')
                    ->get();

                foreach ($getSaleList as $sale) {
                    if ($incentive->incentive_amount > 0) {
                        $salesIncentiveReportList[] = [
                            "photo"=>$sale->photo,
                            "category"=>$incentive->incentive_category,
                            "imei1"=>$sale->ime_number,
                            "imei2"=>$sale->alternate_imei,
                            "model"=>$sale->product_model,
                            "sale_date"=>$sale->sale_date,
                            "dealer_name"=>$sale->dealer_name,
                            "dealer_phone_number"=>$sale->dealer_phone_number,
                            "retailer_name"=>$sale->retailer_name,
                            "retailer_phone_number"=>$sale->retailer_phone_number,
                            "bp_name"=>$sale->bp_name,
                            "bp_phone"=>$sale->bp_phone,
                            "incentive_amount"=>$incentive->incentive_amount,
                            "total_qty"=>$sale->sale_qty,
                            "total_incentive"=>$sale->sale_qty*$incentive->incentive_amount,
                        ];
                    }
                }
            }
        }     

        if (isset($salesIncentiveReportList) && !empty($salesIncentiveReportList)) {
            return view('admin.report.incentive_report',compact('salesIncentiveReportList'))->with('success','Data Found');
        } else {
           Log::warning('Report Module Incentive Data Not Found');
           return redirect()->action([ReportController::class, 'incentiveReportFrom'])->with('error','Data Not Found.Please Try Again');
        }
    }
    
    public function getIncentiveList(Request $request) {
        Session::forget('SearchIncentiveCategory');
        Session::forget('SearchIncentiveGroup');
        Session::forget('SearchIncentiveFromDate');
        Session::forget('SearchIncentiveToDate');
        $month_Sdate = date('Y-m-d');
        $month_Edate = date('Y-m-t');
        $IncentiveList = "";
        $productNameList = [];
        $iNcentiveName = [];

        if ($request->ajax()) {
            $sort_by = $request->get('sortby');
            $sort_type = $request->get('sorttype');
            $query = $request->get('query');
            $query = str_replace(" ", "%", $query);
            $searchVal = str_replace(" ", "%", $query);

            $IncentiveList = Incentive::orderBy('id','desc')
                ->whereBetween(\DB::raw("DATE_FORMAT(end_date, '%Y-%m-%d')"),[$month_Sdate,$month_Edate])
                ->where(function($sql_query) use($searchVal) {
                    $sql_query->where('incentive_title','like', '%'.$searchVal.'%')
                        ->orWhere('incentive_group','like', '%'.$searchVal.'%')
                        ->orWhere('incentive_category','like', '%'.$searchVal.'%')
                        ->orWhere('min_qty', 'like', '%'.$searchVal.'%')
                        ->orWhere('incentive_amount','like', '%'.$searchVal.'%');
                })
                ->paginate(100);

            foreach ($IncentiveList as $key=>$row) {
                $ProductName = json_decode($row->product_model);
                $getIncentiveList  = json_decode($row->incentive_type);
                foreach ($ProductName as $val) {
                    $productNameList[$key][] = $val;
                }
                foreach ($getIncentiveList as $key=>$val) {
                    $iNcentiveName[$key][] = $val;
                }
            }

            return view('admin.report.incentive_list_result_data', compact('IncentiveList','productNameList','iNcentiveName'))->render();
        } else {
            $IncentiveList  = Incentive::orderBy('id','desc')
                ->whereBetween(\DB::raw("DATE_FORMAT(end_date, '%Y-%m-%d')"),[$month_Sdate,$month_Edate])
                ->paginate(100);

            foreach ($IncentiveList as $key=>$row) {
                $ProductName = json_decode($row->product_model);
                $getIncentiveList = json_decode($row->incentive_type);
                foreach ($ProductName as $val) {
                    $productNameList[$key][] = $val;
                }
                foreach ($getIncentiveList as $key=>$val) {
                    $iNcentiveName[$key][] = $val;
                }
            }
        }

        return view('admin.report.incentive_list',compact('IncentiveList','productNameList','iNcentiveName'));
    }

    public function searchIncentiveList(Request $request) {
        $category = $request->input("incentive_category");
        $group = $request->input("incentive_group");        
        $from_date = $request->input("start_date");
        $to_date = $request->input("end_date");

        Session::put('SearchIncentiveCategory',$category);
        Session::put('SearchIncentiveGroup',$group);
        Session::put('SearchIncentiveFromDate',$from_date);
        Session::put('SearchIncentiveToDate',$to_date);

        $search_from_date = Session::get('SearchIncentiveFromDate') ? Session::get('SearchIncentiveFromDate'):date('Y-m-01');
        $search_to_date = Session::get('SearchIncentiveToDate') ? Session::get('SearchIncentiveToDate'):date('Y-m-t');

        $searchCategory = Session::get('SearchIncentiveCategory') ? Session::get('SearchIncentiveCategory'):'';
        $searchGroup = Session::get('SearchIncentiveGroup') ? Session::get('SearchIncentiveGroup'):'';
        $IncentiveList = "";
        $productNameList = [];
        $iNcentiveName = [];

        if ($request->ajax()) {
            $sort_by = $request->get('sortby');
            $sort_type = $request->get('sorttype');
            $query = $request->get('query');
            $query = str_replace(" ", "%", $query);
            $searchVal = str_replace(" ", "%", $query);

            $IncentiveList  = Incentive::orderBy('id','desc')
                ->whereBetween(\DB::raw("DATE_FORMAT(end_date, '%Y-%m-%d')"),[$search_from_date,$search_to_date])
                 ->where(function($sql_query) use($searchCategory,$searchGroup) {
                    if ($searchCategory != null || !empty($searchCategory)) {
                        $sql_query->where('incentive_category','like','%'.$searchCategory.'%');
                    }
                    if ($searchGroup != null || !empty($searchGroup)) {
                        $sql_query->where('incentive_group','=',$searchGroup);
                    }
                })
                ->where(function($sql_query) use($searchVal) {
                    $sql_query->where('incentive_title','like', '%'.$searchVal.'%')
                        ->orWhere('incentive_group','like', '%'.$searchVal.'%')
                        ->orWhere('incentive_category','like', '%'.$searchVal.'%')
                        ->orWhere('min_qty', 'like', '%'.$searchVal.'%')
                        ->orWhere('incentive_amount','like', '%'.$searchVal.'%');
                })
                ->paginate(100);

            foreach ($IncentiveList as $key=>$row) {
                $ProductName = json_decode($row->product_model);
                $getIncentiveList = json_decode($row->incentive_type);
                foreach ($ProductName as $val) {
                    $productNameList[$key][] = $val;
                }
                foreach ($getIncentiveList as $key=>$val) {
                    $iNcentiveName[$key][] = $val;
                }
            }

            return view('admin.report.incentive_list_result_data', compact('IncentiveList','productNameList','iNcentiveName'))->render();
        } else {
            $IncentiveList  = Incentive::orderBy('id','desc')
                ->whereBetween(\DB::raw("DATE_FORMAT(end_date, '%Y-%m-%d')"),[$search_from_date,$search_to_date])
                ->where(function($sql_query) use($category,$group) {
                    if ($category !=null || !empty($category)) {
                        $sql_query->where('incentive_category','like','%'.$category.'%');
                    }
                    if ($group !=null || !empty($group)) {
                        $sql_query->where('incentive_group','=',$group);
                    }
                })
                ->paginate(100);

            foreach ($IncentiveList as $key=>$row) {
                $ProductName = json_decode($row->product_model);
                $getIncentiveList = json_decode($row->incentive_type);
                foreach ($ProductName as $val) {
                    $productNameList[$key][] = $val;
                }
                foreach ($getIncentiveList as $key=>$val) {
                    $iNcentiveName[$key][] = $val;
                }
            }
        }

        return view('admin.report.incentive_list',compact('IncentiveList','productNameList','iNcentiveName'));
    }

    public function SaleIncentiveDetails(Request $request) {
        $bpId = 0;
        $retailerId = 0;
        $report_title = "";
        if ($request->bp) {
            $bpId = $request->bp;
            $report_title = "BP";
        } else if ($request->retailer) {
            $retailerId = $request->retailer;
            $report_title = "Retailer";
        }

        $salesIncentiveReportDetails = DB::table('view_sales_incentive_reports')
            ->orWhere(function($query) use($bpId,$retailerId) {
                if ($bpId > 0) {
                    $query->where('bp_id',$bpId);
                } else if($retailerId > 0) {
                    $query->where('retailer_id',$retailerId);
                }
            })
            ->get();

        if (isset($salesIncentiveReportDetails) && !empty($salesIncentiveReportDetails)) {
            return view('admin.report.incentive_report_details',compact('salesIncentiveReportDetails','report_title'))->with('success','Data Found');
        } else {
           Log::warning('Report Module Sales Incentive Details Data Not Found');
           return redirect()->action([ReportController::class, 'incentiveReportFrom'])->with('error','Data Not Found.Please Try Again');
        }
    }

    public function bpAttendanceForm(Request $request) {
        Session::forget('search_bpid');
        Session::forget('search_retailerid');
        Session::forget('search_sdate');
        Session::forget('search_edate');
        Session::put('attendance_orderby',"all");

        $currentDate = date('Y-m-d');
        $bpAttendanceArray = "";
        if ($request->ajax()) {
            $sort_by = $request->get('sortby');
            $sort_type = $request->get('sorttype');
            $query = $request->get('query');
            $query = str_replace(" ", "%", $query);
            $searchVal = str_replace(" ", "%", $query);

            $bpAttendanceArray = BrandPromoter::select('id','bp_name','bp_phone','distributor_name','distributor_code')
                ->with(['getLatestAttendances' => function($q) use($currentDate) {
                    $q->where(\DB::raw("DATE_FORMAT(date,'%Y-%m-%d')"),$currentDate);
                }])
                ->with(['getOldestAttendances' => function($q) use($currentDate) {
                    $q->where(\DB::raw("DATE_FORMAT(date,'%Y-%m-%d')"),$currentDate);
                }])
                ->where(function($sql_query) use($searchVal)
                {
                    $sql_query->where('bp_name','like', '%'.$searchVal.'%');
                })
                ->get();

            return view('admin.report.bp_attendance_report_result_data',compact('bpAttendanceArray'))->render();
        } else {
            $bpAttendanceArray = BrandPromoter::select('id','bp_name','bp_phone','distributor_name','distributor_code')
                ->with(['getLatestAttendances' => function($q) use($currentDate) {
                    $q->where(\DB::raw("DATE_FORMAT(date,'%Y-%m-%d')"),$currentDate);
                }])
                ->with(['getOldestAttendances' => function($q) use($currentDate) {
                    $q->where(\DB::raw("DATE_FORMAT(date,'%Y-%m-%d')"),$currentDate);
                }])
                ->get();
        }

        return view('admin.report.bp_attendance_report',compact('bpAttendanceArray'));
    }
    
    public function getOrderByAttendance($orderBy) {
        $month_Sdate = date('Y-m-d 00:00:00');
        $month_Edate = date('Y-m-d 23:59:59');
        $currentDate = Session::get('search_sdate') ? Session::get('search_sdate') : date('Y-m-d');
        $reqEdate = Session::get('search_edate') ? Session::get('search_edate') : date('Y-m-d');

        $bpAttendanceArray = "";

        if ($orderBy == "all") {
            $bpAttendanceArray = BrandPromoter::select('id','bp_name')
                ->with(['getLatestAttendances' => function($q) use($currentDate) {
                    $q->where(\DB::raw("DATE_FORMAT(date,'%Y-%m-%d')"),$currentDate);
                }])
                ->with(['getOldestAttendances' => function($q) use($currentDate) {
                    $q->where(\DB::raw("DATE_FORMAT(date,'%Y-%m-%d')"),$currentDate);
                }])
                ->get();
        } else if ($orderBy == "present") {
            $bpLists = DB::table('brand_promoters')->get();
            $attendanceList = DB::table('view_bp_attendance_report')
                ->where('date_time','>=',$month_Sdate)
                ->where('date_time','<=',$month_Edate)
                //->whereBetween('date_time',[$month_Sdate,$month_Edate])
                ->orderBy('id','DESC')
                ->orderBy('date_time','DESC')
                ->get();

            foreach ($attendanceList as $row) {
                $bpAttendanceArray[$row->id] = $row;
            }
        } else if ($orderBy == "absent") {
            $bpLists = DB::table('brand_promoters')->get();
            $bpAttendanceArray = [];
            foreach ($bpLists as $bprow) {
                $newbpArray['id'] = $bprow->id;
                $newbpArray['selfi_pic'] = '';
                $newbpArray['in_status'] = '';
                $newbpArray['out_status'] = '';
                $newbpArray['bp_name'] = $bprow->bp_name;
                $newbpArray['date_time'] = date('Y-m-d');
                $newbpArray['location'] = '';
                $newbpArray['in_time'] = '';
                $newbpArray['remarks'] = '';
                $newbpArray['in_time_location'] = '';
                $newbpArray['out_time'] = '';
                $newbpArray['out_time_location'] = '';
                $newbpArray['total_working_hours'] = '';
                
                $bpAttendanceArray[$bprow->id] = (object) $newbpArray;
            }
        }
        return view('admin.report.bp_attendance_report',compact('bpAttendanceArray'));
    }
    
    public function attendanceDetailsView($id) {
        $currentDate = date('Y-m-d');
        $attendanceInfo = DB::table('bp_attendances')
            ->where('bp_id',$id)
            ->where(\DB::raw("DATE_FORMAT(date,'%Y-%m-%d')"),$currentDate)
            ->first();

        $attendanceDate = date('Y-m-d', strtotime($attendanceInfo->date));
        $attendanceList = DB::table('bp_attendances')
            ->select('bp_attendances.*','brand_promoters.*')
            ->where('bp_attendances.bp_id',$attendanceInfo->bp_id)
            ->where('bp_attendances.date','like','%'.$attendanceDate.'%')
            ->leftJoin('brand_promoters', 'bp_attendances.bp_id', '=', 'brand_promoters.id')
            ->get();

        $attendanceView = View('admin.report.bp_attendance_detail_view',compact('attendanceList'))->render();
        if ($attendanceView) {
            return response()->json(['attendanceView'=>$attendanceView]);
        } else {
            Log::warning('Report Module Attendance Data List Not Found');
            return response()->json('error');
        }
    }
    
    public function bpAttendanceReport(Request $request) {
        $search_bpid = $request->input('bp_id');
        $search_sdate = $request->input('start_date');
        $search_edate = $request->input('end_date');
        $search_attendanceOrderBy = $request->input('order_by');

        Session::put('search_bpid',$search_bpid);
        Session::put('search_sdate',$search_sdate);
        Session::put('search_edate',$search_edate);
        Session::put('attendance_orderby',$search_attendanceOrderBy);        

        $currentDate = Session::get('search_sdate') ? Session::get('search_sdate') : date('Y-m-d');
        $reqEdate = Session::get('search_edate') ? Session::get('search_edate') : date('Y-m-d');
        
        $bpAttendanceArray = "";
        if ($request->ajax()) {
            $sort_by = $request->get('sortby');
            $sort_type = $request->get('sorttype');
            $query = $request->get('query');
            $query = str_replace(" ", "%", $query);

            $bpAttendanceArray = BrandPromoter::select('id','bp_name','bp_phone','distributor_name','distributor_code')
                ->with(['getAttendance' => function($q) use($currentDate) {
                    $q->where(\DB::raw("DATE_FORMAT(date,'%Y-%m-%d')"),$currentDate); //date('2021-12-22')
                }])
                ->get();
        } else {
            $bpAttendanceArray = BrandPromoter::select('id','bp_name','bp_phone','distributor_name','distributor_code')
                ->with(['getLatestAttendances' => function($q) use($currentDate) {
                    $q->where(\DB::raw("DATE_FORMAT(date,'%Y-%m-%d')"),$currentDate);
                    //$q->whereBetween(\DB::raw("DATE_FORMAT(date,'%Y-%m-%d')"),[$reqSdate,$reqEdate]);
                }])
                ->with(['getOldestAttendances' => function($q) use($currentDate) {
                    $q->where(\DB::raw("DATE_FORMAT(date,'%Y-%m-%d')"),$currentDate);
                    //$q->whereBetween(\DB::raw("DATE_FORMAT(date,'%Y-%m-%d')"),[$reqSdate,$reqEdate]);
                }])
                ->where(function($sql_query) use($search_bpid){
                    if($search_bpid !=null || !empty($search_bpid))
                    {
                        $sql_query->where('id','=',$search_bpid);
                    }
                })
                ->get();
        }

        if (isset($bpAttendanceArray) && !empty($bpAttendanceArray)) {
            Log::info('Get BP Attendance By Id');
            return view('admin.report.bp_attendance_report',compact('bpAttendanceArray'))->with('success','Data Found');
        } else {
           Log::warning('BP Attendance Not Found By Id');
           return redirect()->action([ReportController::class, 'bpAttendanceForm'])->with('error','Data Not Found.Please Try Again');
        }
    }

    public function bpAttendanceDetails($bpId,$attendanceDate) {
    	$attendanceDate = date('Y-m-d', strtotime($attendanceDate));
    	$attendanceList = DB::table('bp_attendances')
            ->where('bp_attendances.bp_id',$bpId)
            ->where('bp_attendances.date','like','%'.$attendanceDate.'%')
            ->leftJoin('brand_promoters', 'bp_attendances.bp_id', '=', 'brand_promoters.bp_id')
            ->get();

        if (isset($attendanceList) && !empty($attendanceList)) {
            Log::info('Get BP Attendance Details By Id');
            return view('admin.report.bp_attendance_report_details',compact('attendanceList'))->with('success','Data Found');
        } else {
          Log::warning('BP Attendance Details Not Found');
           return redirect()->action([ReportController::class, 'bpAttendanceForm'])->with('error','Data Not Found.Please Try Again');
        }
    }

    public function bpLeaveReportForm(Request $request) {
        Session::forget('leaveBPId');
        Session::forget('leaveSdate');
        Session::forget('leaveEdate');        
        $month_Sdate = date('Y-m-01');
        $month_Edate = date('Y-m-t');        
        $leaveList = "";

        if ($request->ajax()) {
            $sort_by = $request->get('sortby');
            $sort_type = $request->get('sorttype');
            $query = $request->get('query');
            $query = str_replace(" ", "%", $query);
            $searchVal = str_replace(" ", "%", $query);

            $leaveList = DB::table('view_bp_leave_report')
                ->whereBetween(\DB::raw("DATE_FORMAT(start_date, '%Y-%m-%d')"),[$month_Sdate,$month_Edate])
                ->where(function($sql_query) use($searchVal){
            		if ($searchVal !=null || !empty($searchVal)) {
            			$sql_query->where('bp_name','like', '%'.$searchVal.'%')
                			->orWhere('bp_phone','like', '%'.$searchVal.'%')
                            ->orWhere('retailer_name','like', '%'.$searchVal.'%')
                            ->orWhere('retailer_phone_number','like', '%'.$searchVal.'%')
                            ->orWhere('dealer_name','like', '%'.$searchVal.'%')
                            ->orWhere('dealer_phone_number','like', '%'.$searchVal.'%')
                            ->orWhere('leave_type','like', '%'.$searchVal.'%')
                            ->orWhere('reason','like', '%'.$searchVal.'%')
                            ->orWhere('total_day','like','%'.$searchVal.'%')
                            ->orWhere('status','like','%'.$searchVal.'%');
            		}
            	})
                ->orderBy($sort_by, $sort_type)
                ->paginate(100);

            return view('admin.report.bp_leave_report_result_data', compact('leaveList'))->render();
        } else {
            $leaveList = DB::table('view_bp_leave_report')
                ->whereBetween(\DB::raw("DATE_FORMAT(start_date, '%Y-%m-%d')"),[$month_Sdate,$month_Edate])
                ->paginate(100);
        }

        if (isset($leaveList) && $leaveList->isNotEmpty()) {
            Log::info('Load BP Leave List');
        } else {
            Log::warning('BP Leave List Not Found');
        }
        return view('admin.report.bp_leave_report',compact('leaveList'));
    }

    public function bpLeaveReport(Request $request) {
        $leaveBPId  = $request->input('bp_id');
        $leaveSdate = $request->input('start_date');
        $leaveEdate = $request->input('end_date');

        Session::put('leaveBPId',$leaveBPId);
        Session::put('leaveSdate',$leaveSdate);
        Session::put('leaveEdate',$leaveEdate);

        $bpId = 0;
        if ($request->input('bp_id')) {
            $bpId = $request->input('bp_id');
        }

        $start_date = Session::get('leaveSdate') ? Session::get('leaveSdate'):date('Y-m-01');
        $end_date = Session::get('leaveEdate') ? Session::get('leaveEdate'):date('Y-m-d');
        $getBpId = Session::get('leaveBPId') ? Session::get('leaveBPId'):0;
        
        $leaveList = "";
        if ($request->ajax()) {
            $sort_by = $request->get('sortby');
            $sort_type = $request->get('sorttype');
            $query = $request->get('query');
            $query = str_replace(" ", "%", $query);
            $searchVal = str_replace(" ", "%", $query);

            $leaveList = DB::table('view_bp_leave_report')
                ->whereBetween(\DB::raw("DATE_FORMAT(start_date, '%Y-%m-%d')"),[$start_date,$end_date])
                ->where(function($sql_query) use($searchVal,$getBpId){
            		if ($searchVal != null || !empty($searchVal)) {
            			$sql_query->where('bp_name','like', '%'.$searchVal.'%')
                            ->orWhere('bp_phone','like', '%'.$searchVal.'%')
                            ->orWhere('retailer_name','like', '%'.$searchVal.'%')
                            ->orWhere('retailer_phone_number','like', '%'.$searchVal.'%')
                            ->orWhere('dealer_name','like', '%'.$searchVal.'%')
                            ->orWhere('dealer_phone_number','like', '%'.$searchVal.'%')
                            ->orWhere('leave_type','like', '%'.$searchVal.'%')
                            ->orWhere('reason','like', '%'.$searchVal.'%')
                            ->orWhere('total_day','like','%'.$searchVal.'%')
                            ->orWhere('status','like','%'.$searchVal.'%');
            		}

                    if ($getBpId > 0){
                        $sql_query->where('bp_id','=', $getBpId);
                    }
            	})
                ->orderBy($sort_by, $sort_type)
                ->paginate(100);

            return view('admin.report.bp_leave_report_result_data', compact('leaveList'))->render();
        } else {
            $leaveList = DB::table('view_bp_leave_report')
                ->whereBetween(\DB::raw("DATE_FORMAT(start_date, '%Y-%m-%d')"),[$start_date,$end_date])
                ->where(function($sql_query) use($getBpId){
            		if($getBpId > 0){
            			$sql_query->where('bp_id','=', $getBpId);
            		}
            	})
            	->paginate(100);
        }

        if(isset($leaveList) && !empty($leaveList)) {
            Log::info('BP Leave List Not Found');
            return view('admin.report.bp_leave_report',compact('leaveList'))->with('success','Data Found');
        } else {
           Log::warning('BP Leave List Not Found');
           return redirect()->action([ReportController::class, 'bpLeaveReportForm'])->with('error','Data Not Found.Please Try Again');
        }
    }

    public function imeSoldReport(Request $request) {
        Session::forget('search_bpid');
        Session::forget('search_retailerid');
        Session::forget('search_sdate');
        Session::forget('search_edate');
        Session::forget('search_dealerid');
        Session::forget('search_productid');        
        $month_Sdate = date('Y-m-01');
        $month_Edate = date('Y-m-t');
        $soldImeList = "";        

        if ($request->ajax()) {
            $sort_by = $this-> $request->get('sortby');
            $sort_type = $this-> $request->get('sorttype');
            $query = $this-> $request->get('query');
            $query = $this-> str_replace(" ", "%", $query);
            $searchVal = $this-> str_replace(" ", "%", $query);
            $fieldName = $this-> $request->get('field');

            $soldImeList = DB::table('view_sales_reports')
                ->whereBetween(\DB::raw("DATE_FORMAT(sale_date, '%Y-%m-%d')"),[$month_Sdate,$month_Edate])
                ->where('status',0)
                ->where(function($sql_query) use($fieldName,$searchVal){
                    if($searchVal !=null || !empty($searchVal)) {
                        if($fieldName == 'ime_number') {
                            $sql_query->where('ime_number','like', '%'.$searchVal.'%')
                            ->orWhere('alternate_imei','like', '%'.$searchVal.'%');
                        }
                        else if($fieldName == 'product_model') {
                            $sql_query->where('product_model', 'like', '%'.$searchVal.'%');
                        }
                        else if($fieldName == 'dealer_name') {
                            $sql_query->where('dealer_name', 'like', '%'.$searchVal.'%');
                        }
                        else if($fieldName == 'dealer_phone_number') {
                            $sql_query->where('dealer_phone_number', '=', $searchVal);
                        }
                        else if($fieldName == 'dealer_code') {
                            $sql_query->where('dealer_code', '=', $searchVal);
                        }
                        else if($fieldName == 'retailer_name') {
                            $sql_query->where('retailer_name', 'like', '%'.$searchVal.'%');
                        }
                        else if($fieldName == 'retailer_phone_number') {
                            $sql_query->where('retailer_phone_number', '=', $searchVal);
                        }
                        else if($fieldName == 'bp_name') {
                            $sql_query->where('bp_name', 'like', '%'.$searchVal.'%');
                        }
                        else if($fieldName == 'bp_phone') {
                            $sql_query->where('bp_phone', '=',$searchVal);
                        }
                    }
                })
                //->orderBy($sort_by, $sort_type)
                ->paginate(100);
            return view('admin.report.sold_ime_result_data', compact('soldImeList'))->render();
        } else {
            $soldImeList = DB::table('view_sales_reports')
        		->whereBetween(\DB::raw("DATE_FORMAT(sale_date, '%Y-%m-%d')"),[$month_Sdate,$month_Edate])
                ->where('status',0)
                ->paginate(100);
        }

        if (isset($soldImeList) && $soldImeList->isNotEmpty()) {
            Log::info('Load Sold IMEI List');
        } else {
            Log::warning('Sold IMEI List Not Found');
        }
        return view('admin.report.sold_ime_list',compact('soldImeList'));
    }
    
    public function searchSoldImeiList(Request $request) {
        //dd($request->all());
        $bpId = ($request->input('bp_id')) ? $request->input('bp_id'):0;
		$retailId = ($request->input('retailer_id')) ? $request->input('retailer_id'):0;
		$start_date = $request->input('start_date');
		$end_date = $request->input('end_date');
		$dealerCode = $request->input('dealer_code');
		$productId = $request->input('product_id');		
		$month_Sdate =  ($start_date) ? $start_date:date('Y-m-01');
        $month_Edate =  ($end_date) ? $end_date:date('Y-m-t');
        
        Session::put('salesBPId',$bpId);
        Session::put('salesRetailerId',$retailId);
		Session::put('salesDealerCode',$dealerCode);
		Session::put('salesProductId',$productId);
        Session::put('search_sdate',$month_Sdate);
        Session::put('search_edate',$month_Edate);
        
        if ($request->ajax()) {
            $sort_by = $request->get('sortby');
            $sort_type = $request->get('sorttype');
            $query = $request->get('query');
            $query = str_replace(" ", "%", $query);
            $searchVal = str_replace(" ", "%", $query);

            $soldImeList = DB::table('view_sales_reports')
                ->whereBetween(\DB::raw("DATE_FORMAT(sale_date, '%Y-%m-%d')"),[$month_Sdate,$month_Edate])
                ->where('status',0)
                ->where(function($sql_query) use($searchVal){
                    if($searchVal !=null || !empty($searchVal)){
                        $sql_query->where('ime_number','like', '%'.$searchVal.'%')
            			->orWhere('alternate_imei','like', '%'.$searchVal.'%')
            			->orWhere('product_model', 'like', '%'.$searchVal.'%')
            			->orWhere('dealer_name', 'like', '%'.$searchVal.'%')
            			->orWhere('dealer_phone_number','=', $searchVal)
            			->orWhere('dealer_code','=', $searchVal)
            			->orWhere('retailer_name','like', '%'.$searchVal.'%')
            			->orWhere('retailer_phone_number','=', $searchVal)
            			->orWhere('bp_name','like', '%'.$searchVal.'%')
            			->orWhere('bp_phone','=', $searchVal);
                    }
                })
                ->orderBy($sort_by, $sort_type)
                ->paginate(100);
            return view('admin.report.sold_ime_result_data', compact('soldImeList'))->render();
        } else {
            $soldImeList = DB::table('view_sales_reports')
                ->whereBetween(\DB::raw("DATE_FORMAT(sale_date, '%Y-%m-%d')"),[$month_Sdate,$month_Edate])
                ->where('status',0)
                ->where(function($sql_query) use($bpId,$retailId,$dealerCode,$productId){
            		if($bpId > 0){
            			$sql_query->where('bp_id','=', $bpId);
            		}
            		if($retailId > 0){
            			$sql_query->where('retailer_id','=', $retailId);
            		}
            		if($dealerCode > 0){
            			$sql_query->where('dealer_code', '=', $dealerCode);
            		}
            		if($productId > 0){
            			$sql_query->where('product_master_id','=',$productId);
            		}
            	})
            	->orderBy('id','desc')
            	//->groupBy('bp_id')
                ->paginate(100);
        }
        return view('admin.report.sold_ime_list',compact('soldImeList'));
    }

    public function imeProductDetails($id) {
        if (isset($id) && $id > 0) {
            $productDetails = DB::table('view_product_master')->where('product_master_id',$id)->first();
            if ($productDetails) {
                Log::info('Get IMEI Product Details By Id');
                return response()->json($productDetails);
            } else {
                 Log::warning('IMEI Product Not Found By Id');
                 return response()->json('error');
            }
        }
    }
    
    public function MessageDetails($replyId,$messageId) {
        $imagePath = "no-image.png";
        $baseUrl = URL::to('');
        $pathUrl = $baseUrl.'/public/upload/no-image.png';

        $authorMessage = DB::table('authority_messages')->where('id','=',$messageId)->where('status',0)->first();
        $AuthuserName = "";
        $replyDateTime = "";
        $AuthMessage = "";
        if (isset($authorMessage->reply_user_name)) {
            $AuthuserName = $authorMessage->reply_user_name;
            $replyDateTime = $authorMessage->date_time;
            $AuthMessage = $authorMessage->message;
        }

        $authorMessageView = '<div class="media w-50"><img src="'.$pathUrl.'" alt="user" width="50" class="rounded-circle"><div class="media-body ml-3"><div class="bg-light rounded"><p class="text-small mb-0 text-muted" style="padding: 7px">'.$AuthMessage.'</p></div><p class="small text-muted">'.$AuthuserName.'|'.$replyDateTime.'</p></div></div>';

        $messageList = DB::table('authority_messages')
            ->orWhere(function($query) use($replyId, $messageId){
                if ($replyId > 0) {
                    $query->where('id','=',$replyId);
                    $query->orWhere('reply_for','=',$replyId);
                } else {
                    $query->where('id','=',$messageId);
                }
            })
            ->get();        

        $replyMessageArray = [];
        foreach ($messageList as $key=>$message) {
            if ($message->status == 0) {
                $replyMessageArray[$key]["author"] = $message->reply_user_name;
                $replyMessageArray[$key]["dateTime"] = $message->date_time;
                $replyMessageArray[$key]["message"] = $message->message;
            }

            if ($message->status == 1) {
                $replyMessageArray[$key]["reply"] = $message->reply_user_name;
                $replyMessageArray[$key]["dateTime"] = $message->date_time;
                $replyMessageArray[$key]["message"] = $message->message;
            }
        }

        $viewMessage = [];
        foreach ($replyMessageArray as $key=>$messageInfo) {
            if (isset($messageInfo['author'])) {
                $viewMessage[] = '<div class="media w-50"><img src="'.$pathUrl.'" alt="user" width="50" class="rounded-circle"><div class="media-body ml-3"><div class="bg-light rounded"><p class="text-small mb-0 text-muted" style="padding: 7px">'.$messageInfo["message"].'</p></div><p class="small text-muted">'.$messageInfo["author"].'|'.$messageInfo["dateTime"].'</p></div></div>';
            } else {
                $viewMessage[] = '<div class="media w-50 ml-auto"><div class="media-body"><div class="bg-primary rounded"><p class="text-small mb-0 text-white" style="padding: 7px">'.$messageInfo["message"].'</p></div><p class="small text-muted">'.$messageInfo["reply"].'|'.$messageInfo["dateTime"].'</p></div></div>';
            }
        }
        return response()->json(['sendMessage'=>$authorMessageView,'replyMessage'=>$viewMessage,'messageId'=>$messageId,'replyId'=>$replyId]);
    }
    
    public function reply_message(Request $request) {
        $imagePath = "no-image.png";
        $baseUrl = URL::to('');
        $pathUrl = $baseUrl.'/public/upload/no-image.png';
        $messageId = $request->input('message_id');
        $replyId = $request->input('reply_id');
        $user = Auth::user();
        $userId = $user->id;
        // $userId = auth('api')->user()->id;        
        $employeeId = Auth::user()->employee_id;
        $bpId = Auth::user()->bp_id;
        $retailerId = Auth::user()->retailer_id;
        $phone = "";
        $zone = "";

        if ($employeeId > 0) {
            $getInfo = Employee::select('name','mobile_number')->where('employee_id',$employeeId)->first();
            $phone = (!empty($getInfo['mobile_number'])) ? $getInfo['mobile_number']:"";
            $zone  = "";
        } else if ($bpId > 0) {
            $getInfo = BrandPromoter::select('bp_name','bp_phone','distributor_zone')->where('id',$bpId)->first();
            $phone = (!empty($getInfo['bp_phone'])) ? $getInfo['bp_phone']:"";
            $zone  = (!empty($getInfo['distributor_zone'])) ? $getInfo['distributor_zone']:"";
        } else if ($retailerId > 0) {
            $getInfo = Retailer::select('retailer_name','phone_number','distric_name')->where('id',$retailerId)->first();
            $phone = (!empty($getInfo['phone_number'])) ? $getInfo['phone_number']:"";
            $zone  = (!empty($getInfo['distric_name'])) ? $getInfo['distric_name']:"";
        }

        $userExists = DB::table('users')->where('id',$userId)->first();
        $messageInfo = DB::table('authority_messages')->where('id',$messageId)->first();
        $CheckStatus    = AuthorityMessage::where('id',$messageId)->first();
        $messageStatus  = 1;
        
        if (isset($CheckStatus) && $CheckStatus['who_reply'] == $userId && $CheckStatus['id'] == $messageId) {
            $messageStatus = 1;
        }
        
        /*
        * bnm = 2 & status = 0 (Brand New Message and no reply)
        * bnm = 0 & status = 1 (message reply hoiche)
        */
        
        $bnm = 0; 
        if (isset($CheckStatus) && $CheckStatus['bnm'] == 2) {
            $bnm = 1; 
        }
        $lastInsertId = "";
        $reply_status = 0;
        if ($CheckStatus) {
            $AddMessage = AuthorityMessage::create([
                "message_group_id"=>$CheckStatus['message_group_id'],
                "message"=>$request->input('reply_message'),
                "date_time"=>date('Y-m-d H:i:s'),
                "status"=>$messageStatus,
                'reply_for'=>$replyId ? $replyId:$messageId,
                'who_reply'=> $userId ? $userId:0,
                "reply_user_name"=>$userExists->name,
                "phone"=>$phone ? $phone: "",
                "zone"=>$zone ? $zone:"",
            ]);

            $lastInsertId = DB::getPdo()->lastInsertId();             
            $updateBnmStatus = AuthorityMessage::where('id',$messageId)->update([
                "bnm"=>$bnm ? $bnm:0,
                "reply_for"=>$CheckStatus['message_group_id']
            ]);
            
            if ($AddMessage) {
                $reply_status = 1;
            }
        } 
        $authorMessage = DB::table('authority_messages')->where('id', $lastInsertId)->first();        
        $authorMessageView = '<div class="media w-50"><img src="'.$pathUrl.'" alt="user" width="50" class="rounded-circle"><div class="media-body ml-3"><div class="bg-light rounded"><p class="text-small mb-0 text-muted" style="padding: 7px">'.$authorMessage->message.'</p></div><p class="small text-muted">'.$authorMessage->reply_user_name.'|'.$authorMessage->date_time.'</p></div></div>';         
        $messageList = DB::table('authority_messages')
            ->orWhere(function($query) use($replyId, $messageId){
                if($replyId > 0){
                    $query->where('id','=',$replyId);
                    $query->orWhere('reply_for','=',$replyId);
                } else {
                    $query->where('id','=',$messageId);
                }
            })->get();

        $replyMessageArray = [];
        foreach ($messageList as $key=>$message) {
            if ($message->status == 0) {
                $replyMessageArray[$key]["author"] = $message->reply_user_name;
                $replyMessageArray[$key]["dateTime"] = $message->date_time;
                $replyMessageArray[$key]["message"] = $message->message;
            }
            if ($message->status == 1) {
                $replyMessageArray[$key]["reply"] = $message->reply_user_name;
                $replyMessageArray[$key]["dateTime"] = $message->date_time;
                $replyMessageArray[$key]["message"] = $message->message;
            }
        }

        $viewMessage = [];
        foreach($replyMessageArray as $key=>$messageInfo) {
            if (isset($messageInfo['author'])) {
                 $viewMessage[] = '<div class="media w-50"><img src="'.$pathUrl.'" alt="user" width="50" class="rounded-circle"><div class="media-body ml-3"><div class="bg-light rounded"><p class="text-small mb-0 text-muted" style="padding: 7px">'.$messageInfo["message"].'</p></div><p class="small text-muted">'.$messageInfo["author"].'|'.$messageInfo["dateTime"].'</p></div></div>';
            } else {
                $viewMessage[] = '<div class="media w-50 ml-auto"><div class="media-body"><div class="bg-primary rounded"><p class="text-small mb-0 text-white" style="padding: 7px">'.$messageInfo["message"].'</p></div><p class="small text-muted">'.$messageInfo["reply"].'|'.$messageInfo["dateTime"].'</p></div></div>';
            }
        }

        if ($reply_status == 1) {
            return response()->json(['status'=>'success','sendMessage'=>$authorMessageView,'replyMessage'=>$viewMessage,'messageId'=>$messageId]);
        } else {
             return response()->json(['status'=>'error']);
        }
    }

    public function editLeave($id) {
        if (isset($id) && $id > 0) {
            $month_Sdate = date('Y-m-01');
            $month_Edate = date('Y-m-t');
            $leaveInfo = DB::table('bp_leaves')->where('id',$id)->first();
            $leaveList = DB::table('view_bp_leave_report')
                ->where('bp_id','=',$leaveInfo->bp_id)
                ->where('status','=','Approved')
                ->whereBetween('start_date',[$month_Sdate,$month_Edate])
                ->get();            
            $viewLeaveLists = View('admin.report.bp_old_leave_lists',compact('leaveList'))->render();
            $totalLeaveQty = 0;
            $currentMonthBpLeaveList = [];
            if ($leaveList->isNotEmpty()) {
                foreach ($leaveList as $row) {
                    $totalLeaveQty +=$row->total_day;
                    $currentMonthBpLeaveList[] = '<tr><td>'.$row->leave_type.'</td><td>'.date('d-m-Y', strtotime($row->start_date)).'</td><td>'.$row->start_time.'</td><td>'.$row->total_day.'</td><td>'.$row->reason.'</td></tr>';
                }
            } else {
                $currentMonthBpLeaveList[] = '<tr><td colspan="5" class="text-center text-danger">Leave Status Not Found </td></tr>';
            }
            
            $leaveTypes = DB::table('leave_types')->get();
            $leaveCategories = DB::table('leave_categories')->get();

            $leaveType = [];
            foreach ($leaveTypes as $ltype) {
                $leaveType[] = '<option value="'.$ltype->id.'" class="leaveTypeId'.$ltype->id.'">'.$ltype->name.'</option>';
            }

            $leaveReason = [];
            foreach ($leaveCategories as $cat) {
                $leaveReason[] = '<option value="'.$cat->id.'" class="leaveReason-'.$cat->name.'">'.$cat->name.'</option>';
            }
            Log::info('Edit Leave By Id');
            return response()->json(['leaveInfo'=>$leaveInfo,'leaveType'=>$leaveType,'leaveReason'=>$leaveReason,'leaveList'=>$viewLeaveLists,'totalLeaveQty'=>$totalLeaveQty]);
        } else {
            Log::warning('Edit Leave Failed By Id');
            return redirect()->back()->with('error');
        }
    }

    public function updateLeave(Request $request,$update_id) {
        $rules = ['total_day'=>'required','status'=>'required'];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            Log::error('Leave Update Validation error');
            return Response::json(['errors' => $validator->errors()]);
        }

        $Update = DB::table('bp_leaves')->where('id',$update_id)->update([
            "total_day"=>$request->input('total_day'),
            "start_time"=>$request->input('start_time'),
            "status"=>$request->input('status'),
        ]);

        if ($Update) {
            Log::info('Existing Leave Update');
            return response()->json('success');
        }
        Log::info('Existing Leave Update Failed');
        return response()->json('error');
    }
    
    public function incentiveDetailsView($bp_id=null,$retail_id=null) {
        $bpId = 0;
        $retailerId = 0;
        $getId = "";
        if ($bp_id > 0) {
            $bpId = $bp_id;
            $getId = $bp_id;
        } else if ($retail_id > 0) {
            $retailerId = $retail_id;
            $getId = $retail_id;
        }
        
        $month_Sdate = date('Y-m-01');
        $month_Edate = date('Y-m-t');

        $salesIncentiveReportDetails = DB::table('view_sales_incentive_reports')
            ->whereBetween(DB::raw("DATE_FORMAT(incentive_date,'%Y-%m-%d')"),[$month_Sdate,$month_Edate])
            ->where(function($query) use($bpId,$retailerId){
                if($bpId > 0){
                    $query->where('bp_id',$bpId);
                }
                elseif($retailerId > 0) {
                    $query->where('retailer_id',$retailerId);
                }
            })
            ->get();

        $salesIncentiveLists = View('admin.report.sales_incentive_detail_view',compact('salesIncentiveReportDetails'))->render();

        if ($salesIncentiveLists) {
            return response()->json(['incentiveReportList'=>$salesIncentiveLists,'getId'=>$getId]);
        } else {
            Log::warning('Report Module Incentive Details Data Not Found');
            return response()->json('error');
        }
    }

    public function productSalesReport(Request $request) {
        // dd($request->all());
        $productSalesReport = "";
        if ($request->ajax()) {
            $sort_by = $request->get('sortby');
            $sort_type = $request->get('sorttype');
            $query = $request->get('query');
            $query = str_replace(" ", "%", $query);
            $searchVal = str_replace(" ", "%", $query);

            $month_Sdate = session()->get('search_sdate');
            $month_Edate = session()->get('search_edate');
            $retailId = session()->get('search_retailerid');
            $productId = session()->get('search_productid');
            $dealerCode = session()->get('search_dealerid');

            $productSalesReport = DB::table('view_sales_product_reports')
                ->select('*')
                ->selectRaw('count(sale_qtys) as saleQty')
                ->selectRaw('SUM(sale_qty * sale_price) as saleAmount')
                ->where(function($sql_query) use($searchVal,$month_Sdate,$month_Edate,$retailId,$dealerCode,$productId) {
            		$sql_query->where('product_model','like', '%'.$searchVal.'%')
            			->orWhere('dealer_name','like', '%'.$searchVal.'%')
            			->orWhere('dealer_phone_number', 'like', '%'.$searchVal.'%')
            			->orWhere('dealer_code','like', '%'.$searchVal.'%')
            			->orWhere('retailer_name', 'like', '%'.$searchVal.'%')
            			->orWhere('retailer_phone_number','like', '%'.$searchVal.'%');
                    if ($month_Sdate && $month_Edate) {
                        $sql_query->whereBetween(\DB::raw("DATE_FORMAT(sale_date, '%Y-%m-%d')"),[$month_Sdate,$month_Edate]);
                    }
                    if ($retailId > 0) {
                        $sql_query->where('retailer_id', '=', $retailId);
                    }
                    if ($productId > 0) {
                        $sql_query->where('product_master_id', '=', $productId);
                    }
                    if ($dealerCode > 0) {
                        $sql_query->where('dealer_code', '=', $dealerCode);
                        $sql_query->orWhere('alternate_code', '=', $dealerCode);
                    }
                })
                ->groupBy('dealer_code')
                ->groupBy('retailer_id')
                ->groupBy('product_master_id')
                ->paginate(100);
            
            return view('admin.report.product_sales_result_data', compact('productSalesReport'))->render();
        } else {
            $month_Sdate = $request->input('from_date');
            if (empty($month_Sdate)) {
                $month_Sdate = date('Y-m-01');
            }
            $month_Edate = $request->input('to_date');
            if (empty($month_Edate)) {
                $month_Edate = date('Y-m-t');
            }
            $retailId = $request->input('retailer_id');
            $productId = $request->input('product_id');
            $productName = $request->input('product_name');
            $dealerCode = $request->input('dealer_code');
            if (empty($productName)) {
                Session::forget('search_productid');
                $productId = null;
            }

            Session::put('search_sdate',$month_Sdate);
            Session::put('search_edate',$month_Edate);
            Session::put('search_retailerid',$retailId);
            Session::put('search_product_name',$productName);
            Session::put('search_productid',$productId);
            Session::put('search_dealerid',$dealerCode);

            $productSalesReport = DB::table('view_sales_product_reports')
                ->select('*')
                ->selectRaw('SUM(sale_qty) as saleQty')
                ->selectRaw('SUM(sale_qty * sale_price) as saleAmount')                
                ->where(function($sql_query) use($month_Sdate,$month_Edate,$retailId,$dealerCode,$productId) {
                    if ($month_Sdate && $month_Edate) {
                        $sql_query->whereBetween(\DB::raw("DATE_FORMAT(sale_date, '%Y-%m-%d')"),[$month_Sdate,$month_Edate]);
                    }
                    if ($retailId > 0) {
                        $sql_query->where('retailer_id', '=', $retailId);
                    }
                    if ($productId > 0) {
                        $sql_query->where('product_master_id', '=', $productId);
                    }
                    if ($dealerCode > 0) {
                        $sql_query->where('dealer_code', '=', $dealerCode);
                        $sql_query->orWhere('alternate_code', '=', $dealerCode);
                    }
                })
                ->groupBy('dealer_code')
                ->groupBy('retailer_id')
                ->groupBy('product_master_id')
                ->paginate(100);
        }

        return view('admin.report.product_sales_report',compact('productSalesReport'));
    }
    
    public function modelSalesReport(Request $request) {
        $bpId = $request->input('bp_id');
        $retailId = $request->input('retailer_id');
        $productId = $request->input('product_id');
        $dealerCode = $request->input('dealer_code');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        Session::put('search_bpid',$bpId);
        Session::put('search_retailerid',$retailId);
        Session::put('search_sdate',$start_date);
        Session::put('search_edate',$end_date);
        Session::put('search_dealerid',$dealerCode);
        Session::put('search_productid',$productId);

        $sellerName = "";
        if ($bpId > 0) { 
            $sellerName = BrandPromoter::where('status','=',1)->where('id','=',$bpId)->value('bp_name');
        } 

        if ($retailId > 0) {
            $sellerName = Retailer::where('status','=',1)->where('id','=',$retailId)->value('retailer_name');
        }
        
        $start_date = Session::get('search_sdate') ? Session::get('search_sdate'):date('Y-m-01');
        $end_date = Session::get('search_edate') ? Session::get('search_edate'):date('Y-m-t');
        
        $productSalesReport = "";
        if($request->ajax())  {
            $sort_by = $request->get('sortby');
            $sort_type = $request->get('sorttype');
            $query = $request->get('query');
            $query = str_replace(" ", "%", $query);
            $searchVal = str_replace(" ", "%", $query);

            $productSalesReport = DB::table('view_sales_reports')
                ->select('id','customer_name','customer_phone','sale_date','dealer_code','product_code','ime_number','product_model','product_color','mrp_price','msdp_price','msrp_price','sale_price','retailer_name','retailder_address','retailer_phone_number','bp_name','bp_phone','dealer_name','alternate_code','dealer_phone_number')
                ->selectRaw('count(sale_qty) as saleQty')
                ->selectRaw('SUM(sale_qty*sale_price) as saleAmount')
                ->whereBetween(\DB::raw("DATE_FORMAT(sale_date, '%Y-%m-%d')"),[$start_date,$end_date])
                ->where(function($sql_query) use($searchVal) {
                    $sql_query->where('product_model','like', '%'.$searchVal.'%')
                    ->orWhere('dealer_name','like', '%'.$searchVal.'%')
                    ->orWhere('dealer_phone_number', 'like', '%'.$searchVal.'%')
                    ->orWhere('dealer_code','like', '%'.$searchVal.'%')
                    ->orWhere('retailer_name', 'like', '%'.$searchVal.'%')
                    ->orWhere('retailer_phone_number','like', '%'.$searchVal.'%')
                    ->orWhere('bp_name','like', '%'.$searchVal.'%')
                    ->orWhere('bp_phone','like', '%'.$searchVal.'%');
                })
                ->groupBy('dealer_name')
                ->groupBy('retailer_name')
                ->groupBy('bp_name')
                ->groupBy('product_model')
                ->paginate(100);
            
            return view('admin.report.product_sales_result_data', compact('productSalesReport'))->render();
        } else {
            $productSalesReport = DB::table('view_sales_reports')
                ->select('id','customer_name','customer_phone','sale_date','dealer_code','product_code','ime_number','product_model','product_color','mrp_price','msdp_price','msrp_price','sale_price','retailer_name','retailder_address','retailer_phone_number','bp_name','bp_phone','dealer_name','alternate_code','dealer_phone_number')
                ->selectRaw('count(sale_qty) as saleQty')
                ->selectRaw('SUM(sale_qty*sale_price) as saleAmount')
                ->whereBetween(\DB::raw("DATE_FORMAT(sale_date, '%Y-%m-%d')"),[$start_date,$end_date])
                ->where(function($sql_query) use($dealerCode,$bpId,$retailId,$productId) {
            		if ($dealerCode > 0) {
            			$sql_query->where('dealer_code', '=', $dealerCode);
            			$sql_query->orWhere('alternate_code', '=', $dealerCode);
            		}
            		if ($bpId > 0) {
            			$sql_query->where('bp_id', '=', $bpId);
            		}
            		if ($retailId > 0) {
            			$sql_query->where('retailer_id', '=', $retailId);
            		}
            		if ($productId > 0) {
            			$sql_query->where('product_master_id', '=', $productId);
            		}
                })
                ->orderBy('id','desc')
                ->groupBy('product_model')
                ->paginate(100);
        }
        return view('admin.report.product_sales_report',compact('productSalesReport','sellerName'));
    }
    
    public function productSalesReportDetails($modelNumber) {
        $salesInfoList = DB::table('view_sales_reports')
            ->select('id','sale_date','sale_qty','dealer_code','product_code','ime_number','product_model','product_color','mrp_price','msdp_price','msrp_price','sale_price','retailer_name','retailder_address','retailer_phone_number','bp_name','bp_phone')
            ->where('product_model',$modelNumber)
            ->get();
        $viewProductInfo = [];
        $i = 1;
        foreach ($salesInfoList as $salesInfo) {
            $viewProductInfo[] = "<tr><td>".$i.".</td><td>".$salesInfo->ime_number."</td><td>".$salesInfo->product_model."</td><td>".$salesInfo->product_color."</td><td class='text-right'>".$salesInfo->msrp_price."</td><td class='text-right'>".$salesInfo->mrp_price."</td><td class='text-right'>".$salesInfo->msdp_price."</td><td class='text-right'>".$salesInfo->sale_price."</td><td>".$salesInfo->sale_qty."</td><td>".$salesInfo->bp_name.' - '.$salesInfo->bp_phone."</td><td>".$salesInfo->retailer_name.' - '.$salesInfo->retailer_phone_number."</td><td>".$salesInfo->sale_date."</td></tr>";
            $i++;
        }
        if ($viewProductInfo) {
            return response()->json(['itemList'=>$viewProductInfo]);
        } else {
             return response()->json('error');
        }
    }

    public function sellerProductSalesReport($modelSellerId) {
        $getValue = explode('~',$modelSellerId);
        $modelNumber = $getValue[0];
        $seller = $getValue[1];
        $sellerId = $getValue[2];
        
        $salesInfoList = DB::table('view_sales_reports')
            ->select('id','sale_date','sale_qty','dealer_code','product_code','ime_number','product_model','product_color','mrp_price','msdp_price','msrp_price','sale_price','retailer_name','retailder_address','retailer_phone_number','bp_name','bp_phone')
            ->where('product_model',$modelNumber)
            ->where($seller,$sellerId)
            ->get();

        $viewProductInfo = [];
        $i = 1;
        foreach ($salesInfoList as $k=>$salesInfo) {
            $viewProductInfo[] = "<tr><td>".$i.".</td><td>".$salesInfo->ime_number."</td><td>".$salesInfo->product_model."</td><td>".$salesInfo->product_color."</td><td>".$salesInfo->msrp_price."</td><td>".$salesInfo->mrp_price."</td><td>".$salesInfo->msdp_price."</td><td>".$salesInfo->sale_price."</td><td>".$salesInfo->sale_qty."</td><td>".$salesInfo->bp_name.' - '.$salesInfo->bp_phone."</td><td>".$salesInfo->retailer_name.' - '.$salesInfo->retailer_phone_number."</td><td>".$salesInfo->sale_date."</td></tr>";
            $i++;
        }

        if ($viewProductInfo) {
            return response()->json(['itemList'=>$viewProductInfo]);
        } else {
            return response()->json('error');
        }
    }
    
    public function getRetailerStock() {
        $stockList  = DB::table('retailer_product_stocks')
            ->select('retailer_product_stocks.*','product_masters.product_model as product_name')
            ->leftJoin('product_masters','product_masters.product_master_id','=','retailer_product_stocks.product_id')
            ->get();
        return view('admin.stock.retailer_stock_list',compact('stockList'));
    }
    
    public function searchRetailerStock(Request $request) {
        $clientType = $request->input('client_type');
        $searchId = $request->input('search_id');
        $resultType = $request->input('result_type');
        $modelArray = $request->input('model');        
        Session::forget('clientType');
        Session::forget('searchId');
        Session::forget('searchModel');
        Session::put('clientType', $clientType);
        Session::put('searchId', $searchId);
        Session::put('searchModel', $modelArray);
        Session::put('LAST_ACTIVITY',time());

        $rules = ['client_type'=>'required','search_id'=>'required',];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            Log::error('Retailer Stock Request Validation error');
            return redirect()->back()->with(['errors'=>$validator->errors()]);
        }
        $modelList  = Products::distinct()->get(['product_master_id','product_model']);

        if (isset($clientType) && $clientType == 'retailer') {
            $clientInfo = Retailer::where('phone_number','=',$searchId)->first();
        } else if(isset($clientType) && $clientType == 'dealer') {
            $clientInfo = DealerInformation::where('dealer_code','=',$searchId)->orWhere('alternate_code','=',$searchId)->first();
        } else if(isset($clientType) && $clientType == 'emp') {
            $clientInfo = Employee::where('employee_id','=',$searchId)->first();
            if (empty($clientInfo) || $clientInfo == null) {
                $getCurlResponse = getData(sprintf(RequestApiUrl("EmployeeId"),$searchId),"GET");
                $clientInfo    = json_decode($getCurlResponse['response_data'],true);
            }
        }

        if (isset($clientType) && !empty($clientType) && isset($searchId)  && !empty($searchId)) {
            $getCurlResponse = getData(sprintf(RequestApiUrl("GetStock"),$searchId,$clientType),"GET");
            $responseData = json_decode($getCurlResponse['response_data'],true);
            $dataArray = [];
            $userInfo = [];
            $dealerTotQty = [];
            $retailerTotQty = 0;
            $employeeTotQty = 0;
            $dealerStockTotQty = 0;
            $retailerInfo = "";
            $retailerStockTotQty = 0;
            $dealerWaiseRetailerInfo = [];

            if ($clientType == 'emp') {
                if (isset($responseData) && !empty($responseData)) {
                    foreach ($responseData as $key=>$row) {
                        if(!empty($modelArray)) {
                            if (in_array($row['Model'],$modelArray)) {
                                $dataArray[$row['DealerCode']][$row['RetailerPhone']][] = $row['Model'].'~'.$row['StockQuantity'];
                                $employeeTotQty += $row['StockQuantity'];
                                $dealerTotQty[$row['DealerCode']][] = $row['StockQuantity'];
                            }
                        } else {
                            $dataArray[$row['DealerCode']][$row['RetailerPhone']][] = $row['Model'].'~'.$row['StockQuantity'];
                            $employeeTotQty += $row['StockQuantity'];
                            $dealerTotQty[$row['DealerCode']][] = $row['StockQuantity'];
                        }

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
                            "Division"=>$row['Division'],
                            "RetailerZone"=>$row['RetailerZone'],
                        ];
                    }
                } else {
                    return redirect()->route('retailer.stockForm')->with('error','Invalid Data');
                }
            } else if ($clientType == 'dealer') {
                if (isset($responseData) && !empty($responseData)) {
                    foreach ($responseData as $key=>$row) {
                        if (!empty($modelArray)) {
                            if (in_array($row['Model'],$modelArray)) {
                                if ($searchId == $row['DealerCode'] || $searchId == $row['DealerPhone']) {
                                    $dealerStockTotQty += $row['StockQuantity'];
                                    $dataArray[$row['RetailerPhone']][] = $row['Model'].'~'.$row['StockQuantity'];
                                }
                            }
                        } else {
                            if ($searchId == $row['DealerCode'] || $searchId == $row['DealerPhone']) {
                                $dealerStockTotQty += $row['StockQuantity'];
                                $dataArray[$row['RetailerPhone']][] = $row['Model'].'~'.$row['StockQuantity'];
                            }
                        }

                        $userInfo[$row['RetailerPhone']] = [
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
                            "OwnerName"=>$row['OwnerName'],
                            "ThanaName"=>$row['ThanaName'],
                            "Division"=>$row['Division'],
                        ];
                    }
                } else {
                    return redirect()->route('retailer.stockForm')->with('error','Invalid Data');
                }
            } else if ($clientType == 'retailer') {
                if (isset($responseData) && !empty($responseData)){
                    foreach ($responseData as $key=>$row) {
                        if (!empty($modelArray)) {
                            if (in_array($row['Model'],$modelArray)) {
                                $dataArray[$row['RetailerPhone']][] = $row['Model'].'~'.$row['StockQuantity'];
                                $retailerStockTotQty += $row['StockQuantity'];
                            }
                        } else {
                            $dataArray[$row['RetailerPhone']][] = $row['Model'].'~'.$row['StockQuantity'];
                            $retailerStockTotQty += $row['StockQuantity'];
                        }

                        $retailerInfo = [
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
                            "OwnerName"=>$row['OwnerName'],
                            "ThanaName"=>$row['ThanaName'],
                            "Division"=>$row['Division'],
                        ];
                    }
                } else {
                    return redirect()->route('retailer.stockForm')->with('error','Invalid Data');
                }
            }
            Log::info('Load Retailer Stock');
            return view('admin.stock.retailer_stock_list',compact('responseData','clientInfo','resultType','clientType','dataArray','searchId','userInfo','dealerTotQty','retailerTotQty','employeeTotQty','dealerStockTotQty','retailerInfo','retailerStockTotQty','dealerWaiseRetailerInfo','modelArray','modelList'));
        } else {
            Log::warning('Invalid Request Retailer Stock');
            return redirect()->back()->with('error');
        }
    }

    public function getStockExcelDownload($clientType,$searchId,$searchModel = null) {
        $modelArray = json_decode($searchModel);
        if (!empty($clientType) && !empty($searchId)) {
            $getCurlResponse = getData(sprintf(RequestApiUrl("GetStock"),$searchId,$clientType),"GET");
            $responseData = json_decode($getCurlResponse['response_data'],true);
            $dataArray = [];
            $userInfo = [];
            $dealerTotQty = [];
            $retailerTotQty = 0;
            $employeeTotQty = 0;
            $dealerStockTotQty = 0;
            $retailerInfo = "";
            $retailerStockTotQty = 0;
            $dealerWaiseRetailerInfo = [];
            $clientInfo = Employee::where('employee_id','=',$searchId)->first();
            $fileDownloadUrl = "";

            if ($clientType == 'emp') {
                if(isset($responseData) && !empty($responseData)) {
                    $i = 1;
                    $stockQty = 0;
                    $totalQty = 0;
                    $toalValue = 0;
                    $htmlResponse = "";
                    $htmlResponse .="<table style='font-size: 12px; font-family: 'Helvetica Neue', Helvetica, Arial, Tahoma, sans-serif; color:#ffffff;border=1 px solid #000000'><tbody>";
                    $htmlResponse .="<tr><th colspan='10' style='text-align:center;border: 1px solid black;'>Stock Report</th></tr><tr style='border: 1px solid black;'><th>Dealer Name</th><th>Dealer Code</th><th>Zone</th><th>Retailer Name</th><th>Retailer Phone No</th><th>Retailer Address</th><th>Thana</th><th>Model</th><th>Stock Quantity</th><th>Stock Value</th></tr>";

                    foreach ($responseData as $key=>$row) {
                        $stockQty  = $row['StockQuantity'];
                        $getStockInfo = checkModelStock($row['Model']);
                        $getbgColor = ($i % 2 == 0) ?'eeeeee':'ffffff';
                        $stockStatusColor = "";
                        $currentStockValue = 0;
                        $currentModelMSDP = 0;
                        if (isset($getStockInfo) && !empty($getStockInfo)) {
                            $currentStockValue = $getStockInfo->msdp_price*$stockQty;
                            $currentModelMSDP  = $getStockInfo->msdp_price;
                            
                            if ($getStockInfo->default_qty != null && $getStockInfo->yeallow_qty != null && $getStockInfo->red_qty != null) {
                                if ($stockQty >= $getStockInfo->yeallow_qty && $stockQty < $getStockInfo->default_qty) {
                                    $stockStatusColor = 'FFFF00';
                                } else if ($stockQty < $getStockInfo->yeallow_qty && $stockQty >= $getStockInfo->red_qty) {
                                    $stockStatusColor = 'FF0000';
                                } else {
                                    $stockStatusColor = '7FFFD4';
                                }
                            } else {
                                if ($stockQty >= 1 && $stockQty < 2) {
                                    $stockStatusColor = 'FFFF00';
                                } else if($stockQty < 1 && $stockQty >= 0) {
                                    $stockStatusColor = 'FF0000';
                                } else {
                                    $stockStatusColor = '7FFFD4';
                                }
                            }
                        } else {
                            if ($stockQty >= 1 && $stockQty < 2) {
                                $stockStatusColor = 'FFFF00';
                            } else if ($stockQty < 1 && $stockQty >= 0) {
                                $stockStatusColor = 'FF0000';
                            } else {
                                $stockStatusColor = '7FFFD4';
                            }
                        }

                        if (!empty($modelArray)) {
                            if (in_array($row['Model'],$modelArray)) {
                                $totalQty += $row['StockQuantity'];
                                $toalValue += $row['StockQuantity']*$currentModelMSDP;
                                $totalDealerQty[] = $totalQty;
                                $htmlResponse .="<tr style='border: 1px solid black;'><td>".$row['DealerName']."</td><td>".$row['DealerCode']."</td><td>".$row['DealerZone']."</td><td>".$row['RetailerName']."</td><td>".$row['RetailerPhone']."</td><td>".$row['RetailerAddress']."</td><td>".$row['ThanaName']."</td><td>".$row['Model']."</td><td>".$row['StockQuantity']."</td><td>".$currentStockValue."</td></tr>";
                            }
                        } else {
                            $totalQty += $row['StockQuantity'];
                            $toalValue += $row['StockQuantity']*$currentModelMSDP;
                            $totalDealerQty[] = $totalQty;
                            $htmlResponse .="<tr style='border: 1px solid black;'><td>".$row['DealerName']."</td><td>".$row['DealerCode']."</td><td>".$row['DealerZone']."</td><td>".$row['RetailerName']."</td><td>".$row['RetailerPhone']."</td><td>".$row['RetailerAddress']."</td><td>".$row['ThanaName']."</td><td>".$row['Model']."</td><td>".$row['StockQuantity']."</td><td>".$currentStockValue."</td></tr>";
                        }
                    }

                    $htmlResponse .='<tr>
                            <th colspan="8" style="text-align: right; border: 1px solid black;">Grand Total</th>
                            <th style="text-align: right;border: 1px solid black;">
                                <span>'.number_format($totalQty).'</span>
                            </th><th style="text-align: right;border: 1px solid black;"><span>'.number_format($toalValue).'</th>
                        </tr>';
                    $htmlResponse.="</table>";
                }
                $data = $htmlResponse;
                $fileName = $searchId.'.xls';
                File::put(public_path('/upload/stock_excel_download/'.$fileName),$data);
                $fileDownloadUrl = $fileName;
            } else if ($clientType == 'dealer') {
                if (isset($responseData) && !empty($responseData)) {
                    $i = 1;
                    $stockQty = 0;
                    $totalQty = 0;
                    $toalValue = 0;
                    $htmlResponse = "";
                    $htmlResponse .="<table style='font-size: 12px; font-family: 'Helvetica Neue', Helvetica, Arial, Tahoma, sans-serif; color:#ffffff;border=1 px solid #000000'><tbody>";
                    $htmlResponse .="<tr><th colspan='10' style='text-align:center;border: 1px solid black;'>Stock Report</th></tr><tr style='border: 1px solid black;'><th>Dealer Name</th><th>Dealer Code</th><th>Zone</th><th>Retailer Name</th><th>Retailer Phone No</th><th>Retailer Address</th><th>Thana</th><th>Model</th><th>Stock Quantity</th><th>Stock Value</th></tr>";
                    foreach ($responseData as $key=>$row) {
                        $stockQty = $row['StockQuantity'];
                        $getStockInfo = checkModelStock($row['Model']);
                        $getbgColor = ($i % 2 == 0) ?'eeeeee':'ffffff';
                        $stockStatusColor = "";
                        $currentStockValue = 0;
                        $currentModelMSDP = 0;
                        if (isset($getStockInfo) && !empty($getStockInfo)) {
                            $currentStockValue = $getStockInfo->msdp_price*$stockQty;
                            $currentModelMSDP = $getStockInfo->msdp_price;
                            if ($getStockInfo->default_qty != null && $getStockInfo->yeallow_qty != null && $getStockInfo->red_qty != null ) {
                                if ($stockQty >= $getStockInfo->yeallow_qty && $stockQty < $getStockInfo->default_qty) {
                                    $stockStatusColor = 'FFFF00';
                                } else if ($stockQty < $getStockInfo->yeallow_qty && $stockQty >= $getStockInfo->red_qty) {
                                    $stockStatusColor = 'FF0000';
                                } else {
                                    $stockStatusColor = '7FFFD4';
                                }
                            } else {
                                if ($stockQty >= 1 && $stockQty < 2) {
                                    $stockStatusColor = 'FFFF00';
                                } else if($stockQty < 1 && $stockQty >= 0) {
                                    $stockStatusColor = 'FF0000';
                                } else {
                                    $stockStatusColor = '7FFFD4';
                                }
                            }
                        } else {
                            if ($stockQty >= 1 && $stockQty < 2) {
                                $stockStatusColor = 'FFFF00';
                            } else if ($stockQty < 1 && $stockQty >= 0) {
                                $stockStatusColor = 'FF0000';
                            } else {
                                $stockStatusColor = '7FFFD4';
                            }
                        }
                        if (!empty($modelArray)) {
                            if (in_array($row['Model'],$modelArray)) {
                                $totalQty += $row['StockQuantity'];
                                $toalValue += $row['StockQuantity'] * $currentModelMSDP;
                                $totalDealerQty[] = $totalQty;
                                $htmlResponse .="<tr style='border: 1px solid black;'><td>".$row['DealerName']."</td><td>".$row['DealerCode']."</td><td>".$row['DealerZone']."</td><td>".$row['RetailerName']."</td><td>".$row['RetailerPhone']."</td><td>".$row['RetailerAddress']."</td><td>".$row['ThanaName']."</td><td>".$row['Model']."</td><td>".$row['StockQuantity']."</td><td>".$currentStockValue."</td></tr>";
                            }
                        } else {
                            $totalQty += $row['StockQuantity'];
                            $toalValue += $row['StockQuantity']*$currentModelMSDP;
                            $totalDealerQty[] = $totalQty;
                            $htmlResponse .="<tr style='border: 1px solid black;'><td>".$row['DealerName']."</td><td>".$row['DealerCode']."</td><td>".$row['DealerZone']."</td><td>".$row['RetailerName']."</td><td>".$row['RetailerPhone']."</td><td>".$row['RetailerAddress']."</td><td>".$row['ThanaName']."</td><td>".$row['Model']."</td><td>".$row['StockQuantity']."</td><td>".$currentStockValue."</td></tr>";
                        }
                    }
                    $htmlResponse .='<tr>
                            <th colspan="8" style="text-align: right; border: 1px solid black;">Grand Total</th>
                            <th style="text-align: right;border: 1px solid black;">
                                <span>'.number_format($totalQty).'</span>
                            </th><th style="text-align: right;border: 1px solid black;"><span>'.number_format($toalValue).'</th>
                        </tr>';
                    $htmlResponse.="</table>";
                }
                $data = $htmlResponse;
                $fileName = $searchId.'.xls';
                File::put(public_path('/upload/stock_excel_download/'.$fileName),$data);

                $fileDownloadUrl = $fileName;
            } else if ($clientType == 'retailer') {
                if (isset($responseData) && !empty($responseData)) {
                    $i = 1;
                    $stockQty = 0;
                    $totalQty = 0;
                    $toalValue = 0;
                    $htmlResponse = "";
                    $htmlResponse .="<table style='font-size: 12px; font-family: 'Helvetica Neue', Helvetica, Arial, Tahoma, sans-serif; color:#ffffff;border=1 px solid #000000'><tbody>";
                    $htmlResponse .="<tr><th colspan='10' style='text-align:center;border: 1px solid black;'>Stock Report</th></tr><tr style='border: 1px solid black;'><th>Dealer Name</th><th>Dealer Code</th><th>Zone</th><th>Retailer Name</th><th>Retailer Phone No</th><th>Retailer Address</th><th>Thana</th><th>Model</th><th>Stock Quantity</th><th>Stock Value</th></tr>";
                    foreach ($responseData as $key=>$row) {
                        $stockQty = $row['StockQuantity'];
                        $getStockInfo = checkModelStock($row['Model']);
                        $getbgColor = ($i % 2 == 0) ?'eeeeee':'ffffff';
                        $stockStatusColor  = "";
                        $currentStockValue = 0;
                        $currentModelMSDP  = 0;
                        if (isset($getStockInfo) && !empty($getStockInfo)) {
                            $currentStockValue = $getStockInfo->msdp_price * $stockQty;
                            $currentModelMSDP = $getStockInfo->msdp_price;
                            
                            if ($getStockInfo->default_qty != null && $getStockInfo->yeallow_qty != null && $getStockInfo->red_qty != null)  {
                                if ($stockQty >= $getStockInfo->yeallow_qty && $stockQty < $getStockInfo->default_qty) {
                                    $stockStatusColor = 'FFFF00';
                                } else if($stockQty < $getStockInfo->yeallow_qty && $stockQty >= $getStockInfo->red_qty) 
                                {
                                    $stockStatusColor = 'FF0000';
                                } else {
                                    $stockStatusColor = '7FFFD4';
                                }
                            } else {
                                if ($stockQty >= 1 && $stockQty < 2) {
                                    $stockStatusColor = 'FFFF00';
                                } else if($stockQty < 1 && $stockQty >= 0) {
                                    $stockStatusColor = 'FF0000';
                                } else {
                                    $stockStatusColor = '7FFFD4';
                                }
                            }
                        } else {
                            if ($stockQty >= 1 && $stockQty < 2) {
                                $stockStatusColor = 'FFFF00';
                            } else if($stockQty < 1 && $stockQty >= 0) {
                                $stockStatusColor = 'FF0000';
                            } else {
                                $stockStatusColor = '7FFFD4';
                            }
                        }
                        if (!empty($modelArray)) {
                            if (in_array($row['Model'],$modelArray)) {
                                $totalQty += $row['StockQuantity'];
                                $toalValue += $row['StockQuantity']*$currentModelMSDP;
                                $totalDealerQty[] = $totalQty;
                                $htmlResponse .="<tr style='border: 1px solid black;'><td>".$row['DealerName']."</td><td>".$row['DealerCode']."</td><td>".$row['DealerZone']."</td><td>".$row['RetailerName']."</td><td>".$row['RetailerPhone']."</td><td>".$row['RetailerAddress']."</td><td>".$row['ThanaName']."</td><td>".$row['Model']."</td><td>".$row['StockQuantity']."</td><td>".$currentStockValue."</td></tr>";
                            }
                        } else {
                            $totalQty += $row['StockQuantity'];
                            $toalValue += $row['StockQuantity']*$currentModelMSDP;
                            $totalDealerQty[] = $totalQty;
                            $htmlResponse .="<tr style='border: 1px solid black;'><td>".$row['DealerName']."</td><td>".$row['DealerCode']."</td><td>".$row['DealerZone']."</td><td>".$row['RetailerName']."</td><td>".$row['RetailerPhone']."</td><td>".$row['RetailerAddress']."</td><td>".$row['ThanaName']."</td><td>".$row['Model']."</td><td>".$row['StockQuantity']."</td><td>".$currentStockValue."</td></tr>";
                        }
                    }

                    $htmlResponse .= '<tr>
                            <th colspan="8" style="text-align: right; border: 1px solid black;">Grand Total</th>
                            <th style="text-align: right;border: 1px solid black;">
                                <span>'.number_format($totalQty).'</span>
                            </th><th style="text-align: right;border: 1px solid black;"><span>'.number_format($toalValue).'</th>
                        </tr>';
                    $htmlResponse.="</table>";
                }
                $data = $htmlResponse;
                $fileName = $searchId.'.xls';
                File::put(public_path('/upload/stock_excel_download/'.$fileName),$data);

                $fileDownloadUrl = $fileName;
            }
            return response()->json($fileDownloadUrl);
        }
    }

    public function getStockResult(Request $request) {
        $clientType = $request->input('client_type');
        $searchId = $request->input('search_id');
        $resultType = $request->input('result_type');
        $rules = ['client_type'=>'required','search_id'=>'required',];
        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            Log::error('Retailer Stock Request Validation error');
            return redirect()->back()->with(['errors'=>$validator->errors()]);
        }
        if (isset($clientType) && !empty($clientType) && isset($searchId)  && !empty($searchId)) {
            $getCurlResponse = getData(sprintf(RequestApiUrl("GetStock"),$searchId,$clientType),"GET");
            $responseData = json_decode($getCurlResponse['response_data'],true);
            Log::info('Load Retailer Stock');
            return view('admin.stock.retailer_stock_list',compact('responseData','clientType','resultType'));
        } else {
            Log::warning('Invalid Request Retailer Stock');
            return redirect()->back()->with('error');
        }
    }
    
    public function salesReturn($orderId) {
        $orderIMEI = DB::table('sale_products')->where('sales_id',$orderId)->get(['ime_number']);
        $getPostCurlResponse = salesReturn(sprintf(RequestApiUrl("ReturnOrder")),$orderId);

        if ($getPostCurlResponse == "success") {
            $orderStatus = DB::table('sales')->where('id','=',$orderId)->update(['status'=>3]);
            if ($orderStatus) {
                return response()->json('success');
            }
        } else {
            return response()->json('error');
        }
    }
    
    public function getIMEIdisputeNumber(Request $request) {
        $imeiDisputeList = "";
        if ($request->ajax()) {
            $sort_by = $request->get('sortby');
            $sort_type = $request->get('sorttype');
            $query = $request->get('query');
            $query = str_replace(" ", "%", $query);
            $searchVal = str_replace(" ", "%", $query);

            $imeiDisputeList = DB::table('view_imei_dispute_list')
                ->where('status','=',0)
                ->where(function($sql_query) use($searchVal){
                    if(!empty($searchVal) || $searchVal !=null ) {
                        $sql_query->where('dealer_name','like', '%'.$searchVal.'%')
                        ->orWhere('dealer_phone_number', 'like', '%'.$searchVal.'%')
                        ->orWhere('distributor_code','like', '%'.$searchVal.'%')
                        ->orWhere('retailer_name', 'like', '%'.$searchVal.'%')
                        ->orWhere('retailer_phone','like', '%'.$searchVal.'%')
                        ->orWhere('bp_name','like', '%'.$searchVal.'%')
                        ->orWhere('bp_phone','like', '%'.$searchVal.'%')
                        ->orWhere('imei_number','like', '%'.$searchVal.'%')
                        ->orWhere('comments','like', '%'.$searchVal.'%');
                    }
                })
                ->orderBy($sort_by, $sort_type)
                //->paginate(100);
                ->get();
            return view('admin.imei_dispute.result_data', compact('imeiDisputeList'))->render();
        } else {
            $imeiDisputeList = DB::table('view_imei_dispute_list')->where('status','=',0)->get();
        }

        if (isset($imeiDisputeList) && $imeiDisputeList->isNotEmpty()) {
            Log::info('Load IMEI Dispute List');
        } else {
            Log::warning('IMEI Dispute List Not Found');
        }
        return view('admin.imei_dispute.list',compact('imeiDisputeList'));
    }

    public function editIMEIdisputeNumber($id) {
        $imeiDisputeNumberInfo = DB::table('imei_disputes')->where('id','=',$id)->first();
        if (isset($imeiDisputeNumberInfo) && !empty($imeiDisputeNumberInfo)) {
            return response()->json(['status'=>'success','imeidisputeInfo'=>$imeiDisputeNumberInfo]);
        }
        return response()->json('error');
    }

    public function updateIMEIdisputeNumber(Request $request) {
        $comments = $request->input('comments');
        $imeiDisputeNumber = $request->input('imei_number');
        $imeiDisputeId = $request->input('imei_id');
        if (isset($imeiDisputeId) && $imeiDisputeId > 0) {
            $updateStatus = DB::table('imei_disputes')->where('id','=',$imeiDisputeId)->update([
                "status"=> $request->input('status'),
                "comments"=> $request->input('comments'),
                "updated_at"=>Carbon::now()
            ]);
            if ($updateStatus) {
                return response()->json('success');
            }
        }
        return response()->json('error');
    }
    
    public function getAllPendingOrder() {
        $saleList = DB::table('sales')->where('status',1)->get();

        foreach($saleList as $sale) {
            $saleProductList = DB::table('sale_products')->select('*')->where('sales_id',$sale->id)->get();
            $dealerInfo = DB::table('dealer_informations')
                ->select('dealer_code as code','alternate_code as alternate_code','dealer_name as name','dealer_address as address','zone','dealer_phone_number as phone')
                ->where('dealer_code',$sale->dealer_code)
                ->orWhere('alternate_code',$sale->dealer_code)
                ->first();

            foreach ($saleProductList as $saleProduct) {
                $saleProduct->dealer_name = !empty($dealerInfo->name) ? $dealerInfo->name:"";
                $saleProduct->dealer_phone = !empty($dealerInfo->phone) ? $dealerInfo->phone :"";
                $saleProduct->dealer_code = !empty($dealerInfo->code) ? $dealerInfo->code : "";
                $saleProduct->alternet_code = !empty($dealerInfo->alternate_code) ? $dealerInfo->alternate_code : "";
              //  $sale->product_list = $saleProduct;
            }
            $sale->product_list = $saleProductList;

            $retailerInfo = DB::table('retailers')
                ->select('retailer_name as name','retailder_address as address','phone_number as phone')
                ->where('retailer_id',$sale->retailer_id)
                ->first();

            $brandPromoterInfo = DB::table('brand_promoters')
                ->select('bp_name as name','bp_phone as phone')
                ->where('bp_id',$sale->bp_id)
                ->first();

            $sale->retailer_info = $retailerInfo;
            $sale->bp_info = $brandPromoterInfo;            
        }
        return view('admin.report.pending_sales',compact('saleList'));
    }

    public function PendingOrderStatus($orderId) {
        $StatusInfo = DB::table('sales')->where('id',$orderId)->value('status');
        $old_status = $StatusInfo;
        $UpdateStatus = $old_status == 1 ? 0 : 1;

        $Update = DB::table('sales')->where('id',$orderId)->update([
            "status"=>$UpdateStatus ? $UpdateStatus:0
        ]);

        if ($Update) {
            Log::info('Order Status Updated Successfully');
            return response()->json(['success'=>'Status change successfully.']);
        } else {
            Log::error('Order Status Updated Failed');
            return response()->json(['error'=>'Status Update Failed.Please Try Again.']);
        }
    }

    public function getAllPendingMessage() {
        $MessageList = \DB::table('authority_messages as tab1')
            ->select('tab1.*')
            ->leftJoin('authority_messages as tab2','tab2.reply_for','=','tab1.id')
            ->where('tab1.reply_for','=',0)
            ->whereNull('tab2.reply_for')
            ->orderBy('tab1.id','asc')
            ->paginate(10);
        return view('admin.message.list',compact('MessageList'));
    }
    
    public function getAllPendingLeave(Request $request) {
        $leaveList = DB::table('view_bp_leave_report')->where('status','=','Pending')->paginate(10);
        if ($request->ajax()) {
            $sort_by = $request->get('sortby');
            $sort_type = $request->get('sorttype');
            $query = $request->get('query');
            $query = str_replace(" ", "%", $query);
            $leaveList = DB::table('view_bp_leave_report')
                ->where('id',$query)
                ->where('status','=','Pending')
                ->orWhere('bp_name','like', '%'.$query.'%')
                ->orWhere('leave_type', 'like', '%'.$query.'%')
                ->orWhere('apply_date', 'like', '%'.$query.'%')
                ->orWhere('start_date', 'like', '%'.$query.'%')
                ->orWhere('total_day', 'like', '%'.$query.'%')
                ->orWhere('start_time', 'like', '%'.$query.'%')
                ->orWhere('reason', 'like', '%'.$query.'%')
                ->orWhere('status','=','Pending')
                ->orderBy($sort_by, $sort_type)
                ->paginate(10);

            return view('admin.report.bp_pending_leave_result_data', compact('leaveList'))->render();
        }
        return view('admin.report.bp_pending_leave_list',compact('leaveList'));
    }

    public function getPreBookingOrderList(Request $request) {
        Session::forget('search_bpid');
        Session::forget('search_retailerid');
        Session::forget('search_sdate');
        Session::forget('search_edate');
        Session::forget('search_dealerid');
        Session::forget('search_productid');
        $month_Sdate = date('Y-m-01');
        $month_Edate = date('Y-m-t');        
        $preBookingOrderList = "";
        if ($request->ajax()) {
            $sort_by = $request->get('sortby');
            $sort_type = $request->get('sorttype');
            $query = $request->get('query');
            $query = str_replace(" ", "%", $query);
            $searchVal = str_replace(" ", "%", $query);

            $preBookingOrderList = DB::table('view_prebooking_order_lists')
                ->select('customer_name','customer_phone','customer_address','model','color','qty as bookingQty','advanced_payment','booking_date','bp_name','bp_phone','retailer_name','retailer_phone_number','retailder_address','dealer_name','dealer_phone_number','distributor_code','distributor_code2')
                ->whereBetween('booking_date',[$month_Sdate,$month_Edate])
                ->where(function($sql_query) use($searchVal) {
            		$sql_query->where('customer_name','like', '%'.$searchVal.'%')
            			->orWhere('customer_phone','like', '%'.$searchVal.'%')
            			->orWhere('model', 'like', '%'.$searchVal.'%')
            			->orWhere('color','like', '%'.$searchVal.'%')
            			->orWhere('qty', 'like', '%'.$searchVal.'%')
            			->orWhere('advanced_payment','like', '%'.$searchVal.'%')
            			->orWhere('bp_name','like', '%'.$searchVal.'%')
            			->orWhere('bp_phone','like', '%'.$searchVal.'%')
            			->orWhere('retailer_name','like', '%'.$searchVal.'%')
            			->orWhere('retailer_phone_number','like', '%'.$searchVal.'%')
            			->orWhere('dealer_name','like', '%'.$searchVal.'%')
            			->orWhere('dealer_phone_number','like', '%'.$searchVal.'%')
            			->orWhere('distributor_code','like', '%'.$searchVal.'%')
            			->orWhere('distributor_code2','like', '%'.$searchVal.'%');
                })
                ->paginate(100);

            return view('admin.report.prebooking_order_result_data', compact('preBookingOrderList'))->render();
        } else {
            $preBookingOrderList = DB::table('view_prebooking_order_lists')
                ->select('customer_name','customer_phone','customer_address','model','color','qty as bookingQty','advanced_payment','booking_date','bp_name','bp_phone','retailer_name','retailer_phone_number','retailder_address','dealer_name','dealer_phone_number','distributor_code','distributor_code2')
                ->whereBetween('booking_date',[$month_Sdate,$month_Edate])
                ->paginate(100);
        }
        return view('admin.report.prebooking_order_report',compact('preBookingOrderList'));
    }
    
    public function preBookingReport(Request $request) {
        $getbpId = $request->input('bp_id');
        $getretailId = $request->input('retailer_id');
        $productId = $request->input('product_id');
        $getdealerCode = $request->input('dealer_code');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        
        Session::put('search_bpid',$getbpId);
        Session::put('search_retailerid',$getretailId);
        Session::put('search_sdate',$start_date);
        Session::put('search_edate',$end_date);
        Session::put('search_dealerid',$getdealerCode);
        Session::put('search_productid',$productId);

        $dealerCode = Session::get('search_dealerid') ? Session::get('search_dealerid') : 0;
        $bpId = Session::get('search_bpid') ? Session::get('search_bpid') : 0; 
        $retailId = Session::get('search_retailerid') ? Session::get('search_retailerid') : 0;
        $month_Sdate = Session::get('search_sdate') ? Session::get('search_sdate') : $start_date;
        $month_Edate = Session::get('search_edate') ? Session::get('search_edate') : $end_date;

        $sellerName = "";
        if ($bpId > 0) { 
            $sellerName = BrandPromoter::where('status','=',1)->where('id','=',$bpId)->value('bp_name');
        } 

        if ($retailId > 0) {
            $sellerName = Retailer::where('status','=',1)->where('id','=',$retailId)->value('retailer_name');
        }

        $modelName = "";
        if ($productId > 0) {
            $modelName = Products::where('product_master_id','=',$productId)->value('product_model');
        }
        
        $preBookingOrderList = "";
        if ($request->ajax()) {
            $sort_by = $request->get('sortby');
            $sort_type = $request->get('sorttype');
            $query = $request->get('query');
            $query = str_replace(" ", "%", $query);
            $searchVal = str_replace(" ", "%", $query);

            $preBookingOrderList =  DB::table('view_prebooking_order_lists')
                ->select('customer_name','customer_phone','customer_address','model','color','qty as bookingQty','advanced_payment','booking_date','bp_name','bp_phone','retailer_name','retailer_phone_number','retailder_address','dealer_name','dealer_phone_number','distributor_code','distributor_code2')
                ->whereBetween(\DB::raw("DATE_FORMAT(booking_date, '%Y-%m-%d')"),[$month_Sdate,$month_Edate])
                ->where(function($sql_query) use($searchVal,$dealerCode,$bpId,$retailId,$modelName) {
            		$sql_query->where('customer_name','like', '%'.$searchVal.'%')
            			->orWhere('customer_phone','like', '%'.$searchVal.'%')
            			->orWhere('model', 'like', '%'.$searchVal.'%')
            			->orWhere('color','like', '%'.$searchVal.'%')
            			->orWhere('qty', 'like', '%'.$searchVal.'%')
            			->orWhere('advanced_payment','like', '%'.$searchVal.'%')
            			->orWhere('bp_name','like', '%'.$searchVal.'%')
            			->orWhere('bp_phone','like', '%'.$searchVal.'%')
            			->orWhere('retailer_name','like', '%'.$searchVal.'%')
            			->orWhere('retailer_phone_number','like', '%'.$searchVal.'%')
            			->orWhere('dealer_name','like', '%'.$searchVal.'%')
            			->orWhere('dealer_phone_number','like', '%'.$searchVal.'%')
            			->orWhere('distributor_code','like', '%'.$searchVal.'%')
            			->orWhere('distributor_code2','like', '%'.$searchVal.'%');
                    if ($dealerCode > 0) {
                        $sql_query->where('distributor_code', '=', $dealerCode);
                        $sql_query->orWhere('distributor_code2', '=', $dealerCode);
                    }
                    if ($bpId > 0) {
                        $sql_query->where('bp_id', '=', $bpId);
                    }
                    if ($retailId > 0) {
                        $sql_query->where('retailer_id', '=', $retailId);
                    }
                    if (!empty($modelName)) {
                        $sql_query->where('model','=',$modelName);
                    }
                })
                ->paginate(100);

            return view('admin.report.prebooking_order_result_data', compact('preBookingOrderList'))->render();
        } else {
            $preBookingOrderList =  DB::table('view_prebooking_order_lists')
                ->select('customer_name','customer_phone','customer_address','model','color','qty as bookingQty','advanced_payment','booking_date','bp_name','bp_phone','retailer_name','retailer_phone_number','retailder_address','dealer_name','dealer_phone_number','distributor_code','distributor_code2')
                ->whereBetween(\DB::raw("DATE_FORMAT(booking_date, '%Y-%m-%d')"),[$start_date,$end_date])
                ->where(function($sql_query) use($dealerCode,$bpId,$retailId,$modelName) {
            		if ($dealerCode > 0) {
            			$sql_query->where('distributor_code', '=', $dealerCode);
            			$sql_query->orWhere('distributor_code2', '=', $dealerCode);
            		}
            		if ($bpId > 0) {
            			$sql_query->where('bp_id', '=', $bpId);
            		}
            		if ($retailId > 0) {
            			$sql_query->where('retailer_id', '=', $retailId);
            		}
            		if (!empty($modelName)) {
            			$sql_query->where('model','=',$modelName);
            		}
                })
                ->paginate(100);
        }
        return view('admin.report.prebooking_order_report',compact('preBookingOrderList','sellerName'));
    }

    public function preOrderReportDetails($model) {
        $month_Sdate = date('Y-m-01');
        $month_Edate = date('Y-m-t');

        $salesInfoList = DB::table('prebooking_orders')
            ->select('prebooking_orders.*','brand_promoters.bp_name','retailers.retailer_name')
            ->leftJoin("brand_promoters", "brand_promoters.id", "=", "prebooking_orders.bp_id")
            ->leftJoin("retailers", "retailers.id", "=", "prebooking_orders.retailer_id")
            ->where('prebooking_orders.model','like','%'.$model.'%')
            ->get();

        $viewProductInfo = [];
        foreach ($salesInfoList as $salesInfo) {
             $viewProductInfo[] = "<tr><td>".$salesInfo->customer_name."</td><td>".$salesInfo->customer_phone."</td><td>".$salesInfo->customer_address."</td><td>".$salesInfo->model."</td><td>".$salesInfo->color."</td><td>".$salesInfo->qty."</td><td class='text-right'>".$salesInfo->advanced_payment."</td><td>".$salesInfo->booking_date."</td><td>".$salesInfo->bp_name."</td><td>".$salesInfo->retailer_name."</td></tr>";
        }
        if ($viewProductInfo) {
            return response()->json(['itemList'=>$viewProductInfo]);
        } else {
             return response()->json('error');
        }
    }

    public function getPendingOrderReportList(Request $request) {
        Session::forget('search_bpid');
        Session::forget('search_retailerid');
        Session::forget('search_sdate');
        Session::forget('search_edate');
        Session::forget('search_dealerid');
        Session::forget('search_productid');

        $bpList = BrandPromoter::get(['bp_id','bp_name']);
        $retailerList = Retailer::get(['retailer_id','retailer_name']);
        $month_Sdate =  date('Y-m-01');
        $month_Edate =  date('Y-m-t');        
        $saleList = "";

        if ($request->ajax()) {
            $sort_by = $request->get('sortby');
            $sort_type = $request->get('sorttype');
            $query = $request->get('query');
            $query = str_replace(" ", "%", $query);
            $searchVal = str_replace(" ", "%", $query);

            $saleList = DB::table('view_sales_reports')
                ->whereIn('status',[1,2])
                ->whereBetween(\DB::raw("DATE_FORMAT(sale_date, '%Y-%m-%d')"),[$month_Sdate,$month_Edate])
                ->where(function($sql_query) use($searchVal) {
            		$sql_query->where('customer_name','like', '%'.$searchVal.'%')
            			->orWhere('customer_phone','like', '%'.$searchVal.'%')
            			->orWhere('ime_number', 'like', '%'.$searchVal.'%')
            			->orWhere('alternate_imei','like', '%'.$searchVal.'%')
            			->orWhere('product_model', 'like', '%'.$searchVal.'%')
            			->orWhere('dealer_name','like', '%'.$searchVal.'%')
            			->orWhere('dealer_phone_number','like', '%'.$searchVal.'%')
            			->orWhere('dealer_code','like', '%'.$searchVal.'%')
            			->orWhere('retailer_name','like', '%'.$searchVal.'%')
            			->orWhere('retailer_phone_number','like', '%'.$searchVal.'%')
            			->orWhere('bp_name','like', '%'.$searchVal.'%')
            			->orWhere('bp_phone','like', '%'.$searchVal.'%');
                })
                ->orderBy($sort_by, $sort_type)
                ->paginate(100);
            
            return view('admin.report.pending_sales_report_result_data', compact('saleList'))->render();
        } else {
            $saleList = DB::table('view_sales_reports')
                ->whereIn('status',[1,2])
                ->whereBetween('sale_date',[$month_Sdate,$month_Edate])
                ->orderBy('id','desc')
                ->paginate(100);
    
            foreach($saleList as $sale) {
                $saleProductList = DB::table('sale_products')
                    ->select('*')
                    ->where('product_status','=',1)
                    ->where('sales_id',$sale->id)
                    ->get();
    
                $dealerInfo = DB::table('dealer_informations')
                    ->select('dealer_code as code','alternate_code as alternate_code','dealer_name as name','dealer_address as address','zone','dealer_phone_number as phone')
                    ->where('dealer_code',$sale->dealer_code)
                    ->orWhere('alternate_code',$sale->dealer_code)
                    ->first();
    
                foreach ($saleProductList as $saleProduct)  {
                    $saleProduct->dealer_name = !empty($dealerInfo->name) ? $dealerInfo->name:"";
                    $saleProduct->dealer_phone = !empty($dealerInfo->phone) ? $dealerInfo->phone :"";
                    $saleProduct->dealer_code = !empty($dealerInfo->code) ? $dealerInfo->code : "";
                    $saleProduct->alternet_code = !empty($dealerInfo->alternate_code) ? $dealerInfo->alternate_code : "";
                }                
                $sale->product_list = $saleProductList;
                $retailerInfo = DB::table('retailers')
                    ->select('retailer_name as name','retailder_address as address','phone_number as phone')
                    ->where('retailer_id',$sale->retailer_id)
                    ->first();    
                $brandPromoterInfo = DB::table('brand_promoters')
                    ->select('bp_name as name','bp_phone as phone')
                    ->where('bp_id',$sale->bp_id)
                    ->first();        
                $sale->retailer_info = $retailerInfo;
                $sale->bp_info = $brandPromoterInfo;
            }
        }

        if (isset($saleList) && $saleList->isNotEmpty()) {
            Log::info('Load Order Pending Sale List');
        } else {
            Log::warning('Order Pending Sales List Not Found');
        }
        return view('admin.report.pending_sales_report',compact('saleList'));
    }

    public function searchPendingReportOrderList(Request $request) {
        $getbpId = $request->input('bp_id');
        $getretailId = $request->input('retailer_id');
        $productId = $request->input('product_id');
        $getdealerCode = $request->input('dealer_code');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        
        Session::put('search_bpid',$getbpId);
        Session::put('search_retailerid',$getretailId);
        Session::put('search_sdate',$start_date);
        Session::put('search_edate',$end_date);
        Session::put('search_dealerid',$getdealerCode);
        Session::put('search_productid',$productId);

        $bpId = Session::get('search_bpid') ? Session::get('search_bpid') : 0; 
        $retailId = Session::get('search_retailerid') ? Session::get('search_retailerid') : 0;
        $dealerCode = Session::get('search_dealerid') ? Session::get('search_dealerid') : 0;
        $fromDate = Session::get('search_sdate') ? Session::get('search_sdate') : date('Y-m-01');
        $toDate = Session::get('search_edate') ? Session::get('search_edate') : date('Y-m-t');
        $sellerName = "";

        if ($bpId > 0) { 
            $sellerName = BrandPromoter::where('status','=',1)->where('id','=',$bpId)->value('bp_name');
        } 

        if ($retailId > 0) {
            $sellerName = Retailer::where('status','=',1)->where('id','=',$retailId)->value('retailer_name');
        }
        
        $saleList = "";
        if ($request->ajax()) {
            $sort_by = $request->get('sortby');
            $sort_type = $request->get('sorttype');
            $query = $request->get('query');
            $query = str_replace(" ", "%", $query);
            $searchVal = str_replace(" ", "%", $query);

            $saleList = DB::table('view_sales_reports')
                ->whereIn('status',[1,2])
                ->whereBetween(\DB::raw("DATE_FORMAT(sale_date, '%Y-%m-%d')"),[$fromDate,$toDate])
                ->where(function($sql_query) use($searchVal,$dealerCode,$bpId,$retailId,$productId) {
            		if ($searchVal !=null || !empty($searchVal)) {
                        $sql_query->where('customer_name','like', '%'.$searchVal.'%')
                			->orWhere('customer_phone','like', '%'.$searchVal.'%')
                			->orWhere('ime_number', 'like', '%'.$searchVal.'%')
                			->orWhere('alternate_imei','like', '%'.$searchVal.'%')
                			->orWhere('product_model', 'like', '%'.$searchVal.'%')
                			->orWhere('dealer_name','like', '%'.$searchVal.'%')
                			->orWhere('dealer_phone_number','like', '%'.$searchVal.'%')
                			->orWhere('dealer_code','like', '%'.$searchVal.'%')
                			->orWhere('retailer_name','like', '%'.$searchVal.'%')
                			->orWhere('retailer_phone_number','like', '%'.$searchVal.'%')
                			->orWhere('bp_name','like', '%'.$searchVal.'%')
                			->orWhere('bp_phone','like', '%'.$searchVal.'%');
                    }

                    if ($dealerCode > 0) {
                        $sql_query->where('dealer_code', '=', $dealerCode);
                        $sql_query->orWhere('alternate_code', '=', $dealerCode);
                    }
                    if ($bpId > 0) {
                        $sql_query->where('bp_id', '=', $bpId);
                    }
                    if ($retailId > 0) {
                        $sql_query->where('retailer_id', '=', $retailId);
                    }
                    if ($productId > 0) {
                        $sql_query->where('product_master_id', '=', $productId);
                    }
                })
                ->orderBy($sort_by, $sort_type)
                ->paginate(100);
            
            return view('admin.report.pending_sales_report_result_data', compact('saleList'))->render();
        } else {
            $saleList = DB::table('view_sales_reports')
                ->whereIn('status',[1,2])
                ->whereBetween(\DB::raw("DATE_FORMAT(sale_date, '%Y-%m-%d')"),[$fromDate,$toDate])
                ->where(function($sql_query) use($dealerCode,$bpId,$retailId,$productId) {
            		if ($dealerCode > 0) {
            			$sql_query->where('dealer_code', '=', $dealerCode);
            			$sql_query->orWhere('alternate_code', '=', $dealerCode);
            		}
            		if ($bpId > 0) {
            			$sql_query->where('bp_id', '=', $bpId);
            		}
            		if ($retailId > 0) {
            			$sql_query->where('retailer_id', '=', $retailId);
            		}
            		if ($productId > 0) {
            			$sql_query->where('product_master_id', '=', $productId);
            		}
                })
                ->orderBy('id','desc')
                ->paginate(100);

            foreach ($saleList as $sale) {
                $saleProductList = DB::table('sale_products')
                    ->select('*')
                    ->where('product_status','=',1)
                    ->where('sales_id',$sale->id)
                    ->get();
    
                $dealerInfo = DB::table('dealer_informations')
                    ->select('dealer_code as code','alternate_code as alternate_code','dealer_name as name','dealer_address as address','zone','dealer_phone_number as phone')
                    ->where('dealer_code',$sale->dealer_code)
                    ->orWhere('alternate_code',$sale->dealer_code)
                    ->first();
    
                foreach ($saleProductList as $saleProduct)  {
                    $saleProduct->dealer_name = !empty($dealerInfo->name) ? $dealerInfo->name : "";
                    $saleProduct->dealer_phone = !empty($dealerInfo->phone) ? $dealerInfo->phone :"";
                    $saleProduct->dealer_code = !empty($dealerInfo->code) ? $dealerInfo->code : "";
                    $saleProduct->alternet_code = !empty($dealerInfo->alternate_code) ? $dealerInfo->alternate_code : "";
                }
                $sale->product_list = $saleProductList;
    
                $retailerInfo = DB::table('retailers')
                    ->select('retailer_name as name','retailder_address as address','phone_number as phone')
                    ->where('retailer_id',$sale->retailer_id)
                    ->first();
    
                $brandPromoterInfo = DB::table('brand_promoters')
                    ->select('bp_name as name','bp_phone as phone')
                    ->where('bp_id',$sale->bp_id)
                    ->first();    
                $sale->retailer_info = $retailerInfo;
                $sale->bp_info = $brandPromoterInfo;
            }
        }

        if (isset($saleList) && $saleList->isNotEmpty()) {
            Log::info('Load Order Pending Sale List');
        } else {
            Log::warning('Order Pending Sales List Not Found');
        }
        return view('admin.report.pending_sales_report',compact('saleList','sellerName'));
    }
    
    public function getPendingOrderList(Request $request) {
        $bpList = BrandPromoter::get(['bp_id','bp_name']);
        $retailerList = Retailer::get(['retailer_id','retailer_name']);
        $month_Sdate =  date('Y-m-01');
        $month_Edate =  date('Y-m-t');

        if ($request->ajax()) {
            $sort_by = $request->get('sortby');
            $sort_type = $request->get('sorttype');
            $query = $request->get('query');
            $query = str_replace(" ", "%", $query);
            $searchVal = str_replace(" ", "%", $query);
            $saleList = DB::table('view_sales_reports')
                ->where('status','=',1)
                ->whereBetween(\DB::raw("DATE_FORMAT(sale_date, '%Y-%m-%d')"),[$month_Sdate,$month_Edate])
                ->where(function($sql_query) use($searchVal) {
                    $sql_query->where('customer_name','like', '%'.$searchVal.'%')
                        ->orWhere('customer_phone','like', '%'.$searchVal.'%')
                        ->orWhere('ime_number', 'like', '%'.$searchVal.'%')
                        ->orWhere('alternate_imei','like', '%'.$searchVal.'%')
                        ->orWhere('product_model', 'like', '%'.$searchVal.'%')
                        ->orWhere('dealer_name','like', '%'.$searchVal.'%')
                        ->orWhere('dealer_code','like', '%'.$searchVal.'%')
                        ->orWhere('retailer_name','like', '%'.$searchVal.'%')
                        ->orWhere('retailer_phone_number','like', '%'.$searchVal.'%')
                        ->orWhere('bp_name','like', '%'.$searchVal.'%')
                        ->orWhere('bp_phone','like', '%'.$searchVal.'%');
                })
                ->orderBy($sort_by, $sort_type)
                ->paginate(100);

            return view('admin.order.pending_sales_list_result_data', compact('saleList'))->render();
        } else {
            $saleList = DB::table('view_sales_reports')
                ->where('status','=',1)
                ->whereBetween(\DB::raw("DATE_FORMAT(sale_date, '%Y-%m-%d')"),[$month_Sdate,$month_Edate])
                ->orderBy('id','desc')
                ->paginate(100);
    
            foreach ($saleList as $sale) {
                $saleProductList = DB::table('sale_products')
                    ->select('*')
                    ->where('product_status','=',1)
                    ->where('sales_id',$sale->id)
                    ->get();
                $dealerInfo = DB::table('dealer_informations')
                    ->select('dealer_code as code','alternate_code as alternate_code','dealer_name as name','dealer_address as address','zone','dealer_phone_number as phone')
                    ->where('dealer_code',$sale->dealer_code)
                    ->orWhere('alternate_code',$sale->dealer_code)
                    ->first();
                foreach ($saleProductList as $saleProduct) {
                    $saleProduct->dealer_name = !empty($dealerInfo->name) ? $dealerInfo->name:"";
                    $saleProduct->dealer_phone = !empty($dealerInfo->phone) ? $dealerInfo->phone :"";
                    $saleProduct->dealer_code = !empty($dealerInfo->code) ? $dealerInfo->code : "";
                    $saleProduct->alternet_code = !empty($dealerInfo->alternate_code) ? $dealerInfo->alternate_code : "";
                }    
                $sale->product_list = $saleProductList;    
                $retailerInfo = DB::table('retailers')
                    ->select('retailer_name as name','retailder_address as address','phone_number as phone')
                    ->where('retailer_id',$sale->retailer_id)
                    ->first();    
                $brandPromoterInfo = DB::table('brand_promoters')
                    ->select('bp_name as name','bp_phone as phone')
                    ->where('bp_id',$sale->bp_id)
                    ->first();    
                $sale->retailer_info = $retailerInfo;
                $sale->bp_info = $brandPromoterInfo;
            }
        }

        if (isset($saleList) && $saleList->isNotEmpty()) {
            Log::info('Load Order Pending Sale List');
        } else {
            Log::warning('Order Pending Sales List Not Found');
        }
        return view('admin.order.pending_sales_list',compact('saleList'));
    }
    
    public function searchPendingOrderList(Request $request) {
        $bpId = $request->input('bp_id');
        $retailId = $request->input('retailer_id');
        $productId = $request->input('product_id');
        $dealerCode = $request->input('dealer_code');

        $sellerName = "";
        if ($bpId > 0) { 
            $sellerName = BrandPromoter::where('status','=',1)->where('id','=',$bpId)->value('bp_name');
        } 

        if ($retailId > 0) {
            $sellerName = Retailer::where('status','=',1)->where('id','=',$retailId)->value('retailer_name');
        }

        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        if ($request->ajax()) {
            $sort_by = $request->get('sortby');
            $sort_type = $request->get('sorttype');
            $query = $request->get('query');
            $query = str_replace(" ", "%", $query);
            $searchVal = str_replace(" ", "%", $query);

            $saleList = DB::table('view_sales_reports')
                ->whereIn('status',[1,2])
                ->whereBetween(\DB::raw("DATE_FORMAT(sale_date, '%Y-%m-%d')"),[$month_Sdate,$month_Edate])
                ->where(function($sql_query) use($searchVal){
                    $sql_query->where('customer_name','like', '%'.$searchVal.'%')
                        ->orWhere('customer_phone','like', '%'.$searchVal.'%')
                        ->orWhere('ime_number', 'like', '%'.$searchVal.'%')
                        ->orWhere('alternate_imei','like', '%'.$searchVal.'%')
                        ->orWhere('product_model', 'like', '%'.$searchVal.'%')
                        ->orWhere('dealer_name','like', '%'.$searchVal.'%')
                        ->orWhere('dealer_code','like', '%'.$searchVal.'%')
                        ->orWhere('retailer_name','like', '%'.$searchVal.'%')
                        ->orWhere('retailer_phone_number','like', '%'.$searchVal.'%')
                        ->orWhere('bp_name','like', '%'.$searchVal.'%')
                        ->orWhere('bp_phone','like', '%'.$searchVal.'%');
                })
                ->orderBy($sort_by, $sort_type)
                ->paginate(100);
            return view('admin.order.pending_sales_list_result_data', compact('saleList'))->render();
        } else {
            $saleList = DB::table('view_sales_reports')
                ->whereIn('status',[1,2])
                ->where(function($sql_query) use($bpId,$retailId,$productId,$dealerCode) {
                    if ($bpId > 0) {
                        $sql_query->where('bp_id', '=', $bpId);
                    }
                    if ($retailId > 0) {
                        $sql_query->where('retailer_id', '=', $retailId);
                    }
                    if ($productId > 0) {
                        $sql_query->where('product_master_id', '=', $productId);
                    }
                    if ($dealerCode > 0) {
                        $sql_query->where('dealer_code', '=', $dealerCode);
                        $sql_query->orWhere('alternate_code', '=', $dealerCode);
                    }                    
                })
                ->orderBy('id','desc')
                ->paginate(100);

            foreach($saleList as $sale) {
                $saleProductList = DB::table('sale_products')
                    ->select('*')
                    ->where('product_status','=',1)
                    ->where('sales_id',$sale->id)
                    ->get();
                $dealerInfo = DB::table('dealer_informations')
                    ->select('dealer_code as code','alternate_code as alternate_code','dealer_name as name','dealer_address as address','zone','dealer_phone_number as phone')
                    ->where('dealer_code',$sale->dealer_code)
                    ->orWhere('alternate_code',$sale->dealer_code)
                    ->first();
                foreach ($saleProductList as $saleProduct) {
                    $saleProduct->dealer_name = !empty($dealerInfo->name) ? $dealerInfo->name : "";
                    $saleProduct->dealer_phone = !empty($dealerInfo->phone) ? $dealerInfo->phone :"";
                    $saleProduct->dealer_code = !empty($dealerInfo->code) ? $dealerInfo->code : "";
                    $saleProduct->alternet_code = !empty($dealerInfo->alternate_code) ? $dealerInfo->alternate_code : "";
                }
                $sale->product_list = $saleProductList;
                $retailerInfo = DB::table('retailers')
                    ->select('retailer_name as name','retailder_address as address','phone_number as phone')
                    ->where('retailer_id',$sale->retailer_id)
                    ->first();

                $brandPromoterInfo = DB::table('brand_promoters')
                    ->select('bp_name as name','bp_phone as phone')
                    ->where('bp_id',$sale->bp_id)
                    ->first();

                $sale->retailer_info = $retailerInfo;
                $sale->bp_info = $brandPromoterInfo;
            }
        }

        if (isset($saleList) && $saleList->isNotEmpty()) {
            Log::info('Load Order Pending Sale List');
        } else {
            Log::warning('Order Pending Sales List Not Found');
        }
        return view('admin.order.pending_sales_list',compact('saleList','sellerName'));
    }
    
    public function pendingOrderStatusUpdate(Request $request) {
        $status = $request->input('status');
        $comments = $request->input('comments');
        $orderId = $request->input('pending_order_id');
        $salesInfo  = DB::table('view_sales_reports')->where('id','=',$orderId)->first();
        $productMasterId = $salesInfo->product_master_id;
        $groupId = 2; // 1=BP,2=Retailer
        if ($salesInfo->bp_id > 0 && $salesInfo->retailer_id > 0) {
            $groupId = 1;
        }
        $saleDate = date('Y-m-d',strtotime($salesInfo->sale_date));
        $imei1 = $salesInfo->ime_number ? $salesInfo->ime_number : '';
        $imei2 = $salesInfo->alternate_imei ? $salesInfo->alternate_imei : '';
        $saleId = $orderId ? $orderId : 0;
        $bpId = $salesInfo->bp_id ? $salesInfo->bp_id : 0;
        $retailId = $salesInfo->retailer_id ? $salesInfo->retailer_id : 0;
        $saleQty = $salesInfo->sale_qty ? $salesInfo->sale_qty : 0;

        $incentiveLists = DB::table('incentives')
            ->where('status','=',1)
            ->where('incentive_group','=',$groupId)
            ->where('end_date','>=',$saleDate)
            ->get();
        $responseStatus = 0;
        if ($orderId > 0) {
            if ($status == 2) {
                $changeOrderStatus = DB::table('sales')->where('id','=',$orderId)->update(["status"=>$status,"note"=>$comments]);    
                if ($status == 0) {
                    DB::table('sale_products')->where('sales_id','=',$orderId)->update(["product_status"=>$status,]);
                }
                $responseStatus = 1;
            } else {
                $salesImeiNumber = DB::table('sale_products')->where('sales_id','=',$orderId)->value('ime_number');                
                $getCurlResponse = getData(sprintf(RequestApiUrl("GetIMEIinfo"),$salesImeiNumber),"GET");
                $responseData = (array) json_decode($getCurlResponse['response_data'],true);

                if (isset($responseData) && !empty($responseData)) {
                    $ZoneId = 0;
                    if ($groupId == 1) {
                        $dealerZoneName = $responseData[0]['DealerZone'];
                        if (!empty($dealerZoneName)) {
                            $ZoneId = DB::table('zones')->where('zone_name','like','%'.$dealerZoneName.'%')->value('id');
                        }
                    } else {
                        $getZoneId = DB::table('retailers')->where('retailer_id',$retailId)->value('zone_id');
                        if ($getZoneId != null || !empty($getZoneId)) {
                            $ZoneId = $getZoneId;
                        }
                    }

                    $productStatus = ($responseData[0]['IsSoldOut'] == true) ? "0" : "1";
                    if ($productStatus == 1) {
                        $changeOrderStatus = DB::table('sales')->where('id','=',$orderId)->update([
                            "status"=>$status,
                            "note"=>$comments
                        ]);    
                        if ($status == 0) {
                            DB::table('sale_products')->where('sales_id','=',$orderId)->update(["product_status"=>$status,]);
                        }

                        if ($incentiveLists->isNotEmpty()) {
                            foreach ($incentiveLists as $incentive) {
                                $getModelId = json_decode($incentive->product_model,TRUE);
                                $getIncentiveType = json_decode($incentive->incentive_type,TRUE);
                                $getZone = json_decode($incentive->zone,TRUE);
                                $minQty = $incentive->min_qty;
                                $groupCatId = explode(',', $incentive->group_category_id);
                                $totalSaleQty = DB::table('view_sales_reports')
                                    ->where('product_master_id',$productMasterId)
                                    ->sum('view_sales_reports.sale_qty');
                                
                                if (in_array("all", $getModelId) || in_array($productMasterId, $getModelId)) {
                                    if (in_array("all", $getIncentiveType) || in_array($incentiveType, $getIncentiveType)) {
                                        if (in_array("all", $getZone) || in_array($ZoneId, $getZone)) {
                                            if ($totalSaleQty >= $minQty) {
                                                $getGroupCatId = "A";                                                
                                                DB::table('sale_incentives')->insert([
                                                    "group_category_id"=>($getGroupCatId) ? $getGroupCatId:$incentive->group_category_id,
                                                    "incentive_category"=>$incentive->incentive_category,
                                                    "ime_number"=>$imei1,
                                                    "alternate_imei"=>$imei2,
                                                    "sale_id" =>$saleId, 
                                                    "bp_id" =>$bpId,
                                                    "retailer_id"=>$retailId,
                                                    "incentive_title"=>$incentive->incentive_title,
                                                    "product_model"=>$responseData[0]['Model'],
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
                                            }
                                        }
                                    }
                                }                                
                            }
                        }                        
                        //Ime Database Product Status Update Start
                        $getCurlResponse = getData(sprintf(RequestApiUrl("UpdateIMEIStatus"),$salesImeiNumber),"GET");
                        $responseStatus = 1;
                    } else {
                        return response()->json('warning');
                    }
                }
            }
        }

        if ($responseStatus == 1) {
            return response()->json('success');
        }
        return response()->json('error');
    }

    public function bpSalesReportForm(Request $request) {
        Session::forget('salesBPId');
        Session::forget('salesSdate');
        Session::forget('salesEdate');
        Session::forget('orderBy');
        Session::put('orderBy','DESC');        
        
        $month_Sdate = date('Y-m-01');
        $month_Edate = date('Y-m-t');
        
        if ($request->ajax()) {
            $sort_by = $request->get('sortby');
            $sort_type = $request->get('sorttype');
            $query = $request->get('query');
            $query = str_replace(" ", "%", $query);

            $bpSalesList = DB::table('view_sales_reports')
                ->select('bp_id','bp_name','bp_phone','photo','retailer_name','retailer_phone_number','dealer_name','dealer_code','alternate_code','dealer_phone_number',DB::raw("SUM(sale_qty) as total_qty"), DB::raw("SUM(msrp_price) as total_sale_amount"))
                ->where('status','=',0)
                ->whereBetween(DB::raw("DATE_FORMAT(sale_date,'%Y-%m-%d')"),[$month_Sdate,$month_Edate])
                ->where(function($sql_query) use($query) {
                    if ($query !=null || !empty($query)) {
                        $sql_query->where('bp_name','like', '%'.$query.'%')
                            ->orWhere('bp_phone','like', '%'.$query.'%')
                            ->orWhere('retailer_name','like', '%'.$query.'%')
                            ->orWhere('retailer_phone_number','like','%'.$query.'%')
                            ->orWhere('dealer_name','like', '%'.$query.'%')
                            ->orWhere('dealer_phone_number','like', '%'.$query.'%')
                            ->orWhere('dealer_code','like', '%'.$query.'%')
                            ->orWhere('sale_qty','like','%'.$query.'%')
                            ->orWhere('msrp_price','like','%'.$query.'%');
    				}  
                })
                ->orderBy($sort_by, $sort_type)
                ->groupBy('bp_id')
                ->paginate(100);
            return view('admin.report.bp_result_data', compact('bpSalesList'))->render();
        } else {
            $bpSalesList = DB::table('view_sales_reports')
                ->select('bp_id','bp_name','bp_phone','photo','retailer_name','retailer_phone_number','dealer_name','dealer_code','alternate_code','dealer_phone_number',DB::raw("SUM(sale_qty) as total_qty"), DB::raw("SUM(msrp_price) as total_sale_amount"))
                ->where('status','=',0)
                ->whereBetween(DB::raw("DATE_FORMAT(sale_date,'%Y-%m-%d')"),[$month_Sdate,$month_Edate])
                ->orderBy('total_sale_amount','DESC')
                ->groupBy('bp_id')
                ->paginate(100);
        }

        if (isset($bpSalesList) && $bpSalesList->isNotEmpty()) {
            Log::info('Load Bp Sales List');
        } else {
            Log::warning('Bp Sales List Not Found');
        }
        return view('admin.report.bp_sales_report',compact('bpSalesList'));
    }
    
    public function bpDateRangesalesReport(Request $request) {
        $salesBPId  = $request->input('bp_id');
        $salesSdate = $request->input('start_date');
        $salesEdate = $request->input('end_date');
        $orderBy = $request->input('order_by');
        Session::put('salesBPId',$salesBPId);
        Session::put('salesSdate',$salesSdate);
        Session::put('salesEdate',$salesEdate);
        Session::put('orderBy',$orderBy);        
        $ordBy = "DESC";
        if (isset($ordBy) && !empty($ordBy)) {
            $ordBy = $request->input('order_by');
        }

        $bpId = 0;
        $sellerName = "";
        if ($request->input('bp_id')) {
            $bpId = $request->input('bp_id');
            $sellerName = BrandPromoter::where('status','=',1)->where('id','=',$bpId)->value('bp_name');
        }

        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $currentSdate = date('Y-m-d');
        $before3MonthSdate = date('Y-m-d',strtotime("-3 months",strtotime($currentSdate)));
        $reqSdate = "";
        $reqEdate = "";

        if (!empty($start_date) && strtotime($start_date) >= strtotime($before3MonthSdate) && strtotime($start_date) <= strtotime($currentSdate)) {
            $reqSdate = $start_date;
        } else {
            $reqSdate = Session::get('salesSdate') ? Session::get('salesSdate'):$before3MonthSdate;
        }

        if (!empty($end_date) && strtotime($end_date) >= strtotime($before3MonthSdate) && strtotime($end_date) <= strtotime($currentSdate)) {
            $reqEdate = $end_date;
        } else {
            $reqEdate = Session::get('salesEdate') ? Session::get('salesEdate'):$currentSdate;
        }        
        
        $formDate = Session::get('salesSdate') ? Session::get('salesSdate') : date('Y-m-01');
        $toDate = Session::get('salesEdate') ? Session::get('salesEdate') : date('Y-m-t');
        $bpSalesList = "";

        if ($request->ajax()) {
            $sort_by = $request->get('sortby');
            $sort_type = $request->get('sorttype');
            $query = $request->get('query');
            $query = str_replace(" ", "%", $query);

            $bpSalesList = DB::table('view_sales_reports')
                ->select('bp_id','bp_name','bp_phone','retailer_name','retailer_phone_number','dealer_name','dealer_code','alternate_code','dealer_phone_number',DB::raw("SUM(sale_qty) as total_qty"), DB::raw("SUM(msrp_price) as total_sale_amount"))
                ->whereBetween(DB::raw("DATE_FORMAT(sale_date,'%Y-%m-%d')"),[$formDate,$toDate])
                ->where('status','=',0)
                ->where(function($sql_query) use($query) {
                    if ($query !=null || !empty($query)) {
                        $sql_query->where('bp_name','like', '%'.$query.'%')
                            ->orWhere('bp_phone','=', $query)
                            ->orWhere('retailer_name','like', '%'.$query.'%')
                            ->orWhere('retailer_phone_number','=',$query)
                            ->orWhere('dealer_name','like', '%'.$query.'%')
                            ->orWhere('dealer_code','=',$query)
                            ->orWhere('sale_qty','=',$query)
                            ->orWhere('msrp_price','=',$query);
    				}  
                })
                ->orderBy($sort_by, $sort_type)
                ->groupBy('bp_id')
                ->paginate(100);
            
            return view('admin.report.bp_result_data', compact('bpSalesList','sellerName'))->render();
        } else if (!empty($request->except('_token'))) {
            $bpSalesList = DB::table('view_sales_reports')
                ->select('bp_id','bp_name','bp_phone','retailer_name','retailer_phone_number','dealer_name','dealer_code','alternate_code','dealer_phone_number',DB::raw("SUM(sale_qty) as total_qty"), DB::raw("SUM(msrp_price) as total_sale_amount"))
                ->where('status','=',0)
                ->whereBetween(DB::raw("DATE_FORMAT(sale_date,'%Y-%m-%d')"),[$formDate,$toDate])
                ->where(function($query) use($salesBPId){
                    if ($salesBPId !=null || !empty($salesBPId)) {
                        $query->where('bp_id','=',$salesBPId);
                    }
                })
                ->orderBy('total_sale_amount',$ordBy)
                ->groupBy('bp_id')
                ->paginate(100);
        }
        if ($bpSalesList) {
            return view('admin.report.bp_sales_report',compact('bpSalesList','sellerName'))->with('success','Sales Data Found');
        }
        return view('admin.report.bp_sales_report',compact('bpSalesList','sellerName'))->with('error','Sales Data Not Found');
    }

    public function BpOrderDetailsView($bpId) {
        $formDate = Session::get('salesSdate') ? Session::get('salesSdate') : date('Y-m-01');
        $toDate = Session::get('salesEdate') ? Session::get('salesEdate') : date('Y-m-t');

        $salesInfo = DB::table('view_sales_reports')->where('bp_id',$bpId)->first();
        $saleProductList = DB::table('view_sales_reports')
            ->select('sale_date','product_type','product_model','product_color','msrp_price',DB::raw("SUM(sale_qty) as total_qty"), DB::raw("SUM(msrp_price) as total_sale_amount"))
            ->where('bp_id','=',$bpId)
            ->where('status','=',0)
            ->whereBetween(DB::raw("DATE_FORMAT(sale_date,'%Y-%m-%d')"),[$formDate,$toDate])
            ->groupBy('product_model')
            ->groupBy('product_color')
            ->get();        
        $viewItemList = '';
        $viewItemList = View('admin.report.bp_item_list',compact('saleProductList','salesInfo'))->render();

        if ($viewItemList) {
            Log::info('Load BP Sales Info');
            return response()->json(['itemList'=>$viewItemList]);
        } else {
            Log::warning('BP Sales Info Not Found');
            return response()->json('error');
        }
    }
    
    public function focus_model_to_bp_employee_stock_check(Request $request){
        $clientType = "emp";
        $empIdList = Employee::select('employee_id')
            ->where('status',1)
            ->whereNotNull('email')
            ->whereNotNull('employee_id')
            ->get();

        $ProductInfo = [];
        if (isset($empIdList) && $empIdList->isNotEmpty())  {
            foreach ($empIdList as $erow) {
                $empId = $erow->employee_id;
                $getCurlResponse = getData(sprintf(RequestApiUrl("GetStock"),$empId,$clientType),"GET");
                $responseData = json_decode($getCurlResponse['response_data'],true);                
                if (isset($responseData) && !empty($responseData)) {
                    foreach ($responseData as $key=>$row) {
                        $getStockInfo = checkBPFocusModelStock($row['Model']);
                        if (isset($getStockInfo) && !empty($getStockInfo)) {
                            if ($getStockInfo->green != null && $getStockInfo->yellow != null && $getStockInfo->red != null ) {
                                if ($row['StockQuantity'] >= $getStockInfo->yellow && $row['StockQuantity'] < $getStockInfo->green) {
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
                                } else if ($row['StockQuantity'] < $getStockInfo->yellow && $row['StockQuantity'] >= $getStockInfo->red) {
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
                                }
                            } else {
                                if ($row['StockQuantity'] >= 1 && $row['StockQuantity'] < 2) {
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
                                } else if ($row['StockQuantity'] < 1 && $row['StockQuantity'] >= 0) {
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
                                }
                            }
                        } else {
                            if ($row['StockQuantity'] >= 1 && $row['StockQuantity'] < 2) {
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
                            } else if ($row['StockQuantity'] < 1 && $row['StockQuantity'] >= 0) {
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
                            }
                        }
                    }
                }                
            }
        }

        if (isset($ProductInfo) && !empty($ProductInfo)) {
            foreach ($ProductInfo as $k=>$rowDataList) {
                $getEmail = Employee::where('employee_id',$k)->first();
                $sendEmail = $getEmail['email'];
                Mail::send('admin.mail_confirmation.stock_alert_mail', ['rowDataList' => $rowDataList,'getEmail' => $getEmail], function($message) use ($rowDataList,$getEmail) {
                    $message->to($getEmail['email']);
                    $message->subject('Stock Update');
                    $message->from('info@example.com','Syngenta Retail Management System');
                });
            }
        }
    }

    public function employeeStockCheck(Request $request) {
        $clientType = "emp";
        $empIdList = Employee::select('employee_id')->where('status',1)->whereNotNull('email')->whereNotNull('employee_id')->get();
        $ProductInfo = [];
        if (isset($empIdList) && $empIdList->isNotEmpty()) {
            foreach ($empIdList as $erow) {
                $empId = $erow->employee_id;
                $getCurlResponse = getData(sprintf(RequestApiUrl("GetStock"),$empId,$clientType),"GET");
                $responseData = json_decode($getCurlResponse['response_data'],true);

                if (isset($responseData) && !empty($responseData)) {
                    foreach ($responseData as $key=>$row) {
                        $getStockInfo = checkModelStock($row['Model']);
                        if (isset($getStockInfo) && !empty($getStockInfo)) {
                            if ($getStockInfo->default_qty != null && $getStockInfo->yeallow_qty != null && $getStockInfo->red_qty != null ) {
                                if ($row['StockQuantity'] >= $getStockInfo->yeallow_qty && $row['StockQuantity'] < $getStockInfo->default_qty) {
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
                                } else if ($row['StockQuantity'] < $getStockInfo->yeallow_qty && $row['StockQuantity'] >= $getStockInfo->red_qty) {
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
                                }
                            } else {
                                if ($row['StockQuantity'] >= 1 && $row['StockQuantity'] < 2) {
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
                                } else if ($row['StockQuantity'] < 1 && $row['StockQuantity'] >= 0) {
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
                                }
                            }
                        } else {
                            if ($row['StockQuantity'] >= 1 && $row['StockQuantity'] < 2) {
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
                            } else if ($row['StockQuantity'] < 1 && $row['StockQuantity'] >= 0) {
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
                            }
                        }
                    }
                }                
            }
        }

        if (isset($ProductInfo) && !empty($ProductInfo)) {
            foreach ($ProductInfo as $k=>$rowDataList) {
                $getEmail = Employee::where('employee_id',$k)->first();
                $sendEmail = $getEmail['email'];
                Mail::send('admin.mail_confirmation.stock_alert_mail', ['rowDataList' => $rowDataList,'getEmail' => $getEmail], function($message) use ($rowDataList,$getEmail) {
                    //$message->to('sayed.giantssoft@gmail.com');
                    $message->to($getEmail['email']);
                    $message->subject('Stock Update');
                    $message->from('info@example.com','Syngenta Retail Management System');
                });
            }
        }
    }

    public function stockReportSendMail($sendEmail,$rowDataList) {
        Mail::send('admin.mail_confirmation.stock_alert_mail', ['rowDataList' => $rowDataList], function($message) use ($rowDataList) {
            $message->to($sendEmail);
            $message->subject('Stock Update');
            $message->from('info@exmaple.com','Syngenta Retail Management System');
        });
    }
    
    public function productModelSearch(Request $request) {
        $search = $request->search;
        $productModelList = "";
        if ($search == '') {
            $productModelList = Products::groupBy('product_model')->get(['product_master_id','product_model']);
        } else {
            $productModelList = Products::orderby('product_model','ASC')
                ->select('product_master_id','product_model')
                ->where('product_model','like','%'.$search.'%')
                ->groupBy('product_model')
                ->get();
        }

        $response = array();
        foreach($productModelList as $row) {
            $label = $row->product_model;
            $response[] = array("value"=>$row->product_master_id,"label"=>$label);
        }
        return response()->json($response);
    }

    public function dealerSearch(Request $request) {
        $search = $request->search;
        $getList = "";
        if ($search == '') {
            $getList = DB::table('dealer_informations')->get();
        } else {
            $getList = DB::table('dealer_informations')
                ->where('dealer_name', 'like', '%' .$search . '%')
                ->orWhere('dealer_phone_number','like', '%' .$search . '%')
                ->orWhere('dealer_code','like', '%' .$search. '%')
                ->orWhere('alternate_code','like', '%' .$search. '%')
                ->orWhere('zone','like', '%' .$search. '%')
                ->get();
        }

        $response = array();
        foreach ($getList as $row) {
            $label = $row->dealer_name."( Mo:".$row->dealer_phone_number.")";
            $response[] = array("value"=>$row->dealer_code,"label"=>$label);
        }
        return response()->json($response);
    }

    public function getImeiDisputeReportList(Request $request) {
        Session::forget('search_bpid');
        Session::forget('search_retailerid');
        Session::forget('search_sdate');
        Session::forget('search_edate');
        Session::forget('search_dealerid');
        Session::forget('search_imei');
        
        $month_Sdate = date('Y-m-01');
        $month_Edate = date('Y-m-t');
        $imeiDisputeList = "";

        if ($request->ajax()) {
            $sort_by = $request->get('sortby');
            $sort_type = $request->get('sorttype');
            $query = $request->get('query');
            $query = str_replace(" ", "%", $query);
            $searchVal = str_replace(" ", "%", $query);

            $imeiDisputeList = DB::table('view_imei_dispute_list')
                ->whereBetween(\DB::raw("DATE_FORMAT(date, '%Y-%m-%d')"),[$month_Sdate,$month_Edate])
                ->where(function($sql_query) use($searchVal) {
                    if ($searchVal !=null || !empty($searchVal)) {
                        $sql_query->where('bp_name','like', '%'.$searchVal.'%')
                            ->orWhere('bp_phone','like', '%'.$searchVal.'%')
                            ->orWhere('retailer_name', 'like', '%'.$searchVal.'%')
                            ->orWhere('retailer_phone','like', '%'.$searchVal.'%')
                            ->orWhere('dealer_name','like', '%'.$searchVal.'%')
                            ->orWhere('dealer_phone_number', 'like', '%'.$searchVal.'%')
                            ->orWhere('distributor_code','like', '%'.$searchVal.'%')
                            ->orWhere('distributor_code2','like', '%'.$searchVal.'%')
                            ->orWhere('imei_number','like', '%'.$searchVal.'%')
                            ->orWhere('description', 'like', '%'.$searchVal.'%')
                            ->orWhere('comments', 'like', '%'.$searchVal.'%');
                    }
                })
                ->orderBy($sort_by, $sort_type)
                ->paginate(100);
            return view('admin.report.imei_dispute_report_result_list', compact('imeiDisputeList'))->render();
        } else {
            $imeiDisputeList = DB::table('view_imei_dispute_list')
                ->whereBetween(\DB::raw("DATE_FORMAT(date, '%Y-%m-%d')"),[$month_Sdate,$month_Edate])
                ->paginate(100);
        }

        if (isset($imeiDisputeList) && $imeiDisputeList->isNotEmpty()) {
            Log::info('Load IMEI Dispute List');
        } else {
            Log::warning('IMEI Dispute List Not Found');
        }
        return view('admin.report.imei_dispute_report_list',compact('imeiDisputeList'));
    }

    public function searchImeiDisputeReportList(Request $request) {
        $getbpId = $request->input('bp_id');
        $getretailId = $request->input('retailer_id');
        $getimeiNumber = $request->input('imei_number');
        $getdealerCode = $request->input('dealer_code');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        Session::put('search_bpid',$getbpId);
        Session::put('search_retailerid',$getretailId);
        Session::put('search_sdate',$start_date);
        Session::put('search_edate',$end_date);
        Session::put('search_dealerid',$getdealerCode);
        Session::put('search_imei',$getimeiNumber);

        $bpId = Session::get('search_bpid') ? Session::get('search_bpid') : 0;
        $retailId = Session::get('search_retailerid') ? Session::get('search_retailerid') : 0;
        $imeiNumber = Session::get('search_imei') ? Session::get('search_imei') : 0;
        $dealerCode = Session::get('search_dealerid') ? Session::get('search_dealerid') : 0;
        $month_Sdate = Session::get('search_sdate') ? Session::get('search_sdate') : date('Y-m-01');
        $month_Edate = Session::get('search_edate') ? Session::get('search_edate') : date('Y-m-t');

        if ($request->ajax()) {
            $sort_by = $request->get('sortby');
            $sort_type = $request->get('sorttype');
            $query = $request->get('query');
            $query = str_replace(" ", "%", $query);
            $searchVal = str_replace(" ", "%", $query);

            $imeiDisputeList = DB::table('view_imei_dispute_list')
                ->whereBetween(\DB::raw("DATE_FORMAT(date, '%Y-%m-%d')"),[$month_Sdate,$month_Edate])
                ->where(function($sql_query) use($searchVal,$bpId,$retailId,$imeiNumber,$dealerCode) {
                    if ($searchVal != null || !empty($searchVal)) {
                        $sql_query->where('bp_name','like', '%'.$searchVal.'%')
                            ->orWhere('bp_phone','like', '%'.$searchVal.'%')
                            ->orWhere('retailer_name', 'like', '%'.$searchVal.'%')
                            ->orWhere('retailer_phone','like', '%'.$searchVal.'%')
                            ->orWhere('dealer_name','like', '%'.$searchVal.'%')
                            ->orWhere('dealer_phone_number', 'like', '%'.$searchVal.'%')
                            ->orWhere('distributor_code','like', '%'.$searchVal.'%')
                            ->orWhere('distributor_code2','like', '%'.$searchVal.'%')
                            ->orWhere('imei_number','like', '%'.$searchVal.'%')
                            ->orWhere('description', 'like', '%'.$searchVal.'%')
                            ->orWhere('comments', 'like', '%'.$searchVal.'%');
                    }
                    if ($bpId != null || !empty($bpId) || $bpId > 0) {
                        $sql_query->where('bp_id', '=', $bpId);
                    }
                    if ($retailId != null || !empty($retailId) || $retailId > 0) {
                        $sql_query->where('retailer_id', '=', $retailId);
                    }
                    if ($imeiNumber != null || !empty($imeiNumber) || $imeiNumber > 0) {
                        $sql_query->where('imei_number', '=', $imeiNumber);
                    }
                    if ($dealerCode != null || !empty($dealerCode) || $dealerCode > 0) {
                        $sql_query->where('distributor_code', '=', $dealerCode);
                        $sql_query->orWhere('distributor_code2', '=', $dealerCode);
                    }
                })
                ->orderBy($sort_by, $sort_type)
                ->paginate(100);

            return view('admin.report.imei_dispute_report_result_list', compact('imeiDisputeList'))->render();
        } else {
            $imeiDisputeList = DB::table('view_imei_dispute_list')
                ->whereBetween(\DB::raw("DATE_FORMAT(date, '%Y-%m-%d')"),[$month_Sdate,$month_Edate])
                ->where(function($sql_query) use($bpId,$retailId,$imeiNumber,$dealerCode) {
                    if ($bpId !=null || !empty($bpId) || $bpId > 0) {
                        $sql_query->where('bp_id', '=', $bpId);
                    }
                    if ($retailId !=null || !empty($retailId) || $retailId > 0) {
                        $sql_query->where('retailer_id', '=', $retailId);
                    }
                    if ($imeiNumber !=null || !empty($imeiNumber) || $imeiNumber > 0) {
                        $sql_query->where('imei_number', '=', $imeiNumber);
                    }
                    if ($dealerCode !=null || !empty($dealerCode) || $dealerCode > 0) {
                        $sql_query->where('distributor_code', '=', $dealerCode);
                        $sql_query->orWhere('distributor_code2', '=', $dealerCode);
                    }
                })
                ->orderBy('id','desc')
                ->paginate(100);
        }
        return view('admin.report.imei_dispute_report_list',compact('imeiDisputeList'));
    }
    
    public function getRetailerSearch(Request $request) {
        $search = $request->search;
        $retailList = "";
        if ($search == '')  {
            $retailList = Retailer::orderby('retailer_name','asc')
                ->select('id','retailer_name','phone_number','retailder_address')
                ->get();
        } else {
            $retailList = Retailer::orderby('retailer_name','asc')
                ->select('id','retailer_name','phone_number','retailder_address')
                ->where('retailer_name', 'like', '%' .$search . '%')
                ->orWhere('phone_number','like', '%' .$search . '%')
                ->get();
        }

        $dataArray = array();
        foreach ($retailList as $row) {
            $dataArray[] = '<option value="'.$row->id.'">'.$row->retailer_name.'-'.$row->phone_number.'-'.$row->retailder_address.'</option>';
        }
        return response()->json($dataArray);
    }
    
    public function getModelPrice($productId) {
        $productPrice = DB::table('view_product_master')->where('product_master_id','=',$productId)->value('msrp_price');

        if ($productPrice > 0) {
            return response()->json($productPrice);
        } else {
            return response()->json(0);
        }        
    }
    
    public function getSalesReturn(Request $request) {
        Session::forget('salesReturnBPId');
        Session::forget('salesReturnRetailerId');
        Session::forget('salesReturnSdate');
        Session::forget('salesReturnEdate');    	
    	$bpList = BrandPromoter::get(['bp_id','bp_name']);
    	$retailerList = Retailer::get(['retailer_id','retailer_name']);
        $month_Sdate =  date('Y-m-01');
        $month_Edate =  date('Y-m-t');
        $saleList = "";

        if ($request->ajax()) {
            $sort_by = $request->get('sortby');
            $sort_type = $request->get('sorttype');
            $query = $request->get('query');
            $query = str_replace(" ", "%", $query);

            $saleList = DB::table('view_sales_reports')
                ->whereBetween(\DB::raw("DATE_FORMAT(sale_date, '%Y-%m-%d')"),[$month_Sdate,$month_Edate])
                ->where('status','=',3)
                ->where(function($sql_query) use($query) {
                    if ($query !=null || !empty($query)) {
                        $sql_query->where('bp_name','like', '%'.$query.'%')
                            ->orWhere('bp_phone','like', '%'.$query.'%')
                            ->orWhere('retailer_name','like', '%'.$query.'%')
                            ->orWhere('retailer_phone_number','like','%'.$query.'%')
                            ->orWhere('dealer_name','like', '%'.$query.'%')
                            ->orWhere('dealer_phone_number','like', '%'.$query.'%')
                            ->orWhere('dealer_code','like','%'.$query.'%')
                            ->orWhere('customer_name','like', '%'.$query.'%')
                            ->orWhere('customer_phone','like', '%'.$query.'%');
                    }  
                })
                ->orderBy($sort_by, $sort_type)
                ->groupBy('id')
                ->paginate(100);

            return view('admin.report.sales_return_result_data', compact('saleList'))->render();
        } else {
            $saleList = DB::table('view_sales_reports')
                ->where('status','=',3)
                ->whereBetween(DB::raw("DATE_FORMAT(sale_date,'%Y-%m-%d')"),[$month_Sdate,$month_Edate])
                ->orderBy('id','DESC')
                ->paginate(100);

            foreach ($saleList as $sale) {
                $saleProductList = DB::table('sale_products')->select('*')->where('sales_id',$sale->id)->get();
                $dealerInfo = DB::table('dealer_informations')
                    ->select('dealer_code as code','alternate_code as alternate_code','dealer_name as name','dealer_address as address','zone','dealer_phone_number as phone')
                    ->where('dealer_code',$sale->dealer_code)
                    ->orWhere('alternate_code',$sale->dealer_code)
                    ->first();

                foreach ($saleProductList as $saleProduct) {
                    $saleProduct->dealer_name = !empty($dealerInfo->name) ? $dealerInfo->name:"";
                    $saleProduct->dealer_phone = !empty($dealerInfo->phone) ? $dealerInfo->phone :"";
                    $saleProduct->dealer_code = !empty($dealerInfo->code) ? $dealerInfo->code : "";
                    $saleProduct->alternet_code = !empty($dealerInfo->alternate_code) ? $dealerInfo->alternate_code : "";
                }
                $sale->product_list = $saleProductList;

                $retailerInfo = DB::table('retailers')
                    ->select('retailer_name as name','retailder_address as address','phone_number as phone')
                    ->where('retailer_id',$sale->retailer_id)
                    ->first();
                $brandPromoterInfo = DB::table('brand_promoters')
                    ->select('bp_name as name','bp_phone as phone')
                    ->where('bp_id',$sale->bp_id)
                    ->first();
                $sale->retailer_info = $retailerInfo;
                $sale->bp_info = $brandPromoterInfo;
            }
        }

        if (isset($saleList) && $saleList->isNotEmpty()) {
            Log::info('Load Product Sale List');
        } else {
            Log::warning('Product Sale List Not Found');
        }
    	return view('admin.report.sales_return_report',compact('saleList'));
    }
    
    public function dateRangeReturnSalesReport(Request $request) {
        $getBPId = $request->input('bp_id');
        $getRetailerId = $request->input('retailer_id');
        $salesSdate = $request->input('start_date');
        $salesEdate = $request->input('end_date');

        Session::put('salesReturnBPId',$getBPId);
        Session::put('salesReturnRetailerId',$getRetailerId);
        Session::put('salesReturnSdate',$salesSdate);
        Session::put('salesReturnEdate',$salesEdate);

        $salesBPId = Session::get('salesReturnBPId');
        $salesRetailerId = Session::get('salesReturnRetailerId');
        
        $sellerName = "";
        if ($salesBPId !=null || !empty($salesBPId)) {
            $sellerName = BrandPromoter::where('status','=',1)->where('id','=',$salesBPId)->value('bp_name');
        } else if ($salesRetailerId !=null || !empty($salesRetailerId)) {
            $sellerName = Retailer::where('status','=',1)->where('id','=',$salesRetailerId)->value('retailer_name');
        }

        $startDate = Session::get('salesReturnSdate') ? Session::get('salesReturnSdate'):date('Y-m-01');
        $endDate = Session::get('salesReturnEdate') ? Session::get('salesReturnEdate'):date('Y-m-t');
        
        if ($request->ajax()) {
            $sort_by = $request->get('sortby');
            $sort_type = $request->get('sorttype');
            $query = $request->get('query');
            $query = str_replace(" ", "%", $query);

            $saleList = DB::table('view_sales_reports')
                ->where('status','=',3)
                ->whereBetween(DB::raw("DATE_FORMAT(sale_date,'%Y-%m-%d')"),[$startDate,$endDate])
                ->where(function($sql_query) use($query,$salesBPId,$salesRetailerId) {
                    if ($query !=null || !empty($query)) {
                        $sql_query->where('bp_name','like', '%'.$query.'%')
                            ->orWhere('bp_phone','like', '%'.$query.'%')
                            ->orWhere('retailer_name','like', '%'.$query.'%')
                            ->orWhere('retailer_phone_number','like','%'.$query.'%')
                            ->orWhere('dealer_name','like', '%'.$query.'%')
                            ->orWhere('dealer_phone_number','like', '%'.$query.'%')
                            ->orWhere('dealer_code','like','%'.$query.'%')
                            ->orWhere('customer_name','like', '%'.$query.'%')
                            ->orWhere('customer_phone','like', '%'.$query.'%');
                    } 
                    if ($salesBPId > 0) {
                        $query->where('bp_id','=',$salesBPId);
                    } 
                    if ($salesRetailerId > 0) {
                        $query->where('retailer_id','=',$salesRetailerId);
                    }
                })
                ->orderBy($sort_by, $sort_type)
                ->paginate(100);
        
            return view('admin.report.sales_report_result_data', compact('saleList'))->render();
        } else {
            $saleList = DB::table('view_sales_reports')
                ->whereBetween(\DB::raw("DATE_FORMAT(sale_date, '%Y-%m-%d')"),[$startDate,$endDate])
                ->where('status','=',3)
                ->where(function($query) use($salesBPId,$salesRetailerId){
                    if ($salesBPId > 0) {
                        $query->where('bp_id',$salesBPId);
                    } 
                    if ($salesRetailerId > 0) {
                        $query->where('retailer_id',$salesRetailerId);
                    }
                })
                ->paginate(100);
            
    		foreach ($saleList as $sale) {
                $saleProductList = DB::table('sale_products')
                    ->select('*')
                    ->where('bp_id',$salesBPId)
                    ->where('retailer_id',$salesRetailerId)
                    ->where('sales_id',$sale->id)
                    ->get();    
                $dealerInfo = DB::table('dealer_informations')
                    ->select('dealer_code as code','alternate_code as alternate_code','dealer_name as name','dealer_address as address','zone','dealer_phone_number as phone')
                    ->where('dealer_code',$sale->dealer_code)
                    ->orWhere('alternate_code',$sale->dealer_code)
                    ->first();
                $sale->product_list = $saleProductList;
                $retailerInfo = "";

                if ($sale->retailer_id) {
                    $retailerInfo = DB::table('retailers')
                        ->select('retailer_name as name','retailder_address as address','phone_number as phone')
                        ->where('retailer_id',$sale->retailer_id)
                        ->first();
                }    
                $brandPromoterInfo = "";
                if (isset($bpId) && $bpId > 0) {
                    $brandPromoterInfo = DB::table('brand_promoters')
                        ->select('bp_name as name','bp_phone as phone')
                        ->where('bp_id',$bpId)
                        ->first();
                }
            }

            if (count($saleList) > 0) {
                return view('admin.report.sales_return_report',compact('saleList','retailerInfo','brandPromoterInfo','sellerName'))->with('success','Sales Data Found');
            } else {
                Log::warning(' Date Range Sales List Data Not Found');
                //return redirect()->action([ReportController::class, 'getSalesReturn'])->with('error','Data Not Found.Please Try Again');
                return view('admin.report.sales_return_report',compact('saleList'));
            }
        }
    }
    
    //Use For Corn Job Pending Save Sale For Syngenta
    public function getPendingOrderFeeds() {
        $orderFeeds = DB::table('sales')->select('id','request_data')->where('walton_status','=',1)->get();

        foreach ($orderFeeds as $row) {
            $getOrderFeeds = $row->request_data;
            $getSaleId = $row->id;
            $getPostCurlResponse = postData(sprintf(RequestApiUrl("SaveSale")),$getOrderFeeds);

            if (!empty($getPostCurlResponse) && $getPostCurlResponse == 'success') {
                DB::table('sales')->where('id','=',$getSaleId)->update(['walton_status'=>1]);
            }
        }
    }
    
    public function pending_order_feed_update() {
        $baseUrl = URL::to('');
        $searchDate = "2022-04-19";
        $orderLists = DB::table('sales')
            ->where(\DB::raw("DATE_FORMAT(sale_date,'%Y-%m-%d')"),$searchDate)
            ->where('walton_status','=',1)
            ->get();
        $totalFeed = 0;

        if ($orderLists->isNotEmpty()) {
            $request_sales_data = "";
            foreach($orderLists as $row) {
                $saleId = $row->id;
                $request_sales_data = [
                    "invoice_number"=>$row->invoice_number,
                    "sales_id"=>$row->id,
                    "customer_name"=>$row->customer_name,
                    "customer_phone"=>$row->customer_phone,
                    "bp_id"=> $row->bp_id,
                    "retailer_id"=> $row->retailer_id,
                    "dealer_code"=> $row->dealer_code,
                    "sale_date"=>$row->sale_date,
                    "photo"=>$baseUrl."/public/upload/client/".$row->photo,
                    "status"=>$row->status,
                    "order_type"=>($row->order_type == 1)?"Online":"Offline"
                ];

                $orderItemLists = DB::table('sale_products')->where('sales_id','=',$row->id)->get();

                if ($orderItemLists->isNotEmpty())  {
                    foreach ($orderItemLists as $items) {
                        $request_sales_data['itemLists'][] = [
                            "sales_id"=>$items->sales_id,
                            "ime_number"=>$items->ime_number,
                            "alternate_imei"=>$items->alternate_imei,
                            "product_master_id"=>$items->product_master_id,
                            "dealer_code"=>$items->dealer_code,
                            "product_id"=>$items->product_id,
                            "product_code"=>$items->product_code,
                            "product_type"=>$items->product_type,
                            "product_model"=>$items->product_model,
                            "product_color"=>$items->product_color,
                            "category"=>$items->category,
                            "mrp_price"=>$items->mrp_price,
                            "msdp_price"=>$items->msdp_price,
                            "msrp_price"=>$items->msrp_price,
                            "sale_price"=>$items->msrp_price,
                            "sale_qty"=>$items->sale_qty,
                            "bp_id"=>$items->bp_id,
                            "retailer_id"=>$items->retailer_id,
                            "product_status"=>$items->product_status
                        ];
                    }
                }
                
                DB::table('sales')->where('id','=',$saleId)->update(["request_data"=>$request_sales_data,]);
                $getPostCurlResponse = postData(sprintf(RequestApiUrl("SaveSale")),$request_sales_data);

                if (!empty($getPostCurlResponse) && $getPostCurlResponse == 'success') {
                    DB::table('sales')->where('id','=',$saleId)->update([
                        'walton_status'=>0,
                        "request_data"=>$request_sales_data,
                    ]);
                } else {
                    DB::table('sales')->where('id','=',$saleId)->update([
                        "request_data"=>$request_sales_data,
                    ]);
                }
            }
        }
    }
    
    public function privacy_info() {
		return view('admin.privacy-info');
	}
}
