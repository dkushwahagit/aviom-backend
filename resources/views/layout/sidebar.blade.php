<div class="leftBox">
    <div class="userSection">
        {{-- or asset('images/default.png')  --}}
        <?php 
             $img = Session::get('client_session.0.0.CImage');
             $isExists = Illuminate\Support\Facades\Storage::disk('s3')
                     ->exists('/customer/profilepic/'.$img); 
             $src = ($isExists && !empty($img) && isset($img))?'https://s3-ap-southeast-1.amazonaws.com/sqy/customer/profilepic/'.$img:asset('/images/default.png');
             ?>
      <div class="userPic"><img class="img-responsive img-circle" src="{{ $src }}" alt="User Pic"/></div>
      <div class="userName">{{ Session::get('client_session.0.0.CName')}}</div>
      <div class="userCity">{{ Session::get('client_session.0.0.City')}}, {{ Session::get('client_session.0.0.CountryName')}}</div>
    </div>
    <nav>
      <ul>
        <li class="active"><a href="{{url('/profile')}}"><em class="icon-my-profile"></em> <span>My Profile</span> </a></li>
        <li><a href="{{url('/my-properties')}}"><em class="icon-my-properties"></em> <span>My Properties</span> </a></li>
        <li><a href="{{url('/my-loans')}}"><em class="icon-my-loans"></em> <span>My Loans</span> </a></li>
        <li><a href="{{url('/service-request-list')}}"><em class="icon-service-request"></em> <span>Service Requests</span> </a></li>
        <li><a href="{{url('/my-credit-notes')}}"><em class="icon-my-cradit-notes"></em> <span>My Credit Notes</span> </a></li>
        <li><a href="{{url('/my-referral-list')}}"><em class="icon-my-references"></em> <span>My Referral</span> </a></li>
        <li><a href="{{ url('/exclusive-deals') }}"><em class="icon-exclusive-deal"></em> <span>Exclusive Deals</span> </a></li>
        <li><a href="javascript:$('#reset-password').modal();"><em class="icon-exclusive-deal"></em> <span>Reset Password</span> </a></li>
        <li><a href="{{url('/logout')}}"><em class="icon-logout"></em> <span>Logout</span></a></li>
      </ul>
    </nav>
  </div>