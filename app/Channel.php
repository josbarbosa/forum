<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

/**
 * Class Channel
 * @package App
 */
class Channel extends Model
{
    protected static function boot(): void
    {
        foreach (static::getCacheClearableEvents() as $event) {
            static::$event(function (Model $model) {
                $model->clearCache();
            });
        }
    }

    /**
     * @return array
     */
    protected static function getCacheClearableEvents(): array
    {
        return ['created', 'updated', 'deleted'];
    }

    protected function clearCache(): void
    {
        Cache::forget('channels');
    }


    /**
     * @return string
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * @return HasMany
     */
    public function threads(): HasMany
    {
        return $this->hasMany(Thread::class);
    }
}
