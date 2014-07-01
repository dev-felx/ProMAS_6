<style>
    .popover{
        z-index: 1001 !important;
        width: 400px;
    }
    
    .dummy{
        color: #cccccc;
    }
    .dummy:hover{
        color: #cccccc;
        text-decoration: none;
    } 
    
    .pad_10{
        padding: 10px !important;
    }
    
    .up_event{
        border: #cccccc solid 1px;
        border-radius: 4px;
        padding: 5px 5px 5px 5px;
        margin-top: -10px;
    }
    .up_event_item{
        border-bottom: #cccccc solid 1px;
    }
    .up_event_item:last-child{
        border-bottom: 0px;
    }
</style>
<!-- Calender wrap header -->
<div>
    <h4 class="col-sm-3 pull-left">Project Schedule</h4> 
    <button type="button" class="btn btn-success pull-right push_left_bit glyph_big" data-toggle="tooltip" data-placement="bottom" title="Coordinator Event: Green, 
Supervisor Event: Blue, 
Student Event: Orange. 
Click on event to edit or delete. ">Help <span class="glyphicon glyphicon-question-sign"></span></button>
    <a href="<?php echo site_url(); ?>/timeline/timeline/event" class="btn btn-success pull-right push_left_bit" role="button">View Event List</a>
    <button id='new_btn' class="btn btn-success pull-right ">New Event - Activity</button>&nbsp;
    
    <!-- modified by Minja Junior -->
    <?php if($this->session->userdata('type') == 'supervisor'){?>
    <div class="btn-group pull-right push_right_bit">
        <button type="button" class="btn btn-success">View by Group</button>
        <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
            <span class="caret"></span>
            <span class="sr-only">Toggle Dropdown</span>
        </button>
        <ul class="dropdown-menu" id="myTab">
            <?php foreach ($groups as $value) { ?>
            <li><a href="?pid=<?php echo $value['project_id']; ?>"><?php echo $value['title']; ?></a></li>
            <?php  } ?>
        </ul>
    </div>
    <?php } ?>
</div>
<!-- end of Minja Junior Modification -->

<div class="clearfix"></div>
<hr style="border: none; height: 1px; background:#0093D0;">

<!-- Calender wrap side bar -->
<div id="calender_left" class="col-sm-2 no_pad no_mag" style="margin-top: 44px;">
    <div id="flash_info" class="sider">
        <div class="alert alert-success text-center pad_10" style="margin-bottom: 8px;">Upcoming events</div>
            <div>
                <?php
                if($event != null){
                foreach ($event as $row) {?>
                <div class="up_event_item panel panel-info" style="margin-bottom: 8px;">
                    <div class="panel-heading" style="padding: 5px;">
                        <h5 class="panel-title"><?php echo date("d M", strtotime($row->start)); ?> - <?php echo date("d M", strtotime($row->end)); ?></h5>
                    </div>
                    <div class="panel-body text-center" style="padding: 5px;"><?php echo $row->title; ?></div>
                </div>
                <?php }}else{ ?>
                <p class="text-warning text-center">No Upcoming Events</p>   
                 <?php } ?>
            </div>
    </div>
    <div id="add_new" class="sider hidden">
        <?php $this->load->view('timeline/add_event_view'); ?>
    </div>
    <div id="edit_event" class="sider hidden">
        <?php $this->load->view('timeline/edit_event_view'); ?>
    </div>
</div>

<!-- Calender Itself -->
<div id="calender_cont" class="col-sm-10 bottom_10">
    <?php
        $this->load->view('timeline/calender');
    ?>
</div>
<div id="popover_content_wrapper" class="hidden">
    <div class="clearfix"></div>
    <a id="edit_btn" href="#" class="pull-left hidden">Edit</a>
    <a id="del_event" href="#" class="pull-right hidden">Delete</a>
    
    <a class="pull-left disabled dummy ">Edit</a>
    <a class="pull-right disabled dummy">Delete</a>
    <br/>
</div>
<script>
    var curr_event;
    //wrapper js
    function pop_up(desc,creator_id){
        var user_id = <?php echo $this->session->userdata('user_id'); ?>;
        if(user_id == creator_id){
            $('#edit_btn, #del_event').removeClass('hidden');
            $('.dummy').hide();
        }
        return $("<div class='text-center'>"+desc+"</div>").html() + $('#popover_content_wrapper').html();
    }
    
    //Header button click functions
    $(document).ready(function() {
        
        $('.link').click(function(){
          $('[name="value"]','#demo').attr('value',$(this).data('value'));  
        });
        
        
        $("#new_btn").click(function(){
            $('.sider').hide();
            $('#calender_left').switchClass('col-sm-2','col-sm-3',700,show_new);
            $('#calender_cont').switchClass('col-sm-10','col-sm-9',10,render_calender);
            function render_calender(){ 
                $('#calendar').fullCalendar('render');
            }
            function show_new(){
                $('#add_new').fadeIn(1000).removeClass('hidden');
            }
            
        });
        
        $(".show_def").click(function(){
           $('.sider').hide();
            $('#calender_cont').switchClass('col-sm-9','col-sm-10',10,render_calender);
            $('#calender_left').switchClass('col-sm-3','col-sm-2',10,show_new);
            function render_calender(){ 
                $('#calendar').fullCalendar('render');
            }
            function show_new(){
                $('#flash_info').fadeIn(700);
            }
        });
        
        $('body').on('click', '#edit_btn', function () {
            $('.sider').hide();
            $('#calender_left').switchClass('col-sm-2','col-sm-3',700,show_edit);
            $('#calender_cont').switchClass('col-sm-10','col-sm-9',10,render_calender);
            function render_calender(){ 
                $('#calendar').fullCalendar('render');
            }
            function show_edit(){
                $.post( "<?php echo site_url() ?>/timeline/timeline/get_for_edit" ,{ id: curr_event.id}, function( data ) {
                     $('#etitle').attr('value',data.title);
                     $('#edate_end').attr('value',data.end);
                     $('#edate_start').attr('value',data.start);
                     $('#eid').attr('value',data.id);
                     $('#edescription').html(data.desc);
                },"json");
                $('#edit_event').fadeIn(1000).removeClass('hidden');
                
            }   
             $(this).parent().parent().hide();
             return false;
        });
        
        $('body').on('click', '#del_event', function () {
            //alert($(this).attr('id'));
            var x = confirm('Are you sure you want to delete event?');
            if(x){
                $.post( "<?php echo site_url() ?>/timeline/timeline/del_event" ,{ id: curr_event.id}, function( data ) {
                    if(data.status === 'success'){
                        $('#calendar').fullCalendar( 'removeEvents' , curr_event.id );
                        
                    }
                    
                });
            }
            $(this).parent().parent().hide();
            return false;
        });
        $( window ).resize(function() {
             $('.fc-event').popover('hide');
        });

        /*$('link').click(function(){//element to be click to load the page in the div
            $(your_div_element).load('controller/method');
        });*/ 
    });
</script>