<?php namespace App;

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
     * @return HasMany
     */
    public function replies(): HasMany
    {
        return $this->hasMany(Reply::class);
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
     * @param Reply $reply
     */
    public function addReply(Reply $reply): void
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
}
