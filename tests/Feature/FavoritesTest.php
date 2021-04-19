<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class FavoritesTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function guest_cannot_favorite_a_thread() {
        $this->withExceptionHandling()
            ->post("replies/1/favorites")
            ->assertRedirect('/login');
    }

    /** @test */
    public function an_auth_user_can_favorite_any_reply() {
        $this->be($user = factory('App\User')->create());

        $reply = factory('App\Reply')->create();

        $this->post("replies/{$reply->id}/favorites");

        $this->assertCount(1, $reply->favorites);
    }

    /** @test */
    public function an_auth_user_can_favorite_a_reply_once() {
        $this->be($user = factory('App\User')->create());

        $reply = factory('App\Reply')->create();

        try {
            $this->post("replies/{$reply->id}/favorites");
            $this->post("replies/{$reply->id}/favorites");
        } catch (\Throwable $th) {
            $this->fail('Cannot like more than once');
        }

        $this->assertCount(1, $reply->favorites);
    }
}
