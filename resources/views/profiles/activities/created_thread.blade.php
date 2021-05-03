@component('profiles.activities.activity')
    @slot('heading')
        <a href="/profiles/{{ $activity->subject->creator->name }}">{{ $activity->subject->creator->name }}</a>
        posted: thread
        <a href="{{ $activity->subject->path() }}">{{ $activity->subject->title }}</a>
    @endslot

    @slot('body')
        {{ $activity->subject->body }}
    @endslot
@endcomponent
