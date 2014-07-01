<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * Author:      Devid Felix
 * Description: Controller for user login and session
 * Comments:    Check user access algorithm diagram in documentation for clarity
 */
class Register_user extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
    
        //checking session and allowed roles
        $roles = array('superuser','administrator','coordinator','supervisor','student','panel_head');
        check_session_roles($roles);
        
        $this->load->model('access/manage_users');
        $this->load->model('college_department_model');
        $this->load->model('course_model');
    }

    public function index(){
        
        if($this->session->userdata['type']=='student'){
            $values = array(
                'student_id'=>$this->session->userdata['user_id']
            );
            
            $values_course= array(
              'course_id >'=>0  
            );
            
            $data['userdata']=$this->manage_users->get_student($values);
            $data['course_data']=  $this->course_model->get_all_course($values_course);
        }else{
            $values = array(
                'non_student_users.user_id'=>$this->session->userdata['user_id']
            );
            $values_coll= array(
              'college_id >'=>0  
            );
            $values_depart= array(
              'college_id >'=>0  
            );
            
            $data['userdata']=$this->manage_users->get_non_student($values);
            $data['college_data']=  $this->college_department_model->get_all_college($values_coll);
            $data['depart_data']=  $this->college_department_model->get_all_depart($values_depart);
        }
        
        $this->load->view('profile/register_user_view',$data);
        
    }
    
    public function register(){
        
        if($this->session->userdata['type']=='student'){
            
            $this->form_validation->set_rules('fname','','required');
            $this->form_validation->set_rules('lname','','required');
            $this->form_validation->set_rules('phone','','required|regex_match[/^[0-9+]+$/]|xss_clean');
            $this->form_validation->set_rules('course','','required');
            $this->form_validation->set_rules('password','','required');
            $this->form_validation->set_rules('password_con','','required');
            $this->form_validation->set_message('required',' is required');
            $this->form_validation->set_message('regex_match',' Valid phone no required');
            
            $values = array(
                'student_id'=>$this->session->userdata['user_id']
            );
            $values_course= array(
                'course_id >'=>0  
            );
            
            $data['userdata']=$this->manage_users->get_student($values);
            $data['course_data']=  $this->course_model->get_all_course($values_course);        

            if ($this->form_validation->run() == FALSE){
        
                $data['message']='<div class="alert alert-danger text-center">Fields can not be empty</div>';
                $this->load->view('profile/register_user_view',$data);
            }else{
                
                if($_POST['password'] !== $_POST['password_con']){
                   
                    $data['message']='<div class="alert alert-warning text-center">Passwords do not match</div>'; 
                    $this->load->view('profile/register_user_view',$data);
                }  else {
                
                    $id=  $this->session->userdata['user_id'];
                    $values = array(
                        'first_name' => $_POST['fname'],
                        'last_name' => $_POST['lname'],
                        'phone_no' => $_POST['phone'],
                        'password' => md5($_POST['password']),
                        'course_id' => $_POST['course'],
                        'reg_status' => 1
                        );
                    
                    $result = $this->manage_users->update_student($id,$values);
                    
                    if($result !== NULL){
                        $data['message']='<div class="alert alert-success text-center">Registration completed successfully, you can now <a href="'. site_url().'/access/login"><b>Login<a></b></div>'; 
                        $this->load->view('profile/register_user_view',$data);
                    } else{
                        $data['message']='<div class="alert alert-warning text-center">Registration not complete, Please try again</div>'; 
                        $this->load->view('profile/register_user_view',$data);
                    }
                }//end else passwords match
                
            }//end else validation successfull
            
            
        } else{
            
            $this->form_validation->set_rules('fname','','required');
            $this->form_validation->set_rules('lname','','required');
            $this->form_validation->set_rules('phone','','required|regex_match[/^[0-9+]+$/]|xss_clean');
            $this->form_validation->set_rules('office','','required');
            $this->form_validation->set_rules('seniority','','required');
            $this->form_validation->set_rules('password','','required');
            $this->form_validation->set_rules('password_con','','required');
            $this->form_validation->set_rules('college','','required');
            $this->form_validation->set_rules('dept','','required');
            $this->form_validation->set_message('required',' is required');
            $this->form_validation->set_message('regex_match','Valid phone no required');
            
            $values = array(
                'non_student_users.user_id'=>$this->session->userdata['user_id']
            );
            $values_coll= array(
              'college_id >'=>0  
            );
            $values_depart= array(
              'college_id >'=>0  
            );
            
            $data['userdata']=$this->manage_users->get_non_student($values);
            $data['college_data']=  $this->college_department_model->get_all_college($values_coll);
            
            $data['depart_data']=  $this->college_department_model->get_all_depart($values_depart);
            
            if ($this->form_validation->run() == FALSE){
                $data['message']='<div class="alert alert-danger text-center">Fields can not be empty</div>';
                $this->load->view('profile/register_user_view',$data);
                
            }else{
                
                if($_POST['password'] !== $_POST['password_con']){
                   
                    $data['message']='<div class="alert alert-warning text-center">Passwords do not match</div>'; 
                    $this->load->view('profile/register_user_view',$data);
                }  else {
                
                    $id=  $this->session->userdata['user_id'];
                    $values = array(
                        'first_name' => $_POST['fname'],
                        'last_name' => $_POST['lname'],
                        'phone_no' => $_POST['phone'],
                        'office_location' => $_POST['office'],
                        'password' => md5($_POST['password']),
                        'seniority' => $_POST['seniority'],
                        'department_id' => $_POST['dept'],
                        'reg_status' => 1
                        );
                    
                    $result = $this->manage_users->update_non_student($id,$values);
                
                    if($result !== NULL){
                        $data['message']='<div class="alert alert-success text-center">Registration completed successfully, you can now <a href="'. site_url().'/access/login"><b>Login<a></b></div>'; 
                        $this->load->view('profile/register_user_view',$data);
                    } else{
                        $data['message']='<div class="alert alert-warning text-center">Registration not complete, Please try again</div>'; 
                        $this->load->view('profile/register_user_view',$data);
                    }
                    
                }//end else passwords match
                
                
                    }//end else validation successfull
            
            
        }//end else user is non student
        
        
    }//end function register        

}//end class