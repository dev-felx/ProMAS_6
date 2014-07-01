<div>
    <h4 class="col-sm-3 pull-left"><?php echo $sub_title; ?></h4> 
    <a class="btn btn-success pull-right push_left_bit" href="<?php echo site_url('manage_users/manage/users/student'); ?>">Manage Students</a>
    <a class="btn btn-success pull-right push_left_bit" href="<?php echo site_url('manage_users/manage/users/supervisor'); ?>">Manage Supervisors</a>
    <a class="btn btn-success pull-right" href="<?php echo site_url('manage_users/manage/users/panel_head'); ?>">Manage Panel Heads</a>
    <a class="btn btn-success pull-right" href="<?php echo site_url('manage_users/manage/users/panel_head'); ?>">Manage Panel Members</a>
</div>
<div class="clearfix"></div>
<hr/>
<div class="col-sm-10 col-sm-offset-1">
    <div class="panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title text-center">sProMAS Projects </h3>
        </div>
        <div class="panel-body">
            <form>
                <div class="form-group">
                    <label class="control-label">Select a project </label>
                    <select class="form-control" name="project" id="project">
                        <option></option>
                        <?php 
                            foreach ($projects as $value) {
                                echo '<option value="'.$value['project_id'].'">Project ';
                                echo $value['group_no'].' - '.$value['title'];
                                echo '</option>';
                            }
                        ?>
                    </select>
                    
                </div>
                <div id='msg_frm'></div>
                <div class="form-group hidden detail" >
                    <label class="control-label">Students</label>
                    <ul id="stud" class="list-group"></ul>
                    <button id='show_stu_add' class="btn btn-primary btn-sm col-sm-2"><span class="glyphicon glyphicon-plus"></span>Add Student</button>
                    <div class="clearfix bottom_10"></div>
                    <div class="hidden" id='stu_add_cont'>
                    <div class="col-sm-7">
                        <select id="stu_add_val" class="form-control col-sm-7">
                            <?php
                                foreach ($students_add as $value) {
                                   echo '<option value="'.$value['student_id'].'">'.$value[first_name].' '.$value['last_name'].' Project ';
                                   echo $value['group_no'];
                                   echo '</option>';
                                }
                            ?>
                        </select>  
                    </div>
                    <button id='stu_add_now' class="btn btn-success btn-sm col-sm-1">Add</button>
                    <button id="can_stu" class="btn btn-warning btn-sm col-sm-1 push_left_bit">Cancel</button>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="form-group hidden detail">
                    <label>Project Supervisor<span><button id="show_super_add" class="btn btn-link btn-sm"><span class="glyphicon glyphicon-refresh push_right_bit"></span>Change</button></span></label>
                    <p id="super" class="form-control-static"></p>
                    <div class="hidden" id='super_ch_cont'>
                    <div class="col-sm-7">
                        <select id="super_ch" class="form-control col-sm-7">
                            <?php
                                foreach ($supers_add as $value) {
                                   echo '<option value="'.$value['user_id'].'">'.$value[first_name].' '.$value['last_name'];
                                   echo '</option>';
                                }
                            ?>
                        </select>  
                    </div>
                    <button id='super_ch_now' class="btn btn-success btn-sm col-sm-1">Change</button>
                    <button id="can_super" class="btn btn-warning btn-sm col-sm-1 push_left_bit">Cancel</button>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="form-group hidden detail">
                    <label>Project Assessment Panel Head<span><button id="show_panel_add" class="btn btn-link btn-sm"><span class="glyphicon glyphicon-refresh push_right_bit"></span>Change</button></span></label>
                    <p id='panel' class="form-control-static"></p>
                    <div class="hidden" id='panel_ch_cont'>
                    <div class="col-sm-7">
                        <select id="panel_ch" class="form-control col-sm-7">
                            <?php
                                foreach ($panels_add as $value) {
                                   echo '<option value="'.$value['user_id'].'">'.$value[first_name].' '.$value['last_name'];
                                   echo '</option>';
                                }
                            ?>
                        </select>  
                    </div>
                    <button id='panel_ch_now' class="btn btn-success btn-sm col-sm-1">Change</button>
                    <button id="can_panel" class="btn btn-warning btn-sm col-sm-1 push_left_bit">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        //get project Info
        $('#project').change(function(){
            var id = $(this).val();
            $('.detail').addClass('hidden'); 
            $('#msg_frm').show();
            $('#msg_frm').html('<img style="height: 30px;" class="col-sm-offset-5 push_right_bit" src="<?php echo base_url(); ?>/assets/images/ajax-loader.gif">Fetching Details....');
            setTimeout(function(){
                 var t = "<?php echo site_url(); ?>";
                 var c = t+"/manage_users/group/get_grp_details";
                 $.post( c, {id: id}).done(function(data) {
                     if(data.status === 'true'){
                        //hide loader
                        $('#msg_frm').hide();
                        //populate students
                        $('#stud').html('');
                        for(var i = 0; i < data['students'].length; i++){
                            var x = data['students'][i].first_name+" "+data['students'][i].last_name +" - "+ data['students'][i].registration_no;
                            $('#stud').append('<li id="'+data['students'][i].student_id+'" class="stu_btn list-group-item">'+x+'<span class="remove text-danger glyphicon glyphicon-remove pull-right"><span></li>');
                         }
                         $('#panel').html(data.panel[0].first_name+' '+data.panel[0].last_name);
                         $('#super').html(data.super[0].first_name+' '+data.super[0].last_name);      
                         $('.detail').removeClass('hidden');
                     }else{
                        $('#msg_frm').html('<div class="alert alert-danger">Error Fetching Data</div>');
                     }
                 },'json');
             },400);
        });
        
         $('body').on('click', '.remove', function () {
            var id = $(this).parent('li').attr('id');
            if(confirm('Remove student from this project')){
                var t = "<?php echo site_url(); ?>";
                 var c = t+"/manage_users/group/remove_stu";
                 $.post( c, {id: id}).done(function(data) {
                     if(data.status === 'true'){
                         $('#project').trigger('change');
                     }
                 },'json');
            }
         });
         
         //buttons
         $('#show_stu_add').click(function(){
            $('#stu_add_cont').removeClass('hidden');
            return false;
         });
         $('#show_super_add').click(function(){
            $('#super_ch_cont').removeClass('hidden');
            $('#super').hide();
            return false;
         });
         $('#can_super').click(function(){
            $('#super_ch_cont').addClass('hidden');
            $('#super').show();
            return false;
         });
         $('#can_panel').click(function(){
            $('#panel_ch_cont').addClass('hidden');
            $('#panel').show();
            return false;
         });
         $('#show_panel_add').click(function(){
            $('#panel_ch_cont').removeClass('hidden');
            $('#panel').hide();
            return false;
         });
         $('#can_stu').click(function(){
           $('#stu_add_cont').addClass('hidden');
            return false;
         });
         
         
         //other functions
         $('#stu_add_now').click(function(){
             var id = $('#stu_add_val').val();
             var pro_id = $('#project').val();
             if(confirm('Warning: Student will be removed from current project')){
                 var t = "<?php echo site_url(); ?>";
                 var c = t+"/manage_users/group/add_stu";
                 $.post( c, {id: id, pro_id: pro_id}).done(function(data) {
                     if(data.status === 'true'){
                         $('#project').trigger('change');
                     }
                 },'json');
             }else{
                $('#msg_frm').html('<div class="alert alert-danger">Error Adding Student</div>');
             }
             return false;
         });
         
         
         $('#super_ch_now').click(function(){
                var pro_id = $('#project').val();
                var user_id = $('#super_ch').val();
                if(confirm('Warning: Supervisor will be added this project')){
                    var t = "<?php echo site_url(); ?>";
                    var c = t+"/manage_users/group/ch_super";
                    $.post( c, {id: user_id, pro_id: pro_id}).done(function(data) {
                        if(data.status === 'true'){
                            $('#project').trigger('change');
                            $('#can_super').trigger('click');
                        }
                    },'json');
                }else{
                   $('#msg_frm').html('<div class="alert alert-danger">Error Changing Supervisor</div>');
                }
                return false;
         });
         
         $('#panel_ch_now').click(function(){
                var pro_id = $('#project').val();
                var user_id = $('#panel_ch').val();
                if(confirm('Warning: This panel head will be added this project')){
                    var t = "<?php echo site_url(); ?>";
                    var c = t+"/manage_users/group/ch_panel";
                    $.post( c, {id: user_id, pro_id: pro_id}).done(function(data) {
                        if(data.status === 'true'){
                            $('#project').trigger('change');
                            $('#can_panel').trigger('click');
                        }
                    },'json');
                }else{
                   $('#msg_frm').html('<div class="alert alert-danger">Error changing panel Head</div>');
                }
                return false;
         });
    });
</script>
