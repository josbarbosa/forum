<?php namespace App\Http\Controllers;

use App\Channel;
use App\Thread;

/**
 * Class ThreadSubscriptionsController
 * @package App\Http\Controllers
 */
class ThreadSubscriptionsController extends Controller
{
    /**
     * @param Channel $channel
     * @param Thread $thread
     */
    public function store(Channel $channel, Thread $thread): void
    {
        $thread->subscribe();
    }

    /**
     * @param Channel $channel
     * @param Thread $thread
     */
    public function destroy(Channel $channel, Thread $thread): void
    {
        $thread->unsubscribe();
    }
}
