<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
        <?php $this->load->view('archive/search/header'); ?>
        <div class="container-fluid" style="z-index: 10">
            <div class="row" style="margin-top: 50px;">
                <img src="<?php echo base_url(); ?>assets/images/pro.jpg" alt="sProMAS Archive" class="img-rounded col-xs-12 col-sm-4 col-sm-offset-4">
                <div class="col-xs-12 col-sm-4 col-sm-offset-4" style="margin-top: 50px;">
                    <form role="form" action="<?php echo site_url(); ?>/archive/archive/search_result/" method="POST">
                        <div class="input-group">
                            <input type="text" name="search_key" id="search_key" class="form-control">
                                <span class="input-group-btn">
                                    <button class="btn btn-primary" type="submit">Search!</button>
                                </span>
                        </div><!-- /input-group -->
                        <div class="list-group" id="hide_or_show_search_results_box" align="left"><span id="response_brought"></span></div>
                    </form>
                    <a href="<?php echo site_url(); ?>/archive/archive/explore/" class="btn btn-primary col-xs-10 col-xs-offset-1 col-sm-4 col-sm-offset-4" role="button">Explore</a>
                </div>
            </div>
        </div>
        <?php $this->load->view('archive/search/footer'); ?>
        