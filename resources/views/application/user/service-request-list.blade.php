@extends('layout.dashboard-layout')

@section('title', 'Service Requests')

@push('styles')
<link href="{{asset('assets/css/my-profile.css')}}" rel="stylesheet" type="text/css">
<link href="{{asset('assets/css/timeline.css')}}" rel="stylesheet" type="text/css">
@endpush

@section('content')

  
  <div class="rightBox">
    <h1>Service Requests <a class="pull-right btn btn-primary" data-toggle="modal" data-target="#addNewServiceRequest" href="javascript:void:(0)" >Add New Service Request</a></h1>
    <div class="containerBox">
      <div class="table-responsive">
          {{-- print_r($data) --}} 
          @if (isset($data['RESPONSE_DATA']) && !empty($data['RESPONSE_DATA'])) 
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>#</th>
              <th>Date</th>
              <th>Subject</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
         @foreach ($data['RESPONSE_DATA'] as $v)      
            <tr>
              <td><a class="ticket-interaction" data-interaction-id="{{$v['CIId']}}" href="javascript:void(0)">{{ $v['ticketNo'] }}</a></td>
              <td>{{ date('d-m-Y',strtotime($v['ticketDate'])) }}</td>
              <td>{{ $v['subject'] }}</td>
              <td>{{ ($v['ticketStatus'] == 'O')?'Open':'Close' }}</td>
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

<div id="addNewServiceRequest" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">ADD NEW SERVICE REQUEST</h4>
      </div>
      <div class="modal-body">
        <form>
          <div class="form-group">
            <label for="email">Subject<em>*</em></label>
            <input type="email" class="form-control" id="email">
          </div>
          <div class="form-group">
            <label for="exampleTextarea">Description</label>
            <textarea class="form-control" id="exampleTextarea" rows="3"></textarea>
          </div>
          <div class="row">
            <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
              <div class="form-group">
                <input type="file" class="form-control-file" id="exampleInputFile" aria-describedby="fileHelp">
              </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
              <button type="submit" class="btn btn-danger">SUBMIT</button>
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