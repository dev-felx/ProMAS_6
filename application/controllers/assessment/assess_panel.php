<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


class Assess_panel extends CI_Controller{
    function __construct() {
         
        parent::__construct();
        //checking session and allowed roles
        $roles = array('panel_head', 'supervisor','coordinator','administrator');
        check_session_roles($roles);
        $this->load->model('assessment_model');
        $this->load->model('announcement_model');
        $this->load->model('panel_session_model');
        $this->load->model('access/manage_users');
    }
    
    public function index($user_id){
        //prepare data
        $data['sess_det']=$this->panel_session_model->get_session_details(array('panel_head_id' => $user_id));
        
        if($data['sess_det'] != null){
            $data['sess_head']=$this->manage_users->get_non_student_no_role(array('user_id'=>$user_id));
            $data['sess_mem']=$this->panel_session_model->get_members(array('panel_head_id' => $user_id));
            $data['sess_grp']=$this->panel_session_model->get_projects(array('owner' => $user_id));
            
        }
        
        //prepare view
        $data['views'] = array('/landing/panel_land');
        $data['sub_title'] = 'My Presentation Assessment Session';
        //load view
        
        page_load($data);
    }
    
    public function sess_mem($user_id){
        $exist=$this->panel_session_model->get_members(array('panel_member_id' => $user_id));
        if($exist){
            $data['sess_det']=$this->panel_session_model->get_session_details(array('panel_head_id' =>  $exist[0]['panel_head_id']));
            $data['sess_head']=$this->manage_users->get_non_student_no_role(array('user_id'=>$exist[0]['panel_head_id']));
            $data['sess_mem']=$this->panel_session_model->get_members(array('panel_head_id' => $exist[0]['panel_head_id']));
            $data['sess_grp']=$this->panel_session_model->get_projects(array('owner' => $exist[0]['panel_head_id']));
        }
        
        //prepare view
        $data['views'] = array('/landing/panel_land');
        $data['sub_title'] = 'My Presentation Assessment Session';
        //load view
        
        page_load($data);
    }
    
    public function pres(){
        //get data
        $data['forms'] = $this->assessment_model->get_pres($this->session->userdata('user_id'));
        
        
        //prepare views
        $data['sub_title'] = 'Presentation Assessment';
        $data['views'] = array('/assessment/pres_view');
        $data['title'] = 'sProMAS | Assessment';
        //load view
        page_load($data);
        
    }
    
    public function save_form() {
        $this->form_validation->set_rules("im","Implementation Methodology","required|is_natural|less_than[8]");
        $this->form_validation->set_rules("pq","Presentation Quality","required|is_natural|less_than[9]");
        $this->form_validation->set_rules("ptc","Presentation Time Compliance","required|is_natural|less_than[6]");
        $this->form_validation->set_rules("sc","System correctness","required|is_natural|less_than[16]");
        $this->form_validation->set_rules("sf","System Functionalities","required|is_natural|less_than[11]");
        $this->form_validation->set_message('required','%s marks are required');
        $this->form_validation->set_message('is_natural','%s marks have to a natural number');
        if ($this->form_validation->run('reg') == FALSE){
                echo validation_errors();        
        }else {
            $data = array(
                'im' => $_POST['im'],
                'pq' => $_POST['pq'],
                'ptc' => $_POST['ptc'],
                'sc' => $_POST['sc'],
                'sf' => $_POST['sf'],
                'com' => $_POST['com'],
                'form_id' => $_POST['form_id']
            );
            
            $res = $this->assessment_model->save_form_pres($data);
            if($res){
                $response['forms'] = $this->assessment_model->get_pres($this->session->userdata('user_id'));
                $response['status'] = 'cool';
            }
            header('Content-type: application/json');
            exit(json_encode($response));
        }
    }
}