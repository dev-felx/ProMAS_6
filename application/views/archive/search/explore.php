        <?php $this->load->view('archive/search/header'); ?>
        <script src="<?php echo base_url(); ?>assets/bootstrap/js/bootstrap.js"></script>
        <link type="text/css" rel="Stylesheet" href="<?php echo base_url(); ?>assets/jquery/datatable/jquery.bootstrap.datatable.css">
        <script src="<?php echo base_url(); ?>assets/jquery/datatable/jquery.dataTables.js"></script>
        <script src="<?php echo base_url(); ?>assets/jquery/datatable/jquery.bootstrap.datatable.js"></script>
        <div class="container-fluid">
            <div class="row" style="margin-top: 40px;">
                <div id="leftside" class="col-sm-2">
                    <img src="<?php echo base_url(); ?>assets/images/banner.jpg" alt="sProMAS Archive" class="img-rounded col-sm-12">
                    <div style="margin-top: 120px;" class="container-fluid text-center">
                        <div class="alert alert-info text-center" >Filter by</div>
                        <div class="panel panel-info">
                            <div class="panel-heading">Alphabetical</div>
                            <!--<button type="submit" name="term" value="asc" class="btn btn-primary" id="asc" onclick="filter(document.getElementById('asc').value);">A-Z</button>-->
                            <a href="#" class="list-group-item" id="asc" onclick="filter('asc');">A to Z</a>
                            <!--<button type="submit" name="term" value="desc" class="btn btn-primary" id="desc" onclick="filter(document.getElementById('desc').value);">Z-A</button>-->
                            <a href="#" class="list-group-item" onclick="filter('desc');">Z to A</a>
                        </div>
                        <div class="panel panel-info">
                            <div class="panel-heading">By Academic Year</div>
                        <?php if (isset($a_y)){ 
                            foreach($a_y as $a){?>
                            <!--<button type="submit" name="term" value="<?php //print $a->academic_year?>" class="btn btn-primary filter" onclick="filter('<?php// print $a->academic_year?>');"><?php print $a->academic_year?></button>-->
                            <a href="#" class="list-group-item" onclick="filter('<?php print $a->academic_year?>');"><?php print $a->academic_year?></a>
                        <?php }
                            }  else {?>
                            <div class="alert alert-danger"><p>Nothing Found</p></div>
                            <?php }
                            ?>
                        </div>
                        <div class="panel panel-info">
                            <div class="panel-heading">By Department</div>
                        <?php  if (isset($dep)){
                            foreach($dep as $dp){?>
                                <!--<button type="submit" name="term" value="" class="btn btn-primary filter" onclick="filter('<?php //print $dp->shortform?>');"><?php //print $dp->shortform?></button>-->
                                <a href="#" class="list-group-item" onclick="filter('<?php print $dp->name?>');"><?php print $dp->name?></a>
                            <?php }
                             }  else {?>
                            <div class="alert alert-danger"><p>Nothing Found</p></div>
                            <?php }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="col-sm-8">
                    <div class="page-header">
                        <h2 style="color: 0076a7;">sProMAS Archive Explorer</h2>
                    </div>
                    <h4 style="color: #0076a7">Latest Project's</h4>
                    <div class="list-group" id="maincon">
                        <div id="loading"></div>
                    <?php
                    if(!empty($res)){?>
                        <table id="project_list"  width="100%">
                            <thead>
                                <tr>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                        <?php
                        foreach($res as $v){?>
                            <tr>    
                                <td><a href="<?php echo site_url(); ?>/archive/archive/profile/<?php print $v->project_profile_id ?>" class="list-group-item">
                                <h4 class="list-group-item-heading"><?php print $v->project_name ?></h4>
                                <p class="list-group-item-text"><?php print $v->description ?></p>
                                </a></td>
                            </tr>
                    <?php      
                        }
                    }else {?>
                            <p>No Result Found</p>
                    <?php
                    }
                    ?>      </tbody>
                        </table>
                </div>
                </div>
            </div>
        </div>
        <script>$('#project_list').dataTable(); 
        function filter(filter){
            var filter_cat = filter;
            var response_brought = $("#response_brought");
            var dataString = "filter_cat=" + filter_cat;
			
            $.ajax({  
                type: "POST",  
                url: '<?php echo site_url(); ?>/archive/archive/explore_filter/', 
                data: dataString,  
                success: function(response){
                    $("#project_list").html(response);
                }
            }); 
        }
        </script>
        <?php $this->load->view('archive/search/footer'); ?>                                                