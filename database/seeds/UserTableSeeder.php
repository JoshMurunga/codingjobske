<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $threads = factory('App\Thread', 50)->create();

        foreach ($threads as $thread) {
            factory('App\Reply', 10)->create(['thread_id' => $thread->id]);
        }
    }
}
