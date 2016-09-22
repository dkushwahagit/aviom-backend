
      <div class="timelineBox">
        <div class="heading">
          <h2>Timeline</h2>
        </div>
        <div class="timeline">
            
            <div class="row">
            <div class="col-sm-6">
              <div class="statusClose">
                  <form method="post" action="{{url('/reply-service-ticket')}}" enctype="multipart/form-data">
                <div class="row">
                  <div class="col-sm-12">
                    <p>Add New Message</p>
                  </div>
                 
                </div>
                <div class="row">
                  <div class="col-sm-12">
                      <textarea rows="3" cols="55" name="ticket-desc" required></textarea> 
                  </div>
                </div>
                      <input type="hidden" name="CIId" value="{{$data['RESPONSE_DATA'][0]['CIId']}}" />
                      <?php $ticketOriginArr = end($data['RESPONSE_DATA']);?>
                      <input type="hidden" id="main_CIId" name="main_CIId" value="{{$ticketOriginArr['CIId']}}" />
                      <input type="hidden" name="sub" value="{{$ticketOriginArr['subject']}}" />
                      <input type="hidden" name="TicketNo" value="{{$ticketOriginArr['ticketNo']}}" />
                <div class="row">
                  <div class="attatchFile">
                    <div class="row">
                      <div class="col-sm-6">
                        <label class="btn btn-default">Attach file &hellip;
                            <input type="file" accept=".doc,.docx,.xlsx,.xls,.pdf,.ppt,.jpeg,.bmp,.png,.gif" name="ticket-attachment" style="display: none;">
                        </label>
                      </div>
                      <div class="col-sm-6"> <button type="submit" class="btn pull-right">Submit</button> </div>
                    </div>
                  </div>
                </div>
              </form>
              </div>
            </div>
                
                
          </div>
         
            @foreach ($data['RESPONSE_DATA'] as $v)
                     
          <div class="row">
            <div class="col-sm-6 {{ ($v['msgIdentity'] == 'Request')?'':'col-sm-offset-6'}}">
              <div class="{{ ($v['msgIdentity'] == 'Request')?'statusClose':'statusOpen'}}">
                <div class="row">
                  <div class="col-sm-6">
                    <p>{{$v['subject']}}</p>
                  </div>
                  <div class="col-sm-6">
                    <p class="name">{{$v['enterBy']}}</p>
                    <span>{{ date('d M Y H:i',strtotime($v['createdDate'])) }}</span> </div>
                </div>
                <div class="row">
                  <div class="col-sm-12">
                    <p class="complaint">{{ substr($v['interactionDetails'],0,250) }}</p>
                  </div>
                </div>
                <div class="row">
                  <div class="attatchFile">
                    <div class="row">
                      <div class="col-sm-6">
                       <?php 
                          $file = $v['attachedFile'];
             $isExists = Illuminate\Support\Facades\Storage::disk('s3')
                     ->exists('/customer/ticket/'.$file); 
             echo $src = ($isExists && !empty($file) && isset($file))?'<a target="_blank" href="https://s3-ap-southeast-1.amazonaws.com/sqy/customer/ticket/'.$file.'"><label class="btn btn-default">Download Attachment &hellip;</label></a>':'<a href="javascript:void(0)"><label class="btn btn-default">No Attachment &hellip;</label></a>';
             ?>
                        
                      </div>
                      <div class="col-sm-6"> <a class="btn" href="javascript:void(0)">More</a> </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div> 
            
        @endforeach 
        
        
        </div>
      </div>
  