<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    protected $guarded = [];
    protected $with = ['owner'];

    protected static function boot() {
        parent::boot();

        if (auth()->guest()) return;
        
        static::created(function ($favorite) {
            $favorite->activity()->create([
                'user_id' => auth()->id(),
                'type' => 'created_' .strtolower((new \ReflectionClass($favorite))->getShortName()),
            ]);
        });

        static::deleting(function ($favorite) {
            $favorite->activity()->delete();
        });
    }

    public function activity() {
        return $this->morphMany(Activity::class, 'subject');
    }

    public function owner() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function favReply() {
        return \DB::table('replies')
                ->join('favorites', 'favorites.favorite_id', '=', 'replies.id')
                ->selectRaw('replies.body')
                ->where('favorites.id', '=', $this->id)
                ->get()->pluck('body')->first();
    }

    public function path() {
        return \DB::table('favorites')
                ->join('replies', 'favorites.favorite_id', '=', 'replies.id')
                ->join('threads', 'replies.thread_id', '=', 'threads.id')
                ->join('channels', 'threads.channel_id', '=', 'channels.id')
                ->select('threads.id', 'channels.slug', 'threads.title')
                ->where('favorites.id', '=', $this->id)
                ->get();
    }
}
