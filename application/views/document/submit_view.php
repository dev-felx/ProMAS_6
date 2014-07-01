<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<script src="<?php echo base_url(); ?>assets/jquery/datatable/jquery.dataTables.js">

$('body').on('hidden.bs.modal', '.modal', function () {
        $(this).removeData('bs.modal');
      });

</script>

<div class="container-fluid">
<div class="row" >
        <div class='pull-left'><h4>Project Documents</h4></div>
        <div class=" pull-right">
            <button type="button" data-toggle="modal" href="#upload_review_modal" class="btn btn-success push_right_bit" >Upload Document</button>
            <button type="button" data-toggle="modal" href="#shared_doc_modal" class="btn btn-success " >Shared Documents</button>
        </div>
    </div>
    <div class="row" style="margin-bottom: 15px;">
        <div class="hr"><hr/></div>
    </div>
        
<div id="ptoject_doc_view" class=" col-sm-12" >

<table id="table_id" class=" table table-bordered table-striped dataTable">
             <!--table heading--> 
            <thead >
            <tr>
                <?php            
                    foreach ($table_head as $key ) {
                        echo '<th class=\'sorting_asc\' role="columnheader" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-sort="ascending" aria-label="'.ucwords(str_replace('_', ' ',$key )). ':activate to sort column descending">'
                        .ucwords(str_replace('_', ' ',$key )).'</th>';
                    }
                    echo '<th>Actions</th>';
                ?>
                
            </tr>
            </thead>
             <!--table body--> 
            <tbody>
                
            <?php $i=1; 
            if($documents!=NULL){
               foreach ($documents as $row){
                   if(($row[0]['doc_status'])==2){//skip rows with shared files
                       continue;
                   }
                   
                   $doc_id = $row[0]['doc_id'];
                   $file_name = $row[0]['name'];
                   
                   echo '<tr>';
                   echo '<td>'.$i.'</td>';
                   
                   foreach ($row[0] as $key=> $value) {
                       
                       if(($key == 'name')||($key == 'creator_role')||($key == 'due_date')||($key == 'doc_status')){
                   
                           if(($key=='doc_status')&& $value==0){
                                echo '<td>Not Submited</td>';
                                continue;
                           }elseif(($key=='doc_status')&& $value==1){
                               if($row[0]['rev_status']==0){
                                   echo '<td>Not Reviewed</td>';
                                   continue;
                               }else if($row[0]['rev_status']==1) {
                                   echo '<td>Reviewed</td>';
                                   continue;
                                }
                               
                           }
                           elseif(($key=='doc_status')&& $value==2){
                               echo '<td>Approved</td>';
                               continue;
                           }
                           echo '<td>'.$value.'</td>';
                       }else{
                           
                           continue;
                       }
                   
                      
                   }
                   echo '<td>';
                   ?>
                   <a  data-status="<?php echo $row[0]['doc_status']; ?>" data-rev_status="<?php echo $row[0]['rev_status']; ?>" data-rev_id="<?php echo $row[0]['rev_id']; ?>" data-rev_no="<?php echo $row[0]['rev_no']; ?>" data-doc_name="<?php echo $row[0]['name']; ?>" data-doc_id="<?php echo $row[0]['doc_id']; ?>" type="button" class="upload_m action_view btn_edge btn btn-primary btn-xs"><span  href="#upload_modal"class="glyphicon glyphicon-upload push_right_bit"></span>Upload</a>
                   <?php
                           if($row[0]['doc_status']=='1'){ ?>
            <a type="button" href="<?php echo site_url(); ?>/project/file/download/<?php echo base64_encode($row[0]['rev_file_path']);  ?>" class="action_view btn_edge btn btn-primary btn-xs"><span class="glyphicon glyphicon-download push_right_bit"></span>Download</a>
                   <?php }
                   ?>
                  <?php echo '</td>';
                   echo '</tr>'; 
                   $i++;
                }
            }else{?>
            <div class="alert alert-info">No documents submitted </div>
            <?php } ?>
            </tbody>
            </table>
           </div>
</div>
    
    <div id="upload_modal" class=" modal fade in" >
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="upload_form" class="" enctype="multipart/form-data" action="<?php echo site_url(); ?>/project/file/upload_document" method="POST">
                    
                    <input name="status" type="hidden">
                    <input name="rev_id" type="hidden">
                    <input name="rev_status" type="hidden">
                    <input name="rev_no" type="hidden">
                    <input name="doc_name" type="hidden">
                    <input name="doc_id" type="hidden">
                <div class="modal-header">
                    <div id="msg_upload" class="alert alert-info text-center">Upload Document<span id="msg_upload_span"></span></div>
                </div>
                <div class="modal-body">
                   
                    <div class="form-group">    
                       <input type="file" name="userfile">
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" id="upload_document">Upload</button>
                    <a href="#" class="btn" data-dismiss="modal">Close</a>
                </div>
                </form>
            </div>
        </div>
    </div>

<div id="upload_review_modal" class=" modal fade in" >
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="upload_review_form" class="" enctype="multipart/form-data" action="<?php echo site_url(); ?>/project/file/upload_review" method="POST">
                <div class="modal-header">
                    <div id="msg_review" class="alert alert-info text-center"><b>Upload Document for Review</b>
                    <p>Allowed types are pdf, zip, rar, jpg, jpeg, gif, doc and docx</p></div>
                </div>
                <div class="modal-body">
                   <div class="form-group">
                       <label class="control-label" for="title">Document name</label><?php show_form_error('name'); ?>
                      <input class="form-control" type="text" name="file_name">
                    </div>
                    <div class="form-group">    
                       <input type="file" name="userfile">
                    </div>
                    <div class="form-group">
                        <label for="receiver">To be reviewed by</label><?php show_form_error('receiver'); ?>
                        <?php
                        echo '<div class="radio"><label><input class="group" name="group" type="radio" value="coordinator">Coordinator</label></div>';
                        echo '<div class="radio"><label><input class="group" name="group" type="radio" value="supervisor">Supervisor</label></div>';
                        ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" id="upload_review_doc">Upload</button>
                    <a href="#" class="btn" data-dismiss="modal">Close</a>
                </div>
                </form>
            </div>
        </div>
    </div>

<div id="shared_doc_modal" class=" modal fade in" >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div id="msg_doc" class="alert alert-info text-center">Shared Documents</div>
                </div>
                <div class="modal-body">
                   <?php foreach ($documents as $row){
                echo '<div>';
                
                   if($row[0]['doc_status'] == 2){//skip rows with shared files
                       foreach ($row[0] as $key => $value) {
                       if($key == 'name'){
                           echo '<p><strong class="text-info">Name :</strong> '.$value;
                       }else if($key == 'creator_role'){
                           echo ' -- <strong class="text-info">Shared by :</strong> '.ucfirst($value).'</p>';
                       }
                       //echo $doc_details;
                   } ?>
                   <p><a type="button" href="<?php echo site_url(); ?>/project/file/download/<?php echo base64_encode($row[0]['rev_file_path']);  ?>" class="action_view btn_edge btn-link btn btn-primary btn-xs"><span class="glyphicon glyphicon-download push_right_bit"></span>Download</a></p>
                   <div class="hr"><hr/></div></div>
                   <?php
                   }
                   }
                ?>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn" data-dismiss="modal">Close</a>
                </div>
            </div>
        </div>

<script>
$(document).ready(function(){
    
    $('body').on('click', '.upload_m', function () {
        $('[name="status"]','#upload_form').attr('value',$(this).data('status'));
        $('[name="rev_id"]','#upload_form').attr('value',$(this).data('rev_id'));
        $('[name="rev_no"]','#upload_form').attr('value',$(this).data('rev_no'));
        $('[name="rev_status"]','#upload_form').attr('value',$(this).data('rev_status'));
        $('[name="doc_name"]','#upload_form').attr('value',$(this).data('doc_name'));
        $('[name="doc_id"]','#upload_form').attr('value',$(this).data('doc_id'));
        if($('[name="status"]','#upload_form').attr('value') == '0'){
            $('#msg_upload_span').html('<p>Upload new document</p>');
        }else if($('[name="status"]','#upload_form').attr('value') == '1'){
            $('msg_upload_span').html('<p>Upload a new version of the previous document</p>');
        }
        $('#upload_modal').modal();
        return false;
    });
    
    $('#upload_document').click(function() {
        $("#upload_form").submit(function(){
            var formData = new FormData($(this)[0]);
            var formUrl = $("#upload_form").attr("action");
            $.ajax({
                url: formUrl,
                type: 'POST',
                data: formData,
                async: false,
                 success:function(data){
                     if(data.status === 'success') {
                        $('#msg_upload').removeClass('alert-info');
                        $('#msg_upload').addClass('alert-success');
                        $('#msg_upload').html('Document successfuly uploaded');
                        setTimeout(function(){ $('#req_modal').modal('hide'); window.location.reload();},3000);

                    }else if(data.status === 'file_error') {
                      //  $.each(data.file_errors, function(key,val){
                        $('#msg_upload').removeClass('alert-info');
                        $('#msg_upload').addClass('alert-warning');
                        $('#msg_upload').html(data.file_errors);
                    //});
                    }
                 },
                 cache: false,
                 contentType: false,
                 processData: false
            });
            return false;
        });
        });
        
        $('#upload_review_doc').click(function() {
        $("#upload_review_form").submit(function(){
            var formData = new FormData($(this)[0]);
            var formUrl = $("#upload_review_form").attr("action");
            $.ajax({
                url: formUrl,
                type: 'POST',
                data: formData,
                async: false,
                 success:function(data){
                     if(data.status === 'not_valid'){
                        $('#msg_reviw').removeClass('alert-info');
                        $('#msg_review').addClass('alert-warning');
                        $('#msg_review').html("Fields can not be empty");
                    }else if(data.status === 'success') {
                        $('#msg_review').removeClass('alert-info');
                        $('#msg_review').addClass('alert-success');
                        $('#msg_review').html('Document successfuly shared');
                        setTimeout(function(){ $('#req_modal').modal('hide'); window.location.reload();},3000);
                    }else if(data.status === 'file_error') {
                      //  $.each(data.file_errors, function(key,val){
                        $('#msg_review').removeClass('alert-info');
                        $('#msg_review').addClass('alert-warning');
                        $('#msg_review').html(data.file_errors);
                    //});
                    }
                 },
                 cache: false,
                 contentType: false,
                 processData: false
            });
            return false;
        });
        });
        
        });

</script>