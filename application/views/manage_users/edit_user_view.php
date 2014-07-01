<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if($user =='student'){
    $id= 'student_id';
    }else{   $id = 'user_id';
        }
?>

<div class="container-fluid">
    <?php 
        $this->load->view('manage_users/manage_user_head_view');
    ?>
    <div class='row'>
        <div  id='reg_form' class=" col-sm-10 col-sm-offset-">
        
            <?php if(isset($message)){ echo $message ; } else { ?>
                     <div class="alert alert-info text-center"><b>Edit <?php echo $user_data[0]['first_name']; ?>'s profile</b></div>
                   <?php } ?>
            <form  class="container-fluid" method="POST" action="<?php echo site_url(); ?>/manage_users/manage/update_user/<?php echo $user_data[0][$id]; ?>/<?php echo $user; ?>" role="form">

                 
                        <?php if($user == 'student'){ ?>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label" for="inputReg_no">Registration </label><?php show_form_error('reg_no'); ?>
                                    <input name="reg_no" type="text" class="form-control" id="inputReg_no" value='<?php echo $user_data[0]['registration_no']; ?>'>
                                </div>
                            </div>
                        </div>
                        <?php }
 
                        else {
                        ?>
                        
                        <?php } ?>

                        <div class="row">
                            <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label" for="inputFirstName"> First Name: </label>
                                        <input name="fname" type="text" class=" form-control" id="inputFirstName"  value="<?php echo $user_data[0]['first_name']; ?>">
                                    </div>
                            </div>
                            </div>
                         <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label" for="inputLastName"> Last Name: </label>
                                        <input name="lname" type="text" class="form-control" id="inputLastName" value="<?php echo $user_data[0]['last_name']; ?>">
                                    </div>
                                </div>
                         </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label" for="inputPhoneNumber"> Phone Number </label>
                                    <input name="phone" type="text" class="form-control" id="inputPhoneNumber" value="<?php echo $user_data[0]['phone_no']; ?>">
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group" >
                                    <label class="control-label" for="inputEmail"> Email </label>
                                    <input name="email" type="text" class="form-control" id="inputEmail" value="<?php echo $user_data[0]['email']; ?>">
                                    
                                </div>
                            </div>
                        </div>
                        
                        <?php if($user == 'student'){ ?>
                            
                        <div class="row">
                <div class="form-group">
                    <label class="control-label col-sm-2" for="inputCourse">Course :</label>
                    <div class="">
                        <p class="form-control-static"><?php if(isset($user_data[0]['name'])){ echo $user_data[0]['name']; }?></p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <select name="course_id" class="form-control">
                        <option ></option>
                        <?php foreach ($course_data as $value){ ?>
                        <option value="<?php echo $value['course_id'];  ?>"><?php echo $value['name']; ?></option>';
                        <?php }?>
                    </select><?php show_form_error('course_id');  ?>
                </div>
            </div>
                    <div class="row">
                <div class="form-group">
                    <label class="control-label col-sm-2" for="inputCourse">Project :</label>
                    <div class="">
                    <p class="form-control-static"><?php echo $proj_data[0]['title']; ?></p>
                    </div>
                </div>
            </div>
                
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <select  name="project" class="form-control" >
                                            <option value='<?php echo $user_data[0]['project_id']; ?>'></option>
                                          <?php foreach ($project_data as $value){ ?>
                                          <option value="<?php echo $value['project_id'];  ?>"><?php 
                                          if($value['title']==NULL){ $value['title']='Title not defined'; }
                                          echo $value['group_no'].': '. $value['title']; ?></option>';
                                          <?php }?>
                                        </select>
                                </div>
                            </div>
                        </div>
                        
                <div class='hr'><hr/></div>
                        
                        <?php }  ?>

                        <div class="row">
                            <div class="col-sm-6">
                                <label class="control-label" for="inputUsername">Account Status</label>
                            </div>
                       </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <div class="checkbox-inline">
                                        <input type="hidden" name="reg_status" value="0" />
                                        <label class="checkbox-inline"><input name='reg_status' type="checkbox"<?php  if($user_data[0]['reg_status']==1){ echo 'checked'; } ?>  value="1"  >Registered</label>
                                    </div>
                                    <div class="checkbox-inline">
                                        <input type="hidden" name="acc_status" value="0" />
                                        <label class="checkbox-inline"><input name='acc_status' type="checkbox" <?php if($user_data[0]['acc_status']==1){ echo 'checked'; } ?>  value="1" >Enabled</label>
                                    </div>
                            </div>
                        </div>
                       </div>
                        
                <div class='hr'><hr/></div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label">Roles</label>
                                    
                                    <?php if($user == 'student'){ ?>    
                                        <div class="checkbox">
                                        <label><input disabled type="checkbox" <?php echo 'checked'; ?>>Student</label>
                                        </div>
                                    <?php  } else {
                                        
                                        
                                        if($this->session->userdata['type']=='administrator'){
                                        ?>
                                    
                                    <div class="checkbox">
                                        <input type="hidden" name="admin_role" value="0" />
                                        <label><input class="chk" name="admin_role" type="checkbox"<?php 
                                        
                                        for($i = 0; $i < count($user_data);$i++){
                                            if($user_data[$i]['role']=='administrator')
                                        echo 'checked'; } ?> value="1" name="admin_role">Administrator</label>
                                    </div>
                                    <div class="checkbox">
                                        <input type="hidden" name="coord_role" value="0" />
                                        <label><input class="chk" type="checkbox"<?php 
  
                                        for($i = 0; $i < count($user_data);$i++){
                                            if($user_data[$i]['role']=='coordinator')
                                        echo 'checked'; } ?> value="1" name="coord_role"  >Coordinator</label>
                                    </div>
                                        <?php } else if($this->session->userdata['type']=='coordinator'){ ?>
                                    <div class="checkbox">
                                        <input type="hidden" name="super_role" value="0" />
                                        <label><input class="chk" type="checkbox"<?php 
                                        
                                        for($i = 0; $i < count($user_data);$i++){
                                            if($user_data[$i]['role']=='supervisor')
                                        echo 'checked'; }  ?> value="1" name="super_role" >Supervisor</label>
                                    </div>
                                    <div class="checkbox">
                                        <input type="hidden" name="panel_head_role" value="0" />
                                        <label><input class="chk" type="checkbox"<?php 
                                        
                                        for($i = 0; $i < count($user_data);$i++){
                                            if($user_data[$i]['role']=='panel_head')
                                        echo 'checked'; }  ?> value="1" name="panel_head_role" >Panel Head</label>
                                    </div>
                                        <?php } } ?> 
                                </div>
                            </div>
                       
                        </div>

                <div class='hr'><hr/></div>
                        <div class='row'>
                        <div class=" form-group col-sm-8">
                            <div class='pull-left '>
                                <button id="update_btn" class="btn btn-success btn-sm " type='submit' role='button' >Update</button>
                                <a class="btn btn-danger btn-sm "  role="button" onclick="return confirm('Are you sure you want to delete <?php echo ucfirst($user_data[0]['first_name']) .' '.ucfirst($user_data[0]['last_name']); ?>?')" href="<?php echo site_url(); ?>/manage_users/manage/delete/<?php echo $user_data[0][$id]; ?>/<?php echo $user; ?>">Delete</a>
                                <a class="btn btn-sm btn-warning" href="<?php echo site_url(); ?>/manage_users/manage/users/<?php echo $user; ?>" onclick="" role="button" >Cancel</a>
                             </div>
                         </div>
                         </div>

        </form>
    </div>
    </div>
    </div>