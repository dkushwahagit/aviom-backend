<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Ixudra\Curl\Facades\Curl;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;

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
    
    public static function uploadFiles ($file = null,$fileName = null, $location = '') {
        $extension = $file->getClientOriginalExtension();
        $filename = $fileName.'.'.$extension;
        $resp = Storage::disk('s3')->put($location.$filename, file_get_contents($file), 'public');
        if ($resp) {  // return true on success
          return $filename; 
        }
    }
    
    public static function sendEmail($mailArray = NULL)
    {
        /**
         *  $mailArray = array (
         *               'to'     => array(),
         *               'cc'     => array(),
         *               'bcc'    => array(),
         *               'subject'=> string,
         *               'body'   => string 
         *           );
         */
               // die;
        try {
            if (is_array($mailArray)) {
                echo "I am in sendemail fumction";
               // die;
                $sent = Mail::send([], [], function($message) use ($mailArray) {
                    //echo "i am in sent";

                    echo "mail array";
                    print_r($mailArray);
                   if (isset($mailArray['to']) && !empty($mailArray['to'])) { 
                    echo "inside to";
                    print_r($mailArray['to']);
                    $to = implode(',',$mailArray['to']);
                    print_r($mailArray);
                    echo "after implode";
                   }
                   if (isset($mailArray['cc']) && !empty($mailArray['cc'])) { 
                    $cc = implode(',',$mailArray['cc']);
                   }
                   if (isset($mailArray['bcc']) && !empty($mailArray['bcc'])) { 
                    $bcc = implode(',',$mailArray['bcc']);
                   }
                   echo "after bcc";
                    $message->to($to)
                        ->subject($mailArray['subject'])
                        ->setBody($mailArray['body'], 'text/html');
                });
                
                echo "Sent success";
            } else {

            }
        } catch(\Exception $e) {
            echo $e->getMessage();
            die;
            return $e->getMessage();
        }
    }
    
    /**
     * @fun encrypt
     * @desc This function will encrypt the value
     * @param $plainValue
     * @return string
     */
    public static function encrypt($plainValue) {
        return Crypt::encrypt($plainValue);
    }

    /**
     * @fun decrypt
     * @desc This function will decrypt the value encrypted by encrypt function
     * @param $encryptedValue
     * @return string
     */
    public static function decrypt($encryptedValue) {
        return Crypt::decrypt($encryptedValue);
    }
}
