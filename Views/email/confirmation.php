<form action="" method="post" class="form-horizontal">
    <legend>Email Confirmation</legend>
    <p>Please enter the confirmation code you recieved in your email.</p>
    <fieldset>
        <div class="form-group">
            <label for="confirmationCode" class="col-lg-3 control-label">Confirmation Code:</label>
            <div class="col-lg-3">
                <input class="form-control" type="text" name="confirmationCode" id="confirmationCode">
            </div>
        </div>
    </fieldset>
    <input type="hidden" name="token" value="{TOKEN}">
    <button name="submit" value="submit" type="submit" class="btn btn-lg btn-primary center-block">Submit</button><br>
</form>