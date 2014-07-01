<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Add_group extends CI_Controller{
    
    public function __construct() {
        parent::__construct();
        
        //checking session and allowed roles
        $roles = array('superuser','administrator','coordinator');
        check_session_roles($roles);
    
        //
        $this->load->model('access/manage_users');
        $this->load->helper(array('file','directory'));
        $this->load->model('project_space_model');
        $this->acc_year = $this->project_space_model->get_all_project_space(array('space_id'=>$this->session->userdata['space_id']));
        
    }

    public function group($user){
        $data['acc_yr'] = str_replace('/','-' ,$this->acc_year[0]['academic_year']);
        $data['user'] = $user;
        $data['views']= array('manage_users/add_group_view');
        page_load($data);
    }
    
    public function download($user){
        $this->load->helper('download');
        //$acc_yr = str_replace('/','-' ,  $this->acc_year[0]['academic_year']);
        $filename= './sProMAS_documents/registration_templates/'.$user.'.csv';
        $name = $user.'.csv';
        //reading the file content
        $data = file_get_contents($filename);
        //download a file from a server
        force_download($name, $data);
        
    }//end function download
    
    public function upload($user){
        $this->load->library('upload');
        $this->form_validation->set_rules("fName","Name","required");
        $this->form_validation->set_message('required','%s is required');
        if($this->form_validation->run() === FALSE){
            $response['status'] = 'name_required';
        }else {
            
            $acc_yr = str_replace('/','-' ,$this->acc_year[0]['academic_year']);
            
            $config['upload_path']= './sProMAS_documents/'.$acc_yr.'/registration_files/'.$user.'/';
            $config['allowed_types']= 'csv';
            $config['max_size']='2048';
            $config['file_name']= $_POST['fName'];
            
            //if upload path does not exist create directories
            if (!is_dir($config['upload_path'])) {
                mkdir($config['upload_path'], 0777, TRUE);
            }
            $this->upload->initialize($config);
            if(!$this->upload->do_upload()){
                $response['file_errors'] = $this->upload->display_errors();
                $response['status'] = 'file_error';
            }else {
                $response['status'] = 'success';
                }
            }//end outer else
            header('Content-type: application/json');
            exit(json_encode($response));
            }//end function do upload
            
            
            public function register_users($user){
                $this->load->model('miscellaneous_model');
                $this->load->library('csv_reader');
               
                //fetching content from the csv file
                $content = $this->csv_reader->read_csv_file($_POST['file_path'],$user);
                $data['user'] = $user;
                if($user == 'student'){
                    foreach($content as $field){
                        $values = array(
                                'group_no' =>$field['Group no'],
                                'space_id'=>$this->session->userdata['space_id']
                            );
                        $table = 'student_projects';
                            //checking if the group no exist in the db
                        $result = $this->manage_users->check_value_exists($table, $values);
                        //if the group does not exist
                        if(!$result){
                            $this->load->model('college_department_model');
                            $resoo = $this->college_department_model->get_all_depart(array('department_id >'=>0));
                            $values['department_id'] = $resoo[0]['department_id'];
                            $result = $this->manage_users->add_group($values);
                        }//end if the value does not exist
                    }//foreach($content as $field)
                    $i=0;
                    $j=0;
                    foreach($content as $field){
                        $values = array(
                                'registration_no' =>$field['Registration no']
                            );
                        $table = 'students';
                        //checking if the student exist in the db
                        $result = $this->manage_users->check_value_exists($table, $values);
                        $this->load->model('project_model');
                        $result_project = $this->project_model->get_project_id($field['Group no']);
                        //if student does not exist
                        if(!$result){
                            $values = array(
                                'first_name' =>$field['Firstname'] ,
                                'last_name' =>$field['Lastname'] ,
                                'registration_no' =>$field['Registration no'] ,
                                'password' =>md5(strtolower($field['Lastname'])) ,
                                'email' => $field['Email'],
                                'group_no' =>$field['Group no'],
                                'project_id' =>$result_project[0]['project_id'],
                                'space_id'=>$this->session->userdata['space_id'],
                            );
                            //add a student into the database
                            $userdata = $this->manage_users->add_student($values);
                    if($userdata != NULL ){
                        $this->miscellaneous_model->add_student_id($userdata[0]['student_id']);
                        $fname= $userdata[0]['first_name'];
                        $lname= strtolower($userdata[0]['last_name']);
                        $reg_no= $userdata[0]['registration_no'];
                        $email = $userdata[0]['email'];
                        $from = "admin@promas.com";
                        $to = $email;
                        $subject = "ProMAS | Account registration";
                        $url = base_url();
                        $message = " 
                                    <html>
                                    <head>
                                    <title>ProMAS | Account Registration</title>
                                    </head>
                                    <body>
                                            <h4>Hello $fname,</h4>
                                            <p>Use credentials below to login into the system and complete registration</p>   
                                            <p>Username : $reg_no </p>   
                                            <p>Password : $lname  </p>
                                            <p>Click the link below</p>
                                            <a href='$url index.php/access/login'>Login into promas</a>
                                            <p>Sincerely,</p>
                                            <p>ProMAS admin.</p>
                                    </body>
                                    </html>";
                                //sending email
                                $send_email =  send($from,$to,$subject,$message);
                            
                                $data['results'][$i] = array('Firstname'=> $field['Firstname'],'Lastname'=>$field['Lastname'],'Registration no'=> $field['Registration no']);
                                $i++;
                            }   
                        }else{
                                $data['exists'][$j] = array('Firstname'=> $field['Firstname'],'Lastname'=>$field['Lastname'],'Registration no'=> $field['Registration no']);
                                $j++;
                        }//if student exists    
                    }// end foreach($content as $field)
                }elseif ($user == 'supervisor') {
                   
                   $i=0;
                   $j=0;
                    
                   foreach($content as $field){

                       $values = array(
                            'username' => $field['Email'],
                            );
                      
                       $table = 'non_student_users';
                        
                        //checking if the user exist in the db
                       $result = $this->manage_users->check_value_exists($table, $values);
                       //if user does not exist
                       if(!$result){
                           
                               $values = array(
                                  'first_name' =>$field['Firstname'] ,
                                  'last_name' =>$field['Lastname'] ,
                                  'password' =>md5(strtolower($field['Lastname'])) ,
                                  'email' => $field['Email']  ,
                                  'username'=> $field['Email'],
                                  'space_id'=>$this->session->userdata['space_id'],
                                      );
                           
                                //role to be stored into db
                                $role = strtolower($user);

                            $userdata = $this->manage_users->add_non_student($values,$role);

                                if($userdata != NULL ){
                    
                                    $this->miscellaneous_model->add_non_student_id($userdata[0]['user_id']);

                                    $fname= $userdata[0]['first_name'];
                                    $lname= strtolower($userdata[0]['last_name']);
                                    $email = $userdata[0]['email'];

                                    $from = "admin@promas.com";
                                    //$to = $email;
                                    $to = $email;
                                    $subject = "ProMAS | Account registration";
                                    $url =  base_url();
                                    $message = " 
                                        <html>
                                        <head>
                                        <title>ProMAS | Account Registration</title>
                                        </head>
                                        <body>
                                                <h4>Hello $fname,</h4>
                                                 <p>Use credentials below to login into the system and complete registration</p>   
                                                 <p>Username : $email </p>   
                                                 <p>Password : $lname  </p>
                                                 <p>Click the link below</p>    
                                                 <a href=' $url index.php/access/login'>Login into promas</a>
                                                <p>Sincerely,</p>
                                                <p>ProMAS admin.</p>
                                        </body>
                                        </html>";

                                    //sending email
                                    $send_email =  send($from,$to,$subject,$message);
                            
                                        $data['results'][$i] = array('Firstname'=> $field['Firstname'],'Lastname'=>$field['Lastname'],'Username'=> $field['Email']);
                                        $i++;

                                    }// end if $userdata!=NULL if user was added successfully 
                                    
                                    
                                    }////end if the value does not exist
                            
                            else{
                                
                                $data['exists'][$j] = array('Firstname'=> $field['Firstname'],'Lastname'=>$field['Lastname'],'Username'=> $field['Email']);
                                $j++;
                        }//if  user exists    

                    }//end foreach loop
                   
                   }//end else if $user == 'supervisor'
            
                   if(isset($data['exists']) || isset($data['results'])){
                    $data['views']= array('manage_users/register_view');
                    page_load($data);
                }//end isset($data['exists']) || isset($data['results']
            }// end function register
            
    
            public function delete_file($user){
                $data['acc_yr'] = str_replace('/','-' ,$this->acc_year[0]['academic_year']);
                $data['user']=$user;
                if(unlink($_POST['file_path'])){
                    $data['message'] = 'File deleted successfully';
                    $data['views']= array('manage_users/add_group_view');
                    page_load($data);
                }else{
                    $data['message'] = 'File not deleted, Try again';
                    $data['views']= array('manage_users/add_group_view');
                    page_load($data);
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
                    $data['views']= array('manage_users/add_group_view');
                    page_load($data);
                
            }//end function delete file

}
?>


