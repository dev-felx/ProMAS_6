<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

if(isset($this->session->userdata['user_id'])){
    
    $user_id = $this->session->userdata['user_id'];
    $user_type = $this->session->userdata['type'];
}

?>

<div class="container-fluid">
   <div class='row' style="margin-bottom: -5px; ">
        <div class='text-info pull-left'><h4>My Profile</h4></div>
        <div class=" pull-right">
        <?php echo "<a type='button' class='btn btn-success push_right_bit'  href='". site_url(). "/manage_users/profile/edit_profile/".$this->session->userdata['user_id']."/".$this->session->userdata['type']."'>Edit Profile</a>"; 
        echo "<a type='button' class='btn btn-success push_right_bit'  href='". site_url(). "/manage_users/profile'>View Profile</a>";
        ?>    
        </div>
    </div>
    <div class="row">
        <div class="hr"><hr/></div>
    </div>
    <div class='row'>
    <div class="col-sm-6 col-sm-offset-3">
    
        <form id="reg_form" class="" action="<?php echo site_url(); ?>/access/password/validate_pass_profile" method="POST" role="form">
        <h4 class='text-info text-center'>Change Password</h4>
        
        <div class="row"><div class="hr"><hr/></div></div>   
        <input name="user_id" type="hidden" value="<?php if(isset($user_id)) echo $user_id; ?>">
        <input name="user_type" type="hidden" value="<?php if(isset($user_type)) echo $user_type; ?>">

         <span><?php if (isset($message)){ echo "<p class='text-center text-success'><b>".$message."</b></p>"; }?>
         </span>   

        <?php if(isset($user_id) && isset($user_type)) { ?>
       
        <div class="row">
            <div class="col-sm-12">
                    <div class="form-group">
                        <label for="currPassword" class=" control-label">Current Password:&nbsp;<?php echo show_form_error('curr_password'); ?></label>
                        <input name="curr_password" type="password" class="form-control" id="currPassword" placeholder="Current Password">
                    </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                    <div class="form-group">
                        <label for="inputPassword" class=" control-label">New Password:&nbsp;<?php echo show_form_error('password'); ?></label>
                        <input name="password" type="password" class="form-control" id="inputPassword" placeholder="Password" value="<?php echo set_value('password');?>">
                    </div>
            </div>
            </div>

        <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="inputPasswordCon" class="control-label">Confirm Password:&nbsp;<?php echo show_form_error('password_con'); ?></label>
                        <input name="password_con" type="password" class="form-control" id="inputPasswordCon" placeholder="Confirm Password" value="<?php echo set_value('password_con');?>">
                    </div>
                </div>

        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="form-group" >
                    <button name="submit" type="submit" class="btn btn-primary ">Change</button>
                    <a class="btn btn-warning"  role="button" href="<?php echo site_url(); ?>/home">Cancel</a>
                </div>
            </div>
        </div>

    <?php } ?>
    </form>
        
    </div>
    </div>
</div>

                   