<?php namespace App;

use App\Filters\ThreadFilters;
use App\Http\Traits\RecordsActivity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\App;
use PhpParser\ErrorHandler\Throwing;

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
     * Executed every time we call Thread Model
     */
    protected static function boot(): void
    {
        parent::boot();

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
        return $this->replies()->create($reply);
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
