<form action="" method="post" class="form-horizontal">
    <legend>Change Password</legend>
    <p>Please enter the code you recieved in your email. If you did not recieve the code, please check your spam filter or <a href="{BASE}">click here</a> to request a new reset code.</p>
    <fieldset>
        <div class="form-group">
            <label for="resetCode" class="col-lg-3 control-label">Reset Code</label>
            <div class="col-lg-3">
                <input class="form-control" type="text" name="resetCode" id="resetCode">
            </div>
        </div>
    </fieldset>
    <input type="hidden" name="token" value="{TOKEN}">
    <button name="submit" value="submit" type="submit" class="btn btn-lg btn-primary center-block">Submit</button><br>
</form>