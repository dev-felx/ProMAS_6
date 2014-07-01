<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* 
 * Author: Minja Junior
 * Desc: This file view events list.
 */
?>
<div style="margin-bottom: -5px; ">
    <div>
        <div><h4>Event's list</h4></div>
        <div class="hr">
            <hr style="margin-bottom: 15px;"/>
        </div>
        <div class="clearfix"></div>
        <div class="col-md-9">
        <?php if($test!=NULL){ ?>
        <table class="table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Event Title</th>
                    <th>Description</th>
                    <th>Start Date</th>
                    <th>Due Date</th>
                </tr>
            </thead>
            <tbody>
            <?php
            foreach ($test as $row) {?>
                <tr>
                    <td><?php echo $row->title; ?></td>
                    <td><?php echo $row->desc; ?></td>
                    <td><?php echo $row->start; ?></td>
                    <td><?php echo $row->end; ?></td>
                </tr>
                <?php }
            ?>
            </tbody>
        </table>
        <?php }else{ ?>
        <div class="alert alert-warning text-center">No Event Added</div>
            <?php } ?>
    </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
        <div id="add_new" class="sider"><?php $this->load->view('timeline/add_event_view'); ?></div>
    </div>
</div>