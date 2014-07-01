<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Project_space_model extends CI_Model{
    
    public function get_all_project_space($data){
        
        $this->db->select('*');
        $this->db->from('project_space');
        $this->db->where($data); 

        $query = $this->db->get();
        return $query->result_array();
          
        
    }//end function get space
    
    public function add_project_space($data){
        
        $result_insert = $this->db->insert('project_space', $data); 
        if($result_insert != NULL){
            $id = $this->db->insert_id();
            $query = $this->db->get_where('project_space', array('space_id' => $id));
            $result = $query->result_array();
        }else{
            $result=NULL;
        }
        return $result;
        }//end function add_project_space

        
        }//end class


?>
