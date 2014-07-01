<?php

/* 
 * Author: Devid Felix
 * 
 * 
 */

if(isset($this->session->userdata['user_id'])){
    $user_id = $this->session->userdata['user_id'];
    $user_type = $this->session->userdata['type'];
}


?>

<div class="container-fluid">
    <div class='row' style="margin-bottom: -5px; ">
        <div class='text-info pull-left'><h4>My Profile</h4></div>
        <div class="btn-group pull-right">
            <?php if(isset($btn)){ echo $btn; } ?>
            <a type="button" href="<?php echo site_url(); ?>/access/password/change_pass_profile" class="btn btn-success" >Change Password </a>
        </div>
    </div>
    <div class="row">
        <div class="hr"><hr/></div>
    </div>
    <div class='col-sm-10 col-sm-offset-1'>
        <div class='row'>
            <?php if(isset($form_mode) && $form_mode=='edit'){ ?>
        <form id="reg_form" role="form">
            <div class="row">       
                    <h4 class="text-info text-center<?php if(isset($message)){ echo 'text-success'; } ?>">Personal details<?php if(isset($message)){ echo $message ; } ?></h4>
            </div>
            <div class="hr"><hr/></div>
                <?php if($this->session->userdata['type'] == 'student'){ ?>
            <div class="row">
                <div class="form-group">
                    <label class="col-sm-4 control-label">Registration # :</label>
                    <div class="col-sm-8">
                        <p class="form-control-static"><?php echo $user_data[0]['registration_no']; ?></p>
                    </div>
                </div>
            </div>
               <?php }else{ ?>
            <div class="row">
                <div class="form-group">
                    <label class="col-sm-4 control-label">Seniority :</label>
                    <div class="col-sm-8">
                        <p class="form-control-static"><?php echo $user_data[0]['seniority']; ?></p>
                    </div>
                </div>
            </div>
            <?php } ?>
            <div class="row">
                <div class="form-group">
                    <label class="col-sm-4 control-label">First Name :</label>
                    <div class="col-sm-8">
                        <p class="form-control-static"><?php echo $user_data[0]['first_name']; ?></p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="form-group">
                    <label class="col-sm-4 control-label">Last Name :</label>
                    <div class="col-sm-8">
                        <p class="form-control-static"><?php echo $user_data[0]['last_name']; ?></p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="form-group">
                    <label class="col-sm-4 control-label">Phone Number :</label>
                    <div class="col-sm-8">
                        <p class="form-control-static"><?php echo $user_data[0]['phone_no']; ?></p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <label class="col-sm-4 control-label">Email :</label>
                    <div class="col-sm-8">
                        <p class="form-control-static"><?php echo $user_data[0]['email']; ?></p>
                    </div>
                </div>
            </div>
            
            <?php if($this->session->userdata['type'] != 'student' ) { ?>
            
            <div class="row">
                <div class="form-group">
                    <label class="col-sm-4 control-label">Office Location :</label>
                    <div class="col-sm-8">
                        <p class="form-control-static"><?php echo $user_data[0]['office_location']; ?></p>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="form-group">
                    <label class="col-sm-4 control-label">Department :</label>
                    <div class="col-sm-8">
                        <p class="form-control-static"><?php echo $user_data[0]['name']; ?></p>
                    </div>
                </div>
            </div>
            
            <?php }
            
            else{ ?>
            <div class="row">
                <div class="form-group">
                    <label class="col-sm-4 control-label">Course :</label>
                    <div class="col-sm-8">
                        <p class="form-control-static"><?php echo $user_data[0]['name']; ?></p>
                    </div>
                </div>
            </div>
            <?php }?>
            <div class="hr"><hr/></div>
            <div class='row'>
                <div class=" form-group">
                    <div class='btn-block'>
                       <a class="btn btn-warning col-sm-2 col-sm-offset-5"  role="button" href="<?php echo site_url(); ?>/home">Cancel</a>
                    </div>
                </div>
            </div>
        </form>
       
            
            <?php }else{ ?>
            
        <form id="reg_form" method="POST" action="<?php echo site_url(); ?>/manage_users/profile/update_profile/<?php echo $this->session->userdata['user_id']; ?>/<?php echo $this->session->userdata['type'] ?>" role="form">

            <div class="row">       
                    <h4 class="text-center text-info <?php if(isset($message)){ echo 'text-danger'; } ?>">Personal details<?php if(isset($message)){ echo $message ; } ?></h4>
                 </div>
            <div class='row' >
                <div class="hr" style="margin-top:-1px"><hr/></div>
            </div>
                <?php if($this->session->userdata['type'] == 'student'){ ?>
            <?php }else{ ?>
            <div class="row">
                <div class="form-group">
                    <label for="inputSeniority" class="col-sm-2 control-label">Seniority :</label>
                    <div class="col-sm-0">
                        <p class="form-control-static"><?php echo $user_data[0]['seniority']; ?></p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                <select name="seniority" class="  form-control" >
                    <option></option>
                    <option>Professor</option>
                    <option>Associate Professor</option>
                    <option>Doctor</option>
                    <option>Senior Lecturer</option>
                    <option>Lecturer</option>
                    <option>Assistant Lecturer</option>
                    <option>Tutorial Assistant</option>
                </select><?php show_form_error('seniority'); ?>
                </div>
            </div>
                <?php } ?>

            <div class="row">
                <div class="col-sm-6">
                        <div class="form-group">
                            <label class="control-label" for="inputFirstName"> First Name: </label><?php show_form_error('fname');  ?>
                            <input name="fname" type="text" class=" form-control" id="inputFirstName" placeholder="First Name" value="<?php echo $user_data[0]['first_name']; ?>">
                        </div>

                </div>
            </div>

            <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="control-label" for="inputLastName"> Last Name: </label><?php show_form_error('lname');  ?>
                            <input name="lname" type="text" class="form-control" id="inputLastName" placeholder="LastName" value="<?php echo $user_data[0]['last_name']; ?>">
                        </div>
                    </div>
             </div>

            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label" for="inputPhoneNumber"> Phone Number: </label><?php show_form_error('phone');  ?>
                        <input  name="phone" type="text" class="form-control" id="inputPhoneNumber" value="<?php echo $user_data[0]['phone_no']; ?>">
                    </div>
                </div>
            </div>        
            <?php if($this->session->userdata['type'] != 'student' ) { ?>
            
            <div class='row' >
               <div class="hr" style="margin-top:-1px"><hr/></div>
            </div>
            
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group" >
                        <label for="inputOfficeLocation" class="control-label">Office Location</label><?php show_form_error('office');  ?>
                        <input name="office" type="text" class="form-control" id="inputOfficeLocation" value="<?php echo $user_data[0]['office_location']; ?>" >
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="form-group">
                    <label for="inputDepartment" class=" col-sm-2 control-label">Department :</label>
                    <div class="col-sm-0">
                        <p class="form-control-static"><?php echo $user_data[0]['name']; ?></p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div  class="col-sm-6">
                    <select name="dept" class="form-control" >
                        <option ></option>
                        <?php foreach ($depart_data as $value){ ?>
                        <option value="<?php echo $value['department_id'];  ?>"><?php echo $value['name']; ?></option>';
                        <?php }?>
                    </select><?php show_form_error('dept');  ?>
                </div>
            </div>
        <?php }else{ ?>
                
                
            
            <div class="row">
                <div class="form-group">
                    <label class="control-label col-sm-2" for="inputCourse">Course :</label>
                    <div class="col-sm-0">
                    <p class="form-control-static"><?php echo $user_data[0]['name']; ?></p>
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
                        
            <?php }?>
            <div class="row"><div class="hr"><hr/></div></div>
            <div class='row'>
                <div class=" form-group col-sm-6">
                    <div class='pull-left'>
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a class="btn btn-warning"  role="button" href="<?php echo site_url(); ?>/home">Cancel</a>
                     </div>
                </div>
            </div>
            
        </form>
        
                <?php } ?>
        </div>
    </div>
</div>
