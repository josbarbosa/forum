<?php namespace App\Http\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Trait RecordsActivity
 * @package App\Http\Traits
 */
trait RecordsActivity
{
    /**
     * When we use "bootTraitName", this method is called automatically by laravel
     * Has the same effect as the boot method inside the model
     */
    protected static function bootRecordsActivity(): void
    {
        if (auth()->guest()) {
            return;
        }

        foreach (static::getActivitiesToRecord() as $event) {
            static::$event(function ($model) use ($event) {
                $model->recordActivity($event);
            });
        }

        static::deleting(function ($model) {
            $model->activity()->delete();
        });

    }

    /**
     * @param string $event
     */
    protected function recordActivity(string $event): void
    {
        $this->activity()->create([
            'user_id' => auth()->id(),
            'type'    => $this->getActivityType($event),
        ]);
    }

    /**
     * @return array
     */
    protected static function getActivitiesToRecord(): array
    {
        return ['created'];
    }

    /**
     * @return MorphMany
     */
    public function activity(): MorphMany
    {
        return $this->morphMany('App\Activity', 'subject');
    }

    /**
     * @param string $event
     * @return string
     */
    protected function getActivityType(string $event): string
    {
        $type = strtolower((new \ReflectionClass($this))->getShortName());
        return "{$event}_{$type}";
    }
}
