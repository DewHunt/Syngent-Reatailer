<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Zone;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use DB;
use Validator;
use Response;

class ZoneController extends Controller
{    
    public function index(Request $request) {
        //$ZoneList = GetTableWithPagination('view_zone_list',100);
        $ZoneList = DB::table('view_zone_list')->orderBy('zone_name','ASC')->get();
        Log::info('Load Zone List');
        return view('admin.zone.list',compact('ZoneList'));
    }
    
    public function create() {
        //
    }

    public function store(Request $request) {
        $rules = ['zone_name'=>'required'];
        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            return Response::json(['errors' => $validator->errors()]);
        }
        
        $AddZone = zone::create([
            "zone_name"=>$request->input('zone_name'),
            "status"=>$request->input('status'),
            "created_at"=> Carbon::now()
        ]);

        if ($AddZone) {
            Log::info('Create New Zone');
            return response()->json('success');
        } else {
            Log::warning('Create New Zone');
            return response()->json('error');
        }        
    }

    public function show() {
        //
    }

    public function edit($id) {
        if (isset($id) && $id > 0) {
            $ZoneInfo = DB::table('view_zone_list')->where('id',$id)->first();
            $html = view('admin.zone.edit_form')->with(compact('ZoneInfo'))->render();
            Log::info('Get Zone Information By Id');
            return response()->json($html);
        } else {
            Log::warning('Edit Zone id is Missing');
            return response()->json('error');
        }
    }

    public function update(Request $request) {
        $rules = ['zone_name'=>'required',];
        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            return Response::json(['errors' => $validator->errors()]);
        }

        $id = $request->input('update_id');

        $UpdateZone = zone::where('id',$id)->update([
            "zone_name"=>$request->input('zone_name'),
            "status"=>$request->input('status'),
            "updated_at"=> Carbon::now()
        ]);

        if ($UpdateZone) {
            Log::info('Zone Updated Successfully');
            return response()->json('success');
        } else {
            Log::error('Zone Updated Failed');
            return response()->json('error');
        } 
    }

    public function ChangeStatus($id) {
        if (isset($id) && $id > 0) {
            $StatusInfo = zone::find($id);
            $old_status = $StatusInfo->status;
            $UpdateStatus = $old_status == 1 ? 0 : 1;

            $UpdateZoneStatus = zone::where('id',$id)->update([
                "status"=> $UpdateStatus ? $UpdateStatus:0
            ]);

            if ($UpdateZoneStatus) {
                Log::info('Zone Status Updated Successfully');
                return response()->json(['success'=>'Status change successfully.']);
            } else {
                Log::error('Zone Status Updated Failed');
                return response()->json(['error'=>'Status Update Failed.Please Try Again.']);
            }
        } else {
            Log::warning('Zone id is Missing When Status Changed');
            return response()->json('error');
        }
    }

    public function delete(Request $request) {
        $status = Zone::where('id',$request->id)->delete();

        if ($status == true) {
            return response()->json('success');
        }
        return response()->json('error'); 
    }

    public function destroy(Zone $zone) {
        //
    }
}