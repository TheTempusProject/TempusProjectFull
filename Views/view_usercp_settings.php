<form action="" method="post" class="form-horizontal"  enctype="multipart/form-data">
    <legend>Preferences</legend>
    <fieldset>
    <div class="form-group">
        <label for="timeFormat" class="col-lg-3 control-label">Time Format:</label>
        <div class="col-lg-2">
            {OPTION=timeFormat}
            <select name="timeFormat" id="timeFormat" class="form-control">
                <option value='g:i:s A'>3:33:33 AM</option>
                <option value='h:i:s A'>03:33:33 AM</option>
                <option value='g:i:s a'>3:33:33 am</option>
                <option value='h:i:s a'>03:33:33 am</option>
                <option value='H:i:s'>03:33:33 (military)</option>
                <option value='G:i:s'>3:33:33 (military)</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="pageLimit" class="col-lg-3 control-label">Results per page:</label>
        <div class="col-lg-2">
            {OPTION=pageLimit}
            <select class="form-control" name="pageLimit" id="pageLimit">
                <option value='5'>5</option>
                <option value='10'>10</option>
                <option value='15'>15</option>
                <option value='20'>20</option>
                <option value='25'>25</option>
                <option value='50'>50</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="gender" class="col-lg-3 control-label">Gender:</label>
        <div class="col-lg-2">
            {OPTION=gender}
            <select class="form-control" name="gender" id="gender">
                <option value='male'>male</option>
                <option value='female'>female</option>
                <option value='other'>other</option>
                <option value='unspecified'>unspecified</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="dateFormat" class="col-lg-3 control-label">Date Format:</label>
        <div class="col-lg-2">
            {OPTION=dateFormat}
            <select class="form-control" name="dateFormat" id="dateFormat">
                <option value='n-j-Y'>1-8-1991</option>
                <option value='j-n-Y'>8-1-1991</option>
                <option value='m-d-Y'>01-08-1991</option>
                <option value='d-m-Y'>08-01-1991</option>
                <option value='F-j-Y'>January 8, 1991</option>
                <option value='j-F-Y'>8 January, 1991</option>
                <option value='F-d-Y'>January 08, 1991</option>
                <option value='d-F-Y'>08 January, 1991</option>
                <option value='M-j-Y'>Jan 8, 1991</option>
                <option value='j-M-Y'>8 Jan 1991</option>
                <option value='M-d-Y'>Jan 08, 1991</option>
                <option value='d-M-Y'>08 Jan 1991</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="timezone" class="col-lg-3 control-label">Timezone:</label>
        <div class="col-lg-4">
            {OPTION=timezone}
            {TIMEZONELIST}
        </div>
    </div>
    <div class="form-group">
        <label for="avatar" class="col-lg-3 control-label">Avatar</label>
        <div class="col-lg-3">
            <input class="form-control" type="file" name="avatar" id="avatar">
        </div>
        <div class="col-md-3 col-lg-3 " align="center">
            <img alt="User Pic" src="{BASE}{AVATAR_SETTINGS}" class="img-circle img-responsive avatar-125">
        </div>
    </div>
    <div class="form-group">
        <label for="updates" class="col-lg-3 control-label">Updates:</label>
        <div class="col-lg-2">
            <fieldset class="form-group">
                <div class="form-check">
                    <label class="form-check-label">
                        <input type="radio" class="form-check-input" name="updates" id="updates" value="true" {CHECKED:updates=true}>
                        Subscribed
                    </label>
                </div>
                <div class="form-check">
                    <label class="form-check-label">
                        <input type="radio" class="form-check-input" name="updates" id="updates" value="false" {CHECKED:updates=false}>
                        Unsubscribed
                    </label>
                </div>
          </fieldset>
        </div>
    </div>
    <div class="form-group">
        <label for="newsletter" class="col-lg-3 control-label">Newsletter:</label>
        <div class="col-lg-2">
            <fieldset class="form-group">
                <div class="form-check">
                    <label class="form-check-label">
                        <input type="radio" class="form-check-input" name="newsletter" id="newsletter" value="true" {CHECKED:newsletter=true}>
                        Subscribed
                    </label>
                </div>
                <div class="form-check">
                    <label class="form-check-label">
                        <input type="radio" class="form-check-input" name="newsletter" id="newsletter" value="false" {CHECKED:newsletter=false}>
                        Unsubscribed
                    </label>
                </div>
          </fieldset>
        </div>
    </div>
    </fieldset>
    <input type="hidden" name="token" value="{TOKEN}">
    <button name="submit" value="submit" type="submit" class="btn btn-lg btn-primary center-block">Update</button><br>
</form>