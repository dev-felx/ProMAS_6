<?php
        $this->load->view('templates/header_out');
       ?>
      
      <div class="container">
          
          <div class="row-fluid">
              <div class=" page-header">
                  <h3 class="text-center">Change Password</h3>
              </div>
          </div>    
              
          <div class="row-fluid">
              <?php if (isset($message)){ echo $message; 
              } else { ?>
              <div class="alert alert-info fade in text-center"> Enter your email, a link to reset your password will be sent to you.</div>

                  <?php } ?>
          </div>

          <form class="form-horizontal" action="<?php echo site_url(); ?>/access/password/validate_password/<?php echo $user_type; ?>/<?php echo $user_id; ?>" method="POST" role="form">
          
              <div class="form-group">
                  <label for="inputPassword" class="col-sm-2 control-label">New Password</label>
                  <div class="col-sm-5">
                      <input name="password" type="password" class="form-control" id="inputPassword" placeholder="Password">
                  </div>
              </div>
        
              <div class="form-group">
                  <label for="inputPasswordCon" class="col-sm-2 control-label">Confirm Password</label>
                  <div class="col-sm-5">      
                      <input name="password_con" type="password" class="form-control" id="inputPasswordCon" placeholder="Confirm Password">
                  </div>
              </div>


              <div class="form-group" >
                  <div class="col-sm-offset-2 col-sm-2">  
                  <button name="submit" type="submit" class="btn btn-primary btn-block">Change</button>
                  </div>
              </div>
          
          </form>
          </div>
        <?php
        $this->load->view('templates/footer_out');
       ?>

                   