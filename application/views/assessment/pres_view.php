<div>
    <h4 class="col-sm-3 pull-left"><?php echo $sub_title; ?></h4> 
    <a href="<?php echo site_url('home'); ?>" role="button" class="btn btn-success pull-right push_left_bit">My Session</a>
</div>
<div class="clearfix"></div>
<hr/>
<div class="col-sm-4">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title text-center">Project Groups</h3>
            </div>
            <div class="panel-body">
                <?php if($forms != null){ ?>
                <div class="form-group">
                    <div class="btn-group btn-group-justified">
                        <div class="btn-group">
                          <button id="1" type="button" class="semester btn btn-primary">Semester 1</button>
                        </div>
                        <div class="btn-group">
                          <button id="2" type="button" class="semester btn">Semester 2</button>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Choose Project</label>
                    <select id="pro" class="form-control">
                        <?php 
                            foreach ($forms as $value){
                                echo '<option value="'.$value['project_id'].'">'.$value['project_name'].'</option>';
                            }
                        ?>
                      </select>
                </div>
                <div>
                    <label>Students</label>
                    <ul id="stu" class="list-group">
                      </ul>
                </div>
              <?php } ?>  
                 
            </div>
        </div>
    </div>
    <div class="col-sm-8">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title text-center">Presentation Assessment Form</h3>
            </div>
            <div class="panel-body"> 
                <?php 
                 if($forms != null){
                    $this->load->view('assessment/pres_form'); 
                 } else{ ?>
                  <div class="alert alert-danger">No forms were found. Either you have not being assigned a group or forms were not created.<br/>Contact the coordinator please</div>
              <?php } ?>    
                
            </div>
        </div>
    </div>
<script>
    var forms = <?php echo json_encode($forms); ?>;
    var curr_form;
    var curr_sem = 1;
    $(document).ready(function(){ 
        $( "#pro" ).change(function() {
            var id = $(this).val();
            get_stu(id);
            show_form(id);
        });

        $( "#pro" ).trigger('change'); 

        function get_stu(id){
                $('#stu').html('<img style="height: 30px;" class="col-sm-offset-3 push_right_bit" src="<?php echo base_url(); ?>/assets/images/ajax-loader.gif">Fetching....');
                 setTimeout(function(){
                     var t = "<?php echo site_url(); ?>";
                     var c = t+"/assessment/assess/get_pro_stu";
                     $.post( c, {id: id}).done(function(data) {
                        $('#stu').html('');
                        $('#week').html('');
                        for(var i = 0; i < data['students'].length; i++){
                            var x = data['students'][i].first_name+" "+data['students'][i].last_name +" - "+ data['students'][i].registration_no;
                            $('#stu').append('<li id="'+data['students'][i].registration_no+'" class="stu_btn list-group-item">'+x+'</li>');
                        }      
                    },'json');
                 },400);
            }

            function show_form(id){
                $('#pres_form').slideDown();
                $('#msg_grp').html('');
                for (var i=0; i < forms.length; i++){
                    if (forms[i]['project_id'] == id && forms[i]['semester'] == curr_sem){
                        $('#type').html(forms[i].pres_type);
                        $('#sem').html(forms[i].semester);
                        
                        $('[name="title"]', '#pres_form').html(forms[i].project_name);
                        $('[name="title"]', '#pres_form').val(forms[i].project_name);
                        
                        $('[name="im"]', '#pres_form').attr('value',forms[i].im);
                        $('[name="im"]', '#pres_form').val(forms[i].im);
                        
                        $('[name="sf"]', '#pres_form').attr('value',forms[i].sf);
                        $('[name="sf"]', '#pres_form').val(forms[i].sf);
                        
                        $('[name="sc"]', '#pres_form').attr('value',forms[i].sc);
                        $('[name="sc"]', '#pres_form').val(forms[i].sc);
                        
                        $('[name="pq"]', '#pres_form').attr('value',forms[i].pq);
                        $('[name="pq"]', '#pres_form').val(forms[i].pq);
                        
                        $('[name="ptc"]', '#pres_form').attr('value',forms[i].ptc);
                        $('[name="ptc"]', '#pres_form').val(forms[i].ptc);
                        
                        $('[name="com"]', '#pres_form').attr('value',forms[i].com);
                        $('[name="com"]', '#pres_form').val(forms[i].com);
                        
                        $('[name="form_id"]', '#pres_form').attr('value',forms[i].form_id);
                    }
                }
            }
            
            <?php if($this->session->userdata('type') == 'panel_head'){ ?>
                $('#sav_form').click(function(){
                $('#msg_grp').html('<img style="height: 30px;" class="col-sm-offset-5 push_right_bit" src="<?php echo base_url(); ?>/assets/images/ajax-loader.gif">Fetching....');
                 setTimeout(function(){
                     var t = "<?php echo site_url(); ?>";
                     var c = t+"/assessment/assess_panel/save_form";
                     $.post( c, $("#pres_form").serialize()).done(function(data) {
                         if(data.status == 'cool'){
                             $('#msg_grp').html('<div class="alert alert-success text-center">Saved</div>');
                             forms = data.forms;
                         }else{
                             $('#msg_grp').html('<div class="alert alert-danger text-center">'+data+'</div>');
                         }
                     },'json');
                 },400);
                 return false;
            });
           <?php }else{ ?>
                $('#sav_form').click(function(){
                $('#msg_grp').html('<div class="alert alert-danger text-center">You need Panel Head priviledges to edit this form</div>');
                    return false;
                });
            <?php } ?>
                
            //semester change
        $('.semester').click(function(){
            $('.semester').removeClass('btn-primary');
            $(this).addClass('btn-primary');
            curr_sem =  $(this).attr('id');
            $( "#pro" ).trigger('change');
        });
    });
       
</script>