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

        $this->get($response->headers->get('Location'))
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
    public function guest_cannot_delete_threads() {
        $this->withExceptionHandling();

        $thread = factory('App\Thread')->create();

        $response = $this->delete($thread->path());

        $response->assertRedirect('/login');
    }

    /** @test */
    public function a_thread_can_be_deleted() {
        $this->be($user = factory('App\User')->create());

        $thread = factory('App\Thread')->create();
                
        $reply = factory('App\Reply')->create(['thread_id' => $thread->id]);

        $response = $this->json('DELETE', $thread->path());

        $response->assertStatus(204);

        $this->assertDatabaseMissing('threads', ['id' => $thread->id]);
        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
    }

}
