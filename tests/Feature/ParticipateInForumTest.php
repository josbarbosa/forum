<?php namespace Tests\Feature;

use App\Reply;
use App\Thread;
use App\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * Class ParticipateInForumTest
 * @package Tests\Feature
 */
class ParticipateInForumTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    function unauthenticated_users_may_not_add_replies(): void
    {
        $this->withoutExceptionHandling();
        $this->expectException(AuthenticationException::class);

        $this->post(route('store_reply', ['channel' => 'some-channel', 'thread' => 1]), []);
    }

    /** @test */
    function an_authenticated_user_may_participate_in_forum_threads(): void
    {
        // Given we have a authenticated user
        $this->be($user = factory(User::class)->create());

        // And an existing thread
        $thread = factory(Thread::class)->create();

        // When a user adds a reply to the thread
        $reply = factory(Reply::class)->create();
        $this->post($thread->path() . '/replies',
            $reply->toArray()
        );

        // Then their reply should be visible on the page
        $response = $this->get($thread->path());

        $response->assertSee($reply->body);
    }

    /** @test */
    function a_reply_requires_a_body(): void
    {
        $this->signIn();

        $thread = create(Thread::class);

        $reply = make(Reply::class, 1, ['body' => null]);

        $this->post($thread->path() . '/replies', $reply->toArray())
            ->assertSessionHasErrors('body');
    }

    /** @test */
    function unauthorized_users_cannot_delete_replies()
    {
        $reply = create(Reply::class);

        $this->delete(route('delete_reply', $reply->id))
            ->assertRedirect(route('login'));

        $this->signIn();

        $this->delete(route('delete_reply', $reply->id))
            ->assertStatus(403);
    }

    /** @test */
    function authorized_users_can_delete_replies()
    {
        $this->signIn();

        $reply = create(Reply::class, 1, [
            'user_id' => auth()->id(),
        ]);

        $this->delete(route('delete_reply', $reply->id))
            ->assertStatus(302);

        $this->assertDatabaseMissing('replies', [
            'id' => $reply->id,
        ]);

    }

    /** @test */
    function authorized_users_can_update_replies()
    {
        $this->signIn();

        $reply = create(Reply::class, 1, [
            'user_id' => auth()->id(),
        ]);

        $updateReply = 'You been changed, fool.';
        $this->patch(route('patch_reply', $reply->id), [
            'body' => $updateReply,
        ]);

        $this->assertDatabaseHas('replies', [
            'id'   => $reply->id,
            'body' => $updateReply,
        ]);
    }

    /** @test */
    function unauthorized_users_cannot_update_replies()
    {
        $reply = create(Reply::class);

        $this->patch(route('patch_reply', $reply->id))
            ->assertRedirect(route('login'));

        $this->signIn();

        $this->patch(route('patch_reply', $reply->id))
            ->assertStatus(403);
    }
}
