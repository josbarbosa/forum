<reply :attributes="{{ $reply }}" inline-template v-cloak>
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
            <div v-if="editing">
                <div class="form-group">
                    <textarea class="form-control" v-model="body"></textarea>
                </div>
                <button class="btn btn-xs btn-primary" @click="update">Update</button>
                <button class="btn btn-xs btn-link" @click="editing=false">Cancel</button>
            </div>
            <div v-else v-text="body"></div>
        </div>

        @can('update', $reply)
            <div class="panel-footer level">
                <button class="btn btn-xs mr-1" @click="editing = true">Edit</button>
                <button class="btn btn-xs btn-danger mr-1" @click="destroy">Delete</button>
            </div>
        @endcan
    </div>
</reply>

