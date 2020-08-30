<legend>Settings</legend>
<form action="" method="post" class="form-horizontal" enctype="multipart/form-data">
    <div class="form-group">
        <label for="hash" class="col-lg-3 control-label">Install Hash</label>
        <div class="col-lg-3">
            <input type="text" class="form-check-input" name="hash" id="hash" value="{securityHash}">
        </div>
    </div>
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
        <label for="timezone" class="col-lg-3 control-label">Timezone:</label>
        <div class="col-lg-4">
            {OPTION=timezone}
            {TIMEZONELIST}
        </div>
    </div>
    <div class="form-group">
        <label for="logo" class="col-lg-3 control-label">Logo (200 x 200px):</label>
        <div class="col-lg-3">
            <input class="form-control" type="file" name="logo" id="logo">
        </div>
        <div class="col-md-3 col-lg-3 " align="center">
            <img alt="current logo" src="{BASE}{LOGO}" class="img-circle img-responsive avatar-125">
        </div>
    </div>
    <div class="form-group">
        <label for="pageLimit" class="col-lg-3 control-label">Results per page:</label>
        <div class="col-lg-2">
            {OPTION=pageLimit}
            <select class="form-control" name="pageLimit" id="pageLimit">
                <option value='5'>5</option>
                <option value='10'>10</option>
                <option value='15'>15</option>
                <option value='20'>20</option>
                <option value='25'>25</option>
                <option value='50'>50</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label>Security</label>
        <hr>
    </div>
    <div class="form-group">
        <label for="groupSelect" class="col-lg-3 control-label">Default group for new users:</label>
        <div class="col-lg-2">
            {OPTION=userGroup}
            {groupSelect}
        </div>
    </div>
    <div class="form-group">
        <label for="loginLimit" class="col-lg-3 control-label">Login Limit::</label>
        <div class="col-lg-3">
            <input type="text" class="form-check-input" name="loginLimit" id="loginLimit" value="{LIMIT}">
        </div>
    </div>
    <div class="form-group">
        <label for="cookieExpiry" class="col-lg-3 control-label">Default Cookie Duration:</label>
        <div class="col-lg-3">
            <input type="text" class="form-check-input" name="cookieExpiry" id="cookieExpiry" value="{cookieExpiry}">
        </div>
    </div>
    <div class="form-group">
        <label>Uploads</label>
        <hr>
    </div>
    <div class="form-group">
        <label for="uploads" class="col-lg-3 control-label">Uploads</label>
        <div class="col-lg-3">
            <fieldset class="form-group">
                <div class="form-check">
                    <label class="form-check-label">
                        <input type="radio" class="form-check-input" name="uploads" id="uploads" value="true" {CHECKED:uploads=true}>
                        Enabled
                    </label>
                </div>
                <div class="form-check">
                    <label class="form-check-label">
                        <input type="radio" class="form-check-input" name="uploads" id="uploads" value="false" {CHECKED:uploads=false}>
                        Disabled
                    </label>
                </div>
            </fieldset>
        </div>
    </div>
    <div class="form-group">
        <label for="fileSize" class="col-lg-3 control-label">Max file Size:</label>
        <div class="col-lg-3">
            <input type="text" class="form-check-input" name="fileSize" id="fileSize" value="{maxFileSize}">
        </div>
    </div>
    <div class="form-group">
        <label for="imageSize" class="col-lg-3 control-label">Max image size:</label>
        <div class="col-lg-3">
            <input type="text" class="form-check-input" name="imageSize" id="imageSize" value="{maxImageSize}">
        </div>
    </div>
    <div class="form-group">
        <label>Models:</label>
        <hr>
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
        <label for="recaptcha" class="col-lg-3 control-label">ReCaptcha</label>
        <div class="col-lg-9">
            <fieldset class="form-group">
                <div class="form-check">
                    <label class="form-check-label">
                        <input type="radio" class="form-check-input" name="recaptcha" id="recaptcha" value="true" {CHECKED:recaptcha=true}>
                        Enabled
                    </label>
                </div>
                <div class="form-check">
                    <label class="form-check-label">
                        <input type="radio" class="form-check-input" name="recaptcha" id="recaptcha" value="false" {CHECKED:recaptcha=false}>
                        Disabled
                    </label>
                </div>
            </fieldset>
        </div>
    </div>
    <div class="form-group">
        <label>Logging</label>
        <hr>
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
        <label>Recaptcha</label>
        <hr>
    </div>
    <div class="form-group">
        <label for="sendIP" class="col-lg-3 control-label">Send the Ip to google:</label>
        <div class="col-lg-3">
            <fieldset class="form-group">
                <div class="form-check">
                    <label class="form-check-label">
                        <input type="radio" class="form-check-input" name="sendIP" id="sendIP" value="true" {CHECKED:sendIP=true}>
                        Enabled
                    </label>
                </div>
                <div class="form-check">
                    <label class="form-check-label">
                        <input type="radio" class="form-check-input" name="sendIP" id="sendIP" value="false" {CHECKED:sendIP=false}>
                        Disabled
                    </label>
                </div>
            </fieldset>
        </div>
    </div>
    <div class="form-group">
        <label for="siteHash" class="col-lg-3 control-label">Site Key:</label>
        <div class="col-lg-3">
            <input type="text" class="form-check-input" name="siteHash" id="siteHash" value="{siteHash}">
        </div>
    </div>
    <div class="form-group">
        <label for="privateHash" class="col-lg-3 control-label">Private Hash:</label>
        <div class="col-lg-3">
            <input type="text" class="form-check-input" name="privateHash" id="privateHash" value="{privateHash}">
        </div>
    </div>
    <button name="submit" value="submit" type="submit" class="btn btn-lg btn-primary center-block">Submit</button>
    <input type="hidden" name="token" value="{TOKEN}">
</form>