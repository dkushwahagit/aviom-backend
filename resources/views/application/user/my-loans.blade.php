@extends('layout.dashboard-layout')

@section('title', 'My Loans')

@push('styles')
<link href="{{asset('assets/css/my-profile.css')}}" rel="stylesheet" type="text/css">
@endpush

@section('content')

  <div class="rightBox">
    <h1>My Loans</h1>
    <div class="containerBox">
      <div class="table-responsive">
          @if (isset($data['RESPONSE_DATA']) && !empty($data['RESPONSE_DATA']))
            <table class="table table-bordered">
          <thead>
            <tr>
              <th>#</th>
              <!--<th>MCFRefId</th>-->
              <th>Customer Name</th>
              <th>Bank</th>
              <th>Date</th>
              <th>ROI</th>
              <th>Loan Amount</th>
              <th>Sanctioned Amount</th>
              <th>Disbursed Amount</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody> 
             @foreach ($data['RESPONSE_DATA'] as $k => $v) 
        
            <tr>
              <td>{{++$k}}</td>
              <!--<td>{{ $v['MCFRefrenceId'] }}</td>-->
              <td>{{ $v['Name'] }}</td>
              <td>{{ $v['BankName'] }}</td>
              <td>{{ $v['MCFSubmissionDate'] }}</td>
              <td>{{ $v['LoanROI'] }}</td>
              <td>{{ $v['LoanAmount'] or '0' }}</td>
              <td>{{ $v['SanctionedAmount'] or '0' }}</td>
              <td>{{ $v['DisbursementAmount'] or '0' }}</td>
              <td>{{ $v['Status'] }}</td>
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