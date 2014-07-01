<?php
class Announce extends CI_Controller{
    
    function __construct() {
        parent::__construct();
        
        //checking session and allowed roles
        $roles = array('superuser','administrator','coordinator','supervisor','student');
        check_session_roles($roles);
        //load models
        $this->load->model('announcement_model');
        $this->load->model('project_model');
        $this->load->library('email');
    }
    
    //Load the Announcement view per user type
    public function index(){
        if($this->session->userdata['type']=='administrator'){
            $data['views'] = array('announce_view');
            $data['receiver'] = array('All','coordinatos','Students','Supervisors');
            
            page_load($data);
        }else if($this->session->userdata['type']=='coordinator'){
            //fetch anns
            $data['anns_unread'] = $this->announcement_model->get_ann_unread($this->session->userdata['user_id'],FALSE);
            $data['anns_read'] = $this->announcement_model->get_ann_read($this->session->userdata['user_id'],10);
            //prepare view data
            
            $data['views'] = array('project/announce_view');
            $data['title'] = 'sProMAS | Announcements';
            $data['receiver'] = array('All','Students','Supervisors');

            page_load($data);
        }else if($this->session->userdata['type']=='supervisor'){
            $data['anns_unread'] = $this->announcement_model->get_ann_unread($this->session->userdata['user_id'],FALSE);
            $data['anns_read'] = $this->announcement_model->get_ann_read($this->session->userdata['user_id'],10);
            
            //prep view
            $data['views'] = array('project/announce_view');
            $data['title'] = 'sProMAS | Announcements';
            $data['receiver'] = array('All groups','Choose groups');
            $data['groups'] = $this->announcement_model->get_grps($this->session->userdata['user_id']);
            
            page_load($data);
        }else if($this->session->userdata['type']=='student'){
            //fetch anns
            $data['anns_unread'] = $this->announcement_model->get_ann_unread_stu($this->session->userdata['user_id'],FALSE);
            $data['anns_read'] = $this->announcement_model->get_ann_read_stu($this->session->userdata['user_id'],10);
            //prep views
            $data['views'] = array('project/announce_view');
            $data['title'] = 'sProMAS | Announcements';
            $data['receiver'] = array('All Members','Memebers + Supervisor');
            page_load($data);
        }
    }
    
    public function send(){
        $this->form_validation->set_rules("title","Announcement title","required");
        $this->form_validation->set_rules("ann_desc","Annoucement Body","required");
        $this->form_validation->set_message('required','%s is required');
        if ($this->form_validation->run('reg') == FALSE){
                echo validation_errors();        
        }else {
            $data = array(
                'ann_title' => $_POST['title'],
                'description' => $_POST['ann_desc'],
                'space_id' => $this->session->userdata['space_id'],
                'creator_id' => $this->session->userdata['user_id'],
                'creator_role' => $this->session->userdata['type']
            );
            if($this->session->userdata['type']=='administrator'){
                
            }else if($this->session->userdata['type']=='coordinator'){
                if($_POST['receiver'] == 'All'){
                    $data['scope'] = '1';
                    $email_list = $this->announcement_model->get_email_1_2($this->session->userdata['space_id'],'all');
                }else if($_POST['receiver'] == 'Students'){
                    $data['scope'] = '2';
                    $data['sc_parameter'] = 'stu';
                    $email_list = $this->announcement_model->get_email_1_2($this->session->userdata['space_id'],'stu');
                }else if($_POST['receiver'] == 'Supervisors'){
                    $data['scope'] = '2';
                    $data['sc_parameter'] = 'non_stu';
                    $email_list = $this->announcement_model->get_email_1_2($this->session->userdata['space_id'],'non_stu');
                } 
                
                //send to db
                $result = $this->announcement_model->create_new($data);
                if($result){
                    if($_POST['priority'] == '1'){
                        $this->email->from('announcements@promas.co.tz', 'ProMAS');
                        $this->email->subject('Annoucement: '.$_POST['title']);
                        $this->email->message($_POST['ann_desc']);
                        $this->email->to($email_list);
                        //send email
                        $result2 = $this->email->send();
                        if($result2){
                            echo 'true';
                        }else{
                            echo 'Announcements saved but emails could not be sent';
                        }
                    }else{
                        echo 'true';
                    }
                }else{
                    echo 'Something went wrong, Try again.';
                }
            }else if($this->session->userdata['type']=='supervisor'){
                if($_POST['receiver'] == 'All groups'){
                    $data['scope'] = '3';
                    $data['sc_parameter'] = $this->session->userdata['user_id'];
                    $email_list = $this->announcement_model->get_email_3($this->session->userdata['space_id'],$this->session->userdata['user_id']);
                    
                    $result = $this->announcement_model->create_new($data);
                    if($result){
                        if($_POST['priority'] == '1'){
                            $this->email->from('announcements@promas.co.tz', 'ProMAS');
                            $this->email->subject('Annoucement: '.$_POST['title']);
                            $this->email->message($_POST['ann_desc']);
                            $this->email->to($email_list);
                            //send email
                            $result2 = $this->email->send();
                            if($result2){
                                echo 'true';
                            }else{
                                echo 'Announcements saved but emails could not be sent';
                            }
                        }else{
                            echo 'true';
                        }
                    }else{
                        echo 'Something went wrong, Try again.';
                    }
                }else if($_POST['receiver'] == 'Choose groups'){
                    $data['scope'] = '4';
                    $data['sc_parameter2'] = $this->session->userdata['user_id'];
                    $email_list = $this->announcement_model->get_email_4($this->session->userdata['space_id'],$_POST['groups']);
                    
                    foreach ($_POST['groups'] as $value) {
                        $data['sc_parameter'] = $value;
                        $result = $this->announcement_model->create_new($data);    
                    }
                    if($result){
                        if($_POST['priority'] == '1'){
                            $this->email->from('announcements@promas.co.tz', 'ProMAS');
                            $this->email->subject('Annoucement: '.$_POST['title']);
                            $this->email->message($_POST['ann_desc']);
                            $this->email->to($email_list);
                            //send email
                            $result2 = $this->email->send();
                            if($result2){
                                echo 'true';
                            }else{
                                echo 'Announcements saved but emails could not be sent';
                            }
                        }else{
                            echo 'true';
                        }
                    }else{
                        echo 'Something went wrong, Try again.';
                    }
                }  
            }else if($this->session->userdata['type']=='student'){
                $data['scope'] = '5';
                $data['sc_parameter'] = $this->session->userdata['project_id']; 
                
                if($_POST['receiver'] != 'All Members'){
                    $visor= $this->project_model->get_supervisor($this->session->userdata['project_id']);
                    $data['sc_parameter2'] = $visor;
                    $email_list = $this->announcement_model->get_email_5($this->session->userdata['space_id'],$this->session->userdata['project_id'],$visor);
                }else{
                    $email_list = $this->announcement_model->get_email_5($this->session->userdata['space_id'],$this->session->userdata['project_id'],FALSE);
                }
                $result = $this->announcement_model->create_new($data);
                if($result){
                    if($_POST['priority'] == '1'){
                        $this->email->from('announcements@promas.co.tz', 'ProMAS');
                        $this->email->subject('Annoucement: '.$_POST['title']);
                        $this->email->message($_POST['ann_desc']);
                        $this->email->to($email_list);
                        //send email
                        $result2 = $this->email->send();
                        if($result2){
                            echo 'true';
                        }else{
                            echo 'Announcements saved but emails could not be sent';
                        }
                    }else{
                        echo 'true';
                    }
                }else{
                    echo 'Something went wrong, Try again.';
                }
            }
            
            
        }
    }
    
    
    public function get_more() {
        $user_id = $_POST['id'];
        $lim = $_POST['lim'];
        if($this->session->userdata['type'] !='student'){
            $data = $this->announcement_model->get_ann_read($this->session->userdata['user_id'],$lim);
        }else{
            $data = $this->announcement_model->get_ann_read_stu($this->session->userdata['user_id'],10);
        }
        
        echo json_encode($data);
    }
} 
