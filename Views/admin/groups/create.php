<form action="" method="post" class="form-horizontal">
    <legend>New Group</legend>
    <div class="form-group">
        <label for="name" class="col-lg-3 control-label">Name</label>
        <div class="col-lg-3">
            <input type="text" class="form-check-input" name="name" id="name">
        </div>
    </div>
    <div class="form-group">
        <label for="pageLimit" class="col-lg-3 control-label">Query Limit</label>
        <div class="col-lg-2">
            <select name="pageLimit" id="pageLimit" class="form-control">
                <option value='5'>5</option>
                <option value='10'>10</option>
                <option value='25' selected>25</option>
                <option value='50'>50</option>
                <option value='75'>75</option>
                <option value='100'>100</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="sendMessages" class="col-lg-3 control-label">Send Messages</label>
        <div class="col-lg-3">
            <input name="sendMessages" id="sendMessages" type="checkbox" value="true" checked>
        </div>
    </div>
    <div class="form-group">
        <label for="uploadImages" class="col-lg-3 control-label">Upload Images</label>
        <div class="col-lg-3">
            <input name="uploadImages" id="uploadImages" type="checkbox" value="true" checked>
        </div>
    </div>
    <div class="form-group">
        <label for="feedback" class="col-lg-3 control-label">Send Feedback</label>
        <div class="col-lg-3">
            <input name="feedback" id="feedback" type="checkbox" value="true" checked>
        </div>
    </div>
    <div class="form-group">
        <label for="bugreport" class="col-lg-3 control-label">Submit Bug Reports</label>
        <div class="col-lg-3">
            <input name="bugreport" id="bugreport" type="checkbox" value="true" checked>
        </div>
    </div>
    <div class="form-group">
        <label for="member" class="col-lg-3 control-label">Member Access</label>
        <div class="col-lg-3">
            <input name="member" id="member" type="checkbox" value="true">
        </div>
    </div>
    <div class="form-group">
        <label for="modCP" class="col-lg-3 control-label">Moderator Privileges</label>
        <div class="col-lg-3">
            <input name="modCP" id="modCP" type="checkbox" value="true">
        </div>
    </div>
    <div class="form-group">
        <label for="adminCP" class="col-lg-3 control-label">Administrator Privileges</label>
        <div class="col-lg-3">
            <input name="adminCP" id="adminCP" type="checkbox" value="true">
        </div>
    </div>
    <button name="submit" value="submit" type="submit" class="btn btn-lg btn-primary center-block">Create</button>
    <input type="hidden" name="token" value="{TOKEN}">
</form>