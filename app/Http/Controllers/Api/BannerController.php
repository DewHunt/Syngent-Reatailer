<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use App\Models\Banner;
use Carbon\Carbon;
use DB;
use Validator;
use Response;
use Image;


class BannerController extends Controller
{    
    public function index(Request $request) {   
        $bannerList = DB::table('banners')->orderBy('id','desc')->get();
        if (isset($bannerList) && $bannerList->isNotEmpty()) {
            Log::info('Load Banner List');
        } else {
            Log::warning('Banner List Not Found');
        }
        return view('admin.banner.list',compact('bannerList'));
    }
    
    public function create() {
        //
    }
   
    public function store(Request $request) {
        $rules = [
            'banner_pic'=>'required|image|mimes:jpeg,png,jpg,gif,svg|max:512',
            'status'=>'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            Log::error('Create Banner Validation Failed');
            return Response::json(['errors' => $validator->errors()]);
        }

        $BannerPic = "";
        if ($request->hasFile('banner_pic')) {
            $getPhoto = $request->file('banner_pic');
            $filename = time().'.'.$getPhoto->getClientOriginalExtension();
            //////////////////////////////
            $thumbnailPath = public_path('/upload/banner/thumbnail');
            $img = Image::make($getPhoto->path());
            $img->resize(350, 350, function ($constraint) {
                $constraint->aspectRatio();
            })->save($thumbnailPath.'/'.$filename);
            //////////////////////////////            
            
            $destinationPath = public_path('/upload/banner/');
            $success = $getPhoto->move($destinationPath, $filename);
            $BannerPic = $filename;
        }

        $baseUrl = URL::to('');
        $bannerFullPath = 'public/upload/banner/'.$BannerPic;
        
        $getStatus = $request->input('status');
        $totalActiveBanner = Banner::where('status','=',1)->sum('status');
        $status = ($totalActiveBanner >= 4) ? 0 : $getStatus;

        $addBanner = Banner::create([
            "banner_for"=>$request->input('banner_for'),
            "banner_pic"=>$BannerPic ? $BannerPic : 'no-image.png',
            "image_path"=>$bannerFullPath,
            "status"=>$status,
            "created_at"=>Carbon::now(),
            "updated_at"=>Carbon::now()
        ]);

        if ($addBanner) {
            Log::info('Create Banner Success');
            return response()->json('success');
        } else {
            Log::error('Create Banner Failed');
            return response()->json('error');
        }
    }
    
    public function show($id) {
        //
    }
    
    public function edit($id) {
        if (isset($id) && $id > 0) {
            $editBannerInfo = Banner::where('id',$id)->first();

            if ($editBannerInfo) {
                $html = view('admin.banner.edit_form')->with(compact('editBannerInfo'))->render();
                Log::info('Get Banner By Id');
                return response()->json($html);
                // return response()->json(['bannerInfo'=>$editBannerInfo]);
            } else {
                Log::warning('Banner Not Found By Id');
                return response()->json('error');
            }
        } else {
            Log::warning('Invalid Banner Id');
            return response()->json('error');
        }
    }
    
    public function update(Request $request) {
        $update_id = $request->input('update_id');
        $rules = ['status'=>'required'];

        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            Log::error('Banner Update Validation Failed');
            return Response::json(['errors' => $validator->errors()]);
        }

        $bannerInfo = Banner::where('id',$update_id)->first();
        $BannerPic = "";

        if ($request->hasFile('banner_pic')) {
            $getPhoto = $request->file('banner_pic');
            $filename = time().'.'.$getPhoto->getClientOriginalExtension();
            //////////////////////////////
            $thumbnailPath = public_path('/upload/banner/thumbnail');
            $img = Image::make($getPhoto->path());
            $img->resize(350, 350, function ($constraint) {
                $constraint->aspectRatio();
            })->save($thumbnailPath.'/'.$filename);
            //////////////////////////////
            $destinationPath = public_path('/upload/banner/');
            $success = $getPhoto->move($destinationPath, $filename);        
            $BannerPic = $filename;
        } else {
            $BannerPic = $bannerInfo['banner_pic'];
        }

        $baseUrl = URL::to('');
        $bannerFullPath = 'public/upload/banner/'.$BannerPic;        
        $getStatus = $request->input('status');
        $totalActiveBanner = Banner::where('status','=',1)->sum('status');
        $status = ($totalActiveBanner >= 4) ? 0 : $getStatus;
        
        $Update = Banner::where('id',$update_id)->update([
            "banner_for"=>$request->input('banner_for'),
            "banner_pic"=>$BannerPic ? $BannerPic : 'no-image.png',
            "image_path"=>$bannerFullPath,
            "status"=>$status,
            "updated_at"=>Carbon::now()
        ]);

        if ($Update) {
            if ($totalActiveBanner >=4 && $status == 1) {
                return response()->json('warning');
            }            
            Log::info('Existing Banner Update Success');
            return response()->json('success');
        }
        Log::error('Existing Banner Update Failed');
        return response()->json('error');
    }
    
    public function destroy(Request $request) {
        $id = $request->id;
        $Success = Banner::find($id)->delete();
        if ($Success) {
            Log::info('Banner Remove Success');
            return response()->json('success');
        } else {
            Log::info('Banner Remove Failed');
            return response()->json('error');
        }
    }

    public function ChangeStatus($id) {
        $StatusInfo = Banner::find($id);
        $old_status = $StatusInfo->status;
        $UpdateStatus = $old_status == 1 ? 0 : 1;
        $totalActiveBanner = Banner::where('status','=',1)->sum('status');
        $status = 0;

        if ($totalActiveBanner < 4) {
            $Status = Banner::where('id',$id)->update([
                "status"=> $UpdateStatus ? $UpdateStatus:0
            ]);
            return response()->json(['success'=>'Status change successfully.']);
        } else if ($totalActiveBanner >= 4) {
            $changedStatus = 1;
            if ($UpdateStatus == 1) {
                return response()->json(['warning'=>'Status change Failed.Maximum 4 Banner Activated Allowed']);
            } else {
                $Status = Banner::where('id',$id)->update(["status"=>0]);
                return response()->json(['success'=>'Status change successfully.']);
            }
        } else  {
           return response()->json(['error'=>'Status Update Failed.Please Try Again.']);
        }
    }
}
