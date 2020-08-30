<legend>New Users</legend>
<table class="table table-striped">
    <thead>
        <tr>
            <th style="width: 85%"></th>
            <th style="width: 5%"></th>
            <th style="width: 5%"></th>
            <th style="width: 5%"></th>
        </tr>
    </thead>
    <tbody>
        {LOOP}
        <tr>
            <td align="center">{username}</td>
            <td><a href="{BASE}admin/users/viewUser/{ID}" class="btn btn-sm btn-primary" role="button"><i class="glyphicon glyphicon-user"></i></a></td>
            <td><a href="{BASE}admin/users/edit/{ID}" class="btn btn-sm btn-warning" role="button"><i class="glyphicon glyphicon-edit"></i></a></td>
            <td><a href="{BASE}admin/users/delete/{ID}" class="btn btn-sm btn-danger" role="button"><i class="glyphicon glyphicon-trash"></i></a></td>
        </tr>
        {/LOOP}
        {ALT}
        <tr>
            <td align="center" colspan="4">
                No results to show.
            </td>
        </tr>
        {/ALT}
    </tbody>
</table>