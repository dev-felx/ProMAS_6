<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Miscellaneous_model extends CI_Model{
    
    public function add_student_id($user_id){
        
        return $this->db->insert('miscellaneous_stu', array('user_id'=>$user_id));
        
    }
    
    public function add_non_student_id($user_id){
        
        return $this->db->insert('miscellaneous_non', array('user_id'=>$user_id));
        
    }
    
}

?>
