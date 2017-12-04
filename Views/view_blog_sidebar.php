<div class="panel panel-info">
    <div class="panel-heading">
        <h3 class="panel-title">Recent Posts</h3>
    </div>
    <div class="panel-body">
        <ol class="list-unstyled">
            {LOOP}
                <li><a href="{BASE}blog/post/{ID}">{title}</a></li>
            {/LOOP}
            {ALT}
                <li>No Posts to show</li>
            {/ALT}
        </ol>
    </div>
    <div class="panel-footer">
        <a href="{BASE}blog">View All</a>
    </div>
</div>