<legend>Errors</legend>
{PAGINATION}
<form action="{BASE}admin/errors/delete" method="post">
    <table class="table table-striped">
        <thead>
            <tr>
                <th style="width: 5%">ID</th>
                <th style="width: 25%">Time</th>
                <th style="width: 55%">Description</th>
                <th style="width: 5%"></th>
                <th style="width: 5%"></th>
                <th style="width: 5%">
                    <INPUT type="checkbox" onchange="checkAll(this)" name="check.e" value="E_[]"/>
                </th>
            </tr></thead><tbody>
            {LOOP}
            <tr>
                <td align="center">{ID}</td>
                <td align="center">{DTC}{time}{/DTC}</td>
                <td>{error}</td>
                <td><a href="{BASE}admin/errors/viewError/{ID}" class="btn btn-sm btn-primary" role="button"><i class="glyphicon glyphicon-open"></i></a></td>
                <td><a href="{BASE}admin/errors/delete/{ID}" class="btn btn-sm btn-danger" role="button"><i class="glyphicon glyphicon-trash"></i></a></td>
                <td>
                    <input type="checkbox" value="{ID}" name="E_[]">
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
<br />
<a href="{BASE}admin/errors/clear">clear all</a>