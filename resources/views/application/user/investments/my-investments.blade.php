@extends('layout.dashboard-layout')

@section('title', 'My Investments')

@push('styles')
<link href="{{asset('assets/css/my-profile.css')}}" rel="stylesheet" type="text/css">
<link href="{{asset('assets/css/font-awesome.css')}}" rel="stylesheet" type="text/css">
<link href="{{asset('assets/css/moonicon.css')}}" rel="stylesheet" type="text/css">
<link href="{{asset('assets/css/bootstrap.css')}}" rel="stylesheet" type="text/css">
<link href="{{asset('assets/css/style.css')}}" rel="stylesheet" type="text/css">
@endpush
<?php ///echo "<pre>";print_r($data); //die("dfg"); ?>
@section('content')

 
  <div class="rightBox">
    <h1>My Investments</h1>
    <div class="containerBox">
        
      <div class="panel-group" id="accordion">
       <?php foreach($data['allChqDetail'] as $cnno=>$chqdetail){
           $collapseId = "collapse_".$cnno;
           ?>   
        <div class="panel panel-default">
          <div class="panel-heading">
            <div class="row">
              <div class="col-lg-1 col-md-3 col-sm-3 col-xs-12"> <a style="text-decoration: none" data-toggle="collapse" data-parent="#accordion" href="#<?php echo $collapseId; ?>" class="collapsed">
                <div class="clickBtn"></div>
                </a> </div>
              <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                <p>CN Number : <?php echo $cnno; ?></p>
              </div>
              <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <p>Total Investment (in USD) : <?php echo $data['ActualInvestmentInUSD'][$cnno]; ?></p>
              </div>
              
            </div>
          </div>
            
          <div id="<?php echo $collapseId; ?>" class="panel-collapse collapse out">
            <div class="panel-body">
              <div class="table-responsive">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th>Installment</th>
                      <th>Cheque Number</th>
                      <th>Cheque Amount</th>
                      <th>Period</th>
                      <th>Paid On</th>
                      <th>Upload Cancelled Cheque</th>
                    </tr>
                  </thead>
                  <tbody>
                   <?php foreach($chqdetail as $k=>$detailChq){ ?>   
                    <tr>
                      <td>Installment <?php echo $k+1; ?></td>
                      <td><?php echo $detailChq->ChequeNo; ?></td>
                      <td><?php echo $detailChq->appChque_amount; ?></td>
                      <td><?php echo $detailChq->Period; ?></td>
                   <td><?php if( $detailChq->InterestPaidOn != ''){echo $detailChq->InterestPaidOn; }else{ ?> Pending<?php } ?></td>
                      <td><?php if($detailChq->CancelledChequeReceived == ''){ ?>
                        
                        <form method="post" action="" enctype="multipart/form-data" id="profile-pic-form">
                            <input type="file" class="form-control-file" id="Profiling" aria-describedby="fileHelp" name="CancelledCheque">
                        </form>
                                                      
                         <?php }else{ ?>Amount Received<?php } ?></td>
                     <!-- <td><a href="javascript:void(0)">Click Here <em class="fa fa-angle-down"></em></a></td>-->
                    </tr>
                   <?php } ?> 
                  </tbody>
                </table>
              </div>
            </div>
          </div>
            
        </div>
       <?php } ?>  
      </div>
      
    </div>
  </div>


@endsection  {{-- content Section ends here --}}

@push('scripts')
    <script src="{{ asset("assets/js/jquery-1.12.3.min.js") }}" type="text/javascript"></script>
    <script src="{{ asset("assets/js/bootstrap.min.js") }}" type="text/javascript"></script>
    <script src="{{ asset("assets/js/function.js") }}" type="text/javascript"></script>
    
    <script>
    $( ".clickBtn" ).click(function() {
      $( this ).toggleClass( "highlight" );
    });
    </script>
    
    
@endpush