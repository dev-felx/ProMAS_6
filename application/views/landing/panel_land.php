<div>
    <h4 class="col-sm-4 pull-left"><?php echo $sub_title; ?></h4> 
    <?php if($this->session->userdata('user_id') != null){ ?>
    <a href="<?php echo site_url('assessment/assess_panel/pres'); ?>" role="button" class="btn btn-success pull-right push_left_bit">Presentation Assessment</a>
    <?php } ?>
    </div>
<div class="clearfix"></div>
<hr/>
<div class="panel panel-primary">
  <div class="panel-heading">
    <h3 class="panel-title text-center">Session Details</h3>
  </div>
  <div class="panel-body">
       <?php if(isset($sess_head)){ ?>
      <form  class="form-horizontal" role="form">
          <div class="form-group">
              <label class="col-sm-3 control-label text-right">Panel Head</label>
                <div class="col-sm-9">
                 
                  <p class="form-control-static text-left"><?php echo $sess_head[0]['first_name'].' '.$sess_head[0]['last_name'].str_repeat('&nbsp;', 20).'Email: '.$sess_head[0]['username']; ?></p>
                </div>
          </div>
          <div class="form-group">
                <label class="col-sm-3 control-label text-right">Coordinator</label>
                <div class="col-sm-9">
                  <p class="form-control-static text-left">Hakit jahidk</p>
                </div>
          </div>
          <div class="form-group">
              <label class="col-sm-3 control-label text-right">Venue</label>
              <div class="col-sm-9">
                  <p class="form-control-static text-left"><?php echo $sess_det[0]['venue']; ?></p>
              </div>
          </div>
          <div class="form-group">
              <label class="col-sm-3 control-label text-right">Time</label>
              <div class="col-sm-9">
                  <p class="form-control-static text-left"><?php echo $sess_det[0]['time']; ?></p>
              </div>
          </div>
          <div class="form-group">
              <label class="col-sm-3 control-label text-right">Panel Members</label>
              <div class="col-sm-8">
                <ul class="list-group">
                    <?php 
                        foreach ($sess_mem as $value) {
                            echo '<li class="list-group-item">';
                            echo $value['first_name'].' '.$value['last_name'].str_repeat('&nbsp;', 20).'Email: '.$value['email'];
                            echo '</li>';
                        }
                     ?>               
                </ul>
              </div>
          </div>
          <div class="form-group">
              <label class="col-sm-3 control-label text-right">Groups Assigned</label>
              <div class="col-sm-8">
                <ul class="list-group">
                    <?php 
                        foreach ($sess_grp as $value) {
                            echo '<li class="list-group-item">';
                            echo 'Group '.$value['group_no'].' '.$value['project_name'];
                            echo '</li>';
                        }
                     ?>    
                </ul>
              </div>
          </div>
      </form>
      <?php }else{ ?>
      <p class="text-warning">You have no session set yet</p>
      <?php }?>
  </div>
</div>