<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class College_department_model extends CI_Model {
    
    
    
    public function get_all_college($data){
            
            $this->db->select('*');
            $this->db->from('colleges');
            $this->db->where($data); 
            
            $query = $this->db->get();
            return $query->result_array();
          
        } //end function get all
    
        
        public function get_all_depart($data){
            
            $this->db->select('*');
            $this->db->from('departments');
            $this->db->where($data); 
            
            $query = $this->db->get();
            return $query->result_array();
        } //end function get all
    
    
}

?>
