<legend>New Posts</legend>
<table class="table table-striped">
    <thead>
        <tr>
            <th style="width: 20%"></th>
            <th style="width: 65%"></th>
            <th style="width: 5%"></th>
            <th style="width: 5%"></th>
            <th style="width: 5%"></th>
        </tr>
    </thead>
    <tbody>
        {LOOP}
        <tr>
            <td>{title}</td>
            <td>{contentSummary}</td>
            <td><a href="{BASE}admin/blog/view/{ID}" class="btn btn-sm btn-primary" role="button"><i class="glyphicon glyphicon-open"></i></a></td>
            <td><a href="{BASE}admin/blog/edit/{ID}" class="btn btn-sm btn-warning" role="button"><i class="glyphicon glyphicon-edit"></i></a></td>
            <td width="30px"><a href="{BASE}admin/blog/delete/{ID}" class="btn btn-sm btn-danger" role="button"><i class="glyphicon glyphicon-trash"></i></a></td>
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