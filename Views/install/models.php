{installer-nav}
<br>
<br>
<form action="" method="post" class="form-horizontal">
    <table class="table table-striped">
        <thead>
            <tr>
                <th style="width: 70%">Model Name</th>
                <th style="width: 20%">Version</th>
                <th style="width: 10%">
                    <INPUT type="checkbox" onchange="checkAll(this)" name="check.m" value="M_[]"/>
                </th>
            </tr>
        </thead>
        <tbody>
            {LOOP}
            <tr>
                <td>{name}</td>
                <td>{version}</td>
                <td>
                    <input type="checkbox" value="{name}" name="M_[]">
                </td>
            </tr>
            {/LOOP}
            {ALT}
            <tr>
                <td align="center" colspan="3">
                    No models to install.
                </td>
            </tr>
            {/ALT}
        </tbody>
    </table>
    <input type="hidden" name="token" value="{TOKEN}">
    <button class="btn btn-lg btn-primary center-block" type="submit" name="submit" value="submit">Install</button><br>
</form>