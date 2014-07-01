<?php 
/*Author : Minja Junior
 * 
 */
?>
<html lang="en">
    <head id="head">
        <title>sProMAS Archive</title>
        
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link type="text/css" rel="Stylesheet" href="<?php echo base_url(); ?>assets/bootstrap/css/bootstrap.css" media="screen">
        <link type="text/css" rel="Stylesheet" href="<?php echo base_url(); ?>assets/css/main.css" media="screen">
        
        <script src="<?php echo base_url(); ?>assets/jquery/jquery-1.11.0.js"></script>
        <!--<script src="<?php // echo base_url(); ?>assets/jquery/jquery_1.5.2.js" type="text/javascript"></script>-->
        <script type="text/javascript">
            $(document).ready(function() {
                $("#search_key").on("keyup",function() {
                    var search_key = $("#search_key").val();
                    var response_brought = $("#response_brought");
                    var dataString = "search_key=" + search_key;
		
                    if(search_key.length < 1) {
			$("#hide_or_show_search_results_box").hide();
                    }
                    else if(search_key.length > 0 && search_key.length <= 30) {	
			$.ajax({  
                            type: "POST",  
                            url: '<?php echo site_url(); ?>/archive/archive/suggestions/', 
                            data: dataString,
                            beforeSend: function() {
				$("#hide_or_show_search_results_box").show();
				$("#response_brought").html('<img src="<?php echo base_url(); ?>assets/images/loading.gif" align="absmiddle" alt="Searching '+search_key+'..."> Searching...');
                            },  
                            success: function(response){
				$("#hide_or_show_search_results_box").show();
				$("#response_brought").html(response);
                            }
			}); 
                    }
                    return false;
                });  
            });
        </script>
    </head>
    <body data-twttr-rendered="true">

        <div id="arch_head" class="col-sm-12">
            <div class="container-fluid">
                <div id="login_cont" class="col-sm-2 pull-right" >
                    <?php if($this->session->userdata('user_id_arch') == '' && $this->session->userdata('user_id') == ''){ ?>
                        <span id="arch_name" class="pull-right push_left_bit">Login</span>
                    <?php }else{ ?>
                        <span id="arch_name" class="pull-right push_left_bit"><?php echo $this->session->userdata('fname').' '.$this->session->userdata('fname'); ?></span>
                    <?php } ?>    
                    <button class="btn btn-cyc pull-right " ><span class="glyphicon glyphicon-user"></span></button>
                </div>
                <div class="pull-left">
                    <button class="btn btn-cyc">
                            <span class="glyphicon glyphicon-link"></span></button>
                        <a href="<?php echo site_url('home'); ?>">Switch to Management</a>
                </div>
            </div>
        </div>
        <div id="slide_box" class="col-sm-3 col-sm-offset-9">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title text-center">sProMAS Archive Login</h3>
                </div>
                <div class="panel-body">
                    <?php if($this->session->userdata('user_id_arch') == '' && $this->session->userdata('user_id') == ''){ ?>
                    <form id="login_form" role="form" action="<?php echo site_url('/archive/access/login'); ?>" method="POST">
                        <div id="msg_log"></div>
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
                                <input name="username" type="text" class="form-control" placeholder="Enter email">                
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
                                <input name="pass" type="text" class="form-control" placeholder="Enter your password">                
                            </div>
                        </div>
                        <div class="form-group">
                            <button id="login_btn" class="btn btn-info btn-block">Login</button>
                        </div>
                    </form> 
                    <?php }else { ?>
                    <a href="<?php echo site_url('/archive/access/logout') ?>" class="btn btn-primary btn-block">Logout</a>
                    <?php } ?>
                </div>
                <div class="panel-footer">
                    <a href="<?php echo site_url('/access/login'); ?>" class="pull-left">Use Management Account</a>
                    <?php if($this->session->userdata('user_id_arch') == '' && $this->session->userdata('user_id') == ''){ ?>
                        <a href="#" class="pull-right">Recover Password</a>
                    <?php }else{ ?>
                        <a href="#" class="pull-right">Change Password</a>
                    <?php } ?>
                    <div class="clearfix"></div>
                </div>
            </div> 
        </div>
        <script>
            $('#slide_box').hide();
            $('#login_cont').click(function(){
                $('#slide_box').slideToggle();
            });
            $(document).ready(function(){
                $('#login_btn').click(function(){
                    $('#msg_log').html('<div class="alert alert-info"><img style="height: 30px;" class="col-sm-offset-4 push_right_bit" src="<?php echo base_url(); ?>/assets/images/ajax-loader.gif">Logging in...</div>');
                    setTimeout(function(){
                        var t = "<?php echo site_url(); ?>";
                        var c = t+"/archive/access/login";
                        $.post( c, $("#login_form").serialize()).done(function(data) {
                            if(data.status == 'false'){
                                $('#msg_log').html('<div class="alert alert-danger">'+data.data+'</div>');
                            }else if(data.status == 'true'){
                              window.location.reload();
                            }
                        },'json');
                    },400);
                    return false;
                });
            });
        </script>