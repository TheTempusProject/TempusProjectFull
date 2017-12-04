<form action="" method="post" class="form-horizontal">
    <legend>Reply</legend>
        <fieldset>
        <div class="form-group">
            <label for="message" class="col-lg-3 control-label">Message:</label>
            <div class="col-lg-6">
                <textarea class="form-control" name="message" maxlength="2000" rows="10" cols="50" id="message"></textarea>
            </div>
        </div>
    </fieldset>
    <input type="hidden" name="messageID" value="{messageID}">
    <input type="hidden" name="token" value="{TOKEN}">
    <button name="submit" value="submit" type="submit" class="btn btn-lg btn-primary center-block">Send</button><br>
</form>