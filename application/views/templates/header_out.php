<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Meta -->  
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Title -->
    <title><?php if(isset($title)){ echo $title;}else{echo 'ProMAS';} ?></title>

    <!-- Style-sheets -->
    <link type="text/css" rel="Stylesheet" href="<?php echo base_url(); ?>assets/bootstrap/css/bootstrap.css">
    <link type="text/css" rel="Stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap_tweaks.css" >
    <link type="text/css" rel="Stylesheet" href="<?php echo base_url(); ?>assets/css/main.css" >
        
    <!-- Scripts -->
    <script src="<?php echo base_url(); ?>assets/jquery/jquery-1.11.0.js"></script>
    <script src="<?php echo base_url(); ?>assets/bootstrap/js/bootstrap.js"></script>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    
  </head>
  <body>
      <div class=" row ">
          <nav class=" navbar-inverse navbar navbar-fixed-top" role="navigation">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="container-fluid " >
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                    </button>
                    <a style="font-size: 170%" class=" navbar-brand" href="#">sProMAS</a>
                    <p style="font-size: 110%; color: white" class="navbar-text">Students Project Management and Archiving System</p>
                </div>
                <?php if(!isset($this->session->userdata['user_id'])){ ?>
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1" >
                       
                    <ul class="nav navbar-nav navbar-right">                            
                        <li><a href="<?php echo site_url(); ?>/access/login">Sign In</a></li>
                    </ul>
                </div>
                <?php } ?>
            </div>
        </nav>
          </div>
