<legend>Settings</legend>
<form action="" method="post" class="form-horizontal">
    <div class="form-group">
        <label for="name" class="col-lg-3 control-label">Site Name:</label>
        <div class="col-lg-3">
            <input type="text" class="form-check-input" name="name" id="name" value="{NAME}">
        </div>
    </div>
    <div class="form-group">
        <label for="template" class="col-lg-3 control-label">Template:</label>
        <div class="col-lg-3">
            <input type="text" class="form-check-input" name="template" id="template" value="{TEMPLATE}">
        </div>
    </div>
    <div class="form-group">
        <label for="loginLimit" class="col-lg-3 control-label">Login Limit::</label>
        <div class="col-lg-3">
            <input type="text" class="form-check-input" name="loginLimit" id="loginLimit" value="{LIMIT}">
        </div>
    </div>
    <div class="form-group">
        <label for="groupSelect" class="col-lg-3 control-label">Default group for new users:</label>
        <div class="col-lg-2">
            {OPTION=userGroup}
            {groupSelect}
        </div>
    </div>
    <div class="form-group">
        <label for="logF" class="col-lg-3 control-label">Feedback</label>
        <div class="col-lg-3">
            <fieldset class="form-group">
                <div class="form-check">
                    <label class="form-check-label">
                        <input type="radio" class="form-check-input" name="logF" id="logF" value="true" {CHECKED:feedback=true}>
                        Enabled
                    </label>
                </div>
                <div class="form-check">
                    <label class="form-check-label">
                        <input type="radio" class="form-check-input" name="logF" id="logF" value="false" {CHECKED:feedback=false}>
                        Disabled
                    </label>
                </div>
            </fieldset>
        </div>
    </div>
    <div class="form-group">
        <label for="logE" class="col-lg-3 control-label">Errors</label>
        <div class="col-lg-3">
            <fieldset class="form-group">
                <div class="form-check">
                    <label class="form-check-label">
                        <input type="radio" class="form-check-input" name="logE" id="logE" value="true" {CHECKED:errors=true}>
                        Enabled
                    </label>
                </div>
                <div class="form-check">
                    <label class="form-check-label">
                        <input type="radio" class="form-check-input" name="logE" id="logE" value="false" {CHECKED:errors=false}>
                        Disabled
                    </label>
                </div>
            </fieldset>
        </div>
    </div>
    <div class="form-group">
        <label for="logL" class="col-lg-3 control-label">Logins</label>
        <div class="col-lg-9">
            <fieldset class="form-group">
                <div class="form-check">
                    <label class="form-check-label">
                        <input type="radio" class="form-check-input" name="logL" id="logL" value="true" {CHECKED:logins=true}>
                        Enabled
                    </label>
                </div>
                <div class="form-check">
                    <label class="form-check-label">
                        <input type="radio" class="form-check-input" name="logL" id="logL" value="false" {CHECKED:logins=false}>
                        Disabled
                    </label>
                </div>
            </fieldset>
        </div>
    </div>
    <div class="form-group">
        <label for="logBR" class="col-lg-3 control-label">Bug Reports</label>
        <div class="col-lg-9">
            <fieldset class="form-group">
                <div class="form-check">
                    <label class="form-check-label">
                        <input type="radio" class="form-check-input" name="logBR" id="logBR" value="true" {CHECKED:bugReports=true}>
                        Enabled
                    </label>
                </div>
                <div class="form-check">
                    <label class="form-check-label">
                        <input type="radio" class="form-check-input" name="logBR" id="logBR" value="false" {CHECKED:bugReports=false}>
                        Disabled
                    </label>
                </div>
            </fieldset>
        </div>
    </div>
    <button name="submit" value="submit" type="submit" class="btn btn-lg btn-primary center-block">Submit</button>
    <input type="hidden" name="token" value="{TOKEN}">
</form>