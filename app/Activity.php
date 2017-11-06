<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Class Activity
 * @package App
 */
class Activity extends Model
{
    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @return MorphTo
     */
    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * @param User $user
     * @param int|null $take
     * @return mixed
     */
    public static function feed(User $user, int $take = null)
    {
        return static::where('user_id', $user->id)
            ->latest()
            ->with('subject')
            ->take($take ?? getItemsPerPage('profiles'))
            ->get()
            ->groupBy(function ($activity) {
                return $activity->created_at->format('Y-m-d');
            });
    }
}
