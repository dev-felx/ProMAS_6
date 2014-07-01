<?php

/* 
 * Author: Tesha Evance
 * Description: common tasks for forms
 * Comments: exclusive rights to author, consult on problems
 */


/*
 * Author: Tesha Evance
 * Description: shows form errors individually and proper styling
 */
  function show_form_error($name){
     if(form_error($name) != null){ 
         echo form_error($name, '<span class="error_text">', '</span>'); 
         echo '<script>$( "div" ).last().addClass( "has-error" );</script>';
     }
  }
  
  /*
   * Author: Tesha Evance
   * Description: Loads the header, sidebar, main_wrapper and footer views for you
   * Arguments:1 - Receives an assosiative array which contains an element called "views".
   *               "views" is an indexed array which contain views to be loaded in main_wrapper respective of their in order in the array
   *               ie: first view in array will be loaded first.
   *               The rest of the elements in the assosiative array can be application data.
   * Returns: void
   * Example: loading announcement page in a controller
   *          //this is the title for the page
   *          $data['title'] = 'ProMAS | Announcements';
   *          //this is the views list
   *          $data['views'] = array('project/announce','project/announce_foot');
   *          //this is view data
   *          $data['annoucement_list'] =  data from database;
   *          //then call the helper
   *          page_load($data);
   * Note: "views" and "title" are fixed names, you can use any name instead of "data" 
   */
  function page_load($data){
      
      //side bar location
        $data['sidebar'] = 'templates/side_bar';
        
        //load page
        $CI =& get_instance();
        $CI->load->view('templates/header',$data);
        $CI->load->view('main_wrapper');
        $CI->load->view('templates/footer');
      
  }
  
  function send($from,$to,$subject,$message){
    
    $CI =& get_instance();
    $CI->load->library('email');
    
    $CI->email->from($from,'ProMAS');
    $CI->email->to($to);
    $CI->email->subject($subject);
    $CI->email->message($message);

    if($CI->email->send()) {
        return TRUE;
        } else {
        return FALSE;
        }
    
}


function check_session_roles($roles){
    $CI =& get_instance();
    
    if($CI->session->userdata('user_id') && !in_array($CI->session->userdata('type'),$roles)){
        
        $CI->session->sess_destroy();
        setcookie('remember_promas', 'promas', 1,'/');;
        
        unset($_COOKIE['remember_promas']);
        
        //$message='<div class="alert alert-warning text-center" >Invalid Username or Password</div>';
        redirect('access/login/', 'location');
        
    } elseif(!$CI->session->userdata('user_id')){
        
        $CI->session->set_flashdata('url',  current_url());
        //$message='<div class="alert alert-warning text-center" >Invalid Username or Password</div>';
        redirect('access/login/', 'location');
        }
          
}


function no_cache(){
    
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    header('Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT');
    header('Cache-Control: no-store, no-cache, must-revalidate');
    header('Cache-Control: post-check=0, pre-check=0', false);
    header('Pragma: no-cache');
 
}

/*
 * Author: Tesha Evance
 * Description: Creates system notifications based on occurance of certain sysytem events eg: change in supervisor etc
 *              Can also send email for critical notifications.
 * Arguments: NOTE! PROVIDE ARGUMENTS IN ORDER AS THEY ARE MENTIONED BELOW
 *            *REQUIRED
 *              $desc(string) - the actual notification message Eg: new administrator added
 *              $scope(int) -  check table for allowed values and their meaning
 *            *OPTIONAL
 *              $email(boolean) - default: false; pass true if you want to send email
 *              $sc_p1 - default: null; check table for allowed values and their meaning
 *              $sc_p2 - default: null; check table for allowed values and their meaning
 *              $url(string) - default: null; url linked to your notification, prepends site_url, dont put leading slash  
 *              $glyph(string) - default: bell; glyhicon you want to be displayed with your notification 
 * 
 * CAUTION! : if you want to pass an optional argument but not the preceeding optional argument, pass the default value to the precedding 
 *            argument. Example: I dont want email but i want to pass $sc_p1 so create_notif("hello",1,FALSE,4);
 * Returns(boolean): true on success else false
 * 
*/

function create_notif($desc,$scope,$email = FALSE,$sc_p1 = null,$sc_p2 = null,$url = null,$glyph = 'bell'){
    $CI =& get_instance();
    //prep data
    
    $data = array(
            'description' => $desc,
            'creator_role' => $CI->session->userdata('type'),
            'creator_id' => $CI->session->userdata('user_id'),
            'space_id' => $CI->session->userdata('space_id'),
            'scope' => $scope,
            'sc_parameter' => $sc_p1,
            'sc_parameter2' => $sc_p2,
            'url' => $url,
            'glph' => $glyph
    );
    $CI->load->model('notification_model');
    $CI->load->model('announcement_model');
    $CI->notification_model->create_new($data);
    
    //send email
    if($email == TRUE){
        if($data['scope'] == 1){
            $email_list = $CI->announcement_model->get_email_1_2($CI->session->userdata['space_id'],'all');
        }else if($data['scope'] == 2 && $sc_p1 == 'stu'){
            $email_list = $CI->announcement_model->get_email_1_2($CI->session->userdata['space_id'],'stu');
        }else if($data['scope'] == 2 && $sc_p1 == 'non_stu'){
            $email_list = $CI->announcement_model->get_email_1_2($CI->session->userdata['space_id'],'non_stu');
        }else if($data['scope'] == 3){
            $email_list = $CI->announcement_model->get_email_3($CI->session->userdata['space_id'],$sc_p1);
        }else if($data['scope'] == 5 && isset ($sc_p2)){
            $email_list = $CI->announcement_model->get_email_5($CI->session->userdata['space_id'],$CI->session->userdata['project_id'],$sc_p2);
        }else if($data['scope'] == 5 && !isset ($sc_p2)){
            $email_list = $CI->announcement_model->get_email_5($CI->session->userdata['space_id'],$CI->session->userdata['project_id'],FALSE);
        }
        $CI->load->library('email');
        $CI->email->from('announcements@promas.co.tz', 'ProMAS');
        $CI->email->subject('ProMAS notification');
        $CI->email->message($desc);
        $CI->email->to($email_list);
        //send email
        $result2 = $CI->email->send();
    }
    return true;
}


function force_ssl(){
    $CI =& get_instance();   
    if($CI->uri->segment(1) == 'assessment'){   
        $CI->config->config['base_url'] = str_replace('http://', 'https://', $CI->config->config['base_url']);
        if ($_SERVER['SERVER_PORT'] != 443){
            redirect($CI->uri->uri_string());
        }
    }else{
        
        $CI->config->config['base_url'] = str_replace('https://', 'http://', $CI->config->config['base_url']);
        if ($_SERVER['SERVER_PORT'] == 443){
            redirect($CI->config->config['base_url'].'index.php/'.$CI->uri->uri_string());
        }
    }
}