<?php
class Announcement_model extends CI_Model{
    public function create_new($data) {   
        $query = $this->db->insert('announcements', $data);
        return $query;
    }
    
    public function get_email_1_2($space,$class){
        //get students
        $this->db->select('*');
        $this->db->distinct();
        $this->db->from('students');
        $this->db->where(array('students.space_id' => $space, 'students.email !=' => $this->session->userdata('email')));
        $query = $this->db->get();
        $result =  $query->result_array();
        $email = array();
        foreach ($result as $value) {
            if($this->form_validation->valid_email($value['email']) || 1==1){
            array_push($email, $value['email']);
            }else{
              continue;
            }
        }
        
        //get non students
        $this->db->select('*');
        $this->db->distinct();
        $this->db->from('non_student_users');
        $this->db->where(array('non_student_users.space_id' => $space,'non_student_users.email !=' => $this->session->userdata('email')));
        $query2 = $this->db->get();
        $result2 =  $query2->result_array();
        $email2 = array();
        foreach ($result2 as $value) {
            if($this->form_validation->valid_email($value['email']) || 1==1){
                array_push($email2, $value['email']);
            }else{
              continue;
            }
        }
        
        //serve the emails
        if($class == 'stu'){
            return $email;
        }else if($class == 'non_stu'){
            return $email2;
        }else if($class == 'all'){
            return array_merge($email2,$email);
        }

    }
    
    
    public function get_email_3($space,$visor) {
        $this->db->select('*');
        $this->db->from('student_projects');
        $this->db->where(array('supervisor_id' => $visor,'student_projects.space_id' => $space));
        $this->db->join('students', 'students.project_id = student_projects.project_id','inner');
        $query = $this->db->get();
        $result =  $query->result_array();
        $email = array();
        foreach ($result as $value) {
            array_push($email, $value['email']);
        }
        return $email;
    }
    
    public function get_email_4($space,$grps) {
        $this->db->select('*');
        $this->db->from('students');
        $this->db->where(array('project_id' => $grps[0]));
        for ($i = 1;$i < count($grps); $i++) {
            $this->db->or_where('project_id =' ,$grps[$i]);
        }
        $query = $this->db->get();
        $result =  $query->result_array();
        $email = array();
        foreach ($result as $value) {
            array_push($email, $value['email']);
        }
        return $email;
    }
    public function get_email_5($space,$pid,$visor) {
        $this->db->select('*');
        $this->db->from('students');
        $this->db->where(array('project_id' => $pid,'student_id !=' => $this->session->userdata('user_id'),'space_id' => $space));
        $query = $this->db->get();
        $result =  $query->result_array();
        $email = array();
        foreach ($result as $value) {
            array_push($email, $value['email']);
        }
        
        if($visor != FALSE){
            //get supervisor
            $this->load->model('access/manage_users');
            $result2 = $this->manage_users->get_non_student(array('non_student_users.user_id' => $visor));
            $sup_email = $result2[0]['email'];
            array_push($email, $sup_email);
        }
        return $email;
    }

//=====================================================================================================
public function get_ann_unread($user_id,$keep_last) {
            
            //Get the last annoucement check
            $last_check = $this->db->get_where('miscellaneous_non',array('user_id' => $user_id));
            $last = $last_check->row_array();
            $this->session->set_userdata('last_non', $last);
            
            //order by and table select
            $this->db->order_by("date_created", "desc");
            $this->db->from('announcements');
            
            //Get the level cases
            $this->db->where(array('creator_id !=' => $user_id, 'date_created >' => $last['last_ann_check'], 'announcements.space_id' => $this->session->userdata('space_id'),'scope ' => '1'));
            $this->db->or_where('creator_id !=' , $user_id);
            $this->db->where(array('date_created >' => $last['last_ann_check'], 'announcements.space_id' => $this->session->userdata('space_id'),'scope' => '2','sc_parameter' => 'non_stu'));
            $this->db->or_where('creator_id !=' , $user_id);
            $this->db->where(array('date_created >' => $last['last_ann_check'], 'announcements.space_id' => $this->session->userdata('space_id'),'scope' => '5','sc_parameter2' => $this->session->userdata('user_id') ));
                
            $query = $this->db->get();
            $result = $query->result_array();
            //update lastest ann check
            if($keep_last == FALSE){
                $this->db->query("update miscellaneous_non set last_ann_check=now() where user_id=$user_id");
            }
            
            //attach user info on the anns
            $this->load->model('access/manage_users');
            foreach ($result as $key => $value) {
                if($value['creator_role'] != 'student'){
                    $result2 = $this->manage_users->get_non_student(array('non_student_users.user_id' => $value['creator_id']));
                    $result[$key] = array_merge($value,$result2[0]);
                }else{
                    $result2 = $this->manage_users->get_student(array('students.student_id' => $value['creator_id']));
                    $result[$key] = array_merge($value,$result2[0]);
                }
            }
            return $result;
        }
public function get_ann_unread_stu($user_id,$keep_last) {
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
            $this->db->from('announcements');
            //Get the level cases
            $this->db->where(array('creator_id !=' => $user_id, 'date_created >' => $last['last_ann_check'], 'announcements.space_id' => $this->session->userdata('space_id'),'scope ' => '1'));
            $this->db->or_where('creator_id !=' , $user_id);
            $this->db->where(array('date_created >' => $last['last_ann_check'], 'announcements.space_id' => $this->session->userdata('space_id'),'scope' => '2','sc_parameter' => 'stu'));
            $this->db->or_where('creator_id !=' , $user_id);
            $this->db->where(array('date_created >' => $last['last_ann_check'], 'announcements.space_id' => $this->session->userdata('space_id'),'scope' => '3','sc_parameter' => $sup_id));
            $this->db->or_where('creator_id !=' , $user_id);
            $this->db->where(array('date_created >' => $last['last_ann_check'], 'announcements.space_id' => $this->session->userdata('space_id'),'scope' => '4','sc_parameter' => $this->session->userdata('project_id')));
            $this->db->or_where('creator_id !=' , $user_id);
            $this->db->where(array('date_created >' => $last['last_ann_check'], 'announcements.space_id' => $this->session->userdata('space_id'),'scope' => '5','sc_parameter' => $this->session->userdata('project_id')));
            
            $query = $this->db->get();
            $result = $query->result_array();
            //update lastest ann check
            if($keep_last == FALSE){
                $this->db->query("update miscellaneous_stu set last_ann_check=now() where user_id=$user_id");
            }
            
            //attach user info on the anns
            $this->load->model('access/manage_users');
            foreach ($result as $key => $value) {
                if($value['creator_role'] != 'student'){
                    $result2 = $this->manage_users->get_non_student(array('non_student_users.user_id' => $value['creator_id']));
                    $result[$key] = array_merge($value,$result2[0]);
                    
                }else{
                    $result2 = $this->manage_users->get_student(array('students.student_id' => $value['creator_id']));
                    $result[$key] = array_merge($value,$result2[0]);
                }
            }
            return $result;
        }
        
        public function get_ann_read($user_id,$lim) {
            
            //limit order table
            $this->db->limit($lim);
            $this->db->order_by("date_created", "desc");
            $last = $this->session->userdata('last_non');
            $this->db->from('announcements');
            
            //get level cases
            $this->db->where(array('date_created <' => $last['last_ann_check'], 'announcements.space_id' => $this->session->userdata('space_id'),'scope ' => '1'));
            $this->db->or_where('date_created <' , $last['last_ann_check']);
            $this->db->where(array('announcements.space_id' => $this->session->userdata('space_id'),'scope' => '2','sc_parameter' => 'non_stu'));
            $this->db->or_where('date_created <' , $last['last_ann_check']);
            $this->db->where(array('announcements.space_id' => $this->session->userdata('space_id'),'scope' => '3','sc_parameter' => $this->session->userdata('user_id')));
            $this->db->or_where('date_created <' , $last['last_ann_check']);
            $this->db->where(array('announcements.space_id' => $this->session->userdata('space_id'),'scope' => '4','sc_parameter2' => $this->session->userdata('user_id')));
            $this->db->or_where('date_created <' , $last['last_ann_check']);
            $this->db->where(array('announcements.space_id' => $this->session->userdata('space_id'),'scope' => '5','sc_parameter2' => $this->session->userdata('user_id')));
            
            //run
            $query = $this->db->get();
            $result = $query->result_array();
            
            $this->load->model('access/manage_users');
            foreach ($result as $key => $value) {
                if($value['creator_role'] != 'student'){
                    $result2 = $this->manage_users->get_non_student(array('non_student_users.user_id' => $value['creator_id']));
                    $result[$key] = array_merge($value,$result2[0]);
                }else{
                    $result2 = $this->manage_users->get_student(array('students.student_id' => $value['creator_id']));
                    $result[$key] = array_merge($value,$result2[0]);
                }
            }
            return $result;
            
        }
        
        
        public function get_ann_read_stu($user_id,$lim) {
            //get visor
            $visor= $this->db->get_where('student_projects',array('student_projects.project_id' =>$this->session->userdata('project_id')));
            $result5 =  $visor->row_array();
            $sup_id = $result5['supervisor_id'];
            //limit order table
            $this->db->limit($lim);
            $this->db->order_by("date_created", "desc");
            $last = $this->session->userdata('last_stu');
            $this->db->from('announcements');
            
            //get level cases
            $this->db->where(array('date_created <' => $last['last_ann_check'], 'announcements.space_id' => $this->session->userdata('space_id'),'scope ' => '1'));
            $this->db->or_where('date_created <' , $last['last_ann_check']);
            $this->db->where(array('announcements.space_id' => $this->session->userdata('space_id'),'scope' => '2','sc_parameter' => 'stu'));
            $this->db->or_where('date_created <' , $last['last_ann_check']);
            $this->db->where(array('announcements.space_id' => $this->session->userdata('space_id'),'scope' => '3','sc_parameter' => $sup_id));
            $this->db->or_where('date_created <' , $last['last_ann_check']);
            $this->db->where(array('announcements.space_id' => $this->session->userdata('space_id'),'scope' => '4','sc_parameter' => $this->session->userdata('project_id')));
            $this->db->or_where('date_created <' , $last['last_ann_check']);
            $this->db->where(array('announcements.space_id' => $this->session->userdata('space_id'),'scope' => '5','sc_parameter' => $this->session->userdata('project_id')));
           
            //run
            $query = $this->db->get();
            $result = $query->result_array();
            
            $this->load->model('access/manage_users');
            foreach ($result as $key => $value) {
                if($value['creator_role'] != 'student'){
                    $result2 = $this->manage_users->get_non_student(array('non_student_users.user_id' => $value['creator_id']));
                    $result[$key] = array_merge($value,$result2[0]);
                }else{
                    $result2 = $this->manage_users->get_student(array('students.student_id' => $value['creator_id']));
                    $result[$key] = array_merge($value,$result2[0]);
                }
            }
            return $result;
        }
//=======================================================================================================================
        public function get_grps($user_id) {
            $this->db->select('*');
            $this->db->from('student_projects');
            $this->db->where(array('supervisor_id' => $user_id));
            $query = $this->db->get();
            $result =  $query->result_array();
            return $result;
        }
}
    