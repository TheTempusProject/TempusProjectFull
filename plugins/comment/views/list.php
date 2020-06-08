<div class="panel panel-info widget comments">
    <div class="panel-heading">
        <span class="glyphicon glyphicon-comment"></span>
        <h3 class="panel-title">Comments</h3>
        <span class="label label-primary">{count}</span>
    </div>
    <div class="panel-body">
        <ul class="list-group">
            {LOOP}
            <li class="list-group-item">
                <div class="row">
                    <div class="col-xs-2 col-md-1">
                        <img src="{BASE}{avatar}" class="img-circle img-responsive" alt="" />
                    </div>
                    <div class="col-xs-10 col-md-11">
                        <div>
                            <div class="mic-info">
                                By: <a href="{BASE}home/profile/{author}">{authorName}</a> on {DTC date}{created}{/DTC}
                            </div>
                        </div>
                        <div class="comment-text">
                            {content}
                        </div>
                        {commentControl}
                    </div>
                </div>
            </li>
            {/LOOP} 
            {ALT}
            <li class="list-group-item">
                <div class="row">
                    <div class="col-xs-10 col-md-11">
                        <div class="comment-text">
                            <p class="text-center">Be the first to comment.</p>
                        </div>
                    </div>
                </div>
            </li>
            {/ALT}
        </ul>
    </div>
</div>