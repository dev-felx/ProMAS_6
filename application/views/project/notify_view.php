<div>
    <h4 class="col-sm-3 pull-left">Your Notifications</h4> 
    <button class="btn btn-success pull-right push_left_bit glyph_big"><span class="glyphicon glyphicon-cog"></span></button>
    <form class="col-sm-3 pull-right">
        <div class="input-group">
            <input type="text" class="form-control" placeholder="Search Notifications">
            <span class="input-group-addon"><span class="glyphicon glyphicon-search text-success"></span></span>
        </div>
    </form>
</div>
<div class="clearfix"></div>
<hr/>
<div id='annouces' class="">
<h4 class="col-sm-offset-1">Unread</h4>
<div class="clearfix"></div>
    <?php 
    if($nots_unread == NULL ){
        echo '<div class="col-sm-offset-1 alert alert-info text-center col-sm-6">You have no new notifications</div>';
    }else{ ?>
    <div id="unread">
    <?php 
    foreach ($nots_unread as $value) {?>
        <div class="col-sm-8 col-sm-offset-1 not_box_unread">
                <div><span class="glyphicon glyphicon-calendar push_right_bit"></span><?php echo $value['date_created']; ?></div>
                <div class="no_mag no_pad"><hr/></div>
                <p><span class="push_right_bit glyphicon glyphicon-<?php echo $value['glph'];  ?>"></span><?php echo $value['description']  ?></p>
        </div>
    <?php }} ?>
    </div>
    <div class="clearfix"></div>
    <h4 class="col-sm-offset-1">Read</h4>
    <dic class="clearfix"></dic>
        
        <div id="read">
        <?php if($nots_read == NULL ){
            echo '<div id="read_al"class="col-sm-offset-1 alert alert-info text-center col-sm-6">No notification</div>';
        }else{ ?>
        <?php
        foreach ($nots_read as $value) {?>
         <div class="col-sm-8 col-sm-offset-1 not_box">
            <div><span class="glyphicon glyphicon-calendar push_right_bit"></span><?php echo $value['date_created']; ?></div>
            <div class="no_mag no_pad"><hr/></div>
            <p><span class="push_right_bit glyphicon glyphicon-<?php echo $value['glph'];  ?>"></span><?php echo $value['description'];  ?></p>
        </div>
   
        <?php }} ?>
        </div>
</div>