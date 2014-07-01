<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Csv_reader{
    
    var $fields;            /** columns names retrieved after parsing */ 
    var $separator = ';';    /** separator used to explode each line */
    var $enclosure = '"';    /** enclosure used to decorate each field */
    var $max_row_size = 4096;    /** maximum row size to be used for decoding **/    
    
    
    public function read_csv_file($p_Filepath, $type){
        
        if($type == 'student'){
            $key_field = "no,Firstname,Lastname,Registration no,Email,Project no";
        }else if($type == 'archive_users'){
            $key_field = "no,Firstname,Lastname,Registration no,Email";
        }else{
            $key_field = "no,Firstname,Lastname,Email";
        }
        
        $file = fopen($p_Filepath, 'r');
        $this->fields = fgetcsv($file, $this->max_row_size, $this->separator, $this->enclosure);
        $keys_values  = explode(',',$key_field);
        
        $content    =   array();
        $keys   =   $this->escape_string($keys_values);
    
        $i=0;
        while( ($row = fgetcsv($file, $this->max_row_size, $this->separator, $this->enclosure)) != false ) { 

            if( $row != null ) { // skip empty lines
                $values =   explode(',',$row[0]);

                if(count($keys) == count($values)){
                    $arr        =   array();
                    $new_values =   array();
                    $new_values =   $this->escape_string($values);
                    for($j=0;$j<count($keys);$j++){
                        if($keys[$j] != ""){

                            $arr[$keys[$j]] =   $new_values[$j];                        
                        }
                    }


                    $content[$i]=   $arr;
                    $i++;
                }
            }
        }//end while loop
        fclose($file);
        return $content;
    }// end function register

    function escape_string($data){
        $result =   array();
        foreach($data as $row){
            $result[]   =   str_replace('"', '',$row);
        }
        return $result;
    
        
        }//end function escape string

    
        }//end class
?>


