<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{   
    /**
     * @func display 
     * @desc this function returns user info on the basis of user id
     * @param Request $request
     * @param int $id
     * @return array of user info
     */
    public function display (Request $request,$id = null) {
        $result = self::apiRequest('/user/'.$id,'GET');
        return $result;
    }
    
    /**
     * @func register
     * @desc this function is used to create new user
     * @param Request $request
     * @return boolean true for created successfully, false for error
     */
    public function register (Request $request) {
        if ($request->isMethod('POST')) {
            $inputData = Input::all();
            $result = self::apiRequest('/user/register','POST',$inputData);
            print_r($result);exit();
            
        }
    }
    /**
     * 
     * @param Request $request
     * @return type null
     */
    public function login (Request $request) {
       if(!Session::has('client_session')) {
            if ($request->isMethod('POST')) {
                $inputData = Input::all();
                $rules = array (
                    'username'  => 'required',
                    'password'  => 'required'
                );

                $validator = Validator::make($inputData,$rules); //validating user inputs

                if ($validator->fails()) {
                    return redirect('/')->withErrors($validator)->withInput();
                }else {
                    $result = self::apiRequest('/verify-password','GET',$inputData);
                    if (!empty($result['RESPONSE_DATA']) && isset($result['RESPONSE_DATA'])) {
                        $boolResp = self::createUserSession($result['RESPONSE_DATA']); // creating session for user
                        if ($boolResp) {
                        return redirect('/my-properties');
                        }else {
                            return redirect('/')->with('error','session error !');
                        }
                    }else {
                         return redirect()->back()->withErrors(['error' => 'Invalid user credentials']);
                    }    


                }
            }else {
                return view('application.user.login');
            }
       }else{
           return redirect('/my-properties');
       }
    }
    
    /**
     * @func profile
     * @desc This function will show client profile on the basis of client master id
     * @param int clientMasterId
     * @return null
     */
    public function profile (Request $request) {
        //$resp = Storage::disk('s3');echo $resp->url(config('app.AWS_PROFILE_BUCKET'));
        //echo '<pre>';print_r(get_class_methods($resp)); exit;
        $clientMasterId = Session::get('client_session.0.0.CMId'); //client master id
        //print_r(Session::get('client_session'));exit;
        if (isset($clientMasterId) && !empty($clientMasterId)) {
            $result = self::apiRequest('/profile/'.$clientMasterId, 'GET');
            
            return view('application.user.profile')->with('data',$result);
        }else {
            return redirect('/');
        }
    }
    /**
     * @func logout
     * @desc This function will be used for ending client session
     * @return null
     */
    public function logout () {
        Session::forget('client_session');
        Session::flush();
        return redirect('/');
    }
    
    /**
     * @func displayMyAllProperty
     * @desc This function will be used to show tcf-list of a client 
     * @return null
     */
    public function displayMyAllProperty () {
        $ClientId = Session::get('client_session.0.0.ClientId'); //client id
        
        if (isset($ClientId) && !empty($ClientId)) {
            $result = self::apiRequest('/my-properties', 'GET', array('ClientId' => $ClientId));
            return view('application.user.my-properties')->with('data',$result);
        }else {
            return redirect('/logout');
        }
    }
    /**
     * 
     * @func displayMyPaymentSchedule
     * @desc This function will be used to show a particular tcf payement schedule
     */
    public function displayMyPaymentSchedule (Request $request) {
        if ($request->ajax()) {
            $inputData = Input::all();
            $clientId = $inputData['clientId'];
            $tcfId = $inputData['tcfId'];
            if ((isset($clientId) && !empty($clientId)) && (isset($tcfId) && !empty($tcfId))) {
                $result = self::apiRequest('/my-payment-schedule', 'GET', array('clientId' => $clientId, 'tcfId' => $tcfId));
                $data = view('application.user.my-payment-schedule')->with('data',$result);
                echo $data; exit();
            }
        }
    }
    
    public function displayMyLoan (Request $request) {
        $ClientId = Session::get('client_session.0.0.ClientId'); //client id
        
        if (isset($ClientId) && !empty($ClientId)) {
            $result = self::apiRequest('/my-loans', 'GET', array('ClientId' => $ClientId));
            return view('application.user.my-loans')->with('data',$result);
        }else {
            return redirect('/logout');
        }
    }
    
    public function displayMyCreditNotes () {
        $ClientId = Session::get('client_session.0.0.ClientId'); //client id
        $cmId = Session::get('client_session.0.0.CMId'); //client id
        
        if (isset($ClientId) && !empty($ClientId)) {
            $result = self::apiRequest('/my-credit-notes', 'GET', array('clientId' => $ClientId, 'cmId' => $cmId));
            if (isset($result['RESPONSE_DATA']) && !empty($result['RESPONSE_DATA'])) {
                return view('application.user.my-credit-notes')->with('data',$result);
            }else{
                return redirect('/my-properties');
            }
        }else {
            return redirect('/logout');
        }
    }
    
    public function updateMyProfile (Request $request) {
        $cmId = Session::get('client_session.0.0.CMId');
        if ($request->ajax()) {
            $inputData = Input::all();
            //print_r($inputData); exit();
            if (isset($cmId) && !empty($cmId)) {
                $result = self::apiRequest('/update-my-profile/'.$cmId, 'PUT', $inputData);
                 if ($result['ERROR'] == false) {
                        Session::forget('client_session.0.0.City');
                        Session::forget('client_session.0.0.CountryName');
                        Session::put('client_session.0.0.City',$inputData['City']);
                        Session::put('client_session.0.0.CountryName',$inputData['CountryName']);
                 }
                return $result;
            }
        }
    }
    
    public function updateMyProfilePic (Request $request) {
        $cmId = Session::get('client_session.0.0.CMId');
        $inputData = Input::all();     
        if ($request->ajax()) {
            if(Input::hasFile('CImage')) {
                $fileObj = Input::file('CImage');
                $ruleArr = array('CImage' => 'mimes:jpeg,bmp,png,gif|min:5|max:2048');
                $validator = Validator::make($inputData, $ruleArr);
                
                if ($validator->fails())
                 {
                   $errors = $validator->errors();
                    $result = array (
                        'ERROR'         => true,
                        'RESPONSE_MSG'  => $errors,
                        'RESPONSE_DATA' => ''
                    );
                    return $result;
                 }else {
                       $location = config('app.AWS_PROFILE_BUCKET');
                       $fileName = self::uploadFiles($fileObj,$cmId,$location);
                    if (isset($fileName) && !empty($fileName)) {
                        $result = self::apiRequest('/update-my-profile-pic/'.$cmId, 'POST', array('CImage' => $fileName));
                        Session::forget('client_session.0.0.CImage');
                        Session::put('client_session.0.0.CImage',$result['RESPONSE_DATA']['fileName']);
                        return $result;
                    }
                }    
            }
        }
    }
    
    public function displayExclusiveDeals ($cityid = null) {
         $result = self::apiRequest('/exclusive-deals', 'GET',array('cityid' => $cityid, 'limit' => '12'));
         $city   = self::apiRequest('/city-list', 'GET');
         return view('application.user.exclusive-deals')->with('data',$result)->with('city',$city['cityList']);
    }
    
    public function resetPassword (Request $request) {
        if ($request->isMethod('POST')) {
             $inputData = Input::all();
             $inputData['CMId'] = Session::get('client_session.0.0.CMId');
             $result = self::apiRequest('/reset-password', 'PUT', $inputData);
             return $result;
        }
    }
    
    public function serviceRequestList () {
        $cmId = Session::get('client_session.0.0.CMId');
        $inputData = array ('CMId' => $cmId);
        $result = self::apiRequest('/service-request-list', 'GET', $inputData);
        return view('application.user.service-request-list')->with('data',$result);
        
    }
    
    public function serviceRequestDetails ($cIId = null) {
        $cmId = Session::get('client_session.0.0.CMId');
        $inputData = array ('CMId' => $cmId,'CIId' => $cIId);
        $result = self::apiRequest('/service-request-detail', 'GET', $inputData);
        return view('application.user.service-request-timeline')->with('data',$result);
        
    }
    
    public function generateServiceRequest () {
        $ClientId = Session::get('client_session.0.0.ClientId'); //client id
        $cmId = Session::get('client_session.0.0.CMId');
        $postData = Input::all();
        if(Input::hasFile('ticket-attachment')) {
                $fileObj = Input::file('ticket-attachment');
               // echo $fileObj->getClientMimeType();                exit();
                $ruleArr = array('ticket-attachment' => 'mimes:doc,docx,xlsx,xls,pdf,ppt,jpeg,bmp,png,gif|min:10|max:2048');
                $validator = Validator::make($postData, $ruleArr);
                
                if ($validator->fails())
                 {
                   $errors = $validator->errors();
                    $result = array (
                        'ERROR'         => true,
                        'RESPONSE_MSG'  => $errors,
                        'RESPONSE_DATA' => ''
                    );
                    return redirect('/service-request-list')->with('errors', $errors);
                 }else {
                       $location = config('app.AWS_TICKET_BUCKET');
                       $fileName = self::uploadFiles($fileObj,time(),$location);
                 }
        }         
        $inputData = array (
            'ClientId'            => $ClientId,
            'TcfId'               => 0,
            'InteractionDetails'  => $postData['ticket-desc'],
            'Idate'               => date('Y-m-d H:i:s'),
            'CreatedBy'           => $ClientId,
            'Type'                => 'IN',
            'AttachedFile'        => (isset($fileName) && !empty($fileName))?$fileName:'',
            'CMId'                => $cmId,
            'ScheduleStatus'      => 'P',
            'RefCIId'             => '0',
            'IStatus'             => 'O',
            'TicketNo'            => '#'.time(),
            'ReqSubject'          => $postData['sub'],
            'MRefCIId'            => '0',
            'LoginType'           => 'C'
        );
        
        $result = self::apiRequest('/generate-service-ticket', 'POST', $inputData);
        if ($result['ERROR'] === false) {
              return redirect('/service-request-list')->with('success', $result['RESPONSE_MSG']);
        }else {
            return redirect('/service-request-list')->with('error', $result['RESPONSE_MSG']);
        }
    }
    
    public function replyServiceRequest () {
        $ClientId = Session::get('client_session.0.0.ClientId'); //client id
        $cmId = Session::get('client_session.0.0.CMId');
        $postData = Input::all();
        if(Input::hasFile('ticket-attachment')) {
                $fileObj = Input::file('ticket-attachment');
               // echo $fileObj->getClientMimeType();                exit();
                $ruleArr = array('ticket-attachment' => 'mimes:doc,docx,xlsx,xls,pdf,ppt,jpeg,bmp,png,gif|min:1|max:2048');
                $validator = Validator::make($postData, $ruleArr);
                
                if ($validator->fails())
                 {
                   $errors = $validator->errors();
                    $result = array (
                        'ERROR'         => true,
                        'RESPONSE_MSG'  => $errors,
                        'RESPONSE_DATA' => ''
                    );
                    return redirect('/service-request-list')->with('errors', $errors);
                 }else {
                       $fileName = self::uploadFiles($fileObj,time(),'customer/ticket/');
                 }
        }         
        $inputData = array (
            'ClientId'            => $ClientId,
            'TcfId'               => 0,
            'InteractionDetails'  => $postData['ticket-desc'],
            'Idate'               => date('Y-m-d H:i:s'),
            'CreatedBy'           => $ClientId,
            'Type'                => 'IN',
            'AttachedFile'        => (isset($fileName) && !empty($fileName))?$fileName:'',
            'CMId'                => $cmId,
            'ScheduleStatus'      => 'P',
            'RefCIId'             => $postData['CIId'],
            'IStatus'             => 'O',
            'TicketNo'            => $postData['TicketNo'],
            'ReqSubject'          => $postData['sub'],
            'MRefCIId'            => $postData['main_CIId'],
            'LoginType'           => 'C'
        );
        
        $result = self::apiRequest('/generate-service-ticket', 'POST', $inputData);
        if ($result['ERROR'] === false) {
              return redirect('/service-request-list')->with('success', $result['RESPONSE_MSG']);
        }else {
            return redirect('/service-request-list')->with('error', $result['RESPONSE_MSG']);
        }
    }
    
    public function referralList () {
        $ClientId = Session::get('client_session.0.0.ClientId'); //client id
        $cmId = Session::get('client_session.0.0.CMId');
        $result = self::apiRequest('/my-referral-list', 'GET', array('CMId' => $cmId, 'ClientId' => $ClientId));
        return view('application.user.my-referral')->with('data',$result);
    }
    
    public function addReferral () {
        $inputData = Input::all();
        $ClientId = Session::get('client_session.0.0.ClientId'); //client id
        $cmId = Session::get('client_session.0.0.CMId');
        $inputData['ClientId'] = $ClientId;
        $inputData['CMId'] = $cmId;
        $result = self::apiRequest('/add-referral', 'POST', $inputData);
        return $result;
        //return view('application.user.my-referral')->with('data',$result);
    }
    
    public function forgotPassword (Request $request,$encrypted_email = null) {
        if ($request->isMethod('GET') && is_null($encrypted_email)) {
            
            return view('application.user.forget-password');
            
        }else if($request->isMethod('POST')  && is_null($encrypted_email)) {
            
            $inputData = Input::all();
            $record = self::apiRequest('/get-client-master-id-by-email/'.$inputData['email'], 'GET');
            
            if ($record['ERROR'] === false) {
                $cmId = $record['RESPONSE_DATA']['CMId'];
                self::apiRequest('/set-pwd-flag/'.$cmId, 'PUT');
                $encryptedEmail = self::encrypt($inputData['email']);
                $url = url('/forgot-password/'.$encryptedEmail);
                $body = view('application.user.forget-password-email-template')->with('url',$url);
                //$link = "<a href='{$url}'>Click Here To Reset Your Password</a>";
                $mailArray = array (
                    'to'      => $inputData,
                    'subject' => 'Reset Mysquareyards Password Link',
                    'body'    => $body
                );
                self::sendEmail($mailArray);
                return view('application.user.forget-password')->with('email_success','Email Sent, Please check your inbox!');
            }else {
                return view('application.user.forget-password')->with('email_error','Sorry, this email id is not in our records!');
            }
        }else if ($request->isMethod('GET') && isset($encrypted_email) && !empty($encrypted_email)) {
            $email = self::decrypt($encrypted_email);
            
            return view('application.user.forget-password')->with('emailid',$encrypted_email)->with('email',$email);
        }
    }
    
    public function resetForgotPassword(Request $request) {
        if ($request->ajax()) {
             $inputData = Input::all();
             $email = self::decrypt($inputData['token']);
             $record = self::apiRequest('/get-client-master-id-by-email/'.$email, 'GET');
             
             if ($record['ERROR'] === false) {
             $cmId = $record['RESPONSE_DATA']['CMId'];    
             $result = self::apiRequest('/reset-forgot-password', 'PUT', array('CMId'=>$cmId,'password_confirmation' => $inputData['password_confirmation'],'password' => $inputData['password']));
             return $result;
             }else {
                 return $record;
             }
        }
    }
}
