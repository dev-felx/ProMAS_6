        <?php $this->load->view('archive/search/header'); ?>
        <div class="container-fluid">
            <div class="row">
                <div id="leftside" class="col-sm-2">
                    <a href="<?php echo site_url('archive/archive/search'); ?>"><img src="<?php echo base_url(); ?>assets/images/banner.jpg" alt="sProMAS Archive" class="img-rounded col-sm-12"></a>
                </div>
                <div id="maincon" class="col-sm-8">
                    <?php foreach($result as $v){?>
                   
                    <div class="page-header">
                        <h3 style="color: 0076a7;"><?php print $v->project_name ?></h3>
                    </div>
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#abstract" data-toggle="tab">Abstract</a></li>
                        <li><a href="#details" data-toggle="tab">Details</a></li>
                        <?php if($this->session->userdata('archive_level') == 2 || $this->session->userdata('archive_level') == 3) {?>
                            <li><a href="#documents" data-toggle="tab">Documents</a></li>
                            <li><a href="#participants" data-toggle="tab">Participants</a></li>
                        <?php }?>
                        <li><a href="#permission" data-toggle="tab">Request more Info</a></li>
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div class="tab-pane active" id="abstract">
                            <?php
                                if(!isset($abst['error'])){
                                foreach ($abst as $a){?>
                            <object data="<?php echo base_url().$a->document_path; ?>" width="100%" height="70%">
                                        <p>It appears you don't have a PDF plugin for this browser. <a href="<?php echo site_url(); ?>/archive/archive/download/<?php print $a->document_path; ?>">click here to download the PDF file.</a></p>
                                    </object>
                                <?php
                                } } else {?>
                                <p><?php echo $abst['error']?></p>
                            <?php }
                            ?>
                        </div>
                        <div class="tab-pane" id="details">
                            <p>This project was done during the academic year <?php print $v->academic_year?>, under the department of 
                                 at University of Dar es Salaam.</p>
                        </div>
                        <div class="tab-pane" id="documents">
                            <div class="list-group col-sm-8">
                            <?php if(!isset($docu['error'])){
                                foreach ($docu as $d){
                                    if ($this->session->userdata('archive_level') == 2 && $d->document_name == 'Final Report' ){?>
                                        <li class="list-group-item list-group-item-info"><?php print $d->document_name; ?><a href="<?php echo site_url(); ?>/archive/archive/download/<?php print $a->document_path; ?>" class="btn btn-xs btn-primary pull-right">Download</a></li>
                                    <?php }else { ?>
                                        <li class="list-group-item list-group-item-info"><?php print $d->document_name; ?><a href="<?php echo site_url(); ?>/archive/archive/download/<?php print $a->document_path; ?>" class="btn btn-xs btn-primary pull-right">Download</a></li>
                                    <?php } ?>
                                <?php } 
                            } else {?>
                                <p><?php echo $docu['error']?></p>
                            <?php } ?>
                            </div>
                        </div>
                        <div class="tab-pane" id="participants">
                        <?php if(!isset($part['error'])){
                            foreach ($part as $p){
                                if($p->type == 'coordinator'){ ?>
                                    <h4 style="color: 0076a7;">Project Coordinator</h4>
                                    <address>
                                        &emsp;<strong><?php print $p->first_name; ?> <?php print $p->last_name; ?></strong><br>
                                        &emsp;&emsp;Phone: <?php print $p->phone_no; ?><br>
                                        &emsp;&emsp;Email: <?php print $p->email; ?><br>
                                        &emsp;&emsp;<?php print $p->address; ?>
                                    </address>
                            <?php }elseif ($p->type == 'supervisor') {?>
                                <h4 style="color: 0076a7;">Project Supervisor</h4>
                                <address>
                                    &emsp;<strong><?php print $p->first_name; ?> <?php print $p->last_name; ?></strong><br>
                                    &emsp;&emsp;Phone: <?php print $p->phone_no; ?><br>
                                    &emsp;&emsp;Email: <?php print $p->email; ?><br>
                                    &emsp;&emsp;<?php print $p->address; ?>
                                </address>
                            <?php }else{ ?>
                                <address>
                                    <strong><?php print $p->first_name; ?> <?php print $p->last_name; ?></strong><br>
                                    &emsp;Phone: <?php print $p->phone_no; ?><br>
                                    &emsp;Email: <?php print $p->email; ?><br>
                                    &emsp;<?php print $p->address; ?>
                                </address>
                        <?php } }                        
                            }else {?>
                                <address><?php echo $part['error']?></address>
                            <?php }
                        ?>
                        </div>
                        <div class="tab-pane" id="permission">
                            <div class="col-sm-8">
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
                                    <p class="form-control-static"><?php print $v->project_name?></p>
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
                                        <label class="radio-inline"><input id="stu_radio" name="type" type="radio"  value="student" checked="checked">Student</label>
                                        <label class="radio-inline"><input id="non_radio" name="type" type="radio"  value="non_student">Non Student</label>       
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
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <!--footer class="footer" style="position: absolute; width: 100%; bottom: 40px;">
            <div class="col-sm-8 col-sm-offset-2">
                <hr style="border: none; height: 2px; color: blue; background: #0093D0;"/>
            </div>
            <div class="col-sm-8 col-sm-offset-2" style="margin-top: -20px; color: #0093D0; ">
                <h5 class="pull-left">UDSM | CoICT | Computer Science and Engineering Department</h5>
                <h5 class="pull-right">Copyright &COPY; <?php echo date('20y', time()); ?> ProMAS</h5>
            </div>
        </footer-->
        <script src="<?php echo base_url(); ?>assets/bootstrap/js/bootstrap.js"></script>
        <script>$('#help').tooltip();</script>
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
    </body>
</html>                                                                        
