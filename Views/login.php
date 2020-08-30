<form action="{BASE}home/login" method="post" class="form-horizontal">
    <legend>Please sign in</legend>
    <div class="form-group">
        <label for="username" class="col-lg-3 control-label">Username</label>
        <div class="col-lg-3">
            <input type="text" class="form-check-input" name="username" id="username" placeholder="username" required autofocus>
        </div>
    </div>
    <div class="form-group">
        <label for="password" class="col-lg-3 control-label">Password</label>
        <div class="col-lg-3">
            <input type="password" class="form-check-input" name="password" id="password" placeholder="password" required>
        </div>
    </div>
    <div class="form-group">
        <label for="remember" class="col-lg-3 control-label">Remember me</label>
        <div class="col-lg-3">
            <input name="remember" id="remember" type="checkbox" value="remember-me">
        </div>
    </div>
    {RECAPTCHA}
    <button name="submit" value="submit" type="submit" class="btn btn-lg btn-primary center-block">Sign in</button>
    <input type="hidden" name="token" value="{TOKEN}">
</form><br>
Don't have an account? You can register <a href="{BASE}register">here</a>.<br>
if you need assistance with your username or password, please <a href="{BASE}register/recover">Click here</a>.