<form id="grp_form" class="form-horizontal" role="form">
    <div id="msg_grp"></div>
    <input type="hidden" id="form_id" name="form_id" class="form-control ">
    <div class="form-group">
        <label for="inputEnd Date" class="col-sm-4 control-label">Project Title</label>
        <div class="col-sm-7">
            <p id="title" class="form-control-static">title</p>
        </div>

        <label for="inputWeek No" class="col-sm-4 control-label">Report type</label>
        <div class="col-sm-7">
            <p id="type_" class="form-control-static">type</p>
        </div>
        
        <label for="inputWeek No" class="col-sm-4 control-label">Semester</label>
        <div class="col-sm-7">
            <p id="sem" class="form-control-static">semester</p>
        </div>
    </div>
    <div class="col-sm-10 col-sm-offset-1 bottom_10"><hr/></div>
    <div class="form-group">
        <label for="inputAbstract" class="col-sm-4 control-label">Abstract</label>
        <div class="col-sm-7">
            <input name="abs" class="form-control" id="inputEmail3" placeholder="(Abstract) /3">
        </div>
    </div>
    <div class="form-group">
        <label for="inputAcknowledgment" class="col-sm-4 control-label">Acknowledgment</label>
        <div class="col-sm-7">
            <input name="ack" class="form-control" id="inputEmail3" placeholder="(Acknowledgment) /2">
        </div>
    </div>
    <div class="form-group">
        <label for="inputContents" class="col-sm-4 control-label">Table Of Contents</label>
        <div class="col-sm-7">
            <input name="con" class="form-control" id="inputEmail3" placeholder="(Contents) /3">
        </div>
    </div>
    <div class="form-group">
        <label for="inputGeneral Introduction" class="col-sm-4 control-label">General Introduction</label>
        <div class="col-sm-7">
            <input name="intro" class="form-control" id="inputEmail3" placeholder="(General Introduction) /4">
        </div>
    </div>
    <div class="form-group">
        <label for="inputMain Body" class="col-sm-4 control-label">Main Body</label>
        <div class="col-sm-7">
            <input name="main" class="form-control" id="inputEmail3" placeholder="Main Body(contents and presentation) /15">
        </div>
    </div>
    <div class="form-group">
        <label for="inputMain Body" class="col-sm-4 control-label">References</label>
        <div class="col-sm-7">
            <input name="ref" class="form-control" id="inputEmail3" placeholder="References(Bibliography) /3">
        </div>
    </div>
    <div class="form-group">
        <label for="inputComments" class="col-sm-4 control-label">Comments</label>
        <div class="col-sm-7">
            <textarea name="com" class="form-control" rows="3"></textarea>
        </div>
    </div>
    <div class="form-group">
         <div class="col-sm-2 col-sm-offset-9">
             <button id="sav_form" type="button" class="btn btn-success pull-right">Submit</button>
        </div>
    </div>
</form>