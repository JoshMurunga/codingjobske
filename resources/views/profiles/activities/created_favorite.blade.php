@component('profiles.activities.activity')
    @slot('heading')
        <a href="/profiles/{{ $activity->subject->owner->name }}">{{ $activity->subject->owner->name }}</a>
        Favorited a reply on
        <a
            href="/threads/{{ $activity->subject->path()->pluck('slug')->first() }}/{{ $activity->subject->path()->pluck('id')->first() }}">
            {{ $activity->subject->path()->pluck('title')->first() }}
        </a>
    @endslot

    @slot('body')
        {{ $activity->subject->favReply() }}
    @endslot
@endcomponent
