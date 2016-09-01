<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\UserModel;
use App\Model\ClientLoginModel;
use App\Model\ClientMasterModel;
use DB;

class UserController extends Controller
{
    /**
     * Func: display
     * Desc: this is used to show particular user info
     * Param: user id
     * Return: array
     */
    public function display ($id = null) {
        $users = UserModel::where('UserId', 1)->get();
        if (!empty($users) && isset($users)) {
            $records = array (
                'ERROR'         => false,
                'RESPONSE_MSG'  => 'Data fetched Successfully',
                'RESPONSE_DATA' => $records
            );
        }else {
            $result = array (
                'ERROR'         => true,
                'RESPONSE_MSG'  => 'No Data found',
                'RESPONSE_DATA' => ''
            );
        }
        return $result;
    }
    
    /**
     * 
     */
    public function register () {
        $inputData = Input::all();
        return $inputData;
    }
    
    
    public function verifyPassword () {
        $inputData = Input::all();
        $records = DB::table('clientlogin')
                   ->where('clientlogin.UserName',$inputData['username'])
                   ->where('clientlogin.IsActive',1)
                   ->whereRaw('clientlogin.Password = AES_ENCRYPT("'.$inputData['password'].'","mysquareyards")')
                   ->leftJoin('clientmaster', 'clientlogin.CMId', '=', 'clientmaster.CMId')
                   ->select('clientlogin.ClientLoginId','clientlogin.UserName','clientlogin.ClientId','clientlogin.CMId',
                            'clientmaster.CName','clientmaster.City','clientmaster.CountryName')
                   ->get();
        $records = collect($records)->all();
        //return $records;
        if (!empty($records) && isset($records)) {
            $result = array (
                'ERROR'         => false,
                'RESPONSE_MSG'  => 'Data fetched Successfully',
                'RESPONSE_DATA' => $records
            );
        }else {
            $result = array (
                'ERROR'         => true,
                'RESPONSE_MSG'  => 'No Data found',
                'RESPONSE_DATA' => ''
            );
        }
        return $result;
    }
    
    
    public function profile ($CMId = NULL) {
        $records = ClientMasterModel::where('CMId',$CMId)->first();
        $records = collect($records)->all();
        if (!empty($records) && isset($records)) {
            $result = array (
                'ERROR'         => false,
                'RESPONSE_MSG'  => 'Data fetched Successfully',
                'RESPONSE_DATA' => $records
            );
        }else {
            $result = array (
                'ERROR'         => true,
                'RESPONSE_MSG'  => 'No Data found',
                'RESPONSE_DATA' => ''
            );
        }
        return $result;
    }
}
