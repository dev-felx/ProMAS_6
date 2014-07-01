<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<div class="container-fluid">
   <div class='row' style="margin-bottom: -5px; ">
        <div class='pull-left'><h4>Manage Users - <?php echo ucfirst(str_replace('_',' ', $user));   ?></h4></div>
            <div class=" pull-right">
            <a type="button" class="btn btn-success push_right_bit" href="<?php echo site_url(); ?>/manage_users/panel_session/add_member"  >Add <?php echo ucfirst(str_replace('_',' ', $user));   ?></a>
            </div>
        
    </div>
    <div class="row">
        <div class="hr" ><hr/></div>
    </div>
    <div  class="row">
        
        <div  id='add_user_form' class="container-fluid col-sm-6 col-sm-offset-3">
        
        <form  style="padding-top: 10px"  class=" " role="form" action="<?php echo site_url(); ?>/manage_users/panel_session/adding_member/<?php echo $user; ?>" method="POST">
            <?php if(isset($message)) {
                echo $message;
                } else{ ?>
            <div class="alert alert-info fade in text-center"><b>Add <?php echo ucfirst(str_replace('_',' ', $user));   ?></b> </div>
        <?php } ?>
            
            <div class="hr" style="margin-top: -15px; margin-bottom: 10px"><hr/></div>
            <!--if student, load registration input box-->

            <div class="form-group">       
                <label for="inputFirstname" class=" control-label">Firstname</label><?php show_form_error('fname'); ?>
                <div class="">
                    <input type="text" class="form-control" id="inputFirstname" name="fname" placeholder="" value="<?php echo set_value('fname'); ?>">
                </div>
            </div>

            <div class="form-group">

                <label for="inputLastname" class=" control-label">Lastname</label><?php show_form_error('lname'); ?>
                <div class="">
                    <input type="text" class="form-control" id="inputLastname" name="lname" placeholder="" value="<?php echo set_value('lname'); ?>">
                </div>
            </div>

            <div class="form-group">       
                <label for="inputEmail" class=" control-label">Email</label><?php show_form_error('email'); ?>
                <div class="">
                    <input type="email" class="form-control" id="inputEmail" name="email" placeholder="" value="<?php echo set_value('email'); ?>">
                </div>
            </div>
         
            <div class="form-group">
                <label class="control-label" for="inputPhoneNumber"> Phone Number</label><?php show_form_error('phone_no'); ?>
                <input name="phone_no" type="text" class="form-control" id="inputPhoneNumber" placeholder="+255715123456" value="">
            </div>
                 
            
            <div class="form-group">
                <div class="">
                    <button id="add_send" data-loading-text="loading stuff..." name="submit" type="submit" class="btn btn-primary">Add and Send Email</button>
                    <a class="btn btn-sm btn-warning" href="<?php echo site_url(); ?>/manage_users/panel_session/members/<?php echo $user; ?>" onclick="" role="button" >Cancel</a>
                </div>

            </div>

        </form>
            </div>
    </div>

</div>

