<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<?php $this->load->view('templates/header_out'); ?>
<script src="<?php echo base_url(); ?>assets/jquery/jquery-ui-1.10.3.custom/js/jquery-ui.js"></script>
<link type="text/css" rel="Stylesheet" href="<?php echo base_url(); ?>assets/jquery/jquery-ui-1.10.3.custom/css/jquery-ui.css" media="screen">

<div class=" col-sm-10 col-sm-offset-1"  id="reg_form">
    <div class="container-fluid">
        <form class=" " role="form" action="<?php echo site_url(); ?>/project/project_space/timeline_setup" method="POST">
            <div class="alert alert-info fade in text-center"><b>Setup</b> </div>
            
            <div class="row hr" style="padding-bottom: px"><hr/></div>
            
            <div class="text-info fade in text-center"><b >Define Timeline</b> </div>
            <div class="text-info fade in text-center ">A timeline is defined as a start and end date for an academic year</div>
            
            <div class="radio row">
                    <label>
                    <input type="radio" name="timeline" class="timeline" id="create" value="create" checked>
                    Create new
                  </label>
            </div>
            <div id="create_timeline" class="row">
                <div class="form-group container-fluid">
                    <label for="Start Date" class=" control-label">Start Date<?php show_form_error('sdate'); ?></label>
                    <div class="">
                        <input type="text" id="datepicker1" class=" datepicker form-control" name="sdate" placeholder="Start Date">
                   </div>
                </div>

                <div class="form-group container-fluid">
                    <label for="End Date" class=" control-label">End Date<?php show_form_error('edate'); ?> </label>
                    <div class="">
                        <input type="text" id="datepicker2"  class="datepicker form-control" name="edate" placeholder="End Date">
                    </div>
                </div>
            </div>
            <?php if($space_data !=NULL ){ ?>
            <div class="radio row">
              <label>
                <input type="radio" name="timeline" class="timeline" id="choose" value="choose">
                Choose existing
              </label>
            </div>
            <div id="choose_timeline" class="hide row">
                <div class="form-group container-fluid">
                    <label for="" class="control-label">Timeline</label><?php show_form_error('choose_space'); ?>
                    <select  name="choose_space" class="form-control">
                      <option></option>
                      <?php foreach ($space_data as $value){ ?>
                      <option value="<?php echo $value['space_id'];  ?>">Academic Year : <?php echo $value['academic_year']; ?>&nbsp; &nbsp;Start :<?php echo $value['start_date']; ?> Ends :<?php echo $value['end_date']; ?></option>';
                      <?php }?>
                    </select>
                </div>
            </div>
            <?php  } ?>

            <div class="row hr" style="padding-bottom: px"><hr/></div>
            
            <div class="row" id="assess_interval">
                <div class="text-info fade in text-center"><b>Assessment interval</b></div>
                <div class="text-info fade in text-center ">Assessment interval is defined by coordinator</div>
                <div class="form-group container-fluid">
                    <label for="" class="control-label">Interval</label><?php show_form_error('assess_interval'); ?>
                    <select  name="assess_interval" class="form-control">
                      <option></option>
                      <option value="1">1 week</option>
                      <option value="2">2 weeks</option>
                      <option value="3">3 weeks</option>
                    </select>
                </div>
            </div>    
            <div class="row">
                <div class=" col-sm-offset-4">
                <button type="submit" class=" col-sm-3 btn btn-group btn-primary   ">Submit</button>
                <a class="col-sm-3 btn btn-group btn-warning push_left_bit " href="<?php echo site_url(); ?>/access/logout" role="button" >Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>
    <?php $this->load->view('templates/footer_out'); ?>
<script>
    $(document).ready(function(){
        $('.timeline').click(function(e) {
        if($('#create').is(':checked')) { 
                $('#create_timeline').removeClass('hide');
                $('#assess_interval').removeClass('hide');
            }else{
                $('#create_timeline').addClass('hide');
            }
        if($('#choose').is(':checked')) { 
                $('#choose_timeline').removeClass('hide');
                $('#assess_interval').addClass('hide');
            }else{
                $('#choose_timeline').addClass('hide');
            }
    });    
 
    });
</script>

<script> 
    $(function() {
    
        $( "#datepicker1" ).datepicker({ minDate: "-2m"});
        $( "#datepicker2" ).datepicker({ maxDate: "+1y" }); 
    });
</script>