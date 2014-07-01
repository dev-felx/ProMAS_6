<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<div id='reg_form' class="col-sm-5">
    <div class='container-fluid'>
     
          <?php if(isset($message)) { 
            echo $message;  
          } ?>
        
        <!--Upload form-->
        <form method="POST" class=" form-horizontal" role="form" enctype="multipart/form-data" action="<?php echo site_url(); ?>/project/file/upload">
               <div class="form-group">
                    <div class='col-sm-8 '>
                        <input type="file" name="userfile">
                    </div>
                </div>  

               <div class="form-group">
                    <div class='col-sm-8 '>
                        <input type="hidden" name="doc_id" value="<?php  echo $doc_id; ?>">
                        <input type="hidden" name="file_name" value="<?php  echo $file_name; ?>">
                        <button name="upload" type="submit" class="btn btn-sm btn-primary ">Upload</button>
                        <button name="cancel" id="" type="" class="btn btn-sm btn-danger ">Cancel</button>
                    </div>
               </div>

            </form>
            </div>
    
    </div>
