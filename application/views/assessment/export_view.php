<div>
    <h4 class="col-sm-4 pull-left"><?php echo $sub_title; ?></h4> 
</div>
<div class="clearfix"></div>
<hr/>
<?php if($this->session->userdata('type') == 'supervisor'){ ?>
    <div class="col-sm-10 col-sm-offset-1">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">Export Student Marks</h3>
            </div>
            <div class="panel-body">
                <form role="form" action="<?php echo site_url('assessment/assess/gen_csv_sup'); ?>" method="POST">
                    <div class="form-group">
                        <label>Duration: </label>
                        <select class="form-control" id="time" name="time">
                            <option value="1">Semester 1</option>
                            <option value="2">Semester 2</option>
                            <option value="3">Full Year</option>
                       </select>
                    </div>
                    <div class="form-group">
                        <label>Export For: </label>
                        <select class="form-control" id="grp" name="grp">
                            <option value="0">All My Groups</option>
                            <option value="1">Choose Groups</option>
                       </select>
                    </div>
                    <div class="form-group">
                        <label for="receiver">Choose Groups</label>
                        <select multiple="multiple" id="receiver" class="form-control" name="receiver">
                            <?php
                                foreach ($projects as $value) {
                                    echo "<option value='".$value['project_id']."'>".$value['title']."</option>";
                                }
                            ?>
                       </select>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary col-sm-4 col-sm-offset-4">Export to Excel File</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php }else if($this->session->userdata('type') == 'coordinator'){ ?>
    <div class="col-sm-10 col-sm-offset-1">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">Export Student Marks</h3>
            </div>
            <div class="panel-body">
                <form role="form" action="<?php echo site_url('assessment/assess/gen_csv_coor'); ?>" method="POST">
                    <div class="form-group">
                        <label>Duration: </label>
                        <select class="form-control" id="time" name="time">
                            <option value="1">Semester 1</option>
                            <option value="2">Semester 2</option>
                            <option value="3">Full Year</option>
                       </select>
                    </div>
                    <div class="form-group">
                        <label>Export For: </label>
                        <select class="form-control" id="grp2" name="grp2">
                            <option value="0">All My Groups</option>
                            <option value="1">Choose Groups</option>
                       </select>
                    </div>
                    <div class="form-group">
                        <label for="receiver2">Choose Groups</label>
                        <select multiple="multiple" id="receiver2" class="form-control" name="receiver2">
                            <?php
                                foreach ($projects as $value) {
                                    echo "<option value='".$value['project_id']."'>".$value['title']."</option>";
                                }
                            ?>
                       </select>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary col-sm-4 col-sm-offset-4">Export to Excel File</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php } ?>
<script>
    $('#receiver').parent('div').hide();
    $('#grp').change(function(){
        if($(this).val() == '1'){
            $('#receiver').parent('div').slideDown();
        }else{
            $('#receiver').parent('div').slideUp();
        }
    });
    $('#receiver2').parent('div').hide();
    $('#grp2').change(function(){
        if($(this).val() == '1'){
            $('#receiver2').parent('div').slideDown();
        }else{
            $('#receiver2').parent('div').slideUp();
        }
    });
    
</script>

