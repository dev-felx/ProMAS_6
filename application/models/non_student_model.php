<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Non_student_model extends CI_Model{
    
    public function get_non_student($data){
        
        $this->db->select('*');
        $this->db->from('non_student_users');
        $this->db->where($data);
        $this->db->join('roles', "roles.user_id = non_student_users.user_id",'inner');
        $query= $this->db->get();
        return $query->result_array();
        
    }
    
    
}