<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Users extends CI_Controller{
    function __construct() {
         
        parent::__construct();
        $this->load->model('archive_model');
        $this->load->model('access/manage_users');
        $this->load->helper(array('file','directory'));
        $this->load->model('project_space_model');
        $this->acc_year = $this->project_space_model->get_all_project_space(array('space_id'=>$this->session->userdata['space_id']));
        
    }
    
    public function index(){
        //get data
        $data['acc_yr'] = str_replace('/','-' ,$this->acc_year[0]['academic_year']);
        $data['req_count'] = count($this->archive_model->get_req());
        $data['req'] = $this->archive_model->get_req();
        $data['users'] = $this->archive_model->get_all();
        $data['views'] = array('archive/access/user_list');
  
        //load user's views
        $data['title'] = 'sProMAS | Archive Users'; 
        page_load($data);
    }
    
    public function show_req(){
        //get data
        $req = $this->archive_model->get_req();
        foreach ($req as $key => $value) {
            if($value['acc_type'] == 0){
                $user = $this->archive_model->get_user_id($value['user_id']);
            }else{
                if($value['user_type'] == 'stu'){
                    $user = $this->manage_users->get_student(array('student_id' => $value['user_id']));  
                }else{
                    $user_ = $this->manage_users->get_non_student(array('non_student_users.user_id' => $value['user_id']));
                    $user = $user_[0];
                }
            }
            $req[$key] = array_merge($req[$key], $user);
        }
        $data['title'] = 'sProMAS | Access Request'; 
        $data['req'] = $req;    
        $data['views'] = array('archive/access/req_list');
        
        //load user's views
        page_load($data);
    }
    public function add_single(){
        //form validation
        $this->form_validation->set_rules("fname","First Name","required");
        $this->form_validation->set_rules("lname","Last Name","required");
        $this->form_validation->set_rules("level","Access level","required");
        $this->form_validation->set_rules("email","Email","required|valid_email");
        if($_POST['type'] == 'student'){
            $this->form_validation->set_rules("reg","Registration Number","required");
        }
        
         if ($this->form_validation->run() == FALSE){
              $response['status'] = 'false';
              $response['data'] = validation_errors();
              header('Content-type: application/json');
              exit(json_encode($response));
        }else {
            $data = array(
                'first_name' => $_POST['fname'],
                'last_name' => $_POST['lname'],
                'username' => $_POST['email'],
                'type' => $_POST['type'],
                'level' => $_POST['level'],
                'password' => md5($_POST['lname'])
            );
            
            if($_POST['type'] == 'student'){
               $data['reg_no'] = $_POST['reg'];
            }
            
            $res = $this->archive_model->new_user($data);
            if($res){
                $message = "Welcome to Promas Archive.<br/> Log in using<br/>Email:".$data['username']."<br/>Password (last name):".$data['last_name'];
                $res2 = send('admin@promas.com', $data['username'], 'Promas Archive Account Activation', $message);
                if($res2){
                    $response['status'] = 'true';
                    header('Content-type: application/json');
                    exit(json_encode($response));
                }else{
                    $response['status'] = 'false';
                    $response['data'] = 'User added but email failed';
                    header('Content-type: application/json');
                    exit(json_encode($response));
                }
            }else{
                $response['status'] = 'false';
                $response['data'] = 'User exists, recover password or use another email';
                header('Content-type: application/json');
                exit(json_encode($response));
            }
        }
    }
    
    public function request() {
        if($this->session->userdata('user_id_arch') == ''){
            //form validation
            $this->form_validation->set_rules("fname","First Name","required");
            $this->form_validation->set_rules("lname","Last Name","required");
            $this->form_validation->set_rules("level","Access level","required");
            $this->form_validation->set_rules("email","Email","required|valid_email");
            if($_POST['type'] == 'student'){
                $this->form_validation->set_rules("reg","Registration Number","required");
            }

            if ($this->form_validation->run() == FALSE){
                  $response['status'] = 'false';
                  $response['data'] = validation_errors();
                  header('Content-type: application/json');
                  exit(json_encode($response));
            }else {
                $data = array(
                    'first_name' => $_POST['fname'],
                    'last_name' => $_POST['lname'],
                    'username' => $_POST['email'],
                    'type' => $_POST['type'],
                    'acc_status' => 0,
                    'level' => 1,
                    'password' => md5($_POST['lname']),
                    'info' => $_POST['info']
                );

                if($_POST['type'] == 'student'){
                   $data['reg_no'] = $_POST['reg'];
                }

                $res = $this->archive_model->new_user($data);
                $user_id = $this->archive_model->get_user($_POST['email']);
                if($res){
                    $db_data = array(
                      'user_id' =>  $user_id['user_id'],
                      'project_id' => $_POST['project_id'],
                      'project_name' => $_POST['pname'],
                      'level' => $_POST['level']  
                    );
                    if($_POST['type'] == 'student'){
                        $db_data['user_type'] = 'stu';
                    }else{
                        $db_data['user_type'] = 'nonstu';
                    }
                    $res2 = $this->archive_model->req($db_data);
                    if($res2){
                        $response['status'] = 'true';
                        header('Content-type: application/json');
                        exit(json_encode($response));
                    }
                }else{
                    $response['status'] = 'false';
                    $response['data'] = 'User exists, recover password or use another email';
                    header('Content-type: application/json');
                    exit(json_encode($response));
                }
            }
        }else{
            $db_data = array(
                  'user_id' =>  $this->session->userdata('user_id_arch'),
                  'project_id' => $_POST['project_id'],
                  'project_name' => $_POST['pname'],
                  'level' => $_POST['level']  
                );
            if($this->session->userdata('type') == 'student'){
                        $db_data['user_type'] = 'stu';
                    }else{
                        $db_data['user_type'] = 'nonstu';
                    }
                $res2 = $this->archive_model->req($db_data);
                if($res2){
                    $response['status'] = 'true';
                    header('Content-type: application/json');
                    exit(json_encode($response));
                }
        }
        
    }
    
    
    public function request_man() {
               $db_data = array(
                  'user_id' =>  $this->session->userdata('user_id'),
                  'project_id' => $_POST['project_id'],
                  'project_name' => $_POST['pname'],
                  'level' => $_POST['level'],
                  'acc_type' => 1,
                );
               if($this->session->userdata('type') == 'student'){
                    $db_data['user_type'] = 'stu';
                }else{
                    $db_data['user_type'] = 'nonstu';
                }
                $res2 = $this->archive_model->req($db_data);
                if($res2){
                    $response['status'] = 'true';
                    header('Content-type: application/json');
                    exit(json_encode($response));
                }
    }
    
    public function del($id){
        $res = $this->archive_model->del($id);
        if($res){
            redirect('archive/users','location'); 
        }
    }
    
    public function en($id) {
        $res = $this->archive_model->en($id);
        if($res){
           redirect('archive/users','location'); 
        }
    }
    public function dis($id) {
        $res = $this->archive_model->dis($id);
        if($res){
            redirect('archive/users','location'); 
        }
    }
    public function rej($id,$name,$user) {
        $ress = $this->archive_model->get_user_id($user);
        $mail = $ress['username'];
        $res = $this->archive_model->rej($id);
        if($res){
           $message = "Sorry your request for access was rejected.<br/> Project:".$name;
           send('admin@promas.com', $mail, 'Promas Archive Access Request', $message);
           redirect('archive/users/show_req','location'); 
        }
    }
    public function grant($id,$rel,$name) {
        $ress = $this->archive_model->get_user_id($id);
        $mail = $ress['username'];
        $res = $this->archive_model->grant($id,$rel);
        if($res){
            $message = "Request for access was grant. Re access the project to get requested data<br/> Project:".$name;
            send('admin@promas.com', $mail, 'Promas Archive Access Request', $message);
            redirect('archive/users/show_req','location'); 
        }
    }
    
    public function add_multiple($user){
        
                $this->load->library('csv_reader');
                //fetching content from the csv file
                $content = $this->csv_reader->read_csv_file($_POST['file_path'],$user);
                $data['user'] = $user;
                $i=0;
                $j=0;
                $k=0;
                
                   foreach($content as $field){
                            $values = array(
                                  'first_name' =>$field['Firstname'] ,
                                  'last_name' =>$field['Lastname'] ,
                                  'password' =>md5(strtolower($field['Lastname'])) ,
                                  'reg_no' =>$field['Registration no'] ,
                                  'username'=> $field['Email'],
                                  'level'=>2,
                                  'type'=>'student'
                            );
                            //print_r($values);                            die();
                            $res = $this->archive_model->new_user($values);
                            
                            if($res){
                                $fname= $field['Firstname'];
                                $lname= strtolower($field['Lastname']);
                                $email = $field['Email'];
                                $from = "admin@promas.com";
                                $to = 'stud1@localhost.com';
                                $subject = "ProMAS | Account activation";
                                $url =  site_url();
                                $message = " 
                                    <html>
                                    <head>
                                    <title>ProMAS | Account </title>
                                    </head>
                                    <body>
                                            <h4>Hello $fname,</h4>
                                             <p>Use credentials below to login into the system </p>   
                                             <p>Username : $email </p>   
                                             <p>Password : $lname  </p>
                                             <p>Click the link below</p>    
                                             <a href='$url/archive/archive'>Login into promas archive</a>
                                            <p>Sincerely,</p>
                                            <p>ProMAS admin.</p>
                                    </body>
                                    </html>";
                                //sending email
                                $send_email = send($from,$to,$subject,$message);
                            
                                if($send_email){
                                    $data['success'][$i] = array('Firstname'=> $field['Firstname'],'Lastname'=>$field['Lastname'],'Username'=> $field['Email']);
                                    $i++;
                                }else if(!$send_email){
                                    $data['email'][$k] = array('Firstname'=> $field['Firstname'],'Lastname'=>$field['Lastname'],'Username'=> $field['Email']);
                                    $k++;
                                }
                            }else{
                                $data['error'][$j] = array('Firstname'=> $field['Firstname'],'Lastname'=>$field['Lastname'],'Username'=> $field['Email']);
                                $j++;
                            }
                    }//end foreach loop
                 
                    if(!isset($data['success']) && isset($data['email'])){
                        echo 'mia';
                    }
                    
//                   if(isset($data['exists']) || isset($data['results'])){
//                    $data['views']= array('manage_users/register_view');
//                    page_load($data);
//                }//end isset($data['exists']) || isset($data['results']
        }// end function register
        
            public function delete_file($user){
                $data['acc_yr'] = str_replace('/','-' ,$this->acc_year[0]['academic_year']);
                $data['user']=$user;
                if(unlink($_POST['file_path'])){
                    $data['message'] = 'File deleted successfully';
                    //$data['views']= array('manage_users/add_group_view');
                    //page_load($data);
                }else{
                    $data['message'] = 'File not deleted, Try again';
                    //$data['views']= array('manage_users/add_group_view');
                    //page_load($data);
                }
            }//end function delete file
            
            public function delete_all_files($user){
                $data['acc_yr'] = str_replace('/','-' ,$this->acc_year[0]['academic_year']);
                $acc_yr = str_replace('/','-' ,$this->acc_year[0]['academic_year']);
                
                $files = glob('./sProMAS_documents/'.  $acc_yr.'/registration_files/'.$user.'/*'); // get all file names
                foreach($files as $file){ // iterate files
                    if(is_file($file)){
                        if(unlink($file)){
                            $file_del=TRUE;
                        } // delete file
                    }
                }
                $data['user']=$user;
                if($file_del==TRUE){
                    $data['message'] = 'File deleted successfully';
                }  else {
                    $data['message'] = 'File not deleted, Try again';
                }  
                    //$data['views']= array('manage_users/add_group_view');
                    //page_load($data);
                
            }//end function delete file
}