<?php
        $this->load->view('templates/header_out');
       ?>
      
      <div class="container">
          
          <div class="row-fluid">
              <div class=" page-header">
                  <h3 class="text-center">Reset Password</h3>
              </div>
          </div>    
              
          <div class="row-fluid">
              <?php if (isset($message)){ echo $message; 
              } else { ?>
              <div class="alert alert-info fade in text-center"> Enter your email, a link to reset your password will be sent to you.</div>

                  <?php } ?>
          </div>
          
          <form class="form-horizontal" action="<?php echo site_url(); ?>/access/password/forgot_password" method="POST" role="form">
              
              <div class="form-group" >
                  <label class="control-label col-sm-1" for="inputEmail">Email </label>
                  
                  <div class="col-sm-10">
                      <input name="email" type="email" class="form-control" id="inputEmail" placeholder="Enter registered email"  value="">
                  </div>
              </div>    

              <div class="form-group">
                  <div class="col-sm-offset-1 col-sm-2">
                    <button name="submit" type="submit" class="btn btn-primary btn-block">Send</button>
                  </div>
              </div>
              
          </form>
      </div>
          <?php
            $this->load->view('templates/footer_out');
           ?>