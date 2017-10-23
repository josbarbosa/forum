<?php namespace Tests\Feature;

use App\Channel;
use App\Reply;
use App\Thread;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * Class ReadThreadsTest
 * @package Tests\Feature
 */
class ReadThreadsTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @var Thread
     */
    protected $thread;

    /**
     * Override setUp parent method
     * Executed every time a test runs
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->thread = factory(Thread::class)->create();
    }

    /** @test */
    function a_user_can_view_all_threads(): void
    {
        $response = $this->get(route('threads'));

        /** Test if page return OK status */
        $response->assertStatus(200);
        /** Test if the thread title is in the page */
        $response->assertSee($this->thread->title);
    }

    /** @test */
    function a_user_can_read_a_single_thread(): void
    {
        /** Test if user can access thread */
        $response = $this->get($this->thread->path());
        $response->assertSee($this->thread->title);
    }

    /** @test */
    function a_user_can_read_a_single_thread_with_pagination_replies(): void
    {
        setItemsPerPage('replies', 1);

        $replyOne = create(Reply::class, 1, [
            'thread_id' => $this->thread->id,
        ]);

        $replyTwo = create(Reply::class, 1, [
            'thread_id' => $this->thread->id,
        ]);

        $response = $this->get($this->thread->path());
        $response->assertSee($replyOne->body);
        $response->assertDontSee($replyTwo->body);
    }

    /** @test */
    function a_user_can_read_replies_that_are_associated_with_a_thread(): void
    {
        /** Thread includes replies */
        $reply = factory(Reply::class)->create([
            'thread_id' => $this->thread->id,
        ]);

        /** When we visit a thread page */
        $response = $this->get($this->thread->path());

        /** Then we should see the replies */
        $response->assertSee($reply->body);
    }

    /** @test */
    function a_user_can_filter_threads_according_to_a_channel(): void
    {
        /** Give a channel */
        $channel = create(Channel::class);

        /** Get Thread that are linked to a specifig channel */
        $threadInChannel = create(Thread::class, 1, [
            'channel_id' => $channel->id,
        ]);

        /** Get Thread that are not linked to a given channel */
        $threadNotInChannel = create(Thread::class);

        /** Get page and make assertions */
        $this->get(route('channel_threads', [
                'channel' => $channel->slug,
            ])
        )
            ->assertSee($threadInChannel->title)
            ->assertDontSee($threadNotInChannel->title);
    }

    /** @test */
    function a_user_can_filter_threads_by_any_username(): void
    {
        $this->signIn(create(User::class, 1, [
            'name' => 'JohnDoe',
        ]));

        $threadByJohn = create(Thread::class, 1, [
            'user_id' => auth()->id(),
        ]);

        $otherNotByJohn = create(Thread::class);

        $this->get('threads?by=JohnDoe')
            ->assertSee($threadByJohn->title)
            ->assertDontSee($otherNotByJohn->title);

    }

    /** @test */
    function a_user_can_filter_threads_by_popularity(): void
    {
        /** Given we have three threads With: 2 replies, 3 replies, and 0 replies, respectively */
        $threadWithTwoReplies = create(Thread::class, 1, ['created_at' => new Carbon('-2 minute')]);
        create(Reply::class, 2, ['thread_id' => $threadWithTwoReplies->id]);

        $threadWithThreeReplies = create(Thread::class, 1, ['created_at' => new Carbon('-1 minute')]);
        create(Reply::class, 3, ['thread_id' => $threadWithThreeReplies->id]);

        /**
         * On setUp method we already have a thread created
         * $threadWithZeroReplies = $this->thread;
         */

        /** When I filter all threads by popularity */
        $response = $this->getJson('threads?popular=1');

        /** Then they should be returned from most replies to least */
        $threadsFromResponse = $response->baseResponse->original->getData()['threads'];
        $this->assertEquals([3, 2, 0], $threadsFromResponse->pluck('replies_count')->toArray());
    }
}
