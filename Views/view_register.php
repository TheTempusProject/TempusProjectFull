<form action="" method="post" class="form-horizontal">
    <legend>Register</legend>
    <fieldset>
        <div class="form-group">
            <label for="username" class="col-lg-6 control-label">Username:</label>
            <div class="col-lg-2">
                <input class="form-control" type="text" name="username" id="username">
            </div>
        </div>
        <div class="form-group">
            <label for="email" class="col-lg-6 control-label">Email:</label>
            <div class="col-lg-2">
                <input class="form-control" type="email" name="email" id="email">
            </div>
        </div>
        <div class="form-group">
            <label for="email2" class="col-lg-6 control-label">Re-Enter Email:</label>
            <div class="col-lg-2">
                <input class="form-control" type="email" name="email2" id="email2">
            </div>
        </div>
        <div class="form-group">
            <label for="password" class="col-lg-6 control-label">Password:</label>
            <div class="col-lg-2">
                <input class="form-control" type="password" name="password" id="password">
            </div>
        </div>
        <div class="form-group">
            <label for="password2" class="col-lg-6 control-label">Re-Enter Password:</label>
            <div class="col-lg-2">
                <input class="form-control" type="password" name="password2" id="password2">
            </div>
        </div>
        <div class="form-group">
    	    <center>
    	        I have read and agree to the Terms of Service 
    			<input type="checkbox" name="terms" id="terms" value="1"/>
    			{TERMS}
    		</center>
        </div>
    </fieldset>
    <input type="hidden" name="token" value="{TOKEN}">
	<button name="submit" value="submit" type="submit" class="btn btn-lg btn-primary center-block">Sign up</button><br>
</form>