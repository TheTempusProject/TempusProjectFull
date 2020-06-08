<legend><h2>Installed Dependencies</h2></legend>
{PAGINATION}
<table class="table table-striped">
    <thead>
        <tr>
            <th style="width: 40%">Name</th>
            <th style="width: 40%">Required Version</th>
            <th style="width: 20%">Installed Version</th>
        </tr>
    </thead>
    <tbody>
        {LOOP}
        <tr>
            <td><a href="{BASE}admin/dependencies/viewInfo/{name}">{name}</a></td>
            <td>{requiredVersion}</td>
            <td>{version}</td>
        </tr>
        {/LOOP}
        {ALT}
        <tr>
            <td colspan="6">
                No results to show.
            </td>
        </tr>
        {/ALT}
    </tbody>
</table>