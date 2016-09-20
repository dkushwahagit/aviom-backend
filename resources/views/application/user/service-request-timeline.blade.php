
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
                    <p class="name">Suresh Tiwari</p>
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
                        <label class="btn btn-default">Attachment &hellip;
                          <input type="file" style="display: none;">
                        </label>
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
  