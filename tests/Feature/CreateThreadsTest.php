<?php namespace Tests\Feature;

use App\Thread;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CreateThreadsTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function guests_may_not_create_threads()
    {
        $this->expectException(AuthenticationException::class);

        $thread = make(Thread::class);

        $this->post(route('threads'), $thread->toArray());

    }

    /** @test */
    public function an_authenticated_user_can_create_new_forum_threads()
    {
        // Given a signed in user
        $this->signIn();

        // When we hit the endpoint to create a new thread
        $thread = factory(Thread::class)->make();

        // Then, when we visit the thread page
        $this->post(route('threads', $thread->toArray()));

        // we should see the new thread
        $response = $this->get(route('show_thread', $thread->id));
        $response->assertSee($thread->title);
        $response->assertSee($thread->body);
    }
}
