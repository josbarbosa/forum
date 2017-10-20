<?php namespace Tests\Feature;

use App\Reply;
use App\Thread;
use App\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ParticipateInForumTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function unauthenticated_users_may_not_add_replies()
    {
        $this->expectException(AuthenticationException::class);

        $this->post(route('store_reply', 1), []);
    }

    /** @test */
    public function an_authenticated_user_may_participate_in_forum_threads()
    {
        // Given we have a authenticated user
        $this->be($user = factory(User::class)->create());

        // And an existing thread
        $thread = factory(Thread::class)->create();

        // When a user adds a reply to the thread
        $reply = factory(Reply::class)->create();
        $this->post(route('store_reply', $thread->id), $reply->toArray());

        // Then their reply should be visible on the page
        $response = $this->get(route('show_thread', $thread->id));
        $response->assertSee($reply->body);
    }
}
