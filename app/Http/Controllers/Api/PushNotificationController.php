<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\PreBookin;
use App\Models\Colors;
use App\Models\Products;
use App\Models\ProductMasterPrice;
use App\Models\User;
use App\Models\PushNotification;
use App\Models\BrandPromoter;
use App\Models\Retailer;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\URL;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use DB;
use Validator;
use Mail;
use Response;

class PushNotificationController extends Controller
{    
    public function index(Request $request) {
        $zoneList = Zone::get();
        $notification_list = "";

        if ($request->ajax()) {
            $sort_by = $request->get('sortby');
            $sort_type = $request->get('sorttype');
            $query = $request->get('query');
            $query = str_replace(" ", "%", $query);

            $notification_list = PushNotification::where(function($sql_query) use($query) {
                if (!empty($query)) {
                    $sql_query->where('title','like', '%'.$query.'%')->orWhere('message','like', '%'.$query.'%');
                }
            })
            ->orderBy($sort_by, $sort_type)
            ->paginate(100);
            return view('admin.notification.result_data', compact('notification_list','zoneList'))->render();
        } else {
            $notification_list  = PushNotification::paginate(100);
        }

        if (isset($notification_list) && $notification_list->isNotEmpty()) {
            Log::info('Load Push Notification List');
        } else {
            Log::warning('Push Notification List Not Found');
        }
        return view('admin.notification.list',compact('notification_list','zoneList'));
    }

    
    public function create() {
        //
    }
    
    public function store(Request $request) {
        $rules = ['title'=>'required','message'=>'required',];
        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            Log::error('Push Notification Validation error');
            return response()->json(['fail'=>true,'errors'=>$validator->errors()]);
        }

        $notificationZone = json_encode($request->input('zone'));
        $notificationGroup = json_encode($request->input('message_group'));
        $status = 0;
        $title = $request->input('title');
        $CheckInfo = PushNotification::where('title',$title)->first();
        if ($CheckInfo) {
            $UpdateInfo = PushNotification::where('title',$title)->update([
                "title"=>$request->input('title'),
                "message"=> $request->input('message'),
                "zone"=>$notificationZone,
                "message_group"=>$notificationGroup,
                "status"=>$request->input('status'),
                "date"=>date("Y-m-d"),
                "updated_at"=>Carbon::now()
            ]);
            if ($AddInfo) {
                $status = 1;
            }
            Log::info('Existing Push Notification Updated');    
        } else {
            $AddInfo = PushNotification::create([
                "title"=>$request->input('title'),
                "message"=> $request->input('message'),
                "zone"=>$notificationZone,
                "message_group"=>$notificationGroup,
                "status"=> $request->input('status'),
                "date"=> date("Y-m-d"),
                "created_at"=> Carbon::now(),
                "updated_at"=> Carbon::now()
            ]);

            if ($AddInfo) {
                $status = 1;
            }
            Log::info('Create Push Notification'); 
        }

        if ($status == 1) {
            return response()->json('success');
        } else {
            return response()->json('error');
        }
    }
    
    public function show($id) {
        if (isset($id) && $id > 0) {
            $PushNotificationInfo = PushNotification::where('id',$id)->first();
            if ($PushNotificationInfo) {
                Log::info('Get Push Notificatin By Id');
                return response()->json($PushNotificationInfo); 
            } else {
                Log::warning('Push Notificatin Not Found By Id');
                return response()->json('error');
            }
        } else {
            Log::warning('Invalid Push Notificatin Id');
            return response()->json('error');
        }
    }
    
    public function edit($id) {
        if (isset($id) && $id > 0) {
            $PushNotificationInfo = PushNotification::where('id',$id)->first();
            if ($PushNotificationInfo) {
                $html = view('admin.notification.edit_form')->with(compact('PushNotificationInfo'))->render();
                Log::info('Get Push Notificatin By Id');
                return response()->json($html); 
            } else {
                Log::warning('Push Notificatin Not Found By Id');
                return response()->json('error');
            }
        } else {
            Log::warning('Invalid Push Notificatin Id');
            return response()->json('error');
        }
    }
    
    public function update(Request $request) {
        $id = $request->input('update_id');
        $rules = ['title'=>'required','message'=>'required'];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            Log::error('Push Notificatin Update Validation Failed');
            return response()->json(['fail'=>true,'errors'=>$validator->errors()]);
        }

        $notificationZone = json_encode($request->input('zone'));
        $notificationCategory = json_encode($request->input('category'));
        $notificationGroup = json_encode($request->input('message_group'));

        $CheckInfo = PushNotification::where('id',$id)->first();
        if (isset($CheckInfo) && !empty($CheckInfo)) {
            $UpdateInfo = PushNotification::where('id',$id)->update([
                "title"=>$request->input('title'),
                "message"=> $request->input('message'),
                "zone"=>$notificationZone,
                "category"=>$notificationCategory,
                "message_group"=>$notificationGroup,
                "status"=> $request->input('status'),
                "updated_at"=> Carbon::now()
            ]);

            if ($UpdateInfo) {
                Log::info('Existing Push Notificatin Update');
                return response()->json('success'); 
            } else {
                Log::warning('Existing Push Notificatin Update Failed');
                return response()->json('error');
            }
        } else {
            Log::warning('Existing Push Notificatin Update Failed');
            return response()->json('error');
        }
    }

    public function destroy($id) {
        //
    }

    public function ChangeStatus($id) {
        if (isset($id) && $id > 0) {
            $StatusInfo = PushNotification::where('id',$id)->first();
            $old_status = $StatusInfo->status;
            $UpdateStatus = $old_status == 1 ? 0 : 1;

            $UpdateNotificationStatus = PushNotification::where('id',$id)->update([
                "status"=> $UpdateStatus ? $UpdateStatus:0
            ]);

            if ($UpdateNotificationStatus) {
                Log::info('Push Notification Status Update Success');
                return response()->json('success');
            } else {
                Log::error('Push Notification Status Update Failed');
                return response()->json('error');
            }
        } else {
            Log::error('Invalid Push Notification Id');
            return response()->json('error');
        }
    }

    public function storeToken(Request $request) {
        auth()->user()->update(['device_key'=>$request->token]);
        return response()->json(['Token successfully stored.']);
    }

    public function sendWebNotification(Request $request) {
        $sendId = $request->input('send_id');
        $url = 'https://fcm.googleapis.com/fcm/send';
        $FcmToken = DB::table('device_registrations')->whereNotNull('registration_id')->pluck('registration_id')->all();
        $serverKey = 'BKWnFLTRgmXhJKdFkgrOeX8r6CpAmfluCdcOcvWV84MX6Fm03abFHqSS_Oe4lEGvV6TCjj8dj4qsuWQrCTtyzTw';
  
        $data = [
            "registration_ids" => $FcmToken,
            "notification" => ["title" => $request->title,"body" => $request->body]
        ];
        $encodedData = json_encode($data);
        $headers = ['Authorization:key=' . $serverKey,'Content-Type: application/json',];

        $ch = curl_init();      
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);        
        curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);

        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }        
        // Close connection
        curl_close($ch);
        // FCM response
        $getResult = json_decode($result);
        
        if ($result) {
            PushNotification::where('id',$sendId)->update(["send_status"=> 1]);
            return response()->json(['success'=>$getResult->success,'failure'=>$getResult->failure]);
        } else {
            return response()->json('error');
        }
    }
}
