@extends('layout.user-layout')

@section('title', 'Login')

@push('styles')
{{-- place your page styles here --}}
@endpush

@section('content')

<div class="clearfix">
  <div class="loginBox">
      
    <div class="box">
      <div class="panelHeader">PLEASE LOGIN</div>
      <div class="panelBody">
        <div class="tagLine"><span>USE YOUR USER NAME &amp; PASSWORD</span></div>
        <?php $errors = $errors->all();?>
     @if (isset($errors) && !empty($errors)) 
      <ul class="alert alert-danger">
        @foreach($errors as $err)
          <li>{{$err}}</li>
        @endforeach
      </ul>
     @endif
     @if (isset($error) && !empty($error)) 
      <ul class="alert alert-danger">
        
          <li>{{$err}}</li>
        
      </ul>
     @endif
        <form action="{{url('/')}}" method="post" >
          <div class="form-group">
              <input type="text" name="username" class="form-control" placeholder="User Name" required >
          </div>
          <div class="form-group">
            <input type="password" name="password" class="form-control" placeholder="Password" required >
          </div>
          <div class="row">
            <div class="col-lg-8">
              <div class="clearfix">
                <input type="checkbox" name="remember_me" id="checkbox">
                <label for="checkbox">Remember me </label>
              </div>
              <div class="clearfix"><a href="{{url('/forgot-password')}}">Forgot Password ?</a></div>
            </div>
            <div class="col-lg-4">
              <div class="form-group">
                <button type="submit" class="btn btn-danger pull-right">Submit</button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

@endsection  {{-- content Section ends here --}}

@push('scripts')
    <script src="{{ asset("assets/js/function.js") }}" type="text/javascript"></script>
@endpush