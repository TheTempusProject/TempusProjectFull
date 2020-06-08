<div class="page-header">
    <h1>{groupName} <small>user list</small></h1>
</div>
{PAGINATION}
<form action="{BASE}admin/users/delete" method="post">
    <table class="table table-striped">
        <thead>
            <tr>
                <th style="width: 5%">ID</th>
                <th style="width: 55%">Username</th>
                <th style="width: 25%">Joined</th>
                <th style="width: 5%"></th>
                <th style="width: 5%"></th>
                <th style="width: 5%">
                    <input type="checkbox" onchange="checkAll(this)" name="check.u" value="U_[]"/>
                </th>
            </tr>
        </thead>
        <tbody>
            {LOOP}
            <tr>
                <td>{ID}</td>
                <td><a href='{BASE}admin/users/viewUser/{ID}'>{username}</a></td>
                <td>{DTC date}{registered}{/DTC}</td>
                <td><a href="{BASE}admin/users/edit/{ID}" class="btn btn-sm btn-warning" role="button"><i class="glyphicon glyphicon-edit"></i></a></td>
                <td><a href="{BASE}admin/users/delete/{ID}" class="btn btn-sm btn-danger" role="button"><i class="glyphicon glyphicon-trash"></i></a></td>
                <td>
                    <input type="checkbox" value="{ID}" name="U_[]">
                </td>
            </tr>
            {/LOOP}
            {ALT}
            <tr>
                <td align="center" colspan="6">
                    No results to show.
                </td>
            </tr>
            {/ALT}
        </tbody>
    </table>
    <button name="submit" value="submit" type="submit" class="btn btn-sm btn-danger">Delete</button>
</form>