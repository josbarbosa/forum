<?php namespace App\Http\Controllers;

use App\Channel;
use App\Reply;
use App\Thread;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Class RepliesController
 * @package App\Http\Controllers
 */
class RepliesController extends Controller
{
    /**
     * RepliesController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    /**
     * @param Channel $channel
     * @param Thread $thread
     * @return LengthAwarePaginator
     */
    public function index(Channel $channel, Thread $thread): LengthAwarePaginator
    {
        return $thread->replies()->paginate(getItemsPerPage('replies'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param string $channel
     * @param \App\Thread $thread
     * @return Reply|RedirectResponse
     */
    public function store(string $channel, Thread $thread)
    {
        $this->validate(request(), [
            'body' => 'required',
        ]);

        $reply = $thread->addReply([
            'body'    => request('body'),
            'user_id' => auth()->id(),
        ]);

        if (request()->expectsJson()) {
            return $reply->load('owner');
        }

        return back()->with('flash', 'Your reply has been left.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Reply $reply
     * @return \Illuminate\Http\Response
     */
    public function update(Reply $reply)
    {
        $this->authorize('update', $reply);

        $reply->update(request(['body']));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Reply $reply
     * @return \Illuminate\Http\Response
     */
    public function destroy(Reply $reply)
    {
        $this->authorize('update', $reply);

        \DB::transaction(function () use ($reply) {
            $reply->delete();
        });

        if (request()->expectsJson()) {
            return response(['status' => 'Reply deleted'], 200);
        }

        return back();
    }
}
