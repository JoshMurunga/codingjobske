<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model {
    protected $guarded = [];
    protected $with = ['owner', 'favorites'];

    protected static function boot() {
        parent::boot();

        if (auth()->guest()) return;
        
        static::created(function ($reply) {
            $reply->activity()->create([
                'user_id' => auth()->id(),
                'type' => 'created_' .strtolower((new \ReflectionClass($reply))->getShortName()),
            ]);
        });

        static::deleting(function ($reply) {
            $reply->activity()->delete();
        });
    }

    public function activity() {
        return $this->morphMany(Activity::class, 'subject');
    }

    public function owner() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function favorites() {
        return $this->morphMany(Favorite::class, 'favorite');
    }

    public function favorite() {
        if(!$this->favorites()->where(['user_id' => auth()->id()])->exists()) {
            return $this->favorites()->create(['user_id' => auth()->id()]);
        }
    }

    public function isFavorited() {
        return !! $this->favorites->where('user_id', auth()->id())->count();
    }

    public function getFavoritesCountAttribute() {
        return $this->favorites->count();
    }

    public function thread() {
        return $this->belongsTo(Thread::class);
    }

    public function path() {
        return $this->thread->path() . "#reply-{$this->id}";
    }
}
