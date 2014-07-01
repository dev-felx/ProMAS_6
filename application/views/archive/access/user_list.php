<!-- Scripts -->
<link type="text/css" rel="Stylesheet" href="<?php echo base_url(); ?>assets/jquery/datatable/jquery.bootstrap.datatable.css">
<script src="<?php echo base_url(); ?>assets/jquery/datatable/jquery.dataTables.js"></script>
<script src="<?php echo base_url(); ?>assets/jquery/datatable/jquery.bootstrap.datatable.js"></script>
<div>
    <h4 class="col-sm-3 pull-left">Archive User Manager</h4> 
    <div class="btn-group pull-right push_left_bit">
        <button type="button" class="btn btn-success dropdown-toggle " data-toggle="dropdown">Add New User<span class="caret push_left_bit"></span></button>
        <ul class="dropdown-menu" role="menu">
          <li><a id="show_single" href="#">Single</a></li>
          <li class="divider"></li>
          <li><a id="show_many" >Multiple</a></li>
        </ul>
    </div>
    <a href="<?php echo site_url('/archive/users/show_req'); ?>" role="button" class="btn btn-success pull-right">Access Requests<span class="badge push_left_bit"><?php echo $req_count; ?></span></a>
    
</div>
<div class="clearfix"></div>
<hr/>

<!-- Forms for adding users -->
<div class="col-sm-6 col-sm-offset-3 adds" id="add_single">
    <form id="add_single_form"  role="form" action="<?php echo site_url('archive/access/user/new'); ?>" method="POST">
        <div id="msg" class="alert alert-info text-center">Add New User</div>
        <div class="form-group">
            <label for="fname">First Name</label>
            <input type="text" name="fname" class="form-control">
        </div>
        <div class="form-group">
            <label for="lname">Last Name</label>
            <input type="text" name="lname" class="form-control">
        </div>
        <div class="form-group">
            <label for="email">Email address</label>
            <input type="text" name="email" class="form-control">
        </div>
        <div class="form-group">
            <label for="type">Access Level</label><br/>
            <label class="radio-inline">
                <input name="level" type="radio"  value="1" checked="checked">Low
            </label>
            <label class="radio-inline">
                <input name="level" type="radio"  value="2">Medium
            </label>       
            <label class="radio-inline">
                <input name="level" type="radio"  value="3">High
            </label>       
        </div>
        <div class="form-group">
            <label for="type">User Type</label><br/>
            <label class="radio-inline">
                <input id="stu_radio" name="type" type="radio"  value="student" checked="checked">Student
            </label>
            <label class="radio-inline">
                <input id="non_radio" name="type" type="radio"  value="non_student">Non Student
            </label>       
        </div>
        <div class="form-group" id="reg">
            <label for="reg">Registration Number</label>
            <input type="text"  name="reg" class="form-control">
        </div>
        <div class="form-group">
            <button id="submit_btn" type="button" class="btn btn-success pull-right">Add and Send Email</button>
            <button id="cancel" class="btn btn-warning pull-right push_right_bit">Cancel</button>
        </div>
    </form>
</div>
<div id="add_many" class="adds">
    <?php $this->load->view('/archive/access/add_multiple'); ?>
</div>
<div class="clearfix"></div>
<div id="user_list_table_wrap" class="col-sm-12">
    <table id="user_list_table" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
        <tr>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Username</th>
            <th>Type</th>
            <th>Account Status</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php 
            foreach ($users as $value) {
                echo  '<tr id='.$value['user_id'].'>';
                echo  '<td>'.$value['first_name'].'</td>';
                echo  '<td>'.$value['last_name'].'</td>';
                echo  '<td id="user">'.$value['username'].'</td>';
                echo  '<td>'.$value['type'].'</td>';
                if($value['acc_status'] == '1'){ ?>
                    <td class="text-success">Enabled</td>
                    <td>
                        <a href="<?php echo site_url('archive/users/dis/'.$value['user_id']); ?>" type="button" class="btn btn-warning btn-sm push_left_bit">Disable</a>
                        <a onclick="return confirm('Are you sure you want to delete <?php echo $value['username']; ?>?')" href="<?php echo site_url('archive/users/del/'.$value['user_id']); ?>" type="button" class="btn btn-danger btn-sm push_left_bit">Delete</a>
                    </td>
                <?php }else{ ?>
                    <td class="text-danger">Disabled</td>
                    <td>
                        <a href="<?php echo site_url('archive/users/en/'.$value['user_id']); ?>" type="button" class="btn btn-primary btn-sm push_left_bit">Enable</a>
                        <a onclick="return confirm('Are you sure you want to delete <?php echo $value['username']; ?>?')" href="<?php echo site_url('archive/users/del/'.$value['user_id']); ?>" type="button" class="btn btn-danger btn-sm push_left_bit">Delete</a>
                    </td>
               <?php }
                
                echo  '</tr>';
            }
        ?>
        </tbody>
    </table>
</div>
<script>
    $('#add_single').hide();
    $('#add_many').hide();
    $(document).ready(function(){
        $('#show_single').click(function(){
            $('.adds').hide();
            $('#add_single').slideDown();
            $('#user_list_table_wrap').hide();
            return false;
        });
        $('#cancel').click(function(){
            $('#add_single').hide();
            $('#user_list_table_wrap').show();
            return false;
        });
        $('#stu_radio').change(function(){
            $('#reg').show();
        });
        $('#non_radio').change(function(){
            $('#reg').hide();
        });
        $('#submit_btn').click(function(){
            $('#msg').html('<img style="height: 30px;" class="col-sm-offset-1 push_right_bit" src="<?php echo base_url(); ?>/assets/images/ajax-loader.gif">Saving...');
                 setTimeout(function(){
                     var t = "<?php echo site_url(); ?>";
                     var c = t+"/archive/users/add_single";
                     $.post( c, $("#add_single_form").serialize()).done(function(data) {
                          if(data.status == 'false'){
                              $('#msg').removeClass('alert-info');
                              $('#msg').removeClass('alert-success');
                              $('#msg').addClass('alert-danger');
                              $('#msg').html(data.data);
                          }else if(data.status == 'true'){
                              $('#msg').removeClass('alert-info');
                              $('#msg').removeClass('alert-danger');
                              $('#msg').addClass('alert-success');
                              $('#msg').html('User has being added and email sent');                          }
                    },'json');
                 },400);
         });
         
     //data tables
            $('#user_list_table').dataTable();
            });
            
     //========================================
     $('#show_many').click(function(){
            $('.adds').hide();
            $('#add_many').slideDown();
            $('#user_list_table_wrap').hide();
            return false;
        });
        $('#cancel_many').click(function(){
            $('#add_many').hide
            $('#user_list_table_wrap').show();
            return false;
        });
        
     //=========================================
     
        
</script>