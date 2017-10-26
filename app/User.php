<?php namespace App;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class User
 * @package App
 */
class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'email',
    ];

    /**
     * @return string
     */
    public function getRouteKeyName(): string
    {
        /** change to username later */
        return 'name';
    }

    /**
     * @return HasMany
     */
    public function threads(): HasMany
    {
        return $this->hasMany(Thread::class)->latest();
    }

    /**
     * @return HasMany
     */
    public function activity(): HasMany
    {
        return $this->hasMany(Activity::class);
    }
}
