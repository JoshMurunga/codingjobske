<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Thread extends Model {
    protected $guarded = [];
    protected $with = ['creator', 'channel'];

    protected static function boot() {
        parent::boot();

        static::addGlobalScope('replyCount', function ($builder) {
            $builder->withCount('replies');
        });

        if (auth()->guest()) return;

        static::created(function ($thread) {
            $thread->activity()->create([
                'user_id' => auth()->id(),
                'type' => 'created_' .strtolower((new \ReflectionClass($thread))->getShortName()),
            ]);
        });
    }

    public function activity() {
        return $this->morphMany(Activity::class, 'subject');
    }

    public function path() {
        return "/threads/{$this->channel->slug}/{$this->id}";
    }

    public function replies() {
        return $this->hasMany(Reply::class)->latest();
    }
 
    public function creator() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function addReply($reply){
        $this->replies()->create($reply);
    }

    public function channel() {
        return $this->belongsTo(Channel::class);
    }

    public function scopeFilter($query, $queryFilter) {
        return $queryFilter->apply($query);
    }
}
