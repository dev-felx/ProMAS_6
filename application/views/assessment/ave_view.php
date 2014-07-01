<div>
    <h4 class="col-sm-5 pull-left"><?php echo $sub_title; ?></h4> 
</div>
<div class="clearfix"></div>
<hr/>
<div class="col-sm-4">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title text-center">Students</h3>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <div class="btn-group btn-group-justified">
                        <div class="btn-group">
                          <button id="1" type="button" class="semester btn btn-primary">Semester 1</button>
                        </div>
                        <div class="btn-group">
                          <button id="2" type="button" class="semester btn">Semester 2</button>
                        </div>
                        <div class="btn-group">
                          <button id="3" type="button" class="semester btn">Full Year</button>
                        </div>
                    </div>
                </div>
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
                <div>
                    <label>Students</label>
                    <ul id="stu" class="list-group">
                      </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-8">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title text-center">Student Weekly Average Assessment</h3>
            </div>
            <div id="form_cont" class="panel-body">
                <?php $this->load->view('assessment/ave_form'); ?>
            </div>
        </div>
    </div>
<script> 
var curr_sem = 1;
var curr_stu;
var forms;
$('#ind_form').hide();
$(document).ready(function(){  
    $( "#pro" ).change(function() {
            var id = $(this).val();
            get_stu(id);
        
    });
    
    $( "#pro" ).trigger('change');
    function get_stu(id){
        $('#stu').html('<img style="height: 30px;" class="col-sm-offset-3 push_right_bit" src="<?php echo base_url(); ?>/assets/images/ajax-loader.gif">Fetching....');
         setTimeout(function(){
             var t = "<?php echo site_url(); ?>";
             var c = t+"/assessment/assess/get_pro_stu";
             $.post( c, {id: id}).done(function(data) {
                $('#stu').html('');
                for(var i = 0; i < data['students'].length; i++){
                    var x = data['students'][i].first_name+" "+data['students'][i].last_name +" - "+ data['students'][i].registration_no;
                    $('#stu').append('<li id="'+data['students'][i].registration_no+'" class="stu_btn list-group-item">'+x+'</li>');
                }
            },'json');
         },400);
    }
    
    //student functions
        $('body').on('click', '.stu_btn', function () { 
            $('.stu_btn').removeClass('stu_btn_active');
            $(this).addClass('stu_btn_active');
            curr_stu = $(this).attr('id');
            
            //get forms
             var t = "<?php echo site_url(); ?>";
             var c = t+"/assessment/assess/calc_ave";
             $.post( c, {sc: curr_sem}).done(function(data) {
                forms = data.forms;
                fill();
            },'json'); 
        });
        
        
        $('.semester').click(function(){
            $('.semester').removeClass('btn-primary');
            $(this).addClass('btn-primary');
            curr_sem =  $(this).attr('id');
            $('#'+curr_stu).trigger('click');
        });
        
        
        function fill(){
            for (var i=0; i < forms.length; i++){
                 if (forms[i]['student'] == curr_stu){
                    /* if(curr_sem <= 2){
                         $('#sem').html(curr_sem);
                     }else{
                         $('#sem').html('Full Year');
                     }*/
                     
                     $('#name').html(forms[i].student_name);
                     $('#reg_no').html(forms[i].student);
                     $('#pro_name').html(forms[i].project_name);

                     $('[name="init"]', '#ind_form').attr('value',forms[i].initiative);
                     $('[name="init"]', '#ind_form').val(forms[i].initiative);

                     $('[name="gen"]', '#ind_form').attr('value',forms[i].understand);
                     $('[name="gen"]', '#ind_form').val(forms[i].understand);

                     $('#spec').attr('value',forms[i].contribution);
                     $('#spec').val(forms[i].contribution);

                     $('#qn').attr('value',forms[i].qna);
                     $('#qn').val(forms[i].qna);
                     
                     $('#ind_form').slideDown();
                     
                     

                 }
             }
        }
});
</script>