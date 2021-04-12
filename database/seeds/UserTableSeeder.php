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
        $channels = factory('App\Channel', 10)->create();

        foreach ($channels as $channel) {
            $threads = factory('App\Thread', 50)->create(['channel_id' => $channel->id]);
    
            foreach ($threads as $thread) {
                factory('App\Reply', 10)->create(['thread_id' => $thread->id]);
            }
        }
    }
}
