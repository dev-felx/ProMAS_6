<?php

/**
 * Description of archieve_model
 *
 * @author Minja Junior
 */
class Archive_model extends CI_Model {
    
    public function st($term){
        $this->db->select('*');
        $this->db->from('project_profile'); 
        $this->db->like('project_name', $term);
        $query = $this->db->get();
        
        if ($query->num_rows() > 0){
            foreach ($query->result() as $row) {
                $response[] = $row;
            }
            return $response; 
        }else{
            return FALSE;
        }   
    }
    
    public function search($term){
        $this->db->select('*');
        $this->db->from('project_profile'); 
        $this->db->like('project_name', $term);
        $query = $this->db->get();
        
        if ($query->num_rows() > 0){
            foreach ($query->result() as $row) {
                $response[] = $row;
            }
            return $response; 
        }else{
            return FALSE;
        }
    }
    
    public function profile($id){
        $this->db->select('*');
        $this->db->from('project_profile'); 
        $this->db->where('project_profile_id', $id);
        //$this->db->join('departments', 'departments.department_id = project_profile.project_profile_id');
        $query = $this->db->get();
        
        if ($query->num_rows() > 0){
            foreach ($query->result() as $row) {
                $response[] = $row;
            }
            return $response; 
        }else{
            $response['error'] = 'Profile details was not found';
            return $response;
        }
    }
    
    public function participants($id){
        $this->db->select('*');
        $this->db->from('participants'); 
        $this->db->where('project_profile_id', $id);
        $query = $this->db->get();
        
        if ($query->num_rows() > 0){
            foreach ($query->result() as $row) {
                $response[] = $row;
            }
            return $response; 
        }else{
            $response['error'] = 'Participants not Found';
            return $response;
        }
    }
    
    public function documents($id){
        $this->db->select('*');
        $this->db->from('archive_documents'); 
        $this->db->where('project_profile_id', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            foreach ($query->result() as $row) {
                $response[] = $row;
            }
            return $response; 
        }else{
            $response['error'] = 'Document not Found';
            return $response;
        }
    }
    
    public function abst($id){
        $this->db->select('*');
        $this->db->from('archive_documents'); 
        $this->db->where('project_profile_id', $id);
        $this->db->where('document_name', 'Project abstract');
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            foreach ($query->result() as $row) {
                $response[] = $row;
            }
            return $response; 
        }else{
            $response['error'] = 'Abstract not Found';
            return $response;
        }
    }
    
    public function explore(){
        $this->db->select('*');
        $this->db->from('project_profile');
        $this->db->order_by("project_profile_id", "desc");
        $query = $this->db->get();
        
        if ($query->num_rows() > 0){
            foreach ($query->result() as $row) {
                $response[] = $row;
            }
            return $response; 
        }else{
            return FALSE;
        }
    }
    
    public function get_academic_year(){
        $q = "SELECT DISTINCT academic_year FROM project_profile";
        $query = $this->db->query($q);
        foreach ($query->result() as $row) {
            $response[] = $row;
        }
        return $response;
    }
    
    public function get_department(){
        $q = "SELECT DISTINCT name FROM departments INNER JOIN project_profile WHERE departments.department_id = project_profile.department_id";
        $query = $this->db->query($q);
        foreach ($query->result() as $row) {
            $response[] = $row;
        }
        return $response;
    }
    
    public function filter_alpha($ft){
        $this->db->select('*');
        $this->db->from('project_profile');
        $this->db->order_by('project_name', $ft);
        $query = $this->db->get();
        
        if ($query->num_rows() > 0){
            foreach ($query->result() as $row) {
                $response[] = $row;
            }
            return $response; 
        }else{
            //return FALSE;
        }
    }
    
    public function filter_year($ft){
        $this->db->select('*');
        $this->db->from('project_profile');
        $this->db->where('academic_year', $ft);
        $query = $this->db->get();
        
        if ($query->num_rows() > 0){
            foreach ($query->result() as $row) {
                $response[] = $row;
            }
            return $response; 
        }else{
            //return FALSE;
        }
    }
    
    public function filter_department($ft){
        $this->db->select('*');
        $this->db->from('project_profile');
        $this->db->where('department_id', $ft);
        $query = $this->db->get();
        
        if ($query->num_rows() > 0){
            foreach ($query->result() as $row) {
                $response[] = $row;
            }
            return $response; 
        }else{
            //return FALSE;
        }
    }
    
    public function get_department_id($name){
        $this->db->select('department_id');
        $this->db->from('departments');
        $this->db->where('name', $name);
        $query =  $this->db->get();
        $result = $query->row_array();
        return $result;
    }
    
    public function get_department_name($id){
        $this->db->select('name');
        $this->db->from('departments');
        $this->db->where('department_id', $id);
        $query =  $this->db->get();
        $result = $query->row_array();
        return $result;
    }
    
    
    /*========================================
     * Access functions
     */
    
    public function new_user($data){
        $this->db->select('*');
        $this->db->from('archive_users');
        $this->db->where(array('username' => $data['username']));
        $pre_query = $this->db->get();
        if($pre_query->num_rows() > 0){
            return false;
        }else{
            $query = $this->db->insert('archive_users', $data);
            return $query;
        }
    }
    
    public function get_all(){
        $this->db->select('*');
        $this->db->from('archive_users');
        $query = $this->db->get();
        $result =  $query->result_array();
        return $result;
    }
    
    
    public function del($id){
        return  $this->db->delete('archive_users', array('user_id' => $id)); 
    }
    
    public function en($id){
        $data = array(
            'acc_status' => 1
        );
        $this->db->where('user_id', $id);
        return $this->db->update('archive_users', $data);
    }
    public function dis($id){
        $data = array(
            'acc_status' => 0
        );
        $this->db->where('user_id', $id);
        return $this->db->update('archive_users', $data);
    }
    public function rej($rel){
        $db_data = array(
            'status' => 2,
        );
        $this->db->where('archive_user_project.rel_id', $rel);
        return $res = $this->db->update('archive_user_project', $db_data);
    }
    public function grant($id,$rel){
        $data = array(
            'acc_status' => 1,
        );
        $this->db->where('archive_users.user_id', $id);
        $res = $this->db->update('archive_users', $data);
        if($res){
            $db_data = array(
            'status' => 1,
        );
        $this->db->where('archive_user_project.rel_id', $rel);
        return $res = $this->db->update('archive_user_project', $db_data);
        }
    }
    
    
    public function req($data){
        $query = $this->db->insert('archive_user_project', $data);
        return $query;
    }
    
    public function get_user($email){
        $this->db->select('*');
        $this->db->from('archive_users');
        $this->db->where(array('username' => $email));
        $query = $this->db->get();
        $result =  $query->row_array();
        return $result;
        
    }
    public function get_user_id($id){
        $this->db->select('*');
        $this->db->from('archive_users');
        $this->db->where(array('user_id' => $id));
        $query = $this->db->get();
        $result =  $query->row_array();
        return $result;
        
    }
    public function get_req(){
        $this->db->select('*');
        $this->db->from('archive_user_project');
        $this->db->where(array('status' => 0));
        $query = $this->db->get();
        $result =  $query->result_array();
        return $result;
        
    }
    
    
    //other functions
    public function match_user($username,$password) {
        $query = $this->db->get_where('archive_users', array('username' => $username, 'password' => $password));
        $result =  $query->row_array();
        return $result;
    }
    
    public function get_pro_user($id) {
        $this->db->select('*');
        $this->db->from('archive_user_project');
        $this->db->where(array('user_id' => $id));
        $query = $this->db->get();
        $result =  $query->result_array();
        return $result;
    }
}
