<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Project_model extends CI_Model{
    
    public function add_project($data){
            $result = $this->db->insert('student_projects', $data); 
            if(isset($result) && $result =1){
                $id = $this->db->insert_id();
                $query = $this->db->get_where('student_projects', array('project_id' => $id));
                return $query->result_array();
            }
        }// end function add_group
        
    public function get_project($proj_id){
        $query = $this->db->get_where('student_projects',array('project_id' =>$proj_id));
        if($query->num_rows()>0 ){
            return $query->result_array();
        }
    }
    public function get_project_id($group_no){
        $query = $this->db->get_where('student_projects',array('group_no' =>$group_no));
        if($query->num_rows()>0 ){
            return $query->result_array();
        }
            
    
    }//end function get project group_no
    
    public function get_all_project($data){
        $this->db->select('*');
        $this->db->from('student_projects');
        $this->db->where($data); 
        $query = $this->db->get();
        return $query->result_array();
    } //end function get all
        
        
    public function get_supervisor($project_id){
        $this->db->select('*');
        $this->db->from('student_projects');
        $this->db->where(array('project_id' => $project_id));
        $query = $this->db->get();
        $result =  $query->row_array();
        return $result['supervisor_id'];
    }
        
    public function update_project($project_id,$data){
        $this->db->where('student_projects.project_id', $project_id);
        $result_stud = $this->db->update('student_projects', $data); 
        if($result_stud){
            $query = $this->db->get_where('student_projects', array('project_id' => $project_id));
        }
        return $query->result_array();
    }
        
    public function get_max_group_no(){
        $this->db->select_max('group_no');
        $query = $this->db->get('student_projects');
        return $query->result_array();
    }
    
    public function get_project_row($proj_id){
        $query = $this->db->get_where('student_projects',array('project_id' =>$proj_id));
        if($query->num_rows()>0 ){
            return $query->row_array();
        }
    }
    
    public function get_title($id){
        $this->db->select('*');
        $this->db->from('student_projects');
        $this->db->where('project_id', $id);
        $query = $this->db->get();
        $result = $query->row_array();
        return $result;
    }
    
    public function update_title($id, $data){
        $this->db->where('project_id', $id);
        return $this->db->update('student_projects', $data);
    }
}//end class