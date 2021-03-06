<?php namespace App;

use App\Traits\RecordsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Class Favorite
 * @package App
 */
class Favorite extends Model
{
    use RecordsActivity;

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * Fetch the model that was favorited.
     * @return MorphTo
     */
    public function favorited(): MorphTo
    {
        return $this->morphTo();
    }
}
