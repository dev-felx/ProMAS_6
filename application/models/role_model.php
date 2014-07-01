<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Role_model extends CI_Model{
    
    public function add_role($user_id,$role){
        
        if($this->db->insert('roles',array('role'=>$role,'user_id' =>$user_id))>0){
            
        return TRUE;
        }else{
            return FALSE;
        }
         
    }
    
    public function delete_role($user_id,$role){
        
        return  $this->db->delete('roles', array('user_id' =>$user_id,'role'=>$role));
        
    }
    
    public function count_roles($user_id){
        $this->db->where('user_id',$user_id);
        $this->db->from('roles');
        return $this->db->count_all_results();
    }
}

?>
