<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\UserModel;
use App\Model\ClientLoginModel;
use App\Model\ClientMasterModel;
use App\Model\ClientInteractionModel;
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
        $rulesArr = array (
//            'PanNo'             => '',
            'AlternateMobileNo' => 'numeric',
            'AlternateEmailId'  => 'email',
//            'City'              => '',
//            'Address'           => '',
//            'PermanentCity'     => '',
//            'PermanentAddress'  => '',
//            'PINNo'             => '',
//            'CountryName'       => '',
//            'PPINNo'            => '',
//            'PCountryName'      => '',
//            'OccupationType'    => '',
//            'Designation'       => '',
//            'CompanyName'       => '',
            'GrossSalary'       => 'numeric',
            'OtherIncome'       => 'numeric',
            'EMIAmt'            => 'numeric',
            'DOB'               => 'date',
            'AnniversaryDate'   => 'date',
            'FBLink'            => 'active_url',
            'LinkedInLink'      => 'active_url'
//          'Remarks'           => ''            
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
        $postArr = array (
//            'PanNo'             => $inputData[''],
            'AlternateMobileNo' => $inputData['AlternateMobileNo'],
            'AlternateEmailId'  => $inputData['AlternateEmailId'],
            'City'              => $inputData['City'],
            'Address'           => $inputData['Address'],
            'PermanentCity'     => $inputData['PermanentCity'],
            'PermanentAddress'  => $inputData['PermanentAddress'],
            'PINNo'             => $inputData['PINNo'],
            'CountryName'       => $inputData['CountryName'],
            'PPINNo'            => $inputData['PPINNo'],
            'PCountryName'      => $inputData['PCountryName'],
            'OccupationType'    => $inputData['OccupationType'],
            'Designation'       => $inputData['Designation'],
            'CompanyName'       => $inputData['CompanyName'],
            'GrossSalary'       => (float)$inputData['GrossSalary'],
            'OtherIncome'       => (float)$inputData['OtherIncome'],
            'EMIAmt'            => (float)$inputData['EMIAmt'],
            'DOB'               => (isset($inputData['DOB']) && !empty($inputData['DOB']))?date('Y-m-d',strtotime($inputData['DOB'])):date('Y-m-d',strtotime('1900-01-01')),
            'AnniversaryDate'   => (isset($inputData['AnniversaryDate']) && !empty($inputData['AnniversaryDate']))?date('Y-m-d',strtotime($inputData['AnniversaryDate'])):date('Y-m-d',strtotime('1900-01-01')),
            'FBLink'            => $inputData['FBLink'],
            'LinkedInLink'      => $inputData['LinkedInLink'],
            'Remarks'           => $inputData['Remarks']
        );
        $records = ClientMasterModel::where('CMId',$cmId)
                   ->update($postArr);
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
                                where CMId = "'.$inputData['CMId'].'" And ifnull(clientinteraction.RefCIId,0)=0 And ifnull(clientinteraction.MRefCIId,0)=0 order by clientinteraction.createdDate desc'
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
        $records = DB::select('Select clientinteraction.CIId As CIId, clientinteraction.TicketNo As ticketNo, Case when (ifnull(clientinteraction.RefCIId,0)=0 And ifnull(clientinteraction.MRefCIId,0)=0 )
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
    
    public function generateServiceRequest () {
        $inputData = Input::all();
        $rulesArr = array (
            'ClientId'            => 'required',
            'InteractionDetails'  => 'required',
            'Idate'               => 'required',
            'CreatedBy'           => 'required',
            'Type'                => 'required',
            'CMId'                => 'required',
            'ScheduleStatus'      => 'required',
            'RefCIId'             => 'required',
            'IStatus'             => 'required',
            'TicketNo'            => 'required',
            'ReqSubject'          => 'required',
            'MRefCIId'            => 'required',
            'LoginType'           => 'required'
            );
        if ($inputData['RefCIId'] != '0') {
            $statusObj = DB::table('clientinteraction')->where('CIId',$inputData['RefCIId'])->select('IStatus')->get();
            $statusArr = collect($statusObj)->all();
            $inputData['IStatus'] = ($statusArr[0]->IStatus == 'O')?'O':'R';
        }
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
        $records = DB::table('clientinteraction')->insertGetId($inputData);
        $msg = 'Ticket Generated Successfully';
        if ($inputData['RefCIId'] != '0') {
            $refCIId = $inputData['RefCIId'];
                      
            $records = DB::table('clientinteraction')->where('clientinteraction.CIId',$refCIId)->update(['IStatus' => 'C']);
            $msg = 'Message Sent Successfully';
        }
        if ((!empty($records) && isset($records)) || ($records == '0')) {
                $result = array (
                    'ERROR'         => false,
                    'RESPONSE_MSG'  => $msg,
                    'RESPONSE_DATA' => $records
                );
            }else {
                $result = array (
                    'ERROR'         => true,
                    'RESPONSE_MSG'  => 'Error at insertion time',
                    'RESPONSE_DATA' => $records
                );
            }
        
        
        return $result;     
    }
    
    public function referralList () {
        $inputData = Input::all();
        $records = DB::table('clientreferral')
                ->leftJoin('lead','clientreferral.ContactNo','=','lead.PhoneNumberD')
                ->where('CMId',$inputData['CMId'])
                ->orWhere('ClientId',$inputData['ClientId'])
                ->select('lead.LeadID','clientreferral.*')
                ->get();
        $records = collect($records)->all();
        if (!empty($records) && isset($records)) {
                $result = array (
                    'ERROR'         => false,
                    'RESPONSE_MSG'  => 'Data fetched Successfully!',
                    'RESPONSE_DATA' => $records
                );
            }else {
                $result = array (
                    'ERROR'         => true,
                    'RESPONSE_MSG'  => 'No Records Found!',
                    'RESPONSE_DATA' => $records
                );
            }
        
        
        return $result;
    }
    
    public function addReferral () {
        $inputData = Input::all();
        
        $rulesForLead = array (
            'PhoneNumberD'        => 'unique:lead',
            'EmailId'             => 'unique:lead'
        );
        
        $leadArr = array ('EmailD' => $inputData['EmailId'], 'PhoneNumberD' => $inputData['ContactNo'] ); 
        $validator = Validator::make($leadArr,$rulesForLead);
        
        if ($validator->fails()) {
            $errors = $validator->errors();
                    $result = array (
                        'ERROR'         => true,
                        'RESPONSE_MSG'  => $errors,
                        'RESPONSE_DATA' => ''
                    );
                    return $result;
        }
        $rulesArr = array (
            'ClientId'            => 'required',
            'CName'               => 'required',
            'ContactNo'           => 'required|unique:clientreferral|integer',
            'EmailId'             => 'required|unique:clientreferral|email',
            'City'                => '',
            'CMId'                => 'required'
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
        $records = DB::table('clientreferral')->insertGetId($inputData);
        if (!empty($records) && isset($records)) {
                $result = array (
                    'ERROR'         => false,
                    'RESPONSE_MSG'  => 'Referral Added Successfully',
                    'RESPONSE_DATA' => $records
                );
            }else {
                $result = array (
                    'ERROR'         => true,
                    'RESPONSE_MSG'  => 'Error in query',
                    'RESPONSE_DATA' => $records
                );
            }
        
        
        return $result;     
    }
    
    public function getCMIdByEmail ($email = null) {
        
        $records = DB::table('clientmaster')
                   ->join('clientlogin','clientlogin.CMId','clientmaster.CMId')
                   ->where('clientmaster.EmailId',$email)
                   ->where('clientlogin.IsActive',1)
                   ->select('clientmaster.CMId As CMId')
                   ->first();
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
    
    public function setPwdResetFlag ($cmId) {
        
        $records = DB::table('clientlogin')
                   ->where('clientlogin.CMId',$cmId)
                   ->update(array('PwdResetFlag' => '1'));
        
        if (in_array($records,[0,1])) {
            $result = array (
                'ERROR'         => false,
                'RESPONSE_MSG'  => 'Password flag is set',
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
    
    public function unsetPwdResetFlag ($cmId) {
        
        $records = DB::table('clientlogin')
                   ->where('clientlogin.CMId',$cmId)
                   ->update(array('PwdResetFlag' => '0'));
        
        if (in_array($records,[0,1])) {
            $result = array (
                'ERROR'         => false,
                'RESPONSE_MSG'  => 'Password flag is unset',
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
    
    public function resetForgotPassword (Request $request) {
        
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
              ->where('clientlogin.PwdResetFlag',1)   
              ->update(['Password' => DB::raw('AES_ENCRYPT("'.$inputData['password'].'","mysquareyards")'),'PwdResetFlag' => '0']);
        
        if ($records == '1') {
            $result = array (
                'ERROR'         => false,
                'RESPONSE_MSG'  => 'Password Updated Successfully.',
                'RESPONSE_DATA' => $records
            );
        }else {
            $result = array (
                'ERROR'         => true,
                'RESPONSE_MSG'  => 'Sorry, Reset Password Link Expired',
                'RESPONSE_DATA' => $records
            );
        }
       
        return $result;
    }
    
}
