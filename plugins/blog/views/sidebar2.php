<div class="panel panel-info">
    <div class="panel-heading">
        <h3 class="panel-title">Archives</h3>
    </div>
    <div class="panel-body">
        <ol class="list-unstyled">
            {LOOP}
                <li>({count}) <a href="{BASE}blog/month/{month}/{year}">{monthText} {year}</a></li>
            {/LOOP}
            {ALT}
            {/ALT}
        </ol>
    </div>
</div>