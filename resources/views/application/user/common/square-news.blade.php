@extends('layout.dashboard-layout')

@section('title', 'Square News')

@push('styles')
<link href="{{asset('assets/css/my-profile.css')}}" rel="stylesheet" type="text/css">


<link href="{{asset('assets/css/font-awesome.css')}}" rel="stylesheet" type="text/css">
<link href="{{asset('assets/css/moonicon.css')}}" rel="stylesheet" type="text/css">
<link href="{{asset('assets/css/bootstrap.css')}}" rel="stylesheet" type="text/css">
<link href="{{asset('assets/css/style.css')}}" rel="stylesheet" type="text/css">
<link href="{{asset('assets/css/squaretimes.css')}}" rel="stylesheet" type="text/css">
@endpush

@section('content')
  
  <div class="rightBox">
    <h1>Square News</h1>
    
        <div class="containerBox">
      <div class="newsBox">
       <?php foreach($data['title'] as $k=>$title){ ?>   
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
          <div class="tile">
            <div class="overlay">
              <div class="overlayaDate"> <em class="fa fa-calendar"></em>
                <p><?php echo $data['pubDate'][$k]; ?></p>
              </div>
              <div class="overlayaText">
              <p><?php echo $data['desc'][$k]; ?></div>
            </div>
            <figure><img src="<?php echo $data['image'][$k]; ?>" alt="" class="img-responsive"></figure>
            <div class="imgTag">Image Alt Tag</div>
            <div class="infoBox">
              <div class="date"> <em class="fa fa-calendar"></em>
                <p><?php echo $data['pubDate'][$k]; ?></p>
                <div class="heading"><?php echo $title; ?></div>
              </div>
            </div>
          </div>
        </div>
       <?php } ?>  
       
      </div>
    </div>
    
    
    </div>
  </div>


@endsection  {{-- content Section ends here --}}

@push('scripts')
     <script src="{{ asset("assets/js/jquery-1.12.3.min.js") }}"></script> 
    <script src="{{ asset("assets/js/bootstrap.min.js") }}" type="text/javascript"></script>
    <script src="{{ asset("assets/js/function.js") }}" type="text/javascript"></script>
    
    
    
    
@endpush