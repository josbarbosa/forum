<?php namespace Tests\Unit;

use App\Channel;
use App\Thread;
use App\User;
use Illuminate\Database\Eloquent\Collection;
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

}
