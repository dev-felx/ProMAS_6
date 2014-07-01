<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Profile extends CI_Controller{
    
    public function __construct() {
        parent::__construct();
        
        //checking session and allowed roles
        $roles = array('superuser','administrator','coordinator','supervisor','student');
        check_session_roles($roles);
        
        $this->load->model('access/manage_users');
        $this->load->model('college_department_model');
        $this->load->model('course_model');
    }
    
    public function index(){
        $data['btn'] =  "<a type='button' class='btn btn-success push_right_bit'  href='". site_url(). "/manage_users/profile/edit_profile/".$this->session->userdata['user_id']."/".$this->session->userdata['type']."'>Edit Profile</a>";
        $data['form_mode']='edit';
        if($this->session->userdata['type']=='student'){
            $values = array(
                'student_id'=>  $this->session->userdata['user_id']
            );
            $data['user_data']=$this->manage_users->get_student($values);
        }else{
            $values = array(
                'non_student_users.user_id'=>  $this->session->userdata['user_id']
            );
            $data['user_data']=$this->manage_users->get_non_student($values);
        }
        $data['views'] = array ("profile/edit_profile_view");
        page_load($data);
    }
    
    
    public function edit_profile($user_id, $user,$message=NULL){
        $data['btn'] =  "<a type='button' class='btn btn-success push_right_bit'  href='". site_url(). "/manage_users/profile'>View Profile</a>";
        $data['message'] = $message;
        if($user== 'student'){
            $values=array(
                'student_id'=>$user_id,
            );
            $course_value= array(
                'course_id >'=>0
            );
            $this->load->model('course_model');
            $data['course_data']= $this->course_model->get_all_course($course_value);
            $data['user_data'] = $this->manage_users->get_student($values);
        }else{
            $values = array(
                'non_student_users.user_id'=>$user_id
            );
            $dep_value= array(
                'department_id >'=>0
            );
            $values_coll= array(
              'college_id >'=>0  
            );
            $data['user_data'] = $this->manage_users->get_non_student($values);
            $data['college_data']=  $this->college_department_model->get_all_college($values_coll);
            $data['depart_data']=   $this->college_department_model->get_all_depart($dep_value);
        }
        
        $data['views'] = array ("profile/edit_profile_view");
        $data['user']= $user; 
            
        page_load($data);
            
    }// end edit function
    
    public function update_profile($user_id,$user){
        
        $this->form_validation->set_rules("fname","Firstname","required");
        $this->form_validation->set_rules("lname","Lastname","required");
        $this->form_validation->set_rules("phone","Lastname","required");
        
        if($user== 'student'){
            $this->form_validation->set_rules("course_id","Course","required");
            $this->form_validation->set_message('required',' ');
        
          if($this->form_validation->run() === FALSE ){
              
              $message = ' - Fields can not be empty';
              $this->edit_profile($user_id, $user,$message);
              
          }
           
          else{
              
              $values = array(  
              'first_name'=>$_POST['fname'],  
              'last_name'=>$_POST['lname'],  
              'phone_no'=>$_POST['phone'],  
              'course_id'=>$_POST['course_id'],  
              //'email'=>$_POST['email']  
            );
            
           $result = $this->manage_users->update_student($user_id,$values);
        
           if($result > 0){
          
            $message = " - updated";
            $this->edit_profile($user_id, $user,$message);
        
            
            }
            
          }//end else form validation
           
          }else{
            $this->form_validation->set_rules("seniority","","required");
            $this->form_validation->set_rules("office","","required");
            $this->form_validation->set_rules("dept","","required");
            $this->form_validation->set_message('required',' ');
        
          if($this->form_validation->run() === FALSE ){
              $message = ' - Fields can not be empty';
              $this->edit_profile($user_id, $user,$message);
          } else{
            $values = array(
              'first_name'=>$_POST['fname'],  
              'last_name'=>$_POST['lname'],  
              'phone_no'=>$_POST['phone'],  
              'seniority' =>$_POST['seniority'], 
              'office_location' =>$_POST['office'], 
              'department_id' =>$_POST['dept'], 
            ); 
            $result = $this->manage_users->update_non_student($user_id,$values);
            if($result > 0){
                $message = " - updated";
                $this->edit_profile($user_id, $user,$message);
            }
            }//end form validationis true
        }//end else other non student users
        
    }//end function update profile
}