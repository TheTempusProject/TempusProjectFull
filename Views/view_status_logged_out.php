<form method="post" action="{BASE}home/login" id="signin" class="navbar-form navbar-right" role="form">
    <input type="hidden" name="rurl" id="rurl" value="{RURL}">
    <input type="hidden" name="token" value="{TOKEN}">
    <input id="username" type="text" class="form-control" name="username" value="" placeholder="Username">
    <div class="input-group">
        <input id="password" type="password" class="form-control" name="password" value="" placeholder="Password">
        <span class="input-group-addon">
            <input type="checkbox" name="remember" id="remember">
        </span>
    </div>
    <button type="submit" class="btn btn-primary" name="submit" value="submit">Sign in</button>
</form>