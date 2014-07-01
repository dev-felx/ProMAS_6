
<link type="text/css" href="<?php echo base_url(); ?>assets/jquery/datetime/jquery.datetimepicker.css" rel="stylesheet" />
<script src="<?php echo base_url(); ?>assets/jquery/datetime//jquery.js"></script>
<script src="<?php echo base_url(); ?>assets/jquery/datetime/jquery.datetimepicker.js"></script>

<div>
    <h4 class="col-sm-3 pull-left"><?php echo $sub_title; ?></h4> 
    <a class="btn btn-success pull-right push_left_bit" href="<?php echo site_url('manage_users/panel_session/members/panel_member'); ?>">Manage Panel Members</a>
    <a class="btn btn-success pull-right" href="<?php echo site_url('manage_users/manage/users/panel_head'); ?>">Manage Panel Heads</a>
</div>
<div class="clearfix"></div>
<hr/>
<div class="col-sm-10 col-sm-offset-1">
    <div class="panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title text-center">sProMAS Panel Heads</h3>
        </div>
        <div class="panel-body">
            <form>
                <div class="form-group">
                    <label class="control-label">Select Panel head</label>
                    <select class="form-control" name="project" id="project">
                        <option></option>
                        <?php 
                            foreach ($panel_heads as $value) {
                                echo '<option value="'.$value['user_id'].'">';
                                echo $value['first_name'].' '.$value['last_name'] ;
                                echo '</option>';
                            }
                        ?>
                    </select>
                    
                </div>
                <div id='msg_frm'></div>
                <div class="form-group hidden detail" >
                    <label class="control-label">Projects</label>
                    <ul id="projects" class="list-group"></ul>
                    <button id='show_project_add' class="btn btn-primary btn-sm col-sm-2"><span class="glyphicon glyphicon-plus"></span>Add Project </button>
                    <div class="clearfix bottom_10"></div>
                    <div class="hidden" id='project_add_cont'>
                    <div class="col-sm-7">
                        <select id="project_add_val" class="form-control col-sm-7">
                            <?php
                                foreach ($projects as $value) {
                                   echo '<option value="'.$value['project_id'].'">'.$value['group_no'].' '.$value['title'];
                                   echo '</option>';
                                }
                                ?>
                        </select>  
                    </div>
                    <button id='project_add_now' class="btn btn-success btn-sm col-sm-1">Add</button>
                    <button id="can_project" class="btn btn-warning btn-sm col-sm-1 push_left_bit">Cancel</button>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="form-group hidden detail" >
                    <label class="control-label">Panel members</label>
                    <ul id="members" class="list-group"></ul>
                    <button id='show_member_add' class="btn btn-primary btn-sm col-sm-2"><span class=""></span>Change Panel members</button>
                    <div class="clearfix bottom_10"></div>
                    <div class="hidden" id='member_add_cont'>
                    <div class="col-sm-7">
                        <select id="member_add_val" class="form-control col-sm-7">
                            <?php
                                foreach ($all_members as $value) {
                                   echo '<option value="'.$value['panel_member_id'].'">'.$value[first_name].' '.$value['last_name'];
                                   echo '</option>';
                                }
                                ?>
                        </select>  
                    </div>
                    <button id='member_add_now' class="btn btn-success btn-sm col-sm-1">Add</button>
                    <button id="can_member" class="btn btn-warning btn-sm col-sm-1 push_left_bit">Cancel</button>
                    </div>
                </div>
                
                        <div class="clearfix"></div>
                <div class="form-group hidden detail">
                    <label>Venue and Time<span><button id="show_venue_ch" class="btn btn-link btn-sm"><span class="glyphicon glyphicon-refresh push_right_bit"></span>Change</button></span></label>
                    <p id="venue" class="form-control-static"></p>
                    <div class="hidden" id='venue_ch_t'>
                    <div class="row col-sm-12">
                        <div id="venue_ch" class="form-group col-sm-7">
                            <label for="exampleInputEmail1">Venue</label>
                            <input id="venue_chn" type='text' class="form-control" id="exampleInputEmail1" placeholder="Enter venue">
                          </div>
                         <div id="time_ch" class='col-sm-4'>
                             <label for="exampleInputEmail1">Time allocation</label>
                            <input name="time" type='text' class="form-control" id='datetimepicker'/>
                        </div>
                    </div>
                        <div class="row-fluid col-sm-4">
                    <button id='venue_ch_now' class="btn btn-success btn-sm ">Change</button>
                    <button id="can_venue" class="btn btn-warning btn-sm  push_left_bit">Cancel</button>
                    </div>
                    </div>
                </div>
                
            </form>
            <div id="notify" class="hidden">
                <div class="row-fluid hr"><hr/></div>
                <div class="row-fluid">
                    <div ><a id="notify_btn" type="button" class="btn btn-success">Send Notification</a></div>
                </div>
            </div>
    </div>
</div>
</div>
<script>
    $(document).ready(function(){
        jQuery('#datetimepicker').datetimepicker({
                format:'d.m.Y H:i',
               
        });
        //get project Info
        $('#project').change(function(){
            var id = $(this).val();
            $('.detail').addClass('hidden'); 
            $('#msg_frm').show();
            $('#msg_frm').html('<img style="height: 30px;" class="col-sm-offset-5 push_right_bit" src="<?php echo base_url(); ?>/assets/images/ajax-loader.gif">Fetching Details....');
            setTimeout(function(){
                 var t = "<?php echo site_url(); ?>";
                 var c = t+"/manage_users/panel_session/get_session_details";
                 $.post( c, {id: id}).done(function(data) {
                     if(data.status === 'true'){
                        //hide loader
                        $('#msg_frm').hide();
                        //populate students
                        $('#projects').html('');
                        $('#members').html('');
                        if(data['projects']!=null){
                        for(var i = 0; i < data['projects'].length; i++){
                            var x = data['projects'][i].group_no+" - "+data['projects'][i].project_name;
                            $('#projects').append('<li id="'+data['projects'][i].project_id+'" class="project_btn list-group-item">'+x+'<span class="remove text-danger glyphicon glyphicon-remove pull-right"><span></li>');
                         }
                         }else{
                            $('#projects').append('<div class="alert alert-warning">No projects added</div>');
                         }
                         if(data['members']!=null){
                         for(var i = 0; i < data['members'].length; i++){
                            var y = data['members'][i].first_name+" "+data['members'][i].last_name;
                            $('#members').append('<li id="'+data['members'][i].panel_member_id+'" class="member_btn list-group-item">'+y+'</li>');
                         }
                         }else{
                            $('#members').append('<li class="alert alert-warning">No panel members added</li>');
                         }
                         if(data.session_details[0]!=null){
                            $('#venue').html('<p class="">Venue : '+data.session_details[0].venue+'</p><p class="">Date & Time : '+data.session_details[0].time+'</p>');      
                         }else{
                            $('#venue').html('<p>Venue and Time not defined</p>'); 
                         }
                         $('.detail').removeClass('hidden');
                         $('#notify').removeClass('hidden');
                     }else{
                        $('#msg_frm').html('<div class="alert alert-danger">Error Fetching Data</div>');
                     }
                 },'json');
             },400);
        });
        
         $('body').on('click', '.remove', function () {
            var id = $(this).parent('li').attr('id');
            if(confirm('Remove project group from current panel head ')){
                var t = "<?php echo site_url(); ?>";
                 var c = t+"/manage_users/panel_session/remove_project";
                 $.post( c, {project_id: id}).done(function(data) {
                     if(data.status === 'true'){
                         $('#project').trigger('change');
                     }
                 },'json');
            }
         });
         
         //buttons
         $('#show_project_add').click(function(){
            $('#project_add_cont').removeClass('hidden');
            return false;
         });
         $('#show_member_add').click(function(){
            $('#member_add_cont').removeClass('hidden');
            return false;
         });
         $('#show_venue_ch').click(function(){
            $('#venue_ch_t').removeClass('hidden');
            $('#venue').hide();
            return false;
         });
         $('#can_venue').click(function(){
            $('#venue_ch_t').addClass('hidden');
            $('#venue').show();
            return false;
         });
         $('#can_project').click(function(){
           $('#project_add_cont').addClass('hidden');
            return false;
         });
         $('#can_member').click(function(){
           $('#member_add_cont').addClass('hidden');
            return false;
         });
         
         
         //other functions
         $('#project_add_now').click(function(){
             var project_id = $('#project_add_val').val();
             var panel_head_id = $('#project').val();
             if(confirm('Warning: ')){
                 var t = "<?php echo site_url(); ?>";
                 var c = t+"/manage_users/panel_session/add_project";
                 $.post( c, {project_id: project_id, panel_head_id: panel_head_id}).done(function(data) {
                     if(data.status === 'true'){
                         $('#project').trigger('change');
                     }
                 },'json');
             }else{
                $('#msg_frm').html('<div class="alert alert-danger">Error Adding Project</div>');
             }
             return false;
         });
         $('#member_add_now').click(function(){
             var member_id = $('#member_add_val').val();
             var panel_head_id = $('#project').val();
             if(confirm('Warning: This member will be removed from other panel head group')){
                 var t = "<?php echo site_url(); ?>";
                 var c = t+"/manage_users/panel_session/update_member";
                 $.post( c, {member_id: member_id, panel_head_id: panel_head_id}).done(function(data) {
                     if(data.status === 'true'){
                         $('#project').trigger('change');
                     }
                 },'json');
             }else{
                $('#msg_frm').html('<div class="alert alert-danger">Error Adding Member</div>');
             }
             return false;
         });
         
         
         $('#venue_ch_now').click(function(){
                var panel_head_id = $('#project').val();
                var venue = $('#venue_chn').val();
                var time = $('#datetimepicker').val();
                
                if(confirm('Warning: Venue and time will be changed')){
                    var t = "<?php echo site_url(); ?>";
                    var c = t+"/manage_users/panel_session/change_panel_session";
                    $.post( c, {venue: venue,time: time, panel_head_id: panel_head_id}).done(function(data) {
                        if(data.status === 'true'){
                            $('#project').trigger('change');
                            $('#can_venue').trigger('click');
                        }
                    },'json');
                }else{
                   $('#msg_frm').html('<div class="alert alert-danger">Error Changing Supervisor</div>');
                }
                return false;
         });
         
         
         $('#notify_btn').click(function(){
                var panel_head_id = $('#project').val();
                if(confirm('Warning: Notification will be sent to panel members and panel head of this session')){
                    var t = "<?php echo site_url(); ?>";
                    var c = t+"/manage_users/panel_session/notify";
                    $.post( c, { panel_head_id: panel_head_id}).done(function(data) {
                        if(data.status === 'true'){
                            $('#project').trigger('change');
                            $('#msg_frm').html('<div class="alert alert-success">Notification has been sent to Panel head and panel members</div>');
                        }else{
                            $('#project').trigger('change');
                            $('#msg_frm').html('<div class="alert alert-danger">Notification has not been sent</div>');
                        }
                    },'json');
                }else{
                   $('#msg_frm').html('<div class="alert alert-danger">Error changing panel Head</div>');
                }
                return false;
         });
    });
</script>

