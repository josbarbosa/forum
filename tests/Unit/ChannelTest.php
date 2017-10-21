<?php namespace Tests\Unit;

use App\Channel;
use App\Thread;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * Class ChannelTest
 * @package Tests\Unit
 */
class ChannelTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function a_channel_consists_of_threads(): void
    {
        $channel = create(Channel::class);

        $thread = create(Thread::class, 1, [
            'channel_id' => $channel->id,
        ]);

        $this->assertTrue($channel->threads->contains($thread));
    }
}
