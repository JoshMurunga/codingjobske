<?php

namespace App\Filters;

use App\User;
use Illuminate\Http\Request;

class QueryFilter {
    public function __construct(Request $request) {
        $this->request = $request;
    }
    
    public function apply($builder) {
        if($this->request->has('by')) {
            $username = $this->request->by;
            $user = User::where('name', $username)->firstOrFail();
            return $builder->where('user_id', $user->id);
        }

        if($this->request->has('popular')) {
            $builder->getQuery()->orders = [];

            return $builder->orderBy('replies_count', 'desc');
        }

        if($this->request->has('unanswered')) {
            return $builder->groupBy('threads.id','threads.user_id','threads.channel_id','threads.title','threads.body','threads.created_at','threads.updated_at')
                        ->havingRaw('(SELECT COUNT(*) FROM `replies` WHERE `threads`.`id` = `replies`.`thread_id`) = 0');
            
        }

        return $builder;
    
    }
}
