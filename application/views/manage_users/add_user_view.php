<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<div class="container-fluid">
    <?php 
    $this->load->view('manage_users/manage_user_head_view');
    ?>
    <div  class="row">
        
        <div  id='add_user_form' class="container-fluid col-sm-6 col-sm-offset-3">
        
        <form  style="padding-top: 10px"  class=" " role="form" action="<?php echo site_url(); ?>/manage_users/add_user/add/<?php echo $user; ?>" method="POST">
            <?php if(isset($message)) {
                echo $message;
                } else{ ?>
            <div class="alert alert-info fade in text-center"><b>Add <?php echo ucfirst(str_replace('_',' ', $user));   ?></b> </div>
        <?php } ?>
            
            <div class="hr" style="margin-top: -15px; margin-bottom: 10px"><hr/></div>
            <!--if student, load registration input box-->
            <?php if(isset($user) && ($user=='student')){ ?>
            <div class="form-group">       
                <label for="reg_no" class=" control-label">Registration No</label><?php show_form_error('reg_no'); ?>
                <div class="">
                    <input type="text" class="form-control" id="reg_no" name="reg_no" placeholder="Registration #" value="<?php echo set_value('reg_no'); ?>">
                </div>
            </div>
            <?php }?>
            
            
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
            <?php if(isset($user) && $user == 'panel_head' && $user_data != null){?>
            <div class="form-group">
                <label class="control-label">Assign Project Groups<span class="badge push_left_bit">option</span></label>
                <select multiple="multiple" class="form-control" name="pros[]">
                    <?php 
                     foreach ($user_data as $value) {
                         echo '<option value="'.$value['project_id'].'">';
                         echo 'Group: '.$value['group_no'].' '.$value['title'];
                         echo '</option>';
                     }
                    ?>
                </select>
            </div>
            <?php } ?>
            
            <?php if(isset($user) && (($user=='student') || ($user=='supervisor')) && ($user_data !==NULL)  ){ ?>
            <div class="form-group">       
                <label for="group" class=" control-label">Project <?php if($user=='supervisor') echo'(Option)'; ?></label><?php if($user=='student') show_form_error('group'); ?>
                <div class="radio">
                  <label>
                    <input class="grp_assign" type="radio" name="group" id="" value="new" checked="">
                    Assign new project
                  </label>
                </div>
                <div class="radio"><label>
                   <input class="grp_assign" type="radio" name="group" id="exists" value="exists"  >
                    Assign existing project
                  </label>
                </div>
                <select id="exists_group" name="group_project" class="form-control hide">
                    <option></option>
                    <?php foreach ($user_data as $value){ ?>
                    <option value="<?php echo $value['project_id'];  ?>"><?php 
                    if($value['title']==NULL){ $value['title']='Title not defined'; }
                    echo $value['group_no'].': '. $value['title']; ?></option>';
                 <?php } ?>
                </select>
            </div>
            <?php }?>

            <div class="form-group">
                <div class="">
                    <button id="add_send" data-loading-text="loading stuff..." name="submit" type="submit" class="btn btn-primary">Add and Send Email</button>
                    <a class="btn btn-sm btn-warning" href="<?php echo site_url(); ?>/manage_users/manage/users/<?php echo $user; ?>" onclick="" role="button" >Cancel</a>
                </div>

            </div>

        </form>
            </div>
    </div>

</div>

<script>
$('.grp_assign').click(function(e) {
        if($('#exists').is(':checked')) { 
                $('#exists_group').removeClass('hide');
            }else{
                $('#exists_group').addClass('hide');
            }
    });    
</script>
    
