<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class File extends CI_Controller{
    
    public function __construct() {
        parent::__construct();
    
        $roles = array('superuser','administrator','coordinator','supervisor','student');
        check_session_roles($roles);
        $this->load->model('document_model');
    }

        
    public function index($message=NULL){
        
        if($this->session->userdata['type']!=='student'){
            $values = array(
                'creator_id'=>  $this->session->userdata['user_id'],
                'creator_role'=>  $this->session->userdata['type'],
            );
            
            $data['filter_fields']= array('#','Name','Group','Due date','Status');
            
            $this->load->model('project_model');
            $data['all_groups'] = $this->project_model->get_all_project(array('project_id >'=>0,'space_id'=>  $this->session->userdata['space_id']));
            
            $this->load->model('announcement_model');
            $data['groups'] = $this->announcement_model->get_grps($this->session->userdata['user_id']);
            $data['message']=$message;
            
            $data['views']=array('/document/request_view');
            page_load($data);
        
        }elseif ($this->session->userdata['type']=='student'){
            $values= array(
                'group_no'=>  $this->session->userdata['group_no'],
            );
            
            $data['documents']=  $this->document_model->get_document($values);
            //print_r($data['documents']);            die();
            $data['table_head']= array('#','Name','Created by','Due date','Status');
            $data['views']=array('/document/submit_view');
            page_load($data);
            
        }
    }
    
    public function get_documents($group_no){
        $values = array(
                'creator_id'=>  $this->session->userdata['user_id'],
                'creator_role'=>  $this->session->userdata['type'],
                'group_no'=>$group_no,
                'doc_status !='=>2
            );
        
        $documents =  $this->document_model->get_document($values);
        //print_r($documents[1][0]['rev_file_path']);
        $i=0;
        foreach ($documents as $key => $value) {
           $documents[$i][0]['rev_file_path'] = base64_encode($documents[$i][0]['rev_file_path']);
        
           $i++;
        }
        header('Content-type: application/json');
        exit(json_encode($documents));

        
    }


    public function request(){
        if($_POST['req_doc']=='0'){
            $this->form_validation->set_rules("title","Document title","required");
        }
        $this->form_validation->set_rules("group","Receiver","required");
        $this->form_validation->set_rules("duedate","Receiver","required");
        $this->form_validation->set_message('required','*');
    
        if($this->form_validation->run()==FALSE){
            $errors = array();
                // Loop through $_POST and get the keys
                foreach ($this->input->post() as $key => $value)
                {
                    // Add the error message for this field
                    $errors[$key] = form_error($key);
                }
                $response['errors'] = array_filter($errors); // Some might be empty
                $response['status'] = 'not_valid';
            
        }else{
           
            $data = array(
                'space_id' => $this->session->userdata['space_id'],
                'creator_id' => $this->session->userdata['user_id'],
                'creator_role' => $this->session->userdata['type'],
                'due_date'=>date('Y-m-d',strtotime(mysql_real_escape_string($_POST['duedate']))),
                
            );
            if($_POST['req_doc']=='0'){
                $data['name']=$_POST['title'];
                $data['req_status']=0;//optional document for archive
            }else{
                $data['name']=$_POST['req_doc'];
                $data['req_status']=1;//required document for archive
            }
            
            if($_POST['group'] == 'All groups'){
                if($this->session->userdata['type']=='supervisor'){
                    $this->load->model('announcement_model');
                    $groups = $this->announcement_model->get_grps($this->session->userdata['user_id']);
                    foreach ($groups as $value){ 
                        $data['group_no']=$value['group_no'];
                        $result = $this->document_model->new_doc($data);
                    }//paramaters for notifications
                    $scope= 3;
                    $sc_p1 = $this->session->userdata['user_id'];
                }elseif($this->session->userdata['type']=='coordinator'){
                    $this->load->model('project_model');
                    $value_proj = array(
                        'student_projects.project_id >'=>0);
                        $projects = $this->project_model->get_all_project($value_proj);
                    
                    foreach ($projects as $value){
                        $data['group_no'] = $value['group_no'];
                        $result = $this->document_model->new_doc($data);
                    }
                    $scope= 2;
                    $sc_p1 = 'stu';
                }
            }else if($_POST['group'] == 'Choose groups'){
                
                foreach ($_POST['groups'] as $value) {
                    $data['group_no'] = $value;
                    $result = $this->document_model->new_doc($data);
                }
            }
            if($result){
                
                if($_POST['group']=='All groups'){
                    $desc = 'Document: ' .$_POST['title'].' requested by '.$this->session->userdata['type'];
                    $email= TRUE;
                    $notify = create_notif($desc,$scope,$email,$sc_p1,$sc_p2 = null,$url = null,$glyph = 'bell');
                }else if($_POST['group'] == 'Choose groups'){
                    foreach ($_POST['groups'] as $value) {
                        $this->load->model('project_model');
                        $project_id = $this->project_model->get_project_id($value);
                        $scope = 5;
                        $sc_p1=$project_id[0]['project_id'];
                        $desc = 'Document: ' .$_POST['title'].' requested by '.$this->session->userdata['type'];
                        $email= TRUE;
                        $notify = create_notif($desc,$scope,$email,$sc_p1,$sc_p2 = null,$url = null,$glyph = 'bell');
                }
                    
                }
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

    public function upload_document(){
        
        $this->load->model('project_space_model');
        $values = array(
          'space_id'=>$this->session->userdata['space_id']  
        );
        $space_data = $this->project_space_model->get_all_project_space($values);
        $acc_year = str_replace('/','-' ,$space_data[0]['academic_year']);                 
        
        $this->load->library('upload');
        
        if($this->session->userdata['type']=='student'){
            
            //controlling version no of a document by counting existing versions
            if(($_POST['rev_status']==0)){
               $rev_no=$_POST['rev_no']; 
            }else if(($_POST['rev_status']==1)){
               $rev_no=$_POST['rev_no']+1;
            }
            $config['upload_path']= './sProMAS_documents/'.$acc_year.'/groups/group_'.$this->session->userdata['group_no'].'/';
            $config['allowed_types']= 'pdf|doc|docx';
            $config['overwrite']= TRUE;
            $config['remove_spaces']= FALSE;
            $config['max_size']='2048'; // in Kb
            $config['file_name']=$_POST['doc_name'].' v'.$rev_no.' by student';
            //if upload path does not exist create directories
            if (!is_dir($config['upload_path'])) {
                mkdir($config['upload_path'], 0777, TRUE);
            }
            
            $this->upload->initialize($config);

            if(!$this->upload->do_upload()){
                $response['file_errors'] = $this->upload->display_errors();
                $response['status'] = 'file_error';
                
            } else {
                //obtaining a file extension
                $path_parts = pathinfo($_FILES["userfile"]["name"]);
                $extension = $path_parts['extension'];
                
                $data_doc= array(
                    'doc_status' =>1,//status of the document submitted
                    );
                    
                    $data_rev = array(
                        'doc_id'=>$_POST['doc_id'],
                        'rev_date_upload'=>date("Y-m-d",time()),
                        'rev_status'=>0,//status of the revision if approved or not
                        'rev_file_name'=>$config['file_name'],
                        'rev_file_path'=>$config['upload_path'].$config['file_name'].'.'.$extension,
                        'rev_no'=>$rev_no
                    );
                    //controlling number existing version of the document to be only 2
                    if(($_POST['rev_status']==0)){
                        $result = $this->document_model->update_document($_POST['rev_id'],$_POST['doc_id'],$data_doc,$data_rev);
                    }else if($_POST['rev_status']==1){
                        $result = $this->document_model->insert_new_revision($data_rev);
                    }
                    
                    if($result !== NULL){
                        $response['status'] = 'success';
                    }
            
                    }//end else file uploaded successfully
            
                    }else{// else if user not student
                        //controlling version no of a document by counting existing versions
                        if(($_POST['rev_status']==0)){
                           $rev_no=$_POST['rev_no']+1; 
                        }else if(($_POST['rev_status']==1)){
                           $rev_no=$_POST['rev_no'];
                        }
                        $config['upload_path']= './sProMAS_documents/'.$acc_year.'/groups/group_'.$_POST['group_no'].'/';
                        $config['allowed_types']= 'pdf|doc|docx|zip|rar|jpg|jpeg|gif|png';
                        $config['overwrite']= TRUE;
                        $config['remove_spaces']= FALSE;
                        $config['max_size']='2048';
                        $config['file_name']=$_POST['doc_name'].' v'.$rev_no.' by '.$this->session->userdata['type'];

                        //if upload path does not exist create directories
                        if (!is_dir($config['upload_path'])) {
                            mkdir($config['upload_path'], 0777, TRUE);
                        }
                        $this->upload->initialize($config);

                        if(!$this->upload->do_upload()){
                            $response['file_errors'] = $this->upload->display_errors(); // Some might be empty
                            $response['status'] = 'file_error';

                        } else {
                            //obtaining a file extension
                            $path_parts = pathinfo($_FILES["userfile"]["name"]);
                            $extension = $path_parts['extension'];
                            
                            $data_doc= array(
                                'doc_status' =>1,//status of the document submitted
                            );
                            $data_rev = array(
                                'doc_id'=>$_POST['doc_id'],
                                'rev_date_upload'=>date("Y-m-d",time()),
                                'rev_status'=>1,//status of the document that has been revised and uploaded
                                'rev_file_name'=>$config['file_name'],
                                'rev_file_path'=>$config['upload_path'].$config['file_name'].'.'.$extension,
                                'rev_no'=>$rev_no
                            );
                            
                            //controlling number existing version of the document to be only 2
                            if(($_POST['rev_status']==1)){
                                $result = $this->document_model->update_document($_POST['rev_id'],$_POST['doc_id'],$data_doc,$data_rev);
                            }else if($_POST['rev_status']==0){
                                $result = $this->document_model->insert_new_revision($data_rev);
                            }


                            if($result !== NULL){
                                $response['status'] = 'success';
                            }

                            }//end else file uploaded successfully

                            }//end else user not a student


                            header('Content-type: application/json');
                            exit(json_encode($response));

                                }//end function do upload
                        
    public function share_doc(){
        $this->form_validation->set_rules("file_name","Name","required");
        $this->form_validation->set_rules("group","Group","required");
        $this->form_validation->set_message('required','%s');
    
        if($this->form_validation->run()==FALSE){
            $response['status'] = 'not_valid';
        }else{
            
            $this->load->model('project_space_model');
            $values = array(
              'space_id'=>$this->session->userdata['space_id']  
            );
            $space_data = $this->project_space_model->get_all_project_space($values);
            $acc_year = str_replace('/','-' ,$space_data[0]['academic_year']);
            
            $this->load->library('upload');
            $config['allowed_types']= 'pdf|doc|docx|zip|rar|jpg|jpeg|gif|png';
            $config['overwrite']= TRUE;
            $config['remove_spaces']= FALSE;
            $config['max_size']='2048';
            $config['file_name']=$_POST['file_name'];
            
            //obtaining a file extension
            $path_parts = pathinfo($_FILES["userfile"]["name"]);
            $extension = $path_parts['extension'];

            $data_doc = array(
                    'name'=>$_POST['file_name'],
                    'doc_status'=>2, //document has been shared value
                    'space_id' => $this->session->userdata['space_id'],
                    'creator_id' => $this->session->userdata['user_id'],
                    'creator_role' => $this->session->userdata['type'],
                    );

            if($_POST['group'] == 'All groups'){
            if($this->session->userdata['type']=='supervisor'){
                $this->load->model('announcement_model');
                $groups = $this->announcement_model->get_grps($this->session->userdata['user_id']);
                foreach ($groups as $value){ 
                    $config['upload_path']= './sProMAS_documents/'.$acc_year.'/groups/group_'.$value['group_no'].'/shared/';
                    //if upload path does not exist create directories
                    if (!is_dir($config['upload_path'])) {
                        mkdir($config['upload_path'], 0777, TRUE);
                    }
                    $this->upload->initialize($config);
                    if(!$this->upload->do_upload()){
                        $response['file_errors'] = $this->upload->display_errors(); // Some might be empty
                        $response['status'] = 'file_error';
                    } else {
                    
                    $data_rev = array(
                        'rev_date_upload'=>date("Y-m-d",time()),
                        'rev_status'=>1,//status of the revision if approved or not
                        'rev_file_name'=>$config['file_name'],
                        'rev_file_path'=>$config['upload_path'].$config['file_name'].'.'.$extension,
                    );
                
                    $data_doc['group_no']=$value['group_no'];
                    $result = $this->document_model->share_doc($data_doc,$data_rev);
                    }
                }
                $scope= 3;
                $sc_p1 = $this->session->userdata['user_id'];
            }elseif($this->session->userdata['type']=='coordinator'){
                $this->load->model('project_model');
                $value_proj = array(
                    'student_projects.project_id >'=>0);
                $projects = $this->project_model->get_all_project($value_proj);
                foreach ($projects as $value){
                    $config['upload_path']= './sProMAS_documents/'.$acc_year.'/groups/group_'.$value['group_no'].'/shared/';
                    //if upload path does not exist create directories
                    if (!is_dir($config['upload_path'])) {
                        mkdir($config['upload_path'], 0777, TRUE);
                    }
                    $this->upload->initialize($config);
                    if(!$this->upload->do_upload()){
                        $response['file_errors'] = $this->upload->display_errors(); // Some might be empty
                        $response['status'] = 'file_error';
                    } else {
                    
                    $data_rev = array(
                        'rev_date_upload'=>date("Y-m-d",time()),
                        'rev_status'=>1,//status of the revision if approved or not
                        'rev_file_name'=>$config['file_name'],
                        'rev_file_path'=>$config['upload_path'].$config['file_name'].'.'.$extension,
                    );
                
                    $data_doc['group_no'] = $value['group_no'];
                    $result = $this->document_model->share_doc($data_doc,$data_rev);
                    }
                }
                $scope= 2;
                $sc_p1 = 'stu';
            }
            }else if($_POST['group'] == 'Choose groups'){
                foreach($_POST['groups'] as $value) {
                    $config['upload_path']= './sProMAS_documents/'.$acc_year.'/groups/group_'.$value.'/shared/';
                    //if upload path does not exist create directories
                    if (!is_dir($config['upload_path'])) {
                        mkdir($config['upload_path'], 0777, TRUE);
                    }
                    
                    $this->upload->initialize($config);
                    if(!$this->upload->do_upload()){
                        $response['file_errors'] = $this->upload->display_errors(); // Some might be empty
                        $response['status'] = 'file_error';
                    } else {
                    
                    $data_rev = array(
                        'rev_date_upload'=>date("Y-m-d",time()),
                        'rev_status'=>1,//status of the revision if approved or not
                        'rev_file_name'=>$config['file_name'],
                        'rev_file_path'=>$config['upload_path'].$config['file_name'].'.'.$extension,
                    );
                
                    $data_doc['group_no']=$value;
                    $result = $this->document_model->share_doc($data_doc,$data_rev);
                    }
                }
            }
            
            if($result !== NULL){

                if($_POST['group']=='All groups'){
                    $desc = 'Document: ' .$_POST['file_name'].' shared by '.$this->session->userdata['type'];
                    $email= TRUE;
                    $notify = create_notif($desc,$scope,$email,$sc_p1,$sc_p2 = null,$url = null,$glyph = 'bell');
                }else if($_POST['group'] == 'Choose groups'){
                    foreach ($_POST['groups'] as $value) {
                        $this->load->model('project_model');
                        $project_id = $this->project_model->get_project_id($value);
                        $scope = 5;
                        $sc_p1=$project_id[0]['project_id'];
                        $desc = 'Document: ' .$_POST['title'].' requested by '.$this->session->userdata['type'];
                        $email= TRUE;
                        $notify = create_notif($desc,$scope,$email,$sc_p1,$sc_p2 = null,$url = null,$glyph = 'bell');
                    }

                }
            }
                    
            if($notify){
                $response['status'] = 'success';
            }
                    
            }//end outer else form validation
   
            // You can use the Output class here too
            header('Content-type: application/json');
            exit(json_encode($response));
            
            }//end function share doc
    public function upload_review(){
        $this->form_validation->set_rules("file_name","Name","required");
        $this->form_validation->set_rules("group","Group","required");
        $this->form_validation->set_message('required','%s');
    
        if($this->form_validation->run()==FALSE){
            $response['status'] = 'not_valid';
        }else{
            
            $this->load->model('project_space_model');
            $values = array(
              'space_id'=>$this->session->userdata['space_id']  
            );
            $space_data = $this->project_space_model->get_all_project_space($values);
            $acc_year = str_replace('/','-' ,$space_data[0]['academic_year']);                 

            $this->load->library('upload');

            if($this->session->userdata['type']=='student'){
                $rev_no=1;
                $config['upload_path']= './sProMAS_documents/'.$acc_year.'/groups/group_'.$this->session->userdata['group_no'].'/';
                $config['allowed_types']= 'pdf|doc|docx|zip|rar|jpg|jpeg|gif|png';
                $config['overwrite']= TRUE;
                $config['remove_spaces']= FALSE;
                $config['max_size']='2048'; // in Kb
                $config['file_name']=$_POST['file_name'].' v'.$rev_no.' by student';
                //if upload path does not exist create directories
                if (!is_dir($config['upload_path'])) {
                    mkdir($config['upload_path'], 0777, TRUE);
                }

                $this->upload->initialize($config);

                if(!$this->upload->do_upload()){
                    $response['file_errors'] = $this->upload->display_errors();
                    $response['status'] = 'file_error';
                } else {
                    //obtaining a file extension
                    $path_parts = pathinfo($_FILES["userfile"]["name"]);
                    $extension = $path_parts['extension'];
                    
                    
                    
                    if($_POST['group']=='supervisor'){
                        $this->load->model('project_model');
                        $project = $this->project_model->get_project_id($this->session->userdata['group_no']);
                        $creator_id =$project[0]['supervisor_id'];
                    }else if($_POST['group']=='coordinator'){
                        $values_non_c=array(
                            'roles.role'=>'coordinator',
                            'space_id'=>  $this->session->userdata['space_id']
                        );
                        $this->load->model('non_student_model');
                        $coordinator=$this->non_student_model->get_non_student($values_non_c);
                        $creator_id=$coordinator[0]['user_id'];
                    }
                    
                    $data_doc= array(
                        'doc_status' =>1,//status of the document submitted
                        'name'=>$_POST['file_name'],
                        'space_id' => $this->session->userdata['space_id'],
                        'creator_id' => $creator_id,
                        'creator_role' =>$_POST['group'],
                        'due_date'=>date("Y-m-d",time()+(60*60*24*30)),
                        'group_no'=>$this->session->userdata['group_no'],
                        'req_status'=>0,
                        );
                    $this->load->model('document_model');    
                    $result = $this->document_model->new_doc($data_doc);
                    if($result!=NULL){ 
                        $data_rev = array(
                            'rev_date_upload'=>date("Y-m-d",time()),
                            'rev_status'=>0,//status of the revision if approved or not
                            'rev_file_name'=>$config['file_name'],
                            'rev_file_path'=>$config['upload_path'].$config['file_name'].'.'.$extension,
                            'rev_no'=>$rev_no
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
                    
                    }//end else file uploaded successfully
            
                    }
                    
            }//end outer else form validation
   
            // You can use the Output class here too
            header('Content-type: application/json');
            exit(json_encode($response));
            
            }//end function share doc
     
 
            public function preview($file_path){
                
                $file_path = base64_decode($file_path);
                
                $this->load->library('fpdf/fpdf');
                define('FPDF_FONTPATH',$this->config->item('fonts_path'));

                $this->load->library('fpdi/fpdi');

                $pdf = new FPDI();

                // get the page count
                $pageCount = $pdf->setSourceFile($file_path);
                // iterate through all pages
                for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                    // import a page
                    $templateId = $pdf->importPage($pageNo);
                    // get the size of the imported page
                    $size = $pdf->getTemplateSize($templateId);

                    // create a page (landscape or portrait depending on the imported page size)
                    if ($size['w'] > $size['h']) {
                        $pdf->AddPage('L', array($size['w'], $size['h']));
                    } else {
                        $pdf->AddPage('P', array($size['w'], $size['h']));
                    }

                    // use the imported page
                    $pdf->useTemplate($templateId);

                    $pdf->SetFont('Helvetica');
                    $pdf->SetXY(5, 5);
                    $pdf->Write(8, 'A complete document imported with FPDI');
                }

                // Output the new PDF
                $pdf->Output();
        
        
    }//end function preview

    public function download($file_path){
        
        //$info = new SplFileInfo('foo.txt');
        //var_dump($info->getFilename());
        
        $this->load->helper('file');
        $this->load->helper('download');
        
         
        //reading the file content
        $data = file_get_contents(base64_decode($file_path));
        //download a file from a server
        force_download(basename(base64_decode($file_path)), $data);
        
    }//end function download

    
}
        
