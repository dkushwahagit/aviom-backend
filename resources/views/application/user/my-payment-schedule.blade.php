<?php 
/**
 * @author Parveen Yadav
 * 
 */
?>
        <tr class="payment-schedule">
              <td colspan="9"><h2>Payment Scheduler</h2>
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>TCF ID</th>
                          <th>Stagee Name</th>
                          <th>Payment Description</th>
                          <th>Expected Date</th>
                          <th>Client Payment Confirmation</th>
                          <th>Total Paid Amount</th>
                          <th>Paid Amount Description</th>
                        </tr>
                      </thead>
                      <tbody>

@if (isset($data['RESPONSE_DATA']) && !empty($data['RESPONSE_DATA'])) 
    @foreach ($data['RESPONSE_DATA'] as $k => $v)
                        <tr>
                          <td>{{++$k}}</td>
                          <td>{{$v['tcfRefId']}}</td>
                          <td>{{$v['StageName']}}</td>
                          <td>{{$v['PaymentDescription']}}</td>
                          <td>{{$v['ExpectedDueDate']}}</td>
                          <td>
                              @if($v['PaymentStatus'] = 'P')
                              
                                  Pending
                               @elseif($v['PaymentStatus'] = 'C')
                                  Confirmed                                 
                              
                              @endif
                              </td>
                          <td>{{ $v['AmountPaid'] }}</td>
                          <td>{{ (isset($v['PaymentRemarks']) && !empty($v['PaymentRemarks']))?$v['PaymentRemarks']:'N/A' }}</td>
                        </tr>
                        
        @endforeach
@endif
                      </tbody>
                    </table>

                    
              </td>
</tr>