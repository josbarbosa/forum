<?php namespace App;

use App\Notifications\ThreadWasUpdated;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class ThreadSubscription
 * @package App
 */
class ThreadSubscription extends Model
{
    /**
     * @var array
     */
    protected $guarded = ['id', 'user_id', 'thread_id'];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function thread(): BelongsTo
    {
        return $this->belongsTo(Thread::class);
    }

    /**
     * @param $reply
     */
    public function notify($reply): void
    {
        $this->user->notify(new ThreadWasUpdated($this->thread, $reply));
    }
}
