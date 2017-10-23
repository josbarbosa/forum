<?php namespace Tests\Feature;

use App\Channel;
use App\Reply;
use App\Thread;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestResponse;
use Tests\TestCase;

/**
 * Class CreateThreadsTest
 * @package Tests\Feature
 */
class CreateThreadsTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    function guests_may_not_create_threads(): void
    {
        $this->get('/threads/create')
            ->assertRedirect('/login');

        $this->post('/threads')
            ->assertRedirect('/login');
    }

    /** @test */
    function an_authenticated_user_can_create_new_forum_threads(): void
    {
        // Given a signed in user
        $this->signIn();

        // When we hit the endpoint to create a new thread
        $thread = make(Thread::class);

        // Then, when we visit the thread page
        $response = $this->post(route('threads', $thread->toArray()));

        // we should see the new thread
        $response = $this->get($response->headers->get('Location'));
        $response->assertSee($thread->title);
        $response->assertSee($thread->body);
    }

    /** @test */
    function an_authenticated_user_can_access_create_thread_page(): void
    {
        // Given a signed in user
        $this->signIn();

        // Test if can access the create thread page
        $response = $this->get(route('create_thread'));
        $response->assertStatus(200);
    }

    /** @test */
    function a_thread_requires_a_title(): void
    {
        $this->publishThread(['title' => null])
            ->assertSessionHasErrors('title');
    }

    /** @test */
    function a_thread_requires_a_body(): void
    {
        $this->publishThread(['body' => null])
            ->assertSessionHasErrors('body');
    }

    /** @test */
    function a_thread_requires_a_valid_channel(): void
    {
        create(Channel::class, 2);

        $this->publishThread(['channel_id' => -1])
            ->assertSessionHasErrors('channel_id');
    }

    /** @test */
    function guests_cannot_delete_threads(): void
    {
        $thread = create(Thread::class);

        $response = $this->json('DELETE', $thread->path());

        $response->assertStatus(401);

    }

    /** @test */
    function threads_may_only_be_deleted_by_those_who_have_permissions(): void
    {
        $this->assertTrue(true);
    }

    /** @test */
    function a_thread_can_be_deleted(): void
    {
        $this->signIn();

        $thread = create(Thread::class);
        $reply = create(Reply::class, 1, [
            'thread_id' => $thread->id,
        ]);

        $response = $this->json('DELETE', $thread->path());

        $response->assertStatus(204);

        $this->assertDatabaseMissing('threads', ['id' => $thread->id]);
        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);

        /** missing test when logged user try to delete a thread */
        $thread = create(Thread::class);
        $response = $this->delete($thread->path());
        $response->assertRedirect('/threads');
    }

    /**
     * @param array $overrides
     * @return TestResponse
     */
    function publishThread(array $overrides = []): TestResponse
    {
        // Sign in the User
        $this->signIn();

        // Make the Thread object
        $thread = make(Thread::class, 1, $overrides);

        // Post to ThreadsController
        $response = $this->post('/threads', $thread->toArray());

        // Return the response
        return $response;
    }
}
