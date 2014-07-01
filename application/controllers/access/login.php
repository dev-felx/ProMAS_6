<?php

/* Author : Devid Felix
 * 
 * 
 */

class Login extends CI_Controller{
        
    public function __construct() {
        parent::__construct();
        
    $this->load->model('access/manage_users');
   
    
    }

    public function index(){
        $this->session->keep_flashdata('url');   
        if(isset($_COOKIE['remember_promas'])){
           //a function to validate a cookie
            $this->cookie_login($_COOKIE['remember_promas']);
        }//end isset($_COOKIE['remember_promas']
        else if($this->session->userdata('user_id') != NULL){
            redirect('/home/', 'location');
                 
        }else {
     
            $this->load->view('access/login_page');
        }
        
        
    }
    
    public function verify_user(){
        $this->session->keep_flashdata('url');
       //check if it is student logging in or other user
        $this->form_validation->set_rules("username","","trim|required|");
        $this->form_validation->set_rules("password","","trim|required");
        $this->form_validation->set_message('required','');
        
        if($this->form_validation->run() !== FALSE){
            //check if it is student logging in or other user
                if($this->form_validation->valid_email($_POST['username']) == 1){
                    
                    $this->non_student_login($_POST['username'],md5($_POST['password']));
                    
                }elseif ($this->form_validation->alpha_dash($_POST['username']) == 1){
                    
                    $this->student_login($_POST['username'],md5($_POST['password']));
                } else{
                
                    $data['message']='<div class="alert alert-danger text-center" >Invalid Username or Password</div>';
                    $this->load->view('access/login_page',$data);
                
                }
                
        }// end if $this->form_validation->run() !== FALSE
        else{
            $data['message']='<div class="alert alert-danger text-center" >Username and Password is required</div>';
            $this->load->view('access/login_page',$data);
        }
       
    }//end function verify user
    
    
    public function non_student_login($username,$password){
       
        // sets the session variables upon positive results from db         
        $data['user_data'] = $this->manage_users->match_non_student($username,$password);
             
        $roles = array();

        for($i = 0; $i < count($data['user_data']);$i++){
        
            $roles[$i] = $data['user_data'][$i]['role'];
    
        }//positive results ? set session : redirect to login with error message
            if($data['user_data'] != null){
                $user_data = array(
                   'user_id' => $data['user_data'][0]['user_id'],
                   'username'=> $data['user_data'][0]['username'],
                   'fname' => $data['user_data'][0]['first_name'],
                   'lname' => $data['user_data'][0]['last_name'],
                   'email' => $data['user_data'][0]['email'],
                   'type'  => $data['user_data'][0]['role'],
                   'status'=> $data['user_data'][0]['reg_status'],
                   'roles' => $roles,
                    'space_id' => $data['user_data'][0]['space_id']
                    );
                
                if($user_data['type'] == 'superuser'){
                    $this->session->set_userdata($user_data);
                    
                    //creating cookie or renewing cookie
                   if(($_POST['keep_logged'] == 1) || isset($_COOKIE['remember_promas'])){
                            //calling keep_me_logged function
                            $this->keep_me_logged($this->session->userdata['user_id'],$this->session->userdata['type']);    
                        
                    }//end if keeped_logged

                   redirect('/home/', 'location');
                        
                   }//end $user_data['type'] == super 
                   
                   //if non student user has completed registration
                   else if(($user_data['type'] == ('administrator'||'coordinator'||'supervisor'||'panel_head')) && $user_data['status']== 1){
                        $this->session->set_userdata($user_data);
                        //creating cookie or renewing cookie
                        if(($_POST['keep_logged'] == 1) || isset($_COOKIE['remember_promas']) ){
                            //calling keep_me_logged function
                            $this->keep_me_logged($this->session->userdata['user_id'],$this->session->userdata['type']);    
                    }//end if keeped_logged

                        redirect('/home', 'location');
                   }//end $user_data['type'] == admin||coordinator||supervisor 
                   
                //if non-student user has not completed registration
                else if(($user_data['type'] == ('administrator'||'coordinator'||'supervisor'||'panel_head')) && $user_data['status']== 0){
                        $this->session->set_userdata($user_data);
                        //creating cookie or renewing cookie
                        if(($_POST['keep_logged'] == 1) || isset($_COOKIE['remember_promas'])){
                        //calling keep_me_logged function
                            $this->keep_me_logged($this->session->userdata['user_id'],$this->session->userdata['type']);    
                        
                    }//end if keeped_logged

                    //redirecting to registration page
                    redirect('/manage_users/register_user', 'location');
                    
                }//end $user_data['type'] == admin||coordinator||supervisor status ==0
                
            }//end if $data['user_data'] != null
            
            else{
                $data['message']='<div class="alert alert-danger text-center" >Invalid Username or Password</div>';
                $this->load->view('access/login_page',$data);
            }               
    }//end function
    
    
    public function student_login($username,$password){
                
                $data['user_data'] = $this->manage_users->match_student($username,$password);
                
                if($data['user_data'] != null){
                    $user_data = array(
                       'user_id'  => $data['user_data'][0]['student_id'],
                       'username'  => $data['user_data'][0]['registration_no'],
                       'fname' => $data['user_data'][0]['first_name'],
                       'lname' => $data['user_data'][0]['last_name'],
                       'email'     => $data['user_data'][0]['email'],
                       'type'     => 'student',
                       'status' => $data['user_data'][0]['reg_status'],
                       'project_id' => $data['user_data'][0]['project_id'],
                       'space_id' => $data['user_data'][0]['space_id'], 
                       'group_no' => $data['user_data'][0]['group_no'] 
                        
                    );
                    
                    if ($user_data['status'] == 1 ){
                        $this->session->set_userdata($user_data);
                        
                        //creating cookie or renewing cookie
                        if(($_POST['keep_logged'] == 1) || isset($_COOKIE['remember_promas'])){
                            //calling keep_me_logged function
                            $this->keep_me_logged($this->session->userdata['user_id'],$this->session->userdata['type']);    
                        
                        }//end if keeped_logged

                        redirect('/home/', 'location');
                        
                        }//end $user_data['status'] == 1 
                    
                        else{
                            
                            $this->session->set_userdata($user_data);
                            
                            //creating cookie or renewing cookie
                            if(($_POST['keep_logged'] == 1) || isset($_COOKIE['remember_promas']) ){
                            
                                //calling keep_me_logged function
                                $this->keep_me_logged($this->session->userdata['user_id'],$this->session->userdata['type']);    
                        
                                }//end if keeped_logged

                                //redirecting user to complete registration first
                                redirect('/manage_users/register_user', 'location');
                    
                                
                                }//end inner else
              
                            
                            }//end if data['userdata'] != null
                
                            else{
                                $data['message']='<div class="alert alert-danger text-center" >Invalid Username or Password</div>';
                                $this->load->view('access/login_page',$data);
                        
                            }
    
                            
                            }//end function student loggin
   
                            
    public function keep_me_logged($user_id, $user_type){
        
        
        $name= 'remember_promas';
        //exipre time for cookie
        $expire = time() + 432000;//5days(1day =86400s)
        //generate a token
        $token = md5(microtime (TRUE)*100000);
        //hash token to be stored on db
        $cookie_to_db = hash('sha256',$token);
        //path on the page for cookie to be associated with
        $path= '/';
        
        $data = array(
            'hashed_token'=> $cookie_to_db,
        );
        
        if($user_type == 'student'){
            
            $result = $this->manage_users->update_student($user_id,$data);
        }
 
        else {
            
            $result= $this->manage_users->update_non_student($user_id,$data);
        }
        
        if($result){
            
            setcookie($name, $token, $expire,$path);
        
        }
        
        
        }//end function keep me logged in

        
        public function cookie_login($cookie){
            
            $cookie_browser = hash('sha256', $cookie);
            
            $data = array(
            'hashed_token'=>$cookie_browser    
            );
            
            //verifying cookie on non student table
                $user_data['data'] =$this->manage_users->get_non_student($data);

            
           if($user_data['data'] == NULL){
             
            //verfying cookie on student table
           $user_data['data'] =$this->manage_users->get_student($data);
              
           
           }//end if $user_data['data'] == NULL
           
           if($user_data['data'] != NULL){
               
               if( isset($user_data['data'][0]['role']) && isset($user_data['data'][0]['user_id'])){
                   
                   $user_name = $user_data['data'][0]['username'];
                   $pass_word  =$user_data['data'][0]['password'];
                   
                   $this->non_student_login($user_name, $pass_word);
                   
               }
               
               else{
                   
                   $user_name = $user_data['data'][0]['registration_no'];
                   $pass_word  =$user_data['data'][0]['password'];
                   
                   $this->student_login($user_name, $pass_word);
                   
               }
               
           }//end if $user_data['data'] != NULL
        
 
        else {
            
               $this->load->view('access/login_page');
   
               }// end else $user_data['data'] != NULL
        
        }//end function cookie_login

        
        }// end class login
?>
