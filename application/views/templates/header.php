
<?php no_cache(); ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Meta -->  
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Title -->
    <title><?php if(isset($title)){ echo $title;}else{echo 'sProMAS';} ?></title>

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
      <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="container-fluid">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="<?php echo site_url(); ?>/home">sProMAS</a>
                    </div>

          <!-- Collect the nav links, forms, and other content for toggling -->
          <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1" >
                        <ul class="nav navbar-nav navbar-right"> 
                           <li><a href="<?php echo site_url('archive/access/switcher'); ?>">Archive Home</a></li>
                           <li class="dropdown" id="notification">
                               <a  href="#" class="dropdown-toggle" data-toggle="dropdown">Notifications<span class="badge push_left_bit badge_nav_ie"><?php echo count(unread_not()); ?></span></a>
                               <ul class="dropdown-menu" id='head_not'>
                                    <?php 
                                    if(count(unread_not())!= 0){
                                        $i = 1;
                                        foreach (unread_not() as $value) {
                                            $tito_not = $value['date_created'];
                                            $desc_not = $value['description'];
                                            $gl = $value['glph'];
                                            echo "<li class='not_li'><div class='tito_nt'><span class='glyphicon glyphicon-calendar push_right_bit'></span>$tito_not.</div>\n<div class='desc_not'><span class='push_right_bit glyphicon glyphicon-$gl'</span>$desc_not</div></li>";
                                            if($i <= 4){
                                                $i = $i + 1;
                                                continue;
                                            }else{
                                                break;
                                            }   
                                        } 
                                    }else{
                                        echo '<div class="text-center text-info top_10 bottom_10">No new notification</div>';
                                    }
                                    ?>
                                    <li class="divider"></li>
                                    <div class="text-center top_10"><a href="<?php echo site_url(); ?>/project/notify">See all notification</a></div>
                                </ul>
                            </li>
                            <li class="dropdown" id="announcement">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Announcements<span class="badge push_left_bit badge_nav_ie "><?php echo count(unread_ann()); ?></span></a>
                                <ul class="dropdown-menu" id='head_ann'>
                                    <?php 
                                    if(count(unread_ann())!= 0){
                                        $i = 1;
                                        foreach (unread_ann() as $value) {
                                            $role = ucfirst($value['creator_role']);
                                            $tito = $value['ann_title'];
                                            $desc = $value['description'];
                                            $time = substr($value['date_created'],0,10);
                                            echo "<li class='ann_li'><div>$role.<span class='pull-right'>$time<span></div>"."\n"."<div class='tito'>$tito.<span class='glyphicon glyphicon-chevron-down pull-right push_right_bit'></span></div>"."\n"."<div class='desc'>$desc</div></li>";
                                            if($i <= 4){
                                                $i = $i + 1;
                                                continue;
                                            }else{
                                                break;
                                            } 
                                            
                                        } 
                                    }else{
                                        echo '<div class="text-center text-info top_10 bottom_10">No new announcements</div>';
                                    }
                                    ?>
                                    <li class="divider"></li>
                                    <div class="text-center top_10"><a href="<?php echo site_url(); ?>/project/announce">See all announcement</a></div>
                                </ul>
                            </li>
                            <li class="dropdown">
                                <a  href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon push_right_bit glyphicon-user hidden-xs"></span><?php echo $this->session->userdata['fname']; ?><b class="caret hidden-xs"></b><span class="glyphicon glyphicon-chevron-down pull-right visible-xs"></span></a>
                                <ul class="dropdown-menu">
                                    <li><a href="<?php echo site_url(); ?>/manage_users/profile"><span class="glyphicon push_right_bit glyphicon-user"></span>My profile<span class="glyphicon glyphicon-chevron-right pull-right visible-xs"></span></a></li>
                                    <li><a href="<?php echo site_url(); ?>/access/password/change_pass_profile"><span class="glyphicon push_right_bit glyphicon-pencil"></span>Change password<span class="glyphicon glyphicon-chevron-right pull-right visible-xs"></span></a></li>                                   
                                    <li class="divider"></li>
                                    <li class="hidden-xs"><a href="<?php echo site_url(); ?>/access/logout"><span class="glyphicon push_right_bit glyphicon-off"></span>Logout<span class="glyphicon glyphicon-chevron-right pull-right visible-xs"></span></a></li>                                    
                                </ul>                        
                            </li>
                            
                        </ul>
                        <a  href="#" class="btn btn-info navbar-btn btn-block visible-xs" role="button">Logout</a>
                      </div><!-- /nav-bar-collapse -->
            </div>
        </nav>
      <script>
          $('.desc').hide();
          $('.ann_li').on('mouseenter', function () {
            $(this).children('.desc').slideToggle(500);
            return false;
            });
          $('.ann_li').on('mouseleave', function () {
            $(this).children('.desc').hide();
            return false;
            });
            
            
          $('.desc_not').hide();
          $('.not_li').on('mouseenter', function () {
            $(this).children('.desc_not').slideToggle(500);
            return false;
            });
          $('.not_li').on('mouseleave', function () {
            $(this).children('.desc_not').hide();
            return false;
            });
            
           $('ul.nav li.dropdown').hover(function() {
              $(this).addClass('active');
              $(this).find('.dropdown-menu').stop(true, true).fadeIn(400);
            }, function() {
              $(this).removeClass('active');
              $(this).find('.dropdown-menu').stop(true, true).fadeOut(100);
            });
          
      </script>
