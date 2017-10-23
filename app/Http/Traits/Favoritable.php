<?php namespace App\Http\Traits;

use App\Favorite;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Trait Favoritable
 * @package App\Http\Traits
 */
trait Favoritable
{

    /**
     * @return MorphMany
     */
    public function favorites(): MorphMany
    {
        return $this->morphMany(Favorite::class, 'favorited');
    }

    /**
     * @return Model
     */
    public function favorite(): Model
    {
        $attributes = ['user_id' => auth()->id()];
        if (!$this->favorites()->where($attributes)->exists()) {
            return $this->favorites()->create($attributes);
        }
    }

    /**
     * @return bool
     */
    public function isFavorited(): bool
    {
        return !!$this->favorites->where('user_id', auth()->id())->count();
    }

    /**
     * @return int
     */
    public function getFavoritesCountAttribute(): int
    {
        return $this->favorites->count();
    }
}
