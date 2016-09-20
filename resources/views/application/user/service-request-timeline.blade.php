
      <div class="timelineBox">
        <div class="heading">
          <h2>Timeline</h2>
        </div>
        <div class="timeline">
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
             echo $src = ($isExists && !empty($file) && isset($file))?'<a href="https://s3-ap-southeast-1.amazonaws.com/sqy/customer/ticket/'.$file.'"><label class="btn btn-default">Attachment &hellip;</label></a>':'<a href="javascript:void(0)"><label class="btn btn-default">No Attachment &hellip;</label></a>';
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
  