<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*Author : Devid Felix
 * 
 * 
 */
?>
<link type="text/css" rel="Stylesheet" href="<?php echo base_url(); ?>assets/jquery/datatable/jquery.bootstrap.datatable.css">
<script src="<?php echo base_url(); ?>assets/jquery/datatable/jquery.dataTables.js"></script>
<script src="<?php echo base_url(); ?>assets/jquery/datatable/jquery.bootstrap.datatable.js"></script>

<div id="" class="col-sm-12">            
    <div class='row' style="margin-bottom: -5px; ">
        <div class='pull-left'><h4>Manage Users - <?php echo ucfirst(str_replace('_',' ', $user));   ?></h4></div>
            
            <?php if(($user == 'student') || ($user =='supervisor') ){ ?>
            <div class="pull-right btn-group">
            <button type="button" class="btn btn-success dropdown-toggle push_right_bit" data-toggle="dropdown" >Add <?php echo ucfirst(str_replace('_',' ', $user));   ?></button>
            <ul class="dropdown-menu" role="menu">
                <li><a href="<?php echo site_url(); ?>/manage_users/add_user/individual/<?php echo $user; ?>">Single</a></li>
                <li class="divider"></li>
                <li><a href="<?php echo site_url(); ?>/manage_users/add_group/group/<?php echo $user; ?>">Multiple</a></li>
            </ul>
            <a onclick="return confirm('Are you sure you want to delete all <?php echo $user; ?>s?')" href="<?php echo site_url(); ?>/manage_users/manage/delete_all/<?php echo $user; ?>" type="button" class="btn btn-danger push_right_bit " >Delete All <?php echo str_replace('_',' ', $user).'s'; ?></a>
            </div>
            <?php }else{
                echo '<div class=" pull-right">';
                if(($user == 'panel_head')){
                ?>
        
            <a type="button" class="btn btn-primary push_right_bit" href="<?php echo site_url(); ?>/manage_users/panel_session"  >Manage Panels</a>
              <?php } ?>
            <a type="button" class="btn btn-success push_right_bit" href="<?php echo site_url(); ?>/manage_users/add_user/individual/<?php echo $user; ?>"  >Add <?php echo ucfirst(str_replace('_',' ', $user));   ?></a>
            <a onclick="return confirm('Are you sure you want to delete all <?php echo $user; ?>s?')" href="<?php echo site_url(); ?>/manage_users/manage/delete_all/<?php echo $user; ?>" type="button" class="btn btn-danger push_right_bit " >Delete All <?php echo str_replace('_',' ', $user).'s'; ?></a>
            </div>
                <?php } ?>
            
        
    </div>

    <div class="row">
        <div class="hr" ><hr/></div>
    </div>
    
        <?php if(isset($message)&& $message!=NULL){ ?>
        <div class="row-fluid col-sm-12">
            <h4 class="text-center alert alert-info "><strong><?php echo $message; ?></strong></h4>
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
                   if($user=='student'){
                       $id= 'student_id';
                   }
                   else{
                       $id = 'user_id';
                   }
                   //settting user id
                   $user_id = $row[$id];

                   $row = array_slice($row, 1, 4);
                   echo '<tr>';  
                   foreach ($row as $value) {
                      echo '<td>'.$value.'</td>';
                   }
                   echo '<td>';
                   ?>
                      <a type="button" href="<?php echo site_url(); ?>/manage_users/manage/view/<?php echo $user_id; ?>/<?php echo $user; ?>" class="action_view btn_edge btn btn-primary btn-xs"><span class="glyphicon glyphicon-zoom-in push_right_bit"></span>View</a>
                        <a type="button" href="<?php echo site_url(); ?>/manage_users/manage/edit/<?php echo $user_id; ?>/<?php echo $user; ?>" class="action_edit btn_edge badge_link btn btn-success btn-xs"><span class="glyphicon glyphicon-pencil push_right_bit"></span>Edit</a>
                        <a type="button" onclick="return confirm('Are you sure you want to delete <?php echo ucfirst($row['first_name']) .' '.ucfirst($row['last_name']); ?>?')" href="<?php echo site_url(); ?>/manage_users/manage/delete/<?php echo $user_id; ?>/<?php echo $user; ?>" class="action_del btn_edge badge_link btn btn-danger btn-xs">
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
