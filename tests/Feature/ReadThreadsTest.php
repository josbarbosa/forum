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
        $threadWithTwoReplies = create('App\Thread');
        create('App\Reply', 2, ['thread_id' => $threadWithTwoReplies->id]);

        $threadWithThreeReplies = create('App\Thread');
        create('App\Reply', 3, ['thread_id' => $threadWithThreeReplies->id]);

        $response = $this->getJson('threads?popular=1')->json();

        $this->assertEquals([3, 2, 0], array_column($response['data'], 'replies_count'));
    }

    /** @test */
    function a_user_can_request_all_replies_for_a_given_thread(): void
    {
        $thread = create(Thread::class);
        create(Reply::class, 2, [
            'thread_id' => $thread->id,
        ]);

        /**  replies items setted on the fly to 1 per page */
        setItemsPerPage('replies', 1);

        $response = $this->getJson($thread->path() . '/replies')->json();

        $this->assertCount(1, $response['data']);
        $this->assertEquals(2, $response['total']);
    }

    /** @test */
    function a_user_can_filter_threads_by_those_that_are_unanswered(): void
    {
        $thread = create(Thread::class);
        create(Reply::class, 1, [
            'thread_id' => $thread->id,
        ]);

        $response = $this->getJson('threads?unanswered=1')->json();

        $this->assertCount(1, $response['data']);
    }
}
