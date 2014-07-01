<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Access extends CI_Controller{
    function __construct() {
         
        parent::__construct();
        $this->load->model('archive_model');
    }
    
    public function switcher(){
            //check for session 
            $roles = array('superuser','administrator','coordinator','supervisor','student');
            check_session_roles($roles);
            //Check type of user and grant access
            if($this->session->userdata('type') == 'coordinator'){
                $this->session->set_userdata('archive_level', 3);
            }else if($this->session->userdata('type') == 'supervisor'){
                $this->session->set_userdata('archive_level', 2);
            }if($this->session->userdata('type') == 'student'){
                $this->session->set_userdata('archive_level', 2);
            }
            
            //Redirect to archive home
            redirect('archive/archive','location');  
    }
    
    public function login() {
        $this->form_validation->set_rules("username","Email","trim|required|valid_email");
        $this->form_validation->set_rules("pass","Password","trim|required|md5");
    
        if($this->form_validation->run() == FALSE){
            $response['status'] = 'false';
            $response['data'] = validation_errors();
            header('Content-type: application/json');
            exit(json_encode($response));
        }else{
            if($this->session->userdata('user_id_arch') != null){
                $response['status'] = 'true';
                header('Content-type: application/json');
                exit(json_encode($response));
            }else{
                $res = $this->archive_model->match_user($_POST['username'],$_POST['pass']);
                $projects = $this->archive_model->get_pro_user($res['user_id']); 
                if($res){
                    $sess_data = array(
                            'user_id_arch' => $res['user_id'],
                            'username'=> $res['username'],
                            'fname' => $res['first_name'],
                            'lname' => $res['last_name'],
                            'email' => $res['username'],
                            'type'  => $res['type'],
                            'status'=> $res['acc_status'],
                            'level' => $res['level'],  
                            'projects' => $projects
                    );  
                    $this->session->set_userdata($sess_data);
                    $response['status'] = 'true';
                    header('Content-type: application/json');
                    exit(json_encode($response));
                }else{
                    $response['status'] = 'false';
                    $response['data'] = 'Wrong username or password';
                    header('Content-type: application/json');
                    exit(json_encode($response));
                }
            }
        }
        
    }
    
    
    public function logout() {
        $this->session->sess_destroy();
        //Redirect to archive home
        redirect('archive/archive','location');  
    }
}
