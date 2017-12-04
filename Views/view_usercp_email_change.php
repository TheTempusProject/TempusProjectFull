<form action="" method="post" class="form-horizontal">
    <legend>Email Settings</legend>
    <fieldset>
        <div class="form-group">
            <label for="email" class="col-lg-3 control-label">New Email:</label>
            <div class="col-lg-2">
                <input class="form-control" type="email" name="email" id="email">
            </div>
        </div>
        <div class="form-group">
            <label for="email2" class="col-lg-3 control-label">Re-type email:</label>
            <div class="col-lg-2">
                <input class="form-control" type="email" name="email2" id="email2">
            </div>
        </div>
    </fieldset>
    <input type="hidden" name="token" value="{TOKEN}">
    <button name="submit" value="submit" type="submit" class="btn btn-lg btn-primary center-block">Update</button><br>
</form>