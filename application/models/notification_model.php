<?php
class Notification_model extends CI_Model{
    public function create_new($data) {   
        $query = $this->db->insert('notifications', $data);
        return $query;
    }
    
    //==========================================================================================================
    public function get_not_unread($user_id,$keep_last) {

        //Get the last annoucement check
        $last_check = $this->db->get_where('miscellaneous_non',array('user_id' => $user_id));
        $last = $last_check->row_array();
        $this->session->set_userdata('last_non', $last);

        //order by and table select
        $this->db->order_by("date_created", "desc");
        $this->db->from('notifications');

        //Get the level cases
        $this->db->where(array('creator_id !=' => $user_id, 'date_created >' => $last['last_not_check'], 'notifications.space_id' => $this->session->userdata('space_id'),'scope ' => '1'));
        $this->db->or_where('creator_id !=' , $user_id);
        $this->db->where(array('date_created >' => $last['last_not_check'], 'notifications.space_id' => $this->session->userdata('space_id'),'scope' => '2','sc_parameter' => 'non_stu'));
        $this->db->or_where('creator_id !=' , $user_id);
        $this->db->where(array('date_created >' => $last['last_not_check'], 'notifications.space_id' => $this->session->userdata('space_id'),'scope' => '5','sc_parameter2' => $this->session->userdata('user_id') ));

        $query = $this->db->get();
        $result = $query->result_array();
        //update lastest not check
        if($keep_last == FALSE){
            $this->db->query("update miscellaneous_non set last_not_check=now() where user_id=$user_id");
        }

        return $result;
    }
    
    public function get_not_read($user_id,$lim) {
            
        //limit order table
        $this->db->limit($lim);
        $this->db->order_by("date_created", "desc");
        $last = $this->session->userdata('last_non');
        $this->db->from('notifications');

        //get level cases
        $this->db->where(array('date_created <' => $last['last_not_check'], 'notifications.space_id' => $this->session->userdata('space_id'),'scope ' => '1'));
        $this->db->or_where('date_created <' , $last['last_not_check']);
        $this->db->where(array('notifications.space_id' => $this->session->userdata('space_id'),'scope' => '2','sc_parameter' => 'non_stu'));
        $this->db->or_where('date_created <' , $last['last_not_check']);
        $this->db->where(array('notifications.space_id' => $this->session->userdata('space_id'),'scope' => '3','sc_parameter' => $this->session->userdata('user_id')));
        $this->db->or_where('date_created <' , $last['last_not_check']);
        $this->db->where(array('notifications.space_id' => $this->session->userdata('space_id'),'scope' => '4','sc_parameter2' => $this->session->userdata('user_id')));
        $this->db->or_where('date_created <' , $last['last_not_check']);
        $this->db->where(array('notifications.space_id' => $this->session->userdata('space_id'),'scope' => '5','sc_parameter2' => $this->session->userdata('user_id')));

        //run
        $query = $this->db->get();
        $result = $query->result_array();

        return $result;

    }
    
    public function get_not_unread_stu($user_id,$keep_last) {
        //get supervisor
        $visor= $this->db->get_where('student_projects',array('student_projects.project_id' =>$this->session->userdata('project_id')));
        $result5 =  $visor->row_array();
       
            $sup_id = $result5['supervisor_id'];
        
        //Get the last annoucement check
        $last_check = $this->db->get_where('miscellaneous_stu',array('user_id' => $user_id));
        $last = $last_check->row_array();
        $this->session->set_userdata('last_stu', $last);
        //order by and table select
        $this->db->order_by("date_created", "desc");
        $this->db->from('notifications');
        //Get the level cases
        $this->db->where(array('creator_id !=' => $user_id, 'date_created >' => $last['last_not_check'], 'notifications.space_id' => $this->session->userdata('space_id'),'scope ' => '1'));
        $this->db->or_where('creator_id !=' , $user_id);
        $this->db->where(array('date_created >' => $last['last_not_check'], 'notifications.space_id' => $this->session->userdata('space_id'),'scope' => '2','sc_parameter' => 'stu'));
        $this->db->or_where('creator_id !=' , $user_id);
        $this->db->where(array('date_created >' => $last['last_not_check'], 'notifications.space_id' => $this->session->userdata('space_id'),'scope' => '3','sc_parameter' => $sup_id));
        $this->db->or_where('creator_id !=' , $user_id);
        $this->db->where(array('date_created >' => $last['last_not_check'], 'notifications.space_id' => $this->session->userdata('space_id'),'scope' => '4','sc_parameter' => $this->session->userdata('project_id')));
        $this->db->or_where('creator_id !=' , $user_id);
        $this->db->where(array('date_created >' => $last['last_not_check'], 'notifications.space_id' => $this->session->userdata('space_id'),'scope' => '5','sc_parameter' => $this->session->userdata('project_id')));

        $query = $this->db->get();
        $result = $query->result_array();
        //update lastest ann check
        if($keep_last == FALSE){
            $this->db->query("update miscellaneous_stu set last_not_check=now() where user_id=$user_id");
        }
        return $result;
    }
    
    public function get_not_read_stu($user_id,$lim) {
        //get visor
        $visor= $this->db->get_where('student_projects',array('student_projects.project_id' =>$this->session->userdata('project_id')));
        $result5 =  $visor->row_array();
        $sup_id = $result5['supervisor_id'];
        //limit order table
        $this->db->limit($lim);
        $this->db->order_by("date_created", "desc");
        $last = $this->session->userdata('last_stu');
        $this->db->from('notifications');

        //get level cases
        $this->db->where(array('date_created <' => $last['last_not_check'], 'notifications.space_id' => $this->session->userdata('space_id'),'scope ' => '1'));
        $this->db->or_where('date_created <' , $last['last_not_check']);
        $this->db->where(array('notifications.space_id' => $this->session->userdata('space_id'),'scope' => '2','sc_parameter' => 'stu'));
        $this->db->or_where('date_created <' , $last['last_not_check']);
        $this->db->where(array('notifications.space_id' => $this->session->userdata('space_id'),'scope' => '3','sc_parameter' => $sup_id));
        $this->db->or_where('date_created <' , $last['last_not_check']);
        $this->db->where(array('notifications.space_id' => $this->session->userdata('space_id'),'scope' => '4','sc_parameter' => $this->session->userdata('project_id')));
        $this->db->or_where('date_created <' , $last['last_not_check']);
        $this->db->where(array('notifications.space_id' => $this->session->userdata('space_id'),'scope' => '5','sc_parameter' => $this->session->userdata('project_id')));

        //run
        $query = $this->db->get();
        $result = $query->result_array();

        return $result;
    }
}