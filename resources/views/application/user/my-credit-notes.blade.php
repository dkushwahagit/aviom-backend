@extends('layout.dashboard-layout')

@section('title', 'My Credit Notes')

@push('styles')
<link href="{{asset('assets/css/my-profile.css')}}" rel="stylesheet" type="text/css">
@endpush

@section('content')

  <div class="rightBox">
    <h1>My Credit Notes</h1>
    <div class="containerBox">
      <div class="table-responsive">
      @if (isset($data['RESPONSE_DATA']) && !empty($data['RESPONSE_DATA']))   
      
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>#</th>
              <th>Customer Name</th>
              <th>Builder</th>
              <th>Project Name</th>
              <th>Date Of Booking</th>
              <th>Amount</th>
              <th>Issue Date</th>
            </tr>
          </thead>
          <tbody>
           @foreach($data['RESPONSE_DATA'] as $k => $v)   
            <tr>
              <td>{{ ++$k }}</td>
              <td>{{ $v['clientName'] }}</td>
              <td>{{ $v['builderName'] }}</td>
              <td>{{ $v['projectName'] }}</td>
              <td>{{ $v['bookingDate'] }}</td>
              <td>{{ $v['amount'] }}</td>
              <td>{{ $v['issueDate'] }}</td>
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
    <script src="{{ asset("assets/js/common.js") }}" type="text/javascript"></script>
@endpush