<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreateThreadsTest extends TestCase {
    use DatabaseMigrations;

    /** @test */
    public function a_guest_cannot_create_forum_threads() {
        $this->expectException('Illuminate\Auth\AuthenticationException');

        $thread = factory('App\Thread')->make();

        $this->post('/threads', $thread->toArray());
    }

    /** @test */
    public function guets_cannot_see_create_thread_page() {
        $this->withExceptionHandling()
            ->get('/threads/create')
            ->assertRedirect('/login');
    }

    /** @test */
    public function an_auth_user_can_create_forum_threads() {
        $this->be($user = factory('App\User')->create());

        $thread = factory('App\Thread')->make();

        $response = $this->post('/threads', $thread->toArray());

        $this->get('/threads')
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }

    /** @test */
    public function a_thread_requires_a_title() {
        $this->publishThreads(['title' => null])
            ->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_thread_requires_a_body() {
        $this->publishThreads(['body' => null])
            ->assertSessionHasErrors('body');
    }

    /** @test */
    public function a_thread_requires_a_valid_channel() {
        factory('App\Channel', 2)->create();

        $this->publishThreads(['channel_id' => null])
            ->assertSessionHasErrors('channel_id');

        $this->publishThreads(['channel_id' => 999])
            ->assertSessionHasErrors('channel_id');
    }

    public function publishThreads($overrides = []) {
        $this->withExceptionHandling()->be($user = factory('App\User')->create());

        $thread = factory('App\Thread')->make($overrides);

        return $this->post('/threads', $thread->toArray());
    }

    /** @test */
    public function unauth_users_cannot_delete_threads() {
        $this->withExceptionHandling();

        $thread = factory('App\Thread')->create();

        $this->delete($thread->path())
            ->assertRedirect('/login');

        $this->be($user = factory('App\User')->create());
        $this->delete($thread->path())
            ->assertStatus(403);

    }

    /** @test */
    public function auth_users_can_delete_threads() {
        $this->be($user = factory('App\User')->create());

        $thread = factory('App\Thread')->create(['user_id' => $user->id]);
                
        $reply = factory('App\Reply')->create(['thread_id' => $thread->id]);

        $response = $this->json('DELETE', $thread->path());

        $response->assertStatus(204);

        $this->assertDatabaseMissing('threads', ['id' => $thread->id]);
        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
        $this->assertDatabaseMissing('activities', [
            'subject_id' => $thread->id,
            'subject_type' => get_class($thread)
        ]);
    }

}
