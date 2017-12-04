<li class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-envelope"></i></a>
    <ul class="dropdown-menu message-dropdown">
        <li class="message-header">
            <div class="media">
                <div class="media-body text-center" style="padding-bottom: 10px; padding-top: 10px">
                    {MESSAGE_COUNT} unread message(s) total
                </div>
            </div>
        </li>
        {LOOP}
        <li class="message-preview">
            <a href="{BASE}usercp/messages/viewMessage/{ID}">
                <div class="media">
                    <span class="pull-left">
                        <img class="media-object avatar-round-40" src="{BASE}{fromAvatar}" alt="">
                    </span>
                    <div class="media-body">
                        <h5 class="media-heading"><strong>{userFrom}</strong>
                        </h5>
                        <p class="small text-muted"><i class="fa fa-clock-o"></i> {DTC}{lastReply}{/DTC}</p>
                        {summary}
                    </div>
                </div>
            </a>
        </li>
        {/LOOP}
        {ALT}
        <li class="message-preview">
            <div class="media">
                <div class="media-body text-center" style="padding-bottom: 10px; padding-top: 10px">
                    <h5 class="media-heading"><strong>No Messages</strong></h5>
                </div>
            </div>
        </li>
        {/ALT}
        <li class="message-footer">
            <a href="{BASE}usercp/messages">Read All New Messages</a>
        </li>
    </ul>
</li>