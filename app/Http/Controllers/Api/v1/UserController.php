<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\UserModel;
use App\Model\ClientLoginModel;
use App\Model\ClientMasterModel;
use Illuminate\Support\Facades\Storage;
use Validator;
use DB;
use Ixudra\Curl\Facades\Curl;

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
                            'clientmaster.CName','clientmaster.City','clientmaster.CountryName','clientmaster.CImage')
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
    
    public function displayAllProperty () {
        $inputData = Input::all();
        $records = array();
        if (isset($inputData) && !empty($inputData)) {
        $records = DB::table('tcf')
                   //->where('clienttcfxref.ClientID',$inputData['ClientId'])
                   ->whereRaw('client.ClientID=Case When ifnull((Select CMId From client where ClientID='.$inputData['ClientId'].'),0)=0 '
                           . ' Then '.$inputData['ClientId'].' Else client.ClientID End '
                           . ' AND ifnull(client.CMId,0)=Case When ifnull((Select CMId From client where ClientID='.$inputData['ClientId'].'),0)!=0 '
                           . ' Then ifnull((Select CMId From client where ClientID='.$inputData['ClientId'].'),0) Else ifnull(client.CMId,0) End '
                           . 'AND IFNULL(client.CRMLeadID,"") != ""')
                   ->join('clienttcfxref','clienttcfxref.TCFID','=','tcf.TCFID')
                   ->join('client','clienttcfxref.ClientID','=','client.ClientID')
                   ->join('product','product.ProductID','=','tcf.ProductID')
                   ->join('productproperty','productproperty.ProductPropertyID','=','tcf.ProductPropertyID')
                   ->join('location','location.location_id','=','product.City')
                   ->join('zref','zref.id','=','tcf.SizeUOMID')
                   ->leftJoin('payment_plan','payment_plan.PPId','=','tcf.PPId')
                   ->select('tcf.TCFID As tcfId','tcf.Size As size','tcf.BSPPerSizeUnit As bspPerUnitSize','tcf.ProductID As productId',
                           'tcf.TotalConsiderationValue As totalCost','tcf.DealClosureDate As dealClosedDate','tcf.PPId As ppId',
                           'product.ProductName As projectName','clienttcfxref.ClientID As clientId',
                            'location.name As city','productproperty.PropertyName As unitType',
                           'zref.RefName As measurementUnit','payment_plan.PlanName As paymentPlan')
                   ->get();
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
        }else {
               $result = array (
                    'ERROR'         => true,
                    'RESPONSE_MSG'  => 'Please enter client-id',
                    'RESPONSE_DATA' => ''
                );
        }
        
        return $result;
    }
    
    /**
     * 
     * 
     */
    
    public function displayPaymentSchedule () {
        $inputData = Input::all();
        $records = array();
        if (isset($inputData) && !empty($inputData)) {
        $records = DB::table('tcf')
                   //->where('tcf.ProductID',$inputData['productId'])
                   //->where('tcf.PPId',$inputData['ppId'])
                   ->where('tcf.TCFID',$inputData['tcfId'])
                   ->where('tcfstagedetail.ClientId',$inputData['clientId'])
                   ->orderBy('tcfstagedetail.PPSId', 'asc')
                   ->join('tcfstagedetail','tcfstagedetail.TcfId','=','tcf.TCFID')
                   ->join('payment_plan_stage','payment_plan_stage.PPSId','=','tcfstagedetail.PPSId')
                   ->select('tcf.TCFRefrenceId As tcfRefId','payment_plan_stage.StageName','payment_plan_stage.PaymentDescription',
                           'tcfstagedetail.ExpectedDueDate','tcfstagedetail.PaymentStatus','tcfstagedetail.AmountPaid','tcfstagedetail.PaymentRemarks')
                   ->get();
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
        }else {
               $result = array (
                    'ERROR'         => true,
                    'RESPONSE_MSG'  => 'Please enter client-id',
                    'RESPONSE_DATA' => ''
                );
        }
        
        return $result;
    }
    
    public function displayMyLoan () {
        $inputData = Input::all();
        $records = array();
        if (isset($inputData) && !empty($inputData)) {
        
        $mcf_records = DB::table('mcf')
                   ->whereRaw('ifnull(mcfclient.CMId,0)=ifnull((Select CMId From client where ClientID='.$inputData['ClientId'].'),0)'
                           . 'AND IFNULL(mcfclient.CRMLeadID,"") != "" AND ifnull(lead.TCFId,0)=0 AND ifnull(mcfclient.CMId,0)!=0')
                   ->join('bank','bank.BankId','=','mcf.BankId')
                   ->join('mcfclientxref','mcfclientxref.MCFID','=','mcf.MCFID')
                   ->join('mcfclient','mcfclientxref.ClientID','=','mcfclient.ClientID')
                   ->join('lead','lead.LeadID','=','mcfclient.CRMLeadID')
                   ->select('mcf.MCFID As mcfId','mcfclient.Name','bank.BankName As bankName','mcf.MCFSubmissionDate As mcfSubmissionDate','mcf.LoanAmount As loanAmount',
                           'mcf.SanctionedAmount As sanctionedAmount','mcf.status As status');    
        $query = $mcf_records->toSql();  
        //return $query;
        
        $records = DB::select("select mcf.MCFID As mcfId,mcfclient.Name,bank.BankName As bankName,
                               mcf.MCFSubmissionDate As mcfSubmissionDate,mcf.LoanAmount As loanAmount,
                           mcf.SanctionedAmount As sanctionedAmount,mcf.status As status from mcf "
                . " INNER JOIN bank on bank.BankId = mcf.BankId "
                . " INNER JOIN mcfclientxref on mcfclientxref.MCFID = mcf.MCFID "
                . " INNER JOIN mcfclient on mcfclientxref.ClientID = mcfclient.ClientID "
                . " INNER JOIN lead l ON l.LeadID=mcfclient.CRMLeadID AND ifnull(l.TCFId,0)!=0 "
                . " INNER JOIN  (Select t.TCFId,l.LeadId From client c "
                                  . " INNER JOIN clienttcfxref ct ON c.ClientID = ct.ClientID AND ifnull(c.CRMLeadID,'')!='' "
                                  . " INNER JOIN tcf t ON ct.TCFId=t.TCFId "
                                  . " INNER JOIN lead l on l.TCFId=t.TCFId "
                                  . " where c.ClientID=Case When ifnull((Select CMId From client where ClientID=".$inputData['ClientId']."),0)=0 Then ".$inputData['ClientId']." Else c.ClientID End "
                . " AND ifnull(c.CMId,0)=Case When ifnull((Select CMId From client where ClientID=".$inputData['ClientId']."),0)!=0 Then ".$inputData['ClientId']. " Else ifnull(c.CMId,0) End "
                . " ) t on ifnull(mcfclient.CRMLeadID,'')=t.LeadId Where ifnull(mcfclient.CMId,0)=0 UNION $query order by mcfSubmissionDate,Name");
                        
        
                   //->union($mcf_records);
                   //->get();
        //return $records->toSql(); 
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
        }else {
               $result = array (
                    'ERROR'         => true,
                    'RESPONSE_MSG'  => 'Please enter client-id',
                    'RESPONSE_DATA' => ''
                );
        }
        
        return $result;
        
    }
    
    public function displayMyCreditNotes () {
       $inputData = Input::all();
       $records = array();
       $rules = array (
           'clientId' => 'required'
           
       );
       $validator = Validator::make($inputData,$rules);
       if ($validator->fails()) {
                $errors = $validator->errors();
                $result = array (
                    'ERROR'         => true,
                    'RESPONSE_MSG'  => $errors,
                    'RESPONSE_DATA' => ''
                );
                return $result;
            }
       $and  = (isset($inputData['cmId']) && !empty($inputData['cmId'])) ? " c.CMId = '{$inputData['cmId']}' " 
               : " c.ClientId = '{$inputData['clientId']}' ";
       $records = DB::select(" Select ccn.ClientName As clientName, ccn.IssueDate As issueDate, ccn.Amount As amount, "
               . " p.ProductName As projectName, d.DeveloperName As builderName, t.DealClosureDate As bookingDate "
               . " from client c "
               . " INNER JOIN clientcreditnote As ccn on ccn.ClientId = c.ClientId "
               . " INNER JOIN tcf As t on ccn.TCFId = t.TCFID "
               . " INNER JOIN product As p on p.ProductID = t.ProductID "
               . " INNER JOIN developer As d on d.DeveloperID = p.DeveloperID "
               . " where ccn.CStatus = 'A' AND $and ");
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
    
    public function updateMyProfile (Request $request,$cmId = null) {
        $inputData = Input::all();
        $result = array ();
        $records = ClientMasterModel::where('CMId',$cmId)
                   ->update($inputData);
        if (($records === 1)) {
                $result = array (
                    'ERROR'         => false,
                    'RESPONSE_MSG'  => 'Profile Updated Successfully',
                    'RESPONSE_DATA' => $records
                );
            }else if ($records === 0) {
                $result = array (
                    'ERROR'         => false,
                    'RESPONSE_MSG'  => 'You have not made any changes in form',
                    'RESPONSE_DATA' => $records
                );
            }else {
                $result = array (
                    'ERROR'         => true,
                    'RESPONSE_MSG'  => 'Error in profile update query',
                    'RESPONSE_DATA' => ''
                    );    
            }
        return $result;    
    }
    
    public function updateMyProfilePic (Request $request,$cmId = null) {
       $result = array(); 
       if (isset($cmId) && !empty($cmId)) { 
            $inputData = Input::all();
            $records = ClientMasterModel::where('CMId',$cmId)
                   ->update($inputData);
            if (($records === 1) || ($records === 0)) {
                $result = array (
                    'ERROR'         => false,
                    'RESPONSE_MSG'  => 'Profile Image Changed Successfully',
                    'RESPONSE_DATA' => array('fileName' => $inputData['CImage'], 'queryResult' => $result)
                );
            }
        }else {
                $result = array (
                        'ERROR'         => true,
                        'RESPONSE_MSG'  => 'Client master id missing',
                        'RESPONSE_DATA' => ''
                    );
                    
            }
        return $result;    
    }
    
    public function displayExclusiveDeals (Request $request) {
        $inputData = Input::all();
        $to = 'http://api.squareyards.com/SquareYards/site/city/projectinfocus'; 
        $curlReqObj = Curl::to($to);
       if (isset($inputData) && !empty($inputData)) {
             $curlReqObj = $curlReqObj->withData($inputData);
        }
        $response = $curlReqObj->withTimeout(config('app.DEFAULT_CURL_TIMEOUT'))->get();
        return json_decode($response,1);
    }
    
    
    public function cityList (Request $request) {
        $to = 'http://api.squareyards.com/SquareYards/site/mobile/citylist'; 
        $curlReqObj = Curl::to($to);
        $response = $curlReqObj->withTimeout(config('app.DEFAULT_CURL_TIMEOUT'))->get();
        return json_decode($response,1);
    }
    
    public function resetPassword (Request $request) {
        
        $inputData = Input::all();
        $rulesArr = array (
            'CMId'             => 'required',
            'password'         => 'required|confirmed',
            'password_confirmation' => 'required'
        );
        $validator = Validator::make($inputData,$rulesArr);
        if ($validator->fails()) {
            $errors = $validator->errors();
                    $result = array (
                        'ERROR'         => true,
                        'RESPONSE_MSG'  => $errors,
                        'RESPONSE_DATA' => ''
                    );
                    return $result;
        }
        
        //$updateData = array('Password' => 'AES_DECRYPT("'.$inputData['password'].'","mysquareyards")');
        $records = DB::table('clientlogin')
              ->where('clientlogin.CMId',$inputData['CMId'])
              ->where('clientlogin.IsActive',1)
              ->update(['Password' => DB::raw('AES_ENCRYPT("'.$inputData['password'].'","mysquareyards")')]);
        if ($records == '0' || $records == '1') {
            $result = array (
                'ERROR'         => false,
                'RESPONSE_MSG'  => 'Password Updated Successfully.',
                'RESPONSE_DATA' => $records
            );
        }else {
            $result = array (
                'ERROR'         => true,
                'RESPONSE_MSG'  => 'Could Not Update Password Successfully.',
                'RESPONSE_DATA' => ''
            );
        }
       
        return $result;
    }
    
    public function serviceRequestList () {
        $inputData = Input::all();
        $rulesArr = array (
            'CMId' => 'required'
            );
        $validator = Validator::make($inputData,$rulesArr);
        if ($validator->fails()) {
            $errors = $validator->errors();
                    $result = array (
                        'ERROR'         => true,
                        'RESPONSE_MSG'  => $errors,
                        'RESPONSE_DATA' => ''
                    );
                    return $result;
        }
        $records = DB::select('Select clientinteraction.CIId As CIId, clientinteraction.TicketNo As ticketNo, clientinteraction.ReqSubject As subject,
                                clientinteraction.Idate As ticketDate, 
                                Case when ifnull(b.MRefCIId,0)=0 Then clientinteraction.IStatus 
                                Else (Select IStatus From clientinteraction Where CIId=b.CIId)
                                End as ticketStatus
                                From clientinteraction left join 
                                (Select max(CIId) as CIId,MRefCIId From clientinteraction 
                                where CMId = "'.$inputData['CMId'].'" group by MRefCIId)b on clientinteraction.CIId=b.MRefCIId
                                where CMId = "'.$inputData['CMId'].'" And ifnull(clientinteraction.RefCIId,0)=0 And ifnull(clientinteraction.MRefCIId,0)=0 '
                   );
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
    
    public function serviceRequestDetails () {
        $inputData = Input::all();
        $rulesArr = array (
            'CMId' => 'required',
            'CIId' => 'required'
            );
        $validator = Validator::make($inputData,$rulesArr);
        if ($validator->fails()) {
            $errors = $validator->errors();
                    $result = array (
                        'ERROR'         => true,
                        'RESPONSE_MSG'  => $errors,
                        'RESPONSE_DATA' => ''
                    );
                    return $result;
        }
        $records = DB::select('Select Case when (ifnull(clientinteraction.RefCIId,0)=0 And ifnull(clientinteraction.MRefCIId,0)=0 )
                               then clientinteraction.Idate else clientinteraction.CreatedDate END As createdDate, clientinteraction.ReqSubject As subject,
                                clientinteraction.AttachedFile As attachedFile, clientinteraction.InteractionDetails As interactionDetails,
                                Case when (ifnull(clientinteraction.RefCIId,0)=0 And ifnull(clientinteraction.MRefCIId,0)=0 )
                                Then "Request" Else case when ifnull(clientinteraction.LoginType,"E")="E" Then "Response" Else "Request" END END As msgIdentity
                                ,Case When ifnull(clientinteraction.LoginType,"E")="E" Then e.EmployeeName Else client.Name End As enterBy
                                From clientinteraction  left join employee e on e.EmployeeId = clientinteraction.CreatedBy
                                left outer join client on clientinteraction.ClientId=client.ClientID 
                                where clientinteraction.CMId = "'.$inputData['CMId'].'" '
                . ' And (clientinteraction.CIId = "'.$inputData['CIId'].'" OR ifnull(clientinteraction.MRefCIId,0)="'.$inputData['CIId'].'") '
                . ' order by clientinteraction.createdDate desc'
                   );
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
