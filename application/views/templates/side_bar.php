<?php
/*
 * Author:  Tesha Evance
 * Description: Dynamic side bar creator
 * Comments: Add to configuration only 
*/

/*
 *===========TEMPLATE======
 * $accordion['user'] = array(
 *                          'menu_group' => array(
 *                                              array('menu-item' = 'menu_item_link)
 *                                              .
 *                                              .
 *                                              . 
 *                                          )
 *                          .
 *                          .
 *                          .     
 *                     )
 *                     .
 *                     .
 *                     .
 * Examples:
 *      user = administrator
 *      menu_group = manage users, timeline etc
 *      menu_item =  events,announcements, etc
 *      menu_item_link = manage_user/supervisor, timline/events !NOTE-dont put the leading slash.
 * 
 *  */
//===========CONFIGURATION=============

    //for superuser
    $accordion['superuser'] = array(
        'Manage users' => array(
                                    array('Administrator' => 'manage_users/manage/users/administrator'),
                                    array('Coordinator' => 'manage_users/manage/users/coordinator'),
                                    array('Supervisor' => 'manage_users/manage/users/supervisor'),
                                    array('Student' => 'manage_users/manage/users/student'),
                                    
                               ),
        'Project' => array(
                                    array('Documents' => 'project/file'),
                                    array('Announcements' => 'project/announce'),
                                    array('Notifications' => 'project/notify'),
                               ),
        'Schedule' => array(
                                    array('Event list' => 'timeline/timeline/event'),    // Modified by Minja Junior
                                    array('Calendar' => 'home'),
                               ),
        'Assesment' => array(
                                    array('Weekly Progress' => 'assessment/assess/weekly'),
                                    array('Project Reports' => 'assessment/assess/report'),
                                    array('Average Assessment' => 'assessment/assess/average'),
                                    array('Presentation' => 'assessment/assess_panel/pres'),
                                    array('Export Assessment Data' => 'assessment/assess/export'),
                        ),
        'Archive' => array(
                                    array('Archive Home' => 'archive/access/switcher'),
                                    array('Access Manager' => 'archive/users'), 
                                    array('Archive Publisher' => 'project/publish_project'),
                                    
                     ),
        
    );



    //for administrator 
    $accordion['administrator'] = array(
        'Users' => array(
                                    array('Coordinator' => 'manage_users/manage/users/coordinator'),
                                    array('Supervisor' => 'manage_users/manage/users/supervisor'),
                                    array('Student' => 'manage_users/manage/users/student'),
                                    array('Panel Members' => 'manage_users/panel_session/members/panel_member'),
                                    array('Panel Head' => 'manage_users/manage/users/student'),
                                    array('Archive Users' => 'archive/users'),
                                    
                               ),
        'Project' => array(
                                    array('Documents' => 'project/file'),
                                    array('Announcements' => 'project/announce'),
                                    array('Notifications' => 'project/notify'),
                               ),
        'Schedule' => array(
                                    array('Event list' => 'timeline/timeline/event'),    // Modified by Minja Junior
                                    array('Calendar' => 'home'),
                               ),
        'Assesment' => array(
                                    array('Weekly Progress' => 'assessment/assess/weekly'),
                                    array('Project Reports' => 'assessment/assess/report'),
                                    array('Average Assessment' => 'assessment/assess/average'),
                                    array('Presentation' => 'assessment/assess_panel/pres'),
                                    array('Export Assessment Data' => 'assessment/assess/export'),
                        ),
        'Archive' => array(
                                    array('Archive Home' => 'archive/access/switcher'),
                                    array('Access Manager' => 'archive/users'), 
                                    array('Archive Publisher' => 'project/publish_project'),
                                    
                     )
        
    );
    
    //for coordinator
    $accordion['coordinator'] = array(
        'Manage users' => array(
                                    array('Supervisor' => 'manage_users/manage/users/supervisor'),
                                    array('Student' => 'manage_users/manage/users/student'),
                                    array('Panel Members' => 'manage_users/panel_session/members/panel_member'),
                                    array('Panel Head' => 'manage_users/manage/users/panel_head'),
                                    array('Archive Users' => 'archive/users'),
                                    
                               ),
        'Project' => array(
                                    array('Title' => 'project/request_title'),
                                    array('Manage projects' => 'manage_users/group'),
                                    array('Documents' => 'project/file'),
                                    array('Announcements' => 'project/announce'),
                                    array('Notifications' => 'project/notify'),
                               ),
        'Schedule' => array(
                                    array('Event list' => 'timeline/timeline/event'),    // Modified by Minja Junior
                                    array('Calendar' => 'home'),
                               ),
        'Assesment' => array(
                                    array('Weekly Progress' => 'assessment/assess/weekly'),
                                    array('Project Reports' => 'assessment/assess/report'),
                                    array('Average Assessment' => 'assessment/assess/average'),
                                    array('Presentation' => 'assessment/assess_panel/pres'),
                                    array('Export Assessment Data' => 'assessment/assess/export'),
                        ),
        'Archive' => array(
                                    array('Archive Home' => 'archive/access/switcher'),
                                    array('Access Manager' => 'archive/users'), 
                                    
                                    
                     ),
        
        
    );
    
     //for supervisor
    $accordion['supervisor'] = array(
        'Project' => array(
                                    array('Title' => 'project/request_title'),
                                    array('Documents' => 'project/file'),
                                    array('Announcements' => 'project/announce'),
                                    array('Notifications' => 'project/notify'),
                               ),
        
        'Schedule' => array(
                                    array('Event list' => 'timeline/timeline/event'),    // Modified by Minja Junior
                                    array('Calendar' => 'home'),
                               ),     
        'Assesment' => array(
                                    array('Weekly Progress' => 'assessment/assess/weekly'),
                                    array('Project Reports' => 'assessment/assess/report'),
                                    array('Average Assessment' => 'assessment/assess/average'),
                                    array('Export Assessment Data' => 'assessment/assess/export'),
                        ),
        'Archive' => array(
                                    array('Archive Home' => 'home'),    // Modified by Minja Junior
                                    array('Archive Publisher' => 'project/publish_project'),    // Modified by Minja Junior
                               ),
        
    );
    
    $accordion['student'] = array(
        'Project' => array(
                                    array('Title' => 'project/request_title'),
                                    array('Documents' => 'project/file'),
                                    array('Announcements' => 'project/announce'),
                                    array('Notifications' => 'project/notify'),
                               ),  
        'Schedule' => array(
                                    array('Event list' => 'timeline/timeline/event'),    // Modified by Minja Junior
                                    array('Calendar' => 'home'),
                               )
        
        
    );
    
    $accordion['panel_head'] = array(
        
       'Assesment' => array(
                                    array('Session' => 'home'),
                                    array('Presentation' => 'assessment/assess_panel/pres'),
                        )
        
    );
    
    //=======ENGINE=========
?>

<div class="col-sm-2" style="padding-left: 4px; padding-right: 5px;">
    <!-- Role Box -->
    <div id="role_box" class="bottom_10">
        <div class="col-sm-4" style="border-right:#0093D0 solid 1px; height: 50px;padding-top: 5px;">
            <button class="btn btn-circle text-primary"><span class="glyphicon glyphicon-user"></span></button>
        </div>
        <div class="col-sm-8">
            <p id="role_btn" class="text-left text-primary pull-left"><?php echo str_replace('_',' ',ucfirst($this->session->userdata['type'])); ?></p>
            <?php if($this->session->userdata['type'] != 'student' && count($this->session->userdata['roles']) != 1) { ?>
                <p id="cng_btn" class="text-left text-warning pull-left">Change Role<span class="caret"></span></p>
            <?php } else {?>
                <div class="clearfix"></div>
                <p id="cng_btn" class="text-left text-warning pull-left"> <?php echo ucfirst($this->session->userdata['fname']); ?> </p>
            <?php }?>
        </div>               
    </div>
    <div class="clearfix"></div>
    <?php if($this->session->userdata['type'] != 'student' && count($this->session->userdata['roles']) > 1) { $roles = $this->session->userdata['roles'];?>
        <ul class="list-group" id="role_list">
            <?php for($i = 1;$i <= count($roles);$i++){
                if($roles[($i - 1)] == $this->session->userdata['type']){
                        continue;
                }else{?>
            <li class="list-group-item"><a href="<?php echo site_url()."/home/change_role/".$roles[($i - 1)];?>"><span class="glyphicon push_right_bit glyphicon-user"></span><?php echo  str_replace('_',' ',ucfirst($roles[($i - 1)])); ?><span class="glyphicon glyphicon-chevron-right pull-right visible-xs"></span></a></li>
                <?php }} ?>
        </ul>
    <?php } ?>    
    <div class="clearfix"></div>
    <?php //echo current_url(); ?>
    <div class="col-sm-10 col-sm-offset-1 bottom_10"><hr/></div>
    <div class="clearfix"></div>
    <!-- Side Bar view -->
    <div class="panel-group" id="accordion">
        <?php foreach ($accordion[$this->session->userdata['type']] as $key => $value) { ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title text-center" data-toggle="collapse" data-parent="#accordion" href="#<?php echo str_replace(' ', '_', $key) ?>">
                        <a  href="#"><?php echo $key ?></a>
                    </h4>
                </div>
                <div id="<?php echo str_replace(' ', '_', $key) ?>" class="panel-collapse collapse">
                    <div class="panel-body">
                        <ul class="list-group">
                            <?php foreach ($value as $sub_value) { 
                                    foreach ($sub_value as $sub_sub_key => $sub_sub_value) {?>
                            <li class="list-group-item"><a href="<?php echo site_url().'/'.$sub_sub_value ?>"><?php echo $sub_sub_key; ?></a></li>
                            <?php   }
                                  } ?>
                        </ul>
                    </div>
                </div>
            </div>
      <?php } ?>
      </div>
      </div>

<?php if(($this->session->userdata['type']=='coordinator') && !isset($this->session->userdata['space_id'])){ ?>
<script>
    $('.panel-title').click(function () {
        return false;
    });
</script>
<?php } ?>
<script>
    $('#role_list').hide();
    $('#cng_btn').on('click', function () {
            $('#role_list').slideToggle(500);
            return false;
    });
</script>
