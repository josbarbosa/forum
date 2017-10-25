<?php namespace App\Http\Traits;

use App\Favorite;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Trait Favoritable
 * @package App\Http\Traits
 */
trait Favoritable
{

    protected static function bootFavoritable()
    {
        static::deleting(function ($model) {
            $model->favorites->each->delete();
        });
    }

    /**
     * @return MorphMany
     */
    public function favorites(): MorphMany
    {
        return $this->morphMany(Favorite::class, 'favorited');
    }

    /**
     * @return mixed
     */
    public function favorite()
    {
        $attributes = ['user_id' => auth()->id()];
        if (!$this->favorites()->where($attributes)->exists()) {
            return $this->favorites()->create($attributes);
        }
    }

    /**
     *
     */
    public function unfavorite(): void
    {
        $this->favorites()
            ->where(['user_id' => auth()->id()])
            ->get()
            ->each
            ->delete();
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

    /**
     * @return bool
     */
    public function getIsFavoritedAttribute(): bool
    {
        return $this->isFavorited();
    }
}
