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
                    return redirect('/profile');
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
    }
    
    /**
     * @func profile
     * @desc This function will show client profile on the basis of client master id
     * @param int clientMasterId
     * @return null
     */
    public function profile (Request $request) {
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
        $s3 = Storage::disk('s3'); 
        echo '<pre>'; 
        print_r(get_class_methods($s3)); 
        echo '<pre/>';
        $s3->put('customer/profilepic/abc.txt','My name is Gulloooo.','public');
        print_r($s3->allFiles('customer/profilepic'));
        exit();
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
            return view('application.user.my-credit-notes')->with('data',$result);
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
                $ruleArr = array('CImage' => 'mimes:jpeg,bmp,png,gif|min:100|max:2048');
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
                       $fileName = self::uploadFiles($fileObj,$cmId,'customer/profilepic/');
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
    
    
}
