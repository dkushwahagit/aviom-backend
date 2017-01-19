@extends('layout.dashboard-layout')

@section('title', 'My Profile')

@push('styles')
<link href="{{asset('assets/lib/css/bootstrap-datetimepicker.css')}}" rel="stylesheet" type="text/css">
<link href="{{asset('assets/css/my-profile.css')}}" rel="stylesheet" type="text/css">
@endpush

@section('content')

  <?php
  //echo "<pre>";print_r(Session::get('client_session'));
           // echo "<pre>";print_r($data[0]['BookingId']);
//            echo $data->BookingId 
           //die;
  ?>
  <div class="rightBox">
    <h1>My Profile</h1>
    <div class="profileBox">
     <?php if(Session::get('client_session.0.0.clientType') == 'BUYER'){?>   
      <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
            <?php 
            
             $img = Session::get('client_session.0.0.CImage');
             $awsObj = Illuminate\Support\Facades\Storage::disk('s3');
             $isExists = $awsObj->exists('/customer/profilepic/'.$img); 
             $src = ($isExists && !empty($img) && isset($img))?$awsObj->url(config('app.AWS_PROFILE_BUCKET')).$img:asset('/images/default.png');
             ?>
          <div class="imgBox">
            <div class="userImg">
              <div class="circul">
                <figure> <img src="{{ $src }}" alt="" class="img-circle img-responsive"> </figure>
              </div>
                <form method="post" action="" enctype="multipart/form-data" id="profile-pic-form">
                    <input type="file" class="form-control-file" id="Profiling" aria-describedby="fileHelp" name="CImage" style="display: none;">
                </form>
            </div>
            <div class="userName">
              <p>{{ $data['RESPONSE_DATA']['CName']}}</p>
              <div class="cameraIcon"> <a href="javascript:void(0)"><em class="fa fa-camera"></em></a> </div>
            </div>
            <div class="information">
              <ul>
                <li><em class="icon-mobile"></em> {{ $data['RESPONSE_DATA']['MobileNo']}}</li>
                <li><em class="icon-email"></em> {{ $data['RESPONSE_DATA']['EmailId']}}</li>
              </ul>
            </div>
              <div class="socialSection"> <a href="{{isset($data['RESPONSE_DATA']['FBLink']) && !empty($data['RESPONSE_DATA']['FBLink'])?$data['RESPONSE_DATA']['FBLink']:'javascript:void(0)'}}" target="_blank"><em class="fa fa-facebook-square"></em></a> <a href="{{isset($data['RESPONSE_DATA']['LinkedInLink']) && !empty($data['RESPONSE_DATA']['LinkedInLink'])?$data['RESPONSE_DATA']['LinkedInLink']:'javascript:void(0)'}}" target="_blank"> <em class="fa fa-linkedin-square"></em></a> </div>
            <div class="dvider"></div>
            <a class="editBtn profile-btn" href="javascript:void(0)" role="button">Save profile</a> </div>
        </div>
        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
            <form method="post" action="" enctype="multipart/form-data" id="profile-form">  
                <div class="formBox">
            <div class="row">
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <div class="form-group">
                  <label><em class="icon-my-profile"></em>Pan No</label>
                  <input type="text" id="PanNo" class="form-control" disabled="disabled" placeholder="Enter pan no" name="PanNo" value="{{ $data['RESPONSE_DATA']['PanNo']}}">
                </div>
              </div>
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <div class="form-group">
                  <label><em class="icon-mobile"></em>Alternate Mobile No</label>
                  <input type="text" id="AlternateMobileNo" class="form-control" placeholder="Enter alternate mobile no" name="AlternateMobileNo" value="{{ $data['RESPONSE_DATA']['AlternateMobileNo']}}">
                </div>
              </div>
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <div class="form-group">
                  <label><em class="icon-email"></em>Alternate Email ID</label>
                  <input type="email" class="form-control" placeholder="Enter alternate emial id" name="AlternateEmailId" value="{{ $data['RESPONSE_DATA']['AlternateEmailId']}}" >
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <div class="form-group">
                  <label><em class="icon-city"></em>Current City </label>
                  <input type="text" id="City" name="City" class="form-control" placeholder="Enter current city" value="{{ $data['RESPONSE_DATA']['City']}}">
                  
                </div>
              </div>
              <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                <div class="form-group">
                  <label><em class="icon-address"></em>Current Address</label>
                  <input type="text" id="Address" class="form-control" placeholder="Enter current  address" name="Address" value="{{ $data['RESPONSE_DATA']['Address']}}">
                </div>
              </div>
            </div>        
            
            <div class="row">
              <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                  <label>
                  <em class="icon-city"></em>Permanent City
                  <div class="checkBoxx">
                      <input type="checkbox" value="" id="cityAsAbove" onchange="if($(this).prop('checked') == true){ $('#PermanentCity').val($('#City').val());}">
                  </div>
                  <span>same as above </span>
                  </label>
                  <input type="text" id="PermanentCity" class="form-control" placeholder="Enter permanente city"  name="PermanentCity" value="{{ $data['RESPONSE_DATA']['PermanentCity']}}" >
                </div>
              </div>
              <div class="col-lg-8 col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                  <label>
                  <em class="icon-address"></em>Permanent Address
                  <div class="checkBoxx">
                    <input type="checkbox" value="" id="addressAsAbove" onchange="if($(this).prop('checked') == true){ $('#PermanentAddress').val($('#Address').val());}">
                  </div>
                  <span>same as above </span>
                  </label>
                  <input type="text" id="PermanentAddress" class="form-control" placeholder="Enter permanent address" name="PermanentAddress" value="{{ $data['RESPONSE_DATA']['PermanentAddress']}}">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <div class="form-group">
                  <label><em class="icon-city"></em>Current Zip Code </label>
                  <input type="text" id="PINNo" name="PINNo" value="{{ $data['RESPONSE_DATA']['PINNo'] }}" class="form-control" placeholder="Enter current zip code">
                  
                </div>
              </div>
              <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                <div class="form-group">
                  <label><em class="icon-address"></em>Current Country</label>
                  <input type="text" id="CountryName" name="CountryName" value="{{ $data['RESPONSE_DATA']['CountryName'] }}" class="form-control" placeholder="Enter country name">
                  
                </div>
              </div>
            </div>
            
            <div class="row">
              <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                  <label>
                  <em class="icon-city"></em>Permanent Zip Code
                  <div class="checkBoxx">
                    <input type="checkbox" id="ZipAsAbove" onchange="if($(this).prop('checked') == true){ $('#PPINNo').val($('#PINNo').val());}">
                  </div>
                  <span>same as above </span>
                  </label>
                    <input type="text" id="PPINNo" name="PPINNo" value="{{ $data['RESPONSE_DATA']['PPINNo'] }}" class="form-control" placeholder="Enter permanent zip code">
                  
                </div>
              </div>
              <div class="col-lg-8 col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                  <label>
                  <em class="icon-address"></em>Permanent Country
                  <div class="checkBoxx">
                    <input type="checkbox" id="CountryAsAbove" onchange="if($(this).prop('checked') == true){ $('#PCountryName').val($('#CountryName').val());}">
                  </div>
                  <span>same as above </span>
                  </label>
                 <input type="text" id="PCountryName" name="PCountryName" value="{{ $data['RESPONSE_DATA']['PCountryName'] }}" class="form-control" placeholder="Enter permanent country name">
                </div>
              </div>
            </div>
                    
            <div class="row">
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <div class="form-group">
                  <label><em class="icon-occupation"></em>Occupation Type</label>
                  <?php 
                       $occupationArr = array (
                                          'E'  => 'Salary', 
                                          'SE' => 'Business/Self Employed',
                                        );
                       ?>
                  <select class="form-control" id="OccupationType" name="OccupationType">
                  @foreach ($occupationArr as $k => $v)    
                    <option value="{{ $k }}" {{ ($k == $data['RESPONSE_DATA']['OccupationType'])?'selected':'' }}>{{$v}}</option>
                  @endforeach  
                  </select>
                </div>
              </div>
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <div class="form-group">
                  <label><em class="icon-designation"></em>Designation</label>
                  <input type="text" id="Designation" class="form-control" placeholder="Enter designation" name="Designation" value="{{$data['RESPONSE_DATA']['Designation']}}" >
                </div>
              </div>
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <div class="form-group">
                  <label><em class="icon-company--name"></em>Company Name</label>
                  <input type="text" id="CompanyName" class="form-control" placeholder="Enter company name" name="CompanyName" value="{{$data['RESPONSE_DATA']['CompanyName']}}" >
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <div class="form-group">
                  <label><em class="icon-gross-salary"></em>Gross Salary (Monthly)</label>
                  <input type="number" id="GrossSalary" class="form-control" placeholder="Enter gross salary" name="GrossSalary" value="{{$data['RESPONSE_DATA']['GrossSalary']}}" >
                </div>
              </div>
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <div class="form-group">
                  <label><em class="icon-other-rental-income"></em>Other/Rental Income (Monthly)</label>
                  <input type="number" id="OtherIncome" class="form-control" placeholder="0" name="OtherIncome" value="{{$data['RESPONSE_DATA']['OtherIncome']}}">
                </div>
              </div>
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <div class="form-group">
                  <label><em class="icon-existing-EMI"></em>Existing EMI (Monthly)</label>
                  <input type="number" id="EMIAmt" class="form-control" placeholder="0" name="EMIAmt" value="{{$data['RESPONSE_DATA']['EMIAmt']}}">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <div class="row">
                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="form-group">
                      <label><em class="icon-dob"></em>Date of Birth</label>
                      <input type="text" id="DOB" class="form-control" placeholder="Enter date of birth" value="{{($data['RESPONSE_DATA']['DOB'] == '1900-01-01 00:00:00')?'':date('d-m-Y',strtotime($data['RESPONSE_DATA']['DOB']))}}" name="DOB">
                    </div>
                  </div>
                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="form-group">
                      <label><em class="icon-dob"></em>Anniversary Date</label>
                      <input type="text" id="AnniversaryDate" class="form-control" placeholder="Enter anniversary date" name="AnniversaryDate" value="{{($data['RESPONSE_DATA']['AnniversaryDate'] == '1900-01-01 00:00:00')?'':date('d-m-Y',strtotime($data['RESPONSE_DATA']['AnniversaryDate']))}}">
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                <div class="form-group">
                  <label><em class="icon-remark"></em>Tell Us More About You</label>
                  <textarea class="form-control" name="Remarks">{{$data['RESPONSE_DATA']['Remarks']}}</textarea>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <div class="form-group">
                  {{-- <label><em class="icon-upload"></em>Upload Profile Picture</label> --}}
                  
                </div>
              </div>
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <div class="form-group">
                  <label><em class="fa fa-facebook"></em>Facebook Profile Link</label>
                  <div class="input-group"> <span class="input-group-addon" id="sizing-addon1"><em class="fa fa-facebook"></em></span>
                      <input type="text" id="FBLink" class="form-control" aria-describedby="sizing-addon2" name="FBLink" value="{{$data['RESPONSE_DATA']['FBLink']}}">
                  </div>
                </div>
              </div>
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <div class="form-group">
                  <label><em class="fa fa-linkedin"></em>LinkedIn Profile Link</label>
                  <div class="input-group"> <span class="input-group-addon" id="sizing-addon2"><em class="fa fa-linkedin"></em></span>
                    <input type="text" id="LinkedInLink" class="form-control" aria-describedby="sizing-addon2" name="LinkedInLink" value="{{$data['RESPONSE_DATA']['LinkedInLink']}}">
                  </div>
                </div>
              </div>
            </div>
          </div>
            </form>
        </div>
      </div>
     <?php }else{ ?> 
        <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
            
          <div class="imgBox">
            <div class="userImg">
              <div class="circul">
                <figure> <img src="{{ asset('/images/default.png') }}" alt="" class="img-circle img-responsive"> </figure>
              </div>
                
            </div>
            <div class="userName">
              <p>{{ $data[0]->CustomerName}} </p>
              <div class="cameraIcon"> <a href="javascript:void(0)"><em class="fa fa-camera"></em></a> </div>
            </div>
            <div class="information">
              <ul>
                <li><em class="icon-mobile"></em> {{ $data[0]->Phone}}</li>
                <li><em class="icon-email"></em> {{ $data[0]->Email}}</li>
              </ul>
            </div>
            <a class="editBtn profile-btn-app-form" href="javascript:void(0)" role="button">Save profile</a> </div>
        </div>
        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
            <form method="post" action="" enctype="multipart/form-data" id="profile-form-app-form">  
                <div class="formBox">
            <div class="row">
              
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <div class="form-group">
                    <label><b>Client Personal Detail</b></label>
               </div>
              </div>
            </div>
                    
            <div class="row">
              
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <div class="form-group">
                  <label><em class="icon-mobile"></em>Mobile No</label>
                  <input type="text" id="Phone" class="form-control" placeholder="Enter mobile no" name="Phone" value="{{ $data[0]->Phone}}">
                </div>
              </div>
                
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <div class="form-group">
                  <label><em class="icon-mobile"></em>Alternate Mobile No</label>
                  <input type="text" id="AlternateMobileNo" class="form-control" placeholder="Enter alternate mobile no" name="AlternateMobileNo" value="{{ $data[0]->AlternatePhone}}">
                </div>
              </div>  
              
            </div>        
            <div class="row">
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <div class="form-group">
                  <label><em class="icon-city"></em>City </label>
                  <input type="text" id="City" name="City" class="form-control" placeholder="Enter current city" value="{{ $data[0]->City}}">
                  
                </div>
              </div>
              <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                <div class="form-group">
                  <label><em class="icon-address"></em>Address</label>
                  <input type="text" id="Address" class="form-control" placeholder="Enter current  address" name="Address" value="{{ $data[0]->Address}}">
                </div>
              </div>
            </div>        
            
            
            <div class="row">
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <div class="form-group">
                  <label><em class="icon-city"></em>BLANK </label>
                  <input type="text" id="IBAN" name="IBAN" value="blank" class="form-control" placeholder="Enter current IBAN">
                  
                </div>
              </div>
              <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                <div class="form-group">
                  <label><em class="icon-address"></em>Country</label>
                  <input type="text" id="CountryName" name="Country" value="{{ $data[0]->Country }}" class="form-control" placeholder="Enter country name">
                  
                </div>
              </div>
            </div>
            
                    
            <div class="row">
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <div class="form-group">
                    <label><b>Bank Detail</b></label>
                </div>
                
              </div>
                 
            </div>
                    
            <div class="row">
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <div class="form-group">
                  <label><em class="icon-company--name"></em>Passport No</label>
                  <input type="text" id="CompanyName" class="form-control" placeholder="Enter passport number" name="PassportNo" value="{{ $data[0]->PassportNo }}" >
                </div>
              </div>
                
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <div class="form-group">
                  <label><em class="icon-company--name"></em>Passport No</label>
                  <input type="text" id="CompanyName" class="form-control" placeholder="Enter passport number" name="PassportNo" value="{{ $data[0]->PassportNo }}" >
                </div>
              </div>  
            </div>
                    
                    
            <div class="row">
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <div class="form-group">
                  <label><em class="icon-gross-salary"></em>Swift Code</label>
                  <input type="text" id="GrossSalary" class="form-control" placeholder="Enter Swift Code" name="SwiftCode" value="{{ $data[0]->SwiftCode }}" >
                </div>
              </div>
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <div class="form-group">
                  <label><em class="icon-other-rental-income"></em>blank field</label>
                  <input type="text" id="ConversionRate" class="form-control" placeholder="0" name="ConversionRate" value="blank feild val">
                </div>
              </div>
              
            </div>
                    
            <div class="row">
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <div class="form-group">
                  <label><em class="icon-gross-salary"></em>Bank Address</label>
                  <input type="text" id="BankAddress" class="form-control" placeholder="Enter Bank Address" name="BankAddress" value="{{ $data[0]->BankAddress }}" >
                </div>
              </div>
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <div class="form-group">
                  <label><em class="icon-other-rental-income"></em>blank</label>
                  <input type="text" id="BlockingAmount" class="form-control" placeholder="0" name="BlockingAmount" value="blank value">
                </div>
              </div>
              
            </div>        
            <div class="row">
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <div class="row">
                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="form-group">
                      <label><em class="icon-dob"></em>Account Name</label>
                      <input type="text" id="AccountName" class="form-control" placeholder="Enter Account Name" value="{{ $data[0]->AccountName }}" name="AccountName">
                    </div>
                  </div>
                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="form-group">
                      <label><em class="icon-dob"></em>Account No</label>
                      <input type="text" id="AccountNo" class="form-control" placeholder="Enter Account No" name="AccountNo" value="{{ $data[0]->AccountNo }}">
                    </div>
                      
                      
                  </div>
                </div>
              </div>
              <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                <div class="form-group">
                  <label><em class="icon-remark"></em>Bank Name</label>
                  <input type="text" id="BankName" class="form-control" placeholder="Enter Bank Name" name="BankName" value="{{ $data[0]->BankName }}">
                </div>
              </div>
            </div>
            
          </div>
            </form>
        </div>
      </div>
     <?php }?>    
    </div>
  </div>


@endsection  {{-- content Section ends here --}}

@push('scripts')
    <script src="{{ asset("assets/lib/js/bootstrap-datetimepicker.js")}}" type="text/javascript"></script>  
    <script src="{{ asset("assets/js/function.js") }}" type="text/javascript"></script>
    <script>
$(document).ready(function () {
$('#AnniversaryDate, #DOB').datepicker({
format: "dd-mm-yyyy"
});  
$( "#GrossSalary, #OtherIncome, #EMIAmt" ).on( "keyup", function( event ) {
  if(event.which == '69') {
      $(this).val('');
  }
});
});
</script>
@endpush