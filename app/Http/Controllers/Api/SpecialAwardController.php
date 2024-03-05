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

class SpecialAwardController extends Controller
{
    public function SpecialAwardCreate($groupId) {
        if ($groupId == 1) {
            return redirect('/home');
        }
        $retailerList = Retailer::get(['retailer_id','retailer_name','phone_number','retailder_address']);
        $zoneList = Zone::get();
        $modelList = Products::distinct()->get(['product_master_id','product_model']);
        $CategoryList = DB::table('bp_retailer_categories')->where('status','=',1)->get();

        return view('admin.incentive.add_special_award',compact('zoneList','modelList','retailerList','groupId','CategoryList'));
    }
    
    public function previousSpecialAwardList(Request $request,$groupId) {
        if ($groupId == 1) {
            return redirect('/home');
        }
        $productNameList = [];
        $iNcentiveName   = [];
        $previousAwardList = "";

        if ($request->ajax()) {
            $sort_by = $request->get('sortby');
            $sort_type = $request->get('sorttype');
            $query = $request->get('query');
            $query = str_replace(" ", "%", $query);
            $searchVal = str_replace(" ", "%", $query);
            $AwardList = SpecialAwards::where('award_group','=',$groupId)
                ->where('end_date','>=',date('Y-m-d'))
                ->where(function($sql_query) use($searchVal){
                    if ($searchVal !=null || !empty($searchVal)) {
                        $sql_query->where('award_title','like', '%'.$searchVal.'%')
                        ->orWhere('award_type','like', '%'.$searchVal.'%')
                        ->orWhere('min_qty','like', '%'.$searchVal.'%');
                    }
                })
                ->orderBy('id','desc')
                ->paginate(100);
            return view('admin.incentive.award_result_data',compact('AwardList','productNameList','iNcentiveName','groupId'))->render();
        } else {
            $previousAwardList = SpecialAward::where('award_group',$groupId)
                ->where('end_date','>=',date('Y-m-d'))
                ->orderBy('id','desc')
                ->paginate(100);

            foreach ($previousAwardList as $key=>$row) {
                $ProductName = json_decode($row->product_model);
                $IncentiveList = json_decode($row->incentive_type);
                foreach ($ProductName as $val) {
                    $productNameList[$key][] = $val;
                }
                foreach ($IncentiveList as $key=>$val) {
                    $iNcentiveName[$key][] = $val;
                }
            }
        }
        $title   = "Brand Promoter Previous Special Award List";
        return view('admin.incentive.previous_award_list',compact('previousAwardList','productNameList','iNcentiveName','groupId'));
    }

    public function SpecialAwardList(Request $request,$groupId) {
        if ($groupId == 1) {
            return redirect('/home');
        }
        $AwardList = "";
        $productNameList = [];
        $iNcentiveName = [];
        
        if ($request->ajax()) {
            $sort_by = $request->get('sortby');
            $sort_type = $request->get('sorttype');
            $query = $request->get('query');
            $query = str_replace(" ", "%", $query);
            $searchVal = str_replace(" ", "%", $query);

            $AwardList = SpecialAward::where('award_group','=',$groupId)
                ->where('end_date','>=',date('Y-m-d'))
                ->where(function($sql_query) use($searchVal){
                    if ($searchVal !=null || !empty($searchVal)) {
                        $sql_query->where('award_title','like', '%'.$searchVal.'%')
                        ->orWhere('award_type','like', '%'.$searchVal.'%')
                        ->orWhere('min_qty','like', '%'.$searchVal.'%');
                    }
                })
                ->orderBy('id','desc')
                ->paginate(100);
            return view('admin.incentive.award_result_data',compact('AwardList','productNameList','iNcentiveName','groupId'))->render();
        } else {
            $AwardList = SpecialAward::where('award_group',$groupId)
                ->where('end_date','>=',date('Y-m-d'))
                ->orderBy('id','desc')
                ->paginate(100);

            $productNameList = [];
            $iNcentiveName   = [];

            foreach ($AwardList as $key=>$row) {
                $ProductName = json_decode($row->product_model);
                $IncentiveList = json_decode($row->incentive_type);
                foreach ($ProductName as $val) {
                    $productNameList[$key][] = $val;
                }
                foreach ($IncentiveList as $key=>$val) {
                    $iNcentiveName[$key][] = $val;
                }
            }
        }

        $title   = "Brand Promoter Special Award List";
        return view('admin.incentive.award_list',compact('AwardList','productNameList','iNcentiveName','groupId'));
    }

    public function SpecialAwardStore(Request $request) {
        $rules = [
            'product_model'=>'required',
            'incentive_type'=>'required',
            'zone'=>'required',
            'award_type'=>'required',
            'min_qty'=>'required|numeric|min:1',
            'start_date'=>'required',
            'end_date'=>'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails())
        return redirect()->back()->with('errors',$validator->errors());

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

        $addBrandPromoterSpecialAward = SpecialAward::create([
            "award_group"=> $request->input('award_group'),
            'group_category_id' => implode(",",$request->input('group_category_id')),
            "award_title"=> $request->input('award_title'),
            "product_model"=> json_encode($ProductModel,JSON_FORCE_OBJECT),
            "incentive_type"=> json_encode($IncentiveType,JSON_FORCE_OBJECT),
            "zone"=> json_encode($IncentiveZone,JSON_FORCE_OBJECT),
            "award_type"=> $request->input('award_type'),
            "remune_ration"=> $request->input('remune_ration'),
            "min_qty"=> $request->input('min_qty'),
            "start_date"=> $request->input('start_date'),
            "end_date"=> $request->input('end_date'),
            "status"=>$request->input('status')
        ]);

        if ($addBrandPromoterSpecialAward) {
            Log::info('Special Award Stored Successfully');
            return redirect()->back()->with('success','Special Award Insert Successfully');
        } else {
            Log::error('Special Award Stored Failed');
            return redirect()->back()->with('error','Special Award Insert Failed.Please Try Again');
        }
    }

    public function SpecialAwardEdit($id) {
        $AwardId = \Crypt::decrypt($id);
        $AwardInfo = SpecialAward::where('id',$AwardId)->first();
        $CategoryList = DB::table('bp_retailer_categories')->where('status','=',1)->get();

        $productNameList = [];
        $iNcentiveName = [];
        $zoneIdList = [];
        $awardIncentiveId = [];
        $ProductName = json_decode($AwardInfo['product_model']);
        $iNcentiveIdList = json_decode($AwardInfo['incentive_type']);
        $ZoneList = json_decode($AwardInfo['zone']);

        $ModelStatus = 0;
        foreach ($ProductName as $val) {
            $productNameList[] = $val;
            if ($val == "all"){
                $ModelStatus = 1; //1=all model
            }
        }

        $TypeStatus = 0;
        foreach ($iNcentiveIdList as $key=>$val) {
            $iNcentiveName[] = $val;
            $awardIncentiveId[] = $val;
            if ($val == "all") {
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

        $retailerList = Retailer::distinct()->get(['retailer_id','retailer_name','phone_number']);
        $zoneList = Zone::get()->toArray();
        $modelList = Products::distinct()->get(['product_master_id','product_model'])->toArray();

        return view('admin.incentive.edit_special_award',compact('AwardInfo','productNameList','iNcentiveName','zoneIdList','zoneList','modelList','retailerList','ModelStatus','ZoneStatus','TypeStatus','iNcentiveIdList','awardIncentiveId','CategoryList'));
    }

    public function specialAwardModify(Request $request) {
        $awardGroup = $request->input('award_group');
        $awardId = $request->input('award_id');
        $rules = [
            'award_title'=>'required',
            'product_model'=>'required',
            'incentive_type'=>'required',
            'zone'=>'required',
            'award_type' => 'required',
            'min_qty'=>'required|numeric|min:1',
            'start_date'=>'required',
            'end_date'=>'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            return redirect()->back()->with('errors',$validator->errors());
        }

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

        $updateAward = SpecialAward::where('award_group',$awardGroup)->where("id", $awardId)->update([
            "award_group"=> $awardGroup,
            'award_title' => $request->input('award_title'),
            'group_category_id' => implode(",",$request->input('group_category_id')),
            "product_model"=> json_encode($ProductModel,JSON_FORCE_OBJECT),
            "incentive_type"=> json_encode($IncentiveType,JSON_FORCE_OBJECT),
            "zone"=> json_encode($IncentiveZone,JSON_FORCE_OBJECT),
            'award_type' => $request->input('award_type'),
            'remune_ration' => $request->input('remune_ration'),
            "min_qty"=> $request->input('min_qty'),
            "start_date"=> $request->input('start_date'),
            "end_date"=> $request->input('end_date'),
            "status"=>$request->input('status'),
        ]);

        if ($updateAward) {
            //Log::info('Special Award Update Successfully');
            return redirect()->back()->with('success','Data Update Successfully');
        } else {
            //Log::error('Special Award Update Failed');
            return redirect()->back()->with('error','Data Update Failed.Please Try Again');
        }
    }

    public function SpecialAwardStatus($id) {
        $StatusInfo = SpecialAward::find($id);
        $old_status = $StatusInfo->status;
        $UpdateStatus = $old_status == 1 ? 0 : 1;
        $UpdateAwardStatus = SpecialAward::where('id',$id)->update([
            "status"=> $UpdateStatus ? $UpdateStatus: 0
        ]);

        if ($UpdateAwardStatus) {
            Log::info('Special Award Status Change Successfully');
            return response()->json(['success'=>'Status change successfully.']);
        } else {
            Log::error('Special Award Status Change Failed');
            return response()->json(['error'=>'Status Update Failed.Please Try Again.']);
        }
    }

    public function SpecialAwardDestroy($id) {
        $Success = SpecialAward::find($id)->delete();
        if ($Success) {  
            return redirect()->back()->with('success','Deleted Successfully');
        } else {
            return redirect()->back()->with('error','Deleted Failed');
        }
    }
}