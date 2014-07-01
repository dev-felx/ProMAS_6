<style>
    #edit_event_form{
        border: #ccc solid 1px;
        border-radius: 2px;
        padding: 5px;
    }
    #edit_event_form > .alert-info{
        cursor: pointer;
        margin-bottom: 0px;
    }
    #form_body{
        margin-top: 10px;
    }
    
    .err::-webkit-input-placeholder { /* WebKit browsers */
    color:  #A94442;
    opacity: 0.7;
    }
    .err:-moz-placeholder { /* Mozilla Firefox 4 to 18 */
        color:  #A94442;
        opacity: 0.7;
    }
    .err::-moz-placeholder { /* Mozilla Firefox 19+ */
        color:  #A94442;
        opacity: 0.7;
    }
    .err:-ms-input-placeholder { /* Internet Explorer 10+ */
        color:  #A94442;
        opacity: 0.7;
    }

</style>
<script src="<?php echo base_url(); ?>assets/jquery/jquery-ui-1.10.3.custom/js/jquery-ui.js"></script>
<link type="text/css" rel="Stylesheet" href="<?php echo base_url(); ?>assets/jquery/jquery-ui-1.10.3.custom/css/jquery-ui.css" media="screen">

<form id="edit_event_form" action="#" method="post">   
       <div id="edit_msg" class="alert alert-info text-center">Edit Event or Activity</div>
       <div id="form_body">
       <input class="form-control hidden" type="text" id="eid" name="id">    
       <div class="form-group">
          <input class="form-control" type="text" placeholder="Event Title..." id="etitle" name="title">
       </div>
       
       <div class="form-group">
            <textarea class="form-control" rows="3" placeholder="Event Description..." id="edescription" name="description" ></textarea>
       </div>
                  
       <div class="form-group">
            <div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                <input type="text" id="edate_start"  class="datepicker form-control" name="date_start" placeholder="Start Date">                
            </div>
       </div>

        <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                    <input type="text" id="edate_end"  class="datepicker form-control" name="date_end" placeholder="End Date">                
                </div>
        </div>
        <hr/>
        <div class="form-group">
            <button id="save_btn" class=" col-sm-4 btn btn-success pull-right" type="button">Save</button>
            <button class="show_def col-sm-4 btn btn-danger pull-right push_right_bit" type="button">Cancel</button>
        </div>
       </div>
        <div class="clearfix"></div>
</form>
<script>
    var ajax_alive = false;
    $(document).ready(function(){
        $( "#date_start" ).datepicker({
                dateFormat: 'yy-mm-dd'
        });
        $( "#date_end" ).datepicker({
                dateFormat: 'yy-mm-dd'
        });
        $('#add_event_form > .alert-info').click(function(){
            $('#form_body').slideToggle(500);
        });
        
        $("#save_btn").click(function(){
            if(ajax_alive === false){
                $('#edit_event_form input').parent('div').removeClass('has-error');
                $('#edit_event_form textarea').parent('div').removeClass('has-error');
                $('#edit_msg').html('<img style="height: 30px;" class="push_right_bit" src="<?php echo base_url(); ?>assets/images/ajax-loader.gif">Saving....');
                ajax_alive = true;
                setTimeout(function(){
                     $.post( "<?php echo site_url() ?>/timeline/timeline/edit_event", $("#edit_event_form").serialize() , function( data ) {
                         if(data.status === 'not_valid'){
                            $.each(data.errors, function(key, val) {
                                $('[name="'+ key +'"]', '#edit_event_form').attr('placeholder',val);
                                $('[name="'+ key +'"]', '#edit_event_form').addClass('err');
                                $('[name="'+ key +'"]', '#edit_event_form').parent('div').addClass('has-error');
                                $('#edit_msg').html('We need more data');
                            });
                        }else if(data.status === 'success') {
                            $('#edit_msg').removeClass('alert-info');
                            $('#edit_msg').addClass('alert-success');
                            $('#edit_msg').html('Event saved');
                            $('#calendar').fullCalendar( 'refetchEvents' );
                        }
                    
                    },"json");
                    ajax_alive = false;
                 },300);
             }
             return false;
        });
       
        });
</script>