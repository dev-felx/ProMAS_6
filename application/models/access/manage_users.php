<?php
/*
 * Author: Devid Felix
 * 
 */



 class Manage_users extends CI_Model{
    
     //matches existence of a a non_student user using username and password
        function match_non_student($username, $password){
            $this->db->select('*');
            $this->db->from('non_student_users');
            $this->db->where(array('username' => $username, 'password' => $password)); 
            $this->db->join('roles', 'roles.user_id = non_student_users.user_id','inner');
            $query = $this->db->get();
                if ($query->num_rows() > 0){
                   $result =  $query->result_array();
                   return $result;
                }else{
                  return FALSE;
                }
        }
        
        //matches existence of a student user using registration_no and password
        function match_student($registration_no, $password){
            $query = $this->db->get_where('students', array('registration_no' => $registration_no, 'password' => $password));
                if ($query->num_rows() > 0){
                    return $query->result_array();
                }
        }
        
        
        
        public function get_student($data){
            $this->db->select('*');
            $this->db->from('students');
            $this->db->where($data);
            $this->db->join('courses', "students.course_id = courses.course_id",'inner');
            $query= $this->db->get();
            
            if($query->num_rows()>0 ){
                
                return $query->result_array();
            }else{
                $this->db->select('*');
                $this->db->from('students');
                $this->db->where($data);
                $query= $this->db->get();
                return $query->result_array();
            }
            
            
        }//end function get_student
        
        public function get_non_student($data){
            
            $this->db->select('*');
            $this->db->from('non_student_users');
            $this->db->where($data);
            $this->db->join('roles', "roles.user_id = non_student_users.user_id",'inner');
            $this->db->join('departments', "departments.department_id = non_student_users.department_id",'inner');
            $query= $this->db->get();
            if($query->num_rows()>0 ){
                return $query->result_array();
            }else{
            
                $this->db->select('*');
                $this->db->from('non_student_users');
                $this->db->where($data);
                $this->db->join('roles', "roles.user_id = non_student_users.user_id",'inner');
                $query= $this->db->get();
                
                return $query->result_array();
            }
            
        }//end function get_non_student
        public function get_non_student_no_department($data){
            
            $this->db->select('*');
            $this->db->from('non_student_users');
            $this->db->where($data);
            $this->db->join('roles', "roles.user_id = non_student_users.user_id",'inner');
            $query= $this->db->get();
                
                return $query->result_array();
            
        }//end function get_non_student
        
        public function get_non_student_no_role($data){
            $this->db->select('*');
            $this->db->from('non_student_users');
            $this->db->where($data);
            $query= $this->db->get();
            return $query->result_array();
        }
        
        public function update_student($user_id,$data){
            
            $this->db->where('students.student_id', $user_id);
            $result_stud = $this->db->update('students', $data); 
            
            if($result_stud){
                
                $query = $this->db->get_where('students', array('student_id' => $user_id));
            }
            
            return $query->result_array();
        }
        
        public function update_non_student($user_id,$data){
            
            $this->db->where('non_student_users.user_id', $user_id);
            $result_non_stud =  $this->db->update('non_student_users', $data);
            
            if($result_non_stud){
                $query = $this->db->get_where('non_student_users', array('user_id' => $user_id));
            }
            
            return $query->result_array();
        }
        
        public function get_all_non_student($filters=null){
            
            $this->db->select($filters['fields']);
            $this->db->from('non_student_users');
            $this->db->where('roles.role',$filters['type']);
            $this->db->join('roles', "roles.user_id = non_student_users.user_id",'inner');
            $query = $this->db->get();
            return $query->result_array();
        }
        
        public function get_all_student($filters=null){
            $this->db->select($filters['fields']);
            $query = $this->db->get('students');
            return $query->result_array();
        }
        
        public function add_non_student($data,$role){
            $result_add = $this->db->insert('non_student_users', $data); 
            if(isset($result_add) && $result_add == 1){
                $id = $this->db->insert_id();
                $result_role = $this->db->insert('roles',array('role'=>$role,'user_id' =>$id));
                if(isset($result_role) && $result_role == 1){
                    $query = $this->db->get_where('non_student_users', array('user_id' => $id));
                    return $query->result_array();
                }
               
            }//end if isset($result_add) && $result_add == 1
            
        }// end function add_non_student
        
        public function add_student($data){
            $result = $this->db->insert('students', $data);
            if(isset($result) && $result =1){
                $id = $this->db->insert_id();
                $query = $this->db->get_where('students', array('student_id' => $id));
                return $query->result_array();
            }// end if
            
        }// end function add_student
        
        public function add_group($data){
            return $this->db->insert('student_projects', $data); 
        }// end function add_group
        
        public function check_value_exists($table, $data){
            $query = $this->db->get_where($table, $data);
            if($query->num_rows() > 0){
                return TRUE;
            } else{
                return FALSE;
            }     
        }// end function check value exists
         
        public function delete_non_student($id){
            return  $this->db->delete('non_student_users', array('user_id' => $id)); 
        }
        
        public function delete_student($id){
            return  $this->db->delete('students', array('student_id' => $id)); 
        }
        public function delete_all_student($data){
            return  $this->db->delete('students',$data); 
        }
        public function delete_all_non_student($user){
            $this->db->select('*');
            $this->db->from('non_student_users');
            $this->db->join('roles', "roles.user_id = non_student_users.user_id",'inner');
            $query = $this->db->get();
            $result= $query->result_array();
            $deleted_role = array();
            $deleted_user = array();
            $i =0; $j=0;
            foreach ($result as $value) {
                if($value['role']==$user){
                    $this->db->select('*');
                    $this->db->from('roles');
                    $this->db->where('roles.user_id',$value['user_id']);
                    $query = $this->db->get();
                    $result_roles= $query->result_array();
                    if(count($result_roles)>1){
                       $deleted_role[$i] =  $this->db->delete('roles',array('role_id'=>$value['role_id'])); 
                    }elseif (count($result_roles)==1) {
                       $deleted_user[$i]=  $this->db->delete('non_student_users',array('user_id'=>$value['user_id'])); 
                    }
                }
            }
            if($deleted_role!=NULL || $deleted_user!=NULL){
                return 2;
            }
            
        }
        
}

?>
