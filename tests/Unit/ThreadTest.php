<?php namespace Tests\Unit;

use App\Channel;
use App\Notifications\ThreadWasUpdated;
use App\Thread;
use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * Class ThreadTest
 * @package Tests\Unit
 */
class ThreadTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @var Thread
     */
    protected $thread;

    public function setUp(): void
    {
        parent::setUp();

        $this->thread = factory(Thread::class)->create();
    }

    /** @test */
    function a_thread_can_make_a_string_path(): void
    {
        $thread = create(Thread::class);

        $this->assertEquals('/threads/' . $thread->channel->slug . '/' . $thread->id, $thread->path());
    }

    /** @teste */
    function a_thread_has_replies(): void
    {
        $this->assertInstanceOf(Collection::class, $this->thread->replies);
    }

    /** @test */
    function a_thread_has_a_creator(): void
    {
        $this->assertInstanceOf(User::class, $this->thread->creator);
    }

    /** @test */
    function a_thread_can_add_a_reply(): void
    {
        $this->thread->addReply([
            'body'    => 'Foobar',
            'user_id' => 1,
        ]);

        $this->assertCount(1, $this->thread->replies);
    }

    /** @test */
    function a_thread_belongs_to_a_channel(): void
    {
        $this->assertInstanceOf(Channel::class, $this->thread->channel);
    }

    /** @test */
    function a_thread_can_be_subscribed_to(): void
    {
        /** Given we have a thread */
        $thread = create(Thread::class);

        /** And an authenticated user */
        $this->signIn();

        /** When the user subscribes to the thread */
        $thread->subscribe($userId = 1);

        /** Then we should be able to fetch all threads that the user has subscribed to */
        $this->assertEquals(1, $thread->subscriptions()->where('user_id', $userId)->count());
    }

    /** @test */
    function a_thread_can_be_unsubscribed_from(): void
    {
        /** Given we have a thread */
        $thread = create(Thread::class);

        /** And a user who is subscribed to the thread */
        $thread->subscribe($userId = 1);

        $this->assertCount(1, $thread->subscriptions);

        $thread->unsubscribe($userId);

        $this->assertCount(0, $thread->fresh()->subscriptions);

    }

    /** @test */
    function it_knows_if_the_authenticated_user_is_subscribed_to_it(): void
    {
        $this->signIn();

        $thread = create(Thread::class, 1, [
            'user_id' => auth()->id(),
        ]);

        $this->assertFalse($thread->isSubscribedTo);

        $thread->subscribe();

        $this->assertTrue($thread->isSubscribedTo);
    }

    /** @test */
    function a_thread_notifies_all_registered_subscribers_when_a_reply_is_added(): void
    {
        /** Fake a notification event */
        Notification::fake();

        /** Given an authenticated user */
        $this->signIn();

        /** and user subscribe a thread */
        $this->thread->subscribe();

        /** when adding a reply */
        $this->thread->addReply([
            'body'    => 'Foobar',
            'user_id' => 1,
        ]);

        /** Check notification event */
        Notification::assertSentTo(auth()->user(), ThreadWasUpdated::class);
    }

    /** @test */
    function a_user_has_threads(): void
    {
        $this->assertCount(1, $this->thread->creator->threads);
    }
}
