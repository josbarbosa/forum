<div class="panel panel-default">
    <div class="panel-heading">
        <a href="#">
            {{ $reply->owner->name }}
        </a>
        <span>
            said
        </span>
        <span>
            {{ $reply->created_at->diffForHumans() }}
        </span>
    </div>
    <div class="panel-body">
        {{ $reply->body }}
    </div>
</div>
