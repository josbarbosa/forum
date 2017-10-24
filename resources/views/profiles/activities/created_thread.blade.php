@component('profiles.activities.activity')
    @slot('heading')
        <span>
            {{ $profileUser->name }} published
            <a href="{{ $activity->subject->path() }}">
                "{{ $activity->subject->title }}"
            </a>
        </span>
    @endslot

    @slot('body')
        {{ $activity->subject->body }}
    @endslot
@endcomponent
