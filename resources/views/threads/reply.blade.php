<div class="card">
    <div id="reply-{{ $reply->id }}" class="card-header">
        <div class="level">
            <h6 class="flex">
                <a href="/profiles/{{ $reply->owner->name }}">
                    {{ $reply->owner->name }}
                </a> says {{ $reply->created_at->diffForHumans() }}
            </h6>
            <form action="/replies/{{ $reply->id }}/favorites" method="post">
                @csrf
                <button type="submit" class="btn btn-primary" {{ $reply->isFavorited() ? 'disabled' : '' }}>
                    {{ $reply->favorites_count }}
                    {{ Str::plural('Favorite', $reply->favorites_count) }}
                </button>
            </form>
        </div>
    </div>
    <div class="card-body">
        {{ $reply->body }}
    </div>
    @can('update', $reply)
        <div class="card-footer level">
            <button class="btn btn-success btn-sm mr-1">Edit</button>

            <form action="/replies/{{ $reply->id }}" method="post">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
            </form>
        </div>
    @endcan
</div>
<br>
