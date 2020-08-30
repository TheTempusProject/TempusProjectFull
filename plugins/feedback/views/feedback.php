<form action="" method="post" class="form-horizontal">
    <legend>Feedback</legend>
    <p>Here at {SITENAME} we highly value your feedback. We constantly strive to provide our users with the highest level of quality in everything we do.</p>
	<p>If you would like to provide any suggestions or comments on our service, we ask that you please fill out the quick form below and let us know what's on your mind.</p>
    <fieldset>
    <div class="form-group">
        <label for="name" class="col-lg-1 control-label">Name:</label>
        <div class="col-lg-2">
            <input class="form-control" type="text" name="name" id="name">
        </div>
    </div>
    <div class="form-group">
        <label for="feedbackEmail" class="col-lg-1 control-label">E-mail: (optional)</label>
        <div class="col-lg-2">
            <input class="form-control" type="text" name="feedbackEmail" id="feedbackEmail">
        </div>
    </div>
    <div class="form-group">
        <label for="entry" class="col-lg-3 control-label">Feedback:<br> (max:2000 characters)</label>
        <div class="col-lg-6">
            <textarea class="form-control" name="entry" maxlength="2000" rows="10" cols="50" id="entry"></textarea>
        </div>
    </div>
    </fieldset>
    <input type="hidden" name="token" value="{TOKEN}">
	<button name="submit" value="submit" type="submit" class="btn btn-lg btn-primary center-block">Submit</button><br>
</form>