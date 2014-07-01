<?php
class Event_model extends CI_Model{
    
    public function new_event($data) {
        $query = $this->db->insert('events', $data);
        return $query;
    }
    
    public function get_event($id){
        $this->db->select('*');
        $this->db->from('events'); 
        $this->db->where(array('id' => $id));
        $query = $this->db->get();
        $result =  $query->row_array();
        return $result;
    }
    
    public function update_event($id,$data){
        $this->db->where('id', $id);
        return $this->db->update('events', $data);
    }
    
    public function del_event($id){       
       return  $this->db->delete('events', array('id' => $id)); 
    }
    
    public function load_events($data){
        
        $this->db->select('*');
        $this->db->from('events');
        $this->db->where($data);
        $query = $this->db->get();
        
        if ($query->num_rows() > 0){
            $result =  $query->result_array();
            return $result;
        }else{
            return FALSE;
        }
    }
    
    public function list_events($data){
        if($data['type'] == 'coordinator'){
            $where = "space_id=".$data['space_id']." AND creator_type='".$data['type']."'";
        } elseif($data['type'] == 'supervisor'){
            $where = "space_id=".$data['space_id']." AND creator_type='".$data['type']."' OR creator_type = 'coordinator'";
        }elseif($data['type'] == 'student'){
            $where = "space_id=".$data['space_id']." AND creator_type='".$data['type']."' OR creator_type = 'coordinator'";
        }
        $this->db->where($where,NULL, FALSE);
        $res = $this->db->get('events');
        if($res->num_rows() > 0) {
            foreach ($res->result() as $row) {
                $dt[] = $row;
            }
            return $dt;        
        }
    }
    
    public function upcoming_events($data){
        if($data['type'] == 'coordinator'){
            $where = "space_id=".$data['space_id']." AND creator_type='".$data['type']."' LIMIT 4";
        } elseif($data['type'] == 'supervisor'){
            $where = "space_id=".$data['space_id']." AND creator_type='".$data['type']."' OR creator_type = 'coordinator' LIMIT 4";
        }elseif($data['type'] == 'student'){
            $where = "space_id=".$data['space_id']." AND creator_type='".$data['type']."' OR creator_type = 'coordinator' LIMIT 4";
        }
        $this->db->where($where,NULL, FALSE);
        $res = $this->db->get('events');
        if($res->num_rows() > 0) {
            foreach ($res->result() as $row) {
                $dt[] = $row;
            }
            return $dt;        
        }
    }
}