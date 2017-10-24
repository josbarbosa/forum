<div id="reply-{{ $reply->id }}" class="panel panel-default">
    <div class="panel-heading">
        <div class="level">
            <h5 class="flex">
                <a href="{{ route('show_profile', $reply->owner->name) }}">
                    {{ $reply->owner->name }}
                </a>
                <span>
                    said
                </span>
                <span>
                    {{ $reply->created_at->diffForHumans() }}
                </span>
            </h5>
            <div>
                <form method="POST" action="{{ route('favorite_reply', $reply->id) }}">
                    {{ csrf_field() }}
                    <button {{ $reply->isFavorited() ? 'disabled' : '' }} type="submit" class="btn btn-default">
                        {{ $reply->favorites_count }}
                        {{ str_plural('Favorite', $reply->favorites_count) }}
                    </button>
                </form>
            </div>
        </div>
    </div>
    <div class="panel-body">
        {{ $reply->body }}
    </div>

    @can('update', $reply)
        <div class="panel-footer">
            <form method="POST" action="{{ route('delete_reply', $reply->id) }}">
                {{ csrf_field() }}
                {{ method_field('DELETE') }}

                <button type="submit" class="btn btn-danger btn-xs">Delete</button>
            </form>
        </div>
    @endcan
</div>
