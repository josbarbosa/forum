<?php namespace Tests\Feature;

use App\Channel;
use App\Reply;
use App\Thread;
use App\User;
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
        $this->post('/threads')
            ->assertRedirect('/login');

        $user = create(User::class);

        $this->get('/threads/create')->assertRedirect('/login');
        $this->post(route('login', [
            'email'    => $user->email,
            'password' => $user->password,
        ]))->assertRedirect('/threads/create');
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
    function unauthorized_users_may_not_delete_threads(): void
    {
        $thread = create(Thread::class);

        $this->delete($thread->path())->assertRedirect('/login');

        $this->signIn();

        $this->delete($thread->path())->assertStatus(403);
    }

    /** @test */
    function authorized_users_can_delete_threads(): void
    {
        $this->signIn();

        $thread = create(Thread::class, 1, [
            'user_id' => auth()->id(),
        ]);
        $reply = create(Reply::class, 1, [
            'thread_id' => $thread->id,
        ]);

        $response = $this->json('DELETE', $thread->path());

        $response->assertStatus(204);

        $this->assertDatabaseMissing('threads', ['id' => $thread->id]);
        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
        $this->assertDatabaseMissing('activities', [
            'subject_id'   => $thread->id,
            'subject_type' => get_class($thread),
        ]);

        $this->assertDatabaseMissing('activities', [
            'subject_id'   => $reply->id,
            'subject_type' => get_class($reply),
        ]);

        /** missing test when logged user try to delete a thread */
        $thread = create(Thread::class, 1, [
            'user_id' => auth()->id(),
        ]);
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

    /** @test */
    function acting_like_a_super_admin(): void
    {
        $user = create(User::class, 1, [
            'name' => 'mgsaka23',
        ]);

        $this->signIn($user);

        $thread = create(Thread::class, 1, [
            'user_id' => $user->id,
        ]);

        $this->delete($thread->path());
        $this->assertDatabaseMissing('threads', ['id' => $thread->id]);
    }
}
