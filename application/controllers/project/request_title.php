<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Request_title extends CI_Controller{
    
    public function __construct() {
        parent::__construct();
    
        $roles = array('superuser','administrator','coordinator','supervisor', 'student');
        check_session_roles($roles);
        
        $this->load->model('project_model');
    }
    
    public function index(){
        $data['views'] = array('project/request_title');
        $data['projects'] = $this->project_model->get_all_project(array('space_id'=>$this->session->userdata('space_id')));
        if($this->session->userdata('type') == 'student'){
            $data['pro_t'] = $this->project_model->get_title($this->session->userdata('project_id'));
        }
        page_load($data);
    }
    
    public function update(){
        
        $this->form_validation->set_rules("title","Project Title", "required");
        $this->form_validation->set_rules("description","Project Description","required");
        
        if ($this->form_validation->run() == FALSE){
            $response['status'] = 'false';
            $response['data'] = validation_errors();
            header('Content-type: application/json');
            exit(json_encode($response));
        }else {
            $data = array(
                'title' => $_POST['title'],
                'description' => $_POST['description'],
            );
            $this->project_model->update_title($this->session->userdata('project_id'), $data);
        }
        
        $data['pro_t'] = $this->project_model->get_title($this->session->userdata('project_id'));
        $data['views']= array('project/request_title');    
        page_load($data);
    }
    
    public function get_details($id){
        $data['pro'] = $this->project_model->get_title($id);
        $data['status'] = 'true';
        header('Content-type: application/json');
        exit(json_encode($data));
    }    
}