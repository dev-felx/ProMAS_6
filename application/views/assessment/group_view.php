<div class="col-sm-4">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title text-center">Project Groups</h3>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label>Choose Project</label>
                    <select id="pro" class="form-control">
                        <?php 
                            foreach ($projects as $value){
                                echo '<option value="'.$value['project_id'].'">'.$value['title'].'</option>';
                            }
                        ?>
                      </select>
                </div>
                <div class="form-group">
                    <label>Report type</label>
                    <select id="type" class="form-control">
                        <option></option>
                        <option value="Project proposal">Project‌ proposal</option>
                        <option value="Project progress report">Project‌ progress‌ report</option>
                        <option value="Final Project report">Final‌ Project‌ report</option>
                     </select>
                </div>
                
            </div>
        </div>
    </div>
    <div class="col-sm-8">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title text-center">Report Assessment Form</h3>
            </div>
            <div class="panel-body">
                <?php $this->load->view('assessment/grp_form'); ?>
            </div>
        </div>
    </div>
<script>
    var forms = <?php echo json_encode($forms); ?>;
    var curr_form;
    $('#grp_form').hide();
    $(document).ready(function(){ 
        $( "#pro" ).change(function() {
            $( "#type" ).trigger('change');
        });
        $( "#type" ).change(function() {
            var id = $(this).val(); 
            if(id == ''){
                $('#grp_form').hide();
            }else{
                var pro = $('#pro').val();
                $('#grp_form').slideDown();
                $('#msg_grp').html('');
                for (var i=0; i < forms.length; i++){
                    if (forms[i]['project_id'] == pro && forms[i]['type'] == id){
                        $('#title').html(forms[i].project_name);
                         $('#type_').html(forms[i].type);
                         $('#sem').html(forms[i].semester);
                         
                         $('[name="abs"]', '#grp_form').attr('value',forms[i].abs);
                         $('[name="abs"]', '#grp_form').val(forms[i].abs);
                         
                         $('[name="ack"]', '#grp_form').attr('value',forms[i].ack);
                         $('[name="ack"]', '#grp_form').val(forms[i].ack);
                         
                         $('[name="con"]', '#grp_form').attr('value',forms[i].t_content);
                         $('[name="con"]', '#grp_form').val(forms[i].t_content);
                         
                         $('[name="intro"]', '#grp_form').attr('value',forms[i].intro);
                         $('[name="intro"]', '#grp_form').val(forms[i].intro);
                         
                         $('[name="main"]', '#grp_form').attr('value',forms[i].main);
                         $('[name="main"]', '#grp_form').val(forms[i].main);
                         
                         $('[name="ref"]', '#grp_form').attr('value',forms[i].ref);
                         $('[name="ref"]', '#grp_form').val(forms[i].ref);
                         
                         $('[name="com"]', '#grp_form').html(forms[i].comments);
                         $('[name="com"]', '#grp_form').val(forms[i].comments);
                         
                         $('[name="form_id"]', '#grp_form').attr('value',forms[i].form_id);

                    }
                }
            }
        });
        
        <?php if($this->session->userdata('type') == 'supervisor'){ ?>
            $('#sav_form').click(function(){
                $('#msg_grp').html('<img style="height: 30px;" class="col-sm-offset-5 push_right_bit" src="<?php echo base_url(); ?>/assets/images/ajax-loader.gif">Fetching....');
                 setTimeout(function(){
                     var t = "<?php echo site_url(); ?>";
                     var c = t+"/assessment/assess/save_form_grp";
                     $.post( c, $("#grp_form").serialize()).done(function(data) {
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
                $('#msg_grp').html('<div class="alert alert-danger text-center">You need Supervisor priviledges to edit this form</div>');
                return false;
                });
        <?php } ?>
    }); 
</script>