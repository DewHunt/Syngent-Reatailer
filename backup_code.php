<?php


    public function salesReportForm(Request $request) {
    	Session::forget('salesBPId');
        Session::forget('salesRetailerId');
        Session::forget('salesSdate');
        Session::forget('salesEdate');
    	
    	$bpList = BrandPromoter::get(['bp_id','bp_name']);
    	$retailerList = Retailer::get(['retailer_id','retailer_name']);        
        $month_Sdate = date('Y-m-01');
        $month_Edate = date('Y-m-t');
        $saleList = "";

        if ($request->ajax()) {
            $sort_by = $request->get('sortby');
            $sort_type = $request->get('sorttype');
            $query = $request->get('query');
            $query = str_replace(" ", "%", $query);
            
            $saleList = DB::table('view_sales_reports')
                ->whereBetween(\DB::raw("DATE_FORMAT(sale_date, '%Y-%m-%d')"),[$month_Sdate,$month_Edate])
                ->where('status','=',0)
                ->where(function($sql_query) use($query){
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

            return view('admin.report.sales_report_result_data', compact('saleList'))->render();
        } else {
            $saleList = DB::table('view_sales_reports')
                ->where('status','=',0)
                ->whereBetween(DB::raw("DATE_FORMAT(sale_date,'%Y-%m-%d')"),[$month_Sdate,$month_Edate])
                ->orderBy('id','DESC')
                ->paginate(100);

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
    	return view('admin.report.sales_report',compact('saleList'));
    }
    
    public function OrderDetailsView(Request $request) {
        $saleId = $request->saleId;
        $salesInfo = DB::table('view_sales_reports')->where('id',$saleId)->first();
        $saleProductList = DB::table('view_sales_reports')->select('*')->where('id',$saleId)->get();
        $orderDetailsView = View('admin.report.order_detail_view',compact('salesInfo','saleProductList'))->render();
        if ($orderDetailsView) {
            Log::info('Load Sales Info');
            return response()->json(['orderDetailsInfo'=>$orderDetailsView,'saleId'=>$saleId]);
        } else {
            Log::warning('Sales Info Not Found');
             return response()->json('error');
        }
    }
?>