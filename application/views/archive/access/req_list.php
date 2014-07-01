<!-- Scripts -->
<link type="text/css" rel="Stylesheet" href="<?php echo base_url(); ?>assets/jquery/datatable/jquery.bootstrap.datatable.css">
<script src="<?php echo base_url(); ?>assets/jquery/datatable/jquery.dataTables.js"></script>
<script src="<?php echo base_url(); ?>assets/jquery/datatable/jquery.bootstrap.datatable.js"></script>
<div>
    <h4 class="col-sm-3 pull-left">Access Requests for Projects</h4> 
    <a href="<?php echo site_url('/archive/users/') ?>" role="button" class="btn btn-success pull-right">User List</a> 
</div>;
<div class="clearfix"></div>
<hr/>
<div id="user_list_table_wrap" class="col-sm-12">
    <table id="user_req_table" class="table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Username</th>
                <th>Type</th>
                <th>Project Request</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php 
                foreach ($req as $value) {
                    echo  '<tr id='.$value['user_id'].'>';
                    echo  '<td>'.$value['first_name'].'</td>';
                    echo  '<td>'.$value['last_name'].'</td>';
                    echo  '<td id="user">'.$value['username'].'</td>';
                    if($this->session->userdata('user_id') != ''){
                        echo '<td>'.$this->session->userdata('type').'</td>';
                    }else{
                        echo  '<td>'.$value['type'].'</td>';
                    }    
                    echo  '<td>'.$value['project_name'].'</td>';
             ?>
                        <td>
                            <a href="<?php echo site_url('archive/users/rej/'.$value['rel_id'].'/'.$value['project_name'].'/'.$value['user_id']); ?>" type="button" class="btn btn-warning btn-sm push_left_bit">Reject</a>
                            <a href="<?php echo site_url('archive/users/grant/'.$value['user_id'].'/'.$value['rel_id'].'/'.$value['project_name']); ?>" type="button" class="btn btn-success btn-sm push_left_bit">Grant</a>
                        </td>
                   <?php
                    echo  '</tr>';
                }
            ?>
            </tbody>
    </table>
</div>
<script>
     $(document).ready(function(){
    //data tables
            $('#user_req_table').dataTable();
        });
</script>
