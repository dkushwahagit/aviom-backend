@extends('layout.user-layout')

@section('title', 'FORGOT PASSWORD')

@push('styles')
{{-- place your page styles here --}}
@endpush

@section('content')
@if(!isset($emailid) || empty($emailid))
<div class="clearfix">
  <div class="loginBox">
    <div class="box">
      @if(isset($email_success) && !empty($email_success))  
      <div class="alert alert-success"> {{$email_success}} </div>
      @else  
      <div class="panelHeader">FORGOT PASSWORD</div>
      <div class="panelBody">
        <div class="tagLine"><span>Forgot your password? No problem.</span></div>
        <form method="post" action="{{url('/forgot-password')}}">
          <div class="clearfix">
            <div class="form-group">
                <input type="email" name="email" class="form-control" placeholder="Email Address" required>
            </div>
            <div class="form-group">
              <button type="submit" class="btn btn-danger pull-right">Submit</button>
            </div>
          </div>
        </form>
      </div>
      @endif
    </div>
  </div>
</div>
@else
<div class="clearfix">
  <div class="loginBox">
    <div class="box">
         <div class="panelHeader">RESET PASSWORD</div>
      <div class="panelBody">
   <form method="post" action="{{ url('/reset-password')}}" id="reset-password-form">
        @if(isset($emailid) && !empty($emailid))      
          <div class="form-group">
            <input type="Email" name="" class="form-control" placeholder="" value="{{$emailid}}" disabled >
          </div>
        @endif
          <div class="form-group">
            <input type="password" name="password" class="form-control" placeholder="New Password" required>
          </div>
          <div class="form-group">
            <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm New Password" required>
          </div>
          <div class="row">
            <div class="col-lg-12">
              <div class="form-group">
                <button type="submit" class="btn btn-danger pull-right">Submit</button>
                <button type="reset" id="reset-form" style="display: none;" >Reset</button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endif
@endsection  {{-- content Section ends here --}}

@push('scripts')
    <script src="{{ asset("assets/js/function.js") }}" type="text/javascript"></script>
@endpush