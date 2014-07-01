<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Panel_session extends CI_Controller{
    
    function __construct() {
        
        
        parent::__construct();
        
        $roles = array('superuser','administrator','coordinator');
        check_session_roles($roles);
        
        $this->load->model('project_model');
        $this->load->model('panel_session_model');
        $this->load->model('access/manage_users');
    }
    
    public function index(){
        //prepare data
        $data['projects'] = $this->project_model->get_all_project(array('space_id'=>$this->session->userdata('space_id')));
        $data['panel_heads'] = $this->manage_users->get_non_student_no_department(array('non_student_users.space_id'=>$this->session->userdata('space_id'),'roles.role'=> 'panel_head'));
        $data['all_members'] = $this->panel_session_model->get_members(array('panel_member_id >'=>0,'space_id'=>$this->session->userdata('space_id')));
        //prepare views
        $data['views'] = array('manage_users/panel_session_view');
        $data['sub_title'] = 'Manage Panel Session';
        page_load($data);
    }
    
    public function get_session_details(){
       $id = $_POST['id'];
       $response['projects'] = $this->panel_session_model->get_projects(array('owner'=>$id,'space_id'=>$this->session->userdata('space_id')));
       $response['members'] = $this->panel_session_model->get_members(array('panel_head_id'=>$id,'space_id'=>$this->session->userdata('space_id')));
       $response['session_details'] = $this->panel_session_model->get_session_details(array('panel_head_id'=>$id,'space_id'=>$this->session->userdata('space_id')));
        
       $response['status'] = 'true';
       header('Content-type: application/json');
       exit(json_encode($response));
    }
    public function change_panel_session(){
       $panel_head = $_POST['panel_head_id'];
       $venue = $_POST['venue'];
       $time = date('Y-m-d H:i', strtotime(str_replace('.', '-', $_POST['time'])));
       $data = array(
           'venue'=>$venue,
           'time'=>$time,
           );
       $data_exist = array(
           'panel_head_id'=>$panel_head,
           'space_id'=>$this->session->userdata('space_id')
           );
        $table = 'panel_session';
        //checking if the user exist in the db
        $result_exist = $this->manage_users->check_value_exists($table, $data_exist);
        if(!$result_exist){
            $data['panel_head_id']=$panel_head;
            $data['space_id']=$this->session->userdata('space_id');
            $result = $this->panel_session_model->add_session_details($data);
            if($result!=NULL){
                $response['status'] = 'true';
            }else{
               $response['status'] = 'false';
            }
        }else{
            $result = $this->panel_session_model->update_session_details($panel_head,$data);
            if($result!=NULL){
                $response['status'] = 'true';
            }  else {
               $response['status'] = 'false';

            }
        }
       header('Content-type: application/json');
       exit(json_encode($response));
    }
    
    public function add_project(){
       $project_id = $_POST['project_id'];
       $panel_head_id = $_POST['panel_head_id'];
       //print_r($panel_head_id); die();
       $project = $this->project_model->get_project_row($project_id);
       $data = array(
           'owner'=>$panel_head_id,
           'project_id' => $project_id,
           'group_no' => $project['group_no'],
           'project_name'=>$project['title'],
           'space_id'=>  $this->session->userdata['space_id']
       );
       $check_proj = $this->panel_session_model->count_prev_projects($project_id,$panel_head_id);
       if($check_proj !== 2){
           $data['semester']= 1;
           $data['pres_type']= 'Project proposal';

           $res_1 = $this->panel_session_model->add_project($data);
           if($res_1 !=NULL){
               $data['semester']= 2;
               $data['pres_type']= 'Project report';
               $res_2 = $this->panel_session_model->add_project($data);
            }
           if($res_1 != null && $res_2 != null){
                $response['status'] = 'true';
           }   
       }else{
           $response['status'] = 'fail';
       }   
        header('Content-type: application/json');
        exit(json_encode($response));
    }
   
   public function update_member(){
        $member_id = $_POST['member_id'];
        $panel_head_id = $_POST['panel_head_id'];
        $project = $this->panel_session_model->update_member($member_id,array('panel_head_id'=>$panel_head_id));
        if($project){
                $response['status'] = 'true';
        }else{
           $response['status'] = 'fail';
        }   
           
        header('Content-type: application/json');
        exit(json_encode($response));
   }
   
   public function remove_project(){
       $project_id = $_POST['project_id'];
       
       $data = array(
           'project_id' =>$project_id,
        );
       
       $res= $this->panel_session_model->delete_project($data);
       if($res){
            $response['status'] = 'true';
       }
        header('Content-type: application/json');
        exit(json_encode($response));
   }
   
   public function members($user,$message=NULL){
        $result = $this->panel_session_model->get_members(array('panel_member_id >'=>0));
        $data['table_head'] = array('first_name','last_name','email','phone number');
        $data['message']= urldecode($message);
        $data['table_data'] = $result;
        $data['views'] = array ("manage_users/member_view");
        $data['user']= $user; 
        page_load($data);
    }// end function users
    
    public function notify(){
        $panel_head_id = $_POST['panel_head_id'];
        
        $members = $this->panel_session_model->get_members(array('panel_head_id'=>$panel_head_id,'space_id'=>$this->session->userdata('space_id')));
        $panel_head = $this->manage_users->get_non_student_no_department(array('non_student_users.space_id'=>$this->session->userdata('space_id'),'roles.role'=> 'panel_head','non_student_users.user_id'=>$panel_head_id));
         
        $from = "admin@promas.com";
        $site_url = site_url();
        $to = $panel_head[0]['email'];
        $fname = $panel_head[0]['first_name'];
        $user_id= $panel_head_id;
        $subject = "sProMAS | Panel head notification email";
        $message = " 
                            <html>
                            <head>
                            <title>sProMAS | Panel Head notification email</title>
                            </head>
                            <body>
                                    <h4>Hello $fname,</h4>
                                     <p>Use the following link to view your panel session details </p>    
                                     <a href='$site_url/assessment/assess_panel/index/$user_id'>View panel session details</a>
                                    <p>Sincerely,</p>
                                    <p>sProMAS admin.</p>
                            </body>
                            </html>";
        $send_email_panel =  send($from,$to,$subject,$message);
                   
        foreach ($members as $value){
            $from = "admin@promas.com";
            $site_url = site_url();
            $fname = $value['first_name'];
            $user_id= $value['panel_member_id'];
            $subject = "sProMAS | Panel member notification email";
            $message = " 
                            <html>
                            <head>
                            <title>sProMAS | Panel member notification email</title>
                            </head>
                            <body>
                                    <h4>Hello $fname,</h4>
                                     <p>Use the following link to view your panel session details </p>    
                                     <a href='$site_url/assessment/no_session/sess_mem/$user_id'>View panel session details</a>
                                    <p>Sincerely,</p>
                                    <p>sProMAS admin.</p>
                            </body>
                            </html>";
            $send_email_member =  send($from,$value['email'],$subject,$message);
        }
        
        if($send_email_member && $send_email_panel){
            $response['status'] = 'true';
        }else{
            $response['status'] = 'fail';
        }
        header('Content-type: application/json');
        exit(json_encode($response));
    }


    public function add_member($message=NULL){
        $values = array(
                'student_projects.project_id >'=>0, 
            );
        $data['user_data'] = $this->project_model->get_all_project($values);
        $data['message']=$message;
        $data['user'] = 'panel_member';
        $data['views'] = array('manage_users/add_member_view');
        page_load($data);
        
    }
    
    public function adding_member($user){
            $this->form_validation->set_rules('fname', 'First Name', 'required|trim');
            $this->form_validation->set_rules('lname', 'Last Name', 'required|trim');
            $this->form_validation->set_rules('phone_no', '', 'required|trim');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email|trim');
            
            
            $this->form_validation->set_message('required',' is required');
            $data['user'] = $user;
   
        if ($this->form_validation->run() == FALSE){
            $message = '<div class="alert alert-warning text-center">Fields can not be empty</div>';
            $this->add_member($message);
        }else{//else validation successfull
           
            $data = array(
              'first_name'=>$_POST['fname'],  
              'last_name'=>$_POST['lname'],  
              'email'=>$_POST['email'],
              'phone_no'=>$_POST['phone_no'],
               'space_id'=>  $this->session->userdata['space_id']
            );
            
            $data_exist = array(
                    'email' => $_POST['email'],
                    'space_id'=>  $this->session->userdata['space_id']
                     );
            $table = 'panel_member';
            //checking if the user exist in the db
            $result_exist = $this->manage_users->check_value_exists($table, $data_exist);
            if(!$result_exist){
                $res= $this->panel_session_model->add_member($data);
                if($res!=NULL){
                    $fname = $_POST['fname'];
                    $from = "admin@promas.com";
                    $site_url = site_url();
                    $to = $_POST['email'];
                    $subject = "sProMAS | Project Panel member registration";
                    $message = " 
                        <html>
                        <head>
                        <title>ProMAS | Account Registration</title>
                        </head>
                        <body>
                                <h4>Hello $fname </h4>
                                 <p>You have been registered in sProMAS as one of the panel members,</p>   
                                 <p>For more details contact Department of Computer Science and Engineering project coordinator </p>
                                 <p>Sincerely,</p>
                                <p>ProMAS admin.</p>
                        </body>
                        </html>";
                    }
                    //sending email
                $send_email =  send($from,$to,$subject,$message);
                if(($send_email == TRUE)&& $res != NULL){
                    $message = '<div class="alert alert-success  text-center">User added and email sent</div>';
                    $this->add_member($message);

                }else if(($send_email == FALSE)&& $res != NULL){
                    $message = '<div class="alert alert-success  text-center">User added but email not sent</div>';
                    $this->add_member($message);
                }
            }else{
                $message = '<div class="alert alert-warning text-center">Panel member with the same email exists</div>';
                $this->add_member($message);
            }
        }//end if form validation is true     
        }//eend function add
        
        public function delete_member($user_id){
        
           $result_del = $this->panel_session_model->delete_member(array('panel_member_id'=>$user_id,'space_id'=>$this->session->userdata['space_id']));
           if($result_del>0){
                $message = "Panel member successfully deleted";
            } else{
                $message = "User not deleted";
            }
            $this->members('panel_member', $message);
    }// end delete function
        public function delete_all_member(){
        
           $result_del = $this->panel_session_model->delete_member(array('panel_member_id >'=>0,'space_id'=>$this->session->userdata['space_id']));
           if($result_del>0){
                $message = "All Panel member successfully deleted";
            } else{
                $message = "User not deleted";
            }
            $this->members('panel_member', $message);
    }// end delete function

   
}
