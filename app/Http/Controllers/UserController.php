<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Employee;
use App\Models\BrandPromoter;
use App\Models\Retailer;
use App\Models\Menu;
use Cache;
use Carbon\Carbon;
use DB;
use Validator;
use Session;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $GetUser =  DB::table('users')->paginate(100); //GetTableWithPagination('users',10);
        if($request->ajax()) {
            $sort_by      = $request->get('sortby');
            $sort_type    = $request->get('sorttype');
            $query        = $request->get('query');
            $query        = str_replace(" ", "%", $query);

             $GetUser = DB::table('users')
                ->where('id',$query)
                ->orWhere('name','like', '%'.$query.'%')
                ->orWhere('employee_id','like', '%'.$query.'%')
                ->orWhere('email','like', '%'.$query.'%')
                ->orderBy($sort_by, $sort_type)
                ->paginate(100);
            return view('admin.user.result_data', compact('GetUser'))->render();
        }
        return view('admin.user.list',compact('GetUser'));
    }

    public function GetUserList(Request $request)
    {
        $empList = Employee::get(['id','name','employee_id']);
    	$GetUser = DB::table('view_check_login_user')
        ->where('employee_id','>',0)
        ->orderBy('name','ASC')
        ->get();
        
    	if ($request->ajax()) {
            return view('admin.user.result_data', compact('GetUser','empList'));
        }
    	return view ('admin.user.list',compact('GetUser','empList'));
    }

    public function CreateUser(Request $request)
    {
        $rules = [
            //'name'=>'required',
            //'email'=>'required',
            'password'=>'required|confirmed|min:5',
            'password_confirmation'=>'required_with:password|same:password|min:5'
        ];

        $validator = Validator::make($request->all(), $rules);
        
        if($validator->fails())
        return response()->json([
            'fail'=>true,
            'errors'=>$validator->errors()
        ]);
        
        $user           = Auth::user();
		$authUserName   = $user->name;

    	$name        = $request->input('name');
        $email       = $request->input('email');
        $password    = $request->input('password');
        $empId       = $request->input('employee_id');
        $status      = $request->input('status');
        
        $checkEmpInfo = Employee::where('id','=',$empId)->first();
        $CheckUser    = User::where('email',$email)->first();
        $phone		  = $checkEmpInfo->mobile_number;

        if($CheckUser)
        {
            $updateUser = User::where('id',$CheckUser['id'])
            ->update([
                "employee_id"=>$empId ? $empId:$CheckUser['employee_id'],
                "name"=>$name,
                "email"=>$email,
                "password"=>Hash::make($password),
                "status"=>$status,
                "author"=>$authUserName,
                "updated_at"=>Carbon::now()
            ]);
            
            if($updateUser)
			{
				$messageBody = "HI, ".$name.". Your Password Has Reset.User Name=".$phone." Password=".$password." Thanks, Syngenta";
				$requestData = array(
					'mobileNumber' => $phone,
					'message' =>$messageBody,
				);
				$postRequestData    = json_encode($requestData);
				
				$this->sms_send($postRequestData);
			}
			Log::info('Employee Password Set Successfully-(Employee ID-'.$empId.')');
            return response()->json('success');
        } 
        else 
        {
            $AddUser = User::create([
                "employee_id"=>0,
                "name"=>$name,
                "email"=>$email,
                "password"=>Hash::make($password),
                "status"=>$status,
                "author"=>$authUserName,
                "created_at"=>Carbon::now(),
                "updated_at"=>Carbon::now()
            ]);

            $id = DB::getPdo()->lastInsertId();

            $updateUser = User::where('id',$id)
            ->update([
                "employee_id"=>$id,
                "updated_at"=>Carbon::now()
            ]);
            
            if($updateUser)
			{
				$messageBody = "HI, ".$name.". Your Password Has Reset.User Name=".$phone." Password=".$password." Thanks, Syngenta";
				$requestData = array(
					'mobileNumber' => $phone,
					'message' =>$messageBody,
				);
				$postRequestData    = json_encode($requestData);
				
				$this->sms_send($postRequestData);
			}
			Log::info('Employee Password Set Successfully-(Employee ID-'.$empId.')');
            return response()->json('success');
        }
    }

    public function edit($id)
    {
    	$empList   = Employee::where('status',1)->get(['id','name']);
        $ShowUser  = DB::table('users')->where('id',$id)->first();

    	return response()->json($ShowUser);
    }
    
    public function update(Request $request)
    {
        $newPassword    = $request->input('password');
        
        if(!empty($newPassword)){
            $rules = [
                'name'=>'required',
                //'email'=>'required',
                'password'=>'required|confirmed|min:5',
                'password_confirmation'=>'required_with:password|same:password|min:5'
            ];
            $validator = Validator::make($request->all(), $rules);
            
            if($validator->fails())
            return response()->json([
                'fail'=>true,
                'errors'=>$validator->errors()
            ]);
        }

        $rules = [
            'name'=>'required',
            //'email'=>'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        
        if($validator->fails())
        return response()->json([
            'fail'=>true,
            'errors'=>$validator->errors()
        ]);
        
        $user           = Auth::user();
		$authUserName   = $user->name;

    	$name           = $request->input('name');
        $email          = $request->input('email');
        $oldPassword    = $request->input('old_password');
        $employeeId     = $request->input('update_employee_id');
        $status         = $request->input('status');
        $updateId       = $request->input('update_id');
        
        $checkEmpInfo 	= Employee::where('id','=',$employeeId)->first();
		$phone 			= $checkEmpInfo->mobile_number;


        if($newPassword)
        {
            $updateUser = DB::table('users')
            ->where('id',$updateId)
            ->update([
                "name"=>$name,
                "email"=>$email,
                "password"=>Hash::make($newPassword),
                "status"=>$status,
                "author"=>$authUserName,
                "updated_at"=>Carbon::now()
            ]);
            
            if($updateUser)
			{
				$messageBody = "HI, ".$name.". Your Password Has Reset.User Name=".$phone." Password=".$password." Thanks, Syngenta";
				$requestData = array(
					'mobileNumber' => $phone,
					'message' =>$messageBody,
				);
				$postRequestData    = json_encode($requestData);
				
				$this->sms_send($postRequestData);
			}
			
			Log::info('Employee Password Update Successfully-(Employee ID-'.$employeeId.')');
            return response()->json('user-success');
        }
        else
        {
            $updateUser = DB::table('users')
            ->where('id',$updateId)
            ->update([
                "name"=>$name,
                "email"=>$email,
                "status"=>$status,
                "author"=>$authUserName,
                "updated_at"=>Carbon::now()
            ]);
            Log::info('Employee Info Update Successfully-(Employee ID-'.$employeeId.')');
            return response()->json('user-success');
        }
        return response()->json('error');
    }

    public function show()
    {
        $UserStatus = User::all();
        return view('admin.status', compact('UserStatus'));
    }
    
    public function getUserProfile($id)
    {
        $UserProfileInfo   = DB::table('users')->where('id',$id)->first();
        return view('admin/user/profile',compact('UserProfileInfo'));
    }

    public function userProfileUpdate(Request $request)
    {
		$user           = Auth::user();
		$authUserName   = $user->name;
		
		$name        = $request->input('name');
        $email       = $request->input('email');
        $password    = $request->input('password');
		$userId      = $request->input('user_id');

        $rules = [
            'name'=>'required',
            //'email'=>'required'
        ];
        $validator = Validator::make($request->all(), $rules);
        
        if($validator->fails())
        return redirect()->back()->with('errors',$validator->errors());

        if(isset($password) && !empty($password)) {
            $rules = [
                'password'=>'required|confirmed|min:5',
                'password_confirmation'=>'required_with:password|same:password|min:5'
            ];
            $validator = Validator::make($request->all(), $rules);
        
            if($validator->fails())
            return redirect()->back()->with('errors',$validator->errors());
        }

        $CheckUser   = User::where('id','=',$userId)->first();

        if($CheckUser)
        {
            $checkEmpInfo 	= Employee::where('id','=',$CheckUser['employee_id'])->first();
			$phone 			= $checkEmpInfo->mobile_number;
			
			$status = 0;
            if(isset($password) && !empty($password)) {
                $updateUser = User::where('id',$CheckUser['id'])
                ->update([
                    "name"=>$name,
                    "email"=>$email,
                    "password"=>Hash::make($password),
                    "author"=>$authUserName,
                    "updated_at"=>Carbon::now()
                ]);
				
				if($updateUser)
				{
					$messageBody = "HI, ".$name.". Your Password Has Reset.User Name=".$phone." Password=".$password." Thanks, Syngenta";
					$requestData = array(
						'mobileNumber' => $phone,
						'message' =>$messageBody,
					);
					$postRequestData    = json_encode($requestData);
					
					$this->sms_send($postRequestData);
				}
				
				Log::info('Employee Password Update Successfully-(Employee ID-'.$CheckUser['employee_id'].')');
                $status = 1;
            } else {
                $updateUser = User::where('id',$CheckUser['id'])
                ->update([
                    "name"=>$name,
                    "email"=>$email,
                    "author"=>$authUserName,
                    "updated_at"=>Carbon::now()
                ]);
				Log::info('Employee Info Update Successfully');
                $status = 1;
            }

            if($status == 1) {
                return redirect()->back()->with('success','User Profile Update Successfully');
            } else {
                return redirect()->back()->with('error','User Profile Update Failed.Please Try Again');
            }
        }
        return redirect()->back()->with('error','User Profile Update Failed.Please Try Again');
    }
    
    public function getUserLog(Request $request)
    {
        Session::forget('searchSdate');
        Session::forget('searchEdate');
        
        $month_Sdate = date('Y-m-01');
        $month_Edate = date('Y-m-t');

        $loginLogList = "";
        if($request->ajax()) 
        {
            $sort_by      = $request->get('sortby');
            $sort_type    = $request->get('sorttype');
            $query        = $request->get('query');
            $query        = str_replace(" ", "%", $query);
            $searchVal    = str_replace(" ", "%", $query);

            $loginLogList = DB::table('view_user_login_activity')
            ->whereBetween(\DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"),[$month_Sdate,$month_Edate])
            ->where(function($sql_query) use($searchVal) {
                if(!empty($searchVal) && $searchVal !=null) {
                    $sql_query->where('name', 'like', '%'.$searchVal.'%')
                    ->orWhere('type','like', '%'.$searchVal.'%')
                    ->orWhere('ip_address', 'like', '%'.$searchVal.'%');
                }

            })
            ->orderBy('created_at','desc')
            ->paginate(100);

            return view('admin.log.result_data', compact('loginLogList'))->render();
        }
        else
        {
            $loginLogList = DB::table('view_user_login_activity') //'login_activities as lc'
            ->whereBetween(\DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"),[$month_Sdate,$month_Edate])
            ->orderBy('created_at','desc')
            ->paginate(100);
        }
        return view('admin.log.list',compact('loginLogList'));
    }

    public function getSearchUserLog(Request $request)
    {
        $searchSdate = ($request->input('start_date')) ? $request->input('start_date'):date('Y-m-01');
        $searchEdate = ($request->input('end_date')) ? $request->input('end_date') : date('Y-m-d');


        Session::put('searchSdate',$searchSdate);
        Session::put('searchEdate',$searchEdate);

        $loginLogList = "";

        if($request->ajax()) 
        {
            $sort_by      = $request->get('sortby');
            $sort_type    = $request->get('sorttype');
            $query        = $request->get('query');
            $query        = str_replace(" ", "%", $query);
            $searchVal    = str_replace(" ", "%", $query);

            $loginLogList = DB::table('view_user_login_activity')
            ->whereBetween(\DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"),[$searchSdate,$searchEdate])
            ->where(function($sql_query) use($searchVal) {
                if(!empty($searchVal) && $searchVal !=null) {
                    $sql_query->where('name', 'like', '%'.$searchVal.'%')
                    ->orWhere('type','like', '%'.$searchVal.'%')
                    ->orWhere('user_agent','like', '%'.$searchVal.'%')
                    ->orWhere('ip_address', 'like', '%'.$searchVal.'%');
                }

            })
            ->orderBy('created_at','desc')
            ->paginate(100);

            return view('admin.log.result_data', compact('loginLogList'))->render();
        }
        else
        {
            $loginLogList = DB::table('view_user_login_activity')
            ->whereBetween(DB::RAW("DATE_FORMAT(created_at,'%Y-%m-%d')"),[$searchSdate,$searchEdate])
            ->orderBy('created_at','DESC')
            ->paginate(100);
        }
        return view('admin.log.list',compact('loginLogList'));
    }

    public function ChangeStatus($id) 
    {
        if(isset($id) && $id > 0) {
            $StatusInfo = User::find($id);
            $empId      = $StatusInfo->employee_id;
            $bpId       = $StatusInfo->bp_id;
            $retailerId = $StatusInfo->retailer_id;

            $old_status = $StatusInfo->status;

            $UpdateStatus = $old_status == 1 ? 0 : 1;
            $UpdateUserStatus = User::where('id',$id)
            ->update([
                "status"=> $UpdateStatus ? $UpdateStatus:0
            ]);

            if($UpdateUserStatus) {

                if(isset($empId) && $empId > 0) {
                    Employee::where('id',$empId)
                    ->update([
                        "status"=> $UpdateStatus ? $UpdateStatus:0
                    ]);
                }

                if(isset($bpId) && $bpId > 0) {
                    BrandPromoter::where('id',$bpId)
                    ->update([
                        "status"=> $UpdateStatus ? $UpdateStatus:0
                    ]);
                }

                if(isset($retailerId) && $retailerId > 0) {
                    Retailer::where('id',$retailerId)
                    ->update([
                        "status"=> $UpdateStatus ? $UpdateStatus:0
                    ]);
                }

                Log::info('User Status Changed Successfully');
                return response()->json(['success'=>'Status change successfully.']);
            } else {
                Log::error('User Status Changed Failed');
                return response()->json(['error'=>'Status Update Failed.Please Try Again.']);
            }
        } else {
            Log::error('User Status Changed Failed');
            return response()->json(['error'=>'Status Update Failed.Please Try Again.']);
        }
    }

    public function menuPermission($userId) {
        $parentMenus = Menu::where('status',1)
            ->whereNull('parent_menu')
            ->where('is_full_off','=',0)
            ->orderBy('id','ASC')
            ->get(['id','parent_menu','menu_name','menu_link']);

        $childMenus = Menu::where('status',1)
            ->whereNotNull('parent_menu')
            ->where('is_full_off','=',0)
            ->orderBy('id','ASC')
            ->get(['id','parent_menu','menu_name','menu_link']);

        $userInfo = User::where('id',$userId)->first();
        //$userRole   = UserRole::where('id',$userInfo->user_role_id)->first();

        //$getPermissionMenuId = explode(",",$userInfo['permission_menu_id']));
        //print_r($getPermissionMenuId);

        return view('admin.user.permission')->with(compact('parentMenus','childMenus','userInfo'));
    }

    public function userMenuPermissionSave(Request $request) {
        if (!empty($request->input('permission_menu_id'))) {
            $userId = $request->input('user_id');
            $menuId = implode(',',$request->input('permission_menu_id'));
            $status = User::where('id','=',$userId)->update([
                "permission_menu_id"=>$menuId,
                "updated_at"=>Carbon::now()
            ]);

            if ($status) {
                return redirect()->back()->with('success','User Permission Assigned Successfully');
            }
            return redirect()->back()->with('error','User Permission Assigned Failed');
        } else {
            return redirect()->back()->with('error','Menu Not Selected');
        }
    }
    
    public function sms_send($postRequestData) {
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