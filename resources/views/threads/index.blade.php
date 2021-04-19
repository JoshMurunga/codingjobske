@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @foreach ($threads as $thread)
                    <div class="card">
                        <div class="card-header">
                            <div class="level">
                                <div class="flex">
                                    <a href="{{ $thread->path() }}">
                                        {{ $thread->title }}
                                    </a>
                                </div>
                                <strong>
                                    <a href="{{ $thread->path() }}">{{ $thread->replies_count }}
                                        {{ Str::plural('reply', $thread->replies_count) }}</a>
                                </strong>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="body">{{ $thread->body }}</div>
                        </div>
                    </div>
                    <br>
                @endforeach
            </div>
        </div>
    </div>
@endsection
