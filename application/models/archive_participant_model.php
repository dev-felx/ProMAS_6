<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Archive_participant_model extends CI_Model{

public function add_participants($data){
    
    $result= $this->db->insert_batch('participants', $data);
    return $result; 
}

}