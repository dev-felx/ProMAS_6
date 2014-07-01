<link type="text/css" rel="Stylesheet" href="<?php echo base_url(); ?>assets/jquery/datatable/jquery.bootstrap.datatable.css">
<script src="<?php echo base_url(); ?>assets/jquery/datatable/jquery.dataTables.js"></script>
<script src="<?php echo base_url(); ?>assets/jquery/datatable/jquery.bootstrap.datatable.js"></script>

<div id="manage_users" class="col-sm-12">            
    <div class='row' style="margin-bottom: -5px; ">
        <div class='pull-left'><h4>Manage Users - <?php echo ucfirst(str_replace('_',' ', $user));   ?></h4></div>
            <div class=" pull-right">
            <?php if(($user == 'student') || ($user =='supervisor') ){ ?>
            <button type="button" class="btn btn-success dropdown-toggle push_right_bit" data-toggle="dropdown" >Add <?php echo ucfirst(str_replace('_',' ', $user));   ?></button>
            <ul class="dropdown-menu" role="menu">
                <li><a href="<?php echo site_url(); ?>/manage_users/add_user/individual/<?php echo $user; ?>">Single</a></li>
                <li class="divider"></li>
                <li><a href="<?php echo site_url(); ?>/manage_users/add_group/group/<?php echo $user; ?>">Multiple</a></li>
            </ul>
            <?php }else{  
            ?>
            <a type="button" class="btn btn-primary push_right_bit" href="<?php echo site_url(); ?>/manage_users/panel_session"  >Manage Panels</a>
            <a type="button" class="btn btn-success push_right_bit" href="<?php echo site_url(); ?>/manage_users/panel_session/add_member"  >Add <?php echo ucfirst(str_replace('_',' ', $user));   ?></a>
            <?php } ?>
            <a onclick="return confirm('Are you sure you want to delete all panel member')" href="<?php echo site_url(); ?>/manage_users/panel_session/delete_all_member" type="button" class="btn btn-danger push_right_bit " >Delete All <?php echo str_replace('_',' ', $user).'s'; ?></a>
            </div>
        
    </div>

    <div class="row">
        <div class="hr" ><hr/></div>
    </div>
    
        <?php if(isset($message)&& $message!=NULL){ ?>
        <div class="row-fluid col-sm-12">
            <p class="text-center alert alert-success "><strong><?php echo $message; ?></strong></p>
        </div>
        <?php } ?>
    
<!--    <h4 class="pull-">Current <?php //echo ucfirst($user); ?>s</h4>-->
    <div class="row">
        <div id='user_list' class="table-responsive col-sm-12">
            <table id="table_id" class=" table table-bordered table-striped dataTable">
            <!-- table heading -->
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
            <!-- table body -->
            <tbody>
            <?php 
               foreach ($table_data as $row) {
                   //settting user id
                   $user_id = $row['panel_member_id'];

                   $row = array_slice($row, 1, 4);
                   echo '<tr>';  
                   foreach ($row as $value) {
                      echo '<td>'.$value.'</td>';
                   }
                   echo '<td>';
                   ?>
                     <a type="button" onclick="return confirm('Are you sure you want to delete <?php echo ucfirst($row['first_name']) .' '.ucfirst($row['last_name']); ?>?')" href="<?php echo site_url(); ?>/manage_users/panel_session/delete_member/<?php echo $user_id; ?>" class="action_del btn_edge badge_link btn btn-danger btn-xs">
                            <span class="glyphicon glyphicon-trash push_right_bit"></span>Delete</a>
                  <?php echo '</td>';
                   echo '</tr>';  
                } 
            ?>
            </tbody>
            </table>
    </div>
    </div>
    
</div>
  <script>
    
    $(document).ready(function(){
        
        $('#table_id').dataTable({
            });
    });
    
    </script>
