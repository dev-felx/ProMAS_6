<?php

/*
 * Author: Devid Felix
 * 
 */

class Password extends CI_Controller{
    
    function __construct() {
        parent::__construct();
        
        //dont check session here, it is handled inside respective function
       $this->load->model('access/manage_users');
    
    }
    public function index(){
        $this->load->view('access/forgot_password');
    }
            
    public function forgot_password(){
        $this->form_validation->set_rules("email","Email","required|valid_email");
        $this->form_validation->set_message('required',' is required');
       if($this->form_validation->run() !== FALSE){
            //setting a field to be verified on db
           $data = array(
               'email'=>$_POST['email']
           );
           //verifying email on non student table
           $user_data['data'] =$this->manage_users->get_non_student($data);

           if($user_data['data'] == NULL){
            //verfying email on student table
           $user_data['data'] =$this->manage_users->get_student($data);
           }//end if $user_data['data'] == NULL
           
           if($user_data['data'] != NULL){
               $email = $user_data['data'][0]['email'];
               $user = ucfirst($user_data['data'][0]['first_name']);
               //generate a token
               $token = md5(microtime (TRUE)*100000);
               //hash token to be stored on db
               
               $token_to_db = hash('sha256',$token);
               //expire time to be stored on db
               $expire_time = date("Y-m-d H:i:s",time()+3600);
               //array for updating database
               $data = array(
                   'token_expire' => $expire_time,
                   'hashed_token' => $token_to_db 
               );
               if( isset($user_data['data'][0]['role']) && isset($user_data['data'][0]['user_id'])){
                   $user_id = $user_data['data'][0]['user_id'];
                   $result = $this->manage_users->update_non_student($user_id,$data);
                   $user_type = base64_encode('non_student');
               }else{
                   $user_id = $user_data['data'][0]['student_id'];
                   $result = $this->manage_users->update_student($user_id,$data);
                   $user_type = base64_encode('student');
               }
               if($result){
                   //user id and token to be sent to email
                   $user_id = base64_encode($user_id); 
                   $token_to_email= $token;
                   $site_url = site_url();

                   $from = "admin@promas.com";
                   $to = $email;
                   $subject = "Change password";
                   $message = " 
                        <html>
                        <head>
                        <title>ProMAS | Change password</title>
                        </head>
                        <body>
                                <h4>Hello $user,</h4>    
                                <p>Please visit the following link to change your password</p>
                                <a href='$site_url/access/password/change_password/$user_type/$user_id/$token_to_email'>
                                    Click here to change your password</a>
                                <p>This verification code will expire in 1hour.</p>
                                <p>Sincerely,</p>
                                <p>ProMAS admin.</p>
                        </body></html>";
                    //sending email
                    $email_result =  send($from,$to,$subject,$message);
                    if($email_result){
                        //on success sending email
                        $data['message'] = "<div class='alert alert-success fade in text-center'> A link has been sent, Check your <b>email inbox</b> if not found check your <b>spam folder</b>. A link expires after <b>1 hour<b></b></div>";
                        $this->load->view('access/forgot_password',$data);
                    }else {
                       //on failure sending email
                       $data['message'] = "<div class='alert alert-danger fade in text-center'> Email has not been sent, Please try again.</div>";
                       $this->load->view('access/forgot_password',$data);
                    }//end inner else
                    }else{
                       //on failure to store 
                       $data['message'] = "<div class='alert alert-warning fade in text-center'>Failed to send link please try again</div>";
                       $this->load->view('access/forgot_password', $data);

                   
                   }//end else if $result
               
                    }else{
                       $data['message'] = "<div class='alert alert-warning fade in text-center'>Email was not found, Please use registered email</div>";
                       $this->load->view('access/forgot_password',$data);
                    }//end outer else
           
                }else{
                       $data['message'] = "<div class='alert alert-warning fade in text-center'>Email can not be empty</div>";
                       $this->load->view('access/forgot_password',$data);
                       
                        
                    }// end else $this->form_validation->run() !== FALSE
        
                    }//end function forgot
    
    
                    public function change_password($user_type,$user_id,$hashed_token){
                        
                        //decoding user id and user type
                        $user_type = base64_decode($user_type);
                        $user_id = base64_decode($user_id);
                        
                        //hash token to be matched with db
                        $token_to_email = hash('sha256', $hashed_token);
         
                        
                        if($user_type=='student'){
                            $data = array(
                                'student_id'=>$user_id,
                            );
                            $user_data['data'] =$this->manage_users->get_student($data);
                        }elseif ($user_type=='non_student') {
                            $data = array(
                                'non_student_users.user_id'=>$user_id,
                            );
                            $user_data['data'] =   $this->manage_users->get_non_student($data);
                        }//// end else if $user_type=='non_student'
                        $token_expire = $user_data['data'][0]['token_expire'];
                        $token_to_db = $user_data['data'][0]['hashed_token'];
                        
                        if((strtotime($token_expire) > time()) &&($token_to_db == $token_to_email)){
                            $data['user_id'] =$user_id;
                            $data['user_type']=$user_type;
                            $data['message']= "<div class=\"alert alert-info fade in text-center\"> Hello " . ucfirst($user_data['data'][0]['first_name']).", Create your new password</div>";
                            $this->load->view('access/change_password', $data);
                        }else{
                            //else load the forgor password view with the expire time error on it
                            $data['message']= "<div class=\"alert alert-warning fade in text-center\">Verification code has expired, Send a new link again</div>";
                            $this->load->view('access/forgot_password',$data);
                        }
                    }//end function change password
                    
                    public function validate_password($user_type,$user_id){
        
                       $this->form_validation->set_rules("password","Field","required");
                       $this->form_validation->set_rules("password_con","Field","required");
                       $this->form_validation->set_message('required','%s is required');

                       if($this->form_validation->run() !== FALSE){
                            if($_POST['password'] == $_POST['password_con']){
                                $values = array(
                                   'password' => md5($_POST['password'])
                               );
                               if($user_type == 'student'){
                                    $user_data['data'] =   $this->manage_users->update_student($user_id, $values);
                               }else{
                                       $user_data['data'] =   $this->manage_users->update_non_student($user_id,$values);
                                   } 
                                       
                                if($user_data['data'] != NULL){
                                        $data['user_id'] =$user_id;
                                        $data['user_type']=$user_type;
                                        $data['message']= "<div class='alert alert-success fade in text-center'>Password changed successfull,you can now <a href='". site_url()."'/access/login><b>Login</b><a></div>";
                                        $this->load->view("access/change_password", $data);
                                }else{
                                        $data['user_id'] =$user_id;
                                        $data['user_type']=$user_type;
                                        $data['message']= "<div class='alert alert-danger fade in text-center'>Error occured, password not changed</div>";
                                        $this->load->view("access/change_password", $data);
                                }
                                }else{

                                    $data['user_id'] =$user_id;
                                    $data['user_type']=$user_type;
                                    $data['message']= "<div class='alert alert-warning fade in text-center'>Passwords do not match</div>";
                                    $this->load->view("access/change_password", $data);
                                    
                                }// end else $_POST['password'] == $_POST['password_con']

                                }//end if $this->form_validation->run() !== FALSE


                                else {
                                    $data['user_id'] =$user_id;
                                    $data['user_type']=$user_type;
                                    $data['message']= "<div class='alert alert-warning fade in text-center'>Password can not be empty</div>";
                                    $this->load->view("access/change_password", $data);
                                    
                                }

                                }//END FUNCTTION VALIDATE password

                    
    
                    
                  
                    public function change_pass_profile(){
                     
                        //checking session and allowed roles
                        $roles = array('superuser','administrator','coordinator','supervisor','student');
                        check_session_roles($roles);   
                        $data['views'] = array ("access/change_pass_profile");
                        page_load($data);
                        
                    }
    
                    public function validate_pass_profile(){
        
                       //checking session and allowed roles
                        $roles = array('superuser','administrator','coordinator','supervisor','student');
                        check_session_roles($roles);
                        
                       $this->form_validation->set_rules("curr_password","Field","required");
                       $this->form_validation->set_rules("password","Field","required");
                       $this->form_validation->set_rules("password_con","Field","required");
                       $this->form_validation->set_message('required','%s is required');

                       if($this->form_validation->run() !== FALSE){

                           if($_POST['password'] == $_POST['password_con']){

                               $values = array(
                                   'password' => md5($_POST['password'])
                               );
                               $curr_pass = array(
                                   'password' => md5($_POST['curr_password'])
                               );
                               
                               if($_POST['user_type']=='student'){
                            
                                   $table = 'students'; 
                                   $result = $this->manage_users->check_value_exists($table,$curr_pass);
                                   
                                   if($result){
                                   
                                       $user_data['data'] =   $this->manage_users->update_student($_POST['user_id'], $values);
                                   
                                       if($user_data['data'] != NULL){
                                 
                                            $data['message']= "Password changed successful";
                                            $data['views'] = array ("access/change_pass_profile");
                                            page_load($data);
                                        }//
                                   }else{
                                            
                                            $data['message']= "Invalid current password";
                                            $data['views'] = array ("access/change_pass_profile");
                                            page_load($data);
                                            
                                        }
                            
                                }// end if $user_type=='student'
                        
                                else{
                      
                                    $table = 'non_student_users'; 
                                    $result = $this->manage_users->check_value_exists($table,$curr_pass);
                                    
                                    if($result){
                                        $user_data['data'] =   $this->manage_users->update_non_student($_POST['user_id'],$values);
                                    
                                        if($user_data['data'] != NULL){
                                 
                                            $data['message']= "Password changed successful";
                                            $data['views'] = array ("access/change_pass_profile");
                                            page_load($data);
                                        }//
                                    
                                        }else{
                                            
                                            $data['message']= "Invalid current password";
                                            $data['views'] = array ("access/change_pass_profile");
                                            page_load($data);
                                            
                                        }//end password current is invalid
                                        
                        
                                }//// end else if $user_type=='non_student'

         
                               
                                

                                }// end if $_POST['password'] == $_POST['password_con']

                                else{

                                    $data['message']= 'Password do not match';
                                    $data['views'] = array ("access/change_pass_profile");
                                    page_load($data);
                                }// end else $_POST['password'] == $_POST['password_con']

                                }//end if $this->form_validation->run() !== FALSE


                                else {

                                    $data['views'] = array ("access/change_pass_profile");
                                    page_load($data);
                                }

                                }//END FUNCTTION VALIDATE

}


?>
