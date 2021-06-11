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

    public function favorited() {
        return $this->morphTo('favorite');
    }
}
