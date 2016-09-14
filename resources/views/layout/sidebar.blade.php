<div class="leftBox">
    <div class="userSection">
      <div class="userPic"><img class="img-responsive img-circle" src="{{ asset('images/default.png') }}" alt="User Pic"/></div>
      <div class="userName">{{ Session::get('client_session.0.0.CName')}}</div>
      <div class="userCity">{{ Session::get('client_session.0.0.City')}}, {{ Session::get('client_session.0.0.CountryName')}}</div>
    </div>
    <nav>
      <ul>
        <li class="active"><a href="{{url('/profile')}}"><em class="icon-my-profile"></em> <span>My Profile</span> </a></li>
        <li><a href="{{url('/my-properties')}}"><em class="icon-my-properties"></em> <span>My Properties</span> </a></li>
        <li><a href="{{url('/my-loans')}}"><em class="icon-my-loans"></em> <span>My Loans</span> </a></li>
        <li><a href="service-requests.html"><em class="icon-service-request"></em> <span>Service Requests</span> </a></li>
        <li><a href="{{url('/my-credit-notes')}}"><em class="icon-my-cradit-notes"></em> <span>My Credit Notes</span> </a></li>
        <li><a href="my-referral.html"><em class="icon-my-references"></em> <span>My Referral</span> </a></li>
        <li><a href="exclusive-deals.html"><em class="icon-exclusive-deal"></em> <span>Exclusive Deals</span> </a></li>
        <li><a href="{{url('/logout')}}"><em class="icon-logout"></em> <span>Logout</span></a></li>
      </ul>
    </nav>
  </div>