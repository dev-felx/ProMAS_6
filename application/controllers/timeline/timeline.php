<?php

/**
 * Description of timeline
 *
 * @author User
 */
class Timeline extends CI_Controller {
    
    public function __construct() {
         
        parent::__construct();
        
        // checking session and allowed roles
        $roles = array('superuser','administrator','coordinator','supervisor','student');
        check_session_roles($roles);
        
        $this->load->model('event_model');
        $this->load->model('project_model');
    }
    
    public function add_event(){
        //validate form
        $this->form_validation->set_rules("title","Event title","required");
        $this->form_validation->set_rules("description","Event desciption","required");
        $this->form_validation->set_rules("date_start","Start date","required");
        $this->form_validation->set_rules("date_end","End date","required");
        $this->form_validation->set_message('required','%s is required here');
        $this->form_validation->set_error_delimiters('', '');
        
        if(isset($_POST['output'])){
            $this->form_validation->set_rules("res_name","Output Name","required");
        }
        if ($this->form_validation->run('reg') == FALSE){
                $errors = array();
                // Loop through $_POST and get the keys
                foreach ($this->input->post() as $key => $value)
                {
                    // Add the error message for this field
                    $errors[$key] = form_error($key);
                }
                $response['errors'] = array_filter($errors); // Some might be empty
                $response['status'] = 'not_valid';

                
        }else{
            if(!isset($_POST['receiver']) || $_POST['receiver'] == '1'){
            //prepare data
            $data = array(
                        'title' => $_POST['title'],
                        'desc' => $_POST['description'],
                        'start' => $_POST['date_start'],
                        'end' => $_POST['date_end'],
                        'space_id' => $this->session->userdata('space_id'),
                        'creator_id' => $this->session->userdata('user_id'),
                        'creator_type' => $this->session->userdata('type')
            );
            if($this->session->userdata('type') == 'student'){
                $data['project_id'] = $this->session->userdata('project_id');
            }
            //Check if output  is present
            if(isset($_POST['output'])){
                    $data_2 = array(
                        'file_name' => $_POST['res_name'],
                        'space_id' => $this->session->userdata('space_id'),
                        'file_creator_id' => $this->session->userdata('user_id'),
                        'file_due_date'=>$_POST['date_end']
                    );
                    $this->load->model('file_model');
                    //$this->file_model->new_file($data_2);
            }
            $res = $this->event_model->new_event($data);    
            if($res){
                $response['status'] = 'success';
            }
            }else{
                if(!isset($_POST['groups'])){
                    $response['status'] = 'g_err';
                }else{
                    $data = array(
                        'title' => $_POST['title'],
                        'desc' => $_POST['description'],
                        'start' => $_POST['date_start'],
                        'end' => $_POST['date_end'],
                        'space_id' => $this->session->userdata('space_id'),
                        'creator_id' => $this->session->userdata('user_id'),
                        'creator_type' => $this->session->userdata('type')
                    );
                    foreach ($_POST['groups'] as $value) {
                        $data['project_id'] = $value;
                        $res = $this->event_model->new_event($data); 
                        if($res){
                            $response['status'] = 'success';
                        }
                    }  
                }
            }
        }
        // You can use the Output class here too
        header('Content-type: application/json');
        exit(json_encode($response));
    }
    
    public function get_for_edit(){
       $res = $this->event_model->get_event($_POST['id']);
       echo json_encode($res);
    }
    
    public function edit_event(){
        //validate form
        $this->form_validation->set_rules("title","Event title","required");
        $this->form_validation->set_rules("description","Event desciption","required");
        $this->form_validation->set_rules("date_start","Start date","required");
        $this->form_validation->set_rules("date_end","End date","required");
        $this->form_validation->set_message('required','%s is required here');
        $this->form_validation->set_error_delimiters('', '');
        
        if(isset($_POST['output'])){
            $this->form_validation->set_rules("res_name","Output Name","required");
        }
        if ($this->form_validation->run('reg') == FALSE){
                $errors = array();
                // Loop through $_POST and get the keys
                foreach ($this->input->post() as $key => $value)
                {
                    // Add the error message for this field
                    $errors[$key] = form_error($key);
                }
                $response['errors'] = array_filter($errors); // Some might be empty
                $response['status'] = 'not_valid';

                
        }else{
            //Check if output  is present
            if(isset($_POST['output'])){
                    
            }
            //prepare data
            $data = array(
                        'title' => $_POST['title'],
                        'desc' => $_POST['description'],
                        'start' => $_POST['date_start'],
                        'end' => $_POST['date_end'],
                        'space_id' => $this->session->userdata('space_id'),
                        'creator_id' => $this->session->userdata('user_id')
            );
            $res = $this->event_model->update_event($_POST['id'],$data);    
            if($res){  
                $response['status'] = 'success';
            }
        }
        // You can use the Output class here too
        header('Content-type: application/json');
        exit(json_encode($response));
    }
    
    public function del_event(){
        $res = $this->event_model->del_event($_POST['id']);
        if($res){
            $response['status'] = 'success';
        }else{
            $response['status'] = 'fail';
        }
        header('Content-type: application/json');
        exit(json_encode($response));
    }
    
    public function c_event(){
        $values = array(
            'space_id'=>  $this->session->userdata['space_id'],
            'creator_type'=>  'coordinator'
         );
        echo json_encode($this->event_model->load_events($values));
    }
    
    public function s_event(){
        $values= array(
            'space_id'=>  $this->session->userdata['space_id'],
            'creator_id'=>  $this->session->userdata['user_id'],
            'creator_type'=>  'supervisor'
         );
        echo json_encode($this->event_model->load_events($values));
    }
    
    public function ts_event($data){
        $values= array(
            'space_id'=>  $this->session->userdata['space_id'],
            'project_id' => $data,
            'creator_type'=>  'student'
         );
        echo json_encode($this->event_model->load_events($values));
    }
    
    public function ss_event(){
        
        $values= array(
            'space_id'=>  $this->session->userdata['space_id'],
            'creator_id'=> $this->project_model->get_supervisor($this->session->userdata['project_id']),
            'creator_type'=>  'supervisor'
         );
        echo json_encode($this->event_model->load_events($values));
    }
    
    public function st_event(){
        $values= array(
            'space_id'=>  $this->session->userdata['space_id'],
            'project_id'=>  $this->session->userdata['project_id'],
            'creator_type'=>  'student'
         );
        echo json_encode($this->event_model->load_events($values));
    }
    
    public function event(){
        $values = array (
            'space_id'=>  $this->session->userdata['space_id'],
            'type' => $this->session->userdata['type'],
            //'sup_id'=> $this->project_model->get_supervisor($this->session->userdata['project_id']),
            //'project_id'=>  $this->session->userdata['project_id'],
        );
        
        $data['test'] = $this->event_model->list_events($values);
        $data['views']= array('timeline/event_view');
        page_load($data);
    }
}