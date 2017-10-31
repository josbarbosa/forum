<?php namespace Tests\Feature;

use App\Thread;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * Class SubscribeToThreadsTest
 * @package Tests\Feature
 */
class SubscribeToThreadsTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    function a_user_can_subscribe_to_threads(): void
    {
        $this->signIn();

        /** Given we have a thread */
        $thread = create(Thread::class);

        /** And the user subscribes to the thread */
        $this->post(route('subscription_thread', [
            'channel' => $thread->channel->slug,
            'thread'  => $thread->id,
        ]));

        $this->assertCount(1, $thread->fresh()->subscriptions);
    }

    /** @test */
    function a_user_can_unsubscribe_to_threads(): void
    {
        $this->signIn();

        $thread = create(Thread::class);

        $thread->subscribe();

        /** Delete the thread subscription */
        $this->delete(route('subscription_delete_thread', [
            'channel' => $thread->channel->slug,
            'thread'  => $thread->id,
        ]));

        $this->assertCount(0, $thread->subscriptions);
    }
}
