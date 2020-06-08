<div class="row">
    <div class="col-lg-12 col-sm-12 blog-main">
        <div class="blog-post">
            <h2 class="blog-post-title">{title}</h2>
            <hr>
            <p class="blog-post-meta">{DTC date}{created}{/DTC} by <a href="{BASE}home/profile/{author}">{authorName}</a></p>
            {content}
            {ADMIN}
                <hr>
                <a href="{BASE}admin/blog/delete/{ID}" class="btn btn-md btn-danger" role="button">Delete</a>
                <a href="{BASE}admin/blog/edit/{ID}" class="btn btn-md btn-warning" role="button">Edit</a>
                <hr>
            {/ADMIN}
        </div><!-- /.blog-post -->
        {COMMENTS}
        {NEWCOMMENT}
    </div><!-- /.blog-main -->
</div><!-- /.row -->