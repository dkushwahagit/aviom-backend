<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Support\Facades\Session;

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
                     return redirect()->back()->withErrors('error','Invalid user credentials');
                }    
                
                
            }
        }else {
            return view('application.user.login');
        }
    }
    
    /**
     * 
     * @return null
     */
    public function profile (Request $request) {
        $clientMasterId = Session::get('client_session.0.0.CMId'); //client master id
        if (isset($clientMasterId) && !empty($clientMasterId)) {
            $result = self::apiRequest('/profile/'.$clientMasterId, 'GET');
            
            return view('application.user.profile')->with('data',$result);
        }else {
            return redirect('/');
        }
    }
    
    public function logout () {
        Session::forget('client_session');
        Session::flush();
        return redirect('/');
    }
}
