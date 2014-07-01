<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


class Assess extends CI_Controller{
    function __construct() {
         
        parent::__construct();
        //checking session and allowed roles
        $roles = array('administrator','supervisor','panel_head','coordinator');
        check_session_roles($roles);
        $this->load->model('assessment_model');
        $this->load->model('announcement_model');
    }
    
    public function index(){
         //prepare data
        $forms = $this->assessment_model->get_weekly($this->session->userdata('user_id'));
        if($forms !=  NULL){
            redirect('assessment/assess/weekly', 'location');
        }else{
            $data['views'] = array('/assessment/welcome');
        }
        
        //load view
        $data['title'] = 'sProMAS | Assessment';
        page_load($data);
    }
    
    
    public function setup() {
        //get project supervised
        $projects = $this->announcement_model->get_grps($this->session->userdata('user_id'));
        
        //week interval
        $interval = $this->assessment_model->get_interval();
        if($interval >= '2'){  $st = 0; }else{ $st = 1;}
        $stepper = (int)$interval;
        foreach ($projects as $value) {
            //create groups forms
            $data2 = array(
                'project_id' => $value['project_id'],
                'project_name' => $value['title'],
                'space_id' => $this->session->userdata('space_id'),
                'owner' => $this->session->userdata('user_id')
            );
            
            $data2['type'] = 'Project proposal';
            $data2['semester'] = 1;
            $this->assessment_model->new_group($data2);
            
            $data2['type'] = 'Project progress report';
            $data2['semester'] = 1;
            $this->assessment_model->new_group($data2);
            
            $data2['type'] = 'Final Project report';
            $data2['semester'] = 2;
            $this->assessment_model->new_group($data2);
            
            
            $students = $this->assessment_model->get_project_stu($value['project_id']);
            //create weekly forms 
            foreach ($students as $value2) {
                for($i = $st; $i <= 15;$i = $i + $stepper){
                    if($i == 0){
                        continue;
                    }
                    $data = array(
                        'student' => $value2['registration_no'],
                        'student_name' => ($value2['first_name'].' '.$value2['last_name']),
                        'project_id' => $value['project_id'],
                        'project_name' => $value['title'],
                        'owner' => $this->session->userdata('user_id'),
                        'week_no' => $i,
                        'space_id' => $this->session->userdata('space_id'),
                         'semester' => 1,
                    );
                    $res = $this->assessment_model->new_weekly($data);
                }
            }
            
            foreach ($students as $value2) {
                for($i = $st; $i <= 15;$i = $i + $stepper){
                    if($i == 0){
                        continue;
                    }
                    $data = array(
                        'student' => $value2['registration_no'],
                        'student_name' => ($value2['first_name'].' '.$value2['last_name']),
                        'project_id' => $value['project_id'],
                        'project_name' => $value['title'],
                        'owner' => $this->session->userdata('user_id'),
                        'week_no' => $i,
                        'space_id' => $this->session->userdata('space_id'),
                        'semester' => 2,
                    );
                    $res = $this->assessment_model->new_weekly($data);
                }
            }
        }
        
        
             redirect('assessment/assess/', 'location');
                    
    }
    
    
    public function weekly(){
        //get data
        $data['forms'] = $this->assessment_model->get_weekly($this->session->userdata('user_id'));
        $data['projects'] = $this->announcement_model->get_grps($this->session->userdata('user_id'));
        
        if($data['forms'] ==  NULL){
            redirect('assessment/assess', 'location');
        }
        //prepare views
        $data['sub_title'] = 'Weekly Assessment';
        $data['views'] = array('/assessment/assess_view','/assessment/weekly_view');
        $data['title'] = 'sProMAS | Assessment';
        //load view
        page_load($data);
        
    }
    
    public function ignore_from(){
        $res = $this->assessment_model->ignore_form($_POST['form_id']);
        if($res){
            $response['forms'] = $this->assessment_model->get_weekly($this->session->userdata('user_id'));
            $response['status'] = 'cool';
        }
        header('Content-type: application/json');
        exit(json_encode($response));
    }
    public function report(){
        //get data
        $data['forms'] = $this->assessment_model->get_report($this->session->userdata('user_id'));
        $data['projects'] = $this->announcement_model->get_grps($this->session->userdata('user_id'));
        
        if($data['forms'] ==  NULL){
            redirect('assessment/assess', 'location');
        }
        //prepare views
        $data['sub_title'] = 'Report Assessment';
        $data['views'] = array('/assessment/assess_view','/assessment/group_view');
        $data['title'] = 'sProMAS | Assessment';
        //load view
        page_load($data);
        
    }
        
    public function get_pro_stu(){
        $id = $_POST['id'];
        $response['students'] = $this->assessment_model->get_project_stu($id);
        $response['week'] = $this->assessment_model->get_week();
        header('Content-type: application/json');
        exit(json_encode($response));
    }
    
    public function average(){
        $data['forms'] = $this->assessment_model->get_weekly($this->session->userdata('user_id'));
        $data['projects'] = $this->announcement_model->get_grps($this->session->userdata('user_id'));
        
        if($data['forms'] ==  NULL){
            redirect('assessment/assess', 'location');
        }

        //prepare views
        $data['sub_title'] = 'Average for weekly Assessment';
        $data['views'] = array('/assessment/ave_view');
        $data['title'] = 'sProMAS | Assessment';
        //load view
        page_load($data);
        
    }
    
    public function save_form() {
        $this->form_validation->set_rules("init","Initiative","required|is_natural|less_than[6]");
        $this->form_validation->set_rules("gen","General Project Understanding","required|is_natural|less_than[6]");
        $this->form_validation->set_rules("spec","Specific Contribution","required|is_natural|less_than[11]");
        $this->form_validation->set_rules("qn"," Questions and Answers","required|is_natural|less_than[6]");
        $this->form_validation->set_message('required','%s marks are required');
        $this->form_validation->set_message('is_natural','%s marks have to a natural number');
        if ($this->form_validation->run() == FALSE){
                echo validation_errors();        
        }else {
            $data = array(
                'initiative' => $_POST['init'],
                'understand' => $_POST['gen'],
                'contribution' => $_POST['spec'],
                'qna' => $_POST['qn'],
                'comments' => $_POST['com'],
                'form_id' => $_POST['form_id']
            );
            $res = $this->assessment_model->save_form($data);
            if($res){
                $response['forms'] = $this->assessment_model->get_weekly($this->session->userdata('user_id'));
                $response['status'] = 'cool';
            }
            header('Content-type: application/json');
            exit(json_encode($response));
        }
    }
    
    public function save_form_grp(){ 
        $this->form_validation->set_rules("abs","Abstract","required|is_natural|less_than[4]");
        $this->form_validation->set_rules("ack","Acknowledgment","required|is_natural|less_than[3]");
        $this->form_validation->set_rules("con","Table Of Contents","required|is_natural|less_than[4]");
        $this->form_validation->set_rules("intro","General Introduction","required|is_natural|less_than[5]");
        $this->form_validation->set_rules("main","Main Body","required|is_natural|less_than[16]");
        $this->form_validation->set_rules("ref","References","required|is_natural|less_than[4]");
        $this->form_validation->set_message('required','%s marks are required');
        $this->form_validation->set_message('is_natural','%s marks have to a natural number');
        if ($this->form_validation->run('reg') == FALSE){
                echo validation_errors();        
        }else {
            $data = array(
                'abs' => $_POST['abs'],
                'ack' => $_POST['ack'],
                'comments' => $_POST['com'],
                't_content' => $_POST['con'],
                'intro' => $_POST['intro'],
                'main' => $_POST['main'],
                'ref' => $_POST['ref']
            );
            $form_id = $_POST['form_id'];
            $res = $this->assessment_model->save_form_grp($data,$form_id);
            if($res){  
                $response['forms'] = $this->assessment_model->get_report($this->session->userdata('user_id'));
                $response['status'] = 'cool';
            }
            header('Content-type: application/json');
            exit(json_encode($response));
        }
    }
    
    
    public function export(){ 
        //prepare 
        if($this->session->userdata('type') == 'supervisor'){
            $data['projects'] = $this->announcement_model->get_grps($this->session->userdata('user_id'));
        }elseif($this->session->userdata('type') == 'coordinator'){
            $data['projects'] = $this->assessment_model->get_grps_coor($this->session->userdata('space_id'));
        }
        
        $data['sub_title'] = 'Export Assessment Documents';
        $data['views'] = array('/assessment/export_view');
        $data['title'] = 'sProMAS | Assessment';
        //load view
        page_load($data);
    }
    
    public function gen_csv_sup(){
        header( 'Content-Type: text/csv' );
        header( 'Content-Disposition: attachment;filename=project_assessment.csv');
        $fp = fopen('php://output', 'w');
        
        //put titles
        fputcsv($fp, array('registration_number','first name', 'last name', 'project group number', 'project name', 'weekly assessmemt','report assessment', 'presentation assessment', 'total marks'));
        if($_POST['grp'] == '0'){
            $data['projects'] = $this->announcement_model->get_grps($this->session->userdata('user_id'));
        }else{
            $data['projects'] = $this->assessment_model->get_grps_list($_POST['receiver']);
        }

            foreach ($data['projects'] as $value) {
                $students = $this->assessment_model->get_project_stu_ex($value['project_id']);
                foreach ($students as $sub_value) {
                    $x = implode(',',array_values($sub_value));
                    $x = $x.','.$value['group_no'].','.$value['title'];
                    $y = $this->get_stu_week_total($sub_value['registration_no'],$_POST['time']);
                    $z = $this->get_stu_report_total($value['project_id'],$_POST['time']);
                    $k = $this->get_stu_pres_total($value['project_id'],$_POST['time']);
                    $m = $y+$z+$k;
                    $x = $x.','.$y.','.$z.','.$k.','.$m;
                    fputs($fp, $x);
                    fputs($fp, "\r\n");
                }
            }
        
        fclose($fp);
    }
    public function gen_csv_coor(){
        header( 'Content-Type: text/csv' );
        header( 'Content-Disposition: attachment;filename=project_assessment.csv');
        $fp = fopen('php://output', 'w');
        
        //put titles
        fputcsv($fp, array('registration_number','first name', 'last name', 'project group number', 'project name', 'weekly assessmemt','report assessment', 'presentation assessment', 'total marks'));
        if($_POST['grp2'] == '0'){
            $data['projects'] = $this->assessment_model->get_grps_coor($this->session->userdata('space_id'));
        }else{
            $data['projects'] = $this->assessment_model->get_grps_list($_POST['receiver2']);
        }
            //print_r($data['projects']);die();
            foreach ($data['projects'] as $value) {
                $students = $this->assessment_model->get_project_stu_ex($value['project_id']);
                foreach ($students as $sub_value) {
                    $x = implode(',',array_values($sub_value));
                    $x = $x.','.$value['group_no'].','.$value['title'];
                    $y = $this->get_stu_week_total($sub_value['registration_no'],$_POST['time']);
                    $z = $this->get_stu_report_total($value['project_id'],$_POST['time']);
                    $k = $this->get_stu_pres_total($value['project_id'],$_POST['time']);
                    $m = $y+$z+$k;
                    $x = $x.','.$y.','.$z.','.$k.','.$m;
                    fputs($fp, $x);
                    fputs($fp, "\r\n");
                }
            }
        
        fclose($fp);
    }
    
      public function get_stu_week_total($reg,$sc){
          if($sc <= 2){
                    $student_forms = $this->assessment_model->get_stu_form_ave($reg,$sc);
                }else{
                    $student_forms = $this->assessment_model->get_stu_form($reg);
                }

                //average fields
                $form = array(
                    'initiative' => 0,
                    'understand' => 0,
                    'contribution' => 0,
                    'qna' => 0,
                );
                
                //sum all
                foreach ($student_forms as $sub_sub_value) { 
                    $form['initiative'] =  $form['initiative'] + $sub_sub_value['initiative'];
                    $form['understand'] =  $form['understand'] + $sub_sub_value['understand'];
                    $form['contribution'] = $form['contribution'] + $sub_sub_value['contribution'];
                    $form['qna'] = $form['qna'] + $sub_sub_value['qna'];
                }
                
                //average
                $num  = count($student_forms);
                if($num != 0){
                $form['initiative'] =  $form['initiative'] / $num;
                $form['understand'] =  $form['understand'] / $num;
                $form['contribution'] = $form['contribution'] / $num;
                $form['qna'] = $form['qna'] / $num;
                }else{
                    $total = 0;
                    return $total;
                }
                 
                $total = 0;
                if( $form != null){
                foreach ($form as $value) {
                    $total = $total + $value;
                }
                }
                
                return $total;
      }
      
      
      public function get_stu_report_total($project_id,$sc) {
          $this->db->select('abs,ack,t_content,intro,main,ref');
          $this->db->from('assess_groups');
          if($sc == 1){
                $this->db->where(array('project_id' => $project_id,'type' => 'Project proposal'));
          }else if($sc == 2){
                $this->db->where(array('project_id' => $project_id,'type' => 'Final Project report'));
          }else{
                $this->db->where(array('project_id' => $project_id));
          }
          
          $query = $this->db->get();
          $result =  $query->result_array();
          $total = 0;
            if( $result != null){
            foreach ($result[0] as $value) {
                $total = $total + $value;
            }
            }
            return $total;
   
      }
      
      public function get_stu_pres_total($project_id,$sc){
          $this->db->select('im,pq,ptc,sc,sf');
          $this->db->from('assess_pres');
          if($sc == 1){
                $this->db->where(array('project_id' => $project_id,'semester' => 1));
          }else if($sc == 2){
              $this->db->where(array('project_id' => $project_id,'semester' => 2));
          }else{
              $this->db->where(array('project_id' => $project_id));
          }
          $query = $this->db->get();
          $result =  $query->result_array();
          $total = 0;
          if( $result != null){
            foreach ($result[0] as $value) {
                $total = $total + $value;
            }
          }
            return $total;
      }
      
      
      public function calc_ave() {
        $data['projects'] = $this->announcement_model->get_grps($this->session->userdata('user_id'));
        //compute the average
        $ave_forms = array();
        foreach ($data['projects'] as $value) {
            $students = $this->assessment_model->get_project_stu($value['project_id']);

            foreach ($students as $sub_value) {
                if($_POST['sc'] <= 2){
                    $student_forms = $this->assessment_model->get_stu_form_ave($sub_value['registration_no'],$_POST['sc']);
                }else{
                    $student_forms = $this->assessment_model->get_stu_form($sub_value['registration_no']);
                }
                //average fields
                $form = array(
                    'student' => null,
                    'student_name' => null,
                    'project_id' => null,
                    'project_name' => null,
                    'initiative' => 0,
                    'understand' => 0,
                    'contribution' => 0,
                    'qna' => 0,
                );
                
                //sum all
                foreach ($student_forms as $sub_sub_value) {
                    $form['student'] = $sub_sub_value['student'];
                    $form['student_name'] = $sub_sub_value['student_name']; 
                    $form['project_id'] = $sub_sub_value['project_id']; 
                    $form['project_name'] = $sub_sub_value['project_name']; 
                    $form['initiative'] =  $form['initiative'] + $sub_sub_value['initiative'];
                    $form['understand'] =  $form['understand'] + $sub_sub_value['understand'];
                    $form['contribution'] = $form['contribution'] + $sub_sub_value['contribution'];
                    $form['qna'] = $form['qna'] + $sub_sub_value['qna'];
                }
                
                //average
                $num  = count($student_forms);
                $form['initiative'] =  $form['initiative'] / $num;
                $form['understand'] =  $form['understand'] / $num;
                $form['contribution'] = $form['contribution'] / $num;
                $form['qna'] = $form['qna'] / $num;
                    
                array_push($ave_forms, $form);
            }
            
            
        }
        //print_r($ave_forms);die();
        $response['forms'] = $ave_forms;
        
        header('Content-type: application/json');
        exit(json_encode($response));
      }
      
}
