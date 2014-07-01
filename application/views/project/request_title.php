<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="container-fluid">
    <div class="row">
        <div class='pull-left'><h4>Request Project Title</h4></div>
    </div>
    <div class="row" style="margin-bottom: 15px;">
        <div class="hr"><hr/></div>
    </div>
    <div class="col-sm-8 col-sm-offset-2">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title text-center">Fill In your Project Details</h3>
            </div>
            <div class="panel-body">
                
                    <?php if($this->session->userdata('type') == 'coordinator' || $this->session->userdata('type') == 'supervisor'){?>
                    <div class="form-group">
                        <label class="control-label">Select a project group</label>
                        <select class="form-control" name="project" id="project">
                            <option></option>
                            <?php 
                                foreach ($projects as $value) {
                                    echo '<option value="'.$value['project_id'].'">Group ';
                                    echo $value['group_no'].' - '.$value['title'];
                                    echo '</option>';
                                }
                            ?>
                        </select>
                    </div>
                    <div class="form-group hidden detail">
                        <label>Project Title</label>
                        <p id="title" class="form-control-static"></p>
                    </div>
                    <div class="form-group hidden detail">
                        <label>Project Description</label>
                        <p id="desc" class="form-control-static"></p>
                    </div>
                    <?php }elseif ($this->session->userdata('type') == 'student') {?>
                        <form role="form" action="<?php echo site_url(); ?>/project/request_title/update" method="POST">
                            
                            <div class="form-group detail">
                                <label>Project Title</label>
                                <input type="text" name="title" class="form-control" value="<?php echo $pro_t['title']; ?>" placeholder="<?php echo $pro_t['title']; ?>">
                            </div>
                            <div class="form-group detail">
                                <label>Project Description</label>
                                <textarea name="description" class="form-control" rows="4" placeholder="Enter project Title..."><?php echo $pro_t['description']; ?></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                <?php   } ?>
                
            </div>
        </div>
    </div>  
</div>
<script>
  $('#project').change(function(){
            var id = $(this).val();
            $('.detail').addClass('hidden'); 
            $('#msg_frm').show();
            $('#msg_frm').html('<img style="height: 30px;" class="col-sm-offset-5 push_right_bit" src="<?php echo base_url(); ?>/assets/images/ajax-loader.gif">Fetching Details....');
            setTimeout(function(){
                 var t = "<?php echo site_url(); ?>";
                 var c = t+"/project/request_title/get_details/"+id;
                 $.post(c).done(function(data) {
                     if(data.status === 'true'){
                        // alert(data.pro.title);
                        //populate students
                        $('#title').html(data.pro.title);
                        $('#desc').html(data.pro.description);      
                        $('.detail').removeClass('hidden');
                     }else{
                        $('#msg_frm').html('<div class="alert alert-danger">Error Fetching Data</div>');
                     }
                 },'json');
             },400);
        });
        </script>