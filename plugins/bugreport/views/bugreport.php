<legend>Bug Report</legend>
<p>Thank you for visiting Our bug reporting page. We value our users' input highly and in an effort to better serve your needs, please fill out the form below to help us address this issue.</p>
<p>We read each and every bug report submitted, and by submitting this form you allow us to send you a follow up email.</p>
<form action="" method="post" class="form-horizontal">
    <label for="url">Page you were trying to reach:</label>
    <input type="url" name="url" id="url" class="form-control" aria-describedby="urlHelp">
    <p id="urlHelp" class="form-text text-muted">
        What is the URL of the page you actually received the error on? (The URL is the website address. Example: {BASE}home)
    </p>
    <label for="ourl">Page you were on:</label>
    <input type="url" name="ourl" id="ourl" class="form-control" aria-describedby="ourlHelp">
    <p id="ourlHelp" class="form-text text-muted">
        What is the URL of the page you were on before you received the error? (The URL is the website address. Example: {BASE}home/newhome)
    </p>
    <label for="repeat">*Has this happened more than once?</label>
    <div class="form-check">
        <label class="form-check-label">
            <input class="form-check-input" type="radio" name="repeat" id="repeat" value="false" checked>
            No
        </label>
    </div>
    <div class="form-check">
        <label class="form-check-label">
            <input class="form-check-input" type="radio" name="repeat" id="repeat" value="true">
            Yes
        </label>
    </div>
    <div class="form-group">
        <label for="entry" class="col-lg-3 control-label">Describe the problem/error as best as you can: (max:2000 characters)</label>
        <div class="col-lg-6">
            <textarea class="form-control" name="entry" maxlength="2000" rows="10" cols="50" id="entry"></textarea>
        </div>
    </div>
    <input type="hidden" name="token" value="{TOKEN}">
    <button name="submit" value="submit" type="submit" class="btn btn-lg btn-primary center-block">Submit</button>
</form>