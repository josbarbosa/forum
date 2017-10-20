<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reply extends Model
{
    protected $guarded = [];
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
