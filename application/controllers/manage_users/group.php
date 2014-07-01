<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


class Group extends CI_Controller{
    function __construct() {
         
        parent::__construct();
        $this->load->model('project_model');
        $this->load->model('access/manage_users');
        $this->load->model('assessment_model');
    }
    
    public function index(){
        //prepare data
        $data['projects'] = $this->project_model->get_all_project(array('space_id'=>$this->session->userdata('space_id')));
        
        //get additional data
       $data['students_add'] = $this->manage_users->get_student(array('space_id'=>$this->session->userdata('space_id')));
       $data['supers_add'] = $this->manage_users->get_non_student(array('non_student_users.space_id'=>$this->session->userdata('space_id'),'roles.role'=> 'supervisor'));
       $data['panels_add'] = $this->manage_users->get_non_student(array('non_student_users.space_id'=>$this->session->userdata('space_id'),'roles.role'=> 'panel_head'));
        
        //prepare views
        $data['views'] = array('manage_users/group_view');
        $data['sub_title'] = 'Manage Projects';
        page_load($data);
    }
    
    
   public function get_grp_details(){
       $id = $_POST['id'];
       $response['students'] = $this->manage_users->get_student(array('project_id'=>$id));
       
       //get project data and supervisor
       $project = $this->project_model->get_project_row($id);
       if($project['supervisor_id'] == null){
            $response['super'] = array(array('first_name'=>'Not','last_name'=>'Set'));
       }else{
            $response['super'] = $this->manage_users->get_non_student(array('non_student_users.user_id'=>$project['supervisor_id']));
       }
       //get the panel haed
       $panel_form = $this->assessment_model->get_panel_head($id);
       if($panel_form == null){
            $response['panel'] = array(array('first_name'=>'Not','last_name'=>'Set'));
       }else{
            $response['panel'] = $this->manage_users->get_non_student(array('non_student_users.user_id'=>$panel_form['owner'],'roles.role'=> 'panel_head'));
       }
       $response['status'] = 'true';
       header('Content-type: application/json');
       exit(json_encode($response));
   }
   
   public function remove_stu(){
       $user_id = $_POST['id'];
       $data = array(
           'project_id' => null,
           'group_no' => null
       );
       $res = $this->manage_users->update_student($user_id,$data);
       if($res){
            $response['status'] = 'true';
            header('Content-type: application/json');
            exit(json_encode($response));
       }
   }
   
   public function add_stu(){
       $user_id = $_POST['id'];
       $project = $this->project_model->get_project_row($_POST['pro_id']);
       $data = array(
           'project_id' => $_POST['pro_id'],
           'group_no' => $project['group_no']
       );
       $res = $this->manage_users->update_student($user_id,$data);
       if($res){
            $response['status'] = 'true';
            header('Content-type: application/json');
            exit(json_encode($response));
       }
   }
   
   public function ch_super(){
        $project_id = $_POST['pro_id'];
        $data = array(
            'supervisor_id' => $_POST['id']
        );
        $res = $this->project_model->update_project($project_id,$data);
        if($res){
            $response['status'] = 'true';
            header('Content-type: application/json');
            exit(json_encode($response));
       }
   }
   
   public function ch_panel(){
       $project_id = $_POST['pro_id'];
        $data = array(
            'owner' => $_POST['id']
        );
        $res = $this->assessment_model->update_pres_onpro($project_id,$data);
        if($res){
            $response['status'] = 'true';
            header('Content-type: application/json');
            exit(json_encode($response));
       }
   }
}