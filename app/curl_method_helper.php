<?php
if(!function_exists('methodName')) {
    
    function curlAPI($url,$type=null){
        $headers = array(
            // Set Here Your Requesred Headers
            'Content-Type: application/json',
            'AppApiKey:18197:mostafiz',
            'Content-Length: 0',
        );
        $process = curl_init($url); //your API url
        curl_setopt($process, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($process, CURLOPT_HEADER, 0);
        curl_setopt($process, CURLOPT_TIMEOUT, 20);
        curl_setopt($process, CURLOPT_POST, 1);
         curl_setopt($process, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);
        $response = curl_exec($process);
        
            //    print_r($response);
        
       // $response = json_decode($response, true);
        
        
        return $response;
        
       // print_r($response);
      /*  if(isset($response) && !empty($response))
        {
        	//return $response;
        	$ReturnArray = ['status'=>'200','response_data'=>$response,'response_url'=>$url];
        	return $ReturnArray;
        }
        else
        {
        	$err = curl_error($curl);
        	//return $err
        	$ReturnArray = ['status'=>'400','response_data'=>$err,'response_url'=>$url];
        	
        	return $ReturnArray;
        }
        
        */
        curl_close($process);
        //print_r($return);
    }

    function getData($url,$type=null) {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_IPRESOLVE=>CURL_IPRESOLVE_V4,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $type,
            CURLOPT_HTTPHEADER => array(
                // Set Here Your Requesred Headers
                'Content-Type: application/json',
                'AppApiKey:18197:mostafiz',
            ),
        ));
        $response = "";
        //$response = json_decode(curl_exec($curl),true);
        $response = curl_exec($curl);

        if(isset($response) && !empty($response)) {
        	//return $response;
        	$ReturnArray = ['status'=>'200','response_data'=>$response,'response_url'=>$url];
        	return $ReturnArray;
        } else {
        	$err = curl_error($curl);
        	$ReturnArray = ['status'=>'400','response_data'=>$err,'response_url'=>$url];
        	return $ReturnArray;
        }
        curl_close($curl);
    }
    
    function postData($url,$data) 
    {
        $post = [
            'data' => json_encode($data),
        ];

        $headers = array(
            // Set Here Your Requesred Headers
            // 'Content-Type: text/html',
            // 'Content-Type: application/form-data',
            'AppApiKey:18197:mostafiz',
           // 'Content-Length: 0',
        );

        $ch = curl_init();
          
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);        
        curl_setopt($ch, CURLOPT_POSTFIELDS,$post);

        // Execute post
        $result = curl_exec($ch);
        
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        // response
        if(isset($result) && !empty($result) || $result == "Success") {
            return 'success'; //$result; 
        } else {
            return 'failed';
        }
        
        curl_close($curl);
    }
    
    function postBPData($url,$data) 
    {
        $post = [
            'data' => json_encode($data),
        ];

        $headers = array(
            // Set Here Your Requesred Headers
            // 'Content-Type: text/html',
            // 'Content-Type: application/form-data',
            'AppApiKey:18197:mostafiz',
           // 'Content-Length: 0',
        );

        $ch = curl_init();
          
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);        
        curl_setopt($ch, CURLOPT_POSTFIELDS,$post);

        // Execute post
        $result = curl_exec($ch);
        
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        // response
        if(isset($result) && !empty($result)) {
            return $result; 
        } else {
            return 'failed';
        }
        
        curl_close($curl);
    }
    
    function salesReturn($url,$saleId)
    {
        $post = [
            'salesId' => $saleId,
        ];

        $headers = array(
            // Set Here Your Requesred Headers
            // 'Content-Type: text/html',
            // 'Content-Type: application/form-data',
            'AppApiKey:18197:mostafiz',
           // 'Content-Length: 0',
        );

        $ch = curl_init();
          
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);        
        curl_setopt($ch, CURLOPT_POSTFIELDS,$post);

        // Execute post
        $result = curl_exec($ch);
        
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        // response
        if(isset($result) && !empty($result) || $result == "Success") {
            return 'success'; //$result; 
        } else {
            return 'failed';
        }
        
        curl_close($curl);
    }
}