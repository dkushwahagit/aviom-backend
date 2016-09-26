@extends('layout.dashboard-layout')

@section('title', 'My Referral')

@push('styles')

@endpush

@section('content')

  <div class="rightBox">
    <h1>My Referral <a class="pull-right btn btn-primary" data-toggle="modal" data-target="#addNewReferral" href="javascript:void:(0)" >Add New Referral</a></h1>
    <div class="containerBox">
      <div class="clearfix">
        <figure class="text-center" style="background:#3e4f83;"><img class="img-responsive" src="{{url('assets/images/referral-banner.jpg')}}" alt="Refer Your Friends & Win Exciting Incentive If Your Friend Buys Property Through us"/></figure>
      </div>
      <div class="referralBox">
    @if(isset($data['RESPONSE_DATA']) && !empty($data['RESPONSE_DATA']))      
    @foreach($data['RESPONSE_DATA'] as $k => $v)      
        <div class="tile">
          <div class="panelHeader">
            <h2>{{$v['CName']}}</h2>
            <div class="refId"> Ref ID <span>{{$v['LeadId'] or 'N/A'}}</span></div>
          </div>
          <div class="panelBody">
            <ul>
              <li>Mobile</li>
              <li class="mobile">{{$v['ContactNo']}}</li>
              <li>Email ID</li>
              <li class="email"><a href="javascript:void(0)">{{$v['EmailId']}}</a></li>
            </ul>
          </div>
          <div class="panelFooter">
            <ul>
              <li>Delhi</li>
              <li>Mumbai</li>
              <li>Gurgaon</li>
              <li>Delhi</li>
              <li>Mumbai</li>
              <li>Gurgaon</li>
              <li>Delhi</li>
              <li>Mumbai</li>
              <li>Gurgaon</li>
              <li>Delhi</li>
              <li>Mumbai</li>
              <li>Gurgaon</li>
              <li>Delhi</li>
              <li>Mumbai</li>
              <li>Gurgaon</li>
              <li>Delhi</li>
              <li>Mumbai</li>
              <li>Gurgaon</li>
              <li class="moreTag"><em class="fa fa-angle-down"></em></li>
            </ul>
          </div>
        </div>
    @endforeach   
    
    @else
    <div class="alert alert-danger"> No Record Found !.</div>
    @endif
      </div>
      <div class="blankTile"></div>
    </div>
  </div>

<div id="addNewReferral" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">ADD NEW REFERRAL</h4>
      </div>
      <div class="modal-body">
          <form id="add-referral-form">
          <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <div class="form-group">
                <label for="email">Referral Name</label>
                <input type="text" class="form-control" name="CName" required/>
              </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <div class="form-group">
                <label for="mobile">Mobile</label>
                <input type="text" class="form-control" name="ContactNo" required>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <div class="form-group">
                <label for="email">Email ID </label>
                <input type="email" name="EmailId" class="form-control" required />
              </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <div class="form-group">
                <label for="city">City</label>
                <input type="type" class="form-control" name="City">
              </div>
            </div>
          </div>
<!--          <div class="cloudTag">
            <h4>preference</h4>
            <ul>
              <li>City -</li>
              <li>Delhi</li>
              <li>Mumbai</li>
              <li>Gurgaon</li>
              <li>Noida</li>
              <li>Navi Mumbai</li>
              <li>Bangalore</li>
            </ul>
            <ul>
              <li>Budget -</li>
              <li>0-20L</li>
              <li>0-50L</li>
              <li>0-80L</li>
              <li>0-1CR</li>
            </ul>
            <ul>
              <li>Project Status -</li>
              <li>New Launch</li>
              <li>Under Construction</li>
              <li>Ready to Move</li>
            </ul>
          </div> -->
          <div class="clearfix">
            <button type="submit" class="btn btn-danger">Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

@endsection  {{-- content Section ends here --}}

