@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <div class="level">
                            <span class="flex">
                                <a href="/profiles/{{ $thread->creator->name }}">{{ $thread->creator->name }}</a>
                                posted:
                                {{ $thread->title }}
                            </span>
                            @can('update', $thread)
                                <form action="{{ $thread->path() }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-link">Delete Thread</button>
                                </form>
                            @endcan
                        </div>
                    </div>
                    <div class="card-body">
                        {{ $thread->body }}
                    </div>
                </div>
                <br>
                @if (auth()->check())
                    <form method="POST" action="{{ $thread->path() . '/replies' }}">
                        @csrf
                        <div class="form-group">
                            <textarea class="form-control" name="body" id="body" rows="5"
                                placeholder="Something to say?"></textarea>
                        </div>
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">Post</button>
                        </div>
                    </form>
                @else
                    <div class="text-center">
                        please <a href="{{ route('login') }}">login</a> to add comment
                    </div>
                @endif
                <br>
                <div class="card">
                    @foreach ($replies as $reply)
                        @include ('threads.reply')
                    @endforeach
                </div>
                <br>
                {{ $replies->links() }}
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <p>
                            This thread was published {{ $thread->created_at->diffForHumans() }} by
                            <a href="/profiles/{{ $thread->creator->name }}">
                                {{ $thread->creator->name }}
                            </a>, and currently has
                            {{ $thread->replies_count }} {{ Str::plural('comment', $thread->replies_count) }}.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
