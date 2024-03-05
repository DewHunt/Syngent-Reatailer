<?php
if (!function_exists('isThisMenuActive')) {
	function isThisMenuActive($menuLink = '') {
        $result = \DB::table('menus')->where('menu_link','=',$menuLink)->first();
        if ($result) {
        	if ($result->status == 1 && $result->is_full_off == 0) {
        		return true;
        	} else {
        		return false;
        	}
        } else {
        	return false;
        }
	}
}

if (!function_exists('generate_random_string')) {
	function generate_random_string($length = 10, $dashed_number = 0, $string_type = 'all', $user_def_characters = '') {
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $random_string = '';

	    if ($string_type == 'num') { $characters = '0123456789'; }
	    else if ($string_type == 'up') { $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'; }
	    else if ($string_type == 'low') { $characters = 'abcdefghijklmnopqrstuvwxyz'; }
	    else if ($string_type == 'num-up') { $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'; }
	    else if ($string_type == 'num-low') { $characters = '0123456789abcdefghijklmnopqrstuvwxyz'; }
	    else if ($string_type == 'up-low') { $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'; }
	    else if ($string_type == 'user-def') { $characters = $user_def_characters; }

	    if ($dashed_number > 0) {
	        for ($i = 0; $i < $dashed_number; $i++) {
	            $random_string .= substr(str_shuffle(str_repeat($characters, ceil($length/strlen($characters)))),1,$length)."-";
	        }
	        $random_string .= substr(str_shuffle(str_repeat($characters, ceil($length/strlen($characters)))),1,$length);
	    } else {
	        $random_string = substr(str_shuffle(str_repeat($characters, ceil($length/strlen($characters)))),1,$length);
	    }
	    
	    return $random_string;
	}
}

if (!function_exists('getTableWhere')) { 
    function getTableWhere($table,$where) {
        $data = \DB::table($table)->select(\DB::raw('*'))->where($where)->first();
        return $data;
    }
}

if (!function_exists('_getTableWhere')) { 
    function _getTableWhere($table) {
        $data = \DB::table($table)->select(\DB::raw('*'));
        return $data;
    }
}

if (!function_exists('allPendingNotification')) {
	function allPendingNotification() {
        $month_Sdate = date('Y-m-01');
        $month_Edate = date('Y-m-t');        
		$total_pending_order = \DB::table('sales')
	        ->where('status',1)
	        ->whereBetween(\DB::raw("DATE_FORMAT(sale_date, '%Y-%m-%d')"),[$month_Sdate,$month_Edate])
	        ->count();

	    $total_pending_message = \DB::table('authority_messages as tab1')
	    	->select('tab1.*')
	    	->leftJoin('authority_messages as tab2','tab2.reply_for','=','tab1.id')
	    	->where('tab1.reply_for','=',0)
	    	->whereNull('tab2.reply_for')
	    	->orderBy('tab1.id','asc')
	    	->count();

        $total_dispute_imei = \DB::table('imei_disputes')
	        ->where('status',0)
	        ->whereBetween(\DB::raw("DATE_FORMAT(date, '%Y-%m-%d')"),[$month_Sdate,$month_Edate])
	        ->count();
        
        $total_pending_leave = \DB::table('bp_leaves')
	        ->whereBetween('start_date',[$month_Sdate,$month_Edate])
	        ->where('status','=','Pending')
	        ->count();

        $totalNotification = $total_pending_order + $total_pending_message + $total_dispute_imei + $total_pending_leave;
        $responseArray = ["totalNotification"=>$totalNotification,"pending_order"=>$total_pending_order,"pending_message"=>$total_pending_message,"dispute_imei"=>$total_dispute_imei,'pending_leave'=>$total_pending_leave];
		
		return $responseArray;
	}
}

if (!function_exists('GetTableWithPagination')) {
    function RequestApiUrl($api_url_name) {	
    	$url = "";	
    	if ($api_url_name == "EmployeeId"){
    		$url = "#";
    	} else if ($api_url_name == "DealerDistribution") {
    		$url = "#";
    	} else if ($api_url_name == "ProductAll") {
    		$url = "#";
    	} else if ($api_url_name == "ProducId" || $api_url_name == "ProducModel") {
    		$url = "#";
    	} else if ($api_url_name == "ZoneAll") {
    		$url = "#";
    	} else if ($api_url_name == "ZoneId") {
    		$url = "#";
    	} else if ($api_url_name == "RetailerAll") {
    		$url = "#";
    	} else if ($api_url_name == "RetailerId") {
    		$url = "#";
    	} else if ($api_url_name == "RetailerPhone") {
    		$url = "#";
    	} else if ($api_url_name == "DealerCode") {
    		$url = "#";
    	} else if ($api_url_name == "DealerAll") {
    		//$url = "#";
    		$url = "#";
    	} else if ($api_url_name == "BPromoterPhone") {
    		$url = "#"; //Api Ekhono Dei Nai.
    	} else if ($api_url_name == "GetRetailerStock") {
    		$url = "#";
    	} else if ($api_url_name == "GetRetailerLiftingIncentive") {
    		$url = "#";
    	} else if ($api_url_name == "GetStock") {
    		$url = "#";
    	} else if ($api_url_name == "GetIMEIinfo") {
    		$url = "#";
    	} else if ($api_url_name == "UpdateIMEIStatus") {
            $url = "#";
        } else if ($api_url_name == "orderFeedsArray") {
            $url = "#";
        } else if ($api_url_name == "GetBrandPromoter") {
            $url = "#";
        } else if ($api_url_name == "ReturnOrder") {
            $url = "#";
        } else if ($api_url_name == "SaveSale") {
            $url = "#";
        } else if ($api_url_name == "ImeiCheckWithDealerCode") {
            $url = "#";
        } else if ($api_url_name == "addBP") {
            $url = "#";
        }
    	return $url;
    }
}

if (!function_exists('GetTableWithPagination')) {
	function GetTableWithPagination($table_name,$limit) {
		$data = \DB::table($table_name)->paginate($limit);
		return $data;
	}
}

if (!function_exists('ViewTableListWhere')) {
	function ViewTableListWhere($table_name,$mobileNumber) {
		$data = \DB::table($table_name)
			->where('employee_phone',$mobileNumber)
			->orWhere('brand_promoter_phone',$mobileNumber)
	        ->orWhere('retailer_phone',$mobileNumber)
			->first();
		return $data;
	}
}

if (!function_exists('ViewTableList')) {
	function ViewTableList($table_name) {
		$data = \DB::table($table_name)
		->get();
		return $data;
	}
}

/*
* 200: This code is used for a successful request.
*
* 201: For a successful request and data was created.
*
* 204: For empty response.
*
* 400: This is used for Bad Request. If you enter something wrong or you missed some required parameters, 
* then the request would not be understood by the server, and you will get 400 status code.
*
* 401: This is used for Unauthorized Access. If the request authentication failed or the user does not 
* have permissions for the requested operations, then you will get a 401 status code.
*
* 403: This is for Forbidden or Access Denied.
*
* 404: This will come if the Data Not Found.
*
* 405: This will come if the method not allowed or if the requested method is not supported.
*
* 500: This code is used for Internal Server Error.
*
* 503: And this code is used for Service Unavailable.
*
* 301: When ime number not found.
*
* 302: Any Kind Of Custome Message.
*/

if (!function_exists('_apiResponses')) {
	function _apiResponses($responseCode,$object_result=null,$array_result=null,$errorMsg=null) {
		$response = "";	
		if ($responseCode == 200) {
			$response =['message'=>'success'];
		} else if ($responseCode == 201 && !empty($object_result)) {
			if ($array_result) {
				$response = [$object_result,"not found"=>$array_result];
			} else {
				$response = [$object_result];
			}			
		} else if ($responseCode == 203) {
            $response = ['message'=>$errorMsg,'code'=>203];
		} else if ($responseCode == 204) {
			$response = ['message'=>'success','code'=>204];
		} else if($responseCode == 400) {
			$response = ['message'=>'Bad Request','code'=>400];
		} else if($responseCode == 401) {
			$response = ['message'=>'Unauthorized Access','code'=>401];
		} else if($responseCode == 403) {
			$response = ['message'=>'This is for Forbidden or Access Denied','code'=>403];
		} else if($responseCode == 404) {
			$response = ['message'=>'Data Not Found','code'=>404];
		} else if($responseCode == 405) {
			$response = ['message'=>'Method not allowed','code'=>405];
		} else if($responseCode == 500) {
			$response = ['message'=>'Internal Server Error','code'=>500];
		} else if($responseCode == 503) {
			$response = ['message'=>'Service Unavailable','code'=>503];
		} else if($responseCode == 301 && !empty($object_result)) {
			$response =[
				'message'=>'IMEI Not Found.Please Contact Your Authority',
				'code'=>301,
				"not_found_ime"=>$object_result
			];
		} else if($responseCode == 302) {
			$response = ['message'=>$errorMsg,'code'=>302,];
		} else if($responseCode == 422) {
			$response = ['message'=>$errorMsg,'code'=>422,];
		}
		return $response;
	}
}

if (!function_exists('apiResponses')) {
	function apiResponses($responseCode,$errorMsg=null) {
		$responseArray = [
            ['code'=>100, 'message'=>'Continue'],
            ['code'=>101, 'message'=>'Switching Protocols'],
            ['code'=>102, 'message'=>'Processing'],
            ['code'=>200, 'message'=>'OK'],
            ['code'=>201, 'message'=>'Created'],
            ['code'=>202, 'message'=>'Accepted'],
            ['code'=>203, 'message'=>'Non-Authoritative Information'],
            ['code'=>204, 'message'=>'No Content'],
            ['code'=>205, 'message'=>'Reset Content'],
            ['code'=>206, 'message'=>'Partial Content'],
            ['code'=>207, 'message'=>'Multi-Status'],
            ['code'=>208, 'message'=>'Already Reported'],
            ['code'=>226, 'message'=>'IM Used'],
            ['code'=>300, 'message'=>'Multiple Choices'],
            ['code'=>301, 'message'=>'Moved Permanently'],
            ['code'=>302, 'message'=>'Found'],
            ['code'=>303, 'message'=>'See Other'],
            ['code'=>304, 'message'=>'Not Modified'],
            ['code'=>305, 'message'=>'Use Proxy'],
            ['code'=>307, 'message'=>'Temporary Redirect'],
            ['code'=>308, 'message'=>'Permanent Redirect'],
            ['code'=>400, 'message'=>'Bad Request'],
            ['code'=>401, 'message'=>'Unauthorized'],
            ['code'=>402, 'message'=>'Payment Required'],
            ['code'=>403, 'message'=>'Forbidden'],
            ['code'=>404, 'message'=>'Not Found'],
            ['code'=>405, 'message'=>'Method Not Allowed'],
            ['code'=>406, 'message'=>'Not Acceptable'],
            ['code'=>407, 'message'=>'Proxy Authentication Required'],
            ['code'=>408, 'message'=>'Request Timeout'],
            ['code'=>409, 'message'=>'Conflict'],
            ['code'=>410, 'message'=>'Gone'],
            ['code'=>411, 'message'=>'Length Required'],
            ['code'=>412, 'message'=>'Precondition Failed'],
            ['code'=>413, 'message'=>'Payload Too Large'],
            ['code'=>414, 'message'=>'URI Too Long'],
            ['code'=>415, 'message'=>'Unsupported Media Type'],
            ['code'=>416, 'message'=>'Range Not Satisfiable'],
            ['code'=>417, 'message'=>'Expectation Failed'],
            ['code'=>418, 'message'=>'I\'m a teapot'],
            ['code'=>421, 'message'=>'Misdirected Request'],
            ['code'=>422, 'message'=>'Unprocessable Entity'],
            ['code'=>423, 'message'=>'Locked'],
            ['code'=>424, 'message'=>'Failed Dependency'],
            ['code'=>425, 'message'=>'Reserved for WebDAV advanced collections expired proposal'],
            ['code'=>426, 'message'=>'Upgrade Required'],
            ['code'=>428, 'message'=>'Precondition Required'],
            ['code'=>429, 'message'=>'Too Many Requests'],
            ['code'=>431, 'message'=>'Request Header Fields Too Large'],
            ['code'=>451, 'message'=> 'Unavailable For Legal Reasons'],
            ['code'=>500, 'message'=>'Internal Server Error'],
            ['code'=>501, 'message'=>'Not Implemented'],
            ['code'=>502, 'message'=>'Bad Gateway'],
            ['code'=>503, 'message'=>'Service Unavailable'],
            ['code'=>504, 'message'=>'Gateway Timeout'],
            ['code'=>505, 'message'=>'HTTP Version Not Supported'],
            ['code'=>506, 'message'=>'Variant Also Negotiates'], 
            ['code'=>507, 'message'=>'Insufficient Storage'],
            ['code'=>508, 'message'=>'Loop Detected'],
            ['code'=>510, 'message'=>'Not Extended'],
            ['code'=>511, 'message'=>'Network Authentication Required']
        ];

        foreach ($responseArray as $response) {
            if ($response['code'] == $responseCode) {
	        	$message = $response['message'];
	        	if ($errorMsg) {
	        		$message = $errorMsg;
	        	}
	        	return $response = ['code'=>$responseCode,'message'=>$message];
            }
        }
	}
}

if (!function_exists('checkBPFocusModelStock')) {
	function checkBPFocusModelStock($modelName) {
		$data = \DB::table('bp_model_stocks')->where('model_name','like','%'.$modelName.'%')->first();
		return $data;
	}
}

if (!function_exists('checkModelStock')) {
	function checkModelStock($modelName) {
		//$data = \DB::table('product_masters')
		$data = \DB::table('view_product_master')->where('product_model','like','%'.$modelName.'%')->first();
		return $data;
	}
}

if (!function_exists('checkModelStockByBP')) {
	function checkModelStockByBP($groupId,$productMasterId,$modelName) {
		$data = \DB::table('bp_model_stocks')
		->where('bp_category_id','=',$groupId)
		->where('product_master_id','=',$productMasterId)
		->where('model_name','like','%'.$modelName.'%')
		->first();		
		return $data;
	}
}

if (!function_exists('getNameFirstMessageSender')) {
	function getNameFirstMessageSender($replyFor) {
		$firstSendMessageUserName = \DB::table('authority_messages')
		    ->select('reply_user_name','phone','zone')
            ->where('reply_for','=',$replyFor)
            ->orderBy('id','ASC')
            ->first();
		return $firstSendMessageUserName;
	}
}

if (!function_exists('getBpLeaveStatus')){
	function getBpLeaveStatus($bpId,$getDate) {
		$currentDate = ($getDate) ? $getDate : date('Y-m-d');
		$leaveStatus = \DB::table('bp_leaves')->where('bp_id','=',$bpId)->where('start_date',$currentDate)->value('status');
		return $leaveStatus;
	}
}

if (!function_exists('getIncentiveModels')) {
	function getIncentiveModels($modelArray) {
		$getModel = json_decode($modelArray);
		$getModelArray = [];
		foreach ($getModel as $key=>$value) {
			if ($value !='all') {
				$modelName = \DB::table('product_masters')
					->select('product_model')
		            ->where('product_master_id','=',$value)
		            ->value('product_model');
				array_push($getModelArray, $modelName);
			} else {
				array_push($getModelArray, $value);
			}
		}

		$modelNames = implode(',',$getModelArray);
		return $modelNames;
	}
}

if (!function_exists('getIncentiveZones')) {
	function getIncentiveZones($zoneArray) {
		$getZone = json_decode($zoneArray);
		$getZoneArray = [];
		foreach ($getZone as $key=>$value) {
			if ($value !='all') {
				$zoneName = \DB::table('zones')->select('zone_name')->where('id','=',$value)->value('zone_name');
				array_push($getZoneArray, $zoneName);
			} else {
				array_push($getZoneArray, $value);
			}
		}
		$zoneNames = implode(',',$getZoneArray);
		return $zoneNames;
	}
}

if (!function_exists('getIncentiveGroups')) {
	function getIncentiveGroups($groupId) {		
		$groupNames = \DB::table('bp_retailer_categories')->select('name')->where('id','=',$groupId)->value('name');
		return $groupNames;
	}
}

if (!function_exists('getSyngentaEmpId')) {
	function getSyngentaEmpId($userId) {
		$empId = \DB::table('employees')->select('employee_id')->where('id','=',$userId)->value('employee_id');
		return $empId;
	}
}

if (!function_exists('getPasswordStatus')) {
	function getPasswordStatus($userType,$userId) {
		$fieldName = ($userType == "bp") ? "bp_id":"retailer_id";		
		$passwordStatus = \DB::table('users')->select($fieldName,'password')->where($fieldName,'=',$userId)->value('password');
		if (!empty($passwordStatus) && $passwordStatus != null) {
			return 1;
		}
		return 0;
	}
}