<form id="pres_form" class="form-horizontal" role="form">
    <div id="msg_grp"></div>
    <input name="form_id" type="hidden">
    <div class="form-group">
        <label for="title" class="col-sm-4 control-label">Project Title</label>
        <div class="col-sm-6">
            <p id="title" name="title" class="form-control-static">Project Title</p>
        </div>
        <label for="title" class="col-sm-4 control-label">Presentation type</label>
        <div class="col-sm-6">
            <p id="type" class="form-control-static"></p>
        </div>
        <label for="title" class="col-sm-4 control-label">Semester</label>
        <div class="col-sm-6">
            <p id="sem" class="form-control-static"></p>
        </div>
    </div>
    
    <div class="bottom_10 col-sm-10 col-sm-offset-1"><hr /></div>
    
    <div class="form-group">
        <label for="im" class="col-sm-4 control-label">Implementation Methodology</label>
        <div class="col-sm-6">
            <input name="im" id="im" type="text" class="form-control" placeholder="(Implementation Methodology) /7">
        </div>
    </div>
    
    <div class="form-group">
        <label for="sf" class="col-sm-4 control-label">System Functionalities</label>
        <div class="col-sm-6">
            <input name="sf" id="sf" type="text" class="form-control" placeholder="(System Functionalities) /10">
        </div>
    </div>
    
    <div class="form-group">
        <label for="sc" class="col-sm-4 control-label">System Correctness</label>
        <div class="col-sm-6">
            <input name="sc" id="sc" type="text" class="form-control" placeholder="(System Correctness) /15">
        </div>
    </div>
    
    <div class="form-group">
        <label for="pq" class="col-sm-4 control-label">Presentation Quality</label>
        <div class="col-sm-6">
            <input name="pq" id="pq" type="text" class="form-control" placeholder="(Presentation Quality) /8">
        </div>
    </div>
    
    <div class="form-group">
        <label for="ptc" class="col-sm-4 control-label">Presentation Time Compliance</label>
        <div class="col-sm-6">
            <input name="ptc" id="ptc" type="text" class="form-control" id="inputEmail3" placeholder="(Presentation Time Compliance) /5">
        </div>
    </div>
    
    <div class="form-group">
        <label for="com" class="col-sm-4 control-label">Comments</label>
        <div class="col-sm-6">
            <textarea name="com" id="com" class="form-control" rows="3"></textarea>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-2 col-sm-offset-8">
            <button id="sav_form" type="button" class="btn btn-success pull-right">Save</button>
        </div>
    </div>
</form>
