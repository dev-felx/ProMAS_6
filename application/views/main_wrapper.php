<?php
/*
 * Author: Tesha Evance
 * Description: Generic work space for all users. Content loaded from links in sidebar
 * Comment: Contact author on edit
 */


//load the side bar
if(isset($sidebar) && !empty($sidebar)){
    $this->load->view($sidebar);
    $no_side_bar = FALSE;
} else {
    $no_side_bar = TRUE;
 }
?>


    <div id="work_space"  class="col-sm-10">
        <?php
        //load the appropriate landing page base on user type
        foreach ($views as $value) {
            $this->load->view($value);
        }
        ?>
    </div>

    
    
<?php  
    //change styles if no side bar
    if($no_side_bar == TRUE){ ?>
    <script> 
        $('#work_space').removeClass('col-sm-9');
        $('#work_space').addClass('col-sm-12');
    </script>
<?php } ?>


