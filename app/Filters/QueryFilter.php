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

        return $builder;
    
    }
}
