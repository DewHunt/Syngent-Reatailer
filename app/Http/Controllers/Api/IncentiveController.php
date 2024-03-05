<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use App\Models\DealerInformation;
use App\Models\Retailer;
use App\Models\Zone;
use App\Models\Products;
use App\Models\Incentive;
use App\Models\SpecialAward;
use Carbon\Carbon;
use DB;
use Validator;
use Pagination;
use DataTables;
use Response;
use Redirect;

class IncentiveController extends Controller
{
    public function index() {
        Log::info('Load Incentive Dashboard');
        return view('admin.incentive.dashboard');
    }
    
    public function previousIncentiveList(Request $request, $id) {
        if ($id = 1) {
            return redirect('/home');
        }
        $groupId = $id;
        $oldIncentiveList = Incentive::where('incentive_group',$groupId)->where('end_date','<',date('Y-m-d'))->orderBy('id','desc')->paginate(100);

        $productNameList = [];
        $iNcentiveName = [];

        foreach ($oldIncentiveList as $key=>$row) {
            $ProductName = json_decode($row->product_model);
            $getIncentiveList  = json_decode($row->incentive_type);
            foreach ($ProductName as $val) {
                $productNameList[$key][] = $val;
            }
            foreach ($getIncentiveList as $key=>$val) {
                $iNcentiveName[$key][] = $val;
            }
        }

        if ($request->ajax()) {
            $sort_by = $request->get('sortby');
            $sort_type = $request->get('sorttype');
            $query = $request->get('query');
            $query = str_replace(" ", "%", $query);

            $IncentiveList = Incentive::where('id', $query)
                ->where('incentive_group',1)
                ->orWhere('incentive_title', 'like', '%'.$query.'%')
                ->orWhere('incentive_category','like','%'.$query.'%')
                ->orWhere('incentive_amount', $query)
                ->orWhere('min_qty',$query)
                ->orWhere('start_date', 'like', '%'.$query.'%')
                ->orWhere('end_date', 'like', '%'.$query.'%')
                ->orWhere('status', $query)
                ->orderBy($sort_by, $sort_type)
                ->paginate(100);
            return view('admin.incentive.incentive_result_data',compact('IncentiveList','productNameList','iNcentiveName','groupId'));
        }

        if (isset($oldIncentiveList) && $oldIncentiveList->isNotEmpty()) {
            Log::info('Load Incentive List');
        } else {
            Log::warning('Incentive List Not Found');
        }

        return view('admin.incentive.previous_incentive_list',compact('oldIncentiveList','productNameList','iNcentiveName','groupId'));
    }

    public function IncentiveList(Request $request, $id) {
        if ($id == 1) {
            return redirect('/home');
        }
        $month_Sdate = date('Y-m-01');
        $month_Edate = date('Y-m-t');
        $groupId = $id;
        
        if ($request->ajax()) {
            $sort_by = $request->get('sortby');
            $sort_type = $request->get('sorttype');
            $query = $request->get('query');
            $query = str_replace(" ", "%", $query);
            $searchVal = str_replace(" ", "%", $query);

            $IncentiveList = Incentive::where('incentive_group',$groupId)->where(function($sql_query) use($searchVal){
                    if ($searchVal !=null || !empty($searchVal)) {
                        $sql_query->where('incentive_title','like', '%'.$searchVal.'%')
                            ->orWhere('incentive_category','like', '%'.$searchVal.'%')
                            ->orWhere('incentive_amount', 'like', '%'.$searchVal.'%')
                            ->orWhere('min_qty','like', '%'.$searchVal.'%');
                    }
                })
                ->where('end_date','>=',date('Y-m-d'))
                ->orderBy('id','desc')
                ->paginate(100);
            
            $productNameList = [];
            $iNcentiveName = [];

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

            return view('admin.incentive.incentive_result_data',compact('IncentiveList','productNameList','iNcentiveName','groupId'))->render();
        } else {
            $IncentiveList  = Incentive::where('incentive_group',$groupId)
                //->where('end_date','>=',date('Y-m-d'))
                ->whereBetween(\DB::raw("DATE_FORMAT(end_date,'%Y-%m-%d')"),[$month_Sdate,$month_Edate])
                ->orderBy('id','desc')
                ->paginate(100);

            $productNameList = [];
            $iNcentiveName = [];

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

        if (isset($IncentiveList) && $IncentiveList->isNotEmpty()) {
            Log::info('Load Incentive List');
        } else {
            Log::warning('Incentive List Not Found');
        }

        return view('admin.incentive.incentive_list',compact('IncentiveList','productNameList','iNcentiveName','groupId'));
    }

    public function IncentiveCreate($groupId) {
        if ($groupId == 1) {
            return redirect('/home');
        }
        $retailerList = Retailer::get(['retailer_id','retailer_name','phone_number']);
        $zoneList = Zone::get();
        $modelList = Products::distinct()->get(['product_master_id','product_model']);
        $CategoryList = DB::table('bp_retailer_categories')->where('status','=',1)->orderBy('sorting_number','ASC')->get();

        return view('admin.incentive.incentive_add',compact('zoneList','modelList','retailerList','groupId','CategoryList'));
    }

    public function IncentiveStore(Request $request) {
        $rules = [
            'incentive_category'=>'required',
            'incentive_title'=>'required',
            'product_model'=>'required',
            'incentive_type'=>'required',
            'zone'=>'required',
            'incentive_amount' => 'required',
            'min_qty'=>'required|numeric|min:1',
            'start_date'=>'required',
            'end_date'=>'required'
        ];

        $ProductModel = $request->input('product_model');
        if(in_array("all", $ProductModel)) {
            $ProductModel = ["all"];
        }

        $IncentiveType = $request->input('incentive_type');
        if(in_array("all", $IncentiveType)) {
            $IncentiveType = ["all"];
        }

        $IncentiveZone = $request->input('zone');
        if(in_array("all", $IncentiveZone)) {
            $IncentiveZone = ["all"];
        }
        

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()) {
            Log::error('Incentive Validation Failed');
            return redirect()->back()->with('errors',$validator->errors());
        }

        // $start_date =  $request->input('start_date');
        // $form_date  = Carbon::createFromFormat('m/d/Y', $start_date)->format('d-m-Y');
        // $end_date   =  $request->input('end_date');
        // $to_date    = Carbon::createFromFormat('m/d/Y', $end_date)->format('d-m-Y');


        $addBrandPromoterIncetive = Incentive::create([
            "incentive_category" => $request->input('incentive_category'),
            "incentive_group" => $request->input('incentive_group'),
            "incentive_title" => $request->input('incentive_title'),
            //"group_category_id" => implode(',',$request->input('group_category_id')),
            "group_category_id" => $request->input('group_category_id'),
            "product_model"=> json_encode($ProductModel,JSON_FORCE_OBJECT),
            "incentive_type"=> json_encode($IncentiveType,JSON_FORCE_OBJECT),
            "zone"=> json_encode($IncentiveZone,JSON_FORCE_OBJECT),
            'incentive_amount' => $request->input('incentive_amount'),
            "min_qty"=> $request->input('min_qty'),
            "start_date"=> $request->input('start_date'),
            "end_date"=> $request->input('end_date'),
            "status"=>$request->input('status'),
            "created_at"=>Carbon::now(),
            "updated_at"=>Carbon::now()
        ]);

        if ($addBrandPromoterIncetive) {
            Log::info('Create Incentive Success');
            return redirect()->back()->with('success','Incentive Insert Successfully');
        } else {
            Log::error('Create Incentive Failed');
            return redirect()->back()->with('error','Incentive Insert Failed.Please Try Again');
        }
    }
    
    public function IncentiveEdit($id) {
        $incentiveId = \Crypt::decrypt($id);
        $IncentiveInfo = Incentive::where('id',$incentiveId)->first();
        $productNameList = [];
        $iNcentiveName = [];
        $ProductName = json_decode($IncentiveInfo['product_model']);
        $IncentiveList = json_decode($IncentiveInfo['incentive_type']);
        $ZoneList = json_decode($IncentiveInfo['zone']);
        $CategoryList = DB::table('bp_retailer_categories')->where('status','=',1)->orderBy('sorting_number','ASC')->get();
        
        $ModelStatus = 0;
        foreach ($ProductName as $val) {
            $productNameList[] = $val;
            if ($val == "all") {
                $ModelStatus = 1; //1=all model
            }
        }
        
        $TypeStatus = 0;
        foreach ($IncentiveList as $key=>$val) {
            $iNcentiveName[] = $val;
            if($val == "all") {
                $TypeStatus = 1; //1=all model
            }
        }

        $ZoneStatus = 0;
        foreach ($ZoneList as $key=>$val) {
            $zoneIdList[] = $val;
            if ($val == "all") {
                $ZoneStatus = 1; //1=all model
            }
        }

        $retailerList = "";
        if ($IncentiveInfo['incentive_group'] == 2) {
            //$retailerList   = Retailer::whereIn('id',[2,42])
            $retailerList = Retailer::whereIn('id',(array)$IncentiveList)->get(['id','retailer_name','phone_number']);
        }
        $zoneList = Zone::get()->toArray();
        $modelList = Products::distinct()->get(['product_master_id','product_model'])->toArray();

        return view('admin.incentive.incentive_edit',compact('IncentiveInfo','productNameList','iNcentiveName','zoneIdList','zoneList','modelList','retailerList','ModelStatus','ZoneStatus','TypeStatus','CategoryList'));
    }

    public function IncentiveUpdate(Request $request, $id) {
        $incentiveId = \Crypt::decrypt($id);
        $rules = [
            'incentive_category'=>'required',
            'incentive_title'=>'required',
            'product_model'=>'required',
            'incentive_type'=>'required',
            'zone'=>'required',
            'incentive_amount' => 'required',
            'min_qty'=>'required|numeric|min:1',
            'start_date'=>'required',
            'end_date'=>'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            Log::error('Update Incentive Validation Failed');
            return redirect()->back()->with('errors',$validator->errors());
        }

        // $start_date =  $request->input('start_date');
        // $form_date  =  Carbon::createFromFormat('m/d/Y', $start_date)->format('d-m-Y');
        // $end_date   =  $request->input('end_date');
        // $to_date    =  Carbon::createFromFormat('m/d/Y', $end_date)->format('d-m-Y');

        $ProductModel = $request->input('product_model');
        if (in_array("all", $ProductModel)) {
            $ProductModel = ["all"];
        }

        $IncentiveType = $request->input('incentive_type');
        if (in_array("all", $IncentiveType)) {
            $IncentiveType = ["all"];
        }

        $IncentiveZone = $request->input('zone');
        if (in_array("all", $IncentiveZone)) {
            $IncentiveZone = ["all"];
        }
        
        $getIncentiveInfo = Incentive::where("id", $incentiveId)->first();
        $applyForNew = 0;//No Update
        $old_amount = $getIncentiveInfo->incentive_amount;
        $old_sdate = $getIncentiveInfo->start_date;
        $old_edate = $getIncentiveInfo->end_date;
        $new_amount = $request->input('incentive_amount');
        $new_sdate = $request->input('start_date');
        $new_edate = $request->input('end_date');

        if ($old_amount != $new_amount || strtotime($old_sdate) != strtotime($new_sdate) || strtotime($old_edate) != strtotime($new_edate)) {
            $applyForNew = 1;
        }
        
        $status = 0;

        if (isset($applyForNew) && $applyForNew == 1) {
            $addBrandPromoterIncetive = Incentive::create([
                "parent_id"=>$incentiveId,
                "incentive_category" => $request->input('incentive_category'),
                "incentive_group" => $request->input('incentive_group'),
                "incentive_title" => $request->input('incentive_title'),
                //"group_category_id" => implode(',',$request->input('group_category_id')),
                "group_category_id" => $request->input('group_category_id'),
                "product_model"=> json_encode($ProductModel,JSON_FORCE_OBJECT),
                "incentive_type"=> json_encode($IncentiveType,JSON_FORCE_OBJECT),
                "zone"=> json_encode($IncentiveZone,JSON_FORCE_OBJECT),
                'incentive_amount' => $request->input('incentive_amount'),
                "min_qty"=> $request->input('min_qty'),
                "start_date"=> $request->input('start_date'),
                "end_date"=> $request->input('end_date'),
                "status"=>$request->input('status'),
                "created_at"=>Carbon::now(),
                "updated_at"=>Carbon::now()
            ]);

            if ($addBrandPromoterIncetive) {
                $status = 1;
            }
        } else {
            $updateIncetive = Incentive::where('incentive_group',$request->input('incentive_group'))
                ->where("id", $incentiveId)
                ->update([
                    "incentive_category" => $request->input('incentive_category'),
                    'incentive_group' => $request->input('incentive_group'),
                    'incentive_title' => $request->input('incentive_title'),
                    "group_category_id" => implode(',',$request->input('group_category_id')),
                    "product_model"=> json_encode($ProductModel,JSON_FORCE_OBJECT),
                    "incentive_type"=> json_encode($IncentiveType,JSON_FORCE_OBJECT),
                    "zone"=> json_encode($IncentiveZone,JSON_FORCE_OBJECT),
                    'incentive_amount' => $request->input('incentive_amount'),
                    "min_qty"=> $request->input('min_qty'),
                    "start_date"=> $request->input('start_date'), //$form_date,
                    "end_date"=> $request->input('end_date'), //$to_date,
                    "status"=>$request->input('status'),
                    "updated_at"=>Carbon::now()
                ]);
            
            if ($updateIncetive) {
                $status = 2;
            }
        }

        if ($status == 2) {
            Log::info('Existing Incentive Update Success');
            return redirect()->back()->with('success','Incentive Update Successfully');
        } else if($status == 1) {
            Log::info('Create Incentive Success');
            return redirect()->back()->with('success','Incentive Insert Successfully');
        } else {
            Log::error('Existing Incentive Update Failed');
            return redirect()->back()->with('error','Incentive Updated Failed.Please Try Again');
        }
    }

    public function IncentiveStatus($id) {
        if (isset($id) && $id > 0) {
            $StatusInfo = Incentive::find($id);
            $old_status = $StatusInfo->status;
            $UpdateStatus = $old_status == 1 ? 0 : 1;
            $UpdateIncentiveStatus = Incentive::where('id',$id)->update([
                "status"=> $UpdateStatus ? $UpdateStatus:0
            ]);

            if ($UpdateIncentiveStatus) {
                Log::info('Incentive Status Change Success');
                return response()->json(['success'=>'Status change successfully.']);
            } else {
                Log::error('Incentive Status Change Failed');
                return response()->json(['error'=>'Status Update Failed.Please Try Again.']);
            }
        } else {
            Log::warning('Invalid Incentive Id');
            return response()->json('error');
        }
    }

    public function IncentiveDestroy($id) {
        $Success = Incentive::find($id)->delete();
        if ($Success) {
            return redirect()->back()->with('success','Deleted Successfully');
        } else {
            return redirect()->back()->with('error','Deleted Failed');
        }
    }
}