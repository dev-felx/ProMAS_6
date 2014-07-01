<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Student_model extends CI_Model{
    
    public function get_student($data){
        
        $this->db->select('*');
        $this->db->from('students');
        $this->db->where($data);
        $query= $this->db->get();
        return $query->result_array();
    }

    }
    
    