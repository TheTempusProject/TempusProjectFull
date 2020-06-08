<legend>Groups</legend>
{PAGINATION}
<form action="{BASE}admin/groups/delete" method="post">
    <table class="table table-striped">
        <thead>
            <tr>
                <th style="width: 50%">Name</th>
                <th style="width: 35%">Users</th>
                <th style="width: 5%"></th>
                <th style="width: 5%"></th>
                <th style="width: 5%">
                    <INPUT type="checkbox" onchange="checkAll(this)" name="check.g" value="G_[]"/>
                </th>
            </tr>
        </thead>
        <tbody>
            {LOOP}
            <tr>
                <td><a href="{BASE}admin/groups/viewGroup/{ID}">{name}</a></td>
                <td><a href="{BASE}admin/groups/listmembers/{ID}">{userCount}</a></td>
                <td><a href="{BASE}admin/groups/edit/{ID}" class="btn btn-sm btn-warning" role="button"><i class="glyphicon glyphicon-edit"></i></a></td>
                <td><a href="{BASE}admin/groups/delete/{ID}" class="btn btn-sm btn-danger" role="button"><i class="glyphicon glyphicon-trash"></i></a></td>
                <td>
                    <input type="checkbox" value="{ID}" name="G_[]">
                </td>
            </tr>
            {/LOOP}
            {ALT}
            <tr>
                <td align="center" colspan="5">
                    No results to show.
                </td>
            </tr>
            {/ALT}
        </tbody>
    </table>
    <a href="{BASE}admin/groups/newGroup" class="btn btn-sm btn-primary" role="button">Create</a>
    <button name="submit" value="submit" type="submit" class="btn btn-sm btn-danger">Delete</button>
</form>