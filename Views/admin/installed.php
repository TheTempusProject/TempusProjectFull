<legend><h2>Installed Models</h2></legend>
{PAGINATION}
<table class="table table-striped">
    <thead>
        <tr>
            <th style="width: 30%">Name</th>
            <th style="width: 15%">Status</th>
            <th style="width: 15%">Install Date</th>
            <th style="width: 15%">Last Updated</th>
            <th style="width: 10%">Installed Version</th>
            <th style="width: 10%">File Version</th>
            <th style="width: 5%"></th>
        </tr>
    </thead>
    <tbody>
        {LOOP}
        <tr>
            <td><a href="{BASE}admin/installed/viewModel/{name}">{name}</a></td>
            <td>{installStatus}</td>
            <td>{DTC=date}{installDate}{/DTC}</td>
            <td>{DTC=date}{lastUpdate}{/DTC}</td>
            <td>{currentVersion}</td>
            <td>{version}</td>
            <td><a href="{BASE}admin/installed/viewModel/{name}" class="btn btn-sm btn-primary" role="button"><i class="glyphicon glyphicon-open"></i></a></td>
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