{installer-nav}
<br>
<br>
<form action="" method="post" class="form-horizontal">
    <fieldset>
    <div class="form-group">
        <label for="newUsername" class="col-lg-6 control-label">Username:</label>
        <div class="col-lg-2">
            <input class="form-control" type="text" name="newUsername" id="newUsername" value="">
        </div>
    </div>
    <div class="form-group">
        <label for="userEmail" class="col-lg-6 control-label">Email:</label>
        <div class="col-lg-2">
            <input class="form-control" type="email" name="userEmail" id="userEmail" value="">
        </div>
    </div>
    <div class="form-group">
        <label for="userEmail2" class="col-lg-6 control-label">Re-enter Email:</label>
        <div class="col-lg-2">
            <input class="form-control" type="email" name="userEmail2" id="userEmail2" value="">
        </div>
    </div>
    <div class="form-group">
        <label for="userPassword" class="col-lg-6 control-label">Password:</label>
        <div class="col-lg-2">
            <input class="form-control" type="password" name="userPassword" id="userPassword">
        </div>
    </div>
    <div class="form-group">
        <label for="userPassword2" class="col-lg-6 control-label">Re-enter Password:</label>
        <div class="col-lg-2">
            <input class="form-control" type="password" name="userPassword2" id="userPassword2">
        </div>
    </div>
    </fieldset>
    <input type="hidden" name="token" value="{TOKEN}">
    <button class="btn btn-lg btn-primary center-block" type="submit" name="submit" value="submit">Install</button><br>
</form>