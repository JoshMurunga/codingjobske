<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ParticipateInForumTest extends TestCase {
    use DatabaseMigrations;

    /** @test */
    public function unauthenticated_users_may_not_add_replies() {
        $this->expectException('Illuminate\Auth\AuthenticationException');

        $thread = factory('App\Thread')->create();

        $reply = factory('App\Reply')->make();

        $this->post($thread->path().'/replies', $reply->toArray());
    }

    /** @test */
    public function an_authenticated_user_can_participate_in_thread() {
        $this->be($user = factory('App\User')->create());

        $thread = factory('App\Thread')->create();

        $reply = factory('App\Reply')->make();

        $this->post($thread->path().'/replies', $reply->toArray());

        $this->get($thread->path())
             ->assertSee($reply->body);
    }

    /** @test */
    public function a_reply_requires_a_body() {
        $this->withExceptionHandling()->be($user = factory('App\User')->create());

        $thread = factory('App\Thread')->create();

        $reply = factory('App\Reply')->make(['body' => null]);

        $this->post($thread->path().'/replies', $reply->toArray())
            ->assertSessionHasErrors('body');
    }

    /** @test */
    public function unauthenticated_users_cannot_delete_replies() {
        $this->withExceptionHandling();

        $reply = factory('App\Reply')->create();

        $this->delete("/replies/{$reply->id}")
            ->assertRedirect('login');

        $this->be($user = factory('App\User')->create())
            ->delete("/replies/{$reply->id}")
            ->assertStatus(403);
    }

    /** @test */
    public function authenticated_users_can_delete_replies() {
        $this->be($user = factory('App\User')->create());

        $reply = factory('App\Reply')->create(['user_id' => auth()->id()]);

        $this->delete("/replies/{$reply->id}")
            ->assertStatus(302);

        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
    }

    /** @test */
    public function authenticated_users_can_update_replies() {
        $this->be($user = factory('App\User')->create());

        $reply = factory('App\Reply')->create(['user_id' => auth()->id()]);

        $updatedReply = 'You have been updated';

        $this->patch("/replies/{$reply->id}", ['body' => $updatedReply]);

        $this->assertDatabaseHas('replies', ['id' => $reply->id, 'body' => $updatedReply]);
    }

    /** @test */
    public function unauthenticated_users_cannot_update_replies() {
        $this->withExceptionHandling();

        $reply = factory('App\Reply')->create();

        $this->patch("/replies/{$reply->id}")
            ->assertRedirect('login');

        $this->be($user = factory('App\User')->create())
            ->patch("/replies/{$reply->id}")
            ->assertStatus(403);
    }
}
