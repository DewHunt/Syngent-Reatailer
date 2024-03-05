<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DealerInformation;
use App\Models\BrandPromoter;
use App\Models\Retailer;
use App\Models\Zone;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use DB;
use Validator;
use DataTables;
use Response;

class GeneralSettingController extends Controller
{
    public function get_user_by_group($user)
    {
        $upid           = mt_rand(0,2);
        $dataFormat     = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz';
        $uniquePassword =  substr(str_shuffle($dataFormat), 0, 8);

        if($user == "bp") {
            $bpLists = BrandPromoter::select("id","bp_name","bp_phone")
            ->where('status','=',1)
            ->get();

            if($bpLists->isNotEmpty()) {
                foreach($bpLists as $bp) {
                    $setArray   = [
                        "id"=>$bp->id,
                        "name"=>$bp->bp_name,
                        "phone"=>$bp->bp_phone,
                        "email"=>str_replace(' ', '',strtolower($bp->bp_phone.$upid.'@example.com')),
                        "password"=>strtolower($uniquePassword),
                        "user_type"=>"bp"
                    ];

                    $this->user_password_set($setArray);
                }
            }
        }
        else if($user == "retailer") {
            $retailerLists = Retailer::select("id","retailer_name","phone_number")
            ->where('status','=',1)
            ->get();

            if($retailerLists->isNotEmpty()) {
                foreach($retailerLists as $retailer) {
                    $setArray   = [
                        "id"=>$retailer->id,
                        "name"=>$retailer->retailer_name,
                        "phone"=>$retailer->phone_number,
                        "email"=>str_replace(' ', '',strtolower($retailer->phone_number.$upid.'@example.com')),
                        "password"=>strtolower($uniquePassword),
                        "user_type"=>"retailer"
                    ];

                    $this->user_password_set($setArray);
                }
            }
        }
    }

    public function user_password_set($setArray)
    {
        $id         = $setArray['id'];
        $name       = $setArray['name'];
        $phone      = $setArray['phone'];
        $email      = $setArray['email'];
        $password   = $setArray['password'];
        $userType   = $setArray['user_type'];


        $messageBody = "HI, ".$name.". Your Password Has Reset.User Name=".$phone." Password=".$password." Thanks, Syngenta";
        $requestData = array(
            'mobileNumber' => $phone,
            'message' =>$messageBody,
        );

        $postRequestData    = json_encode($requestData);
        $where_field        = ($userType == 'bp') ? "bp_id":"retailer_id";
        $checkUser          = User::where($where_field,'=',$id)->first();

        $status = 0;

        if($checkUser) {

            $passwordUpdateStatus = User::where($where_field,'=',$id)
            ->update([
                'name'=>$name,
                //'email'=>$email,
                'password' => Hash::make($password),
                'status'=>1,
                "updated_at"=>Carbon::now()
            ]);
            $status = 1;
        } else {
            $passwordUpdateStatus = User::create([
                $where_field=>$id,
                'name'=>$name,
                //'email'=>$email,
                'password' => Hash::make($password),
                'status'=>1,
                "updated_at"=>Carbon::now()
            ]);
            $status = 1;
        }

        if($status == 1) {
            $this->sms_send($postRequestData);
        }
    }

    public function sms_send($postRequestData)
    {
        $cURLConnection = curl_init('#');
        curl_setopt($cURLConnection, CURLOPT_POSTFIELDS, $postRequestData);
        curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cURLConnection, CURLOPT_HTTPHEADER, array(
        // Set Here Your Requesred Headers
        'Content-Type: application/json',
        'AppApiKey:18197:mostafiz',
        ));
        $apiResponse = curl_exec($cURLConnection);
        curl_close($cURLConnection);

        // $apiResponse - available data from the API request
        $jsonArrayResponse = json_decode($apiResponse);
    }
}
