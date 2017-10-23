<?php namespace Tests\Unit;

use App\Reply;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * Class ReplyTest
 * @package Tests\Unit
 */
class ReplyTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    function it_has_an_owner(): void
    {
        //Create a Reply
        $reply = factory(Reply::class)->create();

        //Check if an User instance exists in reply object
        $this->assertInstanceOf(User::class, $reply->owner);
    }
}
