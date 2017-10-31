<?php namespace App\Listeners;

use App\Events\ThreadHasNewReply;

/**
 * Class NotifyThreadSubscribers
 * @package App\Listeners
 */
class NotifyThreadSubscribers
{
    /**
     * Handle the event.
     *
     * @param  ThreadHasNewReply  $event
     * @return void
     */
    public function handle(ThreadHasNewReply $event): void
    {
        $event->thread->subscriptions
            ->where('user_id', '!=', $event->reply->user_id)
            ->each
            ->notify($event->reply);
    }
}
