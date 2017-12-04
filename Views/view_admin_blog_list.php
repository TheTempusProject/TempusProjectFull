<legend>Blog Posts</legend>
{PAGINATION}
<form action="{BASE}admin/blog/delete" method="post">
    <table class="table table-striped">
        <thead>
            <tr>
                <th style="width: 30%">Title</th>
                <th style="width: 20%">Author</th>
                <th style="width: 10%">comments</th>
                <th style="width: 10%">Created</th>
                <th style="width: 10%">Updated</th>
                <th style="width: 5%"></th>
                <th style="width: 5%"></th>
                <th style="width: 10%">
                    <INPUT type="checkbox" onchange="checkAll(this)" name="check.b" value="B_[]"/>
                </th>
            </tr>
        </thead>
        <tbody>
            {LOOP}
            <tr>
                <td><a href="{BASE}admin/blog/view/{ID}">{title}</a>{isDraft}</td>
                <td>{authorName}</td>
                <td>{commentCount}</td>
                <td>{DTC}{created}{/DTC}</td>
                <td>{DTC}{edited}{/DTC}</td>
                <td><a href="{BASE}admin/blog/edit/{ID}" class="btn btn-sm btn-warning" role="button"><i class="glyphicon glyphicon-edit"></i></a></td>
                <td><a href="{BASE}admin/blog/delete/{ID}" class="btn btn-sm btn-danger" role="button"><i class="glyphicon glyphicon-trash"></i></a></td>
                <td>
                    <input type="checkbox" value="{ID}" name="B_[]">
                </td>
            </tr>
            {/LOOP}
            {ALT}
            <tr>
                <td colspan="7">
                    No results to show.
                </td>
            </tr>
            {/ALT}
        </tbody>
    </table>
    <a href="{BASE}admin/blog/new" class="btn btn-sm btn-primary" role="button">Create</a>
    <button name="submit" value="submit" type="submit" class="btn btn-sm btn-danger">Delete</button>
</form>