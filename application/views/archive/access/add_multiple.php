<!--<div class="col-sm-6 col-sm-offset-3" id="add_single">
    <form id="add_many_form"  role="form" action="<?php// echo site_url('archive/access/user/new'); ?>" method="POST">
        <div id="msg" class="alert alert-info text-center">Upload User List in Csv</div>
        <div><a href="#" class="">Click here to download a template file</a></div>
    </form>
</div>-->
<?php $user='archive_users' ?>
<div id='' class='container-fluid'>
 
    
    <div id="reg_form">
    <div class=' text-info row'>
        <div class="">
         <?php if(isset($message)) { ?>

            <p class='text-center text-success'><b><?php echo $message; ?></b></p>

         <?php    } else{ ?>
            <h5 class='pull-left'>To Register archive users using a file,<a href='<?php echo site_url(); ?>/manage_users/add_group/download/<?php echo $user; ?>'><b> Click here first to download a Template file</b>,</a> then you can upload a new file</h5>
         <?php } ?>
            <div class='pull-right '>
            <button data-toggle="modal" href="#upload_file_modal" class="btn btn-success"  role="button" >Upload a new file</button>
            <a onclick="return confirm('Are you sure you want to delete all files?')" href="<?php echo site_url(); ?>/archive/users/delete_all_files/<?php echo $user; ?>" type="button" class="btn btn-danger push_right_bit " >Delete all Files</a>
            </div>
        </div>
    </div>
        <div class="row">
        <div class="hr"><hr/></div></div>
            <div class='row'>
                <div id='file_list' >
                    <?php
                    $file_path = get_filenames('./sProMAS_documents/'.$acc_yr.'/registration_files/'.$user.'/',TRUE);
                    $files_list = directory_map('./sProMAS_documents/'.$acc_yr.'/registration_files/'.$user.'/');
        
                        $i=0;
                    if(!empty($files_list)){
                    foreach($files_list as $file){
                    ?>          
                                <div class=" container-fluid list-group-item">
                                    <div class="pull-left"><?php echo $file; ?></div>
                                    <form action="<?php echo site_url(); ?>/archive/users/delete_file/<?php echo $user; ?>" method="POST">
                                        <button type='submit' onclick="return confirm('Are you sure you want to delete <?php echo $file; ?> file')" class="btn_edge btn btn-danger btn-sm pull-right push_left_bit">
                                            <span class="glyphicon glyphicon-trash push_right_bit">
                                            </span>Delete
                                        </button>
                                        <input type="hidden" name="file_path" value="<?php echo $file_path[$i]; ?>">
                                    </form>
                                    <form method="POST" action="<?php echo site_url(); ?>/archive/users/add_multiple/<?php echo $user; ?>">
                                        <button type='submit' href="" class="btn_edge btn btn-primary btn-sm pull-right">
                                            <span class="glyphicon glyphicon-registration-mark push_right_bit">
                                            </span>Register
                                        </button>
                                        <input type="hidden" name="file_path" value="<?php echo $file_path[$i]; ?>">
                                    </form>
                                </div>



                <?php
                $i++;
                }//end for

                   } else {
                       echo '<div  class="alert alert-info text-center">No files have been uploded</div>';
                   }
                ?>
               <p><?php// echo $links; ?></p>

               <!--end upload form-->
                   
               </div>
            </div>
        <div class="row">
            <a class="btn btn- btn-warning" href="<?php echo site_url(); ?>/archive/users" onclick="" role="button" >Cancel</a>
        </div>
        </div>
    <div id="upload_file_modal" class=" modal fade in" >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div id="msg_upload" class="alert alert-info text-center"><strong>Upload a File</strong><span id="msg_upload_span"></span></div>
                <h5 class="text-info">File should adhere format specified on the template, 
                    The maximum size of an uploaded file for this application is <b>2MB</b></h5>
                </div>
                <form id="upload_form" method="POST" class=" form-horizontal" role="form" enctype="multipart/form-data" action="<?php echo site_url(); ?>/manage_users/add_group/upload/<?php echo $user; ?>">
                <div class="modal-body">
                        <div class="form-group">
                            <div class='container-fluid'>
                                <label class="control-label" for="friedName">Friendly Name: <?php show_form_error('fName'); ?> </label>
                                <input type="text" class="form-control"  name="fName">
                            </div>
                        </div>

                       <div class="form-group">
                            <div class='col-sm-8 '>
                                <label class="control-label" for="uploadForm_file"> File to Upload: </label>
                                <input type="file" name="userfile">
                            </div>
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
    </div>
<script>
    $(document).ready(function(){
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
                        setTimeout(function(){ $('#upload_file_modal').modal('hide'); window.location.reload();},3000);

                    }else if(data.status === 'file_error') {
                      //  $.each(data.file_errors, function(key,val){
                        $('#msg_upload').removeClass('alert-info');
                        $('#msg_upload').addClass('alert-warning');
                        $('#msg_upload').html(data.file_errors);
                    //});
                    }else if(data.status === 'name_required') {
                      //  $.each(data.file_errors, function(key,val){
                        $('#msg_upload').removeClass('alert-info');
                        $('#msg_upload').addClass('alert-warning');
                        $('#msg_upload').html('Name field can not be empty');
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