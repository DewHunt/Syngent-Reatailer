<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AuthorityMessage;
use App\Models\DelarDistribution;
use App\Models\DealerInformation;
use App\Models\Employee;
use App\Models\BrandPromoter;
use App\Models\Retailer;
use App\Models\Zone;
use App\Models\User;
use App\Models\Products;
use App\Models\PushNotification;
use App\Models\Incentive;
use App\Models\SpecialAward;
use Carbon\Carbon;
use DB;
use MPDF;
use Session;
class ExportExcelPdfController extends Controller
{
    public function bpSalesExport(Request $request) {
		$bpId         	= $request->get('bpId');
		$start_date    	= $request->get('getStartDate');
		$end_date      	= $request->get('getEndDate');
		$dataType     	= $request->get('type');
		$searchVal      = $request->get('searchVal');
		
    	$bpSalesList  = DB::table('view_sales_reports')
    	->select('bp_id','bp_name','bp_phone','retailer_name','retailer_phone_number','dealer_name','dealer_code','alternate_code','dealer_phone_number',DB::raw("SUM(sale_qty) as total_qty"), DB::raw("SUM(msrp_price) as total_sale_amount"))
    	->where('status','=',0)
    	->whereBetween(\DB::raw("DATE_FORMAT(sale_date, '%Y-%m-%d')"),[$start_date,$end_date])
    	->where(function($sql_query) use($searchVal,$bpId){
    		/*if ($searchVal !=null || !empty($searchVal)) {
    			$sql_query->where('bp_name','like', '%'.$searchVal.'%')
    			->orWhere('bp_phone','like', '%'.$searchVal.'%')
    			->orWhere('retailer_name', 'like', '%'.$searchVal.'%')
    			->orWhere('retailer_phone_number','like', '%'.$searchVal.'%')
    			->orWhere('dealer_name', 'like', '%'.$searchVal.'%')
    			->orWhere('dealer_code','like', '%'.$searchVal.'%');
    		}*/
    		if($bpId > 0){
    			$sql_query->where('bp_id','=', $bpId);
    		}
    	})
    	->groupBy('bp_id')
    	->get();

		$data = view('admin.export.bp_sales_report')->with(compact('bpSalesList'))->render();
		
		if($dataType == 'excel')
		{
			header("Content-type:application/xls");
			header("Content-Disposition: attachment; filename=bp-sales-report-".date('d-m-Y').".xls");
			return $data;
		}
		else if($dataType == 'pdf')
		{
			/*header("Content-type:application/pdf");
			header("Content-Disposition: attachment; filename=bp-sales-report-".date('d-m-Y').".pdf");
			return $data;*/
			view()->share('bpSalesList', $bpSalesList);
			$pdf_doc = PDF::loadView('admin/export/bp_sales_report', $bpSalesList);
        	return $pdf_doc->download('bp-sales-report-'.date('d-m-Y').'.pdf');
		}
		else if($dataType == 'html')
		{
			header("Content-type: text/html");
			header("Content-Disposition: attachment; filename=bp-sales-report-".date('d-m-Y').".html");
			return $data;
		}
	}
	
	public function salesInvoiceExport(Request $request) {
		$retailerId = $request->get('retailerId');
		$start_date = $request->get('getStartDate');
		$end_date = $request->get('getEndDate');
		$dataType = $request->get('type');
		$searchVal = $request->get('searchVal');

		$saleList  = DB::table('view_sales_reports')
            ->where('status','=',0)
            ->whereBetween(DB::raw("DATE_FORMAT(sale_date,'%Y-%m-%d')"),[$start_date,$end_date])
            ->orderBy('id','DESC')
            ->paginate(100);
		
		$data = view('admin.export.sales_invoice_report')->with(compact('saleList'))->render();
		
		if ($dataType == 'excel') {
			header("Content-type:application/xls");
			header("Content-Disposition: attachment; filename=sales-invoice-report-".date('d-m-Y').".xls");
			return $data;
		} else if($dataType == 'pdf') {
			view()->share('saleList', $saleList);
			$pdf_doc = MPDF::loadView('admin/export/sales_invoice_report',compact('saleList'),[],['sales-invoice-report-'.date('d-m-Y')]);
			return $pdf_doc->download('sales-invoice-report-'.date('d-m-Y').'.pdf');
		} else if($dataType == 'html') {
			header("Content-type: text/html");
			header("Content-Disposition: attachment; filename=sales-invoice-report-".date('d-m-Y').".html");
			return $data;
		}
	}
	
	public function salesIncentiveExport(Request $request) {
		$cat            = $request->get('cat');
		$bpId         	= $request->get('bpId');
		$retailerId     = $request->get('retailerId');
		$get_from_date  = $request->get('getStartDate');
		$get_to_date    = $request->get('getEndDate');
		$dataType     	= $request->get('type');
		$searchVal      = $request->get('searchVal');


        $from_date  = (!empty($get_from_date)) ? $get_from_date :date('Y-m-01');
        $to_date    = (!empty($get_to_date)) ? $get_to_date :date('Y-m-t');
		
		
		$salesIncentiveReportList = DB::table('view_sales_incentive_reports')
        ->select('id','incentive_date','bp_id','retailer_id','category','bp_name','bp_phone','retailer_name','retailer_phone_number','dealer_name','dealer_phone_number',\DB::raw('SUM(incentive_amount) AS total_incentive'),'zone',\DB::raw('SUM(incentive_sale_qty) AS total_qty'))
        ->whereBetween(DB::raw("DATE_FORMAT(incentive_date,'%Y-%m-%d')"),[$from_date,$to_date])
		/*->where(function($sql_query) use($searchVal){
			if ($searchVal !=null || !empty($searchVal)) {
			    $sql_query->where('category','like', '%'.$searchVal.'%')
			    ->orWhere('dealer_name','like', '%'.$searchVal.'%')
			    ->orWhere('bp_name','like', '%'.$searchVal.'%')
			    ->orWhere('retailer_name','like', '%'.$searchVal.'%')
			    ->orWhere('incentive_amount','like', '%'.$searchVal.'%');
			}
		})*/
		->where(function($sql_query) use($cat,$bpId,$retailerId) {
            if(!empty($cat)) {
                $sql_query->where('category', '=', $cat);
            }

            if($bpId > 0){
                $sql_query->where('bp_id','=', $bpId);
            }
            if($retailerId > 0){
                $sql_query->where('retailer_id','=', $retailerId);
            }

        })
		//->groupBy('bp_id')
		//->groupBy('retailer_id')
    	->get();
		
		$data = view('admin.export.sales_incentive_report')->with(compact('salesIncentiveReportList'))->render();
		
		if($dataType == 'excel')
		{
			header("Content-type:application/xls");
			header("Content-Disposition: attachment; filename=sales-incentive-report-".date('d-m-Y').".xls");
			return $data;
		}
		else if($dataType == 'pdf')
		{
			view()->share('salesIncentiveReportList', $salesIncentiveReportList);
			$pdf_doc = PDF::loadView('admin/export/sales_incentive_report', $salesIncentiveReportList);
        	return $pdf_doc->download('sales-incentive-report-'.date('d-m-Y').'.pdf');
		}
		else if($dataType == 'html')
		{
			header("Content-type: text/html");
			header("Content-Disposition: attachment; filename=sales-incentive-report-".date('d-m-Y').".html");
			return $data;
		}
	}
	
	public function bpAttendanceExport(Request $request)
	{
		$bpId         	= ($request->get('bpId')) ? $request->get('bpId'):0;
		$start_date  	= $request->get('getStartDate');
		$end_date    	= $request->get('getEndDate');
		$dataType     	= $request->get('type');
		$searchVal      = $request->get('searchVal');


        $bpAttendanceArray = BrandPromoter::select('id','bp_name')
        ->with(['getLatestAttendances' => function($q) use($start_date) {
            $q->where(\DB::raw("DATE_FORMAT(date,'%Y-%m-%d')"),$start_date);
        }])
        ->with(['getOldestAttendances' => function($q) use($start_date) {
            $q->where(\DB::raw("DATE_FORMAT(date,'%Y-%m-%d')"),$start_date);
        }])
        ->where(function($sql_query) use($bpId){
            if($bpId !=null || !empty($bpId))
            {
                $sql_query->where('id','=',$bpId);
            }
        })
        ->get();
		
		$data = view('admin.export.bp_attendance_report')->with(compact('bpAttendanceArray'))->render();
		
		if($dataType == 'excel')
		{
			header("Content-type:application/xls");
			header("Content-Disposition: attachment; filename=bp-attendance-report-".date('d-m-Y').".xls");
			return $data;
		}
		else if($dataType == 'pdf')
		{
			/*header("Content-type:application/pdf");
			header("Content-Disposition: attachment; filename=bp-attendance-report-".date('d-m-Y').".pdf");
			return $data;*/

			view()->share('bpAttendanceArray', $bpAttendanceArray);
			$pdf_doc = PDF::loadView('admin/export/bp_attendance_report', $bpAttendanceArray);
        	return $pdf_doc->download('bp-attendance-report-'.date('d-m-Y').'.pdf');
		}
		else if($dataType == 'html')
		{
			header("Content-type: text/html");
			header("Content-Disposition: attachment; filename=bp-attendance-report-".date('d-m-Y').".html");
			return $data;
		}
	}
	
	public function bpLeaveExport(Request $request)
	{
		$month_Sdate       =  date('Y-m-01');
        $month_Edate       =  date('Y-m-t');

        $leaveList = DB::table('view_bp_leave_report')
        //->whereBetween('start_date',[$month_Sdate,$month_Edate])
        ->get();
		
		$data = view('admin.export.bp_leave_report')->with(compact('leaveList'))->render();
		
		if($dataType == 'excel')
		{
			header("Content-type:application/xls");
			header("Content-Disposition: attachment; filename=bp-leave-report-".date('d-m-Y').".xls");
			return $data;
		}
		else if($dataType == 'pdf')
		{
			/*header("Content-type:application/pdf");
			header("Content-Disposition: attachment; filename=bp-leave-report-".date('d-m-Y').".pdf");
			return $data;*/

			view()->share('leaveList', $leaveList);
			$pdf_doc = PDF::loadView('admin/export/bp_leave_report', $leaveList);
        	return $pdf_doc->download('bp-leave-report-'.date('d-m-Y').'.pdf');
		}
		else if($dataType == 'html')
		{
			header("Content-type: text/html");
			header("Content-Disposition: attachment; filename=bp-leave-report-".date('d-m-Y').".html");
			return $data;
		}
	}

	public function exportLeaveReport(Request $request)
    {
		$bpId         = $request->get('bpId');
		$from_date    = $request->get('getStartDate');
		$to_date      = $request->get('getEndDate');
		$dataType     = $request->get('type');
		$searchVal    = $request->get('searchVal');
		
        $leaveList = DB::table('view_bp_leave_report')
    	->whereBetween(\DB::raw("DATE_FORMAT(start_date, '%Y-%m-%d')"),[$from_date,$to_date])
    	->where(function($sql_query) use($searchVal,$bpId){
    		/*if ($searchVal !=null || !empty($searchVal)) {
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
    		}*/

    		if($bpId > 0){
				$sql_query->where('bp_id','=', $bpId);
			}
    	})
    	->get();
		
	    $data = view('admin.export.leave_report_list',compact('leaveList'))->render();
		
		if($dataType == 'excel')
		{
			header("Content-type:application/xls");
			header("Content-Disposition: attachment; filename=leave-report-".date('d-m-Y').".xls");
			return $data;
		}
		else if($dataType == 'pdf')
		{
			view()->share('leaveList', $leaveList);
			$pdf_doc = PDF::loadView('admin/export/leave_report_list', $leaveList);
        	return $pdf_doc->download('leave-report-'.date('d-m-Y').'.pdf');
		}
		else if($dataType == 'html')
		{
			header("Content-type: text/html");
			header("Content-Disposition: attachment; filename=leave-report-".date('d-m-Y').".html");
			return $data;
		}
    }
    
    public function exportPendingLeaveReport(Request $request)
    {
		$bpId         = $request->get('bpId');
		$from_date    = $request->get('getStartDate');
		$to_date      = $request->get('getEndDate');
		$dataType     = $request->get('type');
		$searchVal    = $request->get('searchVal');
		
        $leaveList = DB::table('view_bp_leave_report')
        ->where('status','=','Pending')
    	->whereBetween(\DB::raw("DATE_FORMAT(start_date, '%Y-%m-%d')"),[$from_date,$to_date])
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
    	->get();
		
	    $data = view('admin.export.leave_report_list',compact('leaveList'))->render();
		
		if($dataType == 'excel')
		{
			header("Content-type:application/xls");
			header("Content-Disposition: attachment; filename=leave-report-".date('d-m-Y').".xls");
			return $data;
		}
		else if($dataType == 'pdf')
		{
			/*header("Content-type:application/pdf");
			header("Content-Disposition: attachment; filename=leave-report-".date('d-m-Y').".pdf");
			return $data;*/

			view()->share('leaveList', $leaveList);
			$pdf_doc = PDF::loadView('admin/export/leave_report_list', $leaveList);
        	return $pdf_doc->download('leave-report-'.date('d-m-Y').'.pdf');
		}
		else if($dataType == 'html')
		{
			header("Content-type: text/html");
			header("Content-Disposition: attachment; filename=leave-report-".date('d-m-Y').".html");
			return $data;
		}
    }
	
	public function soldIMEIExport_bk_20_04_2022(Request $request)
	{
		$bpId         	= ($request->get('bpId')) ? $request->get('bpId'):0;
		$retailId       = ($request->get('retailerId')) ? $request->get('retailerId'):0;
		$start_date  	= $request->get('getStartDate');
		$end_date    	= $request->get('getEndDate');
		$dealerCode 	= $request->get('dealerId');
		$productId 		= $request->get('searchProductId');
		$dataType     	= $request->get('type');
		$searchVal      = $request->get('searchVal');
		
		$month_Sdate    =  ($start_date) ? $start_date:date('Y-m-01');
        $month_Edate    =  ($end_date) ? $end_date:date('Y-m-t');
		
		$saleList = DB::table('view_sales_reports')
        ->where('status','=',0)
		->whereBetween(\DB::raw("DATE_FORMAT(sale_date, '%Y-%m-%d')"),[$month_Sdate,$month_Edate])
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
    	/*->where(function($sql_query) use($searchVal){
    		if ($searchVal !=null || !empty($searchVal)) {
    			$sql_query->where('dealer_code','like', '%'.$searchVal.'%')
    			->orWhere('alternate_code','like', '%'.$searchVal.'%')
    			->orWhere('dealer_name', 'like', '%'.$searchVal.'%')
    			->orWhere('retailer_name', 'like', '%'.$searchVal.'%')
    			->orWhere('retailer_phone_number','=', $searchVal)
    			->orWhere('bp_name', 'like', '%'.$searchVal.'%')
    			->orWhere('bp_phone','=', $searchVal)
    			->orWhere('ime_number','like', '%'.$searchVal.'%')
    			->orWhere('alternate_imei','like', '%'.$searchVal.'%')
    			->orWhere('product_model', 'like', '%'.$searchVal.'%');
    		}
    	})*/
        ->orderBy('id','desc')
        ->get();

		//$soldImeList = DB::table('view_sales_reports')->where('status',0)->get();
		$data = view('admin.export.sold_imei_report')->with(compact('saleList'))->render();
		if($dataType == 'excel')
		{
			header("Content-type:application/xls");
			header("Content-Disposition: attachment; filename=sold-imei-report-".date('d-m-Y').".xls");
			return $data;
		}
		else if($dataType == 'pdf')
		{
			view()->share('saleList', $saleList);
			$pdf_doc = PDF::loadView('admin/export/sold_imei_report', $saleList);
        	return $pdf_doc->download('sold-imei-report-'.date('d-m-Y').'.pdf');
		}
		else if($dataType == 'html')
		{
			header("Content-type: text/html");
			header("Content-Disposition: attachment; filename=sold-imei-report-".date('d-m-Y').".html");
			return $data;
		}
	}
	
	public function soldIMEIExport(Request $request)
	{
		$bpId         	= ($request->get('bpId')) ? $request->get('bpId'):0;
		$retailId       = ($request->get('retailerId')) ? $request->get('retailerId'):0;
		$start_date  	= $request->get('getStartDate');
		$end_date    	= $request->get('getEndDate');
		$dealerCode 	= $request->get('dealerId');
		$productId 		= $request->get('searchProductId');
		$dataType     	= $request->get('type');
		$searchVal      = $request->get('searchVal');
		$fieldName      = $request->get('field');
		
		$month_Sdate    =  ($start_date) ? $start_date:date('Y-m-01');
        $month_Edate    =  ($end_date) ? $end_date:date('Y-m-t');
		
		$saleList = DB::table('view_sales_reports')
        ->where('status','=',0)
		->whereBetween(\DB::raw("DATE_FORMAT(sale_date, '%Y-%m-%d')"),[$month_Sdate,$month_Edate])
		->where(function($sql_query) use($bpId,$retailId,$dealerCode,$productId,$fieldName,$searchVal){
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

    		if($fieldName == 'ime_number' && !empty($searchVal) ) {
                $sql_query->where('ime_number','like', '%'.$searchVal.'%')
                ->orWhere('alternate_imei','like', '%'.$searchVal.'%');
            }
            else if($fieldName == 'product_model' && !empty($searchVal)) {
                $sql_query->where('product_model', 'like', '%'.$searchVal.'%');
            }
            else if($fieldName == 'dealer_name' && !empty($searchVal)) {
                $sql_query->where('dealer_name', 'like', '%'.$searchVal.'%');
            }
            else if($fieldName == 'dealer_phone_number' && !empty($searchVal)) {
                $sql_query->where('dealer_phone_number', '=', $searchVal);
            }
            else if($fieldName == 'dealer_code' && !empty($searchVal)) {
                $sql_query->where('dealer_code', '=', $searchVal);
            }
            else if($fieldName == 'retailer_name' && !empty($searchVal)) {
                $sql_query->where('retailer_name', 'like', '%'.$searchVal.'%');
            }
            else if($fieldName == 'retailer_phone_number' && !empty($searchVal)) {
                $sql_query->where('retailer_phone_number', '=', $searchVal);
            }
            else if($fieldName == 'bp_name') {
                $sql_query->where('bp_name', 'like', '%'.$searchVal.'%');
            }
            else if($fieldName == 'bp_phone' && !empty($searchVal)) {
                $sql_query->where('bp_phone', '=',$searchVal);
            }

    	})
    	/*->where(function($sql_query) use($searchVal){
    		if ($searchVal !=null || !empty($searchVal)) {
    			$sql_query->where('dealer_code','like', '%'.$searchVal.'%')
    			->orWhere('alternate_code','like', '%'.$searchVal.'%')
    			->orWhere('dealer_name', 'like', '%'.$searchVal.'%')
    			->orWhere('retailer_name', 'like', '%'.$searchVal.'%')
    			->orWhere('retailer_phone_number','=', $searchVal)
    			->orWhere('bp_name', 'like', '%'.$searchVal.'%')
    			->orWhere('bp_phone','=', $searchVal)
    			->orWhere('ime_number','like', '%'.$searchVal.'%')
    			->orWhere('alternate_imei','like', '%'.$searchVal.'%')
    			->orWhere('product_model', 'like', '%'.$searchVal.'%');
    		}
    	})*/
        ->orderBy('id','desc')
        ->get();

		//$soldImeList = DB::table('view_sales_reports')->where('status',0)->get();
		$data = view('admin.export.sold_imei_report')->with(compact('saleList'))->render();
		if($dataType == 'excel')
		{
			header("Content-type:application/xls");
			header("Content-Disposition: attachment; filename=sold-imei-report-".date('d-m-Y').".xls");
			return $data;
		}
		else if($dataType == 'pdf')
		{
			view()->share('saleList', $saleList);
			$pdf_doc = PDF::loadView('admin/export/sold_imei_report', $saleList);
        	return $pdf_doc->download('sold-imei-report-'.date('d-m-Y').'.pdf');
		}
		else if($dataType == 'html')
		{
			header("Content-type: text/html");
			header("Content-Disposition: attachment; filename=sold-imei-report-".date('d-m-Y').".html");
			return $data;
		}
	}
	
	public function salesProductExport(Request $request) {
		$start_date = $request->get('getStartDate');
		$end_date = $request->get('getEndDate');
		$retailId = ($request->get('retailerId')) ? $request->get('retailerId'):0;
		$dealerCode = $request->get('dealerId');
		$productId = $request->get('searchProductId');
		$dataType = $request->get('type');
		
		$productSalesReport = DB::table('view_sales_product_reports')
            ->select('*')
            ->selectRaw('SUM(sale_qty) as saleQty')
            ->selectRaw('SUM(sale_qty * sale_price) as saleAmount')                
            ->where(function($sql_query) use($start_date,$end_date,$retailId,$dealerCode,$productId) {
                if ($start_date && $end_date) {
                    $sql_query->whereBetween(\DB::raw("DATE_FORMAT(sale_date, '%Y-%m-%d')"),[$start_date,$end_date]);
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
		
		$data = view('admin.export.sales_product_report')->with(compact('productSalesReport'))->render();
		
		if ($dataType == 'excel') {
			header("Content-type:application/xls");
			header("Content-Disposition: attachment; filename=sales-product-report-".date('d-m-Y').".xls");
			return $data;
		} else if($dataType == 'pdf') {
			view()->share('productSalesReport', $productSalesReport);
			$pdf_doc = MPDF::loadView('admin/export/sales_product_report',compact('productSalesReport'),[],[
                          'sales-product-report-'.date('d-m-Y')]);
			return $pdf_doc->download('sales-product-report-'.date('d-m-Y').'.pdf');
		} else if($dataType == 'html') {
			header("Content-type: text/html");
			header("Content-Disposition: attachment; filename=sales-product-report-".date('d-m-Y').".html");
			return $data;
		}
	}
	
	public function preBookingOrderExport(Request $request)
	{
		$bpId         	= ($request->get('bpId')) ? $request->get('bpId'):0;
		$retailId       = ($request->get('retailerId')) ? $request->get('retailerId'):0;
		$start_date  	= $request->get('getStartDate');
		$end_date    	= $request->get('getEndDate');
		$dealerCode 	= $request->get('dealerId');
		$productId 		= $request->get('searchProductId');
		$dataType     	= $request->get('type');
		$searchVal      = $request->get('searchVal');
		
		
		$modelName = "";
        if($productId > 0){
            $modelName = Products::where('product_master_id','=',$productId)->value('product_model');
        }
        
        $preBookingOrderList =  DB::table('view_prebooking_order_lists')
        ->select('customer_name','customer_phone','customer_address','model','color','qty as bookingQty','advanced_payment','booking_date','bp_name','bp_phone','retailer_name','retailer_phone_number','retailder_address','dealer_name','dealer_phone_number','distributor_code','distributor_code2')
        //->selectRaw('count(qty) as bookingQty')
        ->whereBetween(\DB::raw("DATE_FORMAT(booking_date, '%Y-%m-%d')"),[$start_date,$end_date])
        /*->where(function($sql_query) use($searchVal)
        {
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
        })*/
        ->where(function($sql_query) use($dealerCode,$bpId,$retailId,$modelName)
        {
    		if($dealerCode > 0){
    			$sql_query->where('distributor_code', '=', $dealerCode);
    			$sql_query->orWhere('distributor_code2', '=', $dealerCode);
    		}
    		if($bpId > 0){
    			$sql_query->where('bp_id', '=', $bpId);
    		}
    		if($retailId > 0){
    			$sql_query->where('retailer_id', '=', $retailId);
    		}
    		if(!empty($modelName)){
    			$sql_query->where('model','=',$modelName);
    		}
        })
        //->groupBy('model')
        ->get();
		
		
		
		$data = view('admin.export.pre_booking_order_report')->with(compact('preBookingOrderList'))->render();
		
		if($dataType == 'excel')
		{
			header("Content-type:application/xls");
			header("Content-Disposition: attachment; filename=pre-booking-order-report-".date('d-m-Y').".xls");
			return $data;
		}
		else if($dataType == 'pdf')
		{
			view()->share('preBookingOrderList', $preBookingOrderList);
			$pdf_doc = PDF::loadView('admin/export/pre_booking_order_report', $preBookingOrderList);
        	return $pdf_doc->download('pre-booking-order-report-'.date('d-m-Y').'.pdf');
		}
		else if($dataType == 'html')
		{
			header("Content-type: text/html");
			header("Content-Disposition: attachment; filename=pre-booking-order-report-".date('d-m-Y').".html");
			return $data;
		}
	}
	
	public function pendingOrderExport(Request $request)
	{
		$bpId         	= ($request->get('bpId')) ? $request->get('bpId'):0;
		$retailId       = ($request->get('retailerId')) ? $request->get('retailerId'):0;
		$start_date  	= $request->get('getStartDate');
		$end_date    	= $request->get('getEndDate');
		$dealerCode 	= $request->get('dealerId');
		$productId 		= $request->get('searchProductId');
		$dataType     	= $request->get('type');
		$searchVal      = $request->get('searchVal');
		
		$productSalesReport = DB::table('view_sales_reports')
        ->select('id','customer_name','customer_phone','sale_date','dealer_code','product_code','ime_number','alternate_imei','product_model','product_color','mrp_price','msdp_price','msrp_price','sale_price','retailer_name','retailder_address','retailer_phone_number','bp_name','bp_phone','dealer_name','alternate_code','dealer_phone_number','order_type','status')
        //->selectRaw('count(sale_qty) as saleQty')
        //->selectRaw('SUM(sale_qty*sale_price) as saleAmount')
        ->whereIn('status',[1,2])
        ->whereBetween(\DB::raw("DATE_FORMAT(sale_date, '%Y-%m-%d')"),[$start_date,$end_date])
        ->where(function($sql_query) use($searchVal,$bpId,$retailId,$dealerCode,$productId){
			/*if ($searchVal !=null || !empty($searchVal)) {
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
			}*/

            if($bpId > 0){
                $sql_query->where('bp_id', '=', $bpId);
            }
            if($retailId > 0){
                $sql_query->where('retailer_id', '=', $retailId);
            }
            if($dealerCode > 0){
                $sql_query->where('dealer_code', '=', $dealerCode);
                $sql_query->orWhere('alternate_code', '=', $dealerCode);
            }
            if($productId > 0){
                $sql_query->where('product_master_id', '=', $productId);
            }
		})
        ->orderBy('id','desc')
        ->get();
		
		$data = view('admin.export.pending_order_report')->with(compact('productSalesReport'))->render();
		
		if($dataType == 'excel')
		{
			header("Content-type:application/xls");
			header("Content-Disposition: attachment; filename=pending-order-report-".date('d-m-Y').".xls");
			return $data;
		}
		else if($dataType == 'pdf')
		{
			view()->share('productSalesReport', $productSalesReport);
			$pdf_doc = PDF::loadView('admin/export/pending_order_report', $productSalesReport);
        	return $pdf_doc->download('pending-order-report-'.date('d-m-Y').'.pdf');
		}
		else if($dataType == 'html')
		{
			header("Content-type: text/html");
			header("Content-Disposition: attachment; filename=pending-order-report-".date('d-m-Y').".html");
			return $data;
		}
	}
	
	public function disputeIMEIExport(Request $request)
	{
		$bpId         	= ($request->get('bpId')) ? $request->get('bpId'):0;
		$retailId       = ($request->get('retailerId')) ? $request->get('retailerId'):0;
		$start_date  	= $request->get('getStartDate');
		$end_date    	= $request->get('getEndDate');
		$dealerCode 	= $request->get('dealerId');
		$imeiNumber     = $request->get('search_imei');
		$searchVal      = $request->get('searchVal');
		$dataType     	= $request->get('type');

		$imeiDisputeList = DB::table('view_imei_dispute_list')
		->whereBetween(\DB::raw("DATE_FORMAT(date, '%Y-%m-%d')"),[$start_date,$end_date])
		->where(function($sql_query) use($bpId,$retailId,$dealerCode,$imeiNumber,$searchVal){
			/*if ($searchVal !=null || !empty($searchVal)) {
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
			}*/
			if($bpId > 0){
				$sql_query->where('bp_id', '=', $bpId);
			}
			if($retailId > 0){
				$sql_query->where('retailer_id', '=', $retailId);
			}
			if($dealerCode > 0){
				$sql_query->where('distributor_code', '=', $dealerCode);
				$sql_query->where('distributor_code2', '=', $dealerCode);
			}
			if($imeiNumber > 0){
				$sql_query->where('imei_number', '=', $imeiNumber);
			}

		})
        ->orderBy('id','desc')
        ->get();
		
		$data = view('admin.export.dispute_imei_report')->with(compact('imeiDisputeList'))->render();
		
		if($dataType == 'excel')
		{
			header("Content-type:application/xls");
			header("Content-Disposition: attachment; filename=dispute-imei-report-".date('d-m-Y').".xls");
			return $data;
		}
		else if($dataType == 'pdf')
		{
			view()->share('imeiDisputeList', $imeiDisputeList);
			$pdf_doc = PDF::loadView('admin/export/dispute_imei_report', $imeiDisputeList);
        	return $pdf_doc->download('dispute-imei-report-'.date('d-m-Y').'.pdf');
		}
		else if($dataType == 'html')
		{
			header("Content-type: text/html");
			header("Content-Disposition: attachment; filename=dispute-imei-report-".date('d-m-Y').".html");
			return $data;
		}
	}
	
	public function exportIncentiveReport(Request $request)
    {
		$category     = $request->get('getCategory');
		$group        = $request->get('getGroup');
		$from_date    = $request->get('getStartDate');
		$to_date      = $request->get('getEndDate');
		$dataType     = $request->get('type');
		$searchVal    = $request->get('searchVal');

		$IncentiveList  = Incentive::orderBy('id','desc')
		->whereBetween(\DB::raw("DATE_FORMAT(end_date, '%Y-%m-%d')"),[$from_date,$to_date])
		->where(function($sql_query) use($category,$group){
			/*if ($searchVal !=null || !empty($searchVal)) {
				$sql_query->where('incentive_category','like','%'.$category.'%')
				->orWhere('incentive_group','=', $searchVal);
			}*/
			
			if($category !=null || !empty($category)){
                $sql_query->where('incentive_category','like','%'.$category.'%');
            }
            if($group !=null || !empty($group)){
                $sql_query->where('incentive_group','=',$group);
            }
		})
		->get();

        $productNameList = [];
        $iNcentiveName   = [];

        foreach($IncentiveList as $key=>$row) {
            $ProductName    = json_decode($row->product_model);
            $getIncentiveList  = json_decode($row->incentive_type);
            foreach($ProductName as $val){
                $productNameList[$key][] = $val;
            }
            foreach($getIncentiveList as $key=>$val) {
                $iNcentiveName[$key][] = $val;
            }
        }
		
	    $data = view('admin.export.incentive_report_list',compact('IncentiveList','productNameList','iNcentiveName'))->render();
		
		if($dataType == 'excel')
		{
			header("Content-type:application/xls");
			header("Content-Disposition: attachment; filename=incentive-report-".date('d-m-Y').".xls");
			return $data;
		}
		else if($dataType == 'pdf')
		{
			view()->share('IncentiveList', $IncentiveList);
			$pdf_doc = PDF::loadView('admin/export/incentive_report_list', $IncentiveList);
        	return $pdf_doc->download('incentive-report-'.date('d-m-Y').'.pdf');
		}
		else if($dataType == 'html')
		{
			header("Content-type: text/html");
			header("Content-Disposition: attachment; filename=incentive-imei-report-".date('d-m-Y').".html");
			return $data;
		}
    }
    
    public function retailerExport(Request $request)
    {
		$dataType     = $request->get('type');
		$retailerList = DB::table('view_retailer_list')
		->orderBy('owner_name','ASC')
		->get();

	    $data = view('admin.export.retailer_list',compact('retailerList'))->render();
		
		if($dataType == 'excel')
		{
			header("Content-type:application/xls");
			header("Content-Disposition: attachment; filename=retailer-list-".date('d-m-Y').".xls");
			return $data;
		}
		else if($dataType == 'pdf')
		{
			/*header("Content-type:application/pdf");
			header("Content-Disposition: attachment; filename=retailer-list-".date('d-m-Y').".pdf");
			return $data;*/

			view()->share('retailerList', $retailerList);
			$pdf_doc = PDF::loadView('admin/export/retailer_list', $retailerList);
        	return $pdf_doc->download('retailer-list-'.date('d-m-Y').'.pdf');
		}
		else if($dataType == 'html')
		{
			header("Content-type: text/html");
			header("Content-Disposition: attachment; filename=retailer-list-".date('d-m-Y').".html");
			return $data;
		}
    }
    
    public function exportUserLog(Request $request)
    {
		$dataType    = $request->get('type');
		$searchSdate = $request->get('start_date') ? $request->get('start_date'):date('Y-m-01');
		$searchEdate = $request->get('end_date') ? $request->get('end_date') : date('Y-m-d');
       
        $loginLogList = DB::table('view_user_login_activity')
        ->whereBetween(DB::RAW("DATE_FORMAT(created_at,'%Y-%m-%d')"),[$searchSdate,$searchEdate])
        ->orderBy('created_at','DESC')
        ->get();

	    $data = view('admin.export.user_log_list',compact('loginLogList'))->render();
		
		if($dataType == 'excel')
		{
			header("Content-type:application/xls");
			header("Content-Disposition: attachment; filename=user-log-list-".date('d-m-Y').".xls");
			return $data;
		}
		else if($dataType == 'pdf')
		{
			/*header("Content-type:application/pdf");
			header("Content-Disposition: attachment; filename=user-log-list-".date('d-m-Y').".pdf");
			return $data;*/

			view()->share('loginLogList', $loginLogList);
			$pdf_doc = PDF::loadView('admin/export/user_log_list', $loginLogList);
        	return $pdf_doc->download('user-log-list-'.date('d-m-Y').'.pdf');
		}
		else if($dataType == 'html')
		{
			header("Content-type: text/html");
			header("Content-Disposition: attachment; filename=user-log-list-".date('d-m-Y').".html");
			return $data;
		}
    }
	
	public function example()
    {
        $month_Sdate    =  date('Y-m-01');
        $month_Edate    =  date('Y-m-t');
        
        $bpSalesList = DB::table('view_sales_reports')
        ->select('bp_id','bp_name','bp_phone','retailer_name','retailer_phone_number','dealer_name','dealer_code','alternate_code','dealer_phone_number',DB::raw("SUM(sale_qty) as total_qty"), DB::raw("SUM(msrp_price) as total_sale_amount"))
        ->where('bp_id','>',0)
        ->whereBetween('sale_date',[$month_Sdate,$month_Edate])
        ->orderBy('total_sale_amount','DESC')
        ->groupBy('bp_id')
        ->get();

        $pdf = view('admin.pdf.bp_sales_report')->with(compact('bpSalesList'))->render();

        //header("Content-type: text/html");
        //header("Content-Disposition: attachment; filename='bp_sales_report.pdf'");

        header("Content-type:application/pdf");
        header("Content-Disposition: attachment; filename='bp_sales_report.pdf'");
        return $pdf;

       /* header("Content-type:application/xls");
        header("Content-Disposition: attachment; filename='bp_sales_report.xls'");
        return $pdf;*/
    }
    
    public function bpSalesIncentiveDownloadById(Request $request)
    {
        $bpId           = $request->get('bpId');
        $month_Sdate    =  date('Y-m-01');
        $month_Edate    =  date('Y-m-t');
        
        
		$salesIncentiveDetails = DB::table('view_sales_incentive_reports')
        ->whereBetween(DB::raw("DATE_FORMAT(incentive_date,'%Y-%m-%d')"),[$month_Sdate,$month_Edate])
        ->where('bp_id',$bpId)
        ->get();

	    $data = view('admin.export.bp_incentive_list',compact('salesIncentiveDetails'))->render();
	    
        header("Content-type:application/xls");
		header("Content-Disposition: attachment; filename=bp-incentive-list-".date('d-m-Y').".xls");
		return $data;
    }
    
    public function retailerSalesIncentiveDownloadById(Request $request)
    {
        $retailerId           = $request->get('retailerId');
        $month_Sdate    =  date('Y-m-01');
        $month_Edate    =  date('Y-m-t');
        
        
		$salesIncentiveDetails = DB::table('view_sales_incentive_reports')
        ->whereBetween(DB::raw("DATE_FORMAT(incentive_date,'%Y-%m-%d')"),[$month_Sdate,$month_Edate])
        ->where('retailer_id',$retailerId)
        ->get();

	    $data = view('admin.export.retailer_incentive_list',compact('salesIncentiveDetails'))->render();
	    
        header("Content-type:application/xls");
		header("Content-Disposition: attachment; filename=retailer-incentive-list-".date('d-m-Y').".xls");
		return $data;
    }
    
    public function salesReturnInvoiceExport(Request $request)
	{
		$bpId         	= $request->get('bpId');
		$retailerId     = $request->get('retailerId');
		$start_date    	= $request->get('getStartDate');
		$end_date      	= $request->get('getEndDate');
		$dataType     	= $request->get('type');
		$searchVal      = $request->get('searchVal');
		
		$bpList 		= BrandPromoter::get(['bp_id','bp_name']);
    	$retailerList 	= Retailer::get(['retailer_id','retailer_name']);

		$saleList  = DB::table('view_sales_reports')
		->where('status','=',3)
		->whereBetween(\DB::raw("DATE_FORMAT(sale_date, '%Y-%m-%d')"),[$start_date,$end_date])
		->where(function($sql_query) use($searchVal,$bpId,$retailerId){
            /*if ($searchVal !=null || !empty($searchVal)) {
                $sql_query->where('bp_name','like', '%'.$searchVal.'%')
                ->orWhere('bp_phone','like', '%'.$searchVal.'%')
                ->orWhere('retailer_name','like', '%'.$searchVal.'%')
                ->orWhere('retailer_phone_number','like','%'.$searchVal.'%')
                ->orWhere('dealer_name','like', '%'.$searchVal.'%')
                ->orWhere('dealer_phone_number','like', '%'.$searchVal.'%')
                ->orWhere('dealer_code','like','%'.$searchVal.'%')
                ->orWhere('customer_name','like', '%'.$searchVal.'%')
                ->orWhere('customer_phone','like', '%'.$searchVal.'%');
			}*/
			
			if($bpId > 0){
			    $sql_query->where('bp_id','=', $bpId);
			}
			if($retailerId > 0){
			    $sql_query->where('retailer_id','=', $retailerId);
			}
        })
		->orderBy('id','DESC')
		->get();
		
		$data = view('admin.export.sales_return_invoice_report')->with(compact('saleList'))->render();
		
		if($dataType == 'excel')
		{
			header("Content-type:application/xls");
			header("Content-Disposition: attachment; filename=sales-return-invoice-report-".date('d-m-Y').".xls");
			return $data;
		}
		else if($dataType == 'pdf')
		{
			view()->share('saleList', $saleList);
			$pdf_doc = PDF::loadView('admin/export/sales_return_invoice_report', $saleList);
        	return $pdf_doc->download('sales-return-invoice-report-'.date('d-m-Y').'.pdf');
		}
		else if($dataType == 'html')
		{
			header("Content-type: text/html");
			header("Content-Disposition: attachment; filename=sales-return-invoice-report-".date('d-m-Y').".html");
			return $data;
		}
	}
	
}
