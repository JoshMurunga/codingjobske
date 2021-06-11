@component('profiles.activities.activity')
    @slot('heading')
        <a href="/profiles/{{ $activity->subject->owner->name }}">{{ $activity->subject->owner->name }}</a>
        Favorited a reply on
        <a href="{{ $activity->subject->favorited->path() }}">
            {{ $activity->subject->favorited->thread->title }}
        </a>
    @endslot

    @slot('body')
        {{ $activity->subject->favorited->body }}
    @endslot
@endcomponent
