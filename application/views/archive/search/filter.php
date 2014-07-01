<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="list-group" id="maincon">
    <?php if(!empty($res)){?>
        <table id="project_list"  width="100%">
            <thead>
                <tr>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($res as $v){?>
                <tr>    
                    <td><a href="<?php echo site_url(); ?>/archive/archive/profile/<?php print $v->project_profile_id ?>" class="list-group-item">
                        <h4 class="list-group-item-heading"><?php print $v->project_name ?></h4>
                        <p class="list-group-item-text"><?php print $v->description ?></p>
                        </a>
                    </td>
                </tr>
            <?php }
    }else {?>
        <p>No Result Found</p>
    <?php } ?>      
            </tbody>
        </table>
</div>