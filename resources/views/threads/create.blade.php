@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Create A New Thread</div>

                <div class="card-body">
                    <form method="POST" action="/threads">
                        @csrf
                        <div class="form-group">
                            <label for="channel_id">Select a Channel:</label>
                            <select id="channel_id" name="channel_id" class="form-control" required>
                                <option value="">Choose one...</option>
                                @foreach($channels as $channel)
                                    <option value="{{ $channel->id }}" {{ old('channel_id') == $channel->id ? 'selected' : '' }} >{{ $channel->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="title">Title:</label>
                            <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="body">Body:</label>
                            <textarea name="body" id="body" rows="8" class="form-control" required>{{ old('body') }}</textarea>
                        </div>
                        <div class="text-right form-group">
                            <button type="submit" class="btn btn-primary">Publish</button>
                        </div>

                        @if(count($errors))
                            <ul class="alert alert-danger">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
