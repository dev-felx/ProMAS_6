<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


class No_session extends CI_Controller{
    function __construct() {
         
        parent::__construct();
        $this->load->model('assessment_model');
        $this->load->model('announcement_model');
        $this->load->model('panel_session_model');
        $this->load->model('access/manage_users');
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
        $data['title'] = 'sProMAS | Assessment Panels';
        $this->load->view('templates/header_out',$data);
        $this->load->view('landing/no_sess_cont');
        $this->load->view('templates/footer');
    }
}