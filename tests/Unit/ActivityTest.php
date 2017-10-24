<?php namespace Tests\Unit;

use App\Activity;
use App\Reply;
use App\Thread;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * Class ActivityTest
 * @package Tests\Unit
 */
class ActivityTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    function it_records_activity_when_a_thread_is_created(): void
    {
        /** Given an authenticated user */
        $this->signIn();

        /** When a thread is created */
        $thread = create(Thread::class);

        /** We need to create an activity log */
        $this->assertDatabaseHas('activities', [
            'type'         => 'created_thread',
            'user_id'      => auth()->id(),
            'subject_id'   => $thread->id,
            'subject_type' => 'App\Thread',
        ]);

        /** First activity in database have match with thread id */
        $activity = Activity::first();
        $this->assertEquals($activity->subject->id, $thread->id);
    }

    /** @test */
    function it_records_activity_when_a_reply_is_created(): void
    {
        /** Given an authenticated user */
        $this->signIn();

        /** Reply factory creates a user, a thread e a reply */
        create(Reply::class);

        /**
         * create a reply and see if exists 2 activities in database
         * One for the thread created and another for the reply
         * User model doesnt has a Records Activity Trait yet
         */
        $this->assertEquals(2, Activity::count());
    }

    /** @test */
    function it_fetches_a_feed_for_any_user(): void
    {
        /** Given we have a thread */
        $this->signIn();

        create(Thread::class, 2, [
            'user_id' => auth()->id(),
        ]);

        /** Update thread to a week ago */
        auth()->user()->activity()->first()->update([
            'created_at' => Carbon::now()->subWeek(),
        ]);

        /** When we fetch their feed */
        $feed = Activity::feed(auth()->user());

        /** Then, it should be returned in the proper format */
        $this->assertTrue($feed->keys()->contains(
            Carbon::now()->format('Y-m-d')
        ));

        $this->assertTrue($feed->keys()->contains(
            Carbon::now()->subWeek()->format('Y-m-d')
        ));
    }
}
