<footer>
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
        <ul>
          <li>Get in Touch</li>
          <li><a href="mailto:connect@squareyards.com"><em class="icon-email"></em> connect@squareyards.com</a></li>
          <li><a href="javascript:void(0)"><em class="icon-mobile"></em> Toll Free: 1800 208 3344</a></li>
        </ul>
      </div>
      <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
        <div class="copy">Copyright © 2016 All Rights Reserved</div>
      </div>
    </div>
  </div>
</footer>
<div id="msg" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Message Box</h4>
      </div>
      <div class="modal-body">
        
      </div>
    </div>
  </div>
</div>




<div id="reset-password" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">RESET PASSWORD</h4>
      </div>
      <div class="modal-body">
          <form method="post" action="{{ url('/reset-password')}}" id="reset-password-form">
         
          <div class="form-group">
            <input type="password" name="password" class="form-control" placeholder="New Password" required>
          </div>
          <div class="form-group">
            <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm New Password" required>
          </div>
          <div class="row">
            <div class="col-lg-12">
              <div class="form-group">
                <button type="submit" class="btn btn-danger pull-right">Submit</button>
                <button type="reset" id="reset-form" style="display: none;" >Reset</button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

