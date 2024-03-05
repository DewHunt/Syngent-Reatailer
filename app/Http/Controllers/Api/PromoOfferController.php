<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Log;
use App\Models\PromoOffer;
use App\Models\BrandPromoter;
use App\Models\Retailer;
use App\Models\Zone;
use Carbon\Carbon;
use DB;
use Validator;
use Response;
use Image;
use Config;
class PromoOfferController extends Controller
{    
    public function index(Request $request) {        
        $zoneList = Zone::get();
        $offerList = GetTableWithPagination('promo_offers',100);

        if (isset($offerList) && $offerList->isNotEmpty()) {
            Log::info('Load Promo Offer List');
        } else {
            Log::warning('Promo Offer List Not Found');
        }
        return view('admin.offer.list',compact('offerList','zoneList'));
    }

    public function create() {
        $retailerList = Retailer::get(['retailer_id','retailer_name','phone_number']);
        $zoneList = Zone::get();
        return view('admin.offer.list',compact('zoneList'));
    }

    public function addOffer(Request $request) {
        // dd($request->all());
        $rules = [
            'offer_for'=>'required',
            'offer_pic'=>'required|image|mimes:jpeg,png,jpg,gif,svg|max:512',
            'sdate'=>'required',
            'edate'=>'required',
            'status'=>'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            Log::error('Promo Offer Validation Failed');
            return Response::json(['errors' => $validator->errors()]);
        }

        $OfferPic = "";
        if ($request->hasFile('offer_pic')) {
            $getPhoto = $request->file('offer_pic');
            $filename = time().'.'.$getPhoto->getClientOriginalExtension();
            //////////////////////////////
            $thumbnailPath = public_path('/upload/offer-thumbnail');
            $img = Image::make($getPhoto->path());
            $img->resize(250, 200, function ($constraint) {
                $constraint->aspectRatio();
            })->save($thumbnailPath.'/'.$filename);
            //////////////////////////////
            $destinationPath = public_path('/upload');
            $success = $getPhoto->move($destinationPath, $filename);
        
            $OfferPic = $filename;
        }

        $baseUrl = Config::get('app.url');
        $offerPicPath = $baseUrl.'public/upload/'.$OfferPic;

        $zoneId = $request->input('zone');

        $addOffer = PromoOffer::create([
            "offer_for"=>$request->input('offer_for'),
            "title" => $request->input('title'),
            "zone"=> $zoneId ? json_encode($zoneId,JSON_FORCE_OBJECT):"",
            "sdate"=> $request->input('sdate'), //$form_date,
            "edate"=> $request->input('edate'), //$to_date,
            "offer_pic"=>$offerPicPath ? $offerPicPath : $OfferPic,
            "photo"=>$OfferPic ? $OfferPic : 'no-image.png',
            "status"=>$request->input('status'),
            "created_at"=>Carbon::now(),
            "updated_at"=>Carbon::now()
        ]);

        if ($addOffer) {
            Log::info('Create Promo Offer Success');
            return response()->json('success');
        } else {
            Log::error('Create Promo Offer Failed');
            return response()->json('error');
        }
    }

    public function editOffer($id) {
        if (isset($id) && $id > 0) {
            $editOfferInfo = PromoOffer::where('id',$id)->first();
            $ZoneList = json_decode($editOfferInfo['zone']);
            $zoneIdList = [];
            if (!empty($ZoneList)) {
                foreach ($ZoneList as $key=>$val) {
                    $zoneIdList[] = $val;
                }
            }
            $zoneList = Zone::get();
            $html = view('admin.offer.edit_form')->with(compact('editOfferInfo','zoneIdList','zoneList'))->render();

            Log::info('Get Promo Offer By Id');
            // return response()->json(['offerInfo'=>$editOfferInfo,'zoneIdList'=>$zoneIdList,'zoneList'=>$zoneList]);
            return response()->json($html);
        } else {
            Log::warning('Invalid Promo Offer Id');
            return response()->json('error');
        } 
    }

    public function updateOffer(Request $request) {
        $update_id = $request->input('update_id');
        $rules = [
            'offer_for'=>'required',
            'sdate'=>'required',
            'edate'=>'required',
            'status'=>'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            Log::error('Promo Offer Update Validation Failed');
            return Response::json(['errors' => $validator->errors()]);
        }

        $offerInfo = PromoOffer::where('id',$update_id)->first();
        $zoneList = "";
        if (!empty($request->input('zone'))) {
            $zoneList = json_encode($request->input('zone'),JSON_FORCE_OBJECT);
        } else {
            $zoneList = $offerInfo['zone'];
        }

        $offerPicPath = "";
        $OfferPhoto   = "";

        if ($request->hasFile('offer_pic')) {
            $getPhoto = $request->file('offer_pic');
            $filename = time().'.'.$getPhoto->getClientOriginalExtension();

            //////////////////////////////
            $thumbnailPath = public_path('/upload/offer-thumbnail');
            $img = Image::make($getPhoto->path());
            $img->resize(250, 200, function ($constraint) {
            $constraint->aspectRatio();
            })->save($thumbnailPath.'/'.$filename);
            //////////////////////////////

            $destinationPath = public_path('/upload');
            $success = $getPhoto->move($destinationPath, $filename);
            $OfferNewPic = $filename;
            $baseUrl = Config::get('app.url');
            $offerPicPath = $baseUrl.'public/upload/'.$OfferNewPic;
            $OfferPhoto = $OfferNewPic;
        } else {
            $offerPicPath = $offerInfo['offer_pic'];
            $OfferPhoto = $offerInfo['photo'];
        }        

        $Update = PromoOffer::where('id',$update_id)->update([
            "offer_for"=>$request->input('offer_for'),
            "title" => $request->input('title'),
            "zone"=> $zoneList,
            "sdate"=> $request->input('sdate'), //$form_date,
            "edate"=> $request->input('edate'), //$to_date,
            "offer_pic"=>$offerPicPath,
            "photo"=>$OfferPhoto,
            "status"=>$request->input('status'),
            "updated_at"=>Carbon::now()
        ]);

        if ($Update) {
            Log::info('Existing Promo Offer Update Success');
            return response()->json('success');
        }
        Log::error('Existing Promo Offer Update Failed');
        return response()->json('error');
    }

    public function ChangeStatus($id) {
        if (isset($id) && $id > 0) {
            $StatusInfo = PromoOffer::find($id);
            $old_status = $StatusInfo->status;
            $UpdateStatus = $old_status == 1 ? 0 : 1;
            $Status = PromoOffer::where('id',$id)->update([
                "status"=> $UpdateStatus ? $UpdateStatus:0
            ]);

            if ($Status) {
                Log::info('Promo Offer Status Changed Success');
                return response()->json(['success'=>'Status change successfully.']);
            } else {
                Log::error('Promo Offer Status Changed Failed');
                return response()->json(['error'=>'Status Update Failed.Please Try Again.']);
            }
        } else {
            Log::warning('Invalid Promo Offer Id');
            return response()->json('error');
        }
    }

    public function destroy($id) {
        $Success = PromoOffer::find($id)->delete();
        if ($Success) { 
            Log::info('Promo Offer Remove Successfully'); 
            return redirect()->back()->with('success','Deleted Successfully');
        } else {
            Log::info('Promo Offer Remove Failed');
            return redirect()->back()->with('error','Deleted Failed');
        }
    }
}
