<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Course_model extends CI_Model{
    
    public function get_all_course($data){
        
        $this->db->select('*');
        $this->db->from('courses');
        $this->db->where($data); 

        $query = $this->db->get();
        return $query->result_array();
        
        
    }
    
    
    
}

?>
