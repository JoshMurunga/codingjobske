<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model {
    protected $guarded = [];
    protected $with = ['owner', 'favorites'];

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
}
