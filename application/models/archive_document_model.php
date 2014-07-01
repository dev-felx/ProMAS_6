<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 
 */

class Archive_document_model extends CI_Model{
    
    public function archive_document($data){
        $result = $this->db->insert('archive_documents', $data);
        return $result;
    }
    
}