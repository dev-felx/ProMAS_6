<div>
    <h4 class="col-sm-3 pull-left">Your Announcements</h4> 
    <button class="btn btn-success pull-right push_left_bit glyph_big">Help</button>
    <button id="send" class="btn btn-success pull-right">Create Announcement</button>
</div>
<div class="clearfix"></div>
<hr/>
<form id="send_annonce" class="col-sm-6 col-sm-offset-3 hidden" role="form" action="<?php site_url(); ?>/announce/send" method="POST">
        <div class="form-group">
            <div id="msg" class="alert alert-success hidden">
            </div>
            <label for="title">Title</label>
            <input type="text" class="form-control" name="title" id="title" placeholder="Enter titlte">
        </div>
        <div class="form-group">
            <label for="ann_desc">Announcement</label>
            <textarea class="form-control" name="ann_desc" id="ann_desc" rows="3"></textarea>
        </div>
        <div class="form-group">
            <label for="receiver">Send To</label>
            <select id="receiver" class="form-control" name="receiver">
                <?php
                    foreach ($receiver as $value) {
                        echo "<option>".$value."</option>";
                    }
                ?>
           </select>
        </div>
        <div id='groups' class="form-group hidden">
       <?php if($this->session->userdata['type']=='supervisor'){
                    echo '<select multiple name="groups[]" class="form-control">';
                    foreach ($groups as $value) { ?>
                    <option value="<?php echo $value['project_id']; ?>"><?php echo $value['title']; ?></option> 
                  <?php  }
                  echo '</select>';
                } ?>
          </div>
        <div class="form-group">
            <label for="ann_desc">Send Email?</label>
            <div class="clearfix"></div>
            <label class="radio-inline">
                <input type="radio" name="priority" id="priority" value="0" checked=""> No
            </label>
            <label class="radio-inline">
                <input type="radio" name="priority" id="priority" value="1"> Yes
            </label>
        </div>
        <div class="form-group" id="ann_footer">
            <button id="cnl" type="button" class="btn btn-success pull-right push_left_bit">Cancel</button>
            <button id="snd" type="button" class="btn btn-success pull-right">Send</button>
            <div class="clearfix"></div>
        </div>
    </form>
<div class="clearfix"></div>
<div id='annouces' class="">
    <h4 class="col-sm-offset-1">Unread</h4>
    <div class="clearfix"></div>
        <?php 
        if($anns_unread == NULL ){
            echo '<div class="col-sm-offset-1 alert alert-info text-center col-sm-6">You have no unread announcements</div>';
        }else{ ?>
        <div id="unread">
        <?php 
        foreach ($anns_unread as $value) {?>
            <div class="col-sm-8 col-sm-offset-1 ann_box_unread">
            <div class="col-sm-3 ann_box_pre">
                <div><span class="glyphicon glyphicon-user push_right_bit"></span><?php echo $value['first_name'].' '.$value['last_name']; ?></div>
                <div><span class="glyphicon glyphicon-registration-mark push_right_bit"></span><?php echo $value['creator_role']; ?></div>
                <div><span class="glyphicon glyphicon-calendar push_right_bit"></span><?php echo $value['date_created']; ?></div>
            </div>
        <div class="col-sm-9" >
            <p class="ann_title"><?php echo $value['ann_title']; ?></p>
            <div class="no_mag no_pad"><hr/></div>
            <p><?php echo $value['description']  ?></p></p>
        </div>
    </div>

        <?php }} ?>
        </div>
    <div class="clearfix"></div>
    <h4 class="col-sm-offset-1">Read</h4>
    <dic class="clearfix"></dic>
        
        <div id="read">
        <?php if($anns_read == NULL ){
            echo '<div id="read_al"class="col-sm-offset-1 alert alert-info text-center col-sm-6">No posted announcements</div>';
        }else{ ?>
        <?php
        foreach ($anns_read as $value) {?>
         <div class="col-sm-8 col-sm-offset-1 ann_box">
            <div class="col-sm-3 ann_box_pre">
                <div><span class="glyphicon glyphicon-user push_right_bit"></span><?php echo $value['first_name'].' '.$value['last_name']; ?></div>
                <div><span class="glyphicon glyphicon-registration-mark push_right_bit"></span><?php echo $value['creator_role']; ?></div>
                <div><span class="glyphicon glyphicon-calendar push_right_bit"></span><?php echo $value['date_created']; ?></div>
            </div>
        <div class="col-sm-9" >
            <p class="ann_title"><?php echo $value['ann_title']; ?></p>
            <div class="no_mag no_pad"><hr/></div>
            <p><?php echo $value['description']  ?></p></p>
        </div>
    </div>
        <?php }} ?>
            
        </div>
    <div class="col-sm-8 col-sm-offset-1 bottom_10" style="background-color: #f8f8f8"><h4 style="cursor: pointer;" id='more' class="text-center text-success">Load More Announcements</h4></div>
</div>
<script>
    var cur_load = 10;
    $(document).ready(function(){
        $("#send").click(function(){
            $('#send_annonce').fadeIn().removeClass('hidden');
        });
        $("#cnl").click(function(){
            $('#send_annonce').fadeOut().addClass('hidden');
        });
        $("#snd").click(function(){
            $('#msg').html('<img style="height: 30px;" class="push_right_bit" src="<?php echo base_url(); ?>/assets/images/ajax-loader.gif">Sending....');
            $('#msg').removeClass('hidden');
             setTimeout(function(){
                 var t = "<?php echo site_url(); ?>";
                 var c = t+"/project/announce/send";
                 $.post( c, $("#send_annonce").serialize()).done(function(data) {
                        if(data == 'true'){
                            $('#msg').removeClass('alert-danger');
                            $('#msg').addClass('alert-success');
                            $('#msg').html('<strong>Done!</strong> Announcement sent');
                            //write 
                            new_ann();
                        }else{
                            $('#msg').removeClass('alert-success');
                            $('#msg').addClass('alert-danger');
                            $('#msg').html(data);
                        }                     
                });
             },400);
        });
    });
    
    function new_ann(){
    var title  = $('#title').val();
    var desc  = $('#ann_desc').val();
    
    var a = '<div class="col-sm-8 col-sm-offset-1 ann_box"><div class="col-sm-3 ann_box_pre">';
    var b = '<div><span class="glyphicon glyphicon-user push_right_bit"></span><?php echo $this->session->userdata["fname"]." ".$this->session->userdata["lname"]; ?></div>';
    var c = '<div><span class="glyphicon glyphicon-registration-mark push_right_bit"></span><?php echo $this->session->userdata["type"]; ?></div><div><span class="glyphicon glyphicon-calendar push_right_bit"></span>Few minutes ago</div>';
    var d = '</div><div class="col-sm-9" ><p class="ann_title">'+title+'</p><hr/><p>'+desc+'</p></div></div>' ;  
        $('#read_al').hide();
        $('#read').prepend(a+b+c+d).hide().fadeIn('slow');
    }
    
     $("#more").click(function(){
            var id  = "<?php echo $this->session->userdata('user_id'); ?>";
            var lim = cur_load+10;
            cur_load = cur_load+10;
            $.post( "<?php echo site_url() ?>/project/announce/get_more", { id: id, lim: lim   } ).done(function( data ) {
                var ann_json = JSON.parse(data);
                $('#read').html('');
                for(var i = 0; i <= ann_json.length; i++){
                    var a = '<div class="col-sm-8 col-sm-offset-1 ann_box"><div class="col-sm-3 ann_box_pre">';
                    var b = '<div><span class="glyphicon glyphicon-user push_right_bit"></span>'+ann_json[1].first_name+' '+ann_json[1].last_name+'</div>';
                    var c = '<div><span class="glyphicon glyphicon-registration-mark push_right_bit"></span>'+ann_json[1].creator_role+'</div><div><span class="glyphicon glyphicon-calendar push_right_bit"></span>'+ann_json[1].date_created+'</div>';
                    var d = '</div><div class="col-sm-9" ><p class="ann_title">'+ann_json[1].ann_title+'</p><hr/><p>'+ann_json[1].description+'</p></div></div>' ;
                    $('#read').append(a+b+c+d).hide().fadeIn('slow');
                }
            });
        });
    
</script>

<?php if($this->session->userdata['type']=='supervisor') {?>
<script>
    $( "#receiver" ).change(function() {
        if($(this).val() == 'Choose groups'){
            $("#groups").removeClass('hidden');
        }else{
            $("#groups").addClass('hidden');
        }
    });
    
    
</script>
<?php } ?>
