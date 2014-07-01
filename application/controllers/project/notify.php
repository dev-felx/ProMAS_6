<?php
class Notify extends CI_Controller{
    
    function __construct() {
        parent::__construct();
        
        //check session ? :redirect to home
    
        $roles = array('superuser','administrator','coordinator','supervisor','student');
        check_session_roles($roles);
        //load models
        $this->load->model('notification_model');
    }
    
    //Load the notification view per user type
    public function index(){
        if($this->session->userdata['type'] != 'student'){
            //fetch nots
              $data['nots_unread'] = $this->notification_model->get_not_unread($this->session->userdata['user_id'],FALSE);
              $data['nots_read'] = $this->notification_model->get_not_read($this->session->userdata['user_id'],10);
            
            //prep view
            $data['title'] = 'sProMAS | Notifications'; 
            $data['views'] = array('project/notify_view');
            page_load($data);
        }else{
            //fetch nots
            $data['nots_unread'] = $this->notification_model->get_not_unread_stu($this->session->userdata['user_id'],FALSE);
            $data['nots_read'] = $this->notification_model->get_not_read_stu($this->session->userdata['user_id'],10);
            
            //prep view
            $data['title'] = 'sProMAS | Notifications'; 
            $data['views'] = array('project/notify_view');
            page_load($data);
        }
    }
}