<?php namespace App;

use App\Events\ThreadHasNewReply;
use App\Filters\ThreadFilters;
use App\Http\Traits\RecordsActivity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Thread
 * @package App
 */
class Thread extends Model
{
    use RecordsActivity;
    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * Eager load relationships
     * @var array
     */
    protected $with = ['creator', 'channel'];

    /**
     * @var array
     */
    protected $appends = ['isSubscribedTo'];

    /**
     * Executed every time we call Thread Model
     */
    protected static function boot(): void
    {
        parent::boot();

        /** deleting thread event */
        static::deleting(function ($thread) {
            $thread->replies->each->delete();
        });
    }

    /**
     * @return HasMany
     */
    public function replies(): HasMany
    {
        return $this
            ->hasMany(Reply::class);
    }

    /**
     * @return BelongsTo
     * Laravel by default will look for a creator id
     * We need to specify the foreign key column
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @param array $reply
     * @return Reply
     */
    public function addReply(array $reply): Reply
    {
        $reply = $this->replies()->create($reply);

        /** Prepare notifications for all subscribers */
        event(new ThreadHasNewReply($this, $reply));

        return $reply;
    }

    /**
     * @return BelongsTo
     */
    public function channel(): BelongsTo
    {
        return $this->belongsTo(Channel::class);
    }

    /**
     * @return string
     */
    public function path(): string
    {
        return "/threads/{$this->channel->slug}/{$this->id}";
    }

    /**
     * @param Builder $query
     * @param ThreadFilters $filters
     * @return Builder
     */
    public function scopefilter(Builder $query, ThreadFilters $filters): Builder
    {
        return $filters->apply($query);
    }

    /**
     * @param int|null $userId
     * @return ThreadSubscription
     */
    public function subscribe(int $userId = null): ThreadSubscription
    {
        $threadSubscription = new ThreadSubscription();
        $threadSubscription->user_id = $userId ?? Auth()->id();
        $threadSubscription->thread_id = $this->id;
        $threadSubscription->save();

        return $threadSubscription;
    }

    /**
     * @param $userId
     * @return bool
     */
    public function unsubscribe(int $userId = null): bool
    {
        return $this->subscriptions()
            ->where('user_id', $userId ?: auth()->id())
            ->delete();
    }

    /**
     * @return HasMany
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(ThreadSubscription::class);
    }

    /**
     * @return bool
     */
    public function getIsSubscribedToAttribute(): bool
    {
        return $this->subscriptions()
            ->where('user_id', auth()->id())
            ->exists();
    }
}
