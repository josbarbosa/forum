<?php namespace App;

use App\Filters\ThreadFilters;
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
     * Add replies count to the global scope
     */
    protected static function boot(): void
    {
        parent::boot();

        static::addGlobalScope('replyCount', function ($builder) {
            $builder->withCount('replies');
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
     * @return int
     */
    public function getReplyCountAttribute(): int
    {
        return $this->replies()->count();
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
     */
    public function addReply(array $reply): void
    {
        $this->replies()->create($reply);
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
}
