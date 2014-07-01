<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


class Home extends CI_Controller{
    function __construct() {
         
        parent::__construct();
        
        $this->load->model('event_model');
    }
    
    public function index(){
        
       $url = $this->session->flashdata('url');
       if(($url != null)){
           redirect($this->session->flashdata('url'), 'location');
       }
        if($this->session->userdata('type')=='superuser'){
            //prepare data to be sent to view
            $data['views'] = array('landing/super_land');
  
            //load user's views
            page_load($data);
        }else if($this->session->userdata['type']=='administrator'){
            //prepare data to be sent to view
            $data['views'] = array('landing/admin_land');
            //load user's views
            page_load($data);
        }else if($this->session->userdata['type']=='coordinator'){
            //if space id exist 
            if(($this->session->userdata['space_id'] != NULL) ){
                $values = array(
                    'space_id'=>  $this->session->userdata['space_id'],
                    'type'=>  'coordinator'
                );
                $data['event'] = $this->event_model->upcoming_events($values);
                //prepare data to be sent to view
                $data['views'] = array('landing/coor_land');
                //load user's views
                page_load($data);
                
            }else{//if it doesnot exist
                redirect('project/project_space', 'location');
            }
            
            
        }else if($this->session->userdata['type']=='supervisor'){
            $this->load->model('announcement_model');
            $values = array(
                    'space_id'=>  $this->session->userdata['space_id'],
                    'type'=>  'supervisor'
                );
            $data['event'] = $this->event_model->upcoming_events($values);
            
            //prepare data to be sent to view
            $data['views'] = array('landing/svisor_land');
            $data['receiver'] = array('All groups calenders','Choose groups calenders');
            $data['groups'] = $this->announcement_model->get_grps($this->session->userdata['user_id']);
  
            //load user's views
            page_load($data);
        }else if($this->session->userdata['type']=='student'){
            
            $values = array(
                    'space_id'=>  $this->session->userdata['space_id'],
                    'type'=>  'student'
                );
            
            $data['event'] = $this->event_model->upcoming_events($values);
            //prepare data to be sent to view
            $data['views'] = array('landing/student_land');
            //load user's views
            page_load($data);
        }else if($this->session->userdata['type']=='panel_head'){
            
            redirect('assessment/assess_panel/index/'.$this->session->userdata('user_id'), 'location');
            
        }else{
           
            redirect('access/login','location');
        }
        
        
            
        }//end function index
    
       	
        public function change_role($role){
            if($this->session->userdata('user_id') != null){
                $this->session->set_userdata('type', $role);
                redirect('home','location');
            }else{
                redirect('access/login','location');
            }
	}
    }

?>
