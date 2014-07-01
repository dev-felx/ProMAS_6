<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Manage extends CI_Controller{
    
    public function __construct() {
        parent::__construct();
        //checking session and allowed roles
        $roles = array('superuser','administrator','coordinator');
        check_session_roles($roles);
        
        $this->load->model('access/manage_users');
        $this->load->model('project_model');
        $this->load->model('role_model');
    }


    
    public function users($user,$message=NULL){
        //print_r($user); die();
        if ($user != 'student') {
            $filters['type'] = $user;
            $filters['fields'] = array('non_student_users.user_id','first_name','last_name','username','email','phone_no','reg_status');
            $result = $this->manage_users->get_all_non_student($filters);
            $data['table_head'] = array('first_name','last_name','username','email');
        } elseif ($user == 'student') {
            $filters['type'] = $user;
            $filters['fields'] = array('student_id','first_name','last_name','registration_no','email');
            $result = $this->manage_users->get_all_student($filters);
            $data['table_head'] = array('first_name','last_name','registration_no','email');
        }
        $data['message']= urldecode($message);
        $data['table_data'] = $result;
        $data['views'] = array ("manage_users/manage_view");
        $data['user']= $user; 
        page_load($data);
    }// end function users
    
    public function view($user_id, $user){
        if($user== 'student'){
            $values=array(
                'student_id'=>$user_id,
            );
           $data['user_data'] = $this->manage_users->get_student($values);
           $proj_id = $data['user_data'][0]['project_id'];
           $data['project_data']= $this->project_model->get_project($proj_id);
        }elseif($user=='supervisor'){
            $values = array(
                'non_student_users.user_id'=>$user_id
            );
            $data['user_data'] = $this->manage_users->get_non_student($values);
            $value = array(
                'supervisor_id'=>$data['user_data'][0]['user_id'] 
            );
            $data['project_data']= $this->project_model->get_all_project($value);
        }else{
            $values = array(
                'non_student_users.user_id'=>$user_id
            );
            $data['user_data'] = $this->manage_users->get_non_student($values);
        }
        $data['views'] = array ("manage_users/view_user_view");
        $data['user']= $user; 
        page_load($data);
            
        
    }// end view function
    
    public function edit($user_id,$user,$message=NULL){
    
        $data['message']= $message;
        
        if($user== 'student'){
            $values=array(
                'student_id'=>$user_id,
             );
            $data['user_data'] = $this->manage_users->get_student($values);
            $value = array(
                'student_projects.project_id >' =>0, 
            );
            $data['project_data'] = $this->project_model->get_all_project($value);
            $data['proj_data'] = $this->project_model->get_project($data['user_data'][0]['project_id']);
            $course_value= array(
                'course_id >'=>0
            );
            $this->load->model('course_model');
            $data['course_data']= $this->course_model->get_all_course($course_value);
        }else{
            $values = array(
                'non_student_users.user_id'=>$user_id
            );
            $data['user_data'] = $this->manage_users->get_non_student($values);
        
        }
        
        $data['views'] = array ("manage_users/edit_user_view");
        $data['user']= $user; 
            
        page_load($data);
            
    }// end edit function
    
    public function update_user($user_id,$user){
        
        $this->form_validation->set_rules("fname","Firstname","required");
        $this->form_validation->set_rules("lname","Lastname","required");
        $this->form_validation->set_rules("email","Email","required");
        
        
        if($user== 'student'){
        
            $this->form_validation->set_rules("reg_no","Reg #","required");
            $this->form_validation->set_message("required",' *');
            
          if($this->form_validation->run() === FALSE ){
              $message = '<div class="alert alert-warning text-center">Fields can not be empty</div>';
              $this->edit($user_id, $user,$message);
          }else{
              
            $project = $this->project_model->get_project($_POST['project']);
            $values = array(
              'registration_no'=>$_POST['reg_no'],  
              'first_name'=>$_POST['fname'],  
              'last_name'=>$_POST['lname'],  
              'phone_no'=>$_POST['phone'],  
              'email'=>$_POST['email'],
              'project_id'=>$_POST['project'],
              'course_id'=>$_POST['course_id'],
              'group_no'=>$project[0]['group_no'],
              'acc_status'=>$_POST['acc_status'],
              'reg_status'=>$_POST['reg_status'],
            );
            $result = $this->manage_users->update_student($user_id,$values);
            if($result > 0){
                $message = '<div class="alert alert-success text-center">Profile updated successful</div>';
                $this->edit($user_id, $user,$message);
            }
          }//end else form validation
           
        }else{
            $this->form_validation->set_message('required',' *');
            if($this->form_validation->run() === FALSE ){
              $message = '<div class="alert alert-warning text-center">Fields can not be empty</div>';
              $this->edit($user_id, $user,$message);
            } else{
                $values = array(
                  'first_name'=>$_POST['fname'],  
                  'last_name'=>$_POST['lname'],  
                  'phone_no'=>$_POST['phone'],
                  'acc_status'=>$_POST['acc_status'],
                  'reg_status'=>$_POST['reg_status'],
                  'email'=>$_POST['email'],

            );
                $role_valid = TRUE;
                if($this->session->userdata['type']=='administrator'){
                    if($_POST['admin_role']==0 && $_POST['coord_role']==0 &&$_POST['super_role']==0&&$_POST['panel_head_role']==0){
                        $role_valid = FALSE;
                    }
                }else if($this->session->userdata['type']=='coordinator'){
                    if($_POST['super_role']==0&&$_POST['panel_head_role']==0){
                        $role_valid = FALSE;
                    }
                }
                
            if($role_valid){
                $result = $this->manage_users->update_non_student($user_id,$values);
                $roles_no = $this->role_model->count_roles($user_id);
            }else{
                $result =NULL;
                $message = '<div class="alert alert-warning text-center">At least one role must be checked</div>';
                $this->edit($user_id, $user,$message);
            }
            
            if($result !== NULL){
                if($this->session->userdata['type']=='administrator'){
                    //admin role added if it doesnot exist
                    if($_POST['admin_role']==1 &&
                    !$this->manage_users->check_value_exists('roles',array('user_id'=>$result[0]['user_id'],'role'=>'administrator'))){
                        $this->role_model->add_role($result[0]['user_id'],'administrator');
                        //role deleted if exist
                    }elseif($_POST['admin_role']==0 && ($roles_no>1) &&
                    $this->manage_users->check_value_exists('roles',array('user_id'=>$result[0]['user_id'],'role'=>'administrator'))){
                        $this->role_model->delete_role($result[0]['user_id'],'administrator');
                    }
                    //role added if doesnot exist
                    if(($_POST['coord_role'])==1 &&
                    !$this->manage_users->check_value_exists('roles',array('user_id'=>$result[0]['user_id'],'role'=>'coordinator'))){
                        $this->role_model->add_role($result[0]['user_id'],'coordinator');
                        //deleted if exist
                    }elseif($_POST['coord_role']==0 &&($roles_no>1) &&
                    $this->manage_users->check_value_exists('roles',array('user_id'=>$result[0]['user_id'],'role'=>'coordinator'))){
                        $this->role_model->delete_role($result[0]['user_id'],'coordinator');
                    }
            }//else $this->session->userdata['type']=='administrator')
              //role added if doesnot exist
                    if(($_POST['panel_head_role'])==1 &&
                    !$this->manage_users->check_value_exists('roles',array('user_id'=>$result[0]['user_id'],'role'=>'panel_head'))){
                        $this->role_model->add_role($result[0]['user_id'],'panel_head');
                        //deleted if exist
                    }elseif($_POST['panel_head_role']==0 &&($roles_no>1) &&
                    $this->manage_users->check_value_exists('roles',array('user_id'=>$result[0]['user_id'],'role'=>'panel_head'))){
                        $this->role_model->delete_role($result[0]['user_id'],'panel_head');
                    }
                    
                    if(($_POST['super_role'])==1 &&
                    !$this->manage_users->check_value_exists('roles',array('user_id'=>$result[0]['user_id'],'role'=>'supervisor'))){
                        $this->role_model->add_role($result[0]['user_id'],'supervisor');
                    }elseif($_POST['super_role']==0 &&($roles_no>1) &&
                    $this->manage_users->check_value_exists('roles',array('user_id'=>$result[0]['user_id'],'role'=>'supervisor'))){
                        $this->role_model->delete_role($result[0]['user_id'],'supervisor');
                    }
                $message = '<div class="alert alert-success text-center">Profile has beeen update</div>';
                $this->edit($user_id, $user,$message);
            }//result is not null if end here
          }//end form validationis true
        
        
    }//end else non student
    
    }//end function update user
    
    public function delete($user_id,$user){
        if($user== 'student'){
           $result_del = $this->manage_users->delete_student($user_id);
        }else{
            $result_del = $this->manage_users->delete_non_student($user_id);
        }
        if($result_del>0){
            $message = "User successfully deleted";
        } else{
            $message = "User not deleted";
        }
        $this->users($user,$message);
    }// end delete function
    public function delete_all($user){
        $values= array(
            'space_id'=>  $this->session->userdata['space_id'],
        );
        if($user =='student' ){
            $result = $this->manage_users->delete_all_student($values);
        }else{
            $result = $this->manage_users->delete_all_non_student($user);
        }
        if($result>0){
            $message = ucfirst($user)."s successfully deleted";
        }else{
            $message = "Users not deleted";
        }
            $this->users($user,$message);
        }
    }// end class Manage extends CI_Controller


?>
