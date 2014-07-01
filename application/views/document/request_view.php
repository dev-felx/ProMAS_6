<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<script src="<?php echo base_url(); ?>assets/jquery/jquery-ui-1.10.3.custom/js/jquery-ui.js"></script>
<link type="text/css" rel="Stylesheet" href="<?php echo base_url(); ?>assets/jquery/jquery-ui-1.10.3.custom/css/jquery-ui.css" media="screen">

<div  class="container-fluid">
    <div class="row" >
        <div class='pull-left'><h4>Project Documents</h4></div>
        <div class=" pull-right">
            <button data-toggle="modal" href="#share_modal" type="button" class="btn btn-success push_right_bit " >Share Document</button>
            <a data-toggle="modal" href="#req_modal" type="button" class="btn btn-success  " >Request Document</a>
        </div>
    </div>
    <div class="row" style="margin-bottom: px;">
        <div class="hr"><hr/></div>
    </div>
    </div>
    
<div class="container-fluid">
    <div class="row-fluid ">
        <div class="btn-group col-sm-3">
        <button type="button" class="btn btn-primary ">View by Group</button>
        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
            <span class="caret"></span>
            <span class="sr-only">Toggle Dropdown</span>
        </button>
        <ul class="dropdown-menu" id="myTab" role="menu">
            <?php if($this->session->userdata['type']=='coordinator'){
                foreach ($all_groups as $value) {
                    echo '<li><a id="group_no_id" class="group_no" data-title="'.$value['title'].'" data-number="'.$value['group_no'].'">'.$value['title'].'</a></li>';
                }
            }else if($this->session->userdata['type']=='supervisor'){
                foreach ($groups as $value) {
                    echo '<li><a id="group_no_id" class="group_no" data-title="'.$value['title'].'" data-number="'.$value['group_no'].'">'.$value['title'].'</a></li>';
                }
            } ?>
        </ul>
        </div>
        <div id="msg_group" class="text-center text-success col-sm-8" style=""></div>
    </div>`

<div class="row-fluid">
<div id="display_table" class="col-sm-12">
    <table id="table_id" class=" table table-bordered table-striped dataTable">
     <thead>
        <tr>
            <th>Document Name</th>
            <th>Due Date</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody id="tb"></tbody>
</table>
</div>
</div>
    </div>

    
    <div id="req_modal" class=" modal fade in" >
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="req_form"  class="" action="<?php echo site_url(); ?>/project/file/request" method="post">
                   
                        <div class="modal-header">
                            <div id="msg" class="alert alert-info text-center"><b>Request Document</b></div>
                        </div>
                       <div class="modal-body">
                           <div class="form-group">
                                <label class="control-label" for="title">Document name</label><?php show_form_error('title'); ?>
                                      
                                <div class="radio"><label>
                                   <input class="req_docs" type="radio" name="req_doc" id="" value="Project Proposal"  checked="">
                                    Project Proposal
                                  </label>
                                </div>
                                <div class="radio">
                                  <label>
                                    <input class="req_docs" type="radio" name="req_doc" id="" value="Final Year Report">
                                    Final Year Report
                                  </label>
                                </div>
                                <div class="radio">
                                  <label>
                                    <input class="req_docs" type="radio" name="req_doc" id="" value="Project abstract">
                                    Abstract
                                  </label>
                                </div>
                                <div class="radio">
                                  <label>
                                      <input class="req_docs" type="radio" name="req_doc" id="others" value="0">
                                    Others
                                  </label>
                                </div>
                              <input class="form-control hide" placeholder="Name of the document to request" type="text" id="req_title" name="title">
                           </div>

                           <div class="form-group">
                                <label for="receiver">Send to</label><?php show_form_error('receiver'); ?>
                                <?php
                                    if($this->session->userdata['type']=='coordinator'){
                                        echo '<div class="checkbox"><label><input name="group" type="checkbox" value="All groups">All groups</label></div>';
                                        
                                    } else if($this->session->userdata['type']=='supervisor'){

                                        echo '<div class="checkbox"><label><input name="group" type="checkbox" value="All groups">All groups</label></div>';
                                        echo '<div class="checkbox"><label><input name="group" id="choose_group" type="checkbox" value="Choose groups">Choose groups</label></div>';
                                    }
                                    ?>
                            </div>

                           <div id='groups' class="form-group">
                           <?php if($this->session->userdata['type']=='supervisor'){
                                        echo '<select multiple name="groups[]" class="form-control">';
                                        foreach ($groups as $value) { ?>
                                        <option value="<?php echo $value['group_no']; ?>"><?php echo $value['title']; ?></option> 
                                      <?php  }
                                      echo '</select>';
                                    } ?>
                              </div>           
                           <div class="form-group">
                            <label for="Due Date" class=" control-label">Due date</label><?php show_form_error('duedate'); ?>
                            <div class="">
                                <input type="text" id="datepicker" class=" datepicker form-control" name="duedate" placeholder="Due Date">
                           </div>
                        </div>

                           <div class="modal-footer">
                                <button type="submit" class="btn btn-success" id="send_req">Request</button>
                                <a href="#" class="btn" data-dismiss="modal">Close</a>
                            </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div id="share_modal" class=" modal fade in" >
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="share_form" class="" enctype="multipart/form-data" action="<?php echo site_url(); ?>/project/file/share_doc" method="POST">
                <div class="modal-header">
                    <div id="msg_share" class="alert alert-info text-center"><b>Share Document</b>
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
                        <label for="receiver">Share with</label><?php show_form_error('receiver'); ?>
                        <?php
                            if($this->session->userdata['type']=='coordinator'){
                                echo '<div class="checkbox"><label><input name="group" type="checkbox" value="All groups">All groups</label></div>';
//                                echo '<div class="checkbox"><label><input name="group" type="checkbox" value="All supervisors">All supervisors</label></div>';
                            } else if($this->session->userdata['type']=='supervisor'){
                                echo '<div class="radio"><label><input class="group" name="group" type="radio" value="All groups">All groups</label></div>';
                                echo '<div class="radio"><label><input class="group" name="group" id="choose_group_share" type="radio" value="Choose groups">Choose groups</label></div>';
                            }
                            ?>
                    </div>

                    <div id='groups_share' class="form-group hide">
                   <?php if($this->session->userdata['type']=='supervisor'){
                                echo '<select multiple name="groups[]" class="form-control">';
                                foreach ($groups as $value) { ?>
                                <option value="<?php echo $value['group_no']; ?>"><?php echo $value['title']; ?></option> 
                              <?php  }
                              echo '</select>';
                            } ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" id="share_document">Share</button>
                    <a href="#" class="btn" data-dismiss="modal">Close</a>
                </div>
                </form>
            </div>
        </div>
    </div>
    
    <div id="upload_modal" class=" modal fade in" >
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="upload_form" class="" enctype="multipart/form-data" action="<?php echo site_url(); ?>/project/file/upload_document" method="POST">
                    
                    <input name="status" type="hidden">
                    <input name="group_no" type="hidden">
                    <input name="rev_id" type="hidden">
                    <input name="rev_status" type="hidden">
                    <input name="rev_no" type="hidden">
                    <input name="doc_name" type="hidden">
                    <input name="doc_id" type="hidden">
                <div class="modal-header">
                    <div id="msg_upload" class="alert alert-info text-center">Upload a revised version of this document<span id="msg_upload_span"></span></div>
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
    
<script>
    $('.req_docs').click(function(e) {
        if($('#others').is(':checked')) { 
                $('#req_title').removeClass('hide');
            }else{
                $('#req_title').addClass('hide');
            }
    });
    $( "#groups" ).hide();
    var site_url = "<?php echo site_url(); ?>";
    
    $(document).ready(function(){
        
        $('.group_no').click(function(e) {
        var group_no = $(this).data('number');
        var title = $(this).data('title');
        var function_url = "<?php echo site_url(); ?>/project/file/get_documents/".concat(group_no);
        
           $('#msg_group').html('<strong><h4>'+title+' documents\'</h4></strong>');
           $('#table_id > tbody').html('');
        
        
    $.get( function_url).done(function(data) {
            for(var i = 0; i < data.length; i++){
                if(data[i][0].doc_status == '1'){
                    var status = 'Submited';
                    var path = site_url+'/project/file/download/'+data[i][0].rev_file_path;
                    var x = '<a type="button" href="'+path+'" class="action_view btn_edge btn btn-primary btn-xs"><span class="glyphicon glyphicon-download push_right_bit"></span>Download</a>\n\
                             <a data-group_no="'+data[i][0].group_no+'"  data-status="'+data[i][0].doc_status+'" data-rev_id="'+data[i][0].rev_id+'"data-rev_status="'+data[i][0].rev_status+'" data-rev_no="'+data[i][0].rev_no+'" data-doc_name="'+data[i][0].name+'" data-doc_id="'+data[i][0].doc_id+'" type="button" class="upload_m action_view btn_edge btn btn-primary btn-xs"><span  href="#upload_modal"class="glyphicon glyphicon-upload push_right_bit"></span>Upload</a>';
                }else if(data[i][0].doc_status == '0'){
                    var status ='Not submitted';
                    var x = 'Not Action';
                }
                $('#table_id > tbody').append('<tr><td>' + data[i][0].name +'</td><td>'+data[i][0].due_date+'</td><td>'+status+'</td><td>'+x+'</td></tr>');
                
            }
        },"json");
       
    });
    
    $('#group_no_id').trigger('click');
        
        $('body').on('click', '.upload_m', function () {
        $('[name="status"]','#upload_form').attr('value',$(this).data('status'));
        $('[name="group_no"]','#upload_form').attr('value',$(this).data('group_no'));
        $('[name="rev_id"]','#upload_form').attr('value',$(this).data('rev_id'));
        $('[name="rev_status"]','#upload_form').attr('value',$(this).data('rev_status'));
        $('[name="rev_no"]','#upload_form').attr('value',$(this).data('rev_no'));
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
        
        
        $('#send_req').click(function() {
        var url = $("#req_form").attr("action");
        $.post( url, $("#req_form").serialize()).done(function(data) {
        if(data.status === 'not_valid'){
            $('#msg').removeClass('alert-info');
            $('#msg').addClass('alert-warning');
            $('#msg').html('Name, Group or Date can not be empty');
        }else if(data.status === 'success') {
            $('#msg').removeClass('alert-info');
            $('#msg').addClass('alert-success');
            $('#msg').html('Request sent');
            setTimeout(function(){ $('#req_modal').modal('hide'); window.location.reload(); },3000);
        }else if(data.status === 'fail') {
            $('#msg').removeClass('alert-info');
            $('#msg').addClass('alert-warning');
            $('#msg').html('Request not sent');
        }
        
        },"json");
        return false;
        });
     
     $('#share_document').click(function() {
        $("#share_form").submit(function(){
            var formData = new FormData($(this)[0]);
            var formUrl = $("#share_form").attr("action");
            $.ajax({
                url: formUrl,
                type: 'POST',
                data: formData,
                async: false,
                 success:function(data){
                     if(data.status === 'not_valid'){
                        $('#msg_share').removeClass('alert-info');
                        $('#msg_share').addClass('alert-warning');
                        $('#msg_share').html("Name or Group can not be empty");
                        
                    }else if(data.status === 'success') {
                        $('#msg_share').removeClass('alert-info');
                        $('#msg_share').addClass('alert-success');
                        $('#msg_share').html('Document successfuly shared');
                        setTimeout(function(){ $('#req_modal').modal('hide'); window.location.reload();},3000);

                    }else if(data.status === 'file_error') {
                      //  $.each(data.file_errors, function(key,val){
                        $('#msg_share').removeClass('alert-info');
                        $('#msg_share').addClass('alert-warning');
                        $('#msg_share').html(data.file_errors);
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
    
    $('.group').click(function(e) {
        if($('#choose_group_share').is(':checked')) { 
                $('#groups_share').removeClass('hide');
            }else{
                $('#groups_share').addClass('hide');
            }
    });    

    $('#choose_group').change(function() {
        $( "#groups" ).slideToggle(300);
    });
    
    $(function(){
        $( "#datepicker" ).datepicker({ maxDate: "+1y"});
    });
    
</script>