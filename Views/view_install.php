<form action="" method="post" class="form-horizontal">
    <legend>Install</legend>
    <fieldset>
    <div class="form-group">
        <label for="dbHost" class="col-lg-6 control-label">Database Host:</label>
        <div class="col-lg-2">
            <input class="form-control" type="text" name="dbHost" id="dbHost" value="{dbHost}">
        </div>
    </div>
    <div class="form-group">
        <label for="dbName" class="col-lg-6 control-label">Database Name:</label>
        <div class="col-lg-2">
            <input class="form-control" type="text" name="dbName" id="dbName" value="{dbName}">
        </div>
    </div>
    <div class="form-group">
        <label for="dbUsername" class="col-lg-6 control-label">Database Username:</label>
        <div class="col-lg-2">
            <input class="form-control" type="text" name="dbUsername" id="dbUsername" value="{dbUsername}">
        </div>
    </div>
    <div class="form-group">
        <label for="dbPassword" class="col-lg-6 control-label">Database Password:</label>
        <div class="col-lg-2">
            <input class="form-control" type="password" name="dbPassword" id="dbPassword">
        </div>
    </div>
    <div class="form-group">
        <label for="siteName" class="col-lg-6 control-label">Site Name:</label>
        <div class="col-lg-2">
            <input class="form-control" type="text" name="siteName" id="siteName" value="{name}">
        </div>
    </div>
    <div class="form-group">
        <label for="newUsername" class="col-lg-6 control-label">User Username:</label>
        <div class="col-lg-2">
            <input class="form-control" type="text" name="newUsername" id="newUsername" value="{user}">
        </div>
    </div>
    <div class="form-group">
        <label for="email" class="col-lg-6 control-label">User Email:</label>
        <div class="col-lg-2">
            <input class="form-control" type="email" name="email" id="email" value="{email}">
        </div>
    </div>
    <div class="form-group">
        <label for="email2" class="col-lg-6 control-label">Re-enter Email:</label>
        <div class="col-lg-2">
            <input class="form-control" type="email" name="email2" id="email2" value="{email2}">
        </div>
    </div>
    <div class="form-group">
        <label for="password" class="col-lg-6 control-label">User Password:</label>
        <div class="col-lg-2">
            <input class="form-control" type="password" name="password" id="password">
        </div>
    </div>
    <div class="form-group">
        <label for="password2" class="col-lg-6 control-label">re-enter Site Password:</label>
        <div class="col-lg-2">
            <input class="form-control" type="password" name="password2" id="password2">
        </div>
    </div>
    </fieldset>
    <input type="hidden" name="token" value="{TOKEN}">
    <button class="btn btn-lg btn-primary center-block" type="submit" name="submit" value="submit">Install</button><br>
</form>