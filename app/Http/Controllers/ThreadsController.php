<?php namespace App\Http\Controllers;

use App\Channel;
use App\Filters\ThreadFilters;
use App\Thread;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Class ThreadsController
 * @package App\Http\Controllers
 */
class ThreadsController extends Controller
{
    /**
     * ThreadsController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Channel $channel
     * @param ThreadFilters $filters
     * @return \Illuminate\Contracts\View\Factory|Collection|View
     */
    public function index(Channel $channel, ThreadFilters $filters)
    {
        $threads = $this->getThreads($channel, $filters);

        if (request()->wantsJson()) {
            return $threads;
        }

        return view('threads.index', compact('threads'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create(): View
    {
        return view('threads.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'title'      => 'required',
            'body'       => 'required',
            'channel_id' => 'required|exists:channels,id',
        ]);

        $thread = Thread::create([
            'user_id'    => auth()->id(),
            'channel_id' => request('channel_id'),
            'title'      => request('title'),
            'body'       => request('body'),
        ]);

        return redirect($thread->path())
            ->with('flash', 'Your thread has been published');
    }

    /**
     * Display the specified resource.
     *
     * @param string $channel
     * @param  \App\Thread $thread
     * @return \Illuminate\View\View
     */
    public function show(string $channel, Thread $thread): View
    {
        return view('threads.show', compact('thread'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Thread $thread
     * @return \Illuminate\Http\Response
     */
    public function edit(Thread $thread)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Thread $thread
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Thread $thread)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Channel $channel
     * @param  \App\Thread $thread
     * @return \Illuminate\Http\Response
     */
    public function destroy(Channel $channel, Thread $thread)
    {
        $this->authorize('update', $thread);

        \DB::transaction(function () use ($thread) {
            $thread->delete();
        });

        if (request()->wantsJson()) {
            return response([], 204);
        } else {
            return redirect('/threads');
        }
    }

    /**
     * @param Channel $channel
     * @param ThreadFilters $filters
     */
    protected function getThreads(Channel $channel, ThreadFilters $filters)
    {
        $threads = Thread::filter($filters)->latest();

        if ($channel->exists) {
            $threads->where('channel_id', $channel->id);
        }

        return $threads->paginate(getItemsPerPage('threads'));
    }
}
