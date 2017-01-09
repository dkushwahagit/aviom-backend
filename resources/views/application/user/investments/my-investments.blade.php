@extends('layout.dashboard-layout')

@section('title', 'My Investments')

@push('styles')
<link href="{{asset('assets/css/my-profile.css')}}" rel="stylesheet" type="text/css">
@endpush

@section('content')

  
  <div class="rightBox">
    <h1>My Investments</h1>
    <div class="containerBox">
      <div class="table-responsive">
      @if (isset($data['RESPONSE_DATA']) && !empty($data['RESPONSE_DATA']))    
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>#</th>
              <th>Project</th>
              <th>City</th>
              <th>Unit Type</th>
              <th>Area</th>
              <th>BSP</th>
              <th>Total Cost</th>
              <th>Month Year</th>
              <th>Payment Plan</th>
            </tr>
          </thead>
          <tbody>
               
              @foreach ($data['RESPONSE_DATA'] as $k => $v) 
                    <tr>
                      <td>{{(++$k)}}</td>
                      <td>{{$v['projectName']}}</td>
                      <td>{{$v['city']}}</td>
                      <td>{{$v['unitType']}}</td>
                      <td>{{$v['size']}} {{$v['measurementUnit']}}</td>
                      <td>{{$v['bspPerUnitSize']}} {{$v['measurementUnit']}}</td>
                      <td>{{$v['totalCost']}}</td>
                      <td>{{date('M Y',strtotime($v['dealClosedDate']))}}</td>
                      <td><a href="javascript:void(0)" @if(isset($v['paymentPlan']) && !empty($v['paymentPlan']))  class="payment-plan" data-tcf-id="{{$v['tcfId']}}" data-client-id="{{$v['clientId']}}" @endif ><?php echo  (isset($v['paymentPlan']) && !empty($v['paymentPlan']))?$v['paymentPlan'].'<em class="fa fa-angle-down"></em>':'N/A'; ?> </a></td>
                    </tr>
                @endforeach        
          
            
            
            
          </tbody>
        </table>
          @else
      <div class="alert alert-danger">No records found ! </div>
       @endif 
      </div>
    </div>
  </div>


@endsection  {{-- content Section ends here --}}

@push('scripts')
    <script src="{{ asset("assets/js/function.js") }}" type="text/javascript"></script>
    
@endpush