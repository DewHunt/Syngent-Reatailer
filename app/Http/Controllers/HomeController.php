<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Zone;
use App\Models\Sale;
use App\Models\DealerInformation;
use App\Models\LoginActivity;
use App\Repositories\HomeInterface;
use App\Repositories\Repository;
use Illuminate\Support\Facades\Log;
use DB;
use Carbon\Carbon;

class HomeController extends Controller
{
    protected $model;
    public function __construct(HomeInterface $homeRepo,DealerInformation $dealer_information,Zone $zone) {
    	$this->middleware('auth');
        $this->home     = $homeRepo;
        $this->model    = new Repository($dealer_information);
        //$this->model    = new Repository($zone);
    }
    
    public function index() {
		$loginLogList = DB::table('view_user_login_activity')
	        ->orderBy('created_at','desc')
	        ->limit(10)
	        ->get();
        return view('admin.home',compact('loginLogList'));
    }
    
    public function get_daily_sales_report() {
		$month_Sdate = date('Y-m-01');
        $month_Edate = date('Y-m-t');

		$monthlySalesList = DB::select(DB::raw("
			SELECT Date(`sale_date`) AS `date`, SUM(`total_qty`) AS `total_qty`, SUM(`total_amount`) AS `total_sale_amount`
			FROM `view_sales_reports`
			WHERE `sale_date` BETWEEN '$month_Sdate' AND '$month_Edate'
			GROUP BY DATE(`sale_date`)
			ORDER BY `sale_date` ASC
		"));
        $salesDate = [];
		$salesQty = [];
        foreach ($monthlySalesList as $key => $value) {
            $salesDate[] = $value->date;
            $salesQty[] = (int)$value->total_qty;
        }
		
		return response()->json(['salesDate'=>json_encode($salesDate,JSON_NUMERIC_CHECK),'salesQty'=>json_encode($salesQty,JSON_NUMERIC_CHECK)]);
	}
	
	public function get_monthly_sales_report() {
		$currentYear = date('Y');
		$yearMonthSalesQtyList = DB::select(DB::raw("
			SELECT MONTHNAME(`sale_date`) AS `monthName`, SUM(`total_qty`) AS `totQty`, SUM(`total_amount`) AS `total_sale_amount`
			FROM `view_sales_reports`
			WHERE YEAR(`sale_date`) = '$currentYear'
			GROUP BY Month(`sale_date`)
			ORDER BY Month(sale_date) ASC
		"));
        
        $yearMonthNameList = [];
        $yearMonthQty      = [];
        foreach($yearMonthSalesQtyList as $row) {
            $yearMonthNameList[] = $row->monthName;
            $yearMonthQty[]      = (int)$row->totQty;
        }
		
		return response()->json(['yearMonthNameList'=>json_encode($yearMonthNameList,JSON_NUMERIC_CHECK),'yearMonthQty'=>json_encode($yearMonthQty,JSON_NUMERIC_CHECK)]);
	}
	
	public function get_bp_top_saler() {
		$topSalerList = DB::select(DB::raw("
			SELECT `bp_name`, SUM(`sale_qty`) AS `total_qty`, SUM(`msrp_price`) AS `total_sale_amount`
			FROM `view_sales_reports`
			GROUP BY `bp_id`
			ORDER BY `total_sale_amount` DESC
			LIMIT 15
		"));
		
		$bpName = [];
        $bpQty = [];
        $bpAmount = [];
		
		foreach ($topSalerList as $row) {
			$bpName[] = $row->bp_name;
			$bpQty[] = $row->total_qty;
			$bpAmount[] = $row->total_sale_amount;
		}

		return response()->json(['bpName'=>json_encode($bpName,JSON_NUMERIC_CHECK),'bpAmount'=>json_encode($bpAmount,JSON_NUMERIC_CHECK)]);
	}
	
	
	public function get_retailer_top_saler() {
		$topSalerList = DB::select(DB::raw("
			SELECT `retailer_name`, SUM(`sale_qty`) AS `total_qty`, SUM(`msrp_price`) AS `total_sale_amount`
			FROM `view_sales_reports`
			GROUP BY `retailer_id`
			ORDER BY `total_sale_amount` DESC
			LIMIT 15
		"));		

		$retailerName = [];
        $retailerQty = [];
        $retailerAmount = [];
		
		foreach ($topSalerList as $row) {
			if ($row->retailer_name != NULL) {
                $retailerName[] = str_replace(array( '\'', '"',',' , ';', '<', '>','.' ), ' ', $row->retailer_name);
                $retailerQty[] = $row->total_qty;
                $retailerAmount[] = $row->total_sale_amount;
            }
		}		
		
		return response()->json(['retailerName'=>json_encode($retailerName,JSON_NUMERIC_CHECK),'retailerAmount'=>json_encode($retailerAmount,JSON_NUMERIC_CHECK)]);
	}
	
	public function get_model_waise_report() {
		$modelWaiseSalesList = DB::select(DB::raw("
			SELECT `product_model`, SUM(`sale_qty`) AS `totQty`
			FROM `view_sales_product_reports`
			WHERE `product_master_id` > 0
			GROUP BY `product_master_id`
			ORDER BY `totQty` DESC
			LIMIT 15
		"));	

		$dataPoints = [];

        foreach ($modelWaiseSalesList as $d) {            
            $dataPoints[] = ["name" => $d->product_model,"y" => (int)$d->totQty];
        }

		return response()->json(['data'=>json_encode($dataPoints)]);
	}

    public function store(Request $request) {
       return $this->model->create($request->all());
    }

    public function show($id) {
       return $this->model->show($id);
    }

    public function update(Request $request, $id) {
       $this->model->update($request->all(), $id);
    }

    public function destroy($id) {
       return $this->model->delete($id);
    }
}
