<?php 
    if($this->session->userdata('user_id') != ''){
        $link = '/archive/users/request_man';
    }else{
        $link = '/archive/users/request';
    }
?>
<form id="request_form" role="form" action="<?php site_url($link); ?>" method="POST">
    <div id="msg_req"></div>
    <input type="text" hidden="" name="project_id"> 
    <input type="text" hidden="hidden" name="pname" value="promas"> 
    <div class="form-group">
        <label class="control-label">Project</label>
        <p class="form-control-static">Project Management and archiving system (ProMAS)</p>
    </div>
    <div class="form-group">
        <label class="control-label">Access To:</label>
        <select class="form-control" name="level">
            <option value="2">Final Year Report</option>
            <option value="3">Full Access to Project</option>
         </select>
    </div>
    <?php if($this->session->userdata('user_id_arch') == '' && $this->session->userdata('user_id') == ''){ ?>
      <div class="form-group"> 
        <div class="input-group">
            <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
            <input type="text" class="form-control" id="fname" name='fname' placeholder="Enter First Name" value="<?php echo set_value('fname'); ?>">
        </div>
      </div>
      <div class="form-group">  
          <div class="input-group">
            <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
            <input type="text" class="form-control" id="lname" name="lname" placeholder="Enter Last Name" value="<?php echo set_value('lname'); ?>">
        </div>
      </div>
      <div class="form-group">
         <div class="input-group">
            <span class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></span>
            <input type="email" class="form-control" id="email" name="email" placeholder="Enter a Valid Email" value="<?php echo set_value('email'); ?>">
        </div> 
      </div>
      <div class="form-group">
            <label for="type">User Type</label><br/>
            <label class="radio-inline">
                <input id="stu_radio" name="type" type="radio"  value="student" checked="checked">Student
            </label>
            <label class="radio-inline">
                <input id="non_radio" name="type" type="radio"  value="non_student">Non Student
            </label>       
        </div>
        <div class="form-group" id="reg">
            <label for="reg">Registration Number</label>
            <input type="text"  name="reg" class="form-control">
        </div>
    <?php } ?>
      <div class="form-group">
          <label class="control-label">Further Information: reasons etc. (option)</label>
          <textarea class="form-control" name="info"></textarea>
      </div>
    <div class="form-group">
        <button id="req_btn" class="btn btn-primary pull-right">Submit</button>
    </div>
</form>
<script> 
$(document).ready(function(){
    $('#stu_radio').change(function(){
        $('#reg').show();
    });
    $('#non_radio').change(function(){
        $('#reg').hide();
    });
    
    $('#req_btn').click(function(){
        $('#msg_req').html('<img style="height: 30px;" class="col-sm-offset-5 push_right_bit" src="<?php echo base_url(); ?>/assets/images/ajax-loader.gif">Fetching....');
         setTimeout(function(){
             var c = "<?php echo site_url($link); ?>";
             $.post( c, $("#request_form").serialize()).done(function(data) {
                 if(data.status == 'true'){
                     $('#msg_req').html('<div class="alert alert-success text-center">Saved</div>');
                 }else{
                     $('#msg_req').html('<div class="alert alert-danger text-center">'+data.data+'</div>');
                 }
             },'json');
         },400);
         return false;
    });
});
</script>