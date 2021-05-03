<?php

namespace App\Http\Controllers;

use App\User;
use App\Activity;

class ProfileController extends Controller
{
    /** 
     * @return \Request 
     * 
     */
    public function show(User $user) {
        // return $user->activity()->latest()->with('subject')->get();

        return view('profiles.show', [
            'profileUser' => $user,
            'activities' => Activity::feed($user)
        ]);
    }
}
