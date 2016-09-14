<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Ixudra\Curl\Facades\Curl;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    /**
     * @func apiRequest
     * @param string $url,string $method and array $data 
     * @return array 
     */
    public static function apiRequest ($url = '', $method = '', $data = array()) {
        
        if ((trim($url) != '') && isset($url)) {
            $to = config('app.API_URL').'/index.php/Api/v1'.$url;
            $curlReqObj = Curl::to($to);
            if (isset($data) && !empty($data)) {
             $curlReqObj = $curlReqObj->withData($data);
            }
            $method = trim($method);
            switch ($method) {
              case 'POST':
                $response = $curlReqObj->withTimeout(config('app.DEFAULT_CURL_TIMEOUT'))->post();
              break;    

              case 'PUT':
                $response = $curlReqObj->withTimeout(config('app.DEFAULT_CURL_TIMEOUT'))->put();
              break;

              case 'DELETE':
                $response = $curlReqObj->withTimeout(config('app.DEFAULT_CURL_TIMEOUT'))->delete();
              break;

              case 'GET' :
                $response = $curlReqObj->withTimeout(config('app.DEFAULT_CURL_TIMEOUT'))->get();
              break;    
            };
            //$response = json_encode($response,1);
            $response = json_decode($response,1);
            return $response;
        } else {
            $errorArr = array ('ERROR' => 'Error while accessing api!');
            $response = json_encode($errorArr);
            return $response;
        }    
    }
    
    public static function createUserSession ($clientDetails = array()) {
        
        if (isset($clientDetails) && !empty($clientDetails)) {
            if (!Session::has('client_session')) {
                Session::push('client_session', $clientDetails);
                Session::save();
            }
        }else {
            return false;
        }
        return true;
    }
    
    public static function uploadFiles ($file = null,$fileName = null, $location = 'customer/profilepic/') {
        $extension = $file->getClientOriginalExtension();
        $filename = $fileName.'.'.$extension;
        $resp = Storage::disk('s3')->put($location.$filename, file_get_contents($file), 'public');
        if ($resp) {  // return true on success
          return $filename; 
        }
    }
}
