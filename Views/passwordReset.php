<form action="" method="post" class="form-horizontal">
    <legend>Change Password</legend>
    <fieldset>
        <div class="form-group">
            <label for="password" class="col-lg-3 control-label">New Password:</label>
            <div class="col-lg-3">
                <input class="form-control" type="password" name="password" id="password">
            </div>
        </div>
        <div class="form-group">
            <label for="password2" class="col-lg-3 control-label">Re-Type New Password:</label>
            <div class="col-lg-3">
                <input class="form-control" type="password" name="password2" id="password2">
            </div>
        </div>
    </fieldset>
    <input type="hidden" name="resetCode" value="{resetCode}">
    <input type="hidden" name="token" value="{TOKEN}">
    <button name="submit" value="submit" type="submit" class="btn btn-lg btn-primary center-block">Submit</button><br>
</form>