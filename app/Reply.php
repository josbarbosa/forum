<?php namespace App;

use App\Http\Traits\Favoritable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Reply
 * @package App
 */
class Reply extends Model
{
    use Favoritable;

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
     * @return BelongsTo
     * When the method name is not expected by laravel
     * We need to specify the foreignKey column manually
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
