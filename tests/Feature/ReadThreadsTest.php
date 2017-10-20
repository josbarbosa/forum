<?php namespace Tests\Feature;

use App\Reply;
use App\Thread;
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
    public function a_user_can_view_all_threads(): void
    {
        $response = $this->get(route('threads'));

        //Test if page return OK status
        $response->assertStatus(200);
        //Test if the thread title is in the page
        $response->assertSee($this->thread->title);
    }

    /** @test */
    public function a_user_can_read_a_single_thread(): void
    {
        //Test if user can access thread
        $response = $this->get($this->thread->path());
        $response->assertSee($this->thread->title);
    }

    /** @test */
    public function a_user_can_read_replies_that_are_associated_with_a_thread(): void
    {
        //Thread includes replies
        $reply = factory(Reply::class)->create([
            'thread_id' => $this->thread->id,
        ]);

        //When we visit a thread page
        $response = $this->get($this->thread->path());

        //Then we should see the replies
        $response->assertSee($reply->body);
    }
}
