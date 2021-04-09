@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"> <a href="#">{{ $thread->creator->name }}</a> posted: {{ $thread->title }}</div>
                <div class="card-body">
                    {{ $thread->body }}
                </div>
            </div>
        </div>
    </div>
    <br>
    @if(auth()->check())
        <div class="row justify-content-center">
            <div class="col-md-8">
                <form method="POST" action="{{ $thread->path() . '/replies' }}">
                    @csrf
                    <div class="form-group">
                        <textarea class="form-control" name="body" id="body" rows="5" placeholder="Something to say?"></textarea>
                    </div>
                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">Post</button>
                    </div>
                </form>
            </div>
        </div>
    @else
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="text-center">
                    please <a href="{{ route('login') }}">login</a>  to add comment
                </div>
            </div>
        </div>
    @endif
    <br>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                @foreach ( $thread->replies as $reply)
                    @include ('threads.reply')
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
 