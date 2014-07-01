<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Archive extends CI_Controller {
    
    public function __construct() {
        
        parent::__construct();
        
        $this->load->model('archive_model');
    }

    public function index(){
        $this->load->view('archive/search/homepage');
    }
    
    public function suggestions(){
        $searchterm = $this->input->post('search_key');
        $data['ajax_req'] = TRUE;
        $search_term = mysql_real_escape_string(strip_tags($searchterm));
        $data['res'] = $this->archive_model->st($search_term);
        $this->load->view('archive/search/suggest', $data);
    }

    public function search(){
        $data['view'] = 'suggest';
        $this->load->view('archive/search/search', $data);
    }
    
    public function search_result(){
        $searchterm = $this->input->post('search_key');
        $search_term = mysql_real_escape_string(strip_tags($searchterm));
        $data['res'] = $this->archive_model->search($search_term);
        $this->load->view('archive/search/search_result', $data);
    }
    
    public function profile($id){
        $data['result'] = $this->archive_model->profile($id);
        $data['part'] = $this->archive_model->participants($id);
        $data['docu'] = $this->archive_model->documents($id);
        $data['abst'] = $this->archive_model->abst($id);
        $this->load->view('archive/search/profile_view', $data);
    }

    public function explore(){
        $data['a_y'] = $this->archive_model->get_academic_year();
        $data['dep'] = $this->archive_model->get_department();
        $data['res'] = $this->archive_model->explore();
        $this->load->view('archive/search/explore', $data);
    }
    
    public function explore_filter(){
        $term = $this->input->post('filter_cat');
        $did = $this->archive_model->get_department_id($term);
        $data['res'] = $this->archive_model->filter_alpha($term);
        $data['res'] = $this->archive_model->filter_year($term);
        if(isset($did['department_id'])){
            $data['res'] = $this->archive_model->filter_department($did['department_id']);
        }
        $this->load->view('archive/search/filter', $data);
    }
    
    public function download($file_path){
        
        $this->load->helper('file');
        $this->load->helper('download');
        
        $data = file_get_contents(base64_decode($file_path));
        force_download(basename(base64_decode($file_path)), $data);
        
    }
}
