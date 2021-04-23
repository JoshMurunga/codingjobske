@component('profiles.activities.activity')
    @slot('heading')
        <a href="/profiles/{{ $activity->subject->owner->name }}">{{ $activity->subject->owner->name }}</a>
        Replied to:
        <a href="{{ $activity->subject->thread->path() }}">{{ $activity->subject->thread->title }}</a>
    @endslot
    <span>
        {{ $activity->subject->created_at->diffForHumans() }}
    </span>
    @slot('body')
        {{ $activity->subject->body }}
    @endslot
@endcomponent
