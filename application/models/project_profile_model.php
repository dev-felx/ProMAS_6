<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Project_profile_model extends CI_Model{
    
    public function add_project_profile($data){
        $result = $this->db->insert('project_profile', $data);
        if($result!=NULL){
            return $this->db->insert_id();
        }
    }
    
}