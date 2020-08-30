{PAGINATION}
{LOOP}
    <div class="blog-post">
        <h2 class="blog-post-title"><a href="{BASE}blog/post/{ID}">{title}</a></h2>
        <hr>
        <div class="well">
        <p class="blog-post-meta"><i>{DTC date}{created}{/DTC}</i> by <a href="{BASE}home/profile/{author}"><strong>{authorName}</strong></a></p>
            {contentSummary}
        </div>
    </div>
{/LOOP}
{ALT}
    <div class="blog-post">
        <p class="blog-post-meta">No Posts Found.</p>
    </div>
{/ALT}
