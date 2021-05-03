<?php

namespace App;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ActivityTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_records_activity_when_a_thread_is_created() {
        $this->be($user = factory('App\User')->create());

        $thread = factory('App\Thread')->create();

        $this->assertDatabaseHas('activities', [
            'type' => 'created_thread',
            'user_id' => auth()->id(),
            'subject_id' => $thread->id,
            'subject_type' => 'App\Thread'
        ]);

        $activity = Activity::first();

        $this->assertEquals($activity->subject->id, $thread->id);
    }

    /** @test */
    public function it_records_activity_when_a_reply_is_created() {
        $this->be($user = factory('App\User')->create());

        $reply = factory('App\Reply')->create();

        $this->assertEquals(2, Activity::count());
    }

    /** @test */
    public function it_fetches_a_feed_for_any_user() {
        $this->be($user = factory('App\User')->create());

        factory('App\Thread', 2)->create(['user_id' => auth()->id()]);

        auth()->user()->activity()->first()->update(['created_at' => \Carbon\Carbon::now()->subWeek()]);

        $feed = Activity::feed(auth()->user());

        $this->assertTrue($feed->keys()->contains(
            \Carbon\Carbon::now()->format('Y-m-d')
        ));

        $this->assertTrue($feed->keys()->contains(
            \Carbon\Carbon::now()->subWeek()->format('Y-m-d')
        ));
    }
}
