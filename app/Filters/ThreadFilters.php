<?php namespace App\Filters;

use App\User;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class ThreadFilters
 * @package App\Filters
 */
class ThreadFilters extends Filters
{
    /**
     * @var array
     */
    protected $filters = ['by', 'popular'];

    /**
     * Filter the query by a given username
     *
     * @param string $username
     * @return Builder
     */
    protected function by(string $username): Builder
    {
        $user = User::where('name', $username)->firstOrFail();

        return $this->builder->where('user_id', $user->id);
    }


    /**
     * Filter the query according to most popular threads
     * @return Builder
     */
    protected function popular(): Builder
    {
        return $this->builder->orderBy('replies_count', 'desc');
    }
}
