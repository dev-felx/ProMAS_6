<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Publish_project extends CI_Controller{
    
    public function __construct() {
        parent::__construct();
    
        $roles = array('superuser','administrator','coordinator','supervisor');
        check_session_roles($roles);
        }
    
    
    public function index(){
        
        $data['title'] = 'ProMAS | Publish Project';
        
        if($this->session->userdata['type']=='coordinator'){
            $this->load->model('project_model');
            $data['groups'] = $this->project_model->get_all_project(array('project_id >'=>0,
                'space_id'=>$this->session->userdata['space_id']));
            
        }elseif (($this->session->userdata['type']=='supervisor')) {
            
            $this->load->model('announcement_model');
            $data['groups'] = $this->announcement_model->get_grps($this->session->userdata['user_id']);
            
        }
        
        $data['views']= array('project/publish_view');    
        page_load($data);
    }
    
    public function get_project_details($group_no){
        
        $values_doc= array(
                'group_no'=>$group_no,
                'doc_status ='=>1,
                'space_id'=>  $this->session->userdata['space_id']
            );
        $this->load->model('document_model');
        $details['documents']=$this->document_model->get_document($values_doc);
        if($details['documents']!=NULL){
            $i=0;
            foreach ($details['documents'] as $key => $value) {
               $details['documents'][$i][0]['rev_file_path'] = base64_encode($details['documents'][$i][0]['rev_file_path']);
            $i++;
            }
        }
        
        $this->load->model('project_model');
        $project_id = $this->project_model->get_project_id($group_no);
        
        $this->load->model('student_model');
        $values_stud= array(
            'project_id'=>$project_id[0]['project_id'],
            'space_id'=>  $this->session->userdata['space_id']
        );
        $details['students']=$this->student_model->get_student($values_stud);
       
        header('Content-type: application/json');
        exit(json_encode($details));
        
    }
    
//    public function request_document($doc_id,$rev_id,$group_no,$doc_name){
//        $data_doc = array(
//                'doc_status' =>1,
//            );
//        $data_rev = array(
//                'rev_status' =>1,
//            );
//        $this->load->model('document_model');
//        $result = $this->document_model->update_document($rev_id,$doc_id,$data_doc,$data_rev);
//        if($result!=NULL){    
//            $this->load->model('project_model');
//            $project_id = $this->project_model->get_project_id($group_no);
//            $scope = 5;
//            $sc_p1=$project_id[0]['project_id'];
//            $desc = 'Document: ' .$doc_name.' needs to be modified contact your '.$this->session->userdata['type'].' for details';
//            $email= TRUE;
//            $notify = create_notif($desc,$scope,$email,$sc_p1,$sc_p2 = null,$url = null,$glyph = 'bell');
//    
//            if($notify==TRUE){
//                $response['status'] = 'success';
//            }else{
//                $response['status'] = 'not_vslid';
//            }
//
//        }else{
//            $response['status'] = 'not_vslid';
//        }
//        header('Content-type: application/json');
//        exit(json_encode($response));
//        }
//    
    public function publish_documents($doc_id,$project_id){
        
        $this->load->model('document_model');
        $document = $this->document_model->get_doc_archive($doc_id,  $this->session->userdata['space_id']);
       // print_r($document);        die();
        $this->load->model('project_space_model');
        $acc_year = $this->project_space_model->get_all_project_space(array('space_id'=>$this->session->userdata['space_id']));
        $acc_yr = str_replace('/','-' ,$acc_year[0]['academic_year']);
        $dest_path = './sProMAS_documents/'.$acc_yr.'/archive/group_'.$document[0]['group_no'].'/';
        //getting file extension
        $info = new SplFileInfo($document[0]['rev_file_path']);
        $ext = $info->getExtension();
        //renaming the file
        $dest_file = $dest_path.$document[0]['name'].'.'.$ext;
        
        //if upload path does not exist create directories
        if (!is_dir($dest_path)) {
            mkdir($dest_path, 0777, TRUE);
        }
        
        $copied = copy($document[0]['rev_file_path'], $dest_file);
        if($copied){
            $values = array(
                'document_name'=>$document[0]['name'],  
                'document_path'=>$dest_file,  
                'project_profile_id'=>$project_id,
                
            );
         
            $this->load->model('archive_document_model');
            $result = $this->archive_document_model->archive_document($values);    
            if($result!=NULL){
                $response['status'] = 'success';
            }else{
                $response['status'] = 'not_valid';
            }
            }else{
            //not copied
            $response['status'] = 'not_valid';
        }
        
        header('Content-type: application/json');
        exit(json_encode($response));
    }
    
    public function publish($group_no){

        $this->load->model('project_model');
        $project = $this->project_model->get_project_id($group_no);
        
        $this->load->model('project_space_model');
        $acc_year = $this->project_space_model->get_all_project_space(array('space_id'=>  $this->session->userdata['space_id']));
        
        $project_profile_data= array(
          'project_name'=>$project[0]['title'],  
          'description'=>$project[0]['description'],  
          'academic_year'=>$acc_year[0]['academic_year'],
          'publish_status'=>0,
          'department_id'=>$project[0]['department_id'],  
        );
        
        $this->load->model('project_profile_model');
        $proj_prof_id = $this->project_profile_model->add_project_profile($project_profile_data);
        if(isset($proj_prof_id) && $proj_prof_id!=NULL){
            
            $participants= array();
            $this->load->model('archive_participant_model');
            $this->load->model('student_model');
            $values_stud= array(
                'project_id'=>$project[0]['project_id'],
                'space_id'=>  $this->session->userdata['space_id']
            );
            $students=$this->student_model->get_student($values_stud);
            if($students!=NULL){
                foreach ($students as $value) {
                    $participant = array(
                        'first_name'=>$value['first_name'],
                        'last_name'=>$value['last_name'],
                        'phone_no'=>$value['phone_no'],
                        'email'=>$value['email'],
                        'type'=>'student',
                        'project_profile_id'=>$proj_prof_id,
                        );
                        array_push($participants, $participant);
            
            }
            $result_student = $this->archive_participant_model->add_participants($participants);
            
            }else{
                echo 'students empty';
            }
            
            $this->load->model('non_student_model');
            $values_non_s=array(
                'non_student_users.user_id'=>$project[0]['supervisor_id'],
                'roles.role'=>'supervisor',
                'space_id'=>  $this->session->userdata['space_id']
            );
            $spvs= array();
            $supervisor=$this->non_student_model->get_non_student($values_non_s); 
            if($supervisor!=NULL){
                $participant = array(
                        'first_name'=>$supervisor[0]['first_name'],
                        'last_name'=>$supervisor[0]['last_name'],
                        'phone_no'=>$supervisor[0]['phone_no'],
                        'email'=>$supervisor[0]['email'],
                        'type'=>'supervisor',
                        'address'=>$supervisor[0]['office_location'],
                        'project_profile_id'=>$proj_prof_id,
                        );
                        array_push($spvs, $participant);
                $result_spvs = $this->archive_participant_model->add_participants($spvs);
            }else{
                echo 'Supervisor empty';
            }
            
            $values_non_c=array(
                'roles.role'=>'coordinator',
                'space_id'=>  $this->session->userdata['space_id']
            );
            $coord = array();
            $coordinator=$this->non_student_model->get_non_student($values_non_c);
            if($coordinator!=NULL){
                $participant = array(
                        'first_name'=>$coordinator[0]['first_name'],
                        'last_name'=>$coordinator[0]['last_name'],
                        'phone_no'=>$coordinator[0]['phone_no'],
                        'email'=>$coordinator[0]['email'],
                        'type'=>'coordinator',
                        'address'=>$coordinator[0]['office_location'],
                        'project_profile_id'=>$proj_prof_id,
                        );
                    array_push($coord, $participant);
                    $result_coord = $this->archive_participant_model->add_participants($coord);
            }else{
                echo 'Coordinator empty';
            }
            
        }else{
            echo 'Project profile id not present';
        }
        
        if($result_coord != NULL && $result_spvs != NULL && $result_student != NULL){
                $response['project_profile_id'] = $proj_prof_id;
                $response['status'] = 'success';
            
        }else{
            $response['status'] = 'not_valid';
            //echo ' Some partcipant are missng';
        }
        
        header('Content-type: application/json');
        exit(json_encode($response));
    }
    
    public function upload_doc(){
        
        $this->form_validation->set_rules("fName","Name","required");
        $this->form_validation->set_message('required','%s is required');
        if($this->form_validation->run() === FALSE){
            $response['status'] = 'name_required';
        }else {
            
            $this->load->model('project_space_model');
            $acc_year = $this->project_space_model->get_all_project_space(array('space_id'=>$this->session->userdata['space_id']));
            $acc_yr = str_replace('/','-' ,$acc_year[0]['academic_year']);
            
            $this->load->library('upload');
            $config['upload_path']= './sProMAS_documents/'.$acc_yr.'/groups/group_'.$_POST['group_no'].'/';
            $config['allowed_types']= 'pdf|doc|docx|zip|rar|jpg|jpeg|gif|png';
            $config['max_size']='2048';
            $config['file_name']= $_POST['fName'];
            
                
            //if upload path does not exist create directories
            if (!is_dir($config['upload_path'])) {
                mkdir($config['upload_path'], 0777, TRUE);
            }
            $this->upload->initialize($config);
            if(!$this->upload->do_upload()){
                $response['file_errors'] = $this->upload->display_errors();
                $response['status'] = 'file_error';
            }else {
                //obtaining a file extension
                $path_parts = pathinfo($_FILES["userfile"]["name"]);
                $extension = $path_parts['extension'];
            
                $data_doc= array(
                        'doc_status' =>1,//status of the document submitted
                        'name'=>$config['file_name'],
                        'space_id' => $this->session->userdata['space_id'],
                        'creator_id' => $this->session->userdata['user_id'],
                        'creator_role' =>$this->session->userdata['type'],
                        'due_date'=>date("Y-m-d",time()+(60*60*24*14)),
                        'group_no'=>$_POST['group_no'],
                        'req_status'=>0,
                        );
                $this->load->model('document_model');    
                $result = $this->document_model->new_doc($data_doc);
                if($result!=NULL){   
                    $data_rev = array(
                            'rev_date_upload'=>date("Y-m-d",time()),
                            'rev_status'=>1,//status of the revision if approved or not
                            'rev_file_name'=>$config['file_name'],
                            'rev_file_path'=>$config['upload_path'].$config['file_name'].'.'.$extension,
                            'rev_no'=>1
                        );
                        $data_doc_update= array(
                            'doc_status' =>1,
                        );

                    $result_update = $this->document_model->update_document($result[0]['rev_id'],$result[0]['doc_id'],$data_doc_update,$data_rev);
                    
                    if($result_update!=NULL){
                        $response['status'] = 'success';
                    }
                    
                }else{
                    $response['status'] = 'not_valid';
                    
                }
                }
            }//end outer else
            header('Content-type: application/json');
            exit(json_encode($response));
            
            
                }//end function do upload
           
    public function request_doc(){
        if($_POST['req_doc']=='0'){
            $this->form_validation->set_rules("title","Document title","required");
        }
        $this->form_validation->set_rules("duedate","Receiver","required");
        $this->form_validation->set_message('required','*');
    
        if($this->form_validation->run()==FALSE){
            $response['status'] = 'not_valid';
        }else{
           
            $data = array(
                'space_id' => $this->session->userdata['space_id'],
                'creator_id' => $this->session->userdata['user_id'],
                'creator_role' => $this->session->userdata['type'],
                'due_date'=>date('Y-m-d',strtotime(mysql_real_escape_string($_POST['duedate']))),
                'group_no'=>$_POST['group_no']
            );
            if($_POST['req_doc']=='0'){
                $data['name']=$_POST['title'];
                $data['req_status']=0;//optional document for archive
            }else{
                $data['name']=$_POST['req_doc'];
                $data['req_status']=1;//required document for archive
            }
            $this->load->model('document_model');
            $result = $this->document_model->new_doc($data);
            if($result!=NULL){
                $scope= 3;
                $sc_p1 = $this->session->userdata['user_id'];
                $desc = 'Document: ' .$_POST['title'].' needs to be submitted contact your '.$this->session->userdata['type'].' for details';
                $email= TRUE;
                $notify = create_notif($desc,$scope,$email,$sc_p1,$sc_p2 = null,$url = null,$glyph = 'bell');
                if($notify){
                    $response['status'] = 'success';
                }
            }else{
                $response['status'] = 'fail';
            }
        }
        
        // You can use the Output class here too
        header('Content-type: application/json');
        exit(json_encode($response));
        
    }
    
    
}
