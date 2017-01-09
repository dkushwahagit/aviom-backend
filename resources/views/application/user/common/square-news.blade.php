@extends('layout.dashboard-layout')

@section('title', 'Square News')

@push('styles')
<link href="{{asset('assets/css/my-profile.css')}}" rel="stylesheet" type="text/css">
@endpush

@section('content')

  
  <div class="rightBox">
    <h1>Square News</h1>
    <div class="containerBox">
       <div class="row">
        @if (isset($data['RESPONSE_DATA']) && !empty($data['RESPONSE_DATA']))    
               
              @foreach ($data['RESPONSE_DATA'] as $k => $v) 
                    <div class="col-md-4 col-sm-6">
                      <a href="#" class="thumbnail">
                          <img src="..." alt="...">
                        </a>
                      <div class="thumbnail">
                          <img src="..." alt="...">
                          <div class="caption">
                            <h3>Thumbnail label</h3>
                            <p>...</p>
                            <p><a href="#" class="btn btn-primary" role="button">Button</a> <a href="#" class="btn btn-default" role="button">Button</a></p>
                          </div>
                      </div>
                    </div>
                @endforeach                  
          @else
      <div class="alert alert-danger">No records found ! </div>
       @endif
       </div> 
      </div>
    </div>
  </div>


@endsection  {{-- content Section ends here --}}

@push('scripts')
    <script src="{{ asset("assets/js/function.js") }}" type="text/javascript"></script>
    
@endpush