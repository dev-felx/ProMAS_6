<?php

/*
 * Author: Devid Felix
 * 
 */

class Logout extends CI_Controller{
    
    public function index() {
        
        //destroy user session and redirect to the login page
        $this->output->set_header('Last-Modified:'.gmdate('D, d M Y H:i:s').'GMT');
$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
$this->output->set_header('Cache-Control: post-check=0, pre-check=0',false);
$this->output->set_header('Pragma: no-cache');
        
        unset($_SESSION['promas_session']);
        $this->session->sess_destroy();
        setcookie('remember_promas', 'promas', 1,'/');;
        
        unset($_COOKIE['remember_promas']);
        
        redirect('access/login', 'location');
    }
}

?>