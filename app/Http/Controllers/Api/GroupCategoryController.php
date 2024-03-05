<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use DB;
use Validator;
use Response;

class GroupCategoryController extends Controller
{
    
    public function index(){
        $getBpRetailerCatList = DB::table('bp_retailer_categories')
        //->where('status','=',1)
        ->orderBy('sorting_number','ASC')
        ->get();

        Log::info('Load BP and Retailer Group List');
        return view('admin.group-category.list',compact('getBpRetailerCatList'));
    }

    
    public function create()
    {
        //
    }

    
    public function store(Request $request)
    {
        //dd($request->all());
        $rules = [
            'group_name'=>'required',
            'sorting_number'=>'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        
        if($validator->fails())
        return Response::json(['errors' => $validator->errors()]);

        $group_name     = $request->input('group_name');
        $CheckStatus    = DB::table('bp_retailer_categories')
        ->where('name',$group_name)->first();

        $updateId       = $request->input('update_id');


        if($updateId) {
            $Update = DB::table('bp_retailer_categories')
            ->where('id',$updateId)
            ->update([
                "name"=>$group_name,
                "sorting_number"=>$request->input('sorting_number'),
                "status"=>$request->input('status'),
                'updated_at'=> Carbon::now()
            ]);
            Log::info('Update Existing Group Name Successfully');
            return response()->json('success');
        } else {
            if($CheckStatus) {
                return response()->json('warning');
            } else {
                $Add = DB::table('bp_retailer_categories')
                ->insert([
                    "name"=>$group_name,
                    "sorting_number"=>$request->input('sorting_number'),
                    "status"=>$request->input('status'),
                    'created_at'=> Carbon::now(),
                    'updated_at'=> Carbon::now()
                ]);
                Log::info('Create New Group Name Successfully');
                return response()->json('success');
            }
        }
        Log::warning('Create New Group Name Successfully');
        return response()->json('error');
    }

    
    public function show($id)
    {
        //
    }

    
    public function edit($id)
    {
        if(isset($id) && $id > 0) {
            $getInfo = DB::table('bp_retailer_categories')
            ->where('id',$id)
            ->first();
            Log::info('Get Group Name  By Id');
            return response()->json($getInfo);
        } else {
            Log::warning('Edit Group  Id is Missing');
            return response()->json('error');
        }
    }

    
    public function update(Request $request, $id)
    {
        //
    }

    
    public function destroy($id)
    {
        //
    }

    public function ChangeStatus($id) 
    {
        if(isset($id) && $id > 0) {
            $StatusInfo = DB::table('bp_retailer_categories')->find($id);
            $old_status = $StatusInfo->status;


            $UpdateStatus = $old_status == 1 ? 0 : 1;

            $UpdateZoneStatus = DB::table('bp_retailer_categories')
            ->where('id',$id)
            ->update([
                "status"=> $UpdateStatus ? $UpdateStatus:0
            ]);

            if($UpdateZoneStatus) {
                Log::info('Status Updated Successfully');
                return response()->json(['success'=>'Status change successfully.']);
            } else {
                Log::error('Status Updated Failed');
                return response()->json(['error'=>'Status Update Failed.Please Try Again.']);
            }
        } else {
            Log::warning('id is Missing When Status Changed');
            return response()->json('error');
        } 
    }

}
