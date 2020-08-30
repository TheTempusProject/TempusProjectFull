<div class="row">
    <div class="col-sm-8 blog-main">
        <div class="page-header">
            <h1>{title} <small>{DTC}{created}{/DTC} by <a href="{BASE}admin/users/viewUser/{author}">{authorName}</a></small></h1>
        </div>
        <div class="well">{content}</div>
        <a href="{BASE}admin/blog/delete/{ID}" class="btn btn-md btn-danger" role="button">Delete</a>
        <a href="{BASE}admin/blog/edit/{ID}" class="btn btn-md btn-warning" role="button">Edit</a>
        <a href="{BASE}admin/comments/blog/{ID}" class="btn btn-md btn-primary" role="button">View Comments</a>
    </div><!-- /.blog-main -->
</div><!-- /.row -->