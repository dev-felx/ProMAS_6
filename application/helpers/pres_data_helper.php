<?php
function unread_ann(){
    $CI =& get_instance();
    $CI->load->model('announcement_model');
    if($CI->session->userdata['type'] != 'student' ){
        $data = $CI->announcement_model->get_ann_unread($CI->session->userdata['user_id'],TRUE);  
    }else{
        $data = $CI->announcement_model->get_ann_unread_stu($CI->session->userdata['user_id'],TRUE); 
    }
    return $data;
}
function unread_not(){
    $CI =& get_instance();
    $CI->load->model('notification_model');
    if($CI->session->userdata['type'] != 'student' ){
        $data = $CI->notification_model->get_not_unread($CI->session->userdata['user_id'],TRUE);  
    }else{
        $data = $CI->notification_model->get_not_unread_stu($CI->session->userdata['user_id'],TRUE); 
    }
    return $data;
}