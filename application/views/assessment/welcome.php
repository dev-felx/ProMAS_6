<style>
    .tab-pane{
        padding-top: 10px;
    }
</style>
<h3 class="text-center">Welcome to Assessment.</h3>
<hr/>
<div class="clearfix"></div>
<div id="setup" class="col-sm-10 col-sm-offset-1">
<h4 class="text-center text-warning">Assessment forms have not being created.</h4>
<div style="border: #ccc solid 1px; border-radius: 5px; padding: 5px;" class="bottom_10" >
    <form class="form-horizontal" action="<?php echo site_url('/assessment/assess/setup'); ?>" method="POST">
    <div class="col-sm-10">
        <h4 class="text-info">Assessment options</h4>
        <hr/>
     
            <div class="checkbox  bottom_10">
                <label>
                  Create events on my calender 
                  <input type="checkbox" name="event" value="1">
                </label>
                
            </div>
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="notif" value="1">
                  Remind me via system notifications
                </label>
            </div>
          
    </div>
    <div class="clearfix"></div>
    <br/>
    <button id="save" class="btn btn-primary col-sm-2 col-sm-offset-5">Create Forms</button>
     <div class="clearfix"></div>
</form> 
</div>
</div>


