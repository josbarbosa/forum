<?php namespace App;

use App\Traits\Favoritable;
use App\Traits\RecordsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Reply
 * @package App
 */
class Reply extends Model
{
    use Favoritable, RecordsActivity;

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * Eager load owner relationship
     * @var array
     */
    protected $with = ['owner', 'favorites'];

    /**
     * Add attributes to the eloquent model query
     * @var array
     */
    protected $appends = ['favoritesCount', 'isFavorited'];

    /**
     * Override boot method
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function (self $reply) {
            $reply->thread->increment('replies_count');
        });

        static::deleted(function (self $reply) {
            $reply->thread->decrement('replies_count');
        });
    }

    /**
     * @return BelongsTo
     * When the method name is not expected by laravel
     * We need to specify the foreignKey column manually
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return BelongsTo
     */
    public function thread(): BelongsTo
    {
        return $this->belongsTo(Thread::class);
    }

    /**
     * @return string
     */
    public function path(): string
    {
        return "{$this->thread->path()}#reply-{$this->id}";
    }
}
